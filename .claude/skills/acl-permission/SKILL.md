---
name: acl-permission
description: このプロジェクトの4段階グループ権限(ACL)を正しく実装・確認する手順。グループ権限が必要なルート/操作を追加するとき、認可・権限チェック・CheckGroupPermission ミドルウェア・UserGroup を扱うときに使用。
---

# 4段階ACL（グループ権限）の実装ガイド

新潟大学釣り同好会web の認可中核。`user_groups` ピボット（`user_id` × `group_id`）でグループごとに独立した権限を持つ。

## 権限レベル（UserGroup の定数を使う）

| Level | 定数 | ラベル |
|------|------|--------|
| 1 | `UserGroup::PERMISSION_LEVEL_PENDING` | 認証待機（申請中・閲覧不可） |
| 2 | `UserGroup::PERMISSION_LEVEL_MEMBER` | 一般メンバー |
| 3 | `UserGroup::PERMISSION_LEVEL_ADMIN` | 幹部・管理者 |
| 4 | `UserGroup::PERMISSION_LEVEL_OWNER` | グループオーナー |

## 保護ルートの追加手順（`src/routes/web.php`）

グループ権限が必要なルートは `groups/{group}` プレフィックス内に置き、`check.group.permission:LEVEL` ミドルウェアを付ける。
（ミドルウェアのエイリアスは `bootstrap/app.php` に登録された **`check.group.permission`**。）

```php
Route::prefix('groups/{group}')->group(function () {
    // レベル2以上：一般メンバー
    Route::middleware('check.group.permission:2')->group(function () {
        Route::get('/', [GroupController::class, 'show'])->name('groups.show');
    });
    // レベル3以上：管理者
    Route::middleware('check.group.permission:3')->group(function () {
        Route::get('/members', [GroupController::class, 'members'])->name('groups.members');
    });
    // レベル4のみ：オーナー
    Route::middleware('check.group.permission:4')->group(function () {
        Route::delete('/', [GroupController::class, 'destroy'])->name('groups.destroy');
    });
});
```

`CheckGroupPermission`（`src/app/Http/Middleware/CheckGroupPermission.php`）が次を保証する：
ログイン済み → グループ所属 → `is_approved` 済み → `permission_level >= LEVEL`。
通過時に `current_group` / `current_user_group` をリクエストへ付与するのでコントローラで使える。

## コントローラ内で追加判定が必要なとき

ミドルウェアだけで足りない細かい判定は `UserGroup` のメソッドを使う：

```php
use App\Enums\PermissionLevel;

$userGroup = $request->get('current_user_group'); // ミドルウェアが付与
if (! $userGroup->hasPermissionLevel(PermissionLevel::Admin)) {
    abort(403);
}
```

**比較は `PermissionLevel` enum を渡す**（`hasPermissionLevel()` の引数は enum 型。int 定数を渡すと TypeError）。
`UserGroup::PERMISSION_LEVEL_*` の int 定数は書き込み・テスト・Factory 用の数値エイリアス（`UserGroup.php` のコメント参照）。

## やってはいけないこと（権限漏れの典型）

- `group_id` を見ずに `user_id` だけで権限を引く → **別グループの権限で操作できてしまう**。必ず両方でスコープ。
- 承認待ち（`is_approved=false`）を通す。
- Blade の `@can`/`@auth` 表示制御だけで「守った気になる」→ サーバ側認可が本体。
- 自前で `permission_level` を数値直書き → 比較は enum（`PermissionLevel::*`）、書き込み・Factory は定数（`UserGroup::PERMISSION_LEVEL_*`）を使う。

## 追加したら必ずテストする（`pest-tester` / `.claude/rules/testing.md`）

各保護ルートについて以下を Pest で網羅：
1. 必要レベル未満のメンバー → 403
2. `is_approved=false` のメンバー → 403
3. 別グループのメンバー → アクセス不可
4. 正しい権限のメンバー → 成功

```php
it('レベル不足のメンバーは管理者ページにアクセスできない', function () {
    $user = User::factory()->create();
    $group = Group::factory()->create();
    UserGroup::factory()->create([
        'user_id' => $user->id, 'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER, 'is_approved' => true,
    ]);
    actingAs($user)->get(route('groups.members', $group))->assertForbidden();
});
```
