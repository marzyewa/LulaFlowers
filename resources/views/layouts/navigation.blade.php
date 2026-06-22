<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-stone-200/80 bg-[#fbfaf7]/95 backdrop-blur">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-full bg-[#dce8dd] text-xl">✿</span>
            <span class="font-display text-2xl font-bold text-[#294435]">Lula Flowers</span>
        </a>

        <div class="hidden items-center gap-8 md:flex">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Главная</a>
            <a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.*') ? 'nav-link-active' : '' }}">Статьи</a>

            @auth
                <a href="{{ route('posts.create') }}" class="button-primary">Новая статья</a>
                <div class="relative" x-data="{ profile: false }">
                    <button @click="profile = !profile" class="flex items-center gap-2 text-sm font-semibold text-stone-700">
                        {{ Auth::user()->name }} <span>⌄</span>
                    </button>
                    <div x-cloak x-show="profile" @click.outside="profile = false" class="absolute right-0 mt-3 w-44 rounded-xl border border-stone-200 bg-white p-2 shadow-xl">
                        <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-stone-100">Профиль</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-stone-100">Выйти</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-link">Войти</a>
                <a href="{{ route('register') }}" class="button-primary">Регистрация</a>
            @endauth
        </div>

        <button @click="open = !open" class="rounded-lg p-2 text-2xl md:hidden" aria-label="Открыть меню">☰</button>
    </div>

    <div x-cloak x-show="open" class="border-t border-stone-200 bg-white px-4 py-4 md:hidden">
        <div class="flex flex-col gap-3">
            <a href="{{ route('home') }}" class="nav-link">Главная</a>
            <a href="{{ route('posts.index') }}" class="nav-link">Статьи</a>
            @auth
                <a href="{{ route('posts.create') }}" class="nav-link">Новая статья</a>
                <a href="{{ route('profile.edit') }}" class="nav-link">Профиль</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-link">Выйти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Войти</a>
                <a href="{{ route('register') }}" class="nav-link">Регистрация</a>
            @endauth
        </div>
    </div>
</nav>
