<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ギャラリー - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl font-bold text-purple-800 mb-6 text-center">ギャラリー</h1>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-semibold text-purple-700 mb-4">📸 活動の思い出</h2>
                <p class="text-gray-700 leading-relaxed mb-6">
                    サークルメンバーが撮影した釣行の様子や、釣果の写真を掲載しています。
                    みんなの素敵な瞬間をお楽しみください！
                </p>
            </div>

            <!-- ギャラリーグリッド -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- 写真プレースホルダー -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-blue-200 to-blue-300 flex items-center justify-center">
                        <div class="text-center text-blue-700">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium">海釣りの様子</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">2024年夏合宿</h3>
                        <p class="text-sm text-gray-600">佐渡島での海釣り</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-green-200 to-green-300 flex items-center justify-center">
                        <div class="text-center text-green-700">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium">川釣りの風景</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">信濃川釣行</h3>
                        <p class="text-sm text-gray-600">アユ釣りに挑戦</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-orange-200 to-orange-300 flex items-center justify-center">
                        <div class="text-center text-orange-700">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium">釣果自慢</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">大物ゲット！</h3>
                        <p class="text-sm text-gray-600">70cm級のブリ</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-red-200 to-red-300 flex items-center justify-center">
                        <div class="text-center text-red-700">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium">BBQの様子</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">釣行後のBBQ</h3>
                        <p class="text-sm text-gray-600">みんなで釣果を味わう</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-teal-200 to-teal-300 flex items-center justify-center">
                        <div class="text-center text-teal-700">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium">新入生歓迎会</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">2024年春の歓迎会</h3>
                        <p class="text-sm text-gray-600">新メンバーと一緒に</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-indigo-200 to-indigo-300 flex items-center justify-center">
                        <div class="text-center text-indigo-700">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium">装備メンテナンス</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">サークル室での活動</h3>
                        <p class="text-sm text-gray-600">道具の手入れ</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-purple-700 mb-4">📝 写真投稿について</h2>
                <p class="text-gray-700">
                    メンバーの皆さんは、活動中に撮影した写真をサークルのギャラリーに投稿できます。
                    ログイン後、マイページから簡単に投稿可能です。素敵な瞬間をみんなでシェアしましょう！
                </p>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('welcome') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                    ホームに戻る
                </a>
            </div>
        </div>
    </div>
</body>
</html>
