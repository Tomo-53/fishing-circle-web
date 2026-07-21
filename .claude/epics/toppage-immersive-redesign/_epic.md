# エピック: トップページ没入型リデザイン（案1 × 案2 融合）

- **slug**: `toppage-immersive-redesign`
- **ブランチ**: `feature/toppage-immersive-redesign`
- **作成日**: 2026-07-19
- **状態**: 進行中 <!-- PR #29 作成済み。dev マージ・ブラウザ目視確認は未完了 -->
- **PR**: https://github.com/Tomo-53/fishing-circle-web/pull/29

## 概要

Blade 版トップページ（`src/resources/views/welcome.blade.php`）を、静的な左右分割レイアウトから「水面から釣り場へ」をコンセプトにした没入型デザインへ刷新する。
案1（KURUTOGA DIVE 風の波オープニング + 朝/昼/夜テーマ）と案2（視覚・認知心理学の原則）を融合し、公開トップのみをスコープとする。

## 決定事項

- **対象は Blade 版（dev 起点）**。Next.js 版（`feature/nextjs-frontend-migration`）は未マージのため今回は触らない。
- **コンセプトは案1 × 案2 の融合**。案1を演出の柱、案2を全体のデザイン原則にする。
- **スコープはトップページのみ**。about / activities / gallery / join / 会員エリアへの展開は後続。
- **時間帯テーマ**は `html[data-theme="dawn|day|night"]` + CSS カスタムプロパティ（`--sky-from` 等）。
  - 朝 5–10時 / 昼 10–17時 / 夜 17–5時。FOUC 防止のため `<style>` より前のインラインスクリプトで適用。
  - 右下フローティングボタン（🌅☀️🌙）と右上天体ボタンは **開発用プレビューのみ**（`app.env !== production`）。本番では非表示。`aria-pressed` 連動。
- **オープニング**はテーマ連動2層 SVG 波のせり上がり（砂浜/空 → rise → ロゴ → 退出）。固定色は使わず `--sky-*` / `--wave-*` を共有。`sessionStorage` でセッション1回のみ。スキップ（ボタン + Escape）可。`prefers-reduced-motion` 時は即スキップ。
  - 自動退出 ~2200ms / 退出アニメ 0.7s。JS は `resources/js/welcome.js` を `app.js` から import（専用 Vite エントリは権限・manifest 都合で見送り）。
  - 再生中は `#main-content` に `inert`、オープニングは `role="dialog"`。
  - ヒーロー見出しの figure-ground 水面マスクは廃止（文字遮蔽が問題だったため）。図地効果はオープニング側に移管。
  - 魚シルエットはスクロール parallax + bobbing（ヒーロー可視中のみ rAF）。
- **心理学原則の割当**:
  - 図と地の多義性 → オープニングの砂浜→没入（ヒーロー見出しマスクは廃止）
  - 色による奥行 → 暖色（sunset/warm）= 手前 CTA、寒色（ocean 深色）= 背景
  - 遮蔽（オクルージョン）→ 魚シルエットを z=5（写真 z=0 と本文 z=10 の間）に配置
  - テクスチャ勾配 → 泡パーティクル（手前=大/濃、奥=小/薄）
  - サッカード誘導 → IntersectionObserver スタッガ reveal + CTA リップル
- **アニメ規約**: `transform` / `opacity` のみ。`transition-all` 禁止。`box-shadow` トランジションも避ける。
- **追加アセットは初版では不要**だったが、実写ヒーロー3枚を後から追加済み（下記）。
- **ヒーロー実写3テーマ**（2026-07-19）:
  - `hero-dawn.jpg`（縦・朝マヅメ／太陽の光路）→ `object-position: center 58%`、暖色パレット。開発時 celestial はテーマ切替ボタンとして全テーマ表示
  - `hero-day.jpg`（横・埠頭シルエット＋橙帯）→ `object-position: center 32%`、藍灰＋橙のパレット（旧スカイブルー廃止）
  - `hero-night.jpg`（縦・紺碧＋地平線橙リム）→ `object-position: center 45%`、暗部優勢パレット
  - 切替は `html[data-theme]` + CSS opacity クロスフェード（JS で `src` 差し替えなし）。オーバーレイ強度もテーマ別。
  - 既存 `welcome.jpg` は残置（未使用フォールバック）。
- **フッター**は既存 `@include('components.layout.footer')` を流用。
- **ACL 影響なし**（公開ページのみ・認可/DB 非接触）。security-auditor は必須ではない。

## 前提

