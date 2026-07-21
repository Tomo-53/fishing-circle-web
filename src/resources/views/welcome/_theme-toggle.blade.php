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
