# Git ワークフロー規約（全セッション適用）

## ブランチ戦略

- 統合ブランチは `dev`、公開ブランチは `main`。
- 新しい作業は必ず `dev` から切る：`feature/<内容>` / `fix/<内容>` / `refactor/<内容>` / `docs/<内容>` / `chore/<内容>`。
- `main` / `dev` へ直接コミット・直接 push しない。PR 経由でマージする。

## コミット

- 粒度は小さく、意味のある単位で。
- メッセージは日本語可。プレフィックス例：`feat:` `fix:` `refactor:` `docs:` `test:` `chore:`。
- コミット前チェック：
  1. `docker-compose exec app ./vendor/bin/pint`（PHP整形）
  2. `docker-compose exec app ./vendor/bin/phpstan analyse --memory-limit=512M`（静的解析）
  3. `docker-compose exec app npm run lint`（JSリント）
  4. `docker-compose exec app npm run format:check`（JS/CSS整形チェック）
  5. `docker-compose exec app php artisan test`（影響範囲のテスト）
- 機密ファイル（`.env` 等）が staged に含まれていないか確認する。

## PR

- ベースは原則 `dev`。
- 変更内容・テスト結果・影響範囲を記載する。
- レビュー観点はセキュリティ（特にACL）・テスト・整形。`security-auditor` / `code-reviewer` エージェントを活用する。

## 禁止

- `git push --force` を `main` / `dev` に対して行わない。
- `migrate:fresh` / `migrate:rollback` を本番・共有DBに対して行わない。
