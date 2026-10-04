<?php

// ========================
// ギャラリーページ（Issue #46）
// グリッドは軽量サムネイル（images/gallery/thumbs/）、ライトボックスは元画像を使う
// ========================

test('ギャラリーのサムネイルがすべて public に存在する', function () {
    $html = $this->withoutVite()
        ->get(route('gallery'))
        ->assertOk()
        ->getContent();

    preg_match_all('#src="[^"]*?/images/(gallery/thumbs/[^"]+)"#', $html, $matches);

    expect($matches[1])->toHaveCount(25);
    foreach ($matches[1] as $path) {
        expect(file_exists(public_path('images/'.$path)))->toBeTrue("サムネイルがありません: {$path}");
    }
});

test('ギャラリーは未実装の写真投稿機能を利用可能と案内しない', function () {
    $this->withoutVite()
        ->get(route('gallery'))
        ->assertOk()
        ->assertSee('準備中')
        ->assertDontSee('マイページから簡単に投稿可能です');
});
