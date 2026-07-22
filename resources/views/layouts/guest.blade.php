<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cream px-4">
            <div class="w-full sm:max-w-md animate-fade-in-up">
                <div class="flex flex-col items-center text-center">
                    <a href="/">
                        <x-application-logo class="text-3xl" />
                    </a>
                    <p class="font-sans text-sm font-medium text-ink/70 mt-2">Sistem Distribusi Produk</p>
                    <p class="font-sans text-xs text-ink/40 mt-0.5">Prediksi Produk Terlaris Menggunakan Algoritma C4.5</p>
                </div>

                <div class="mt-5 px-6 py-6 bg-surface border border-denim/10 overflow-hidden rounded-xl shadow-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
