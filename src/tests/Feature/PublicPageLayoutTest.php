<?php

// ========================
// 下層公開ページの共通レイアウト（Issue #43）
// <x-layout.public> で共通ヘッダー（welcome/_header）を表示し、現在ページをナビで示す
// ========================

dataset('下層公開ページ', [
    'サークル紹介' => ['about', 'サークル紹介'],
    '活動内容' => ['activities', '活動内容'],
    'ギャラリー' => ['gallery', 'ギャラリー'],
    '入部案内' => ['join', '入部案内'],
]);

test('下層公開ページが共通ヘッダー付きで表示される', function (string $routeName, string $heading) {
    $this->withoutVite()
        ->get(route($routeName))
        ->assertOk()
        ->assertSee('id="site-header"', false)
        ->assertSee('welcome-subpage', false)
        ->assertSee('<title>'.$heading.' - '.config('app.name'), false)
        ->assertSee($heading);
})->with('下層公開ページ');

test('下層公開ページでは現在ページのナビにだけ aria-current が付く', function (string $routeName) {
    $html = $this->withoutVite()
        ->get(route($routeName))
        ->assertOk()
        ->getContent();

    $currentUrl = preg_quote(route($routeName), '/');

    // PC ナビとスマホナビの 2 箇所で現在ページに aria-current="page" が付く
    expect(preg_match_all('/<a href="'.$currentUrl.'"\s+aria-current="page"/', $html))->toBe(2);
    // 他ページのリンクには付かない（合計 2 箇所のみ）
    expect(substr_count($html, 'aria-current="page"'))->toBe(2);
})->with('下層公開ページ');

test('トップページのナビには aria-current が付かない', function () {
    $this->withoutVite()
        ->get(route('welcome'))
        ->assertOk()
        ->assertSee('id="site-header"', false)
        ->assertDontSee('aria-current="page"', false)
        ->assertDontSee('welcome-subpage', false);
});
