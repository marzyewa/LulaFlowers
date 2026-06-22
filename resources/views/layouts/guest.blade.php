<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Lula Flowers') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700|playfair-display:600,700" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-stone-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-8 bg-[#edf1e9]">
            <div>
                <a href="/" class="flex items-center gap-3 font-display text-3xl font-bold text-[#294435]">
                    <span class="grid h-12 w-12 place-items-center rounded-full bg-white text-2xl shadow-sm">✿</span>
                    Lula Flowers
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-7 py-7 bg-white shadow-xl overflow-hidden rounded-2xl border border-stone-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
