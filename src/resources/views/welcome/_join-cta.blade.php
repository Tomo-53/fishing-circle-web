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
            <p class="welcome-text-muted mb-10 leading-relaxed max-w-md mx-auto">
                新大生なら誰でも入部歓迎。経験不問、竿なし道具なしでも大丈夫。まず一緒に海へ出かけましょう。
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('join') }}"
                   class="welcome-cta cta-ripple relative overflow-hidden px-10 py-4 rounded-full font-bold text-base tracking-wider shadow-2xl focus:outline-none focus:ring-2 focus:ring-white/50"
                   onclick="addRipple(event)"
                   aria-label="入部案内ページへ">
                    入部案内を見る →
                </a>
                <a href="https://instagram.com/new_river_run" target="_blank" rel="noopener noreferrer"
                   class="px-8 py-4 rounded-full font-medium welcome-text-muted hover:text-white text-sm tracking-wider border welcome-border-soft hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/40 flex items-center gap-2">
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
