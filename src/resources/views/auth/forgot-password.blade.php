<x-guest-layout>

    @if (session('status'))
        <!-- 成功メッセージが表示された場合の戻るボタン -->
         <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            パスワードリセットリンクをメールにて送信いたしました。メールをご確認ください。
        </div>
        <div class="flex items-center justify-between mt-4 mb-4">
            <a href="{{ route('login') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← ログイン画面に戻る
            </a>
            <a href="{{ url('/') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ホームに戻る
            </a>
        </div>
    @else
       <div class="mb-4 text-sm text-gray-600">
        パスワード再設定用のリンクをメールにて送信いたします。
         </div>
        <!-- 成功メッセージがない場合のみフォームを表示 -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="メールアドレス" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ← ログイン画面に戻る
                </a>

                <x-primary-button>
                    パスワードリセットリンクを送信
                </x-primary-button>
            </div>
        </form>
    @endif
</x-guest-layout>
