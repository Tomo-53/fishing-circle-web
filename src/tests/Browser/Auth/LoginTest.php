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
        // 前のテストでログイン済みの場合があるため、クッキーを削除してセッションをリセット
        $browser->driver->manage()->deleteAllCookies();
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'wrong-password')
            ->press('ログイン')
            // フォーム送信後にエラー要素（.text-red-600）が現れるまで待機。
            // 「/login に留まっている＆未認証」という振る舞いで検証する。
            ->waitFor('.text-red-600')
            ->assertPathIs('/login')
            ->assertGuest();
    });
});
