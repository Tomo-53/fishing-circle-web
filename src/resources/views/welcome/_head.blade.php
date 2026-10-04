{{--
  公開ページ共通の <head> 後半（トップ welcome.blade.php と下層 <x-layout.public> の両方で使う）。
  favicon / 時間帯テーマの FOUC 防止 / フォント / Google Analytics / Vite + welcome.css。
  <meta charset> / viewport / title / description は呼び出し側で出力する。
--}}
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

{{-- FOUC防止: テーマ適用を最初に行う --}}
<script>
(function(){
    var h = new Date().getHours();
    var t = (h>=5&&h<10)?'dawn':(h>=10&&h<17)?'day':'night';
    document.documentElement.dataset.theme = t;
})();
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&family=Comfortaa:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Google Analytics (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LP4F4GCRFF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-LP4F4GCRFF');
</script>

@php
    $welcomeViteInputs = ['resources/css/app.css', 'resources/js/app.js'];
    $welcomeCssViaVite = false;
    if (file_exists(public_path('hot'))) {
        $welcomeViteInputs[] = 'resources/css/welcome.css';
        $welcomeCssViaVite = true;
    } elseif (file_exists(public_path('build/manifest.json'))) {
        $welcomeManifest = json_decode((string) file_get_contents(public_path('build/manifest.json')), true) ?? [];
        if (isset($welcomeManifest['resources/css/welcome.css'])) {
            $welcomeViteInputs[] = 'resources/css/welcome.css';
            $welcomeCssViaVite = true;
        }
    }
@endphp
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite($welcomeViteInputs)
@endif
{{-- Vite に welcome.css が無いとき（未 build）はソースをそのまま読む。build 後は @vite 側のみ --}}
@unless ($welcomeCssViaVite)
    <style>{!! file_get_contents(resource_path('css/welcome.css')) !!}</style>
@endunless
