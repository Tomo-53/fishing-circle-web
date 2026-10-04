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
                {{-- 目は頭側（左）、エラは目の後方（尾側）に配置 --}}
                <circle cx="25" cy="34" r="5" fill="rgba(0,25,55,0.8)"/>
                <circle cx="23.5" cy="32.5" r="2" fill="rgba(255,255,255,0.9)"/>
                <path d="M40,24 Q50,40 40,56" fill="none" stroke="rgba(0,25,55,0.35)" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M46,29 Q53,40 46,51" fill="none" stroke="rgba(0,25,55,0.2)" stroke-width="1" stroke-linecap="round"/>
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
            <p class="text-xs tracking-[0.45em] welcome-text-muted mb-4 uppercase font-sans">Niigata University Fishing Circle</p>

            <h1 class="font-bold leading-none"
                style="font-family: 'Comfortaa', 'Noto Sans JP', sans-serif; font-size: clamp(3rem, 10vw, 7.5rem); letter-spacing: -0.03em; color: var(--text-bright); text-shadow: 0 2px 24px rgba(0,0,0,0.45)">
                新潟大学<br>釣り同好会
            </h1>
        </div>

        {{-- PC(md+) は 1 行、スマホは「サークル」と「仲間と」の間で改行（区切りの — は md+ のみ） --}}
        <p class="welcome-text-muted text-base md:text-lg tracking-wider font-sans mb-10 leading-relaxed md:whitespace-nowrap">
            <span class="inline-block">新大唯一の釣りサークル</span><span class="hidden md:inline"> — </span><br class="md:hidden"><span class="inline-block">仲間と自然と、深く潜ろう。</span>
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('join') }}"
               class="welcome-cta cta-ripple relative overflow-hidden inline-flex items-center justify-center min-h-[48px] px-10 py-4 rounded-full border-2 border-transparent font-semibold text-base tracking-wider shadow-xl focus:outline-none focus:ring-2 focus:ring-white/50"
               onclick="addRipple(event)"
               aria-label="入部案内ページへ">
                入部案内 →
            </a>
            <a href="{{ route('about') }}"
               class="welcome-cta-secondary inline-flex items-center justify-center min-h-[48px] px-10 py-4 rounded-full font-medium text-base tracking-wider shadow-lg transition-colors focus:outline-none focus:ring-2 focus:ring-white/50">
                サークル紹介
            </a>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="relative z-10 flex justify-center pb-8" aria-hidden="true">
        <div class="flex flex-col items-center gap-2 welcome-text-subtle">
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
