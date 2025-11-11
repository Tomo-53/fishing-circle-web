<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="grade" :value="__('Grade')" />
            <select id="grade" name="grade" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">学年を選択してください</option>
                <option value="B1" {{ old('grade', $user->grade) == 'B1' ? 'selected' : '' }}>B1（学部1年）</option>
                <option value="B2" {{ old('grade', $user->grade) == 'B2' ? 'selected' : '' }}>B2（学部2年）</option>
                <option value="B3" {{ old('grade', $user->grade) == 'B3' ? 'selected' : '' }}>B3（学部3年）</option>
                <option value="B4" {{ old('grade', $user->grade) == 'B4' ? 'selected' : '' }}>B4（学部4年）</option>
                <option value="M1" {{ old('grade', $user->grade) == 'M1' ? 'selected' : '' }}>M1（修士1年）</option>
                <option value="M2" {{ old('grade', $user->grade) == 'M2' ? 'selected' : '' }}>M2（修士2年）</option>
                <option value="D1" {{ old('grade', $user->grade) == 'D1' ? 'selected' : '' }}>D1（博士1年）</option>
                <option value="D2" {{ old('grade', $user->grade) == 'D2' ? 'selected' : '' }}>D2（博士2年）</option>
                <option value="D3" {{ old('grade', $user->grade) == 'D3' ? 'selected' : '' }}>D3（博士3年）</option>
                <option value="OTHER" {{ old('grade', $user->grade) == 'OTHER' ? 'selected' : '' }}>その他</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('grade')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
