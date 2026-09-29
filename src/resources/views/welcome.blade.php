<!DOCTYPE html>
{{--
  トップページ専用スタンドアロン Blade（レイアウトコンポーネント未使用）。
  時間帯テーマ: html[data-theme] + CSS変数。JS は resources/js/welcome.js（app.js 経由）。
  詳細は .claude/epics/toppage-immersive-redesign/_epic.md
  セクション markup は welcome/ 配下の partial、スタイルは resources/css/welcome.css。
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="day">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', '新潟大学釣り同好会') }}</title>
    <meta name="description" content="新潟大学唯一の釣りサークル。釣り技術の向上・仲間との交流・大会出場。初心者大歓迎。入部案内・活動内容はこちら。">
    <meta property="og:title" content="{{ config('app.name', '新潟大学釣り同好会') }}">
    <meta property="og:description" content="新潟大学唯一の釣りサークル。初心者から上級者まで、仲間と自然と深く潜ろう。">
    <meta property="og:type" content="website">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    {{-- FOUC防止: テーマ適用を最初に行う --}}
    <script>
    (function(){
        var h = new Date().getHours();
        var t = (h>=5&&h<10)?'dawn':(h>=10&&h<17)?'day':'night';
        document.documentElement.dataset.theme = t;
    })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&family=Comfortaa:wght@400;600;700&display=swap" rel="stylesheet">

    @php
        $welcomeViteInputs = ['resources/css/app.css', 'resources/js/app.js'];
        $welcomeCssViaVite = false;
        if (file_exists(public_path('hot'))) {
            $welcomeViteInputs[] = 'resources/css/welcome.css';
            $welcomeCssViaVite = true;
        } elseif (file_exists(public_path('build/manifest.json'))) {
            $welcomeManifest = json_decode((string) file_get_contents(public_path('build/manifest.json')), true) ?? [];
            if (isset($welcomeManifest['resources/css/welcome.css'])) {
                $welcomeViteInputs[] = 'resources/css/welcome.css';
                $welcomeCssViaVite = true;
            }
        }
    @endphp
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite($welcomeViteInputs)
    @endif
    {{-- Vite に welcome.css が無いとき（未 build）はソースをそのまま読む。build 後は @vite 側のみ --}}
    @unless ($welcomeCssViaVite)
        <style>{!! file_get_contents(resource_path('css/welcome.css')) !!}</style>
    @endunless
</head>
<body class="bg-gray-900 text-white overflow-x-hidden">

    <div id="main-content">

        @include('welcome._header')

        <main>
            @include('welcome._hero')
            @include('welcome._about')
            @include('welcome._activities')
            @include('welcome._join-cta')
        </main>

        {{-- ─────────────── I. Footer ─────────────── --}}
        @include('components.layout.footer')

    </div>{{-- /main-content --}}

    @include('welcome._theme-toggle')

</body>
</html>
