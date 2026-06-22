# エピック: Next.js フロントエンド移行

- **slug**: `nextjs-frontend-migration`
- **ブランチ**: `feature/nextjs-frontend-migration`
- **ベース**: `dev`
- **作成日**: 2026-06-23
- **状態**: 進行中（Sanctum composer install 待ち）

## 概要

既存 Laravel（Blade SSR）を **Laravel API + Next.js SPA** 構成に移行する。
学習・ポートフォリオ目的。Blade 画面は削除せず並走させ、安全に段階移行する。

## 決定事項

### アーキテクチャ
- バック: Laravel をそのまま API 専用化（`routes/api.php` 追加）。Blade は並走・削除しない。
- フロント: `frontend/` に Next.js (App Router + TypeScript + Tailwind) を新規追加。
- 認証方式: **Sanctum SPA Cookie 認証**（同一サイト運用）。JWT / トークン方式は使わない。
  - 理由: CSRF・セッション管理が Laravel 標準のまま使える。フロント側のトークン保管リスクがない。

### API 設計
- エンドポイントのプレフィックスは `/api/`。
- 認可は `check.group.permission:LEVEL` ミドルウェアで、`web.php` と同じ権限レベルを踏襲。
- エラーレスポンスは `{message, code}` 形式の JSON。
  - `code` 例: `NOT_MEMBER` / `PENDING_APPROVAL` / `INSUFFICIENT_PERMISSION`
- バリデーションエラーは Laravel 標準の 422 + `errors` フィールドをそのまま使う。

### CheckGroupPermission の JSON 対応
- `$request->expectsJson()` で API/Web を判定し、API なら JSON、Web なら既存の `abort()/redirect()` を返す。
- `user_id × group_id` の二重スコープは API でも厳守。

### Next.js の認証ガード
- Next.js middleware は Cookie の有無だけを確認（SSR から API を叩けないため）。
- 実際の認証確認は Client Component で `/api/user` を叩く。
- `AuthContext` で user 状態を管理。未認証は `/login` にリダイレクト。

### デザイントークン
- `src/tailwind.config.js` の色（ocean/nature/sunset/warm）・フォント・影・角丸を `frontend/tailwind.config.ts` に移植。既存 Blade との見た目を統一。

## 前提

- Laravel 本体は `src/`。Next.js は `frontend/`。
- テストは Pest（API 認可の境界値テストが必須）。
- `composer require laravel/sanctum` は Docker 起動後に実行が必要（Docker が停止中のため未実行）。
- Sanctum の migration（`personal_access_tokens` テーブル）は `php artisan migrate` で適用する。

## 学び

- Sanctum SPA 認証で `supports_credentials: true` にする場合、`allowed_origins` に `'*'` は使えない（CORS 仕様）。必ず具体的なドメインを列挙する。
- Next.js の SSR（Server Component）から Laravel の Cookie セッションを転送するのは複雑。認証必須ページは Client Component にして `/api/user` で確認する方がシンプル。
- `config/sanctum.php` に `Sanctum::currentApplicationUrlWithPort()` を書くとパッケージ未インストール時にエラーになる。代わりに env 変数のみで構成した。

## 未解決事項（PR 前にやること）

- [ ] Docker 起動後に `composer require laravel/sanctum` && `php artisan migrate` を実行して動作確認
- [ ] `.env` に `SANCTUM_STATEFUL_DOMAINS=localhost:3000` と `FRONTEND_URL=http://localhost:3000` を追加して確認
- [ ] `npm install` && `npm run dev` でフロント起動確認
- [ ] ログイン → グループ作成 → メンバー承認 の E2E 動作確認
- [ ] パスワードリセット完了ページ（`/reset-password`）の実装
- [ ] メール確認（`/verify-email`）ページの実装
- [ ] `pint` で Laravel 側の整形確認（`docker-compose exec app ./vendor/bin/pint --test`）

## タスク履歴

- 2026-06-23 — `dev` から `feature/nextjs-frontend-migration` を切り出し
- 2026-06-23 — Laravel API 化（Sanctum / CORS / api.php / API コントローラ / Resource）実装
- 2026-06-23 — Next.js `frontend/` 新規作成（全 11 画面 + API クライアント + 認証コンテキスト）
- 2026-06-23 — API 認可テスト追加（AuthApiTest / GroupApiTest で ACL 境界値テスト）
- 2026-06-23 — SETUP.md・CLAUDE.md・frontend/CLAUDE.md にドキュメントを追記
