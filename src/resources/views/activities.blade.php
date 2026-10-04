<x-layout.public
    title="活動内容"
    description="新潟大学釣り同好会の活動内容。月例釣行会・定例会・合宿など、季節に合わせた釣り企画と年間行事を紹介します。">

    <x-layout.page-hero
        label="Activities"
        title="活動内容" />

    {{-- 本文の構成は #44 で刷新予定。ここではトーン（濃紺・半透明カード）のみ揃えている --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 md:pb-24">

        <!-- 8つのグリッドレイアウト -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">
            <!-- 1. 画像1 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3">
                <div class="w-full rounded-xl overflow-hidden">
                    <img src="{{ asset('images/active1.jpg') }}" alt="活動の様子" class="w-full h-auto object-contain">
                </div>
            </div>

            <!-- 2. 文章1：活動内容 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col justify-center h-full">
                <h2 class="text-xl font-bold text-white mb-4">活動内容</h2>
                <div class="welcome-text-muted leading-relaxed space-y-3 text-sm">
                    <p>魚を見て、釣って、学んで、食べて……。釣りや魚に関することは何でもやります！海や川、自然と触れ合い、地球人として成長してみませんか。</p>
                    <p>兼部している人、バイトが忙しい人も大歓迎！テスト期間1週間前からは活動はありません。マイペースに釣りに行けます。基本的に活動は自由参加です。</p>
                    <p>企画は、一人で行くのが難しい釣りや車での釣行、離島での合宿からまったりハゼ釣りまで季節に合ったバラエティに富んだものとなっています。興味のある人はどんどん参加してください！</p>
                </div>
            </div>

            <!-- 3. 文章2：年間行事 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col justify-center h-full">
                <h2 class="text-xl font-bold text-white mb-4">年間行事</h2>
                <div class="welcome-text-muted leading-relaxed space-y-2 text-xs">
                    <p><strong class="text-white">4月:</strong> お花見会、新歓説明会、新歓食事会</p>
                    <p><strong class="text-white">5月:</strong> 新歓釣行会（海釣り、マス釣り）</p>
                    <p><strong class="text-white">6月:</strong> 新歓釣行会（海釣り、マス釣り）、確コン、安全マナー講習会</p>
                    <p><strong class="text-white">7月:</strong> 部内戦</p>
                    <p><strong class="text-white">8月:</strong> 浜コン</p>
                    <p><strong class="text-white">9月:</strong> 夏合宿（粟島などの離島など）</p>
                    <p><strong class="text-white">10月:</strong> 新大祭出店、佐渡ビックゲーム</p>
                    <p><strong class="text-white">11月:</strong> 個人遠征など</p>
                    <p><strong class="text-white">12月:</strong> 忘年会、忘年釣行会（マス釣り）</p>
                    <p><strong class="text-white">1月:</strong> 水族館、冬合宿</p>
                    <p><strong class="text-white">2月:</strong> 新潟フィッシングショー</p>
                    <p><strong class="text-white">3月:</strong> 追いコン</p>
                    <p class="text-sky-300 font-medium">大まかにはこんな感じ！釣り会は普段の活動として毎月行ってます！</p>
                    <p class="text-xs welcome-text-subtle">※行事の時期やその内容は年度によって変ります。</p>
                </div>
            </div>

            <!-- 4. 画像2 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3">
                <div class="w-full rounded-xl overflow-hidden">
                    <img src="{{ asset('images/active2.jpg') }}" alt="年間行事の様子" class="w-full h-auto object-contain">
                </div>
            </div>

            <!-- 5. 画像3 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3">
                <div class="w-full rounded-xl overflow-hidden">
                    <img src="{{ asset('images/active3.jpg') }}" alt="普段の活動" class="w-full h-auto object-contain">
                </div>
            </div>

            <!-- 6. 文章3：普段の活動 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col justify-center h-full">
                <h2 class="text-xl font-bold text-white mb-4">普段の活動</h2>
                <div class="welcome-text-muted leading-relaxed space-y-3 text-sm">
                    <div>
                        <h3 class="text-sm font-semibold text-sky-300 mb-1">〈定例会〉</h3>
                        <p class="text-xs">直近の部員の釣果報告やミーティング、勉強会。月に2回、平日の5限後。場所は図書館グループ学習室。ここで意気投合して即日釣りに！？なんてことも！</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-sky-300 mb-1">〈月例釣行会〉</h3>
                        <p class="text-xs">月に1回、県内（主に新潟市内）の釣り場でみんなで仲良く釣り！＆めざせスキルアップ！五十嵐浜キス釣り、日和山堤防釣り、ハゼ釣り、船タイラバ…etc</p>
                    </div>
                </div>
            </div>

            <!-- 7. 文章4：新大釣りサーの雰囲気・特徴 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col justify-center h-full">
                <h2 class="text-xl font-bold text-white mb-4">新大釣りサーの雰囲気・特徴</h2>
                <div class="welcome-text-muted leading-relaxed space-y-2 text-xs">
                    <p>基本的に活動は自由参加。マイペースに参加する人、毎回の活動に参加する人など様々。目標の魚を目指して情熱を燃やす人や、近場でマイペースな釣りをする人、はたまた飲みだけ参加する人も（笑）</p>
                    <p>経験者~初心者まで様々な人がいます。また、多くの人が兼部や、バイトとの掛け持ちをしています。</p>
                    <p>学生間の交流も盛んで、飲み会や食事会は良く行います。誘い合って一緒に釣り行くことは日常茶飯事。</p>
                    <div class="mt-3">
                        <h3 class="text-sm font-semibold text-sky-300 mb-1">釣りの様子</h3>
                        <p>月例釣行会や新歓釣行・忘年釣行ではみんなでワイワイと楽しく釣りを。その後はみんなでご飯食べに行ったり、釣った魚を料理して食事会をしたり。</p>
                        <p>プライベートでは、車持ちの人の運転やレンタカーで少し遠くのポイントやエリアトラウトなどに行くことも。</p>
                        <p class="text-sky-300 font-medium">苦難・喜びを共にし、絆を深めた者達は最高の仲間です！さぁ、釣りをきっかけに最高の仲間をつくろう！</p>
                    </div>
                </div>
            </div>

            <!-- 8. 画像4 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3">
                <div class="w-full rounded-xl overflow-hidden">
                    <img src="{{ asset('images/active4.jpg') }}" alt="サークルの雰囲気" class="w-full h-auto object-contain">
                </div>
            </div>
        </div>
    </div>
</x-layout.public>
