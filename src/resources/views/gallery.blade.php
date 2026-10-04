<x-layout.public
    title="ギャラリー"
    description="新潟大学釣り同好会のギャラリー。釣行や合宿、釣果など、サークル活動の思い出を写真で紹介します。">

    <x-layout.page-hero
        label="Gallery"
        title="ギャラリー" />

    {{-- 本文の構成は #46 で刷新予定。ここではトーン（濃紺・半透明カード）のみ揃えている --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 md:pb-24">

        <div class="bg-white/5 border border-white/10 rounded-2xl p-8 mb-8">
            <h2 class="text-2xl font-semibold text-white mb-4">📸 活動の思い出</h2>
            <p class="welcome-text-muted leading-relaxed">
                サークルメンバーが撮影した釣行の様子や、釣果の写真を掲載しています。
                みんなの素敵な瞬間をお楽しみください！
            </p>
        </div>

        <!-- ギャラリーグリッド -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- 写真プレースホルダー -->
            <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-sky-500/15 to-sky-900/40 flex items-center justify-center">
                    <div class="text-center text-sky-200/80">
                        <svg class="w-12 h-12 mx-auto mb-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">海釣りの様子</p>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-base font-semibold text-white">2024年夏合宿</h3>
                    <p class="text-sm welcome-text-muted">佐渡島での海釣り</p>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-sky-500/15 to-sky-900/40 flex items-center justify-center">
                    <div class="text-center text-sky-200/80">
                        <svg class="w-12 h-12 mx-auto mb-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">川釣りの風景</p>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-base font-semibold text-white">信濃川釣行</h3>
                    <p class="text-sm welcome-text-muted">アユ釣りに挑戦</p>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-sky-500/15 to-sky-900/40 flex items-center justify-center">
                    <div class="text-center text-sky-200/80">
                        <svg class="w-12 h-12 mx-auto mb-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">釣果自慢</p>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-base font-semibold text-white">大物ゲット！</h3>
                    <p class="text-sm welcome-text-muted">70cm級のブリ</p>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-sky-500/15 to-sky-900/40 flex items-center justify-center">
                    <div class="text-center text-sky-200/80">
                        <svg class="w-12 h-12 mx-auto mb-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">BBQの様子</p>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-base font-semibold text-white">釣行後のBBQ</h3>
                    <p class="text-sm welcome-text-muted">みんなで釣果を味わう</p>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-sky-500/15 to-sky-900/40 flex items-center justify-center">
                    <div class="text-center text-sky-200/80">
                        <svg class="w-12 h-12 mx-auto mb-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">新入生歓迎会</p>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-base font-semibold text-white">2024年春の歓迎会</h3>
                    <p class="text-sm welcome-text-muted">新メンバーと一緒に</p>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-sky-500/15 to-sky-900/40 flex items-center justify-center">
                    <div class="text-center text-sky-200/80">
                        <svg class="w-12 h-12 mx-auto mb-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">装備メンテナンス</p>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-base font-semibold text-white">サークル室での活動</h3>
                    <p class="text-sm welcome-text-muted">道具の手入れ</p>
                </div>
            </div>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-white mb-4">📝 写真投稿について</h2>
            <p class="welcome-text-muted leading-relaxed">
                メンバーの皆さんは、活動中に撮影した写真をサークルのギャラリーに投稿できます。
                ログイン後、マイページから簡単に投稿可能です。素敵な瞬間をみんなでシェアしましょう！
            </p>
        </div>
    </div>
</x-layout.public>
