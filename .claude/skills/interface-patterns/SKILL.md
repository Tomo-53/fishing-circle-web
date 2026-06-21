---
name: interface-patterns
description: 会員制サイトの定番画面（一覧/フォーム/詳細/ダッシュボード）を一貫した型紙で組む手順。空状態・ページネーション・バリデーション表示・ACL 表示制御の定石を含む。新しい画面を追加するときに使用。
---

# インターフェイス型紙（画面パターン集）

このプロジェクト（**Blade + Tailwind + Alpine**、会員制・グループ権限）でよく作る画面の型紙。
既存トークン/コンポーネントは `design-system`、磨きは `ui-polish`、a11y は `accessibility`、演出は `ui-motion` を併用する。

## 共通の骨格

- レイアウトは `x-app-layout`（認証後）/ `x-guest-layout`（認証前）。
- 横幅は `.container-custom`、縦の余白は `.section-sm` などトークンで統一。
- ページに `h1` は1つ。見出し階層を順守（`accessibility`）。

## ACL 表示制御（全画面共通・必須）

グループ権限が絡む画面では、表示制御とサーバ側認可を**二重**で守る。

- 表示: `@can` / `@if($userLevel >= ...)` で操作ボタン等を出し分ける。
- 本体: ルートの `check.group.permission:LEVEL` と Policy/FormRequest が真の防御（`acl-permission` スキル）。
- **表示制御だけに頼らない**。ボタンを隠してもエンドポイントは保護されていること。

## 1. 一覧（リスト）

```blade
<x-app-layout>
  <div class="container-custom section-sm">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-display font-semibold text-gray-900">グループ一覧</h1>
      @can('create', App\Models\Group::class)
        <a href="{{ route('groups.create') }}" class="btn btn-primary">新規作成</a>
      @endcan
    </div>

    @forelse ($groups as $group)
      {{-- カード or 行。divide-y で等間隔 --}}
    @empty
      {{-- 空状態: アイコン + 説明 + 主要アクション --}}
      <div class="card text-center text-gray-500">
        <p class="mb-4">まだグループがありません。</p>
        @can('create', App\Models\Group::class)
          <a href="{{ route('groups.create') }}" class="btn btn-primary">最初のグループを作る</a>
        @endcan
      </div>
    @endforelse

    <div class="mt-6">{{ $groups->links() }}</div>
  </div>
</x-app-layout>
```

- **空状態を必ず用意**する（`@forelse`/`@empty`）。空＝失敗に見せない。
- ページネーションは Eloquent の `paginate()` + `{{ $items->links() }}`。
- 一覧データは N+1 回避のため `with(...)` で eager load（コントローラ側）。

## 2. フォーム（作成/編集）

```blade
<form method="POST" action="{{ route('groups.store') }}" class="card space-y-6">
  @csrf
  <div>
    <x-input-label for="name" :value="__('グループ名')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                  :value="old('name')" required autofocus
                  aria-describedby="name-error" />
    <x-input-error :messages="$errors->get('name')" id="name-error" class="mt-2" />
  </div>
  <div class="flex items-center justify-end gap-3">
    <a href="{{ route('groups.index') }}" class="btn btn-outline">キャンセル</a>
    <x-primary-button>保存</x-primary-button>
  </div>
</form>
```

- 値は `old(...)` で再入力を保持。エラーは `x-input-error` + `aria-describedby`（`accessibility`）。
- 編集は `@method('PATCH')`、削除フォームは `@method('DELETE')` + 確認（`x-modal` 等）。
- 主要アクションは右下に1つ（primary）、副次は outline/secondary。

## 3. 詳細（show）

- 見出し + メタ情報 + 本文。操作（編集/削除）は権限で出し分け（`@can`）。
- ラベルと値は `flex`/`grid` で整列。関連一覧があれば「1. 一覧」型を内包。

## 4. ダッシュボード的サマリ

- `grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6` でカードを並べる（`grid-cols-auto-fit` も可）。
- 各カードは `.card`。数値は大きく（`text-3xl font-semibold`）、ラベルは小さく（`text-sm text-gray-500`）。
- ロード直後の控えめな出現は `animate-fade-in` 程度に留める（`ui-motion`）。

## 仕上げ

- レスポンシブ（`sm: md: lg:`）で崩れないか確認。
- `ui-reviewer` でレビュー、表示確認は `webapp-testing` を提案。
