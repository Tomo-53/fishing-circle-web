<?php

use Laravel\Dusk\Browser;

test('未認証ユーザーがダッシュボードへアクセスするとログイン画面へリダイレクトされる', function () {
    $this->browse(function (Browser $browser) {
        // 前テストの認証状態が残らないようにクッキーをリセットする（順序非依存にする）
        freshGuest($browser);
        $browser->visit('/dashboard')
            ->assertPathIs('/login')
            ->assertGuest();
    });
});

test('公開トップページは未認証でも表示できる', function () {
    $this->browse(function (Browser $browser) {
        freshGuest($browser);
        $browser->visit('/')
            ->assertPathIs('/')
            ->assertDontSee('Server Error');
    });
});
