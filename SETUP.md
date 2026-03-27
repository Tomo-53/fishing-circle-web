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