# src/resources/views — Blade テンプレート

UI を構成する Blade テンプレート。サーバ側認可と組み合わせた二重防御でコンテンツを保護する。

## 責務（ディレクトリ別）

| ディレクトリ / ファイル | 責務 |
|---|---|
| `layouts/` | 共通レイアウト（`x-app-layout` / `x-guest-layout`） |
| `components/` | 再利用 Blade コンポーネント |
| `groups/` | グループ一覧・詳細・メンバー管理画面 |
| `auth/` | ログイン・登録・パスワードリセット（Breeze 標準） |
| `profile/` | プロフィール編集 |
| `emails/` | メール通知テンプレート |
| トップ直下 | `dashboard`・`welcome` 等の汎用画面 |

## 重要な実装ルール

- ユーザー入力の出力は `{{ }}` で自動エスケープ。`{!! !!}` は原則禁止（XSS対策）。
- フォームには必ず `@csrf`。状態変更は POST/PATCH/DELETE + `@method('...')` で行う。
- `@can` / `@auth` は表示制御のみ。**サーバ側認可の代替にしない**（ミドルウェア / Policy が本体）。
- Enum の表示には `label()` / `shortLabel()` を使う（例: `{{ $userGroup->permissionLevel->label() }}`）。

## 参照

- Blade/フロントエンド規約 → `.cursor/rules/blade-frontend.mdc`
- セキュリティ規約 → `.cursor/rules/security.mdc`（XSS・CSRF・二重防御の方針）
- ルート CLAUDE.md → `/CLAUDE.md`

## 更新ルール

このディレクトリ配下に新しい画面グループを追加・変更・削除したら、このファイルの責務表も同じ変更で更新すること。
規約の本体は `.cursor/rules/` とルート `CLAUDE.md` にあるため、ここには重複させず参照に留める。
矛盾を見つけたらルート / rules 側を正とし、本ファイルを修正する。
