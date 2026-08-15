# DDD/OODコードレビュー — fishing-circle-web

> レビュー基準：書籍『現場で役立つシステム設計の原則』第1章〜第5章  
> ブランチ：`refactor/ddd-review-ch1-5`（`dev` 起点）  
> レビュー日：2026-08-15

---

## 【総合評価】

**総合スコア：B−（良い出発点あり、コントローラに業務ロジックが集積）**

| チェックポイント | 評価 | 主な課題 |
|---|---|---|
| 1. データとロジックの一体化 | ★★★★☆ | `GroupName` 値オブジェクト・`GroupNameCast` は優秀。`Group::boot()` の副作用は懸念あり |
| 2. 状態と区分の整理 | ★★★☆☆ | `PermissionLevel` Enum は優秀だが、`UserGroup::PERMISSION_LEVEL_*` int定数が二重定義され形骸化 |
| 3. 業務とシステムの一致 | ★★☆☆☆ | 「管理者に昇格する」「一般メンバーに降格する」という業務の**コト**がコントローラに散在し、Modelに不在 |
| 4. ドメイン知識の反映 | ★★★☆☆ | `master_user_id`（DB用語）が残る。ユビキタス言語は「オーナー」「幹部」 |
| 5. UIとAPIの分割 | ★★☆☆☆ | `GroupController` が1クラスで13メソッドを持ち、権限チェック・業務ルール・リダイレクト生成まで担っている |

**最重要問題：`promoteToAdmin` / `demoteToMember` の4連続ガード節**。  
「オーナーかチェック → メンバー存在チェック → Ownerでないかチェック → Admin状態チェック → UPDATE」という手続きがコントローラに直書きされており、この知識がコントローラにしか存在しない（Tell, Don't Ask 違反）。

---

## 【指摘箇所と問題点】

### P1 ★★★★★ ：`GroupController::promoteToAdmin` / `demoteToMember` の業務ロジック散在

**ファイル：** `app/Http/Controllers/GroupController.php`（L182–L243）

```php
// Before（コントローラが業務ルールをすべて知っている）
public function promoteToAdmin(Request $request, Group $group, User $user)
{
    $authUser = Auth::user();

    if (! $group->isOwnedBy($authUser)) {               // ← ①業務ルール
        return back()->with('error', '...');
    }
    $userGroup = $group->memberRecordOf($user);
    if (! $userGroup || ! $userGroup->isApproved()) {   // ← ②業務ルール
        return back()->with('error', '...');
    }
    if ($userGroup->isOwner()) {                         // ← ③業務ルール
        return back()->with('error', '...');
    }
    if ($userGroup->isAdmin()) {                         // ← ④業務ルール
        return back()->with('error', '...');
    }
    $userGroup->update(['permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN]); // ← ⑤int定数
    return back()->with('success', '...');
}
```

**問題点:**
- コントローラが「誰を昇格できるか」というドメイン知識をすべて持っている（Tell, Don't Ask 違反）。
- `UserGroup::PERMISSION_LEVEL_ADMIN`（int `3`）を直接使っており、`PermissionLevel::Admin` Enum が形骸化。
- 同一パターンの4ガード節が `demoteToMember` にも重複。
- 同じロジックを別コントローラや CLI から再利用する手段がない。

---

### P2 ★★★★☆ ：`UserGroup::PERMISSION_LEVEL_*` int定数の二重定義

**ファイル：** `app/Models/UserGroup.php`（L55–L61）

```php
public const PERMISSION_LEVEL_PENDING = 1;
public const PERMISSION_LEVEL_MEMBER  = 2;
public const PERMISSION_LEVEL_ADMIN   = 3;
public const PERMISSION_LEVEL_OWNER   = 4;
```

`PermissionLevel` Enum の `value` と完全に一致するが、int定数として別定義されている。  
コントローラ・Factoryが `UserGroup::PERMISSION_LEVEL_MEMBER` を使い続ける限り、  
Enum に移行しても「実態は int」という状態が続く。

**影響範囲（grep件数）：** コントローラ4箇所、`Group::boot()` 1箇所、Factory 4箇所、テスト14箇所

