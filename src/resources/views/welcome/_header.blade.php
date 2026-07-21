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
                <a href="{{ route('about') }}" class="nav-link welcome-text-muted hover:text-white text-sm tracking-wider transition-colors rounded-sm focus:outline-none focus:ring-2 focus:ring-white/60">サークル紹介</a>
                <a href="{{ route('activities') }}" class="nav-link welcome-text-muted hover:text-white text-sm tracking-wider transition-colors rounded-sm focus:outline-none focus:ring-2 focus:ring-white/60">活動内容</a>
                <a href="{{ route('gallery') }}" class="nav-link welcome-text-muted hover:text-white text-sm tracking-wider transition-colors rounded-sm focus:outline-none focus:ring-2 focus:ring-white/60">ギャラリー</a>
                <a href="{{ route('join') }}" class="nav-link welcome-text-muted hover:text-white text-sm tracking-wider transition-colors rounded-sm focus:outline-none focus:ring-2 focus:ring-white/60">入部案内</a>
            </nav>

            {{-- Right: SNS + Auth + Hamburger --}}
            <div class="flex items-center gap-4">
                {{-- SNS icons --}}
                <div class="hidden md:flex items-center gap-3">
                    <a href="https://instagram.com/new_river_run" target="_blank" rel="noopener noreferrer"
                       class="welcome-text-subtle hover:text-white transition-colors" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://x.com/new_river_runs" target="_blank" rel="noopener noreferrer"
                       class="welcome-text-subtle hover:text-white transition-colors" aria-label="X (Twitter)">
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
                           class="px-4 py-1.5 rounded-full text-sm welcome-text-muted hover:text-white border welcome-border-soft hover:border-white/50 transition-colors">
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
                        class="md:hidden welcome-text-muted hover:text-white p-1 focus:outline-none focus:ring-2 focus:ring-white/40 rounded"
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
             class="welcome-mobile-nav md:hidden pb-4 border-t border-white/10 mt-2 pt-4"
             role="navigation" aria-label="モバイルナビゲーション">
            <nav class="flex flex-col gap-1">
                <a href="{{ route('about') }}" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg text-sm tracking-wide transition-colors focus:outline-none focus:ring-2 focus:ring-white/40">サークル紹介</a>
                <a href="{{ route('activities') }}" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg text-sm tracking-wide transition-colors focus:outline-none focus:ring-2 focus:ring-white/40">活動内容</a>
                <a href="{{ route('gallery') }}" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg text-sm tracking-wide transition-colors focus:outline-none focus:ring-2 focus:ring-white/40">ギャラリー</a>
                <a href="{{ route('join') }}" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg text-sm tracking-wide transition-colors focus:outline-none focus:ring-2 focus:ring-white/40">入部案内</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="welcome-cta mt-2 mx-4 py-2.5 text-center rounded-full text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/40">ダッシュボード</a>
                @else
                    <div class="flex gap-2 mt-2 px-4">
                        <a href="{{ route('login') }}" class="flex-1 py-2 text-center rounded-full text-sm text-white/80 border border-white/30 focus:outline-none focus:ring-2 focus:ring-white/40">ログイン</a>
                        <a href="{{ route('register') }}" class="welcome-cta flex-1 py-2 text-center rounded-full text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/40">新規登録</a>
                    </div>
                @endauth
            </nav>
        </div>
    </div>
</header>
{{-- /Header --}}
