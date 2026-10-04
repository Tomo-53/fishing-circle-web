@props([
    'label',
    'title',
    'lead' => null,
])
{{--
  下層ページの見出し帯。トップ About セクション見出し（welcome/_about）と同じ形式:
  英字ラベル（sky・字間広め）＋ 日本語見出し（Comfortaa）＋ 細い区切り線 ＋ 任意のリード文。
  ページの <h1> はここで出力する。
--}}
<section {{ $attributes->merge(['class' => 'welcome-page-hero relative overflow-hidden']) }}>

    {{-- 背景の魚シルエット（装飾） --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute -right-10 top-6 w-72 md:w-[28rem]" style="opacity:0.035" viewBox="0 0 400 200" fill="white">
            <ellipse cx="155" cy="100" rx="145" ry="65"/>
            <path d="M290,100 Q345,55 400,20 Q400,180 355,145 Q375,100 290,100 Z"/>
            <circle cx="55" cy="85" r="12" fill="rgba(0,10,30,0.8)"/>
            <circle cx="51" cy="81" r="5" fill="white"/>
        </svg>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-12 md:pt-20 md:pb-16 text-center">
        <p class="text-xs tracking-[0.5em] text-sky-400/70 uppercase mb-3">{{ $label }}</p>
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4"
            style="font-family: 'Comfortaa', 'Noto Sans JP', sans-serif">
            {{ $title }}
        </h1>
        <div class="mx-auto h-px w-16 bg-sky-500/40"></div>
        @if ($lead)
            <p class="mt-6 max-w-2xl mx-auto welcome-text-muted text-sm md:text-base leading-relaxed">
                {{ $lead }}
            </p>
        @endif
    </div>
</section>
