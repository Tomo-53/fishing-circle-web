---
paths:
  - "src/app/**/*.php"
  - "src/routes/**/*.php"
  - "src/config/**/*.php"
---

# PHP / Laravel コーディング規約

PHP ファイルを編集するときに適用する。

## スタイル

- **Laravel Pint**（`laravel` プリセット）に準拠。編集後 `docker-compose exec app ./vendor/bin/pint <file>` で整形する。
- PSR-12 ベース。インデントは半角スペース4。
- すべての PHP ファイルは `declare(strict_types=1);` は既存に合わせる（現状未使用なら強制しない）。
- クラス・メソッドには簡潔な PHPDoc を付ける。説明コメントは**日本語**で書く（既存コードに倣う）。

## Eloquent / Model

- Mass Assignment は `$fillable` で明示制御する。`$guarded = []` は使わない。
- リレーションは型ヒント付きで定義し、N+1 を避けるため `with()` / `withCount()` で eager load する。
- クエリスコープやアクセサで重複ロジックをまとめる。

## Controller

- 「薄いコントローラ」を保つ。バリデーションは FormRequest、認可は Middleware / Policy、複雑な処理は専用クラスへ。
- リクエスト値は必ず検証済みデータ（`$request->validated()`）を使う。`$request->all()` を直接モデルに渡さない。
- レスポンスのリダイレクトには `->with('status'|'error', ...)` でフラッシュメッセージを付ける（既存パターンに倣う）。

## ルーティング

- 名前付きルート（`->name(...)`）を必須とする。
- グループ権限が必要なルートは `->middleware('check.group.permission:LEVEL')` を付ける。
- ルートモデルバインディングを活用する。

## 禁止事項

- 生 SQL の文字列結合（SQLインジェクション）。クエリビルダ / Eloquent / バインドを使う。
- 既存マイグレーションの破壊的編集。スキーマ変更は新規マイグレーションで行う。
