# エピック: Dusk e2e テスト + ラベル起動 CI の導入

<!--
案件ノート（_epic.md）。1 エピック = 1 機能（基本は 1 `feature/` ブランチ）。
タスクを完了するたびに、決定事項・前提・学び・未解決事項をここへ「昇格」して永続化する。
個別タスクファイル（tasks/NNN-*.md）は完了後に削除してよいが、知識はここに残す。
機密（個人情報・釣果の座標・`.env` 値・認証情報）は絶対に書かない（.claude/rules/security.md）。
-->

- **slug**: `e2e-ci-setup`
- **ブランチ**: `chore/ci-cd-tdd-setup`
- **作成日**: 2026-06-22
- **状態**: 進行中 <!-- 進行中 / 保留 / 完了 -->

## 概要

Laravel Dusk を導入し、実ブラウザ（Chrome）による e2e テスト基盤を整える。
GitHub Actions では PR に `run-e2e` ラベルが付いている時だけ e2e を実行し、通常の push では走らせない「ラベル起動」方式を採用した。
既存の CI（`ci.yml`）は変更せず、専用の `e2e.yml` を追加する設計で既存フローへの影響をゼロにする。

## 決定事項

- **フレームワーク: Laravel Dusk**（^8.3）を採用。Pest/PHP と同一言語でテストを書けるためスタック統一を優先した。Playwright も検討したが Node 系の追加は避けた。
- **ラベル起動方式**（`run-e2e`）を採用。毎 push で実行すると重くなるため、意図的に e2e を実行したい PR にのみラベルを付ける運用とした。ラベルが無い PR はジョブごとスキップし、余計な課金・待ち時間を防ぐ。
- **DB は SQLite ファイル**（`database/dusk.sqlite`）を使用。Dusk はサーバープロセスとテストプロセスが分離するため in-memory SQLite は共有不可。ファイル SQLite で両プロセスが同一 DB を参照できるようにした。
- `DatabaseTruncation` でテスト間の状態をリセット。Dusk は DB トランザクションをラップできないため `RefreshDatabase` ではなく Truncation を使う。
- `npm run build`（実アセット）を CI でビルド。Dusk は実ページをブラウザで読むため、Pest 向けのスタブ manifest では不十分。

## 前提

- PHP 8.2 / Laravel 12 / Pest 3（Pest ベースのテストスイート）
- 既存 Pest テストは in-memory SQLite（`phpunit.xml` で `DB_DATABASE=:memory:`）
- `database/.gitignore` が `*.sqlite*` を除外済みのため `dusk.sqlite` は自動的に Git 管理外
- GitHub Actions ランナー（ubuntu-latest）には Chrome が同梱されており、`dusk:chrome-driver --detect` で追従可能
- プロジェクトの Laravel 本体は `src/` 配下（ルートではない）

## 学び

- Dusk の DuskTestCase には `prepare()` を `static` に実装し、`startChromeDriver` を呼ぶ必要がある。Sail 環境では不要（`runningInSail()` で分岐）。
- CI でサーバーが応答する前に `php artisan dusk` が走ると失敗する。`curl` ループで応答待ちを挟むことで安定する。
- `php artisan serve` は `--no-reload` フラグが必要なケースと不要なケースがある。ファイル変更監視を切ることで CI では安定するが、Laravel のバージョンによってはフラグが未サポートの場合もある（e2e.yml でフラグを外した）。
- `.env.dusk.example` を `src/` 直下に置き、ローカルでは `.env.dusk.local` にコピーして使う規約とした（Dusk は `APP_ENV=local` 時に `.env.dusk.local` を自動読込する）。

## 未解決事項

- [ ] ローカルで Docker 内から `composer require --dev "laravel/dusk:^8.3"` を実行し `composer.lock` を更新・コミットする（CI が動くようになる前提条件）。
- [ ] GitHub リポジトリに `run-e2e` ラベルを作成する（`gh label create run-e2e --color 1d76db`）。
- [ ] テスト用 PR に `run-e2e` を付けて CI の `E2E (Dusk)` ジョブが実際に動くことを確認する。
- [ ] 将来: MySQL サービスコンテナへの差し替え（本番 DB との一致度向上）を検討する。
- [ ] 将来: `main`/`dev` マージ時の定期 e2e 実行（`workflow_dispatch` 含む）追加を検討する。

## タスク履歴

- 2026-06-22 001 Laravel Dusk 基盤導入（DuskTestCase・Browser ディレクトリ・`.env.dusk.example`）
- 2026-06-22 002 ラベル起動 CI ワークフロー（`.github/workflows/e2e.yml`）作成
- 2026-06-22 003 サンプル e2e テスト 4 本追加（LoginTest / GuestTest）
- 2026-06-22 004 `SETUP.md` §7.4 に e2e 運用手順を追記
