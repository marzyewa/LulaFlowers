<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title.' — ' : '' }}Lula Flowers</title>
        <meta name="description" content="Lula Flowers — статьи о цветах, комнатных растениях, букетах и флористике.">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700|playfair-display:600,700" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-stone-800 antialiased">
        <div class="flex min-h-screen flex-col bg-[#fbfaf7]">
            @include('layouts.navigation')

            @if (session('success'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 3500)"
                    x-show="show"
                    x-transition
                    class="fixed right-4 top-24 z-50 max-w-sm rounded-2xl border border-emerald-200 bg-white px-5 py-4 text-sm text-emerald-800 shadow-xl"
                >
                    {{ session('success') }}
                </div>
            @endif

            @isset($header)
                <header class="border-b border-stone-200 bg-white/80">
                    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1">
                {{ $slot }}
            </main>

            <footer class="mt-16 border-t border-stone-200 bg-[#23362d] text-stone-200">
                <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-2 lg:px-8">
                    <div>
                        <div class="font-display text-2xl text-white">Lula Flowers</div>
                        <p class="mt-3 max-w-md text-sm leading-6 text-stone-300">
                            Спокойное место для тех, кто любит цветы, растения, букеты и красивую флористику.
                        </p>
                    </div>
                    <div class="md:text-right">
                        <a href="{{ route('home') }}" class="mr-5 hover:text-white">Главная</a>
                        <a href="{{ route('posts.index') }}" class="hover:text-white">Все статьи</a>
                        <p class="mt-4 text-xs text-stone-400">© {{ date('Y') }} Lula Flowers</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
