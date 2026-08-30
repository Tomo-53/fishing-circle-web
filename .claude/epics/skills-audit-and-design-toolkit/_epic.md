# エピック: スキル棚卸し＋デザイン/アニメーション系スキル導入

- **slug**: `skills-audit-and-design-toolkit`
- **ブランチ**: `claude/interesting-dubinsky-b6de20`
- **作成日**: 2026-07-06
- **状態**: 進行中 <!-- コミットまで完了。dev への PR は未作成 -->

## 概要

`.claude/skills/` 配下の既存10スキルを「守り（解釈の余地・古さの除去）」「攻め（実績の還流）」の観点で棚卸しした。
調査の結果、フロント開発が本格化する前にデザイン/アニメーション系の実績あるスキルを揃えたいという方針に転換し、
公式・コミュニティで人気の高い外部スキル5本を導入。あわせて棚卸しで見つかった既存スキルの客観的な不具合も同時に修正した。

## 決定事項

- 外部スキルは **npm/CLI のグローバルインストールではなく、上流リポジトリからベンダリング**する方式を採用（`.claude/skills/<name>/` に SKILL.md 一式＋LICENSE.txt を配置）。既存の `frontend-design`/`webapp-testing`（公式スキル）と同じ流儀に揃え、再現性を優先した。
- 導入は3系統5スキル：**ui-ux-pro-max**（nextlevelbuilder/ui-ux-pro-max-skill、約100k★、デザイン方針決めの検索DB）、**design-taste-frontend**（Leonxlnx/taste-skill、約37k★、量産型デザイン防止。11変種中フロント用の1つのみ採用）、**emil-design-eng / review-animations / animation-vocabulary**（emilkowalski/skills、モーション設計・レビュー）。
  - 公式 anthropics/skills の `theme-factory`／`canvas-design` と freshtechbro/claudedesignskills（GSAP/Three.js系）は見送り（ユーザー選定外・依存追加が必要なため）。
- 導入した各スキルの SKILL.md 冒頭に「このプロジェクトでの使い方」注記を追加。**design-system スキルのトークン（ocean/nature/sunset/warm、Noto Sans JP/Comfortaa）を常に優先**させ、React前提の記述は Blade+Tailwind3+Alpine.js に読み替える方針を明記した。
- 棚卸しで見つかった客観的な不具合（実行時エラーになる例・実装と乖離した記述）は、スキルの目的やスコープを変えない範囲で同一ブランチで修正した。スコープを変える提案（webapp-testingの処遇、frontend-designの優先関係）はユーザーに確認の上で対応方針を決定。
- 着地は「ブランチへのコミットまで」。dev への PR 作成は今回のスコープ外（ユーザー判断待ち）。

## 前提

- スキルは main ブランチには存在せず、`dev` ブランチ配下に git 管理されている（`.claude/skills/`、計10→15スキル）。作業前にセッション worktree のブランチを `git reset --hard dev` でローカル dev 起点に付け替えてから着手した。
- `ui-ux-pro-max` は検索スクリプト（`scripts/search.py`、BM25検索）が Python 3 を要求する。Docker コンテナ `app` には Python が無いため、**ホスト側の python3** で実行する運用とした。
- プロジェクトの ACL は `PermissionLevel` enum（`src/app/Enums/PermissionLevel.php`）で比較し、`UserGroup::PERMISSION_LEVEL_*` の int 定数は書き込み・Factory 用の別物という役割分担になっている（既存スキルの例はこの区別を反映していなかった）。
- Laravel の Policy（`src/app/Policies/`）はこのプロジェクトに1つも定義されていない。ACL の表示制御は Policy 経由の `@can` ではなく、`CheckGroupPermission` ミドルウェアが付与する `current_user_group` を見る方式が実態。

## 学び

- 外部スキル導入時は「①ベンダリング＋LICENSE同梱 ②プロジェクト適合の冒頭注記 ③ AI_AGENT_GUIDE.md のスキル一覧更新（出典・スター数・役立ち方・使い分けフロー）」の3点セットで行うと、汎用スキルとプロジェクト固有ルール（design-systemトークン等）の矛盾を防げる。
- スキルの「良い例」は実際に動くコードで検証しないと罠になる。`interface-patterns` の `@can` 例は文法的には正しくても、このプロジェクトに Policy が存在しないため常に false になり「ボタンが表示されない」という気づきにくい不具合を生んでいた。
- `git clone` してスキルスクリプトをテスト実行すると `__pycache__` 等の副産物がステージされることがある。コミット前に生成物混入を確認する必要がある（本件は `git rm -r --cached` で amend 済み）。
- ブランチが正しいベース（ローカル最新 dev）の子孫かどうかは `git merge-base --is-ancestor dev HEAD` で確認できる。差分の見た目のサイズ（挿入行数）は、外部スキルのベンダリングデータ（CSV等）が大半を占めることがあるため、`git diff --stat -- <path>` で内訳を分解して確認すると誤解を避けられる。

## 未解決事項

- [ ] dev への PR を作成するかどうかはユーザー判断待ち（現時点はコミットまでに留めている）。
- [ ] `ui-ux-pro-max` のデータ量（CSV多数・1.8MB）を縮小するか（例: `data/stacks/` を `html-tailwind`/`laravel` のみに絞る）は未検討・未着手。
- [ ] 新スキル候補として挙がった **static-analysis**（PHPStan baseline運用・ESLint設定の知見を linter-setup epic から還流）はドラフトのみで未作成。
- [ ] `webapp-testing` スキルは Python Playwright 前提だが、このプロジェクトの e2e は Laravel Dusk 採用済み（`e2e-ci-setup` エピック参照）。矛盾の注記と `dusk-e2e` スキル新設は本エピックの直前調査で承認方針が決まっていたが、本セッションの後半でユーザー要望が「デザイン/アニメーション系スキルの追加」に転換したため、この対応はまだ実施していない。

## タスク履歴

- 2026-07-06 001 既存10スキルを6項目（発動/再現性/例/完了条件/鮮度/構造）で棚卸し、`.claude/AI_AGENT_GUIDE.md` の記載やプロジェクト実コード（enum・ルート・Policy有無・package.json等）と突き合わせ調査
- 2026-07-06 002 デザイン/アニメーション系スキル5本（ui-ux-pro-max / design-taste-frontend / emil-design-eng / review-animations / animation-vocabulary）を上流からベンダリングし導入、プロジェクト適合注記と AI_AGENT_GUIDE.md を更新
- 2026-07-06 003 棚卸しで確認済みの既存スキル不具合4件（acl-permission / interface-patterns / accessibility / ui-motion）を修正
