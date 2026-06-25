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
- **E2E の自動実行は CI 一本化**。公式どおり `127.0.0.1:8000`（ループバック）＋ ChromeDriver の最小構成で動かす。Selenium / noVNC / 録画コンテナは不要と判断し撤去した。
- **ローカル目視は「全部 Docker」規約の例外**としてホストの Chrome + PHP で `php artisan dusk` を実行する（`http://127.0.0.1:8001` でループバックを使うため HTTPS 問題が起きない）。詳細は SETUP.md §7.4 参照。ポートは Docker nginx が 8000 を占有するため 8001 を使う。
- **ChromeDriver はローカルでは手動起動**が必要（`DUSK_DRIVER_URL=http://localhost:9515` で自動起動をスキップ）。Pest + Symfony Process の非同期起動が WSL2 上で安定しないためで、CI と同じ「明示起動→実行」方式に統一した。
- **テストピラミッド方針**: E2E は全体の 5〜10% に相当する重要経路（ログイン成功・失敗・未認証ガード・公開トップ）のみ。バリデーション等の詳細は Pest Feature 層で担保する。

## 前提

- PHP 8.2 / Laravel 12 / Pest 3（Pest ベースのテストスイート）
- 既存 Pest テストは in-memory SQLite（`phpunit.xml` で `DB_DATABASE=:memory:`）
- `database/.gitignore` が `*.sqlite*` を除外済みのため `dusk.sqlite` は自動的に Git 管理外
- GitHub Actions ランナー（ubuntu-latest）には Chrome が同梱されており、`dusk:chrome-driver --detect` で追従可能
- プロジェクトの Laravel 本体は `src/` 配下（ルートではない）
- ローカル開発環境は WSL2 (Ubuntu) + Docker。ホスト PHP は 8.3（Docker は 8.2）。ホスト Chrome は Google Chrome 149

## 学び

- Dusk の DuskTestCase には `prepare()` を `static` に実装し、`startChromeDriver` を呼ぶ必要がある。Sail 環境では不要（`runningInSail()` で分岐）。CI では `CI=true` 環境変数でスキップし、ワークフロー側で chromedriver を明示起動する。
- CI でサーバーが応答する前に `php artisan dusk` が走ると失敗する。`curl` ループで応答待ちを挟むことで安定する。
- `.env.dusk.example` を `src/` 直下に置き、ローカルでは `.env.dusk.local` にコピーして使う規約とした（Dusk は `APP_ENV=local` 時に `.env.dusk.local` を自動読込する）。
- **Chrome の HTTPS 自動アップグレード問題（教訓）**: `app` のようなホスト名を `http://` で開くと Chrome 94+ が自動的に `https://` へ昇格する。`127.0.0.1`（ループバック）や IP リテラルは昇格しないため、`APP_URL=http://127.0.0.1:8000` を使うことで問題が発生しない。CI はもともとループバックを使っていたため影響ゼロだった。ローカルで独自に組んだ Selenium 可視化スタック（`app:8001` というホスト名）だけが問題の原因だった。
- CI/Linux 環境では `--no-sandbox` フラグが必要（名前空間の制約のため）。headless ブランチに含める。
- `SESSION_DRIVER=database` / `CACHE_STORE=database` のままだと MySQL セッションテーブルへの接続が走り 500 エラーになる。`file` に変更することで解消する（ローカルと CI の `.env.dusk.*` 両方で要確認）。
- **WSL2 上でのホスト PHP 向け必要拡張**: `php8.3-curl`（WebDriver HTTP）・`php8.3-zip`（chromedriver バイナリのダウンロード解凍）が必要。ホストの Chrome + PHP は WSL2 上で動く（WSLg により GUI も可能）。
- **Pest + Dusk で `startChromeDriver()` が WSL2 上で安定しない**: Symfony Process の `start()` は非同期で返り、Pest の `setUpBeforeClass()` ライフサイクルとの組み合わせで ChromeDriver が起動完了前にテストが走ることがある。ポートが `0 ms` で接続拒否される。回避策は `DUSK_DRIVER_URL` を設定して手動起動に切り替えること。
- **Docker が 8000 を占有しているためローカル serve は 8001 を使う**: `artisan serve --port=8001` と `.env.dusk.local` の `APP_URL=http://127.0.0.1:8001` を合わせる。
- **ローカルでのセットアップ手順（3 ターミナル構成）**:
  1. ターミナル A: `php artisan serve --env=dusk.local --host=127.0.0.1 --port=8001`
  2. ターミナル B: `vendor/laravel/dusk/bin/chromedriver-linux --port=9515`
  3. ターミナル C: `php artisan dusk`
