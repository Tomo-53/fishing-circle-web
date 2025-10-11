# Fishing Circle Web

Laravel + Docker を使用した釣りサークル向けWebアプリケーション

## 🚀 セットアップ（推奨手順）

### 1. リポジトリのクローンと移動
```bash
git clone https://github.com/Tomo-53/fishing-circle-web.git
cd fishing-circle-web
```

### 2. 環境設定とコンテナの起動
```bash
# .env.exampleをコピーして.envファイルを作成
cp .env.example .env

# .envファイルを編集し、DB設定や権限問題回避のためのUID/GIDを設定
# 例: UID=1000, GID=1000

# コンテナをビルド・起動（初回ビルドには時間がかかります）
docker-compose up -d --build
```

### 3. Laravel環境の初期化（重要: 順番厳守）

#### ステップ1: 基本依存関係のインストール
```bash
# ① Composer依存パッケージのインストール
# ※ 初回起動時はパッケージがインストールされていないため、これが必須
docker-compose exec app composer install

# ② アプリケーションキーの生成
# セッションや暗号化が正常に動作するために必須
docker-compose exec app php artisan key:generate
```

#### ステップ2: Laravel Breezeのセットアップ
```bash
# ③ Laravel Breeze認証システムのインストール
docker-compose exec app php artisan breeze:install blade

# ④ Node.js依存関係のインストールとビルド
docker-compose exec app npm install
docker-compose exec app npm run build
```

#### ステップ3: データベースの初期化
```bash
# ⑤ データベースマイグレーション
# 定義したテーブル構造をDBに反映
docker-compose exec app php artisan migrate

# ⑥ 開発用ダミーデータの作成（オプション）
docker-compose exec app php artisan db:seed

# ⑦ ストレージリンクの作成（ファイルアップロード用）
docker-compose exec app php artisan storage:link
```

#### ステップ4: 最終設定
```bash
# ⑧ 全キャッシュクリア
docker-compose exec app php artisan optimize:clear

# ⑨ 設定の再キャッシュ（本番環境推奨）
docker-compose exec app php artisan config:cache
```

### 4. 動作確認
- **Webアプリケーション**: http://localhost:8000
- **データベース**: localhost:3306


## 🛠️ 開発・運用コマンド

### Laravel Artisanコマンドの実行
```bash
docker-compose exec app php artisan [command]
```

### データベース操作
```bash
# マイグレーション実行
docker-compose exec app php artisan migrate

# マイグレーション状況確認
docker-compose exec app php artisan migrate:status

# マイグレーションロールバック
docker-compose exec app php artisan migrate:rollback

# 全てのテーブルを削除し、再マイグレーション
docker-compose exec app php artisan migrate:fresh

# 再マイグレーション + シーダー実行
docker-compose exec app php artisan migrate:fresh --seed
```

### キャッシュ・ログ操作
```bash
# 全キャッシュクリア（設定、ルート、ビュー）
docker-compose exec app php artisan optimize:clear

# 個別キャッシュクリア
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# アプリケーションログの確認
docker-compose logs app
```

## 🔧 トラブルシューティング（よくある問題）

### 1. "APP_KEY not set" エラー
```bash
docker-compose exec app php artisan key:generate
```

### 2. データベース接続エラー
```bash
# DBコンテナを再起動
docker-compose restart db

# データベースの状況確認
docker-compose exec db mysql -u root -p
```

### 3. 権限エラー
```bash
# 権限エラーが再発する場合（最後の手段: セキュリティリスクあり）
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### 4. Node.js/NPMエラー
```bash
# node_modulesを削除して再インストール
docker-compose exec app rm -rf node_modules
docker-compose exec app npm install
docker-compose exec app npm run build
```

### 5. Composer関連エラー
```bash
# Composerキャッシュクリア
docker-compose exec app composer clear-cache

# vendor削除して再インストール
docker-compose exec app rm -rf vendor
docker-compose exec app composer install
```
