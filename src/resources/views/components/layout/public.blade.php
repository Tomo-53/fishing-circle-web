@props([
    'title',
    'description' => null,
])
<!DOCTYPE html>
{{--
  公開下層ページ（サークル紹介・活動内容・ギャラリー・入部案内）の共通レイアウト。
  トップ（welcome.blade.php）と同じ <head>（welcome/_head）・ヘッダー（welcome/_header）・フッターを使い、
  時間帯テーマ（html[data-theme]）と濃紺トーンをトップと揃える。

  使い方:
    <x-layout.public title="サークル紹介" description="…">
        <x-layout.page-hero label="About Us" title="サークル紹介" lead="…" />
        …本文…
    </x-layout.public>

  body.welcome-subpage でヘッダーを最初から背景付き表示にする（welcome.css）。
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="day">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name', '新潟大学釣り同好会') }}</title>
    @if ($description)
        <meta name="description" content="{{ $description }}">
        <meta property="og:description" content="{{ $description }}">
    @endif
    <meta property="og:title" content="{{ $title }} - {{ config('app.name', '新潟大学釣り同好会') }}">
    <meta property="og:type" content="website">
    @include('welcome._head')
</head>
<body class="welcome-subpage bg-gray-900 text-white overflow-x-hidden">

    <a href="#page-main"
       class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:px-4 focus:py-2 focus:rounded-full focus:bg-white focus:text-gray-900 focus:text-sm focus:font-semibold">
        本文へスキップ
    </a>

    <div id="main-content" class="flex min-h-screen flex-col">

        @include('welcome._header')

        {{-- 固定ヘッダー（h-16）の高さ分だけ本文を下げる --}}
        <main id="page-main" class="flex-1 pt-16" tabindex="-1">
            {{ $slot }}
        </main>

        @include('components.layout.footer')

    </div>{{-- /main-content --}}

    @include('welcome._theme-toggle')

</body>
</html>