- **`dusk:chrome-driver --detect` で作成される `bin/chromedriver-linux64/` はディレクトリ**（空）。実際に使うバイナリは `bin/chromedriver-linux`（composer install 時に取得済み）。

## 未解決事項

- [x] ローカルで `php artisan dusk` が全テスト PASS することを確認する（chromedriver 手動起動 + `DUSK_DRIVER_URL` 設定済み）。→ 2026-06-25 確認済み（4 テスト全 PASS / 8 assertions / 9.55s）
- [ ] GitHub リポジトリに `run-e2e` ラベルを作成する（`gh label create run-e2e --description "この PR で Dusk の e2e テストを実行する" --color 1d76db`）。
- [ ] テスト用 PR に `run-e2e` を付けて CI の `E2E (Dusk)` ジョブが実際に動くことを確認する。
- [x] SETUP.md §7.4 を最新の 3 ターミナル構成（serve / chromedriver / dusk）に更新する。→ 2026-06-25 更新済み
- [ ] 将来: MySQL サービスコンテナへの差し替え（本番 DB との一致度向上）を検討する。
- [ ] 将来: `main`/`dev` マージ時の定期 e2e 実行（`workflow_dispatch` 含む）追加を検討する。

## タスク履歴

- 2026-06-22 001 Laravel Dusk 基盤導入（DuskTestCase・Browser ディレクトリ・`.env.dusk.example`）
- 2026-06-22 002 ラベル起動 CI ワークフロー（`.github/workflows/e2e.yml`）作成
- 2026-06-22 003 サンプル e2e テスト 4 本追加（LoginTest / GuestTest）
- 2026-06-22 004 `SETUP.md` §7.4 に e2e 運用手順を追記
- 2026-06-24 005 ローカル可視 e2e 基盤整備（docker-compose.dusk.yml / scripts/e2e-local.sh / selenium-video 録画 / noVNC）
- 2026-06-24 006 scripts/e2e-local.sh デバッグ: composer install でdusk コマンド解決・SESSION_DRIVER=file 修正・Chrome HTTPS-First Mode 問題を特定
- 2026-06-24 007 DuskTestCase に Chrome HTTPS アップグレード無効化フラグ群を追加（Chrome 149 では未効果）
- 2026-06-24 008 サブエージェント委譲規約整備（.cursor/rules/agent-routing.mdc / .claude/rules/agent-routing.md 新設・各 agent description 強化・CLAUDE.md 委譲セクション追加）
- 2026-06-24 009 E2E を最小構成へ簡素化: ローカル Selenium 可視化スタック撤去（docker-compose.dusk.yml / scripts/e2e-local.sh / noVNC）・DuskTestCase から HTTPS 回避フラグ群を削除して --no-sandbox を追加・テストピラミッド方針を確立・SETUP.md §7.4 をホスト Chrome 公式運用に書き換え・CLAUDE.md に例外を明記
- 2026-06-24 010 ローカル実行デバッグ: php8.3-curl / php8.3-zip インストール・ポート競合（8000→8001）解決・Pest+Dusk の `startChromeDriver()` WSL2 問題を特定→`DUSK_DRIVER_URL` で手動起動方式に変更・`.env.dusk.local` に `DUSK_DRIVER_URL=http://localhost:9515` 追記・APP_URL を 127.0.0.1:8001 に修正
- 2026-06-25 011 ローカル全テスト PASS 確認（4 passed / 8 assertions / 9.55s）・SETUP.md §7.4 を 3 ターミナル構成（serve / chromedriver / dusk）に更新
- 2026-06-25 012 ACL 権限別 e2e テスト追加（`tests/Browser/Group/AclTest.php`）: 7 シナリオ全 PASS（7 passed / 10 assertions / 7.86s）。Level 1/2/3/4 各正常系・異常系・グループ横断アクセス・非メンバーの境界を網羅
