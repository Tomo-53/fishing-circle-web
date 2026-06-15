<?php

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;

// ========================
// メンバー承認（level 3+ 操作）
// ========================

test('管理者が承認待ちメンバーを承認するとレベル 2・承認済みになる', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $admin = User::factory()->create();
    joinGroup($admin, $group, UserGroup::PERMISSION_LEVEL_ADMIN);

    $applicant = User::factory()->create();
    joinGroup($applicant, $group, UserGroup::PERMISSION_LEVEL_PENDING, false);

    $this->actingAs($admin)
        ->post(route('groups.approve-member', [$group, $applicant]))
        ->assertRedirect();

    $this->assertDatabaseHas('user_groups', [
        'user_id' => $applicant->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
        'is_approved' => true,
    ]);
});

test('承認対象が存在しない場合は error フラッシュが返る', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $nonApplicant = User::factory()->create();
    // グループに存在しないユーザーを承認しようとする
    joinGroup($nonApplicant, $group, UserGroup::PERMISSION_LEVEL_MEMBER); // 既に承認済み

    $this->actingAs($owner)
        ->post(route('groups.approve-member', [$group, $nonApplicant]))
        ->assertSessionHas('error');
});

// ========================
// 管理者への昇格（level 4 専用）
// ========================

test('オーナーが一般メンバーを管理者（level 3）に昇格できる', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $member = User::factory()->create();
    joinGroup($member, $group, UserGroup::PERMISSION_LEVEL_MEMBER);

    $this->actingAs($owner)
        ->post(route('groups.promote-member', [$group, $member]))
        ->assertRedirect();

    $this->assertDatabaseHas('user_groups', [
        'user_id' => $member->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN,
    ]);
});

test('既に管理者のメンバーを昇格しようとすると error フラッシュが返る', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $admin = User::factory()->create();
    joinGroup($admin, $group, UserGroup::PERMISSION_LEVEL_ADMIN);

    $this->actingAs($owner)
        ->post(route('groups.promote-member', [$group, $admin]))
        ->assertSessionHas('error');
});

test('オーナー自身を昇格しようとすると error フラッシュが返る', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $this->actingAs($owner)
        ->post(route('groups.promote-member', [$group, $owner]))
        ->assertSessionHas('error');
});

// ========================
// メンバーへの降格（level 4 専用）
// ========================

test('オーナーが管理者を一般メンバー（level 2）に降格できる', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $admin = User::factory()->create();
    joinGroup($admin, $group, UserGroup::PERMISSION_LEVEL_ADMIN);

    $this->actingAs($owner)
        ->post(route('groups.demote-member', [$group, $admin]))
        ->assertRedirect();

    $this->assertDatabaseHas('user_groups', [
        'user_id' => $admin->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
    ]);
});

test('既に一般メンバーを降格しようとすると error フラッシュが返る', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $member = User::factory()->create();
    joinGroup($member, $group, UserGroup::PERMISSION_LEVEL_MEMBER);

    $this->actingAs($owner)
        ->post(route('groups.demote-member', [$group, $member]))
        ->assertSessionHas('error');
});

test('オーナー自身を降格しようとすると error フラッシュが返る', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $this->actingAs($owner)
        ->post(route('groups.demote-member', [$group, $owner]))
        ->assertSessionHas('error');
});

// ========================
// メンバー削除（level 3+ 操作）
// ========================

test('管理者が一般メンバーをグループから削除できる', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $admin = User::factory()->create();
    joinGroup($admin, $group, UserGroup::PERMISSION_LEVEL_ADMIN);

    $member = User::factory()->create();
    joinGroup($member, $group, UserGroup::PERMISSION_LEVEL_MEMBER);

    $this->actingAs($admin)
        ->delete(route('groups.remove-member', [$group, $member]))
        ->assertRedirect();

    $this->assertDatabaseMissing('user_groups', [
        'user_id' => $member->id,
        'group_id' => $group->id,
    ]);
});

test('オーナーはグループから削除できない（オーナー保護）', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $admin = User::factory()->create();
    joinGroup($admin, $group, UserGroup::PERMISSION_LEVEL_ADMIN);

    $this->actingAs($admin)
        ->delete(route('groups.remove-member', [$group, $owner]))
        ->assertSessionHas('error');

    // オーナーのレコードが残っていることを確認
    $this->assertDatabaseHas('user_groups', [
        'user_id' => $owner->id,
        'group_id' => $group->id,
    ]);
});
