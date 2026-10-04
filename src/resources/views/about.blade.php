<x-layout.public
    title="サークル紹介"
    description="新潟大学釣り同好会のサークル紹介。大学公認・設立10年以上、40名以上が在籍する県内随一の大学釣り団体です。">

    <x-layout.page-hero
        label="About Us"
        title="サークル紹介" />

    {{--
      構成（#45）: 写真と文章を 1 ブロック（article）にまとめる。#44（activities）と同じ型・クラス体系。
        about1 ↔ 新潟大学釣り同好会とは / about2 ↔ なぜ釣りなのか
      - スマホ: 写真 → 見出し → 本文 の縦並び。PC（lg 以上）: 写真と文章を左右に並べ、ブロックごとに左右を入れ替える。
      - 文字の階層: h2 = text-2xl md:text-3xl / 本文 = text-base leading-relaxed。
      - 本文は OB 執筆の原文。文言は一字一句変えないこと（構造・スタイル・<wbr>・<br> の扱いのみ変更可）。
      - 見出しは break-keep。本文は対応ブラウザで word-break:auto-phrase（文節単位の改行）、必要箇所に <wbr>。
      - 写真は縦長のため 4:3 に切り抜き、object-position で人物が切れない位置に合わせている。
    --}}
    @php
        $h2Class = 'text-2xl md:text-3xl font-bold text-white leading-snug break-keep';
        $bodyClass = 'max-w-prose text-base leading-relaxed welcome-text-muted text-left [word-break:auto-phrase]';
        $photoClass = 'aspect-[4/3] overflow-hidden rounded-xl bg-white/5';
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 md:pb-24">
        <div class="space-y-16 md:space-y-24">

            {{-- 1. 新潟大学釣り同好会とは（写真 about1・左） --}}
            <article aria-labelledby="about-circle"
                class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-6 lg:p-8 grid gap-6 lg:grid-cols-12 lg:gap-10 lg:items-center">
                <div class="lg:col-span-5">
                    <div class="{{ $photoClass }}">
                        <img src="{{ asset('images/about1.jpg') }}" alt="サークル活動の様子"
                            class="h-full w-full object-cover object-[center_70%]" decoding="async">
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <h2 id="about-circle" class="{{ $h2Class }}">新潟大学<wbr>釣り同好会とは</h2>
                    <div class="mt-3 mb-6 h-px w-12 bg-sky-500/50" aria-hidden="true"></div>
                    <div class="{{ $bodyClass }} space-y-4">
                        <p class="text-lg md:text-xl font-semibold leading-relaxed text-sky-300">大学公認サークル、<wbr>設立10年以上、<wbr>男女合わせて<wbr>40名以上が<wbr>在籍する<wbr>県内随一の<wbr>大学釣り団体</p>
                        <p>初心者～上級者、男女含めて様々なメンバーが在籍。</p>
                        <p class="text-sky-300 font-medium">一緒に楽しく釣りを！そして釣り技術向上を目指して活動中。</p>
                    </div>
                </div>
            </article>

            {{-- 2. なぜ釣りなのか（写真 about2・右） --}}
            <article aria-labelledby="about-why"
                class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-6 lg:p-8 grid gap-6 lg:grid-cols-12 lg:gap-10 lg:items-start">
                <div class="lg:col-span-5 lg:order-last lg:sticky lg:top-24">
                    {{-- 釣った魚（写真下部）まで見せるため、この写真だけ縦長の枠にする --}}
                    <div class="aspect-[4/5] overflow-hidden rounded-xl bg-white/5">
                        <img src="{{ asset('images/about2.jpg') }}" alt="釣りの風景"
                            class="h-full w-full object-cover object-center" loading="lazy" decoding="async">
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <h2 id="about-why" class="{{ $h2Class }}">なぜ釣りなのか</h2>
                    <div class="mt-3 mb-6 h-px w-12 bg-sky-500/50" aria-hidden="true"></div>
                    <div class="{{ $bodyClass }} space-y-5">
                        <p>新大に初めて来た人は驚いたはず。その海の近さに。</p>
                        <p>大学裏、眼下に広がる日本海。延々とのびるサーフ、水平線の先には鎮座する大いなる島"佐渡"。</p>
                        <p>陸を見れば信濃川と阿賀野川が作り出した広大な平野とそこに点在する潟の数々、豊かな河口域や山々の渓流。そしてここに生きる魚達。</p>
                        <p class="!my-8 text-lg md:text-xl font-semibold text-sky-300">新潟は多様な水辺の王国だ。</p>
                        <p>そして新大の海の近さ、多様な水辺環境は釣りに最高の環境だ。</p>
                        <p class="!mt-8 border-l-2 border-sky-400/70 pl-4 text-sky-300 font-medium leading-loose">
                            さぁ、釣りに行こう。水辺とそこに生きる自然を感じに。<br>
                            魚との出会いを求めて。<br>
                            新たな発見と感動を求めて。
                        </p>
                        <p class="!mt-10 font-display text-xl md:text-2xl font-bold tracking-wide text-white">enjoy nature, enjoy fishing</p>
                    </div>
                </div>
            </article>

        </div>

        {{-- 入部案内への導線（トップ「ギャラリーをもっと見る」と同系統） --}}
        <div class="text-center mt-16 md:mt-20">
            <a href="{{ route('join') }}"
               class="inline-flex items-center gap-2 px-8 py-3 rounded-full text-sm font-medium text-white/80 hover:text-white border border-white/25 hover:border-white/50 hover:bg-white/10 transition-colors">
                入部案内を見る
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</x-layout.public>
