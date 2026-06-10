---
name: laravel-feature
description: このコードベース(Laravel 12 + src/ 配下)で新機能を end-to-end で追加する手順。モデル/マイグレーション/コントローラ/ルート/ビュー/テストを一貫した規約で作るときに使用。
---

# Laravel 機能追加の手順（このプロジェクト版）

Laravel 本体は `src/` 配下。コマンドは Docker コンテナ `app` 内で実行する。

## 1. ブランチ

`dev` から `feature/<内容>` を切る（`.claude/rules/git-workflow.md`）。

## 2. データモデル / マイグレーション

```bash
docker-compose exec app php artisan make:model Xxx -m   # モデル+マイグレーション
```
- マイグレーションは新規ファイルで前進。`down()` を実装。型・制約・インデックス・外部キーを検討。
- モデルは `$fillable`（Mass Assignment 制御）・`$casts`・リレーションを定義。グループ権限が絡むなら `UserGroup` との関係を確認。

## 3. バリデーション（FormRequest）

```bash
docker-compose exec app php artisan make:request StoreXxxRequest
```
- `rules()` に検証ルール、`authorize()` に認可。コントローラでは `$request->validated()` のみ使う。

## 4. コントローラ（薄く保つ）

```bash
docker-compose exec app php artisan make:controller XxxController
```
- ロジックはモデル/サービスへ。eager load（`with`/`withCount`）で N+1 回避。

## 5. ルート（`src/routes/web.php`）

- 名前付きルート必須。グループ権限が必要なら `check.group.permission:LEVEL` を付ける（詳細は `acl-permission` スキル参照）。

## 6. ビュー（Blade + Tailwind）

- `x-app-layout` を使う。出力は `{{ }}` でエスケープ。フォームに `@csrf`、エラーは `@error`。
- 凝った UI は `frontend-design` スキルを参照。

## 7. テスト（Pest）

```bash
docker-compose exec app php artisan test
```
- Feature テストを追加。グループ権限が絡むなら ACL 境界（権限不足/承認待ち/別グループ/正常）を必ず網羅。

## 8. 仕上げ

```bash
docker-compose exec app ./vendor/bin/pint    # 整形
docker-compose exec app php artisan test     # 回帰
```
- 仕様変更があれば README.md / SETUP.md を更新。
- 大きめの機能は `commander` エージェントや Agent Team で分担すると効率的。
