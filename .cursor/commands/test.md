# test

Docker コンテナ `app` 内で Pest テストを実行します。

このコマンドの後ろに入力した文字列を引数として扱います（例: `/test Auth`）。

手順:
- 引数が空なら全テスト: `docker-compose exec app php artisan test`
- 引数がディレクトリ風（`/` を含む）ならパス指定: `docker-compose exec app php artisan test <引数>`
- それ以外はフィルタ: `docker-compose exec app php artisan test --filter=<引数>`

結果を要約し、失敗があれば該当テストとエラーの原因・修正方針を示してください。
アサーションを甘くしてテストを通す「ごまかし」は禁止です。
