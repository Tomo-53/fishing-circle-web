<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>活動内容 - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl font-bold text-green-800 mb-8 text-center">活動内容</h1>

            <!-- 8つのグリッドレイアウト -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 items-start">
                <!-- 1. 画像1 -->
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <div class="w-full rounded-lg overflow-hidden">
                        <img src="{{ asset('images/active1.jpg') }}" alt="活動の様子" class="w-full h-auto object-contain">
                    </div>
                </div>

                <!-- 2. 文章1：活動内容 -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col justify-center h-full">
                    <h2 class="text-xl font-bold text-green-800 mb-4">活動内容</h2>
                    <div class="text-gray-700 leading-relaxed space-y-3 text-sm">
                        <p>魚を見て、釣って、学んで、食べて……。釣りや魚に関することは何でもやります！海や川、自然と触れ合い、地球人として成長してみませんか。</p>
                        <p>兼部している人、バイトが忙しい人も大歓迎！テスト期間1週間前からは活動はありません。マイペースに釣りに行けます。基本的に活動は自由参加です。</p>
                        <p>企画は、一人で行くのが難しい釣りや車での釣行、離島での合宿からまったりハゼ釣りまで季節に合ったバラエティに富んだものとなっています。興味のある人はどんどん参加してください！</p>
                    </div>
                </div>

                <!-- 3. 文章2：年間行事 -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col justify-center h-full">
                    <h2 class="text-xl font-bold text-green-800 mb-4">年間行事</h2>
                    <div class="text-gray-700 leading-relaxed space-y-2 text-xs">
                        <p><strong>4月:</strong> お花見会、新歓説明会、新歓食事会</p>
                        <p><strong>5月:</strong> 新歓釣行会（海釣り、マス釣り）</p>
                        <p><strong>6月:</strong> 新歓釣行会（海釣り、マス釣り）、確コン、安全マナー講習会</p>
                        <p><strong>7月:</strong> 部内戦</p>
                        <p><strong>8月:</strong> 浜コン</p>
                        <p><strong>9月:</strong> 夏合宿（粟島などの離島など）</p>
                        <p><strong>10月:</strong> 新大祭出店、佐渡ビックゲーム</p>
                        <p><strong>11月:</strong> 個人遠征など</p>
                        <p><strong>12月:</strong> 忘年会、忘年釣行会（マス釣り）</p>
                        <p><strong>1月:</strong> 水族館、冬合宿</p>
                        <p><strong>2月:</strong> 新潟フィッシングショー</p>
                        <p><strong>3月:</strong> 追いコン</p>
                        <p class="text-green-700 font-medium">大まかにはこんな感じ！釣り会は普段の活動として毎月行ってます！</p>
                        <p class="text-xs text-gray-500">※行事の時期やその内容は年度によって変ります。</p>
                    </div>
                </div>

                <!-- 4. 画像2 -->
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <div class="w-full rounded-lg overflow-hidden">
                        <img src="{{ asset('images/active2.jpg') }}" alt="年間行事の様子" class="w-full h-auto object-contain">
                    </div>
                </div>

                <!-- 5. 画像3 -->
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <div class="w-full rounded-lg overflow-hidden">
                        <img src="{{ asset('images/active3.jpg') }}" alt="普段の活動" class="w-full h-auto object-contain">
                    </div>
                </div>

                <!-- 6. 文章3：普段の活動 -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col justify-center h-full">
                    <h2 class="text-xl font-bold text-green-800 mb-4">普段の活動</h2>
                    <div class="text-gray-700 leading-relaxed space-y-3 text-sm">
                        <div>
                            <h3 class="font-semibold text-green-700 mb-1">〈定例会〉</h3>
                            <p class="text-xs">直近の部員の釣果報告やミーティング、勉強会。月に2回、平日の5限後。場所は図書館グループ学習室。ここで意気投合して即日釣りに！？なんてことも！</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-green-700 mb-1">〈月例釣行会〉</h3>
                            <p class="text-xs">月に1回、県内（主に新潟市内）の釣り場でみんなで仲良く釣り！＆めざせスキルアップ！五十嵐浜キス釣り、日和山堤防釣り、ハゼ釣り、船タイラバ…etc</p>
                        </div>
                    </div>
                </div>

                <!-- 7. 文章4：新大釣りサーの雰囲気・特徴 -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col justify-center h-full">
                    <h2 class="text-xl font-bold text-green-800 mb-4">新大釣りサーの雰囲気・特徴</h2>
                    <div class="text-gray-700 leading-relaxed space-y-2 text-xs">
                        <p>基本的に活動は自由参加。マイペースに参加する人、毎回の活動に参加する人など様々。目標の魚を目指して情熱を燃やす人や、近場でマイペースな釣りをする人、はたまた飲みだけ参加する人も（笑）</p>
                        <p>経験者~初心者まで様々な人がいます。また、多くの人が兼部や、バイトとの掛け持ちをしています。</p>
                        <p>学生間の交流も盛んで、飲み会や食事会は良く行います。誘い合って一緒に釣り行くことは日常茶飯事。</p>
                        <div class="mt-3">
                            <h3 class="font-semibold text-green-700 mb-1">釣りの様子</h3>
                            <p>月例釣行会や新歓釣行・忘年釣行ではみんなでワイワイと楽しく釣りを。その後はみんなでご飯食べに行ったり、釣った魚を料理して食事会をしたり。</p>
                            <p>プライベートでは、車持ちの人の運転やレンタカーで少し遠くのポイントやエリアトラウトなどに行くことも。</p>
                            <p class="text-green-700 font-medium">苦難・喜びを共にし、絆を深めた者達は最高の仲間です！さぁ、釣りをきっかけに最高の仲間をつくろう！</p>
                        </div>
                    </div>
                </div>

                <!-- 8. 画像4 -->
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <div class="w-full rounded-lg overflow-hidden">
                        <img src="{{ asset('images/active4.jpg') }}" alt="サークルの雰囲気" class="w-full h-auto object-contain">
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('welcome') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                    ホームに戻る
                </a>
            </div>
        </div>
    </div>
</body>
</html>
