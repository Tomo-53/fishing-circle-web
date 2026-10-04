<!DOCTYPE html>
{{--
  トップページ専用スタンドアロン Blade（レイアウトコンポーネント未使用）。
  時間帯テーマ: html[data-theme] + CSS変数。JS は resources/js/welcome.js（app.js 経由）。
  詳細は .claude/epics/toppage-immersive-redesign/_epic.md
  セクション markup は welcome/ 配下の partial、スタイルは resources/css/welcome.css。
  _head / _header / _theme-toggle は下層ページの <x-layout.public> でも共用している。
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
    @include('welcome._head')
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
