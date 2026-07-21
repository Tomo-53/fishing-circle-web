<!DOCTYPE html>
{{--
  トップページ専用スタンドアロン Blade（レイアウトコンポーネント未使用）。
  時間帯テーマ: html[data-theme] + CSS変数。JS は resources/js/welcome.js（app.js 経由）。
  オープニング → #main-content の順。詳細は .claude/epics/toppage-immersive-redesign/_epic.md
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

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        {{-- 朝/昼/夜で --sky-* / --wave-* が変わる。色は hero-*.jpg のトーンに同期 --}}
        /* ─── Time-based CSS variables（写真に合わせたパレット） ─── */
        :root, html[data-theme="day"] {
            --sky-from: #4a5d72;
            --sky-mid:  #2c3a4a;
            --sky-to:   #1a2430;
            --sky-deep: #0d1218;
            --wave-back: rgba(74, 93, 114, 0.4);
            --wave-mid:  rgba(44, 58, 74, 0.6);
            --wave-front: rgba(13, 18, 24, 0.88);
            --celestial-color: #f5c07a;
            --celestial-glow: rgba(245, 192, 122, 0.35);
            --accent-warm: #c2410c;
            --text-bright: rgba(255,255,255,0.95);
        }
        html[data-theme="dawn"] {
            --sky-from: #3d5a80;
            --sky-mid:  #c4782e;
            --sky-to:   #e8a030;
            --sky-deep: #1a1208;
            --wave-back: rgba(232, 160, 48, 0.3);
            --wave-mid:  rgba(196, 120, 46, 0.5);
            --wave-front: rgba(26, 18, 8, 0.88);
            --celestial-color: #ffe08a;
            --celestial-glow: rgba(255, 224, 138, 0.55);
            --accent-warm: #b45309;
            --text-bright: rgba(255,255,250,0.95);
        }
        html[data-theme="night"] {
            --sky-from: #0a1e38;
            --sky-mid:  #061428;
            --sky-to:   #020810;
            --sky-deep: #010508;
            --wave-back: rgba(10, 30, 56, 0.5);
            --wave-mid:  rgba(6, 20, 40, 0.7);
            --wave-front: rgba(1, 5, 8, 0.9);
            --celestial-color: #f0e6c8;
            --celestial-glow: rgba(240, 230, 200, 0.3);
            --accent-warm: #b45309;
            --text-bright: rgba(220,240,255,0.95);
        }

        /* x-cloak for Alpine */
        [x-cloak] { display: none !important; }

        /* ─── Opening animation（テーマ連動・砂浜 → 波せり上がり） ─── */
        #opening {
            background: linear-gradient(
                180deg,
                var(--sky-from) 0%,
                var(--sky-to) 55%,
                var(--sky-mid) 85%,
                var(--sky-deep) 100%
            );
        }
        @keyframes openingExit {
            0%   { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(-100%); opacity: 0; }
        }
        #opening.closing {
            animation: openingExit 0.7s cubic-bezier(0.76, 0, 0.24, 1) forwards;
        }
        #opening.closing .opening-wave-rise,
        #opening.closing .opening-wave-sway,
        #opening.closing .opening-foam {
            animation: none !important;
        }
        /* dawn: 明るい空上でもロゴ可読性を確保 */
        html[data-theme="dawn"] #opening-text {
            text-shadow: 0 2px 20px rgba(26, 18, 8, 0.55), 0 1px 4px rgba(0, 0, 0, 0.4);
        }
        html[data-theme="dawn"] #skip-btn {
            background: rgba(26, 18, 8, 0.45);
            border-color: rgba(255, 255, 255, 0.4);
            color: rgba(255, 255, 250, 0.95);
        }
        /* 親: 縦せり上がり / 子: 横うねり（transform 分離で GPU 合成） */
        @keyframes waveRise {
            0%   { transform: translateY(100%); }
            100% { transform: translateY(-15vh); }
        }
        @keyframes waveSway {
            0%, 100% { transform: translateX(-4%); }
            50%      { transform: translateX(4%); }
        }
        @keyframes foamRecede {
            0%   { transform: translateX(0); opacity: 0; }
            30%  { opacity: 0.7; }
            70%  { transform: translateX(-8%); opacity: 0.5; }
            100% { transform: translateX(-16%); opacity: 0; }
        }
        .opening-wave-rise {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 115vh;
            transform: translateY(100%);
            will-change: transform;
        }
        #opening.is-playing .opening-wave-rise--back {
            animation: waveRise 2.4s cubic-bezier(0.42, 0, 0.18, 1) 0.3s forwards;
        }
        #opening.is-playing .opening-wave-rise--front {
            animation: waveRise 2.4s cubic-bezier(0.42, 0, 0.18, 1) 0.45s forwards;
        }
        .opening-wave-sway {
            width: 200%;
            height: 100%;
            will-change: transform;
        }
        #opening.is-playing .opening-wave-sway {
            animation: waveSway 3s ease-in-out infinite;
        }
        #opening.is-playing .opening-wave-sway--front {
            animation-delay: -0.8s;
            animation-duration: 3.4s;
        }
        .opening-wave-svg {
            display: block;
            width: 100%;
            height: clamp(180px, 28vh, 320px);
        }
        .opening-wave-fill {
            flex: 1;
            width: 100%;
            min-height: calc(115vh - clamp(180px, 28vh, 320px));
        }
        .opening-foam {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 28%;
            height: 24px;
            opacity: 0;
            pointer-events: none;
            z-index: 2;
        }
        #opening.is-playing .opening-foam {
            animation: foamRecede 0.4s ease-in-out forwards;
        }
        #opening-text {
            opacity: 0;
            color: var(--text-bright);
            transition: opacity 0.5s ease-out;
        }
        #opening-text.is-visible { opacity: 1; }

        /* Bubble particles */
        .bubble {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
            animation: bubbleRise linear infinite;
        }
        @keyframes bubbleRise {
            0%   { transform: translateY(0) scale(1); opacity: 0.5; }
            100% { transform: translateY(-120vh) scale(0.4); opacity: 0; }
        }

        /* ─── Reveal animation ─── */
        .reveal {
            opacity: 0; transform: translateY(1.75rem);
            transition: opacity 0.65s ease-out, transform 0.65s ease-out;
        }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.15s; }
        .reveal-delay-2 { transition-delay: 0.30s; }
        .reveal-delay-3 { transition-delay: 0.45s; }

        /* ─── Header scroll（テーマ深色に合わせる） ─── */
        #site-header { background: transparent; transition: background 0.3s ease, box-shadow 0.3s ease; }
        #site-header.scrolled { background: rgba(13,18,24,0.93); backdrop-filter: blur(10px); box-shadow: 0 2px 24px rgba(0,0,0,0.35); }
        html[data-theme="dawn"] #site-header.scrolled { background: rgba(26,18,8,0.93); }
        html[data-theme="night"] #site-header.scrolled { background: rgba(1,5,8,0.95); }

        {{-- 3枚の img を常に DOM に置き、data-theme 変更時は opacity のみ切替（src 差替えなし） --}}
        /* ─── Hero theme photos ─── */
        .hero-photo {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.45s ease;
            pointer-events: none;
        }
        .hero-photo-dawn { object-position: center 58%; }
        .hero-photo-day  { object-position: center 42%; }
        .hero-photo-night { object-position: center 45%; }
        @media (max-width: 768px) {
            .hero-photo-day { object-position: center 62%; }
            .hero-photo-dawn { object-position: center 64%; }
        }
        html[data-theme="dawn"]  .hero-photo-dawn  { opacity: 1; }
        html[data-theme="day"]   .hero-photo-day   { opacity: 1; }
        html[data-theme="night"] .hero-photo-night { opacity: 1; }

        /* Theme-specific overlays（中央コピー帯のコントラスト確保） */
        .hero-overlay-base,
        .hero-overlay-tint { position: absolute; inset: 0; pointer-events: none; }
        .hero-overlay-base {
            background: linear-gradient(180deg, rgba(0,0,0,0.12) 0%, rgba(0,0,0,0.42) 52%, var(--sky-deep) 95%);
        }
        html[data-theme="dawn"] .hero-overlay-base {
            background: linear-gradient(180deg, rgba(0,0,0,0.28) 0%, rgba(30,14,4,0.62) 48%, var(--sky-deep) 92%);
        }
        html[data-theme="day"] .hero-overlay-base {
            background: linear-gradient(180deg, rgba(0,0,0,0.18) 0%, rgba(8,12,18,0.48) 52%, var(--sky-deep) 95%);
        }
        html[data-theme="night"] .hero-overlay-base {
            background: linear-gradient(180deg, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.4) 50%, var(--sky-deep) 95%);
        }
        .hero-overlay-tint {
            background: linear-gradient(180deg, var(--sky-from) 0%, transparent 30%);
            opacity: 0.45;
        }
        html[data-theme="dawn"] .hero-overlay-tint { opacity: 0.35; }
        html[data-theme="night"] .hero-overlay-tint { opacity: 0.55; }

        /* 開発用テーマ切替ボタン: 全テーマで操作可能（装飾時の dawn 非表示は廃止） */
        html[data-theme="dawn"] #hero-celestial { opacity: 0.9; }
        html[data-theme="day"] #hero-celestial { opacity: 0.75; }
        html[data-theme="night"] #hero-celestial { opacity: 1; }

        /* ─── CTA ripple ─── */
        .cta-ripple { transition: transform 0.2s ease, opacity 0.2s ease; }
        .cta-ripple:hover { transform: scale(1.04); filter: brightness(1.08); }
        .ripple-ring {
            position: absolute; border-radius: 50%; background: rgba(255,255,255,0.35);
            width: 60px; height: 60px; margin: -30px;
            animation: rippleEffect 0.85s ease-out forwards; pointer-events: none;
        }
        @keyframes rippleEffect {
            from { transform: scale(0); opacity: 0.5; }
            to   { transform: scale(4); opacity: 0; }
        }

        /* ─── Fish: JS parallax 用（初期は静止） ─── */
        .hero-fish { will-change: transform; }

        /* ─── Dev celestial theme toggle ─── */
        #hero-celestial {
            border: none;
            padding: 0;
            background: transparent;
            cursor: pointer;
            transition: opacity 0.45s ease, transform 0.2s ease;
        }
        #hero-celestial:hover { transform: scale(1.08); }
        #hero-celestial:focus-visible {
            outline: 2px solid rgba(255,255,255,0.7);
            outline-offset: 6px;
            border-radius: 50%;
        }
        #hero-celestial .celestial-orb {
            width: 52px; height: 52px;
            background: var(--celestial-color);
            border-radius: 50%;
            box-shadow: 0 0 0 14px var(--celestial-glow), 0 0 70px var(--celestial-glow);
            pointer-events: none;
        }

        /* ─── Texture depth background ─── */
        .texture-depth-bg {
            background:
                radial-gradient(ellipse at 15% 85%, rgba(255,255,255,0.03) 0%, transparent 50%),
                radial-gradient(ellipse at 85% 15%, rgba(255,255,255,0.015) 0%, transparent 40%),
                linear-gradient(to bottom, var(--sky-deep) 0%, #111827 100%);
        }
        .texture-dot {
            position: absolute; border-radius: 50%; background: rgba(255,255,255,0.07);
        }

        /* ─── Photo hover ─── */
        .photo-card { overflow: hidden; position: relative; }
        .photo-card img { transition: transform 0.5s ease; }
        .photo-card:hover img { transform: scale(1.06); }
        .photo-card .photo-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 55%);
            opacity: 0; transition: opacity 0.4s ease;
        }
        .photo-card:hover .photo-overlay { opacity: 1; }

        /* ─── reduced-motion ─── */
        @media (prefers-reduced-motion: reduce) {
            .bubble { animation: none; }
            #opening { display: none !important; }
            #main-content { opacity: 1 !important; }
            .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
            .opening-wave-rise,
            .opening-wave-sway,
            .opening-foam { animation: none !important; }
            .hero-fish { transform: none !important; }
            .animate-bounce { animation: none !important; }
            .nav-link::after { transition: none; }
            .photo-card img { transition: none; }
            .photo-card .photo-overlay { transition: none; }
            .hero-photo, #hero-celestial { transition: none; }
        }

        /* ─── Nav link underline ─── */
        .nav-link {
            position: relative; padding-bottom: 2px;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0;
            height: 1px; background: white; transform: scaleX(0);
            transition: transform 0.2s ease; transform-origin: right;
        }
        .nav-link:hover::after { transform: scaleX(1); transform-origin: left; }

        /* ─── main-content hidden until opening completes ─── */
        #main-content { opacity: 0; transition: opacity 0.4s ease; }
    </style>
