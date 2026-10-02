<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>サークル紹介 - {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Analytics (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LP4F4GCRFF"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-LP4F4GCRFF');
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-cyan-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl font-bold text-blue-800 mb-8 text-center">サークル紹介</h1>

            <!-- 4つのグリッドレイアウト -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- 左上：新潟大学釣り同好会とは -->
                <div class="bg-white rounded-lg shadow-lg p-8 flex flex-col justify-center">
                    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">新潟大学釣り同好会とは</h2>
                    <div class="text-gray-700 leading-relaxed space-y-4">
                        <p class="font-semibold text-lg text-blue-700">
                            大学公認サークル、設立10年以上、男女合わせて40名以上が在籍する県内随一の大学釣り団体
                        </p>
                        <p>
                            初心者～上級者、男女含めて様々なメンバーが在籍。
                        </p>
                        <p class="text-blue-600 font-medium">
                            一緒に楽しく釣りを！そして釣り技術向上を目指して活動中。
                        </p>
                    </div>
                </div>

                <!-- 右上：画像2 -->
                <div class="bg-white rounded-lg shadow-lg p-4 flex items-center justify-center">
                    <div class="w-full h-64 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/about1.jpg') }}" alt="サークル活動の様子" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- 左下：画像3 -->
                <div class="bg-white rounded-lg shadow-lg p-4 flex items-center justify-center">
                    <div class="w-full h-64 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/about2.jpg') }}" alt="釣りの風景" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- 右下：なぜ釣りなのか -->
                <div class="bg-white rounded-lg shadow-lg p-8 flex flex-col justify-center">
                    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">なぜ釣りなのか</h2>
                    <div class="text-gray-700 leading-relaxed space-y-3 text-sm">
                        <p>
                            新大に初めて来た人は驚いたはず。その海の近さに。
                        </p>
                        <p>
                            大学裏、眼下に広がる日本海。延々とのびるサーフ、水平線の先には鎮座する大いなる島"佐渡"。
                        </p>
                        <p>
                            陸を見れば信濃川と阿賀野川が作り出した広大な平野とそこに点在する潟の数々、豊かな河口域や山々の渓流。そしてここに生きる魚達。
                        </p>
                        <p class="font-semibold text-blue-700">
                            新潟は多様な水辺の王国だ。
                        </p>
                        <p>
                            そして新大の海の近さ、多様な水辺環境は釣りに最高の環境だ。
                        </p>
                        <p class="text-blue-600">
                            さぁ、釣りに行こう。水辺とそこに生きる自然を感じに。<br>
                            魚との出会いを求めて。<br>
                            新たな発見と感動を求めて。
                        </p>
                        <p class="text-center font-bold text-blue-800 italic mt-4">
                            enjoy nature, enjoy fishing
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('welcome') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                    ホームに戻る
                </a>
            </div>
        </div>
    </div>
</body>
</html>
