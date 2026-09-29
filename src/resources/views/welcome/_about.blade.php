{{-- ─────────────── D. About Section ─────────────── --}}
<section class="relative py-24 overflow-hidden" style="background: #111827">

    <!-- 魚を描画 -->
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
                <div class="about-card-number" aria-hidden="true">01</div>
                <div class="about-card-body">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center mb-5">
                        <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">目指しているもの</h3>
                    <p class="welcome-text-muted text-sm leading-relaxed">
                        釣り技術の向上・学生間の交流・釣り文化や自然環境の理解と普及。みんなで楽しく釣りができることを目指しています！
                    </p>
                </div>
            </div>

            {{-- Card 2: 雰囲気 --}}
            <div class="reveal reveal-delay-2 relative bg-white/5 border border-white/10 rounded-2xl p-8 overflow-hidden hover:bg-white/8 transition-colors">
                <div class="about-card-number" aria-hidden="true">02</div>
                <div class="about-card-body">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center mb-5">
                        <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">雰囲気は？</h3>
                    <p class="welcome-text-muted text-sm leading-relaxed">
                        初心者もベテランもワイワイと。宅飲みや食事会（釣れた魚料理！）、佐渡・粟島などへの遠征も。苦難と喜びを共にした仲間は最高の絆に。
                    </p>
                </div>
            </div>

            {{-- Card 3: 大会実績 --}}
            <div class="reveal reveal-delay-3 relative bg-white/5 border border-white/10 rounded-2xl p-8 overflow-hidden hover:bg-white/8 transition-colors">
                <div class="about-card-number" aria-hidden="true">03</div>
                <div class="about-card-body">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center mb-5">
                        <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">大会実績</h3>
                    <ul class="welcome-text-muted text-sm leading-relaxed space-y-1.5">
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
            <div class="welcome-photo-frame welcome-photo-frame--3x4 rounded-2xl">
                <img src="{{ asset('images/about1.jpg') }}" alt="活動風景1" class="welcome-photo-pos-about1">
            </div>
            <div class="welcome-photo-frame welcome-photo-frame--3x4 rounded-2xl">
                <img src="{{ asset('images/about2.jpg') }}" alt="活動風景2" class="welcome-photo-pos-about2">
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
