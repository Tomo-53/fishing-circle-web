# src/resources/views/welcome — トップページ partial

公開トップ（`welcome.blade.php`）専用の Blade partial。会員 ACL 非接触。

親ファイルは 1 つ上の [`../welcome.blade.php`](../welcome.blade.php)（スタンドアロン HTML）。
スタイルは [`../../css/welcome.css`](../../css/welcome.css)、JS は [`../../js/welcome.js`](../../js/welcome.js)（`app.js` から import）。
案件ノート: [`.claude/epics/toppage-immersive-redesign/_epic.md`](../../../../.claude/epics/toppage-immersive-redesign/_epic.md)

## partial 一覧

| ファイル | 責務 |
|---|---|
| `_header.blade.php` | 固定ヘッダー・ナビ（スクロールで背景変化） |
| `_hero.blade.php` | ヒーロー（テーマ別写真・見出し・CTA・泡/魚シルエット） |
| `_about.blade.php` | サークル紹介セクション |
| `_activities.blade.php` | 活動内容セクション |
| `_join-cta.blade.php` | 入部案内 CTA セクション |
| `_theme-toggle.blade.php` | 開発用テーマ切替（`app.env !== production` 時のみ表示） |

## オープニングについて

`_opening.blade.php` は **意図的に未配置（一時撤去）**。波オープニングは作り直し予定。
現状はヒーローから即表示。`welcome.js` にも `initOpening` は無い。

再追加するときは:

1. `_opening.blade.php` をこのディレクトリに置く
2. 親 `welcome.blade.php` で `#main-content` の前に `@include('welcome._opening')`
3. `welcome.css` / `welcome.js` に演出を戻す
4. この表と親 `views/CLAUDE.md` を更新する

## 親 Blade の組み立て順

```
welcome.blade.php
  └─ #main-content
       ├─ _header
       ├─ main
       │    ├─ _hero
       │    ├─ _about
       │    ├─ _activities
       │    └─ _join-cta
       └─ footer（components.layout.footer）
  └─ _theme-toggle
```

## 更新ルール

partial の追加・削除・責務変更時は、この表と [`../CLAUDE.md`](../CLAUDE.md) の `welcome/` 行を同じ変更で更新すること。
