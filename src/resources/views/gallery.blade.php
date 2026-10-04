<x-layout.public
    title="ギャラリー"
    description="新潟大学釣り同好会のギャラリー。釣行や合宿、釣果など、サークル活動の思い出を写真で紹介します。">

    <x-layout.page-hero
        label="Gallery"
        title="ギャラリー" />

    {{--
      構成（#46）: 説明カード → 写真グリッド → 写真投稿の説明カード。
      - 写真はサイト内の既存写真のみ。alt は他ページで使っている既存の alt を流用し、新しいキャプションは付けない。
      - グリッド: スマホ 2 列（先頭の横長写真だけ 2 列ぶち抜き）/ md 以上 3 列。正方形に揃え object-cover、人物が切れないよう object-position を個別指定。
      - ホバー表現はトップの「活動フォト」（photo-card / welcome-photo-frame / photo-overlay）と同じ。
      - クリック / Enter でライトボックス（resources/js/gallery-lightbox.js）。
      - 説明文の本文は既存の文章。文言は一字一句変えないこと（構造・スタイルのみ変更可）。
    --}}
    @php
        $h2Class = 'text-2xl md:text-3xl font-bold text-white leading-snug break-keep';
        $bodyClass = 'max-w-prose text-base leading-relaxed welcome-text-muted [word-break:auto-phrase]';

        // file: public/images/ のファイル名 / alt: 既存ページの alt / position: サムネイルの object-position
        $photos = [
            ['file' => 'join1.jpg', 'alt' => '入会案内画像1', 'position' => 'object-[center_60%]'],
            ['file' => 'active4.jpg', 'alt' => 'サークルの雰囲気', 'position' => 'object-[center_30%]'],
            ['file' => 'about1.jpg', 'alt' => 'サークル活動の様子', 'position' => 'object-[center_75%]'],
            ['file' => 'active2.jpg', 'alt' => '年間行事の様子', 'position' => 'object-[center_80%]'],
            ['file' => 'join3.jpg', 'alt' => '新歓の流れ画像', 'position' => 'object-[40%_center]'],
            ['file' => 'about2.jpg', 'alt' => '釣りの風景', 'position' => 'object-center'],
            ['file' => 'active1.jpg', 'alt' => '活動の様子', 'position' => 'object-[center_25%]'],
            ['file' => 'join2.jpg', 'alt' => '入会案内画像2', 'position' => 'object-center'],
            ['file' => 'active3.jpg', 'alt' => '普段の活動', 'position' => 'object-center'],
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
                    <li class="{{ $i === 0 ? 'col-span-2 md:col-span-1' : '' }}">
                        <button type="button"
                            x-ref="thumb{{ $i }}"
                            @click="show({{ $i }})"
                            aria-haspopup="dialog"
                            class="group photo-card welcome-photo-frame block w-full {{ $i === 0 ? 'aspect-[4/3] md:aspect-square' : 'aspect-square' }} rounded-xl bg-white/5 cursor-zoom-in focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400 focus-visible:ring-offset-2 focus-visible:ring-offset-gray-900">
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
