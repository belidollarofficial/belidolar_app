<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Masuk' }} - {{ config('app.name', 'Beli Dolar') }}</title>
    <link rel="icon" href="{{ \App\Support\Branding::faviconUrl() }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-10"
         style="background: radial-gradient(circle at 20% 15%, #1e293b 0%, #0f1724 55%);">
        <div class="w-full max-w-md">

            <div class="flex flex-col items-center mb-6">
                <a href="{{ route('beranda') }}" wire:navigate class="flex items-center gap-3 no-underline">
                    <x-brand-mark class="w-12 h-12 rounded-2xl" />
                    <span class="text-2xl font-extrabold">
                        <span class="text-[#818cf8]">Beli</span> <span class="text-[#22d3ee]">Dolar</span>
                    </span>
                </a>
                <p class="text-sm text-gray-400 mt-3">Panel Admin</p>
            </div>

            <div class="bg-[#1e293b] border border-[#334155] rounded-2xl shadow-2xl p-6 sm:p-8">
                {{ $slot }}
            </div>

            <p class="text-center text-xs text-gray-500 mt-6">
                &copy; {{ date('Y') }} Beli Dolar. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>