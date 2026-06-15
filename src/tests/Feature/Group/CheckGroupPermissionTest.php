<?php

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;

// グループ詳細（level 2 必要）への URL を返すヘルパー
function showUrl(Group $group): string
{
    return route('groups.show', $group);
}

// メンバー管理（level 3 必要）への URL を返すヘルパー
function membersUrl(Group $group): string
{
    return route('groups.members', $group);
}

// グループ更新（level 4 必要）の URL とデータを返すヘルパー
function updateUrl(Group $group): string
{
    return route('groups.update', $group);
}

// ========================
// 未ログイン
// ========================

test('未ログインでグループ詳細にアクセスすると login にリダイレクトされる', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $this->get(showUrl($group))
        ->assertRedirect(route('login'));
});

// ========================
// 非メンバー → 403
// ========================

test('グループに参加していないユーザーがグループ詳細にアクセスすると 403', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $outsider = User::factory()->create();

    $this->actingAs($outsider)
        ->get(showUrl($group))
        ->assertForbidden();
});

// ========================
// 承認待ち（is_approved=false）→ 403
// ========================

test('承認待ちのユーザーがグループ詳細にアクセスすると 403', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $applicant = User::factory()->create();
    joinGroup($applicant, $group, UserGroup::PERMISSION_LEVEL_PENDING, false);

    $this->actingAs($applicant)
        ->get(showUrl($group))
        ->assertForbidden();
});

// ========================
// 権限レベル不足 → 403
// ========================

test('一般メンバー（level 2）がメンバー管理（level 3 必要）にアクセスすると 403', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $member = User::factory()->create();
    joinGroup($member, $group, UserGroup::PERMISSION_LEVEL_MEMBER);

    $this->actingAs($member)
        ->get(membersUrl($group))
        ->assertForbidden();
});

test('管理者（level 3）がグループ更新（level 4 必要）にアクセスすると 403', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $admin = User::factory()->create();
    joinGroup($admin, $group, UserGroup::PERMISSION_LEVEL_ADMIN);

    $this->actingAs($admin)
        ->put(updateUrl($group), ['name' => '新しい名前'])
        ->assertForbidden();
});

// ========================
// グループ横断の権限漏れ防止（Critical）
// ========================

test('グループ A のオーナーがグループ B の詳細にアクセスすると 403', function () {
    $ownerA = User::factory()->create();
    $groupA = Group::factory()->create(['master_user_id' => $ownerA->id]);
    // ownerA は groupA のオーナー（level 4）だが groupB には無関係

    $ownerB = User::factory()->create();
    $groupB = Group::factory()->create(['master_user_id' => $ownerB->id]);

    $this->actingAs($ownerA)
        ->get(showUrl($groupB))
        ->assertForbidden();
});

test('グループ A のオーナーがグループ B のメンバー管理にアクセスすると 403', function () {
    $ownerA = User::factory()->create();
    $groupA = Group::factory()->create(['master_user_id' => $ownerA->id]);

    $ownerB = User::factory()->create();
    $groupB = Group::factory()->create(['master_user_id' => $ownerB->id]);

    $this->actingAs($ownerA)
        ->get(membersUrl($groupB))
        ->assertForbidden();
});

// ========================
// 正常系（各レベルが必要レベルちょうどでアクセス可）
// ========================

test('一般メンバー（level 2）がグループ詳細（level 2 必要）にアクセスできる', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $member = User::factory()->create();
    joinGroup($member, $group, UserGroup::PERMISSION_LEVEL_MEMBER);

    $this->actingAs($member)
        ->get(showUrl($group))
        ->assertOk();
});

test('管理者（level 3）がメンバー管理（level 3 必要）にアクセスできる', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $admin = User::factory()->create();
    joinGroup($admin, $group, UserGroup::PERMISSION_LEVEL_ADMIN);

    $this->actingAs($admin)
        ->get(membersUrl($group))
        ->assertOk();
});

test('オーナー（level 4）がグループ更新（level 4 必要）を実行できる', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $this->actingAs($owner)
        ->put(updateUrl($group), ['name' => '更新後グループ名'])
        ->assertRedirect(route('groups.show', $group));
});
