# エピック: Next.js フロントエンド移行

- **slug**: `nextjs-frontend-migration`
- **ブランチ**: `feature/nextjs-frontend-migration`
- **ベース**: `dev`
- **作成日**: 2026-06-23
- **状態**: 進行中（Phase 1 — 動作確認・残ページ実装中）

## 概要

既存 Laravel（Blade SSR）を **Laravel API + Next.js SPA** 構成に移行する。
学習・ポートフォリオ目的。移行期間中は Blade を並走させ、安全に段階移行する。
移行完了後（Phase 4）に Blade を削除し、Next.js 単独構成へ刷新する。

## 決定事項

### リポジトリ・ブランチ戦略

- **現リポジトリで継続**。新リポジトリへの分割・`dev2` ブランチの設置は不採用。
  - 理由: バックエンド（Laravel）は継続利用するため分割の利点がない。移行作業の大半は既に `feature/nextjs-frontend-migration` に存在する。Git 履歴がバックアップの役割を果たすため `dev2` は不要。「刷新後は Blade が消えている」状態は Phase 4 の削除 PR で同一リポジトリ内で実現できる。
- ブランチ運用は既存規約どおり: `feature/*` → PR → `dev` → PR → `main`。
- 直接コミット・force push は `main` / `dev` に対して行わない。

### アーキテクチャ

- バック: Laravel をそのまま API 専用化（`routes/api.php` 追加）。Blade は移行期間中は並走。削除は Phase 4 で判断。
- フロント: `frontend/` に Next.js (App Router + TypeScript + Tailwind) を追加。
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

## ロードマップ

### Phase 1 — 現ブランチの完了（`feature/nextjs-frontend-migration`）

- [ ] Docker 起動後に `composer require laravel/sanctum` && `php artisan migrate` を実行して動作確認
- [ ] `.env` に `SANCTUM_STATEFUL_DOMAINS=localhost:3000` と `FRONTEND_URL=http://localhost:3000` を追加して確認
- [ ] `npm install` && `npm run dev` でフロント起動確認
- [ ] パスワードリセット完了ページ（`/reset-password`）の実装
- [ ] メール確認（`/verify-email`）ページの実装
- [ ] ログイン → グループ作成 → メンバー承認 の E2E 動作確認
- [ ] `pint` で Laravel 側の整形確認（`docker-compose exec app ./vendor/bin/pint --test`）
- [ ] PR を出して `dev` へマージ

### Phase 2 — 品質基盤（フィーチャーブランチを切って対応）

- [ ] CI（`.github/workflows/ci.yml`）に `frontend/` ジョブを追加（lint / type-check / build）。現状は `src/` のみ対象
- [ ] Next.js 側の E2E テスト（Playwright）追加。Dusk は Blade 用として現状維持
- [ ] グループ設定（名前変更）画面など `frontend/CLAUDE.md` の未完了事項の消化

### Phase 3 — 本番移行準備（未決事項として記録）

- [ ] デプロイ・ドメイン設計を決定する。Sanctum SPA Cookie 認証は same-site 前提のため、本番のドメイン構成（例: 同一ドメイン + リバースプロキシ）を決める必要がある
- [ ] 本番環境での動作確認・Staging 相当の確認

### Phase 4 — Blade 削除（将来・時期未定）

**実施判断の基準**:
Next.js 版が本番相当で安定稼働しており、全機能（認証・グループ管理・釣果投稿等）が Blade なしで完結すること。

**手順**:
1. 削除直前に `blade-final` タグを打つ（`git tag blade-final && git push origin blade-final`）
2. `refactor/remove-blade` ブランチを `dev` から切る
3. 削除対象: `src/resources/views/`・`web.php` の Blade ルート・Breeze Blade スキャフォールド・Dusk テスト・Blade 専用 npm パッケージ
4. PR を出して `dev` → `main` へマージ

> Blade 時代のコードは `blade-final` タグと Git 履歴から常時参照可能。新リポジトリを作らなくても「刷新後は Blade が消えている」状態が実現できる。

## 前提

- Laravel 本体は `src/`。Next.js は `frontend/`。
- テストは Pest（API 認可の境界値テストが必須）。
- Sanctum の migration（`personal_access_tokens` テーブル）は `php artisan migrate` で適用する。

## 学び

- Sanctum SPA 認証で `supports_credentials: true` にする場合、`allowed_origins` に `'*'` は使えない（CORS 仕様）。必ず具体的なドメインを列挙する。
- Next.js の SSR（Server Component）から Laravel の Cookie セッションを転送するのは複雑。認証必須ページは Client Component にして `/api/user` で確認する方がシンプル。
- `config/sanctum.php` に `Sanctum::currentApplicationUrlWithPort()` を書くとパッケージ未インストール時にエラーになる。代わりに env 変数のみで構成した。
- 新リポジトリ / dev2 ブランチは採用しない。Git タグで「削除前の状態」を保存し、単一リポジトリで刷新を完結させる方が履歴・CI・ドキュメント基盤を失わずに済む。

## タスク履歴

- 2026-06-23 — `dev` から `feature/nextjs-frontend-migration` を切り出し
- 2026-06-23 — Laravel API 化（Sanctum / CORS / api.php / API コントローラ / Resource）実装
- 2026-06-23 — Next.js `frontend/` 新規作成（全 11 画面 + API クライアント + 認証コンテキスト）
- 2026-06-23 — API 認可テスト追加（AuthApiTest / GroupApiTest で ACL 境界値テスト）
- 2026-06-23 — SETUP.md・CLAUDE.md・frontend/CLAUDE.md にドキュメントを追記
- 2026-07-02 — 開発計画を見直し。リポジトリ戦略を決定し、フェーズ制ロードマップに再編
