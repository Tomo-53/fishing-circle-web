# migrate

Docker コンテナ `app` 内でマイグレーション操作を行います。

このコマンドの後ろに入力した文字列を引数として扱います（`status` / `up`）。

- `status`（または空）: `docker-compose exec app php artisan migrate:status`
- `up` / `apply`: `docker-compose exec app php artisan migrate`

`migrate:fresh` / `migrate:reset` / `migrate:rollback` は破壊的なため、このコマンドからは実行しません。
必要な場合はリスクを説明し、ユーザーの明示的な確認を得てください。
適用後はスキーマと対応モデル（`$fillable` 等）の整合を確認してください。
