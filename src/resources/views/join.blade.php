<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>入部案内 - {{ config('app.name') }}</title>
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
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-amber-800 mb-6 text-center">入部案内</h1>

            <!-- 4つのグリッドレイアウト -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <!-- 左上：入会は随時受け付け中！ -->
                <div class="bg-white rounded-lg shadow-lg p-8 flex flex-col justify-center">
                    <h2 class="text-2xl font-bold text-amber-700 mb-6">入会は随時受け付け中！</h2>
                    <div class="text-gray-700 leading-relaxed space-y-4">
                        <p>
                            当サークルでは年中メンバー募集中です。<br>
                            新歓時期以外でもOK！また、例年多くの２年生以上の方も入会してます。
                        </p>
                        <p class="font-semibold text-amber-600">
                            経験者～初心者、女子、男子問わず大歓迎！
                        </p>
                        <div class="bg-amber-50 p-4 rounded-lg">
                            <p class="font-bold text-amber-800 mb-2">
                                気になった方はX・InstagramのDMへGo!
                            </p>
                            <p class="text-amber-700">
                                釣りを始めてみたい君、釣りをもっとしたい釣りキチ、入会を待ってるぞ！
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 右上：画像1 -->
                <div class="bg-white rounded-lg shadow-lg p-4 flex items-center justify-center">
                    <img src="{{ asset('images/join1.jpg') }}" alt="入会案内画像1" class="w-full h-64 object-cover rounded-lg">
                </div>

                <!-- 左下：画像2 -->
                <div class="bg-white rounded-lg shadow-lg p-4 flex items-center justify-center">
                    <img src="{{ asset('images/join2.jpg') }}" alt="入会案内画像2" class="w-full h-64 object-cover rounded-lg">
                </div>
                <!-- 右下：サークル入会費について -->
                <div class="bg-white rounded-lg shadow-lg p-8 flex flex-col justify-center">
                    <h2 class="text-2xl font-bold text-amber-700 mb-6">サークル入会費について</h2>
                    <div class="text-gray-700 leading-relaxed space-y-4">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                            <h3 class="text-lg font-bold text-yellow-800 mb-2">・年会費のみ（5000円以下）</h3>
                            <p class="text-yellow-700">
                                いただいた会費は全体活動の費用（エサ代など）や部内貸し出しタックルの整備などに充て、活発な活動や釣り技術向上を目指します。
                            </p>
                        </div>
                    </div>
                </div>


            </div>

            <!-- 新歓の流れセクション（目立つデザイン） -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-amber-800 py-8 px-4 rounded-lg  mb-12">
                <h2 class="text-4xl font-extrabold text-center mb-2 drop-shadow-md"> 新歓の流れ </h2>
                <p class="text-center text-xl font-medium drop-shadow-sm">年間を通した入会サポート</p>
            </div>

            <div class="grid md:grid-cols-5 gap-6 lg:gap-8 items-start mb-12">
                <!-- 左側：join3画像（3/5の幅） -->
                <div class="md:col-span-3 bg-white rounded-lg shadow-lg p-4 h-full flex items-center">
                    <img src="{{ asset('images/join3.jpg') }}" alt="新歓の流れ画像" class="w-full h-full object-contain rounded-lg min-h-[500px]">
                </div>

                <!-- 右側：新歓の流れ内容（2/5の幅） -->
                <div class="md:col-span-2 bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-2xl font-bold text-amber-700 mb-6 text-center">📅 新歓スケジュール</h3>
                    <div class="space-y-4">
                        <!-- 2月～3月 -->
                        <div class="bg-gradient-to-r from-pink-50 to-pink-100 rounded-lg p-4 border-l-4 border-pink-500">
                            <h4 class="text-lg font-bold text-pink-600 mb-2">2月～3月：</h4>
                            <div class="text-gray-700 text-sm space-y-1">
                                <p>二次試験が終わり合格発表🌸</p>
                                <p>部員達も新歓に向けて準備に入ります。4月から始まる新歓の情報を見逃さないようにしよう！</p>
                            </div>
                        </div>

                        <!-- 4月 -->
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border-l-4 border-green-500">
                            <h4 class="text-lg font-bold text-green-600 mb-2">4月：</h4>
                            <ul class="text-gray-700 text-sm space-y-1">
                                <li>• 新歓説明会</li>
                                <li>• 新歓お花見会</li>
                                <li>• 新歓食事会</li>
                            </ul>
                        </div>

                        <!-- 5月 -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border-l-4 border-blue-500">
                            <h4 class="text-lg font-bold text-blue-600 mb-2">5月：</h4>
                            <p class="text-gray-700 text-sm">• 新歓釣行会（in五頭フィッシングパーク）</p>
                        </div>

                        <!-- 6月 -->
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border-l-4 border-purple-500">
                            <h4 class="text-lg font-bold text-purple-600 mb-2">6月：</h4>
                            <ul class="text-gray-700 text-sm space-y-1">
                                <li>• 新歓釣行会（in日和山突堤・五十嵐浜）</li>
                                <li>• 安全・マナー講習会＆確コン</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ホーム画面に戻るボタン -->
            <div class="mt-8 text-center">
                <a href="{{ route('welcome') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                    ホームに戻る
                </a>
            </div>

        </div>
    </div>
</body>
</html>
