<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'sort' => ['nullable', 'in:newest,oldest'],
        ]);

        $posts = Post::with(['category', 'user'])
            ->when($validated['q'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($validated['category'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->orderBy('created_at', ($validated['sort'] ?? 'newest') === 'oldest' ? 'asc' : 'desc')
            ->paginate(9)
            ->withQueryString();

        return view('posts.index', [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('posts.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePost($request);
        $validated['user_id'] = $request->user()->id;
        $validated['slug'] = $this->makeUniqueSlug($validated['title']);
        $validated['image'] = $this->storeImage($request);

        $post = Post::create($validated);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Статья успешно опубликована.');
    }

    public function show(Post $post): View
    {
        $post->load(['category', 'user']);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        $this->authorizeOwner($post);

        return view('posts.edit', [
            'post' => $post,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->authorizeOwner($post);

        $validated = $this->validatePost($request);
        $validated['slug'] = $this->makeUniqueSlug($validated['title'], $post->id);

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($post->image);
            $validated['image'] = $this->storeImage($request);
        }

        $post->update($validated);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Статья успешно обновлена.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorizeOwner($post);
        $this->deleteUploadedImage($post->image);
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Статья удалена.');
    }

    private function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return 'storage/'.$request->file('image')->store('posts', 'public');
    }

    private function deleteUploadedImage(?string $path): void
    {
        if ($path && Str::startsWith($path, 'storage/')) {
            Storage::disk('public')->delete(Str::after($path, 'storage/'));
        }
    }

    private function authorizeOwner(Post $post): void
    {
        abort_unless(auth()->id() === $post->user_id, 403);
    }

    private function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (Post::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
