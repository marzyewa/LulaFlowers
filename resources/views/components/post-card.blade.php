@props(['post'])

<article class="card group overflow-hidden">
    <a href="{{ route('posts.show', $post) }}" class="block aspect-[16/10] overflow-hidden bg-[#dce8dd]">
        <img
            src="{{ asset($post->image ?: 'images/flowers/placeholder.jpg') }}"
            alt="{{ $post->title }}"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
        >
    </a>
    <div class="p-6">
        <a href="{{ route('posts.index', ['category' => $post->category_id]) }}" class="category-badge">
            {{ $post->category->name }}
        </a>
        <h2 class="mt-4 font-display text-2xl leading-tight text-[#23362d]">
            <a href="{{ route('posts.show', $post) }}" class="hover:text-[#a45f68]">{{ $post->title }}</a>
        </h2>
        <p class="mt-3 line-clamp-3 text-sm leading-6 text-stone-600">
            {{ \Illuminate\Support\Str::limit($post->content, 145) }}
        </p>
        <div class="mt-5 flex items-center justify-between text-xs text-stone-500">
            <span>{{ $post->created_at->format('d.m.Y') }}</span>
            <a href="{{ route('posts.show', $post) }}" class="font-semibold text-[#7c4d55]">Читать →</a>
        </div>
    </div>
</article>
