# セットアップガイド

新潟大学釣り同好会webの開発環境構築と運用手順をまとめたドキュメントです。

## 1. 前提条件

開発開始前に以下をインストールしてください。

- Docker（推奨: 最新版）
- Docker Compose（推奨: v2.x以上）
- Git

## 2. ローカル開発環境の起動手順

## 2.1 リポジトリのクローン

```bash
git clone https://github.com/Tomo-53/fishing-circle-web.git
cd fishing-circle-web
```

## 2.2 .envファイルの準備

このプロジェクトでは2つの.envファイルを使用します。

### A. プロジェクトルートの.env（機密情報を保持）

```bash
cp .env.example .env
```

必要に応じて編集してください。

```properties
# データベース設定
DB_DATABASE=*****
DB_USERNAME=*****
DB_PASSWORD=*****

# Laravelアプリケーションキー
APP_KEY=***********************

# メール設定（Gmail App Password）
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-16-digit-app-password
MAIL_FROM_ADDRESS=your-gmail@gmail.com

# Docker権限設定
UID=1000
GID=1000
```

### B. Laravel用のsrc/.env（Git管理外・機密値は変数参照）

```bash
cp src/.env.example src/.env
```

通常は変更不要です（機密値はルート.envの値を参照）。
`src/.env`はGit管理外、`src/.env.example`のみGit管理対象です。

## 2.3 Dockerコンテナ起動

```bash
docker-compose up -d --build
docker-compose ps
```

## 2.4 Laravel初期化

以下は順番に実行してください。

```bash
# 1) Composer依存関係のインストール
docker-compose exec app composer install

# 2) アプリケーションキー生成
docker-compose exec app php artisan key:generate

# 3) マイグレーション実行
docker-compose exec app php artisan migrate

# 4) フロントエンド依存関係インストールとビルド
docker-compose exec app npm install
docker-compose exec app npm run build
```

※ `breeze:install` は未導入の新規プロジェクト時のみ実行してください。

## 2.5 動作確認

- Webアプリ: http://localhost:8000
- phpMyAdmin: http://localhost:8080
  - ユーザー名: root
  - パスワード: ルート.envのDB_PASSWORD

## 3. .envとセキュリティ設定

## 3.1 Gmail App Passwordの取得

パスワードリセットなどのメール送信にはGmail App Passwordが必要です。

1. Googleアカウント設定を開く
2. セキュリティ -> 2段階認証プロセスを有効化
3. アプリパスワードを生成
4. 発行された16文字を取得
5. ルート.envのMAIL_PASSWORDに設定

## 3.2 セキュリティ注意事項

- .env（機密情報）はGitにコミットしない
- Gmail通常パスワードではなくApp Passwordを使用する
- 本番ではローカルと異なる認証情報を使用する

ファイル管理の方針:

- 管理対象: .env.example, src/.env.example
- 管理対象外: .env, src/.env

機密値の扱い例:

```properties
# ルートの .env（Git管理外）
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=*******
APP_KEY=*******

# src/.env（変数参照）
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
APP_KEY=${APP_KEY}
```

## 4. 本番環境（Railway）デプロイ

## 4.1 Railwayプロジェクト作成

```bash
npm install -g @railway/cli
railway login
railway init
```

## 4.2 データベース追加

```bash
railway add mysql
```

## 4.3 環境変数設定

Railway管理画面で以下を設定します。

```properties
APP_ENV=production
APP_DEBUG=false
APP_KEY=*******
APP_URL=https://your-app.railway.app

MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=your-gmail@gmail.com

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
```

## 4.4 デプロイ後の初期化

```bash
railway run php artisan migrate --force
```

## 5. トラブルシューティング

## 5.1 APP_KEY not set

```bash
docker-compose exec app php artisan key:generate
```

## 5.2 データベース接続エラー

```bash
docker-compose restart db
docker-compose exec db mysql -u root -psecret_password -e "SHOW DATABASES;"
```

`-p` 実行後のパスワードはルート`.env`の `DB_PASSWORD` を入力してください。

## 5.3 権限エラー（Permission denied）

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

## 5.4 メール送信エラー

- Gmail App Passwordの設定値を確認
- Googleアカウントの2段階認証設定を確認
- .envのメール関連値を再確認

## 5.5 フロントエンドビルドエラー

```bash
docker-compose exec app rm -rf node_modules package-lock.json
docker-compose exec app npm install
docker-compose exec app npm run build
```

## 5.6 コンテナ起動失敗

```bash
docker-compose down --volumes
docker-compose up -d --build
```

## 6. 補助コマンド

```bash
# マイグレーション状態
docker-compose exec app php artisan migrate:status

# ルート一覧
docker-compose exec app php artisan route:list

# コントローラ作成
docker-compose exec app php artisan make:controller

# DBリセット
docker-compose exec app php artisan migrate:fresh

# シード投入
docker-compose exec app php artisan db:seed

# キャッシュクリア
docker-compose exec app php artisan optimize:clear
```

## 7. TDD / CI / CD フロー

### 7.1 ブランチ戦略

```
main          ← 本番（Railway が自動デプロイ）
 └─ dev       ← 統合ブランチ（PR 経由でのみ更新）
     └─ feature/xxx  ← 機能開発（ここで TDD サイクルを回す）
```

