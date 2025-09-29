# Fishing Circle Web

Laravel + Docker を使用した釣りサークル向けWebアプリケーション

## 🚀 セットアップ

### 1. リポジトリのクローン
```bash
git clone https://github.com/Tomo-53/fishing-circle-web.git
cd fishing-circle-web
```

### 2. 環境設定
```bash
# .env.exampleをコピーして.envファイルを作成
cp .env.example .env
# .envファイルを編集して実際の設定値を入力
```

### 3. Dockerでの起動
```bash
# コンテナをビルド・起動
docker-compose up -d --build

# Laravelの初期設定
docker-compose exec app php artisan migrate
```

### 4. アクセス
- Webアプリケーション: http://localhost:8000
- データベース: localhost:3306

## 📁 プロジェクト構造

```
/
├── docker/              # Docker設定ファイル
├── src/                 # Laravelアプリケーション
├── docker-compose.yml   # Docker Compose設定
├── .env.example         # 環境変数の設定例
└── README.md           # このファイル
```

## 🛠️ 開発

### Laravel Artisanコマンドの実行
```bash
docker-compose exec app php artisan [command]
```

### データベースマイグレーション
```bash
docker-compose exec app php artisan migrate
```

### キャッシュクリア
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```