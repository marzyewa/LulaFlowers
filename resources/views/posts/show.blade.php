<x-app-layout>
    <x-slot name="title">{{ $post->title }}</x-slot>

    <article>
        <header class="bg-[#edf1e9]">
            <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-2 lg:px-8">
                <div>
                    <a href="{{ route('posts.index', ['category' => $post->category_id]) }}" class="category-badge">{{ $post->category->name }}</a>
                    <h1 class="mt-6 font-display text-4xl leading-tight text-[#23362d] sm:text-5xl">{{ $post->title }}</h1>
                    <div class="mt-6 flex flex-wrap gap-x-5 gap-y-2 text-sm text-stone-600">
                        <span>{{ $post->user->name }}</span>
                        <span>{{ $post->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
                <img src="{{ asset($post->image ?: 'images/flowers/placeholder.jpg') }}" alt="{{ $post->title }}" class="aspect-[16/10] w-full rounded-[2rem] object-cover shadow-xl">
            </div>
        </header>

        <div class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="article-content whitespace-pre-line">{{ $post->content }}</div>

            <div class="mt-12 flex flex-wrap items-center justify-between gap-4 border-t border-stone-200 pt-8">
                <a href="{{ route('posts.index') }}" class="button-secondary">← Все статьи</a>
                @if (auth()->check() && auth()->id() === $post->user_id)
                    <div class="flex gap-3">
                        <a href="{{ route('posts.edit', $post) }}" class="button-secondary">Редактировать</a>
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Удалить эту статью?')">
                            @csrf
                            @method('DELETE')
                            <button class="button-danger">Удалить</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </article>
</x-app-layout>
