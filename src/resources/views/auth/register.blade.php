<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Grade -->
        <div class="mt-4">
            <x-input-label for="grade" :value="__('学年')" />
            <select id="grade" name="grade" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">学年を選択してください</option>
                <option value="B1" {{ old('grade') == 'B1' ? 'selected' : '' }}>B1（学部1年）</option>
                <option value="B2" {{ old('grade') == 'B2' ? 'selected' : '' }}>B2（学部2年）</option>
                <option value="B3" {{ old('grade') == 'B3' ? 'selected' : '' }}>B3（学部3年）</option>
                <option value="B4" {{ old('grade') == 'B4' ? 'selected' : '' }}>B4（学部4年）</option>
                <option value="M1" {{ old('grade') == 'M1' ? 'selected' : '' }}>M1（修士1年）</option>
                <option value="M2" {{ old('grade') == 'M2' ? 'selected' : '' }}>M2（修士2年）</option>
                <option value="D1" {{ old('grade') == 'D1' ? 'selected' : '' }}>D1（博士1年）</option>
                <option value="D2" {{ old('grade') == 'D2' ? 'selected' : '' }}>D2（博士2年）</option>
                <option value="D3" {{ old('grade') == 'D3' ? 'selected' : '' }}>D3（博士3年）</option>
                <option value="OTHER" {{ old('grade') == 'OTHER' ? 'selected' : '' }}>その他</option>
            </select>
            <x-input-error :messages="$errors->get('grade')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
