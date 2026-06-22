# エピック: リンター導入（静的解析・コード品質自動チェック）

- **slug**: `linter-setup`
- **ブランチ**: `chore/linter`
- **作成日**: 2026-06-22
- **状態**: 完了 <!-- 進行中 / 保留 / 完了 -->

## 概要

PHP の静的解析（Larastan/PHPStan）と JavaScript のリンター・整形（ESLint + Prettier）を導入した。
書いている最中（エディタ）・コミット前・CI の3段階でコード品質を自動チェックできる体制を整え、
後輩が開発環境を開いた瞬間から同じ品質基準で作業できるようにする。

## 決定事項

- PHPStan の level は 5 からスタート。既存コードの型エラーは段階的に解消していく方針とした。
  既存15件は `phpstan-baseline.neon` で凍結し、新規コードにはエラーゼロを維持する。
- ESLint と Prettier は役割を分離する。ESLint がロジックの問題、Prettier が整形を担当。
  `eslint-config-prettier` で ESLint の整形ルールをオフにして競合を防ぐ。
- `.vscode/` をリポジトリに含めて Git 管理する。推奨拡張機能と設定を共有し、
  後輩が Cursor でプロジェクトを開いた瞬間に同じ環境になる。
- Blade ファイルは Prettier の対象外とした（PHP 側の Pint が担当するため重複を避ける）。
- ESLint フラットコンフィグ（`eslint.config.js`）を採用。ESLint 9 の標準形式。

## 前提

- Laravel 本体は `src/` 配下。コマンドはすべて `docker-compose exec app ...` 経由で実行する。
- ホスト Mac に PHP/Node は不要。Docker コンテナ `app`（PHP 8.2 + Node.js 20）で完結する。
- Pint（PHP整形）は導入済みで CI でも動いていた。今回は「静的解析」と「JS リンター」の追加。
- JS ファイルは `src/resources/js/app.js`・`bootstrap.js` の2ファイルのみ（Alpine.js + axios）。

## 学び

- PHPStan 初回実行で18件の既存エラーを検出。うち3件は PHPDoc 誤記（`array<int,string>` → `list<string>`）で即修正できた。
  残り15件は `--generate-baseline` で自動生成した `phpstan-baseline.neon` に凍結。
- `Group::memberRecordOf()` の戻り値型アノテーションがなく、`Model|null` と推論されていた。
  `/** @var UserGroup|null */` を追記するだけで解消。PHPDoc の明示が重要。
- ESLint 9 フラットコンフィグは `.eslintrc` から書き方が大きく変わる（`import` ベース）。
  ブラウザグローバル（`globals.browser`）と Alpine/axios を `globals` に追加する必要がある。
- `eslint-config-prettier` は `...prettierConfig` として必ずルール配列の**最後**に置く。
  これより前のルールと競合する整形ルールを上書きしてオフにする仕組みのため。
- `.vscode/settings.json` の ESLint ワーキングディレクトリを `src` に向けないと、
  ルートから見て `eslint.config.js` が見つからず拡張機能が動かない。
  `"eslint.workingDirectories": [{ "directory": "src", "changeProcessCWD": true }]` が必要。

## 未解決事項

- [ ] phpstan-baseline.neon の15件を段階的に解消する（別 PR で対応予定）
- [ ] PHPStan の level を 5 → 6 → 7 と引き上げていく
- [ ] `npm audit` の脆弱性13件（3 moderate / 8 high / 2 critical）の対応（既存パッケージ起因）

## タスク履歴

- 2026-06-22 001 Larastan/PHPStan 導入・型エラー3件修正・baseline 生成
- 2026-06-22 002 ESLint + Prettier 導入・CSS 整形適用
- 2026-06-22 003 CI 更新・エディタ統合（.vscode/）・ドキュメント更新
- 2026-06-22 004 PR #25 作成（base: dev）
