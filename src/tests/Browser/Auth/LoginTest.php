<?php

use App\Models\User;
use Laravel\Dusk\Browser;

test('登録済みユーザーがログイン画面からログインしてダッシュボードへ遷移できる', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('ログイン')
            ->waitForLocation('/dashboard')
            ->assertPathIs('/dashboard')
            ->assertAuthenticated();
    });
});

test('誤ったパスワードではログインできずログイン画面に留まる', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'wrong-password')
            ->press('ログイン')
            ->waitForText('メールアドレスまたはパスワードが正しくありません。')
            ->assertPathIs('/login')
            ->assertGuest();
    });
});
