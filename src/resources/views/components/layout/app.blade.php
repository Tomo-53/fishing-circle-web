<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', '新潟大学釣り同好会') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $description ?? '新潟大学釣り同好会の公式ウェブサイト。活動内容、メンバー募集、イベント情報などを掲載しています。' }}">
    <meta name="keywords" content="釣り, サークル, フィッシング, 新潟大学, 新潟, 新潟大学釣り同好会, circle {{ $keywords ?? '' }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $description ?? '新潟大学釣り同好会の公式ウェブサイト。活動内容、メンバー募集、イベント情報などを掲載しています。' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Additional Head Content -->
    {{ $head ?? '' }}
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-screen bg-white">
        <!-- Header -->
        @isset($header)
            <header class="bg-white shadow-sm border-b border-gray-100">
                {{ $header }}
            </header>
        @endisset

        <!-- Navigation -->
        @isset($navigation)
            <nav class="bg-ocean-500 text-white">
                {{ $navigation }}
            </nav>
        @endisset

        <!-- Main Content -->
        <main class="min-h-screen">
            {{ $slot }}
        </main>

        <!-- Footer -->
        @isset($footer)
            <footer class="bg-gray-900 text-white">
                {{ $footer }}
            </footer>
        @else
            <x-layout.footer />
        @endisset
    </div>

    <!-- Scripts -->
    {{ $scripts ?? '' }}

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
