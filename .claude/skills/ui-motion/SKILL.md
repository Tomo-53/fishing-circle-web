---
name: ui-motion
description: Alpine.js の x-transition と Tailwind の transition ユーティリティで、節度がありパフォーマンスの良いマイクロインタラクションを作る手順。ホバー/フォーカス/開閉/ページ内の状態変化に動きを付けるとき、または過剰・カクつくアニメーションを直すときに使用。
---

# UI モーション（マイクロインタラクション）

このプロジェクトの **Alpine.js + Tailwind CSS** で、品よく・軽い動きを付ける。
アニメーションの 12 原則のうち Web UI に効くのは少数（**緩急 / 予期 / 適度な誇張 / 余韻**）。多用は禁物で、**意味のある瞬間に絞る**。

## 原則

- **目的のある動きだけ**: 状態変化（出現/消滅/開閉/フィードバック）を分かりやすくするための動き。装飾的に動かさない。
- **速く・短く**: UI のトランジションは `150〜250ms`（`duration-150`〜`duration-200`）が目安。長いほど鈍く感じる。
- **自然なイージング**: 標準は `ease-out`（入り）/`ease-in`（消え）。リニアは機械的に見える。
- **1画面に主役は1つ**: 同時に多数を動かさない。ページロードは控えめなスタッガ程度に。

## パフォーマンス（最重要）

- アニメーションは **`transform` と `opacity` のみ**を対象にする（GPU 合成で滑らか）。
- `width` / `height` / `top` / `left` / `margin` をアニメさせない（レイアウト再計算でカクつく）。サイズ変化は `transform: scale()` で代替。
- `transition-colors` / `transition-transform` / `transition-opacity` のように**対象を限定**する（`transition-all` は避ける）。

## Tailwind での基本（ホバー/フォーカス）

```blade
<button class="transition-colors duration-150 ease-out
               bg-indigo-600 hover:bg-indigo-700
               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
  送信
</button>
```

## Alpine での開閉（x-transition）

```blade
<div x-show="open"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-1"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-1">
  …
</div>
```

## prefers-reduced-motion（必須配慮）

- 動きを減らす設定のユーザーには動きを抑える。Tailwind の `motion-reduce:` バリアントを使う。

```blade
<div class="transition-transform duration-200 motion-reduce:transition-none motion-reduce:transform-none">…</div>
```

## アンチパターン

- ページ全体が常に動く / 無限ループのアニメ（注意を奪う）。
- 500ms 超の遅いトランジション、過剰なバウンス。
- `transition-all` でレイアウト系プロパティまで動かしてカクつく。
- 動きだけで状態を伝える（色・テキストでも併せて伝える。`accessibility` スキル参照）。

仕上がりの見た目確認は `webapp-testing`、静的な質感の磨きは `ui-polish` に委ねる。
