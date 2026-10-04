<x-layout.public
    title="ギャラリー"
    description="新潟大学釣り同好会のギャラリー。釣行や合宿、釣果など、サークル活動の思い出を写真で紹介します。">

    <x-layout.page-hero
        label="Gallery"
        title="ギャラリー" />

    {{--
      構成（#46）: 説明カード → 写真グリッド → 写真投稿の説明カード。
      - 写真はサイト内の既存写真（images/）とオーナー提供の写真（images/gallery/）。alt は既存写真は他ページの alt を流用、
        提供写真はオーナー確認済みの文言。魚種は未確認のため alt に書かない。
      - 並び順: 集合写真・釣果・料理/イベント・風景が偏らないよう混在させる。
      - グリッド: スマホ 2 列 / md 以上 3 列。正方形に揃え object-cover、人物・釣果が切れないよう object-position を個別指定。
      - 'wide' の写真は 2 列幅（スマホは 2:1、PC は隣の正方形と同じ高さ ≒ 2:1）。
        25 枚 + wide 5 枚 = 30 マス → スマホ 15 行・PC 10 行でちょうど埋まる（最終行に余りを出さない）。
        並べ替えるときは、スマホでは wide の直前の通常写真が偶数枚、PC では wide が 3 列目から始まらないことを保つこと。
      - ホバー表現はトップの「活動フォト」（photo-card / welcome-photo-frame / photo-overlay）と同じ。
      - クリック / Enter でライトボックス（resources/js/gallery-lightbox.js）。
      - 説明文の本文は既存の文章。文言は一字一句変えないこと（構造・スタイルのみ変更可）。
    --}}
    @php
        $h2Class = 'text-2xl md:text-3xl font-bold text-white leading-snug break-keep';
        $bodyClass = 'max-w-prose text-base leading-relaxed welcome-text-muted [word-break:auto-phrase]';

        // file: public/images/ からの相対パス / alt / position: サムネイルの object-position / wide: 2 列幅
        $photos = [
            ['file' => 'gallery/group-beach.jpg', 'alt' => '砂浜での集合写真', 'position' => 'object-[center_60%]', 'wide' => true],
            ['file' => 'gallery/catch-01.jpg', 'alt' => '木陰の川辺で釣果を持つ部員', 'position' => 'object-[center_60%]'],
            ['file' => 'gallery/festival-booth.jpg', 'alt' => '学園祭の出店（「釣り同好会」の看板）', 'position' => 'object-center'],
            ['file' => 'gallery/pier-sunset.jpg', 'alt' => '夕暮れの堤防のシルエット', 'position' => 'object-[25%_center]'],
            ['file' => 'gallery/catch-05.jpg', 'alt' => '海を背に釣果を持つ部員', 'position' => 'object-[center_40%]'],
            ['file' => 'active3.jpg', 'alt' => '普段の活動', 'position' => 'object-center'],
            ['file' => 'gallery/catch-03.jpg', 'alt' => '海辺の堤防で釣果を持つ部員', 'position' => 'object-[center_25%]'],
            ['file' => 'gallery/ferry-deck.jpg', 'alt' => 'フェリーのデッキから海を眺める部員', 'position' => 'object-center'],
            ['file' => 'active4.jpg', 'alt' => 'サークルの雰囲気', 'position' => 'object-[center_30%]'],
            ['file' => 'gallery/sand-writing.jpg', 'alt' => '砂浜に書いた「釣り同好会」の文字', 'position' => 'object-center', 'wide' => true],
            ['file' => 'gallery/group-ferry.jpg', 'alt' => 'フェリー乗り場での集合写真', 'position' => 'object-[center_65%]', 'wide' => true],
            ['file' => 'gallery/catch-02.jpg', 'alt' => '河原で釣果を持つ部員', 'position' => 'object-[center_30%]'],
            ['file' => 'gallery/sashimi.jpg', 'alt' => '釣った魚で作った刺身と料理', 'position' => 'object-center'],
            ['file' => 'gallery/beach-bonfire.jpg', 'alt' => '夜の浜辺の焚き火', 'position' => 'object-[center_30%]'],
            ['file' => 'gallery/catch-06.jpg', 'alt' => '港の岸壁で釣果を持つ部員', 'position' => 'object-[center_30%]'],
            ['file' => 'join2.jpg', 'alt' => '入会案内画像2', 'position' => 'object-center'],
            ['file' => 'gallery/catch-07.jpg', 'alt' => '計測ボードの上の釣果', 'position' => 'object-center'],
            ['file' => 'gallery/dinner-party.jpg', 'alt' => '和室での食事会', 'position' => 'object-center'],
            ['file' => 'gallery/snow-trout.jpg', 'alt' => '雪の中での釣果', 'position' => 'object-[center_65%]'],
            ['file' => 'gallery/sea-wading.jpg', 'alt' => '海に入ってはしゃぐ部員', 'position' => 'object-[center_60%]', 'wide' => true],
            ['file' => 'gallery/group-sunset.jpg', 'alt' => '夕焼けの堤防での集合写真', 'position' => 'object-[center_60%]', 'wide' => true],
            ['file' => 'gallery/catch-04.jpg', 'alt' => '川沿いの土手で釣果を持つ部員', 'position' => 'object-center'],
            ['file' => 'active2.jpg', 'alt' => '年間行事の様子', 'position' => 'object-[center_80%]'],
            ['file' => 'join3.jpg', 'alt' => '新歓の流れ画像', 'position' => 'object-[40%_center]'],
            ['file' => 'active1.jpg', 'alt' => '活動の様子', 'position' => 'object-[center_25%]'],
        ];

        $lightboxPhotos = array_map(
            fn (array $photo) => ['src' => asset('images/'.$photo['file']), 'alt' => $photo['alt']],
            $photos,
        );
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 md:pb-24">

        <section aria-labelledby="gallery-memories"
            class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 mb-8 md:mb-10">
            <h2 id="gallery-memories" class="{{ $h2Class }}">活動の思い出</h2>
            <div class="mt-3 mb-5 h-px w-12 bg-sky-500/50" aria-hidden="true"></div>
            <p class="{{ $bodyClass }}">
                サークルメンバーが撮影した釣行の様子や、釣果の写真を掲載しています。
                みんなの素敵な瞬間をお楽しみください！
            </p>
        </section>

        {{-- 写真グリッド + ライトボックス --}}
        <div x-data="galleryLightbox(@js($lightboxPhotos))" @keydown.window="onKeydown($event)" class="mb-8 md:mb-10">
            <ul class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4" role="list" aria-label="活動写真">
                @foreach ($photos as $i => $photo)
                    <li class="{{ ($photo['wide'] ?? false) ? 'col-span-2' : '' }}">
                        <button type="button"
                            x-ref="thumb{{ $i }}"
                            @click="show({{ $i }})"
                            aria-haspopup="dialog"
                            class="group photo-card welcome-photo-frame block w-full {{ ($photo['wide'] ?? false) ? 'aspect-[2/1] md:aspect-auto md:h-full' : 'aspect-square' }} rounded-xl bg-white/5 cursor-zoom-in focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400 focus-visible:ring-offset-2 focus-visible:ring-offset-gray-900">
                            <img src="{{ asset('images/'.$photo['file']) }}"
                                alt="{{ $photo['alt'] }}"
                                class="{{ $photo['position'] }}"
                                loading="lazy"
                                decoding="async">
                            <span class="photo-overlay group-focus-visible:opacity-100" aria-hidden="true"></span>
                            <span class="absolute bottom-2 right-2 flex h-8 w-8 items-center justify-center rounded-full bg-black/40 text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100 group-focus-visible:opacity-100 motion-reduce:transition-none" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4h4M20 8V4h-4M4 16v4h4M20 16v4h-4" />
                                </svg>
                            </span>
                        </button>
                    </li>
                @endforeach
            </ul>

            {{-- ライトボックス --}}
            <div x-cloak
                x-show="open"
                x-ref="dialog"
                role="dialog"
                aria-modal="true"
                aria-label="写真の拡大表示"
                @click="onBackdropClick($event)"
                x-transition:enter="transition-opacity duration-200 ease-out motion-reduce:transition-none"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-150 ease-in motion-reduce:transition-none"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[100] flex flex-col bg-gray-950/95 backdrop-blur-sm">

                <div class="flex items-center justify-between px-4 py-3 sm:px-6">
                    <p class="text-sm tabular-nums text-white/70" aria-live="polite">
                        <span x-text="index + 1"></span> / {{ count($photos) }}
                    </p>
                    <button type="button"
                        x-ref="closeButton"
                        data-lightbox-content
                        @click="close()"
                        class="flex h-11 w-11 items-center justify-center rounded-full text-white/80 hover:bg-white/10 hover:text-white transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400"
                        aria-label="閉じる">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>

                <div class="relative flex flex-1 min-h-0 items-center justify-center px-4 pb-6 sm:px-20">
                    <img :src="current.src"
                        :alt="current.alt"
                        data-lightbox-content
                        decoding="async"
                        class="max-h-full max-w-full rounded-lg object-contain shadow-2xl">

                    <button type="button"
                        data-lightbox-content
                        @click="prev()"
                        class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 flex h-11 w-11 items-center justify-center rounded-full bg-black/40 text-white/90 hover:bg-white/15 hover:text-white transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400"
                        aria-label="前の写真">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button type="button"
                        data-lightbox-content
                        @click="next()"
                        class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 flex h-11 w-11 items-center justify-center rounded-full bg-black/40 text-white/90 hover:bg-white/15 hover:text-white transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400"
                        aria-label="次の写真">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <section aria-labelledby="gallery-posting"
            class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8">
            <h2 id="gallery-posting" class="{{ $h2Class }}">写真投稿について</h2>
            <div class="mt-3 mb-5 h-px w-12 bg-sky-500/50" aria-hidden="true"></div>
            <p class="{{ $bodyClass }}">
                メンバーの皆さんは、活動中に撮影した写真をサークルのギャラリーに投稿できます。
                ログイン後、マイページから簡単に投稿可能です。素敵な瞬間をみんなでシェアしましょう！
            </p>
        </section>
    </div>
</x-layout.public>