- 作業は必ず `dev` から `feature/xxx` を切って行う
- `main` へ直接コミット・push しない
- マージ順序: `feature → dev`（PR）→ `dev → main`（PR）

### 7.2 TDD サイクル（ローカル）

```bash
# 1. Pest テストを先に書く（Red）
docker-compose exec app php artisan test   # → 失敗することを確認

# 2. 機能を実装する（Green）
docker-compose exec app php artisan test   # → 全件パスを確認

# 3. 整形してコミット（Refactor）
docker-compose exec app ./vendor/bin/pint  # コード整形
git add . && git commit -m "feat: ..."
```

### 7.3 CI（GitHub Actions）

`.github/workflows/ci.yml` により、以下のタイミングで自動実行されます。

| トリガー | 対象 |
|---------|------|
| push | `main` / `dev` / `feature/**` / `refactor/**` / `hotfix/**` / `chore/**` |
| PR | `main` または `dev` 宛て |

**実行内容（`src/` 配下で実行）:**

1. PHP 8.2 + 必要拡張のセットアップ
2. `composer install`（`vendor/` をキャッシュして高速化）
3. `.env.example` からテスト用 `.env` を生成
4. `./vendor/bin/pint --test`（整形チェック、差分があれば失敗）
5. `php artisan test`（Pest、SQLite in-memory で実行）

> CI に MySQL サービスは不要です。`phpunit.xml` が `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` に上書きするため、本番と同じ `.env.example` をそのまま使えます。

### 7.4 E2E テスト（Laravel Dusk・ラベル起動）

実ブラウザ（Chrome）で画面操作を検証する e2e テストは Laravel Dusk で実装しています。
通常の CI（`ci.yml`）とは分離し、`.github/workflows/e2e.yml` で **PR に `run-e2e` ラベルが付いている時だけ** 実行します。

**起動条件:**

| トリガー | 動作 |
|---------|------|
| PR に `run-e2e` ラベルを付与（`labeled`） | e2e ジョブが起動 |
| ラベル付き PR への push（`synchronize`） | e2e ジョブが再実行 |
| ラベルが無い PR | ジョブごとスキップ（`if: contains(... 'run-e2e')`） |

**初回のみ: ラベルを作成する**

リポジトリに `run-e2e` ラベルが存在しないと付与できません。一度だけ作成します。

```bash
# GitHub CLI で作成（推奨）
gh label create run-e2e --description "この PR で Dusk の e2e テストを実行する" --color 1d76db
```

もしくは GitHub の Web UI（リポジトリ → Issues → Labels → New label）で `run-e2e` を作成します。

**使い方:**

1. PR を作成する
2. PR の右サイドバー（または `gh pr edit <番号> --add-label run-e2e`）で `run-e2e` ラベルを付ける
3. Actions の `E2E (Dusk)` ジョブが起動し、成功/失敗が表示される
4. e2e が不要になったらラベルを外す（以後は走らない）

**CI 内の実行内容（`src/` 配下）:**

1. PHP 8.2 + 拡張、`composer install`
2. ファイル SQLite 用の `.env` を生成（`DB_DATABASE` を `database/dusk.sqlite` に設定）し `php artisan migrate --force`
3. `npm ci` + `npm run build`（Dusk は実ページを読むため実アセットをビルド）
4. `php artisan dusk:chrome-driver --detect`（インストール済み Chrome に追従）
5. `php artisan serve` をバックグラウンド起動
6. `php artisan dusk` を実行（失敗時はスクリーンショット/コンソールログを artifact として保存）

> in-memory SQLite はサーバープロセスとテストプロセスで共有できないため、e2e では **ファイル SQLite** を使います。

**ローカルで Dusk を実行する場合:**

```bash
# 1) e2e 用の環境ファイルを用意（ファイル SQLite を使う）
cp src/.env.dusk.example src/.env.dusk.local
docker-compose exec app php artisan key:generate --show   # 出力された base64:... を .env.dusk.local の APP_KEY に貼り付け

# 2) e2e 用 DB を用意（--env=dusk.local で .env.dusk.local を読み込む）
docker-compose exec app touch database/dusk.sqlite
docker-compose exec app php artisan migrate --env=dusk.local

# 3) 実行
docker-compose exec app php artisan dusk
```

### 7.5 CD（Railway 自動デプロイ）

`main` ブランチへのマージを Railway が検知し、自動でビルド・デプロイします。
Railway 側の設定は以下の手順で行います（一度だけ）。

#### Railway ダッシュボード設定手順

1. [railway.app](https://railway.app) にログインしてプロジェクトを開く
2. サービスの **Settings** → **Source** → **GitHub Repo** を接続
3. **Branch** を `main` に設定
4. **Deploy** タブの **Start Command** に以下を設定:
   ```
   php artisan migrate --force && php-fpm
   ```
5. **Variables** タブで本番環境変数を設定:

```properties
APP_ENV=production
APP_DEBUG=false
APP_KEY=（php artisan key:generate --show で生成）
APP_URL=https://your-app.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-16-digit-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

#### デプロイフロー全体像

```
ローカル TDD
    ↓ push
GitHub Actions CI（Pint + Pest）
    ↓ グリーン → PR マージ → main 更新
Railway 自動ビルド（Nixpacks）
    ↓
php artisan migrate --force
    ↓
本番公開
```