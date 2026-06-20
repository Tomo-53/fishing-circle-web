---
name: accessibility
description: Blade テンプレートのアクセシビリティ(a11y)監査と修正の手順。フォームのラベル付け、エラーの関連付け、キーボード操作、フォーカス可視化、コントラスト比(WCAG AA)、Alpine 開閉要素の ARIA を整える。フォーム/操作可能な UI/モーダル/ドロップダウンを追加・修正するときに使用。
---

# アクセシビリティ（a11y / WCAG AA）

このプロジェクトの **Blade + Tailwind + Alpine.js** UI を、WCAG 2.x の AA を目安にアクセシブルにする。
会員制サイトは継続利用されるため、キーボード操作とフォーム回りの a11y を特に重視する。

## フォーム（最重要）

- すべての入力に**ラベル**を関連付ける。既存の `x-input-label`（`for`）と入力（`id`）を対応させる。`placeholder` をラベル代わりにしない。
- 必須項目は視覚（`*`）だけでなく `required` 属性でも表す。
- エラーは `x-input-error` で表示し、入力に `aria-invalid="true"` と `aria-describedby="<errorId>"` を付けて関連付ける。
- 関連する入力群は `<fieldset>` + `<legend>` でグループ化する（ラジオ/チェックボックス等）。

```blade
<x-input-label for="title" :value="__('タイトル')" />
<x-text-input id="title" name="title" type="text"
              required
              aria-invalid="@error('title') true @else false @enderror"
              aria-describedby="title-error" />
<x-input-error :messages="$errors->get('title')" id="title-error" class="mt-2" />
```

## キーボード操作とフォーカス

- 操作可能要素はネイティブ要素（`<button>` / `<a href>` / `<input>`）を使う。`<div>` をクリック要素にしない（やむを得ない場合は `role` + `tabindex="0"` + キーイベント）。
- フォーカスリングを消さない。`focus:outline-none` で消したら必ず `focus:ring-2 focus:ring-offset-2` 等の代替可視化を付ける。
- リンクとボタンを使い分ける（遷移=`<a>`、操作=`<button>`）。

## Alpine の開閉 UI（モーダル/ドロップダウン）

- トリガに `:aria-expanded="open"`、対象に `aria-controls` と `id` を付ける。
- モーダルは `role="dialog"` `aria-modal="true"`、開いたらフォーカスを内部へ、`Escape` で閉じる（`x-on:keydown.escape.window`）。`x-trap` があればフォーカストラップに使う。

```blade
<button type="button" x-on:click="open = !open"
        :aria-expanded="open" aria-controls="menu">メニュー</button>
<div id="menu" x-show="open" x-on:keydown.escape.window="open = false" role="menu">…</div>
```

## 画像・アイコン・構造

- 意味のある画像に `alt`、装飾画像は `alt=""`。アイコンのみのボタンは `aria-label` を付ける。
- 見出しは階層順（`h1`→`h2`→`h3`）。スキップしない。ページに `h1` は1つ。
- ランドマーク（`<header> <nav> <main> <footer>`）を使う。

## 色・コントラスト

- 本文テキストとボタンはコントラスト比 **4.5:1 以上**（大きい文字は 3:1）。`text-gray-400` を本文に使わない。
- 情報を**色だけ**で伝えない（エラーは色＋テキスト＋アイコン）。

## prefers-reduced-motion

- 動きは控えめに。詳細は `ui-motion` スキルに従い、`motion-reduce:` で代替する。

## 確認チェックリスト

- [ ] すべての入力にラベルが関連付いている（`for`/`id`）
- [ ] エラーが `aria-describedby` / `aria-invalid` で入力に紐づく
- [ ] Tab だけで全操作でき、フォーカスリングが見える
- [ ] モーダル/メニューに `aria-expanded`/`aria-controls`、Escape で閉じる
- [ ] 画像に適切な `alt`、アイコンボタンに `aria-label`
- [ ] 見出し階層が順序どおり、ランドマークがある
- [ ] 本文・ボタンのコントラストが AA を満たす
- [ ] 情報が色だけに依存していない

実際の表示・キーボード挙動の確認は `webapp-testing` スキルを活用してよい。
