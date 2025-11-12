# 🎣 新潟大学釣り同好会 Web アプリケーション

Laravel + MySQL + Docker を使用した釣りサークル向けWebアプリケーション

## 🌟 機能概要
- **ユーザー登録・認証システム**（Laravel Breeze）
- **グループ管理機能**（サークル・チーム管理）
- **権限ベースアクセス制御**（一般メンバー・幹部・オーナー）
- **釣果報告・ギャラリー**機能
- **レスポンシブデザイン**（スマホ対応）
- **メール送信機能**（パスワードリセット等）

---

## 🚀 初回セットアップガイド

### 📋 前提条件
開発を始める前に、以下がインストールされていることを確認してください：

- **Docker** （推奨：最新版）
- **Docker Compose** （推奨：v2.x以上）
- **Git** 

### 1️⃣ リポジトリのクローンと移動
```bash
git clone https://github.com/Tomo-53/fishing-circle-web.git
cd fishing-circle-web
```

### 2️⃣ 環境変数ファイルの設定

#### ⚠️ **重要**: 2つの`.env`ファイルが必要です

#### **A. プロジェクトルートの`.env`設定（機密情報管理用）**
```bash
# ルートディレクトリでテンプレートをコピー
cp .env.example .env

# .env ファイルを編集
nano .env  # または code .env
```

**ルートの`.env`の内容例：**
```properties
# データベース設定
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=secret_password

# Laravel アプリケーションキー（重要）
APP_KEY=base64:yulPogD7wCQsUyfuCWVpCkL7cFP9w4Llo8aVun/rlak=

# Gmail SMTP設定（パスワードリセット機能用）
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-16-digit-app-password
MAIL_FROM_ADDRESS=your-gmail@gmail.com

# Docker用権限設定
UID=1000
GID=1000
```

#### **B. Laravel用の`.env`設定**
```bash
# src ディレクトリでテンプレートをコピー
cp src/.env.example src/.env

# この設定は通常変更不要（環境変数を参照するため）
```

### 3️⃣ Gmail App Password の取得（メール機能用）

メール送信機能（パスワードリセット等）を使用するには、Gmail App Passwordが必要です：

1. **Googleアカウント設定** にアクセス
2. **セキュリティ** → **2段階認証プロセス** を有効化
3. **アプリパスワード** を生成
4. 16文字のパスワードを取得
5. ルートの`.env`ファイルの`MAIL_PASSWORD`に設定

### 4️⃣ Dockerコンテナの起動
```bash
# 初回ビルド・起動（時間がかかります）
docker-compose up -d --build

# 起動状況確認
docker-compose ps
```

### 5️⃣ Laravel環境の初期化

#### **重要: 順番を守って実行してください**

```bash
# ① Composer依存パッケージのインストール
docker-compose exec app composer install

# ② アプリケーションキーの生成（セキュリティのため必須）
docker-compose exec app php artisan key:generate

# ③ データベースマイグレーション実行
docker-compose exec app php artisan migrate

# ④ 認証システム（Laravel Breeze）のインストール
docker-compose exec app php artisan breeze:install blade

# ⑤ Node.js依存関係のインストールとビルド
docker-compose exec app npm install
docker-compose exec app npm run build
```

### 6️⃣ 動作確認
開発環境が正常に起動したら、以下にアクセスしてください：

- **🌐 Webアプリケーション**: http://localhost:8000
- **🗄️ phpMyAdmin**: http://localhost:8080
  - ユーザー名: `root`
  - パスワード: `secret_password`

---

## 🏗️ プロジェクト構成

### 📊 データベース設計
| テーブル | 説明 |
|----------|------|
| `users` | ユーザー情報（名前、メール、学年） |
| `groups` | グループ情報（サークル・チーム） |
| `user_groups` | ユーザーとグループの関係・権限管理 |

### 🔐 権限システム
| レベル | 名称 | 権限 |
|--------|------|------|
| 1 | 申請中 | グループ参加申請中（承認待ち） |
| 2 | 一般メンバー | 基本機能利用 |
| 3 | 幹部・管理者 | メンバー管理、グループ設定変更 |
| 4 | オーナー | 全権限（グループ削除含む） |

---

## 🛠️ 開発・運用コマンド

