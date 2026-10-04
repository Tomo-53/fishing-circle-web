<x-layout.public
    title="活動内容"
    description="新潟大学釣り同好会の活動内容。月例釣行会・定例会・合宿など、季節に合わせた釣り企画と年間行事を紹介します。">

    <x-layout.page-hero
        label="Activities"
        title="活動内容" />

    {{--
      構成（#44）: 写真と説明文を 1 ブロック（article）にまとめ、対応関係を明確にする。
        active1 ↔ 活動内容 / active2 ↔ 年間行事 / active4 ↔ 新大釣りサーの雰囲気・特徴
        普段の活動 はブロック内を〈定例会〉〈月例釣行会〉の 2 サブブロックに分け、各説明のすぐ上に対応写真を置く（全幅・PC は左右 2 カラム）。
      - スマホ: 写真 → 見出し → 本文 の縦並び。PC（lg 以上）: 写真と文章を左右に並べ、ブロックごとに左右を入れ替える。
      - 文字の階層: h2 = text-2xl md:text-3xl / h3 = text-lg font-semibold / 本文 = text-base leading-relaxed（注記のみ text-sm）。
      - 本文は OB 執筆の原文。文言は一字一句変えないこと（構造・スタイル・<wbr> のみ変更可）。
      - 見出しは break-keep + <wbr> で語の途中で折り返さない。本文は対応ブラウザで word-break:auto-phrase（文節単位の改行）。
    --}}
    @php
        $h2Class = 'text-2xl md:text-3xl font-bold text-white leading-snug break-keep';
        $h3Class = 'text-lg font-semibold text-white leading-snug break-keep border-l-2 border-sky-400/70 pl-3';
        $bodyClass = 'max-w-prose text-base leading-relaxed welcome-text-muted text-left [word-break:auto-phrase]';
        $photoClass = 'aspect-[4/3] overflow-hidden rounded-xl bg-white/5';
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 md:pb-24">
        <div class="space-y-16 md:space-y-24">

            {{-- 1. 活動内容（写真 active1・左） --}}
            <article aria-labelledby="activity-overview"
                class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-6 lg:p-8 grid gap-6 lg:grid-cols-12 lg:gap-10 lg:items-start">
                <div class="lg:col-span-5 lg:sticky lg:top-24">
                    <div class="{{ $photoClass }}">
                        <img src="{{ asset('images/active1.jpg') }}" alt="活動の様子"
                            class="h-full w-full object-cover object-[center_25%]" loading="lazy" decoding="async">
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <h2 id="activity-overview" class="{{ $h2Class }}">活動内容</h2>
                    <div class="mt-3 mb-6 h-px w-12 bg-sky-500/50" aria-hidden="true"></div>
                    <div class="{{ $bodyClass }} space-y-4">
                        <p>魚を見て、釣って、学んで、食べて……。釣りや魚に関することは何でもやります！海や川、自然と触れ合い、地球人として成長してみませんか。</p>
                        <p>兼部している人、バイトが忙しい人も大歓迎！テスト期間1週間前からは活動はありません。マイペースに釣りに行けます。基本的に活動は自由参加です。</p>
                        <p>企画は、一人で行くのが難しい釣りや車での釣行、離島での合宿からまったりハゼ釣りまで季節に合ったバラエティに富んだものとなっています。興味のある人はどんどん参加してください！</p>
                    </div>
                </div>
            </article>

            {{-- 2. 年間行事（写真 active2・右） --}}
            <article aria-labelledby="activity-calendar"
                class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-6 lg:p-8 grid gap-6 lg:grid-cols-12 lg:gap-10 lg:items-start">
                <div class="lg:col-span-5 lg:order-last lg:sticky lg:top-24">
                    <div class="{{ $photoClass }}">
                        <img src="{{ asset('images/active2.jpg') }}" alt="年間行事の様子"
                            class="h-full w-full object-cover object-[center_62%]" loading="lazy" decoding="async">
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <h2 id="activity-calendar" class="{{ $h2Class }}">年間行事</h2>
                    <div class="mt-3 mb-6 h-px w-12 bg-sky-500/50" aria-hidden="true"></div>
                    <div class="{{ $bodyClass }}">
                        <dl class="divide-y divide-white/10 border-y border-white/10">
                            @foreach ([
                                ['4月:', 'お花見会、新歓説明会、新歓食事会'],
                                ['5月:', '新歓釣行会（海釣り、マス釣り）'],
                                ['6月:', '新歓釣行会（海釣り、マス釣り）、確コン、安全マナー講習会'],
                                ['7月:', '部内戦'],
                                ['8月:', '浜コン'],
                                ['9月:', '夏合宿（粟島などの離島など）'],
                                ['10月:', '新大祭出店、佐渡ビックゲーム'],
                                ['11月:', '個人遠征など'],
                                ['12月:', '忘年会、忘年釣行会（マス釣り）'],
                                ['1月:', '水族館、冬合宿'],
                                ['2月:', '新潟フィッシングショー'],
                                ['3月:', '追いコン'],
                            ] as [$month, $events])
                                <div class="grid grid-cols-[4rem_1fr] gap-x-4 py-2.5">
                                    <dt class="font-semibold text-sky-300 tabular-nums whitespace-nowrap">{{ $month }}</dt>
                                    <dd>{{ $events }}</dd>
                                </div>
                            @endforeach
                        </dl>
                        <p class="mt-6 text-sky-300 font-medium">大まかにはこんな感じ！釣り会は普段の活動として毎月行ってます！</p>
                        <p class="mt-2 text-sm welcome-text-subtle">※行事の時期やその内容は年度によって変ります。</p>
                    </div>
                </div>
            </article>

            {{-- 3. 普段の活動（全幅。〈定例会〉〈月例釣行会〉それぞれに写真を添える） --}}
            <article aria-labelledby="activity-regular"
                class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-6 lg:p-8">
                <h2 id="activity-regular" class="{{ $h2Class }}">普段の活動</h2>
                <div class="mt-3 mb-6 lg:mb-8 h-px w-12 bg-sky-500/50" aria-hidden="true"></div>
                <div class="grid gap-10 lg:grid-cols-2 lg:gap-10">
                    <section aria-labelledby="activity-regular-meeting">
                        <div class="{{ $photoClass }}">
                            <img src="{{ asset('images/activities/regular-meeting-1.jpg') }}" alt="学習室でモニターを囲んでミーティング"
                                width="1280" height="960"
                                class="h-full w-full object-cover" loading="lazy" decoding="async">
                        </div>
                        <h3 id="activity-regular-meeting" class="mt-5 {{ $h3Class }}">〈定例会〉</h3>
                        <p class="mt-3 {{ $bodyClass }}">直近の部員の釣果報告やミーティング、勉強会。月に2回、平日の5限後。場所は図書館グループ学習室。ここで意気投合して即日釣りに！？なんてことも！</p>
                    </section>
                    <section aria-labelledby="activity-regular-trip">
                        <div class="{{ $photoClass }}">
                            <img src="{{ asset('images/activities/monthly-trip-1.jpg') }}" alt="夕方の堤防で竿を出す部員"
                                width="961" height="1280"
                                class="h-full w-full object-cover object-[center_88%]" loading="lazy" decoding="async">
                        </div>
                        <h3 id="activity-regular-trip" class="mt-5 {{ $h3Class }}">〈月例釣行会〉</h3>
                        <p class="mt-3 {{ $bodyClass }}">月に1回、県内（主に新潟市内）の釣り場でみんなで仲良く釣り！＆めざせスキルアップ！五十嵐浜キス釣り、日和山堤防釣り、ハゼ釣り、船タイラバ…etc</p>
                    </section>
                </div>
            </article>

            {{-- 4. 新大釣りサーの雰囲気・特徴（写真 active4・右） --}}
            <article aria-labelledby="activity-atmosphere"
                class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-6 lg:p-8 grid gap-6 lg:grid-cols-12 lg:gap-10 lg:items-start">
                <div class="lg:col-span-5 lg:order-last lg:sticky lg:top-24">
                    <div class="{{ $photoClass }}">
                        <img src="{{ asset('images/active4.jpg') }}" alt="サークルの雰囲気"
                            class="h-full w-full object-cover object-[center_30%]" loading="lazy" decoding="async">
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <h2 id="activity-atmosphere" class="{{ $h2Class }}">新大釣りサーの<wbr>雰囲気・<wbr>特徴</h2>
                    <div class="mt-3 mb-6 h-px w-12 bg-sky-500/50" aria-hidden="true"></div>
                    <div class="{{ $bodyClass }} space-y-8">
                        <div class="space-y-4">
                            <p>基本的に活動は自由参加。マイペースに参加する人、毎回の活動に参加する人など様々。目標の魚を目指して情熱を燃やす人や、近場でマイペースな釣りをする人、はたまた飲みだけ参加する人も（笑）</p>
                            <p>経験者~初心者まで様々な人がいます。また、多くの人が兼部や、バイトとの掛け持ちをしています。</p>
                            <p>学生間の交流も盛んで、飲み会や食事会は良く行います。誘い合って一緒に釣り行くことは日常茶飯事。</p>
                        </div>
                        <section>
                            <h3 class="{{ $h3Class }}">釣りの様子</h3>
                            <div class="mt-3 space-y-4">
                                <p>月例釣行会や新歓釣行・忘年釣行ではみんなでワイワイと楽しく釣りを。その後はみんなでご飯食べに行ったり、釣った魚を料理して食事会をしたり。</p>
                                <p>プライベートでは、車持ちの人の運転やレンタカーで少し遠くのポイントやエリアトラウトなどに行くことも。</p>
                                <p class="text-sky-300 font-medium">苦難・喜びを共にし、絆を深めた者達は最高の仲間です！さぁ、釣りをきっかけに最高の仲間をつくろう！</p>
                            </div>
                        </section>
                    </div>
                </div>
            </article>

        </div>
    </div>
</x-layout.public>
