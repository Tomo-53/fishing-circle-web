# CLAUDE.md — 新潟大学釣り同好会web

このファイルはプロジェクト全体に適用される指示です。全セッション開始時に読み込まれます。
詳細な規約は `.claude/rules/` に分割しています（ファイル種別ごとに自動ロード）。

## プロジェクト概要

新潟大学釣り同好会の会員制Webサイト。釣果情報の保護・公開制御と、後輩へ引き継げる開発基盤の構築が目的。
コードベース・コメント・コミットメッセージは**日本語**を基本とする。

## アーキテクチャの状態

> **現在の状態（feature/nextjs-frontend-migration ブランチ）**:
> `src/` の Laravel Blade 構成と `frontend/` の Next.js 構成が**並走**している移行期。
> Blade は削除せず残っており、API ルート (`src/routes/api.php`) を追加した状態。
> 詳細は `frontend/CLAUDE.md` を参照。

```
┌────────────────────────────────────────────┐
│  ブラウザ                                   │
│    ├── Next.js :3000  （移行先フロント）     │
│    └── Blade :8000    （既存・並走中）       │
└────────────────────────────────────────────┘
         ↓ JSON + Cookie（Sanctum SPA認証）
┌────────────────────────────────────────────┐
│  Laravel API :8000（src/）                  │
│    ├── routes/api.php   ← NEW              │
│    └── routes/web.php   ← 既存 Blade       │
└────────────────────────────────────────────┘
         ↓
      MySQL
```

## 技術スタック

- **Backend**: Laravel 12 / PHP 8.2+
- **Frontend（移行先）**: Next.js 15 (App Router) + TypeScript + Tailwind CSS 3
- **Frontend（既存・並走）**: Blade + Tailwind CSS 3 + Alpine.js + Vite
- **API 認証**: Laravel Sanctum（SPA Cookie セッション）
- **DB**: MySQL 8.0+（テストは SQLite in-memory）
- **テスト**: Pest 3（PHPUnit ベース）
- **整形/静的解析**: Laravel Pint（PHP整形）/ Larastan（PHP静的解析）/ ESLint + Prettier（JS/CSS）
- **認証**: Laravel Breeze（Blade用）/ Sanctum（API用）
- **環境**: Docker / Docker Compose / Nginx

## ディレクトリ構成

- Laravel 本体はリポジトリ直下ではなく `src/` 配下にある。**パスは常に `src/` から始める**。
- `src/app/` … Models, Http/Controllers（Web + Api 両方）, Http/Middleware, Http/Requests
- `src/routes/web.php` `src/routes/auth.php` … Blade 向けルート
- `src/routes/api.php` … Next.js SPA 向け API ルート（NEW）
- `src/database/migrations/` … スキーマ定義（マイグレーション中心運用）
- `src/resources/views/` … Blade テンプレート（並走中）
- `src/tests/Feature` `src/tests/Unit` … Pest テスト
- `frontend/` … Next.js フロントエンド（移行先）→ 詳細は `frontend/CLAUDE.md`

## 主要コマンド

すべて Docker コンテナ `app` 内で実行する（ホストに PHP/Composer/Node を想定しない）。

```bash
docker-compose up -d                              # 環境起動
docker-compose exec app php artisan test          # テスト実行（Pest）
docker-compose exec app ./vendor/bin/pint         # コード整形（Pint）
docker-compose exec app ./vendor/bin/pint --test  # 整形チェックのみ（CI向け）
docker-compose exec app ./vendor/bin/phpstan analyse --memory-limit=512M  # 静的解析（Larastan）
docker-compose exec app npm run lint              # JSリント（ESLint）
docker-compose exec app npm run lint:fix          # JSリント自動修正
docker-compose exec app npm run format            # JS/CSS整形（Prettier）
docker-compose exec app npm run format:check      # 整形チェックのみ（CI向け）
docker-compose exec app php artisan migrate       # マイグレーション
docker-compose exec app php artisan migrate:status # 状態確認
docker-compose exec app php artisan route:list     # ルート一覧
docker-compose exec app npm run dev                # Blade フロントビルド(開発)
docker-compose exec app npm run build              # Blade フロントビルド(本番)
```

Next.js の起動（`frontend/` で実行、ホスト Node.js 必要）:
```bash
cd frontend && npm install && npm run dev  # http://localhost:3000
```

- Laravel: http://localhost:8000 / phpMyAdmin: http://localhost:8080 / Next.js: http://localhost:3000

## アーキテクチャの要点：4段階ACL（最重要）

グループ単位の権限管理。`UserGroup` ピボット（`user_id` × `group_id`）で**グループごとに独立**して権限を持つ。

| Level | ラベル | 権限 |
|------|--------|------|
| 1 | 認証待機（申請中） | 閲覧不可。承認待ち |
| 2 | 一般メンバー | グループ内コンテンツ閲覧 |
| 3 | 管理者・幹部 | メンバー承認・管理操作 |
| 4 | グループオーナー | 所有者権限・全操作 |

- 権限チェックは `App\Http\Middleware\CheckGroupPermission`（`src/app/Http/Middleware/CheckGroupPermission.php`）に集約。
- ルートでは `->middleware('check.group.permission:LEVEL')` の形で必要レベルを指定する。
- **`user_id` と `group_id` の両方でスコープを切る**こと。グループ横断の権限漏れは重大なセキュリティ欠陥。

## 必ず守るルール

- 変更後は対象範囲のテストを実行し、`./vendor/bin/pint` で整形してからコミットする。
- マイグレーションは**新規ファイルで前進**させる。既存マイグレーションの編集や `migrate:fresh` の本番実行は禁止。
- 機密情報（`.env`、`src/.env`、APP_KEY、メールパスワード）はコミットしない。`.env.example` のみ管理対象。
- 認可は必ずサーバ側（ミドルウェア / Policy / FormRequest）で行う。Blade の表示制御だけに依存しない。
- ユーザー入力は FormRequest でバリデーションする。Mass Assignment は `$fillable` で制御する。

## ワークフロー

- 作業は `dev` から切ったフィーチャーブランチで行う（`main` へ直接コミットしない）。
- 詳細規約は `.claude/rules/`、再利用手順は `.claude/skills/`、専門役割は `.claude/agents/` を参照。
- 機能ごとの決定事項・前提・学びは案件ノート `.claude/epics/<slug>/_epic.md` に蓄積する（着手時 `/epic-new`、完了時 `/epic-done`、次タスク提案 `/next`）。詳細は `epic-knowledge` スキル参照。
- AIエージェント環境の全体像は @.claude/AI_AGENT_GUIDE.md を参照。
