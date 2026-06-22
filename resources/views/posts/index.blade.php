<x-app-layout>
    <x-slot name="title">Статьи</x-slot>
    <x-slot name="header">
        <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="eyebrow">Библиотека Lula Flowers</p>
                <h1 class="section-title">Статьи о цветах</h1>
                <p class="mt-3 max-w-2xl text-stone-600">Уход за растениями, идеи букетов, сезонные подборки и основы флористики.</p>
            </div>
            @auth
                <a href="{{ route('posts.create') }}" class="button-primary">Новая статья</a>
            @endauth
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('posts.index') }}" class="card grid gap-4 p-5 md:grid-cols-[1fr_240px_180px_auto]">
            <input name="q" type="search" value="{{ request('q') }}" placeholder="Поиск по статьям..." class="form-control">
            <select name="category" class="form-control">
                <option value="">Все категории</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="sort" class="form-control">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Сначала новые</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Сначала старые</option>
            </select>
            <button class="button-primary">Найти</button>
        </form>

        @if (request()->hasAny(['q', 'category', 'sort']))
            <div class="mt-4 text-right">
                <a href="{{ route('posts.index') }}" class="text-sm font-semibold text-[#7c4d55]">Сбросить фильтры</a>
            </div>
        @endif

        <div class="mt-10 grid gap-7 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <x-post-card :post="$post" />
            @empty
                <div class="card col-span-full p-10 text-center">
                    <p class="font-display text-2xl text-[#23362d]">Ничего не найдено</p>
                    <p class="mt-2 text-stone-600">Попробуйте изменить запрос или выбрать другую категорию.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">{{ $posts->links() }}</div>
    </div>
</x-app-layout>
