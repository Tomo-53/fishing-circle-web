<?php

// ========================
// 開発用 時間帯テーマ切替ボタン（Issue #40）
// 環境名ではなく config('app.theme_preview') で表示を制御する
// ========================

test('theme_preview が false のときトップページにテーマ切替ボタンが出力されない', function () {
    config(['app.theme_preview' => false]);

    $this->withoutVite()
        ->get(route('welcome'))
        ->assertOk()
        ->assertDontSee('data-theme-btn', false);
});

test('theme_preview が true のときトップページにテーマ切替ボタンが出力される', function () {
    config(['app.theme_preview' => true]);

    $this->withoutVite()
        ->get(route('welcome'))
        ->assertOk()
        ->assertSee('data-theme-btn="dawn"', false)
        ->assertSee('data-theme-btn="day"', false)
        ->assertSee('data-theme-btn="night"', false);
});

test('APP_ENV が production 以外でも theme_preview が false ならボタンは出力されない', function () {
    config(['app.env' => 'staging', 'app.theme_preview' => false]);

    $this->withoutVite()
        ->get(route('welcome'))
        ->assertOk()
        ->assertDontSee('data-theme-btn', false);
});
