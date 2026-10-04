<x-layout.public
    title="サークル紹介"
    description="新潟大学釣り同好会のサークル紹介。大学公認・設立10年以上、40名以上が在籍する県内随一の大学釣り団体です。">

    <x-layout.page-hero
        label="About Us"
        title="サークル紹介" />

    {{-- 本文の構成は #45 で刷新予定。ここではトーン（濃紺・半透明カード）のみ揃えている --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 md:pb-24">

        <!-- 4つのグリッドレイアウト -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 左上：新潟大学釣り同好会とは -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-bold text-white mb-6 text-center">新潟大学釣り同好会とは</h2>
                <div class="welcome-text-muted leading-relaxed space-y-4">
                    <p class="font-semibold text-lg text-sky-300">
                        大学公認サークル、設立10年以上、男女合わせて40名以上が在籍する県内随一の大学釣り団体
                    </p>
                    <p>
                        初心者～上級者、男女含めて様々なメンバーが在籍。
                    </p>
                    <p class="text-sky-300 font-medium">
                        一緒に楽しく釣りを！そして釣り技術向上を目指して活動中。
                    </p>
                </div>
            </div>

            <!-- 右上：画像2 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3 flex items-center justify-center">
                <div class="w-full h-64 rounded-xl overflow-hidden">
                    <img src="{{ asset('images/about1.jpg') }}" alt="サークル活動の様子" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- 左下：画像3 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3 flex items-center justify-center">
                <div class="w-full h-64 rounded-xl overflow-hidden">
                    <img src="{{ asset('images/about2.jpg') }}" alt="釣りの風景" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- 右下：なぜ釣りなのか -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-bold text-white mb-6 text-center">なぜ釣りなのか</h2>
                <div class="welcome-text-muted leading-relaxed space-y-3 text-sm">
                    <p>
                        新大に初めて来た人は驚いたはず。その海の近さに。
                    </p>
                    <p>
                        大学裏、眼下に広がる日本海。延々とのびるサーフ、水平線の先には鎮座する大いなる島"佐渡"。
                    </p>
                    <p>
                        陸を見れば信濃川と阿賀野川が作り出した広大な平野とそこに点在する潟の数々、豊かな河口域や山々の渓流。そしてここに生きる魚達。
                    </p>
                    <p class="font-semibold text-sky-300">
                        新潟は多様な水辺の王国だ。
                    </p>
                    <p>
                        そして新大の海の近さ、多様な水辺環境は釣りに最高の環境だ。
                    </p>
                    <p class="text-sky-300">
                        さぁ、釣りに行こう。水辺とそこに生きる自然を感じに。<br>
                        魚との出会いを求めて。<br>
                        新たな発見と感動を求めて。
                    </p>
                    <p class="text-center font-bold text-white italic mt-4" style="font-family: 'Comfortaa', 'Noto Sans JP', sans-serif">
                        enjoy nature, enjoy fishing
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layout.public>
