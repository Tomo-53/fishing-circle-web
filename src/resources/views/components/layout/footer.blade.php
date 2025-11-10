<footer class="bg-gray-900 text-white section">
    <div class="container-custom">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- サークル情報 -->
            <div class="md:col-span-2">
                <h3 class="text-xl font-bold mb-4 text-gradient-ocean">{{ config('app.name') }}</h3>
                <p class="text-gray-300 mb-4 leading-relaxed">
                    私たちは釣りを愛する仲間が集まったサークルです。初心者から上級者まで、みんなで楽しく釣りを楽しんでいます。
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-ocean-400 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-ocean-400 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-ocean-400 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.042-3.441.219-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.690 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.888-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.357-.631-2.749-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.990-5.367 11.990-11.988C24.007 5.367 18.641.001.012.001z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- クイックリンク -->
            <div>
                <h4 class="text-lg font-semibold mb-4">クイックリンク</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('welcome') }}" class="text-gray-300 hover:text-ocean-400 transition-colors duration-200">ホーム</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-ocean-400 transition-colors duration-200">サークル紹介</a></li>
                    <li><a href="{{ route('activities') }}" class="text-gray-300 hover:text-ocean-400 transition-colors duration-200">活動内容</a></li>
                    <li><a href="{{ route('gallery') }}" class="text-gray-300 hover:text-ocean-400 transition-colors duration-200">ギャラリー</a></li>
                    <li><a href="{{ route('join') }}" class="text-gray-300 hover:text-ocean-400 transition-colors duration-200">入部案内</a></li>
                </ul>
            </div>

            <!-- お問い合わせ情報 -->
            <div>
                <h4 class="text-lg font-semibold mb-4">お問い合わせ</h4>
                <ul class="space-y-2 text-gray-300">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-ocean-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        contact@fishing-circle.com
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-ocean-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        東京都港区
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-ocean-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        090-1234-5678
                    </li>
                </ul>
            </div>
        </div>

        <!-- コピーライト -->
        <div class="border-t border-gray-800 mt-8 pt-8 text-center">
            <p class="text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</footer>