---

### P3 ★★★☆☆ ：`Group::boot()` にオーナー自動登録の副作用

**ファイル：** `app/Models/Group.php`（L113–L131）

```php
protected static function boot()
{
    parent::boot();
    static::created(function ($group) {
        UserGroup::create([...]);  // 暗黙の副作用
    });
    static::deleting(function ($group) {
        $group->userGroups()->delete();  // 暗黙の副作用
    });
}
```

**問題点:**
- `Group::create()` を呼んだ時点で `UserGroup` レコードが暗黙に生成される。
- Seeder やテストで Group だけを作りたい場合にも副作用が走る。
- 「グループ作成時にオーナーを自動登録する」という業務ルールが、フレームワークのライフサイクルフック内に埋まっており、仕様書を見ても発見しにくい。
- Eloquent の `observed` イベント依存であり、`update()`/`save()` と混在すると理解を妨げる。

---

### P4 ★★☆☆☆ ：`master_user_id` というカラム名のユビキタス言語違反

**ファイル：** `app/Models/Group.php`（L23, L107）、`GroupController`（L77）

ドメインエキスパートが使う言葉は「オーナー」「グループ作成者」であり、`master_user_id` はDBのカラム名に由来するシステム用語。  
`masterUser()` リレーションはあるが、`isOwnedBy()` の内部でも `master_user_id` を直接参照している。  

> **注記：** カラム名変更はマイグレーションを伴い影響範囲が広いため、今回のリファクタリング対象外とする（別Issueで管理推奨）。

---

### P5 ★★☆☆☆ ：`GroupController::approveMember` の業務ロジック散在

**ファイル：** `app/Http/Controllers/GroupController.php`（L162–L176）

```php
$userGroup->update([
    'is_approved' => true,
    'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
]);
```

「承認する＝is_approvedをtrueにしてpermission_levelをMemberにする」という業務知識がコントローラに直書き。  
`UserGroup::approve()` のようなドメインメソッドがあれば、「承認とは何をすることか」が1箇所に凝集される。  
→ P1（promote/demote）完了後のP2スライスで実装予定。

---

## 【Before/After コード例】

### P1 Before/After：メンバー昇格/降格

#### Before（GroupController.php）

```php
public function promoteToAdmin(Request $request, Group $group, User $user)
{
    /** @var \App\Models\User $authUser */
    $authUser = Auth::user();

    if (! $group->isOwnedBy($authUser)) {
        return back()->with('error', 'オーナーのみが管理者を任命できます');
    }
    $userGroup = $group->memberRecordOf($user);
    if (! $userGroup || ! $userGroup->isApproved()) {
        return back()->with('error', '昇格対象のメンバーが見つかりません');
    }
    if ($userGroup->isOwner()) {
        return back()->with('error', 'オーナーの権限は変更できません');
    }
    if ($userGroup->isAdmin()) {
        return back()->with('error', $user->name.'さんは既に管理者です');
    }
    $userGroup->update(['permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN]);

    return back()->with('success', $user->name.'さんを管理者に昇格させました');
}
```

#### After：各レイヤーに責務を分散

**① ドメイン例外（新規）** `app/Exceptions/GroupMembershipException.php`

```php
<?php
namespace App\Exceptions;

use RuntimeException;

/** グループ所属・権限に関する業務ルール違反 */
final class GroupMembershipException extends RuntimeException {}
```

**② Group モデルに「コト」を追加** `app/Models/Group.php`

