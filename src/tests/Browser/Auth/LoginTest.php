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
        // 前のテストでログイン済みの場合があるため、クッキーを削除してセッションをリセット。
        // freshGuest() はドメイン上でクッキー削除するため about:blank での no-op を防ぐ。
        freshGuest($browser);
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'wrong-password')
            ->press('ログイン')
            // CSS クラスではなく表示テキストで待機することで Tailwind クラス変更に強くなる。
            // 「/login に留まっている＆未認証」という振る舞いで検証する。
            ->waitForText('メールアドレスまたはパスワードが正しくありません。')
            ->assertPathIs('/login')
            ->assertGuest();
    });
});
