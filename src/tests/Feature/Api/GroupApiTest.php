<?php

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ========================
// グループ作成
// ========================

test('認証済みユーザーがグループを作成できる', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/groups', ['name' => '釣りサークルAPI'])
        ->assertCreated()
        ->assertJsonPath('name', '釣りサークルAPI');

    $this->assertDatabaseHas('groups', ['name' => '釣りサークルAPI', 'master_user_id' => $user->id]);
});

test('未認証ユーザーはグループを作成できない', function () {
    $this->postJson('/api/groups', ['name' => '無認証グループ'])
        ->assertUnauthorized();
});

test('グループ名が重複している場合422を返す', function () {
    $user = User::factory()->create();
    Group::factory()->create(['name' => '既存グループ', 'master_user_id' => $user->id]);

    $this->actingAs($user)
        ->postJson('/api/groups', ['name' => '既存グループ'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

// ========================
// グループ詳細（ACL 境界テスト）
// ========================

test('一般メンバー（lv2）はグループ詳細を取得できる', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    UserGroup::create([
        'user_id' => $member->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
        'is_approved' => true,
    ]);

    $this->actingAs($member)
        ->getJson("/api/groups/{$group->id}")
        ->assertOk()
        ->assertJsonPath('group.id', $group->id);
});

test('承認待ち（lv1）のユーザーはグループ詳細にアクセスできない', function () {
    $owner = User::factory()->create();
    $pending = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    UserGroup::create([
        'user_id' => $pending->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_PENDING,
        'is_approved' => false,
    ]);

    $this->actingAs($pending)
        ->getJson("/api/groups/{$group->id}")
        ->assertForbidden()
        ->assertJsonPath('code', 'PENDING_APPROVAL');
});

test('グループ未参加のユーザーはグループ詳細にアクセスできない', function () {
    $owner = User::factory()->create();
    $outsider = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $this->actingAs($outsider)
        ->getJson("/api/groups/{$group->id}")
        ->assertForbidden()
        ->assertJsonPath('code', 'NOT_MEMBER');
});

test('別グループのメンバーは他グループの詳細にアクセスできない', function () {
    $owner1 = User::factory()->create();
    $owner2 = User::factory()->create();
    $group1 = Group::factory()->create(['master_user_id' => $owner1->id]);
    $group2 = Group::factory()->create(['master_user_id' => $owner2->id]);

    // owner2 は group1 のメンバーではない
    $this->actingAs($owner2)
        ->getJson("/api/groups/{$group1->id}")
        ->assertForbidden();
});

// ========================
// メンバー管理（ACL 境界テスト）
// ========================

test('管理者（lv3）はメンバー一覧を取得できる', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    UserGroup::create([
        'user_id' => $admin->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN,
        'is_approved' => true,
    ]);

    $this->actingAs($admin)
        ->getJson("/api/groups/{$group->id}/members")
        ->assertOk();
});

test('一般メンバー（lv2）はメンバー管理にアクセスできない', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    UserGroup::create([
        'user_id' => $member->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
        'is_approved' => true,
    ]);

    $this->actingAs($member)
        ->getJson("/api/groups/{$group->id}/members")
        ->assertForbidden();
});

test('オーナーのみがグループを削除できる', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    UserGroup::create([
        'user_id' => $admin->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN,
        'is_approved' => true,
    ]);

    // 管理者は削除できない
    $this->actingAs($admin)
        ->deleteJson("/api/groups/{$group->id}")
        ->assertForbidden();

    // オーナーは削除できる
    $this->actingAs($owner)
        ->deleteJson("/api/groups/{$group->id}")
        ->assertOk();

    $this->assertDatabaseMissing('groups', ['id' => $group->id]);
});

// ========================
// 参加申請
// ========================

test('認証済みユーザーがグループに参加申請できる', function () {
    $owner = User::factory()->create();
    $applicant = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $this->actingAs($applicant)
        ->postJson("/api/groups/{$group->id}/join")
        ->assertCreated();

    $this->assertDatabaseHas('user_groups', [
        'user_id' => $applicant->id,
        'group_id' => $group->id,
        'is_approved' => false,
    ]);
});

test('既に参加済みのユーザーが再申請すると409を返す', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    UserGroup::create([
        'user_id' => $member->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
        'is_approved' => true,
    ]);

    $this->actingAs($member)
        ->postJson("/api/groups/{$group->id}/join")
        ->assertConflict();
});

// ========================
// メンバー承認・昇格・降格（境界テスト）
// ========================

test('管理者がメンバーを承認できる', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create();
    $pending = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    UserGroup::create([
        'user_id' => $admin->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN,
        'is_approved' => true,
    ]);
    UserGroup::create([
        'user_id' => $pending->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_PENDING,
        'is_approved' => false,
    ]);

    $this->actingAs($admin)
        ->postJson("/api/groups/{$group->id}/members/{$pending->id}/approve")
        ->assertOk();

    $this->assertDatabaseHas('user_groups', [
        'user_id' => $pending->id,
        'group_id' => $group->id,
        'is_approved' => true,
        'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
    ]);
});

test('オーナーのみがメンバーを管理者に昇格できる', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create();
    $member = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    UserGroup::create([
        'user_id' => $admin->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN,
        'is_approved' => true,
    ]);
    UserGroup::create([
        'user_id' => $member->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
        'is_approved' => true,
    ]);

    // 管理者は昇格できない（オーナーのみ）
    $this->actingAs($admin)
        ->postJson("/api/groups/{$group->id}/members/{$member->id}/promote")
        ->assertForbidden();

    // オーナーは昇格できる
    $this->actingAs($owner)
        ->postJson("/api/groups/{$group->id}/members/{$member->id}/promote")
        ->assertOk();

    $this->assertDatabaseHas('user_groups', [
        'user_id' => $member->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN,
    ]);
});
