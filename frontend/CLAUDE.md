# frontend/ — Next.js フロントエンド

Laravel API と通信する SPA フロントエンド。
`feature/nextjs-frontend-migration` ブランチで追加。**Laravel Blade（`src/resources/views/`）と並走中**。

## 責務

| ディレクトリ / ファイル | 責務 |
|---|---|
| `src/app/` | Next.js App Router のページ |
| `src/app/(auth)/` | ゲスト専用ページ（ログイン・登録・パスワードリセット） |
| `src/app/(protected)/` | 認証必須ページ（ダッシュボード・プロフィール・グループ） |
| `src/components/ui/` | 共通 UI コンポーネント（Nav・Alert・PermissionBadge 等） |
| `src/contexts/auth-context.tsx` | 認証状態の Provider（user / login / logout / register） |
| `src/lib/api.ts` | Laravel API 向け fetch ラッパ（CSRF Cookie 取得 + X-XSRF-TOKEN 付与） |
| `src/middleware.ts` | 認証必須ルートの Cookie ガード（302 リダイレクト） |
| `src/types/index.ts` | TypeScript 型定義（User / Group / Member / PermissionLevel 等） |
| `tailwind.config.ts` | デザイントークン（`src/tailwind.config.js` から移植・統一）|

## 認証フロー（Sanctum SPA Cookie 認証）

```
1. GET /sanctum/csrf-cookie  → XSRF-TOKEN Cookie を受け取る
2. POST /api/login           → X-XSRF-TOKEN ヘッダ付きで送信
3. Laravel がセッション Cookie を発行
4. 以降のリクエストは Cookie + X-XSRF-TOKEN で認証される
```

> **重要**: `credentials: 'include'` がないと Cookie が送信されない。
> `lib/api.ts` の `request()` 関数がこれを担保している。

## 認可（ACL）の注意点

- 認可の本体は必ず **Laravel サーバ側**（`CheckGroupPermission` ミドルウェア）。
- Next.js 側の表示制御（`current_user_group.is_owner` 等による UI 出し分け）は **UX 補助のみ**。
- 権限レベルは `src/types/index.ts` の `PERMISSION_LEVEL` で定数化。

## 起動方法

```bash
# .env.local を作成（初回のみ）
cp .env.local.example .env.local
# NEXT_PUBLIC_API_URL=http://localhost:8000 を確認

# 依存インストール
npm install

# 開発サーバ起動
npm run dev   # → http://localhost:3000
```

> **前提**: Laravel（Docker）が :8000 で起動済み、かつ Sanctum が composer install 済みであること。
> Sanctum のインストール手順は `SETUP.md` のセクション 7.3 を参照。

## 環境変数

| キー | 説明 | 例 |
|------|------|----|
| `NEXT_PUBLIC_API_URL` | Laravel API の URL | `http://localhost:8000` |

> **機密情報は含めない**。`.env.local` はリポジトリにコミットしない（`.gitignore` で除外）。
> 管理対象は `.env.local.example` のみ。

## ページ一覧

### 公開（認証不要）
| URL | ファイル | 内容 |
|-----|---------|------|
| `/` | `app/page.tsx` | ウェルカムページ |
| `/about` | `app/about/page.tsx` | サークル紹介 |
| `/activities` | `app/activities/page.tsx` | 活動紹介 |
| `/gallery` | `app/gallery/page.tsx` | ギャラリー |
| `/join` | `app/join/page.tsx` | 入会案内 |

### 認証ページ（ゲスト専用）
| URL | ファイル | 内容 |
|-----|---------|------|
| `/login` | `app/(auth)/login/page.tsx` | ログイン |
| `/register` | `app/(auth)/register/page.tsx` | 新規登録 |
| `/forgot-password` | `app/(auth)/forgot-password/page.tsx` | パスワードリセット |

### 会員ページ（認証必須）
| URL | ファイル | 内容 |
|-----|---------|------|
| `/dashboard` | `app/(protected)/dashboard/page.tsx` | ダッシュボード |
| `/profile` | `app/(protected)/profile/page.tsx` | プロフィール設定 |
| `/groups` | `app/(protected)/groups/page.tsx` | マイグループ一覧 |
| `/groups/all` | `app/(protected)/groups/all/page.tsx` | 全グループ検索 |
| `/groups/create` | `app/(protected)/groups/create/page.tsx` | グループ作成 |
| `/groups/[id]` | `app/(protected)/groups/[id]/page.tsx` | グループ詳細（lv2+）|
| `/groups/[id]/members` | `app/(protected)/groups/[id]/members/page.tsx` | メンバー管理（lv3+）|

## 対応する Laravel API エンドポイント

`src/routes/api.php` に定義。全エンドポイントの認可は Laravel 側で行う。

## 未完了事項

- [ ] Sanctum インストール済み後の動作確認
- [ ] パスワードリセット完了ページ（`/reset-password` ページ）の実装
- [ ] メール確認（verify-email）ページの実装
- [ ] グループ設定（名前変更）画面の実装
- [ ] E2E テスト（Playwright）の追加

## 参照

- Laravel API ルート → `src/routes/api.php`
- 認証ミドルウェア → `src/app/Http/Middleware/CheckGroupPermission.php`
- Sanctum 設定 → `src/config/sanctum.php` / `src/config/cors.php`
- デプロイ手順 → `SETUP.md` セクション 7
