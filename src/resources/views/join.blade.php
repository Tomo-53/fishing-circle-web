<x-layout.public
    title="入部案内"
    description="新潟大学釣り同好会の入部案内。入会は随時受付中、経験者～初心者まで大歓迎。年会費や新歓の流れを紹介します。">

    <x-layout.page-hero
        label="Join Us"
        title="入部案内" />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 md:pb-24">

        <!-- 4つのグリッドレイアウト -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16 md:mb-20">
            <!-- 左上：入会は随時受け付け中！ -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-bold text-white mb-6">入会は随時受け付け中！</h2>
                <div class="welcome-text-muted leading-relaxed space-y-4">
                    <p>
                        当サークルでは年中メンバー募集中です。<br>
                        新歓時期以外でもOK！また、例年多くの２年生以上の方も入会してます。
                    </p>
                    <p class="font-semibold text-sky-300">
                        経験者～初心者、女子、男子問わず大歓迎！
                    </p>
                    <div class="bg-sky-500/10 border border-sky-400/20 p-4 rounded-xl">
                        <p class="font-bold text-white mb-2">
                            気になった方はX・InstagramのDMへGo!
                        </p>
                        <p>
                            釣りを始めてみたい君、釣りをもっとしたい釣りキチ、入会を待ってるぞ！
                        </p>
                    </div>
                </div>
            </div>

            <!-- 右上：画像1 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3 flex items-center justify-center">
                <img src="{{ asset('images/join1.jpg') }}" alt="入会案内画像1" class="w-full h-64 object-cover rounded-xl">
            </div>

            <!-- 左下：画像2 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3 flex items-center justify-center">
                <img src="{{ asset('images/join2.jpg') }}" alt="新大祭での集合写真" class="w-full h-64 object-cover rounded-xl">
            </div>

            <!-- 右下：サークル入会費について -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-bold text-white mb-6">サークル入会費について</h2>
                <div class="welcome-text-muted leading-relaxed space-y-4">
                    <div class="bg-white/5 border-l-4 border-sky-400 p-4 rounded-r-xl">
                        <h3 class="text-lg font-bold text-sky-300 mb-2">・年会費のみ（5000円以下）</h3>
                        <p>
                            いただいた会費は全体活動の費用（エサ代など）や部内貸し出しタックルの整備などに充て、活発な活動や釣り技術向上を目指します。
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 新歓の流れ（トップのセクション見出しと同じトーン） -->
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4"
                style="font-family: 'Comfortaa', 'Noto Sans JP', sans-serif">
                新歓の流れ
            </h2>
            <div class="mx-auto h-px w-16 bg-sky-500/40"></div>
            <p class="mt-4 welcome-text-muted text-sm md:text-base">年間を通した入会サポート</p>
        </div>

        <div class="grid md:grid-cols-5 gap-6 lg:gap-8 items-start">
            <!-- 左側：join3画像（3/5の幅） -->
            <div class="md:col-span-3 bg-white/5 border border-white/10 rounded-2xl p-3 h-full flex items-center">
                <img src="{{ asset('images/join3.jpg') }}" alt="新歓の流れ画像" class="w-full h-full object-contain rounded-xl min-h-[500px]">
            </div>

            <!-- 右側：新歓の流れ内容（2/5の幅） -->
            <div class="md:col-span-2 bg-white/5 border border-white/10 rounded-2xl p-6">
                <h3 class="text-2xl font-bold text-white mb-6 text-center">📅 新歓スケジュール</h3>
                <div class="space-y-4">
                    <!-- 2月～3月 -->
                    <div class="bg-white/5 rounded-r-xl p-4 border-l-4 border-sky-400/70">
                        <h4 class="text-lg font-bold text-sky-300 mb-2">2月～3月：</h4>
                        <div class="welcome-text-muted text-sm space-y-1">
                            <p>二次試験が終わり合格発表🌸</p>
                            <p>部員達も新歓に向けて準備に入ります。4月から始まる新歓の情報を見逃さないようにしよう！</p>
                        </div>
                    </div>

                    <!-- 4月 -->
                    <div class="bg-white/5 rounded-r-xl p-4 border-l-4 border-sky-400/70">
                        <h4 class="text-lg font-bold text-sky-300 mb-2">4月：</h4>
                        <ul class="welcome-text-muted text-sm space-y-1">
                            <li>• 新歓説明会</li>
                            <li>• 新歓お花見会</li>
                            <li>• 新歓食事会</li>
                        </ul>
                    </div>

                    <!-- 5月 -->
                    <div class="bg-white/5 rounded-r-xl p-4 border-l-4 border-sky-400/70">
                        <h4 class="text-lg font-bold text-sky-300 mb-2">5月：</h4>
                        <p class="welcome-text-muted text-sm">• 新歓釣行会（in五頭フィッシングパーク）</p>
                    </div>

                    <!-- 6月 -->
                    <div class="bg-white/5 rounded-r-xl p-4 border-l-4 border-sky-400/70">
                        <h4 class="text-lg font-bold text-sky-300 mb-2">6月：</h4>
                        <ul class="welcome-text-muted text-sm space-y-1">
                            <li>• 新歓釣行会（in日和山突堤・五十嵐浜）</li>
                            <li>• 安全・マナー講習会＆確コン</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.public>
