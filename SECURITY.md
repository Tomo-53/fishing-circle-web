# 🔒 セキュリティ設定ガイド

## ⚡ クイックセキュリティチェック

### ✅ 必須チェック項目
- [ ] ルート`.env`ファイルが`.gitignore`に含まれている
- [ ] 機密情報が平文でGitにコミットされていない
- [ ] Gmail App Password（16文字）を使用している
- [ ] `src/.env`には変数参照（${VARIABLE}）のみ記載
- [ ] 本番環境で異なるパスワードを使用

---

## 📁 環境変数の管理体系

### ファイル役割分担
```
fishing-circle-web/
├── 🔒 .env                    # 機密情報（Git管理外）
├── ✅ .env.example           # テンプレート（Git管理対象）
└── src/
    ├── 🔒 .env              # 設定ファイル（変数参照のみ）
    └── ✅ .env.example      # テンプレート（Git管理対象）
```

### 機密情報の分離設計
| 環境 | 機密情報の保存場所 | 公開状況 |
|------|-------------------|----------|
| **ローカル** | ルート`.env` | 非公開 |
| **GitHub** | `src/.env`（変数参照のみ） | 公開 |
| **Railway** | 環境変数設定 | 非公開 |

---

## 🔐 機密情報設定の詳細

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

#### `src/.env`ファイル（参照のみ・GitHub公開）
```properties
# 安全な変数参照のみ
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

## 🚀 本番環境でのセキュリティ

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

# HTTPS強制
FORCE_HTTPS=true

# CSRFプロテクション
CSRF_COOKIE_SECURE=true
```

---

## 🛡️ セキュリティベストプラクティス

### 開発フローでの注意点

#### ❌ やってはいけないこと
```bash
# 機密情報を含むファイルをコミット
git add .env                    # 危険！
git add src/.env                # 内容による

# 平文パスワードをコード内に記載
MAIL_PASSWORD=mypassword123     # 危険！

# 本番設定をローカルファイルに記載
APP_ENV=production             # ローカルでは危険
```

#### ✅ 推奨すること
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

#### 機密情報の共有（推奨方法）
```
1. ドキュメント（SECURITY.md）で手順説明
2. 各自で個別に設定取得
3. チーム内での口頭・Slack等での設定支援
4. 共通のテストアカウント作成（開発用）
```

---

## 🔍 セキュリティ診断

### 設定チェックコマンド
```bash
# 1. 機密情報漏洩チェック
git log --oneline | head -10   # 過去のコミットを確認

# 2. ファイル追跡状況確認  
git ls-files | grep .env       # .envが追跡されていないことを確認

# 3. Laravel設定確認
docker-compose exec app php artisan config:show mail
docker-compose exec app php artisan route:list | grep auth
```

### セキュリティ監査チェックリスト
- [ ] `.gitignore`に`.env`が含まれている
- [ ] GitHub上で`.env`ファイルが公開されていない
- [ ] App Passwordが16文字の英数字である
- [ ] 本番環境で`APP_DEBUG=false`に設定
- [ ] 本番環境で`APP_ENV=production`に設定
- [ ] 異なる環境で異なるAPP_KEYを使用
- [ ] HTTPSが有効化されている（本番）

---

## 🆘 セキュリティインシデント対応

### もし機密情報をコミットしてしまった場合

#### 1. 即座に実行すべき対応
```bash
# Git履歴から機密情報を削除
git filter-branch --force --index-filter \
'git rm --cached --ignore-unmatch .env' \
--prune-empty --tag-name-filter cat -- --all

# 強制プッシュ（注意：共同開発の場合は要相談）
git push origin --force --all
```

#### 2. 機密情報の無効化
```
1. Gmail App Passwordを即座に削除・再生成
2. Laravel APP_KEYを再生成
3. データベースパスワード変更（本番環境）
4. チームメンバーへの緊急連絡
```

#### 3. 再発防止策
```
1. .gitignoreの再確認・強化
2. pre-commitフックの導入
3. セキュリティ研修の実施
4. レビュープロセスの強化
```


## 🎯 セキュリティ目標

このプロジェクトでは以下を目指しています：

1. **機密情報の完全分離**: コードと機密情報を明確に分離
2. **チーム開発の安全性**: 複数人開発でも機密情報が漏洩しない仕組み  
3. **本番環境の堅牢性**: 本番環境での適切なセキュリティ設定
4. **継続的改善**: セキュリティ知識の向上と設定の継続的改善


