# pint

Docker コンテナ `app` 内で Laravel Pint を実行してコードを整形します。

このコマンドの後ろに入力した文字列を引数として扱います（例: 空＝全体整形 / `--test`＝確認のみ / ファイルパス＝対象限定）。

`docker-compose exec app ./vendor/bin/pint <引数>`

整形結果（変更ファイル数・スタイル違反の有無）を報告してください。