### Laravel Artisan コマンド
```bash
# コマンド実行の基本形
docker-compose exec app php artisan [command]

# よく使用するコマンド
docker-compose exec app php artisan migrate:status    # マイグレーション状況確認
docker-compose exec app php artisan route:list       # ルート一覧
docker-compose exec app php artisan make:controller  # コントローラ作成
```

### データベース操作
```bash
# マイグレーション実行
docker-compose exec app php artisan migrate

# 全テーブル削除して再マイグレーション（開発用）
docker-compose exec app php artisan migrate:fresh

# ダミーデータ作成（開発用）
docker-compose exec app php artisan db:seed
```

### キャッシュ・最適化
```bash
# 全キャッシュクリア
docker-compose exec app php artisan optimize:clear

# 本番用最適化
docker-compose exec app php artisan optimize
```

---

## 🔒 セキュリティについて

### ⚠️ **重要な注意事項**
- **`.env`ファイル（機密情報）は絶対にGitにコミットしないでください**
- **Gmail通常パスワードではなく、必ずApp Passwordを使用してください**
- **本番環境では異なるパスワード・設定を使用してください**

### 📁 ファイルセキュリティ
```
📂 Git管理状況
├── ✅ .env.example           # 管理対象（テンプレート）
├── ✅ src/.env.example       # 管理対象（テンプレート）
├── ❌ .env                   # 管理外（機密情報含む）
└── ❌ src/.env               # 管理外（機密情報含む）
```

### 🔐 機密情報の管理
```properties
# ルートの .env（Git管理外）
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop  # App Password（16文字）
APP_KEY=base64:your-app-key        # Laravel暗号化キー

# src/.env（Git管理対象・安全）
MAIL_USERNAME=${MAIL_USERNAME}     # 変数参照のみ
MAIL_PASSWORD=${MAIL_PASSWORD}     # 変数参照のみ
APP_KEY=${APP_KEY}                 # 変数参照のみ
```

---

## 🚀 本番デプロイ（Railway）

### 1. Railway プロジェクト作成
```bash
# Railway CLI インストール
npm install -g @railway/cli

# プロジェクト作成
railway login
railway init
```

### 2. データベース追加
```bash
railway add mysql
```

### 3. 環境変数設定
Railway の管理画面で以下を設定：
```properties
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-app-key-here
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

### 4. デプロイ後の初期化
```bash
# マイグレーション実行
railway run php artisan migrate --force
```

---

## 🔧 トラブルシューティング

### よくある問題と解決方法

#### 1. 🚨 "APP_KEY not set" エラー
```bash
docker-compose exec app php artisan key:generate
```

#### 2. 🚨 データベース接続エラー
```bash
# DBコンテナ再起動
docker-compose restart db

# 接続テスト
docker-compose exec db mysql -u root -psecret_password -e "SHOW DATABASES;"
```

#### 3. 🚨 権限エラー（Permission denied）
```bash
# ストレージ権限修正
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

#### 4. 🚨 メール送信エラー
- Gmail App Passwordが正しく設定されているか確認
- 2段階認証が有効になっているか確認
- `.env`ファイルのメール設定を再確認

#### 5. 🚨 フロントエンドビルドエラー
```bash
# Node.js関連の再インストール
docker-compose exec app rm -rf node_modules package-lock.json
docker-compose exec app npm install
docker-compose exec app npm run build
```

#### 6. 🚨 コンテナが起動しない
```bash
# 全リセット
docker-compose down --volumes
docker-compose up -d --build
```

---

## 🤝 開発チームへの参加

### 新メンバー向けクイックスタート
1. このREADMEの手順に従ってローカル環境を構築
2. http://localhost:8000 でサイト動作確認
3. ユーザー登録・ログインテスト
4. 開発用ブランチの作成・作業開始

### 開発フロー
```bash
# 開発開始
git checkout -b feature/your-feature-name

# 開発・テスト
docker-compose exec app php artisan test

# コミット・プッシュ
git add .
git commit -m "Add: your feature description"
git push origin feature/your-feature-name
```

---

## 📞 サポート・質問

- **Issues**: バグ報告・機能要望は [GitHub Issues](https://github.com/Tomo-53/fishing-circle-web/issues) へ
- **ディスカッション**: 開発に関する質問は [GitHub Discussions](https://github.com/Tomo-53/fishing-circle-web/discussions) へ

---

## 📄 ライセンス

MIT License - 詳細は [LICENSE](LICENSE) ファイルを参照