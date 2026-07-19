# AIエージェント開発環境ガイド

新潟大学釣り同好会web 用に整備した Claude Code の AI エージェント開発環境の全体像です。
すべて [Claude Code 公式ドキュメント](https://code.claude.com/docs) の仕様に準拠しています。

## 1. 全体像

| 機構 | 役割 | 公式機能 | 場所 |
|------|------|---------|------|
| **CLAUDE.md** | プロジェクト共通の指示（全セッションに読み込まれる） | [Memory](https://code.claude.com/docs/en/memory) | `CLAUDE.md` |
| **Rules** | ファイル種別ごとに自動ロードされる詳細規約 | [Rules](https://code.claude.com/docs/en/memory) | `.claude/rules/` |
| **Subagents** | 専門役割の独立エージェント（テスター・指揮官 等） | [Subagents](https://code.claude.com/docs/en/sub-agents) | `.claude/agents/` |
| **Skills** | 必要時のみ読み込まれる手順・知識 | [Skills](https://code.claude.com/docs/en/skills) | `.claude/skills/` |
| **Commands** | `/名前` で明示実行するスラッシュコマンド | [Skills/Commands](https://code.claude.com/docs/en/skills) | `.claude/commands/` |
| **Hooks** | ツール実行前後に自動で走る検査・整形 | [Hooks](https://code.claude.com/docs/en/hooks) | `.claude/hooks/` + `settings.json` |
| **Agent Team** | 複数エージェントが協調する並行作業体制 | [Agent Teams](https://code.claude.com/docs/en/agent-teams) | `settings.json`（有効化）+ Subagents |
| **Epics（案件知識）** | 案件ごとに決定/前提/学びを蓄積し、完了のたびに賢くなる | Memory（拡張） | `.claude/epics/` |
| **Settings** | 権限・環境変数・Hooks の設定 | [Settings](https://code.claude.com/docs/en/settings) | `.claude/settings.json` |

## 2. Subagents（Agent Team ロスター）

`.claude/agents/` に9体。`commander` がリード（指揮官）、他は専門ワーカー。

| エージェント | 役割 | 主なツール | モデル | 色 | 備考 |
|-------------|------|-----------|--------|----|----|
| **commander** | 指揮官／統括。分解・委譲・統合 | Agent(各workerへ委譲), Read, Bash | inherit | 紫 | Team リード/`--agent` 用 |
| **laravel-backend** | バックエンド実装（Controller/Model/Middleware） | Read,Edit,Write,Bash,Grep,Glob | inherit | 青 | |
| **frontend-blade** | フロント実装（Blade/Tailwind/Alpine） | + Skill | inherit | 緑 | frontend-design 参照 |
| **pest-tester** | テスター。Pest 作成・実行・解析 | Read,Edit,Write,Bash,Grep,Glob | inherit | 黄 | ACL 境界を必ず網羅 |
| **code-reviewer** | コードレビュー（読み取り専用） | Read,Grep,Glob,Bash | inherit | 水 | `memory: project` で学習蓄積 |
| **ui-reviewer** | UI/UXレビュー（読み取り専用） | Read,Grep,Glob,Bash | inherit | 水 | `memory: project`、デザイン/a11y/一貫性 |
| **security-auditor** | セキュリティ監査（読み取り専用） | Read,Grep,Glob,Bash | inherit | 赤 | `memory: project`、ACL最優先 |
| **db-migrator** | DB／マイグレーション | Read,Edit,Write,Bash,Grep,Glob | inherit | 橙 | 破壊的操作を遮断する frontmatter hook 付き |
| **docs-writer** | ドキュメント | Read,Edit,Write,Grep,Glob | haiku | 桃 | コスト最適化 |

**使い方の例**
- 自動委譲: 「最近の変更をレビューして」→ `code-reviewer` が起動
- 明示指定: `@agent-pest-tester` でテスター起動 / 「security-auditor で認可を監査して」
- 指揮官をメインに: `claude --agent commander`（worker を統括）

**委譲ルーティング規約**: `.claude/rules/agent-routing.md`（常時適用）にトリガー→担当の対応表を定義。実装後は必ず `pest-tester` → `code-reviewer` + `security-auditor` の順で検証する。

## 3. Skills

`.claude/skills/`。`/名前` で明示実行、または関連時に Claude が自動ロード。

| スキル | 種別 | 用途 |
|--------|------|------|
| **webapp-testing** | 公式（anthropics/skills, GitHub） | Playwright でローカルWebアプリを操作・検証・スクショ・ログ確認 |
| **frontend-design** | 公式（anthropics/skills, GitHub） | 平凡でない高品質なフロントUIを実装 |
| **acl-permission** | カスタム | 4段階ACL（グループ権限）の正しい実装・確認手順 |
| **laravel-feature** | カスタム | 新機能を end-to-end で追加する手順（model→…→test） |
| **ui-polish** | カスタム | 既存 Blade の磨き込み（間隔/階層/タイポ/整列の統一） |
| **accessibility** | カスタム | Blade の a11y 監査・修正（ラベル/ARIA/キーボード/コントラスト） |
| **ui-motion** | カスタム | Alpine + Tailwind の節度ある・高性能なマイクロインタラクション |
| **design-system** | カスタム | Tailwind トークンと共通コンポーネントの一貫運用・拡張 |
| **interface-patterns** | カスタム | 一覧/フォーム/詳細/ダッシュボードの画面型紙（空状態・ACL表示制御） |
| **epic-knowledge** | カスタム | 案件ノート(_epic.md)の読込・知識昇格・次タスク提案の手順 |
| **ui-ux-pro-max** | 導入（[nextlevelbuilder/ui-ux-pro-max-skill](https://github.com/nextlevelbuilder/ui-ux-pro-max-skill)・約100k★） | 新規画面の**デザイン方針決め**。67 UIスタイル・161色パレット・フォントペア・UXガイドを BM25 検索して提案（要ホスト python3） |
| **design-taste-frontend** | 導入（[Leonxlnx/taste-skill](https://github.com/Leonxlnx/taste-skill)・約37k★） | LP・トップ・特設ページの**「AI 臭い」量産型デザイン防止**。ブリーフ読解→方向決め→プリフライト検査 |
| **emil-design-eng** | 導入（[emilkowalski/skills](https://github.com/emilkowalski/skills)・animations.dev 主宰） | UI の質感・**モーション判断基準**（動かすべき所/止めるべき所）。ui-motion の作法を実務家の知見で補強 |
| **review-animations** | 導入（同上・手動実行専用） | 実装済みアニメーションの**厳格レビュー**（transform/opacity・時間・イージングの craft bar） |
| **animation-vocabulary** | 導入（同上） | 「あのポップっと出るやつ」→ 正式なモーション用語への**逆引き辞書**（指示の言語化に使う） |

**フロント開発での使い分け（推奨フロー）**
1. 新規画面のデザイン方針決め → **ui-ux-pro-max**（提案は design-system トークンへ寄せる）
2. LP・特設ページの実装 → **design-taste-frontend** ＋ frontend-design ／ 会員機能画面の実装 → **interface-patterns**
3. 動きの設計 → **ui-motion**（作法）＋ **emil-design-eng**（判断基準）
4. 仕上げ → **ui-polish**（静的な磨き）→ `/review-animations`（動きのレビュー）→ ui-reviewer

> 公式の文書系スキル（docx/pdf/pptx/xlsx 等）は Claude 環境に既定で利用可能なため再導入していません。

## 4. Commands（スラッシュコマンド）

すべて `disable-model-invocation: true`（手動実行専用）。

| コマンド | 機能 |
|---------|------|
| `/test [filter\|path]` | Pest 実行（引数でフィルタ/パス指定） |
| `/pint [args]` | Laravel Pint で整形（`--test` で確認のみ） |
| `/migrate [status\|up]` | マイグレーション状態確認/適用（破壊的操作は除外） |
| `/new-feature <name>` | 新機能開発ワークフロー（ブランチ→分担→テスト→レビュー→監査） |
| `/acl-audit <target>` | 指定対象のACL認可を security-auditor で監査 |
| `/epic-new <name>` | 新しい案件ノート（`.claude/epics/<slug>/_epic.md`）を作成 |
| `/epic-done` | 直近の作業から決定/前提/学びを案件ノートへ昇格、完了タスクを整理 |
| `/next` | 案件ノートの未解決事項から次タスクを1つ提案（わんこそば配膳） |

## 5. Hooks

`.claude/settings.json` に登録、スクリプトは `.claude/hooks/`。すべて `jq` で堅牢に JSON 解析。

| イベント | 対象 | スクリプト | 動作 |
|---------|------|-----------|------|
| **SessionStart** | — | `session-context.sh` | 現在ブランチを通知、保護ブランチなら警告 |
| **PreToolUse** | Bash | `block-dangerous.sh` | `rm -rf /`・main/devへのforce push・`--force`破壊的migration・`.env`のgit add を遮断（exit2） |
| **PreToolUse** | Write/Edit | `protect-env.sh` | `.env`系への書込を遮断（`.env.example`は許可） |
| **PostToolUse** | Write/Edit | `pint-format.sh` | 編集した `src/` の PHP を Pint 整形（Docker可なら自動、不可なら整形を促す） |
| **Stop** | — | `epic-reminder.sh` | `feature/` ブランチで案件ノートがあれば、知識昇格（`/epic-done`）を促す（非ブロッキング） |
| **PreToolUse**（db-migrator限定） | Bash | `guard-migrations.sh` | db-migrator の破壊的migration（fresh/reset/rollback/wipe）を遮断 |

- いずれも検証済み：危険コマンドは exit 2 で遮断、通常コマンドは exit 0 で通過。
- Docker がホストPATHに無い環境でも壊れない設計（整形は「促す」にフォールバック）。

## 6. Agent Team（協調型）

`.claude/settings.json` の `env` で **`CLAUDE_CODE_EXPERIMENTAL_AGENT_TEAMS=1`** を設定し有効化（公式: 既定無効の実験的機能、要 v2.1.32+）。

- **使い方**: メインセッションをリードに、「Agent Team を作って、backend/frontend/tester に分担して並行実装して」のように自然言語で指示。
- **teammate の役割**は上記 Subagent 定義を再利用できる（例:「security-reviewer エージェントの定義で teammate を立てて」）。公式仕様上、teammate では subagent の `tools` と `model` が適用され、本文がシステムプロンプトに追記される。
- **Subagent との違い**: Subagent は結果を親に返すだけ。Agent Team は teammate 同士が直接通信し共有タスクリストで自己調整する。レビュー/調査/レイヤ横断の並行作業に有効（トークン消費は増える）。
- 表示モード（in-process / split panes）は `teammateMode` で調整可。tmux/iTerm2 が無くても in-process で動く。

## 7. Rules（path-scoped 自動ロード）

| ファイル | 適用範囲 | 内容 |
|---------|---------|------|
| `security.md` | 常時 | 認可(ACL)・入力出力・機密情報の最優先規約 |
| `git-workflow.md` | 常時 | ブランチ/コミット/PR 規約（dev起点） |
| `php-laravel.md` | `src/app,routes,config/**.php` | PHP/Laravel コーディング規約 |
| `blade-frontend.md` | `views/css/js` | Blade/Tailwind/Alpine 規約 |
| `testing.md` | `tests/factories` | Pest テスト規約（ACL境界必須） |

## 8. Epics（案件知識層 — 完了するたびに賢くなる仕組み）

静的な規約（グローバル `CLAUDE.md` / 領域 `.claude/rules/`）に加え、**案件ごとに動的な知識を蓄積**する層。タスク完了のたびに知識を昇格し、過去の意図を踏まえて動けるようにする。

**3階層の知識**（エージェントは上から順に読み込む）

| 階層 | 役割 | 場所 |
|------|------|------|
| グローバル | 全共通ルール | ルート `CLAUDE.md` |
| 領域 | ファイル種別・領域ごとの規約 | `.claude/rules/` ＋ `src/**/CLAUDE.md` |
| **案件** | その案件の全記録（決定/前提/学び/未解決） | `.claude/epics/<slug>/_epic.md` |

- **単位**: 1 エピック = 1 機能（基本は 1 `feature/` ブランチ）。
- **昇格ループ**: 完了タスクから「決定事項・前提・学び・未解決事項」を抽出し `_epic.md` へ追記。個別タスク `tasks/NNN-*.md` は消えても知識は残る。
- **運用**: 着手時 `/epic-new <name>` → 作業 → `/next` で次タスク提案 → 完了時 `/epic-done` で昇格。手順は `epic-knowledge` スキル、層の説明は `.claude/epics/README.md` を参照。
- **セキュリティ**: 案件ノートは Git 共有資産。個人情報・釣果座標・`.env` 値等の機密は書かない（`.claude/rules/security.md`）。

## 9. 利用上の注意（公式仕様）

- **Hooks と settings.json は信頼ダイアログの承認が必要**。初回起動時にワークスペースを信頼してください（macOS: トラスト確認）。
- ディスク上で agents/skills を追加・編集した場合、反映に**セッション再起動**が必要な場合があります（Skill の本文編集はライブ反映）。
- Hooks のスクリプトは**ホスト側シェル**で実行されます。Docker がPATHにある環境では自動整形まで行います。
- `commander` の `Agent(...)` による直接 spawn は `claude --agent commander` でメインスレッドとして起動した場合に有効です（Subagent は入れ子 spawn 不可）。

## 10. 推奨ワークフロー

```
/new-feature 釣果投稿機能
  → dev から feature/ ブランチ
  → /epic-new 釣果投稿機能（案件ノート作成）
  → db-migrator（スキーマ） → laravel-backend + frontend-blade（実装）
  → pest-tester（テスト, ACL境界） → code-reviewer + security-auditor（検証）
  → /pint（整形） → docs-writer（ドキュメント）
  → /epic-done（決定/学びを案件ノートへ昇格） → PR を dev へ
```
