<?php

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;

// ========================
// グループ作成（store）
// ========================

test('認証済みユーザーがグループを作成できる', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('groups.store'), ['name' => '釣りサークルA'])
        ->assertRedirect();

    $this->assertDatabaseHas('groups', ['name' => '釣りサークルA', 'master_user_id' => $user->id]);
});

test('グループ作成時に作成者がオーナー（level 4・承認済み）として自動登録される', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('groups.store'), ['name' => '釣りサークルB']);

    $group = Group::where('name', '釣りサークルB')->firstOrFail();

    $this->assertDatabaseHas('user_groups', [
        'user_id' => $user->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_OWNER,
        'is_approved' => true,
    ]);
});

test('グループ名が重複している場合バリデーションエラーになる', function () {
    $user = User::factory()->create();
    Group::factory()->create(['name' => '既存グループ', 'master_user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('groups.store'), ['name' => '既存グループ'])
        ->assertSessionHasErrors('name');
});

test('未ログインのユーザーはグループを作成できない', function () {
    $this->post(route('groups.store'), ['name' => '不正グループ'])
        ->assertRedirect(route('login'));
});

// ========================
// グループ一覧（myGroups）
// ========================

test('myGroups には承認済みグループのみ表示される', function () {
    $user = User::factory()->create();

    // 承認済みグループ（オーナーとして作成 → 自動で level4 承認済み）
    $approvedGroup = Group::factory()->create(['master_user_id' => $user->id]);

    // 申請中グループ（別オーナーのグループに pending で参加）
    $owner = User::factory()->create();
    $pendingGroup = Group::factory()->create(['master_user_id' => $owner->id]);
    joinGroup($user, $pendingGroup, UserGroup::PERMISSION_LEVEL_PENDING, false);

    $response = $this->actingAs($user)->get(route('groups.myGroups'));

    $response->assertOk()
        ->assertSee($approvedGroup->name)
        ->assertDontSee($pendingGroup->name);
});

// ========================
// 全グループ一覧（allGroups）
// ========================

test('allGroups は全グループが表示される', function () {
    $user = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('groups.all'))
        ->assertOk()
        ->assertSee($group->name);
});

test('allGroups は検索キーワードでフィルタされる', function () {
    $user = User::factory()->create();
    $matchGroup = Group::factory()->create(['name' => '海釣りサークル', 'master_user_id' => $user->id]);
    $noMatchGroup = Group::factory()->create(['name' => 'バスケ部', 'master_user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->get(route('groups.all', ['search' => '海釣り']));

    $response->assertOk()
        ->assertSee($matchGroup->name)
        ->assertDontSee($noMatchGroup->name);
});

// ========================
// グループ参加申請（join）
// ========================

test('未参加ユーザーが参加申請するとレベル 1・未承認の UserGroup が作成される', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $applicant = User::factory()->create();

    $this->actingAs($applicant)
        ->post(route('groups.join', $group))
        ->assertRedirect();

    $this->assertDatabaseHas('user_groups', [
        'user_id' => $applicant->id,
        'group_id' => $group->id,
        'permission_level' => UserGroup::PERMISSION_LEVEL_PENDING,
        'is_approved' => false,
    ]);
});

test('申請済みのユーザーが再度申請すると error フラッシュが返る', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $applicant = User::factory()->create();
    joinGroup($applicant, $group, UserGroup::PERMISSION_LEVEL_PENDING, false);

    $this->actingAs($applicant)
        ->post(route('groups.join', $group))
        ->assertSessionHas('error');
});

test('既に参加済みのユーザーが join すると error フラッシュが返る', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $member = User::factory()->create();
    joinGroup($member, $group, UserGroup::PERMISSION_LEVEL_MEMBER);

    $this->actingAs($member)
        ->post(route('groups.join', $group))
        ->assertSessionHas('error');
});

// ========================
// グループ更新・削除（オーナー操作）
// ========================

test('オーナーがグループ名を更新できる', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $this->actingAs($owner)
        ->put(route('groups.update', $group), ['name' => '新グループ名'])
        ->assertRedirect(route('groups.show', $group));

    $this->assertDatabaseHas('groups', ['id' => $group->id, 'name' => '新グループ名']);
});

test('オーナーがグループを削除すると user_groups もカスケード削除される', function () {
    $owner = User::factory()->create();
    $group = Group::factory()->create(['master_user_id' => $owner->id]);

    $member = User::factory()->create();
    joinGroup($member, $group, UserGroup::PERMISSION_LEVEL_MEMBER);

    $this->actingAs($owner)
        ->delete(route('groups.destroy', $group))
        ->assertRedirect(route('groups.myGroups'));

    $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    $this->assertDatabaseMissing('user_groups', ['group_id' => $group->id]);
});