```php
/**
 * メンバーを管理者に昇格させる。
 * 業務ルール違反は GroupMembershipException を投げる。
 */
public function promote(User $target, User $actor): void
{
    if (! $this->isOwnedBy($actor)) {
        throw new GroupMembershipException('オーナーのみが管理者を任命できます');
    }

    $record = $this->memberRecordOf($target);
    if (! $record || ! $record->isApproved()) {
        throw new GroupMembershipException('昇格対象のメンバーが見つかりません');
    }
    if ($record->isOwner()) {
        throw new GroupMembershipException('オーナーの権限は変更できません');
    }
    if ($record->isAdmin()) {
        throw new GroupMembershipException("{$target->name}さんは既に管理者です");
    }

    $record->update(['permission_level' => PermissionLevel::Admin]);
}

/**
 * 管理者を一般メンバーに降格させる。
 */
public function demote(User $target, User $actor): void
{
    if (! $this->isOwnedBy($actor)) {
        throw new GroupMembershipException('オーナーのみが権限を変更できます');
    }

    $record = $this->memberRecordOf($target);
    if (! $record || ! $record->isApproved()) {
        throw new GroupMembershipException('降格対象のメンバーが見つかりません');
    }
    if ($record->isOwner()) {
        throw new GroupMembershipException('オーナーの権限は変更できません');
    }
    if (! $record->isAdmin()) {
        throw new GroupMembershipException("{$target->name}さんは既に一般メンバーです");
    }

    $record->update(['permission_level' => PermissionLevel::Member]);
}
```

**③ Action クラス（新規）** `app/Actions/Group/PromoteMember.php`

```php
<?php
namespace App\Actions\Group;

use App\Models\Group;
use App\Models\User;

final class PromoteMember
{
    public function __invoke(Group $group, User $target, User $actor): void
    {
        $group->promote($target, $actor);
    }
}
```

**④ 薄いコントローラ** `app/Http/Controllers/GroupController.php`

```php
public function promoteToAdmin(Request $request, Group $group, User $user, PromoteMember $action): RedirectResponse
{
    /** @var \App\Models\User $actor */
    $actor = $request->user();

    try {
        $action($group, $user, $actor);
    } catch (GroupMembershipException $e) {
        return back()->with('error', $e->getMessage());
    }

    return back()->with('success', $user->name.'さんを管理者に昇格させました');
}
```

**設計上の改善ポイント：**
- コントローラは「何が起きたか（成功/失敗）」に集中し、「なぜ失敗したか」は Domain が保証する。
- `PermissionLevel::Admin` Enum を直接使い、int定数エイリアスへの依存を断ち切る。
- `PromoteMember` Action を介することで、将来的にキューやバッチからも同じ業務ロジックを呼べる。

---

## 【優先バックログ】

| ID | 内容 | 重要度 | 状態 |
|---|---|---|---|
| P0 | ブランチ作成 + 本レビュードキュメント生成 | 前提 | ✅ 完了 |
| P1 | `promoteToAdmin` / `demoteToMember` を Action + Group ドメインメソッドに分離 | ★★★★★ | 🚧 実施中 |
| P2 | `UserGroup::PERMISSION_LEVEL_*` int定数を `PermissionLevel` Enum に統一（コントローラ・Factory・テスト全件） | ★★★★☆ | 📋 待機 |
| P3 | `approveMember` を `UserGroup::approve()` ドメインメソッド + Action 化 | ★★★☆☆ | 📋 待機 |
| P4 | `Group::boot()` の副作用を明示的な `Group::createWithOwner()` 静的ファクトリに分離 | ★★★☆☆ | 📋 待機 |
| P5 | `CLAUDE.md` 責務表を新レイヤー構成（Actions/）に合わせて更新 | ★★☆☆☆ | 📋 待機 |
| OUT | `master_user_id` カラムリネーム | 範囲外 | ⛔ 対象外 |

---

## 【良い既存設計（維持すること）】

- **`PermissionLevel` Enum（`app/Enums/PermissionLevel.php`）**：`label()`, `atLeast()`, `canRemove()` など、区分ごとの振る舞いを1箇所に凝集している。Chap.2の「リッチなEnum」の模範例。
- **`GroupName` 値オブジェクト + `GroupNameCast`**：長さ制約を VO 内に閉じ、コントローラやリクエストに検証ロジックが漏れない。Chap.1の「プリミティブ型の濫用を避ける」の模範例。
- **`CheckGroupPermission` ミドルウェア**：ACL チェックをルーティング層に分離し、コントローラが二重チェックしない設計。
- **`Group::memberRecordOf()`**：UserGroup レコードの取得ロジックを Model に凝集している点は良い。
