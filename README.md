# Fishing Circle Web

Laravel + Docker を使用した釣りサークル向けWebアプリケーション

## 🚀 セットアップ（推奨手順）

### 1. リポジトリのクローンと移動
```bash
git clone https://github.com/Tomo-53/fishing-circle-web.git
cd fishing-circle-web
```

### 2. 環境設定（重要：2つの.envファイル設定）

#### プロジェクトルートの.env設定
```bash
# Docker用の環境設定
cp .env.example .env

# .envファイルを編集（必要に応じて）
# UID=1000, GID=1000 など
```

#### Laravel用の.env設定
```bash
# Laravel本体の環境設定（重要）
cp src/.env.example src/.env

# データベース設定の確認（通常は変更不要）
# DB_HOST=db
# DB_DATABASE=laravel_db
# DB_USERNAME=laravel_user  
# DB_PASSWORD=secret_password
```

### 3. Dockerコンテナの起動
```bash
# コンテナをビルド・起動（初回ビルドには時間がかかります）
docker-compose up -d --build

# コンテナ状況確認
docker-compose ps
```

### 4. Laravel環境の初期化（重要: 順番厳守）

#### ステップ1: 基本依存関係のインストール
```bash
# ① Composer依存パッケージのインストール
docker-compose exec app composer install

# ② アプリケーションキーの生成（セキュリティのため必須）
docker-compose exec app php artisan key:generate

# ③ 生成されたAPP_KEYの確認
docker-compose exec app cat .env | grep APP_KEY
```

#### ステップ2: データベースの初期化
```bash
# ④ データベースマイグレーション
docker-compose exec app php artisan migrate

# ⑤ マイグレーション状況確認
docker-compose exec app php artisan migrate:status
```

#### ステップ3: Laravel Breezeのセットアップ（認証システム）
```bash
# ⑥ Laravel Breeze認証システムのインストール
docker-compose exec app php artisan breeze:install blade

# ⑦ Node.js依存関係のインストールとビルド
docker-compose exec app npm install
docker-compose exec app npm run build
```



### 4. 動作確認
- **Webアプリケーション**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
  - ユーザー名: `root`
  - パスワード: `secret_password`
- **データベース直接接続**: localhost:3306



## 📊 実装済みデータベース構造

### テーブル一覧
- `users` - ユーザー情報（学年含む）
- `groups` - グループ情報
- `user_groups` - ユーザーとグループの関係（権限管理）
- `migrations` - マイグレーション履歴

### 権限システム
| レベル | 名称 | 説明 |
|--------|------|------|
| 1 | 認証待機 | グループ参加申請中 |
| 2 | 一般メンバー | 通常のメンバー |
| 3 | 幹部・管理者 | メンバー管理権限 |
| 4 | グループオーナー | 全権限 |

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

# 全テーブル削除して再マイグレーション
docker-compose exec app php artisan migrate:fresh

# 開発用ダミーデータ作成
docker-compose exec app php artisan db:seed
```

### キャッシュ・ログ操作
```bash
# 全キャッシュクリア
docker-compose exec app php artisan optimize:clear

# 個別キャッシュクリア
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# アプリケーションログの確認
docker-compose logs app
```

## 🔧 トラブルシューティング

### 1. "APP_KEY not set" エラー
```bash
# 新しいキーを生成
docker-compose exec app php artisan key:generate
```

### 2. データベース接続エラー
```bash
# DBコンテナ再起動
docker-compose restart db

# 接続テスト
docker-compose exec db mysql -u root -psecret_password
```

### 3. 権限エラー
```bash
# ストレージ権限修正
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

### 4. コンテナが起動しない
```bash
# 全コンテナ停止して再起動
docker-compose down
docker-compose up -d --build
```

### 5. フロントエンドビルドエラー
```bash
# Node.js関連の再インストール
docker-compose exec app rm -rf node_modules package-lock.json
docker-compose exec app npm install
docker-compose exec app npm run build
