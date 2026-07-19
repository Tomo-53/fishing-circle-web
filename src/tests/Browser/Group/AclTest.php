<?php

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Laravel\Dusk\Browser;

/**
 * ACL（権限別）e2e テスト。
 *
 * 各テストは独立して実行できる。選択実行の方法:
 *   php artisan dusk tests/Browser/Group/AclTest.php          # このファイル全件
 *   php artisan dusk --filter="承認待ち"                       # テスト名でフィルタ
 *   php artisan dusk --filter="Level 2"
 *   php artisan dusk --filter="Level 3"
 *   php artisan dusk --filter="Level 4"
 *
 * DatabaseTruncation は Pest.php で Browser/ 配下に自動適用済み。
 * loginAs($user) は Dusk の内部ルート /_dusk/login を使用（APP_ENV=local 時のみ有効）。
 */
beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->group = Group::factory()->create(['master_user_id' => $this->owner->id]);
    // Group::boot() の created フックにより owner が Level 4 / is_approved=true で自動登録される
});

test('承認待ちユーザー（Level 1）がグループページにアクセスすると 403 になる', function () {
    $pending = User::factory()->create();
    joinGroup($pending, $this->group, UserGroup::PERMISSION_LEVEL_PENDING, false);

    $this->browse(function (Browser $browser) use ($pending) {
        $browser->loginAs($pending)
            ->visit(route('groups.show', $this->group))
            ->assertSee('403');
    });
});

test('一般メンバー（Level 2）がグループページを閲覧できる', function () {
    $member = User::factory()->create();
    joinGroup($member, $this->group, UserGroup::PERMISSION_LEVEL_MEMBER);

    $this->browse(function (Browser $browser) use ($member) {
        $browser->loginAs($member)
            ->visit(route('groups.show', $this->group))
            ->assertSee($this->group->name)
            ->assertSee('グループ情報')
            // オーナー専用 UI は表示されない（Blade @if isOwner の検証）
            ->assertDontSee('グループ削除')
            ->assertDontSee('ダブルクリックでグループ名変更可能');
    });
});

test('一般メンバー（Level 2）がメンバー管理ページにアクセスすると 403 になる', function () {
    $member = User::factory()->create();
    joinGroup($member, $this->group, UserGroup::PERMISSION_LEVEL_MEMBER);

    $this->browse(function (Browser $browser) use ($member) {
        $browser->loginAs($member)
            ->visit(route('groups.members', $this->group))
            ->assertSee('403');
    });
});

test('管理者（Level 3）がメンバー管理ページを閲覧できる', function () {
    $admin = User::factory()->create();
    joinGroup($admin, $this->group, UserGroup::PERMISSION_LEVEL_ADMIN);

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
            ->visit(route('groups.members', $this->group))
            ->assertSee('メンバー管理')
            ->assertSee('承認済みメンバー');
    });
});

test('管理者（Level 3）がグループページでオーナー専用 UI を閲覧できない', function () {
    $admin = User::factory()->create();
    joinGroup($admin, $this->group, UserGroup::PERMISSION_LEVEL_ADMIN);

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
            ->visit(route('groups.show', $this->group))
            ->assertSee($this->group->name)
            ->assertSee('メンバー管理')           // Level 3 以上に表示
            ->assertDontSee('グループ削除')        // Level 4 専用ボタンは非表示
            ->assertDontSee('ダブルクリックでグループ名変更可能'); // Level 4 専用ヒントは非表示
    });
});

test('オーナー（Level 4）がグループページでオーナー専用の操作を確認できる', function () {
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->owner)
            ->visit(route('groups.show', $this->group))
            ->assertSee($this->group->name)
            ->assertSee('グループ削除')
            ->assertSee('ダブルクリックでグループ名変更可能');
    });
});

test('別グループのメンバーが他のグループのページにアクセスすると 403 になる', function () {
    $otherOwner = User::factory()->create();
    Group::factory()->create(['master_user_id' => $otherOwner->id]);
    // $otherOwner は自分のグループの Level 4 だが、$this->group には所属していない

    $this->browse(function (Browser $browser) use ($otherOwner) {
        $browser->loginAs($otherOwner)
            ->visit(route('groups.show', $this->group))
            ->assertSee('403');
    });
});

test('グループ未所属のユーザーがグループページにアクセスすると 403 になる', function () {
    $stranger = User::factory()->create();

    $this->browse(function (Browser $browser) use ($stranger) {
        $browser->loginAs($stranger)
            ->visit(route('groups.show', $this->group))
            ->assertSee('403');
    });
});
