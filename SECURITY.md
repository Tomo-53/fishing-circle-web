# セキュリティ設定ガイド

## クイックセキュリティチェック

### 必須チェック項目
- [ ] ルート`.env`ファイルが`.gitignore`に含まれている
- [ ] 機密情報が平文でGitにコミットされていない
- [ ] Gmail App Password（16文字）を使用している
- [ ] `src/.env`はGit管理外、`src/.env.example`のみGit管理対象
- [ ] 機密値（APP_KEY/DB_PASSWORD/MAIL_PASSWORD等）は`${VARIABLE}`参照で分離
- [ ] 本番環境で異なるパスワードを使用

---

##  環境変数の管理体系

### ファイル役割分担
```
fishing-circle-web/
├── .env                    # 機密情報（Git管理外）
├── .env.example           # テンプレート（Git管理対象）
└── src/
    ├── .env              # ローカル設定（Git管理外）
    └── .env.example      # テンプレート（Git管理対象）
```

### 機密情報の分離設計
| 環境 | 機密情報の保存場所 | 公開状況 |
|------|-------------------|----------|
| **ローカル** | ルート`.env` | 非公開 |
| **GitHub** | `src/.env.example`（テンプレート） | 公開 |
| **Railway** | 環境変数設定 | 非公開 |

---

##  機密情報設定の詳細

### 1. Gmail App Password の取得

#### Step 1: Google アカウントの設定
```
1. https://myaccount.google.com/ にアクセス
2. 左メニューから「セキュリティ」を選択
3. 「Googleへのログイン」セクションを確認
```

#### Step 2: 2段階認証の有効化（必須）
```
1. 「2段階認証プロセス」をクリック
2. 電話番号またはアプリで認証を設定
3. 「有効にする」をクリック
```

#### Step 3: App Password の生成
```
1. 「2段階認証プロセス」画面で「アプリパスワード」を選択
2. アプリを選択: 「メール」
3. デバイスを選択: 「その他（カスタム名）」
4. 名前を入力: 「釣りサークルWeb」
5. 「生成」をクリック
6. 16文字のパスワードをコピー（例：abcd efgh ijkl mnop）
```

### 2. 環境変数の設定

#### ルート`.env`ファイル（機密情報）
```properties
# データベース設定
DB_DATABASE=******* 
DB_USERNAME=******* 
DB_PASSWORD=******* 

# Laravel アプリケーションキー（必須・機密）
APP_KEY=******* 

# Gmail SMTP設定（機密情報）
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=*******   # ← 16文字のApp Password
MAIL_FROM_ADDRESS=your-email@gmail.com

# Docker権限設定
UID=1000
GID=1000
```

#### `src/.env`（Git管理外）/ `src/.env.example`（GitHub公開）
```properties
# 機密値は変数参照で分離（APP_ENV等の一般設定は固定値でOK）
APP_KEY=${APP_KEY}
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS}"

# 本番環境では変更される値
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
```

---

##  本番環境でのセキュリティ

### Railway での環境変数設定

#### 必須設定項目
```properties
# Laravel基本設定
APP_ENV=production
APP_DEBUG=false
APP_KEY=******* 
APP_URL=https://your-app.railway.app

# メール設定（同じGmailアカウント使用可）
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_FROM_ADDRESS=your-email@gmail.com

# データベース（Railwayが自動設定）
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
```

#### セキュリティ強化設定
```properties
# セッションセキュリティ
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

補足: HTTPS強制は環境変数`FORCE_HTTPS`ではなく、
Laravelの`AppServiceProvider`で`URL::forceScheme('https')`を使って実施します。

---

##  セキュリティベストプラクティス

### 開発フローでの注意点

#### × やってはいけないこと
```bash
# 機密情報を含むファイルをコミット
git add .env                    # 危険！
git add src/.env                # 危険！（Git管理外ファイル）

# 平文パスワードをコード内に記載
MAIL_PASSWORD=mypassword123     # 危険！

# 本番設定をローカルファイルに記載
APP_ENV=production             # ローカルでは危険
```

#### 〇 推奨すること
```bash
# テンプレートファイルの更新
git add .env.example           # 安全
git add src/.env.example       # 安全

# 変数参照の使用
MAIL_PASSWORD=${MAIL_PASSWORD} # 安全

# 環境別設定の分離
ローカル: APP_ENV=local        # 安全
本番: APP_ENV=production       # 安全（Railway設定）
```

### チーム開発での共有方法

#### 新メンバー向けセットアップ
```bash
# 1. テンプレートのコピー
cp .env.example .env
cp src/.env.example src/.env

# 2. 個人の機密情報を設定
# ルート.envファイルを編集：
# - MAIL_USERNAME: 各自のGmail
# - MAIL_PASSWORD: 各自のApp Password
# - APP_KEY: 各自で生成（php artisan key:generate）

# 3. Git追跡確認
git status                      # .envファイルが表示されないことを確認
```


