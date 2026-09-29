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
            <div class="photo-card welcome-photo-frame welcome-photo-frame--4x3 reveal reveal-delay-{{ $i < 2 ? $i + 1 : $i - 1 }} rounded-xl cursor-pointer">
                <img src="{{ asset('images/' . $img . '.jpg') }}"
                     alt="活動写真{{ $i + 1 }}"
                     class="welcome-photo-pos-{{ $img }}">
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
