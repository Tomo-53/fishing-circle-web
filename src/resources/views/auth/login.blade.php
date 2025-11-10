<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- 認証エラーメッセージ -->
    @if ($errors->any() && !$errors->get('email') && !$errors->get('password'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            @foreach ($errors->all() as $error)
                @if (str_contains($error, 'These credentials do not match our records') || str_contains($error, 'credentials'))
                    メールアドレスまたはパスワードが正しくありません。
                @else
                    {{ $error }}
                @endif
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="メールアドレス" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            @if ($errors->get('email'))
                <div class="mt-2 text-sm text-red-600">
                    @foreach ($errors->get('email') as $error)
                        @if ($error === 'The email field is required.')
                            メールアドレスは必須です。
                        @elseif ($error === 'The email field must be a valid email address.')
                            有効なメールアドレスを入力してください。
                        @elseif (str_contains($error, 'These credentials do not match our records') || str_contains($error, 'credentials'))
                            メールアドレスまたはパスワードが正しくありません。
                        @else
                            {{ $error }}
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="パスワード" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            @if ($errors->get('password'))
                <div class="mt-2 text-sm text-red-600">
                    @foreach ($errors->get('password') as $error)
                        @if ($error === 'The password field is required.')
                            パスワードは必須です。
                        @elseif (str_contains($error, 'These credentials do not match our records') || str_contains($error, 'credentials'))
                            メールアドレスまたはパスワードが正しくありません。
                        @else
                            {{ $error }}
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Remember Me -->


        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    パスワードをお忘れですか？
                </a>
            @endif

            <x-primary-button class="ms-3">
                ログイン
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
