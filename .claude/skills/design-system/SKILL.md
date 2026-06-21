---
name: design-system
description: このプロジェクトの Tailwind デザイントークン(色/余白/影/角丸/フォント)と共通 Blade コンポーネントを一貫して使う・拡張する手順。色やサイズの直書きを減らし、画面間の統一感を保つときに使用。
---

# デザインシステム（トークンと共通コンポーネント）

このプロジェクトには [`src/tailwind.config.js`](src/tailwind.config.js) に整備済みのデザイントークンと、`@tailwindcss/forms` ＋ カスタムコンポーネントがある。
**新しい色やサイズを直書きせず、既存トークンに揃える**ことを最優先にする。

## 既定トークン（これを使う）

### 配色（テーマカラー）
| トークン | 用途 | メイン |
|---------|------|--------|
| `ocean-*` | 主要・リンク・プライマリ操作（海） | `ocean-500` / hover `ocean-600` |
| `nature-*` | セカンダリ・成功（自然/緑） | `nature-500` |
| `sunset-*` | アクセント・注意喚起（夕日/橙） | `sunset-500` |
| `warm-*` | 補助の暖色（黄） | `warm-500` |

- 任意の hex（`bg-[#0ea5e9]`）や標準色の乱用（`bg-sky-500` など ocean と重複する色）を避け、上記スケールを使う。
- テキストの濃淡は `text-gray-900/700/500` で階層を作る（本文に薄すぎる色を使わない）。

### フォント
- `font-sans`（Noto Sans JP）= 本文の既定、`font-display`（Comfortaa）= 見出し/ロゴ、`font-serif`（Noto Serif JP）= 限定的に。

### 余白・影・角丸
- 余白は標準スケール + カスタム（`spacing.18/88/128/144`）。セクションは `.section` / `.section-sm`、横コンテナは `.container-custom` を使う。
- 影は `shadow-soft` / `shadow-card` / `shadow-floating` のいずれか。アドホックな `shadow-[...]` を作らない。
- 角丸はカード `rounded-card`、ボタン `rounded-button` に統一。

### モーション
- `animate-fade-in` / `animate-slide-up` / `animate-slide-down` / `animate-bounce-gentle` が定義済み。演出は `ui-motion` スキルの原則に従う。

## 共通コンポーネント（再利用する）

### CSS コンポーネント（tailwind.config.js のプラグイン）
- ボタン: `.btn` + `.btn-primary` / `.btn-secondary` / `.btn-accent` / `.btn-outline`
- カード: `.card`（+ ホバーで浮く `.card-hover`）
- レイアウト: `.container-custom` / `.section` / `.section-sm`

### Blade コンポーネント（`src/resources/views/components/`）
- フォーム: `x-input-label` / `x-text-input` / `x-input-error`
- ボタン: `x-primary-button` / `x-secondary-button` / `x-danger-button`
- UI: `x-modal` / `x-dropdown` / `x-dropdown-link` / `x-nav-link`
- レイアウト: `x-app-layout` / `x-guest-layout`

**新しいボタンやカードを素の Tailwind で組まず、まず上記の再利用を検討する。**

## 拡張するとき

1. まず既存トークン/コンポーネントで足りないか確認する（多くは足りる）。
2. 不足する場合のみ `tailwind.config.js` の `theme.extend` か `addComponents` にトークンとして追加し、命名は既存規則（`ocean-*`, `shadow-soft` など）に揃える。
3. 1箇所限りの値を新トークン化しない（再利用が2回以上見込めるものだけ）。
4. 追加・変更したら、影響する Blade を既存トークンへ寄せ直す。

色のコントラスト要件は `accessibility`、見た目の統一作業は `ui-polish` スキルを併用する。