- Laravel 本体は `src/` 配下。公開ルート `/` は `welcome.blade.php`（スタンドアロン HTML、レイアウト未使用）。
- `src/tailwind.config.js` に `ocean` / `nature` / `sunset` / `warm` と Comfortaa / Noto Sans JP が定義済みだったが、旧 welcome は標準 `blue-*` 直書きで未使用だった。
- Alpine.js は Vite 経由（`resources/js/app.js` の `Alpine.start()`）。`@vite` は manifest/hot があるときのみ読み込む。
- WSL 側で Docker Desktop 統合が無効だと `docker-compose` / ブラウザ目視確認ができない（本セッションではホスト PHP で Pint・Pest のみ実行）。
- `dev` 直下に残っていた未追跡 `frontend/`（`node_modules` + `.next` 残骸）は作業前に削除済み。Next.js ソースは migration ブランチに安全に残っている。

## 学び

- 公開ページのスタンドアロン Blade に巨大な Tailwind v4 フォールバック CSS を埋め込んでいた旧実装は肥大化の元。Vite ビルド前提にし、フォールバックは削除してよい。
- `prefers-reduced-motion` は「自分が書いたクラス」だけでなく、Tailwind の `animate-bounce` やインライン `style="animation: fishSwim ..."` も漏れやすい。レビューで必ず拾う。
- `aria-label` を素の `<div>` に付けても無効。`role="group"` 等が必要。ハンバーガーの `aria-label` は開閉状態で動的に変える（`:aria-label`）。
- `sessionStorage` + タイマー + スキップの組み合わせでは `closeOpening()` が二重発火しうる。冪等フラグ（`closing`）を入れる。
- UI レビュー観点でオープニングは「実質待ち時間」が重要。待機 + 退出アニメの合計が体感待ちになる。
- コントラスト: `text-white/45` は装飾ラベルでも WCAG AA を割りやすい。`/70` 以上を目安にする。
- テーマ切替のタッチターゲットは `w-9`（36px）では不足。`w-11`（44px）を推奨。
- 縦写真（3:4）と横写真（4:3）を同じ `object-cover` で使う場合、テーマごとに `object-position` を変えないと地平線／太陽が切れる。色トークンも写真スペクトルに合わせないとグラデが写真と喧嘩する。
- Vite の `node_modules/.vite-temp` が root 所有だとホスト/コンテナ双方で `npm run build` が EACCES になる。`docker-compose exec -u root` で chown してから www-data で build する。
- オープニング退出中に SKIP で `clearTimers()` を先に呼ぶと退出タイマーが消え画面が固まる。`closeOpening()` に任せる。
- `__welcomeBooted` をモジュール評価時点で立てると、遅延読込時に load フォールバックが無効化され白紙になる。マーカーは init 完了後（`__welcomeInited`）。

## 未解決事項

- [ ] ブラウザで 3 テーマ + オープニング（砂浜→rise）+ reduced-motion + 魚 parallax を目視確認する。
- [ ] PR #29 のレビュー対応・`dev` へのマージ（今回のオープニング改修分を含む）。
- [x] 朝マヅメ / 日中 / 夜釣りの海景画像 3 枚をヒーロー背景に適用（実写・テーマ別パレット同期済み）。
- [x] インライン JS を `resources/js/welcome.js` に分離（`app.js` から import）。
- [ ] （任意・後続）オープニングを AI 生成の波ループ動画（mp4/webm）に差し替える構造は用意済みだが未着手。
- [ ] （後続エピック候補）公開下層（about / activities / gallery / join）へ時間帯テーマと共通ヘッダーを展開する。
- [ ] 既存フッターのプレースホルダ連絡先（`contact@fishing-circle.com` 等）は実情報への差し替えが別途必要（本エピック外）。

## タスク履歴

- 2026-07-19 001 `dev` の未追跡 `frontend/` 残骸（node_modules / .next）を削除し作業ツリーをクリーン化
- 2026-07-19 002 `feature/toppage-immersive-redesign` を作成し、`welcome.blade.php` を没入型デザインへ完全書き直し（時間帯テーマ・波オープニング・図地タイポ・遮蔽・テクスチャ勾配・スタッガ reveal・ヘッダー・テーマ切替）
- 2026-07-19 003 Pest 53 件パス / Pint PASS。code-reviewer・ui-reviewer の指摘（reduced-motion 漏れ・transition-all・aria・コントラスト・オープニング長さ等）を修正
- 2026-07-19 004 PR #29（base: `dev`）を作成し、レビュー指摘の追加修正をプッシュ
- 2026-07-19 005 実写ヒーロー3枚（dawn/day/night）を配置し、テーマ別 object-position・オーバーレイ・CSS パレット・celestial 可視性を同期
- 2026-07-19 006 オープニングをテーマ連動2層波せり上がりに刷新。水面マスク削除・魚 parallax・開発用テーマ切替。`welcome.js` 分離。Pest 54 件 PASS / Vite build 成功。ui-reviewer・code-reviewer 指摘（a11y inert / skip race / dawn コントラスト）を反映
