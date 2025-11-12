# 🚀 詳細セットアップガイド

> **Note**: 基本的なセットアップ手順は [README.md](README.md) を参照してください。  
> このファイルでは、より詳細な設定や高度な使用方法について説明します。

## � 高度な環境設定

### カスタムポート設定
デフォルトポートが使用されている場合、`docker-compose.yml`を編集してポートを変更できます：

```yaml
services:
  nginx:
    ports:
      - "8080:80"  # デフォルト 8000:80 から変更
  
  phpmyadmin:
    ports:
      - "8081:80"  # デフォルト 8080:80 から変更
  
  db:
    ports:
      - "3307:3306"  # デフォルト 3306:3306 から変更
```

### 開発環境の最適化

#### Xdebugの設定（PHPデバッグ用）
```dockerfile
# docker/php/Dockerfile に追加
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
```

#### Hot Reload設定（フロントエンド開発）
```bash
# Viteの開発サーバー起動
docker-compose exec app npm run dev

# ファイル監視モード
docker-compose exec app npm run watch
```

## �️ データベース詳細管理

### 初期データの投入
```bash
# 基本的なダミーデータ作成
docker-compose exec app php artisan db:seed

# 特定のSeederの実行
docker-compose exec app php artisan db:seed --class=UserSeeder
docker-compose exec app php artisan db:seed --class=GroupSeeder
```

### バックアップとリストア
```bash
# データベースバックアップ
docker-compose exec db mysqldump -u root -psecret_password laravel_db > backup_$(date +%Y%m%d).sql

# リストア
docker-compose exec -T db mysql -u root -psecret_password laravel_db < backup_20241112.sql
```

## 🔒 セキュリティの詳細設定

### 本番環境用の追加設定
```bash
# セッションの暗号化を有効化
SESSION_ENCRYPT=true

# CSRFトークンの強化
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true

# データベース接続の暗号化
DB_ENCRYPT=true
```

### SSL/TLS証明書の設定（本番環境）
```bash
# Let's Encryptを使用する場合
docker run --rm -it \
  -v $(pwd)/ssl:/etc/letsencrypt \
  certbot/certbot certonly \
  --standalone \
  -d your-domain.com
```

## 🚀 パフォーマンスチューニング

### Laravelの最適化
```bash
# 本番環境用最適化（全実行推奨）
docker-compose exec app php artisan optimize
docker-compose exec app php artisan view:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan config:cache
```

### MySQLの設定調整
`docker/mysql/my.cnf` を編集：
```ini
[mysql]
default_character_set = utf8mb4

[mysqld]
character_set_server = utf8mb4
collation_server = utf8mb4_unicode_ci

# パフォーマンス設定
innodb_buffer_pool_size = 256M
query_cache_size = 32M
```

## 🔄 CI/CD設定

### GitHub Actions設定例
`.github/workflows/test.yml`:
```yaml
name: Laravel Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: laravel_test
        ports:
          - 3306:3306

    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
      - name: Install Dependencies
        run: composer install
        working-directory: ./src
      - name: Run Tests
        run: php artisan test
        working-directory: ./src
```

## 📊 監視・ログ設定

### アプリケーションログの設定
```bash
# ログレベルの変更
LOG_LEVEL=info

# ログチャンネルの設定
LOG_CHANNEL=daily  # 日別ログファイル
LOG_STACK=single   # 単一ログファイル
```

### パフォーマンス監視
```bash
# Laravel Telescopeのインストール（開発環境のみ）
docker-compose exec app composer require laravel/telescope --dev
docker-compose exec app php artisan telescope:install
docker-compose exec app php artisan migrate
```

## 🧪 テスト環境

### ユニットテストの実行
```bash
# 全テストの実行
docker-compose exec app php artisan test

# 特定テストの実行
docker-compose exec app php artisan test --filter UserTest
docker-compose exec app php artisan test tests/Feature/AuthTest.php
```

### テストデータベースの設定
`src/.env.testing`:
```properties
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

---

## 🌐 多言語対応

### 言語ファイルの編集
```bash
# 日本語ファイル
src/lang/ja/
├── auth.php
├── pagination.php
├── passwords.php
└── validation.php
```

### 新しい言語の追加
```bash
# 韓国語追加の例
docker-compose exec app php artisan lang:publish --lang=ko
```

---

## 📞 開発サポート

このセットアップで問題が発生した場合：

1. **GitHub Issues** でバグ報告
2. **GitHub Discussions** で技術的質問
3. **README.md** の基本手順を再確認