</head>
<body class="bg-gray-900 text-white overflow-x-hidden">

    {{-- ─────────────── Opening Screen（テーマ連動・砂浜 → 波せり上がり） ─────────────── --}}
    <div id="opening"
         class="fixed inset-0 z-50 flex flex-col items-center justify-center overflow-hidden select-none"
         role="dialog"
         aria-modal="true"
         aria-label="オープニング"
         aria-hidden="true">

        <button type="button" id="skip-btn"
                class="absolute top-5 right-5 z-30 text-sm text-white/70 hover:text-white transition-colors tracking-wider px-4 py-1 rounded-full border border-white/25 hover:border-white/50 focus:outline-none focus:ring-2 focus:ring-white/40"
                aria-label="オープニングをスキップ">
            SKIP ×
        </button>

        {{-- foam: 打ち寄せ→引き（せり上がり前） --}}
        <div class="opening-foam" aria-hidden="true">
            <svg viewBox="0 0 1440 24" preserveAspectRatio="none" style="width:100%;height:100%;display:block">
                <path d="M0,12 C180,22 360,2 540,12 C720,22 900,4 1080,12 C1260,20 1380,8 1440,12"
                      fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="2"/>
            </svg>
        </div>

        {{-- 波レイヤー1（奥）: --wave-back、やや先行 --}}
        <div class="opening-wave-rise opening-wave-rise--back" style="z-index:3" aria-hidden="true">
            <div class="opening-wave-sway flex flex-col h-full">
                <svg class="opening-wave-svg" viewBox="0 0 2880 320" preserveAspectRatio="none">
                    {{-- Get Waves 系パスを2倍タイル（ユーザー提供パスベース） --}}
                    <path fill="var(--wave-back)" fill-opacity="1"
                          d="M0,32L48,26.7C96,21,192,11,288,16C384,21,480,43,576,96C672,149,768,235,864,250.7C960,267,1056,213,1152,192C1248,171,1344,181,1392,186.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
                    <path fill="var(--wave-back)" fill-opacity="1" transform="translate(1440,0)"
                          d="M0,32L48,26.7C96,21,192,11,288,16C384,21,480,43,576,96C672,149,768,235,864,250.7C960,267,1056,213,1152,192C1248,171,1344,181,1392,186.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
                </svg>
                <div class="opening-wave-fill" style="background: var(--wave-back)"></div>
            </div>
        </div>

        {{-- 波レイヤー2（手前）: --wave-front、Y オフセットで立体感 --}}
        <div class="opening-wave-rise opening-wave-rise--front" style="z-index:4" aria-hidden="true">
            <div class="opening-wave-sway opening-wave-sway--front flex flex-col h-full">
                <svg class="opening-wave-svg" viewBox="0 0 2880 320" preserveAspectRatio="none">
                    <path fill="var(--wave-front)" fill-opacity="1"
                          d="M0,40L48,34.7C96,29,192,19,288,24C384,29,480,51,576,104C672,157,768,243,864,258.7C960,275,1056,221,1152,200C1248,179,1344,189,1392,194.7L1440,200L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
                    <path fill="var(--wave-front)" fill-opacity="1" transform="translate(1440,0)"
                          d="M0,40L48,34.7C96,29,192,19,288,24C384,29,480,51,576,104C672,157,768,243,864,258.7C960,275,1056,221,1152,200C1248,179,1344,189,1392,194.7L1440,200L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
                </svg>
                <div class="opening-wave-fill" style="background: var(--wave-front)"></div>
            </div>
        </div>

        {{-- テキストロゴ（手前波が中央通過時にフェードイン） --}}
        <div id="opening-text" class="relative z-20 text-center px-4">
            <p class="text-xs tracking-[0.5em] mb-3 uppercase font-sans" style="opacity:0.75">Niigata University</p>
            <h2 class="font-bold leading-none" style="font-family: 'Comfortaa', 'Noto Sans JP', sans-serif; font-size: clamp(2.5rem, 8vw, 5rem)">
                新潟大学<br>釣り同好会
            </h2>
            <div class="mt-5 flex items-center justify-center gap-3">
                <div class="h-px w-12 bg-current opacity-30"></div>
                <p class="text-xs tracking-[0.3em] uppercase" style="opacity:0.8">Dive Into Fishing</p>
                <div class="h-px w-12 bg-current opacity-30"></div>
            </div>
        </div>
    </div>
    {{-- /Opening --}}

    {{-- オープニング中は welcome.js が inert + opacity:0。終了後に操作可能になる --}}
    <div id="main-content">

        {{-- ─────────────── Fixed Header ─────────────── --}}
        <header id="site-header" class="fixed top-0 left-0 right-0 z-40"
                x-data="{ mobileOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">

                    {{-- Logo --}}
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3 flex-shrink-0">
                        <img src="{{ asset('images/logo.png') }}" alt="釣り同好会ロゴ" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
                        <span class="font-bold text-white text-sm tracking-wider" style="font-family: 'Comfortaa', sans-serif;">
                            新大釣り同好会
                        </span>
                    </a>

                    {{-- Desktop Nav --}}
                    <nav class="hidden md:flex items-center gap-8" aria-label="メインナビゲーション">
                        <a href="{{ route('about') }}" class="nav-link text-white/75 hover:text-white text-sm tracking-wider transition-colors rounded-sm focus:outline-none focus:ring-2 focus:ring-white/60">サークル紹介</a>
                        <a href="{{ route('activities') }}" class="nav-link text-white/75 hover:text-white text-sm tracking-wider transition-colors rounded-sm focus:outline-none focus:ring-2 focus:ring-white/60">活動内容</a>
                        <a href="{{ route('gallery') }}" class="nav-link text-white/75 hover:text-white text-sm tracking-wider transition-colors rounded-sm focus:outline-none focus:ring-2 focus:ring-white/60">ギャラリー</a>
                        <a href="{{ route('join') }}" class="nav-link text-white/75 hover:text-white text-sm tracking-wider transition-colors rounded-sm focus:outline-none focus:ring-2 focus:ring-white/60">入部案内</a>
                    </nav>

                    {{-- Right: SNS + Auth + Hamburger --}}
                    <div class="flex items-center gap-4">
                        {{-- SNS icons --}}
                        <div class="hidden md:flex items-center gap-3">
                            <a href="https://instagram.com/new_river_run" target="_blank" rel="noopener noreferrer"
                               class="text-white/55 hover:text-white transition-colors" aria-label="Instagram">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="https://x.com/new_river_runs" target="_blank" rel="noopener noreferrer"
                               class="text-white/55 hover:text-white transition-colors" aria-label="X (Twitter)">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                        </div>

                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="hidden md:inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium text-white border border-white/30 hover:bg-white/10 transition-colors">
                                ダッシュボード
                            </a>
                        @else
                            <div class="hidden md:flex items-center gap-2">
                                <a href="{{ route('login') }}"
                                   class="px-4 py-1.5 rounded-full text-sm text-white/75 hover:text-white border border-white/25 hover:border-white/50 transition-colors">
                                    ログイン
                                </a>
                                <a href="{{ route('register') }}"
                                   class="px-4 py-1.5 rounded-full text-sm font-semibold text-white transition-colors"
                                   style="background: var(--accent-warm)">
                                    新規登録
                                </a>
                            </div>
                        @endauth

                        {{-- Hamburger --}}
                        <button @click="mobileOpen = !mobileOpen"
                                class="md:hidden text-white/75 hover:text-white p-1 focus:outline-none focus:ring-2 focus:ring-white/40 rounded"
                                :aria-expanded="mobileOpen.toString()"
                                :aria-label="mobileOpen ? 'メニューを閉じる' : 'メニューを開く'"
                                aria-controls="mobile-nav">
                            <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Mobile Menu --}}
                <div x-show="mobileOpen" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     id="mobile-nav"
                     class="md:hidden pb-4 border-t border-white/10 mt-2 pt-4"
                     style="background: rgba(0,0,0,0.6); backdrop-filter: blur(12px);"
                     role="navigation" aria-label="モバイルナビゲーション">
                    <nav class="flex flex-col gap-1">
                        <a href="{{ route('about') }}" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg text-sm tracking-wide transition-colors">サークル紹介</a>
                        <a href="{{ route('activities') }}" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg text-sm tracking-wide transition-colors">活動内容</a>
                        <a href="{{ route('gallery') }}" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg text-sm tracking-wide transition-colors">ギャラリー</a>
                        <a href="{{ route('join') }}" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg text-sm tracking-wide transition-colors">入部案内</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="mt-2 mx-4 py-2.5 text-center rounded-full text-sm font-semibold text-white" style="background: var(--accent-warm)">ダッシュボード</a>
                        @else
                            <div class="flex gap-2 mt-2 px-4">
                                <a href="{{ route('login') }}" class="flex-1 py-2 text-center rounded-full text-sm text-white/80 border border-white/30">ログイン</a>
                                <a href="{{ route('register') }}" class="flex-1 py-2 text-center rounded-full text-sm font-semibold text-white" style="background: var(--accent-warm)">新規登録</a>
                            </div>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>
        {{-- /Header --}}


        <main>

            {{-- ─────────────── C. Hero Section ─────────────── --}}
            <section class="relative min-h-screen flex flex-col overflow-hidden"
                     style="background: linear-gradient(180deg, var(--sky-from) 0%, var(--sky-mid) 45%, var(--sky-deep) 100%)">

                {{-- テーマ別ヒーロー写真（パララックス） --}}
                <div id="hero-bg" class="absolute inset-0" style="will-change: transform">
                    <img src="{{ asset('images/hero-dawn.jpg') }}" alt=""
                         class="hero-photo hero-photo-dawn"
                         loading="eager" decoding="async" aria-hidden="true">
                    <img src="{{ asset('images/hero-day.jpg') }}" alt=""
                         class="hero-photo hero-photo-day"
                         loading="eager" decoding="async" aria-hidden="true">
                    <img src="{{ asset('images/hero-night.jpg') }}" alt=""
                         class="hero-photo hero-photo-night"
                         loading="eager" decoding="async" aria-hidden="true">
                    <div class="hero-overlay-base"></div>
                    <div class="hero-overlay-tint"></div>
                    <script>
                    (function(){
                        var t = document.documentElement.dataset.theme || 'day';
                        var el = document.querySelector('.hero-photo-' + t);
                        if (el) { el.setAttribute('fetchpriority', 'high'); }
                    })();
                    </script>
                </div>

                {{-- Bubble particles --}}
                <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
                    <div class="bubble" style="left:7%;bottom:4%;width:38px;height:38px;animation-duration:9s;animation-delay:0s"></div>
                    <div class="bubble" style="left:20%;bottom:9%;width:26px;height:26px;animation-duration:11s;animation-delay:-2s"></div>
                    <div class="bubble" style="left:68%;bottom:3%;width:44px;height:44px;animation-duration:8s;animation-delay:-1.5s"></div>
                    <div class="bubble" style="left:84%;bottom:13%;width:30px;height:30px;animation-duration:10s;animation-delay:-4s"></div>
                    <div class="bubble" style="left:42%;bottom:28%;width:18px;height:18px;animation-duration:13s;animation-delay:-3s;opacity:0.08"></div>
                    <div class="bubble" style="left:62%;bottom:35%;width:14px;height:14px;animation-duration:15s;animation-delay:-6s;opacity:0.08"></div>
                    <div class="bubble" style="left:28%;bottom:55%;width:7px;height:7px;animation-duration:17s;animation-delay:-9s;opacity:0.05"></div>
                    <div class="bubble" style="left:77%;bottom:62%;width:5px;height:5px;animation-duration:20s;animation-delay:-12s;opacity:0.04"></div>
                </div>

                {{-- Celestial: 開発用テーマ切替（本番非表示） --}}
                @if (config('app.env') !== 'production')
                <button type="button" id="hero-celestial"
                        class="absolute z-[3]"
                        style="top:13%; right:10%"
                        title="時間帯プレビュー（開発用）"
                        aria-label="時間帯プレビュー（開発用）。クリックで次のテーマへ">
                    <span class="celestial-orb" aria-hidden="true"></span>
                </button>
                @endif

                {{-- Fish silhouettes（スクロール連動 parallax） --}}
                <div class="absolute inset-0 z-[5] pointer-events-none overflow-hidden" aria-hidden="true">
                    <div class="hero-fish"
                         data-speed-x="0.03"
                         data-speed-y="-0.08"
                         data-bob-amp="8"
                         data-bob-period="4000"
                         style="position:absolute;bottom:36%;right:5%">
                        <svg width="160" height="80" viewBox="0 0 160 80" fill="white" opacity="0.13">
                            <ellipse cx="62" cy="40" rx="58" ry="27"/>
                            <path d="M118,40 Q138,20 160,5 Q160,75 140,60 Q148,40 118,40 Z" opacity="0.8"/>
                            <circle cx="106" cy="33" r="5" fill="rgba(0,25,55,0.8)"/>
                            <circle cx="106" cy="33" r="2" fill="rgba(255,255,255,0.9)"/>
                            <line x1="18" y1="28" x2="32" y2="24" stroke="rgba(255,255,255,0.35)" stroke-width="1.5"/>
                            <line x1="18" y1="40" x2="35" y2="40" stroke="rgba(255,255,255,0.25)" stroke-width="1"/>
                            <line x1="18" y1="52" x2="32" y2="56" stroke="rgba(255,255,255,0.35)" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div class="hero-fish"
                         data-speed-x="-0.02"
                         data-speed-y="-0.15"
                         data-bob-amp="6"
                         data-bob-period="5500"
                         style="position:absolute;top:26%;left:7%">
                        <svg width="55" height="28" viewBox="0 0 55 28" fill="white" opacity="0.06">
                            <ellipse cx="22" cy="14" rx="20" ry="9"/>
                            <path d="M40,14 Q48,7 55,2 Q55,26 48,20 Q52,14 40,14 Z"/>
                        </svg>
                    </div>
                </div>

                {{-- Headline (z=10, above fish) --}}
                <div class="relative z-10 flex-1 flex flex-col items-center justify-center text-center px-4 pt-24 pb-16">

                    <div class="relative inline-block mb-6">
                        <p class="text-xs tracking-[0.45em] text-white/75 mb-4 uppercase font-sans">Niigata University Fishing Circle</p>

                        <h1 class="font-bold leading-none"
                            style="font-family: 'Comfortaa', 'Noto Sans JP', sans-serif; font-size: clamp(3rem, 10vw, 7.5rem); letter-spacing: -0.03em; color: var(--text-bright); text-shadow: 0 2px 24px rgba(0,0,0,0.45)">
                            新潟大学<br>釣り同好会
                        </h1>
                    </div>

                    <p class="text-white/80 text-base md:text-lg tracking-wider font-sans mb-10 max-w-sm leading-relaxed">
                        新大唯一の釣りサークル — 仲間と自然と、深く潜ろう。
                    </p>

                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <a href="{{ route('join') }}"
                           class="cta-ripple relative overflow-hidden px-8 py-3 rounded-full font-semibold text-white text-sm tracking-wider shadow-xl focus:outline-none focus:ring-2 focus:ring-white/50"
                           style="background: var(--accent-warm)"
                           onclick="addRipple(event)"
                           aria-label="入部案内ページへ">
                            入部案内 →
                        </a>
                        <a href="{{ route('about') }}"
                           class="px-8 py-3 rounded-full font-medium text-white/90 hover:text-white text-sm tracking-wider border border-white/45 hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/40">
                            サークル紹介
                        </a>
                    </div>
                </div>

                {{-- Scroll indicator --}}
                <div class="relative z-10 flex justify-center pb-8" aria-hidden="true">
                    <div class="flex flex-col items-center gap-2 text-white/35">
                        <span class="text-xs tracking-widest uppercase font-sans">Scroll</span>
                        <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Wave transition to About section --}}
                <div class="absolute bottom-0 left-0 right-0 pointer-events-none z-[6]" aria-hidden="true">
                    <svg viewBox="0 0 1440 55" preserveAspectRatio="none" style="width:100%;height:55px;display:block;fill:#111827">
                        <path d="M0,28 C240,55 480,0 720,28 C960,55 1200,0 1440,28 L1440,55 L0,55 Z"/>
                    </svg>
                </div>
            </section>
            {{-- /Hero --}}


            {{-- ─────────────── D. About Section ─────────────── --}}
            <section class="relative py-24 overflow-hidden" style="background: #111827">

                {{-- Large fish silhouette behind heading (occlusion depth) --}}
                <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="z-index:1">
                    <svg style="position:absolute;top:5%;left:-5%;width:55%;opacity:0.03" viewBox="0 0 400 200" fill="white">
                        <ellipse cx="155" cy="100" rx="145" ry="65"/>
                        <path d="M290,100 Q345,55 400,20 Q400,180 355,145 Q375,100 290,100 Z"/>
                        <circle cx="265" cy="82" r="12" fill="rgba(0,10,30,0.8)"/>
                        <circle cx="265" cy="82" r="5" fill="white"/>
                    </svg>
                </div>

                <div class="relative z-[2] max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{-- Section header --}}
                    <div class="text-center mb-16 reveal">
                        <p class="text-xs tracking-[0.5em] text-sky-400/70 uppercase mb-3">About Us</p>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4"
                            style="font-family: 'Comfortaa', 'Noto Sans JP', sans-serif">
                            新大釣りサークルについて
                        </h2>
                        <div class="mx-auto h-px w-16 bg-sky-500/40"></div>
                    </div>

                    {{-- 3-column info cards with stagger reveal --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

                        {{-- Card 1: 目指すもの --}}
                        <div class="reveal reveal-delay-1 relative bg-white/5 border border-white/10 rounded-2xl p-8 overflow-hidden hover:bg-white/8 transition-colors">
                            <div class="absolute bottom-4 right-4 text-7xl font-black text-sky-400/8 leading-none select-none" aria-hidden="true">01</div>
                            <div class="relative z-10">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center mb-5">
                                    <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-3">目指しているもの</h3>
                                <p class="text-white/60 text-sm leading-relaxed">
                                    釣り技術の向上・学生間の交流・釣り文化や自然環境の理解と普及。みんなで楽しく釣りができることを目指しています！
                                </p>
                            </div>
                        </div>

                        {{-- Card 2: 雰囲気 --}}
                        <div class="reveal reveal-delay-2 relative bg-white/5 border border-white/10 rounded-2xl p-8 overflow-hidden hover:bg-white/8 transition-colors">
                            <div class="absolute bottom-4 right-4 text-7xl font-black text-sky-400/8 leading-none select-none" aria-hidden="true">02</div>
                            <div class="relative z-10">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center mb-5">
                                    <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-3">雰囲気は？</h3>
                                <p class="text-white/60 text-sm leading-relaxed">
                                    初心者もベテランもワイワイと。宅飲みや食事会（釣れた魚料理！）、佐渡・粟島などへの遠征も。苦難と喜びを共にした仲間は最高の絆に。
                                </p>
                            </div>
                        </div>

                        {{-- Card 3: 大会実績 --}}
                        <div class="reveal reveal-delay-3 relative bg-white/5 border border-white/10 rounded-2xl p-8 overflow-hidden hover:bg-white/8 transition-colors">
                            <div class="absolute bottom-4 right-4 text-7xl font-black text-sky-400/8 leading-none select-none" aria-hidden="true">03</div>
                            <div class="relative z-10">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center mb-5">
                                    <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-3">大会実績</h3>
                                <ul class="text-white/60 text-sm leading-relaxed space-y-1.5">
                                    <li class="flex items-start gap-2">
                                        <span class="text-sky-400 mt-0.5 flex-shrink-0">—</span>
                                        第5回 佐渡ビックゲーム FishRankerカップ 出場（2024）
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-sky-400 mt-0.5 flex-shrink-0">—</span>
                                        第17回 GSBC 第6位・第8位・ベストフォト賞（2024）
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- About images --}}
                    <div class="grid grid-cols-2 gap-4 mt-12 reveal">
                        <div class="rounded-2xl overflow-hidden aspect-video">
                            <img src="{{ asset('images/about1.jpg') }}" alt="活動風景1" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="rounded-2xl overflow-hidden aspect-video">
                            <img src="{{ asset('images/about2.jpg') }}" alt="活動風景2" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                </div>

                {{-- Wave to activities section --}}
                <div class="absolute bottom-0 left-0 right-0 pointer-events-none z-[3]" aria-hidden="true">
                    <svg viewBox="0 0 1440 48" preserveAspectRatio="none" style="width:100%;height:48px;display:block;fill:#0c4a6e">
                        <path d="M0,24 C360,48 720,0 1080,24 C1260,36 1380,12 1440,24 L1440,48 L0,48 Z" fill="#0c4a6e"/>
                    </svg>
                </div>
            </section>
            {{-- /About --}}


            {{-- ─────────────── E. Activity Photos Section ─────────────── --}}
            <section class="relative py-24 overflow-hidden texture-depth-bg">

                {{-- Texture dots (size gradient = depth cue) --}}
                <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
                    <div class="texture-dot" style="width:160px;height:160px;bottom:5%;left:2%;opacity:0.04"></div>
                    <div class="texture-dot" style="width:110px;height:110px;bottom:15%;right:3%;opacity:0.04"></div>
                    <div class="texture-dot" style="width:70px;height:70px;top:20%;left:8%;opacity:0.025"></div>
                    <div class="texture-dot" style="width:45px;height:45px;top:35%;right:12%;opacity:0.02"></div>
                    <div class="texture-dot" style="width:25px;height:25px;top:55%;left:25%;opacity:0.015"></div>
                    <div class="texture-dot" style="width:15px;height:15px;top:65%;right:35%;opacity:0.01"></div>
                </div>

                <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="text-center mb-14 reveal">
                        <p class="text-xs tracking-[0.5em] text-sky-400/70 uppercase mb-3">Activities</p>
                        <h2 class="text-3xl md:text-4xl font-bold text-white"
                            style="font-family: 'Comfortaa', 'Noto Sans JP', sans-serif">
                            活動フォト
                        </h2>
                    </div>

                    {{-- 2×2 photo grid --}}
                    <div class="grid grid-cols-2 gap-3 md:gap-4">
                        @foreach(['active1', 'active2', 'active3', 'active4'] as $i => $img)
                        <div class="photo-card reveal reveal-delay-{{ $i < 2 ? $i + 1 : $i - 1 }} rounded-xl aspect-video cursor-pointer">
                            <img src="{{ asset('images/' . $img . '.jpg') }}"
                                 alt="活動写真{{ $i + 1 }}"
                                 class="w-full h-full object-cover">
                            <div class="photo-overlay"></div>
                        </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-10 reveal">
                        <a href="{{ route('gallery') }}"
                           class="inline-flex items-center gap-2 px-8 py-3 rounded-full text-sm font-medium text-white/80 hover:text-white border border-white/25 hover:border-white/50 hover:bg-white/8 transition-colors">
                            ギャラリーをもっと見る
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
            {{-- /Activities --}}


            {{-- ─────────────── F. Join CTA Section ─────────────── --}}
            <section class="relative py-28 overflow-hidden">

                {{-- Full bleed background --}}
                <div class="absolute inset-0">
                    <img src="{{ asset('images/join1.jpg') }}" alt="" class="w-full h-full object-cover" aria-hidden="true">
                    <div class="absolute inset-0" style="background: rgba(1,8,18,0.82)"></div>
                    <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(12,74,110,0.5) 0%, rgba(1,8,18,0.4) 100%)"></div>
                </div>

                <div class="relative z-10 max-w-2xl mx-auto px-4 text-center">
                    <div class="reveal">
                        <p class="text-xs tracking-[0.5em] text-sky-400/75 uppercase mb-4">Join Us</p>
                        <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight"
                            style="font-family: 'Comfortaa', 'Noto Sans JP', sans-serif">
                            釣りを、もっと<br>深く楽しもう。
                        </h2>
                        <p class="text-white/60 mb-10 leading-relaxed max-w-md mx-auto">
                            新大生なら誰でも入部歓迎。経験不問、竿なし道具なしでも大丈夫。まず一緒に海へ出かけましょう。
                        </p>
                        <div class="flex flex-wrap justify-center gap-4">
                            <a href="{{ route('join') }}"
                               class="cta-ripple relative overflow-hidden px-10 py-4 rounded-full font-bold text-white text-base tracking-wider shadow-2xl focus:outline-none focus:ring-2 focus:ring-white/50"
                               style="background: var(--accent-warm)"
                               onclick="addRipple(event)"
                               aria-label="入部案内ページへ">
                                入部案内を見る →
                            </a>
                            <a href="https://instagram.com/new_river_run" target="_blank" rel="noopener noreferrer"
                               class="px-8 py-4 rounded-full font-medium text-white/75 hover:text-white text-sm tracking-wider border border-white/30 hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/40 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                Instagram をフォロー
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            {{-- /CTA --}}

        </main>

        {{-- ─────────────── I. Footer ─────────────── --}}
        @include('components.layout.footer')

    </div>{{-- /main-content --}}


    {{-- ─────────────── H. Theme Toggle（開発用・本番非表示） ─────────────── --}}
    @if (config('app.env') !== 'production')
    <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-2" role="group" aria-label="時間帯テーマ切替（開発用）">
        <button type="button" onclick="setTheme('dawn')"
                class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-sm border border-white/20 text-base hover:border-white/50 hover:bg-black/60 transition-colors focus:outline-none focus:ring-2 focus:ring-white/40"
                data-theme-btn="dawn" aria-pressed="false"
                title="朝テーマ" aria-label="朝のテーマに切替">
            🌅
        </button>
        <button type="button" onclick="setTheme('day')"
                class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-sm border border-white/20 text-base hover:border-white/50 hover:bg-black/60 transition-colors focus:outline-none focus:ring-2 focus:ring-white/40"
                data-theme-btn="day" aria-pressed="false"
                title="昼テーマ" aria-label="昼のテーマに切替">
            ☀️
        </button>
        <button type="button" onclick="setTheme('night')"
                class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-sm border border-white/20 text-base hover:border-white/50 hover:bg-black/60 transition-colors focus:outline-none focus:ring-2 focus:ring-white/40"
                data-theme-btn="night" aria-pressed="false"
                title="夜テーマ" aria-label="夜のテーマに切替">
            🌙
        </button>
    </div>
    @endif

    {{-- Vite/welcome.js が読めない場合の保険。通常は welcome.js が __welcomeInited を立てる --}}
    <script>
    (function () {
        function forceShow() {
            if (window.__welcomeInited) return;
            var opening = document.getElementById('opening');
            if (opening) opening.style.display = 'none';
            var main = document.getElementById('main-content');
            if (main) {
                main.style.opacity = '1';
                main.removeAttribute('inert');
                main.removeAttribute('aria-hidden');
            }
        }
        window.addEventListener('load', forceShow);
        setTimeout(forceShow, 4000);
    })();
    </script>

</body>
</html>
