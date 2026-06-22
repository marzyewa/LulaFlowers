<x-app-layout>
    <section class="relative overflow-hidden bg-[#edf1e9]">
        <div class="mx-auto grid min-h-[620px] max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div class="relative z-10">
                <span class="category-badge">Цветы • растения • вдохновение</span>
                <h1 class="mt-6 max-w-2xl font-display text-5xl leading-[1.08] text-[#23362d] sm:text-6xl">
                    Всё самое важное о цветах — с любовью и без суеты
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-stone-600">
                    Практические статьи об уходе за растениями, сезонных цветах, букетах и современной флористике.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('posts.index') }}" class="button-primary">Смотреть статьи</a>
                    @auth
                        <a href="{{ route('posts.create') }}" class="button-secondary">Написать статью</a>
                    @endauth
                </div>
            </div>
            <div class="relative">
                <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-[#e9cdd0]/60 blur-3xl"></div>
                <img src="{{ asset('images/flowers/hero.jpg') }}" alt="Цветы и комнатные растения" class="relative aspect-[4/5] w-full rounded-[2.5rem] object-cover shadow-2xl">
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="eyebrow">Свежие публикации</p>
                <h2 class="section-title">Новые статьи</h2>
            </div>
            <a href="{{ route('posts.index') }}" class="font-semibold text-[#7c4d55]">Все статьи →</a>
        </div>
        <div class="mt-10 grid gap-7 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($latestPosts as $post)
                <x-post-card :post="$post" />
            @empty
                <p class="text-stone-600">Статьи скоро появятся.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-[#f2e8e6]">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <p class="eyebrow">Выберите направление</p>
            <h2 class="section-title">Темы Lula Flowers</h2>
            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($categories as $category)
                    <a href="{{ route('posts.index', ['category' => $category->id]) }}" class="rounded-2xl border border-white/60 bg-white/65 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-lg">
                        <span class="text-2xl">✿</span>
                        <h3 class="mt-4 font-display text-xl text-[#23362d]">{{ $category->name }}</h3>
                        <p class="mt-2 text-xs text-stone-500">{{ $category->posts_count }} статей</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
