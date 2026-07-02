<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

// e2e（Dusk）はサーバープロセスと DB を共有するため、トランザクション方式の
// RefreshDatabase ではなく DatabaseTruncation でテスト間の状態をリセットする。
pest()->extend(Tests\DuskTestCase::class)
    ->use(Illuminate\Foundation\Testing\DatabaseTruncation::class)
    ->in('Browser');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * 指定ユーザーをグループに参加させる汎用ヘルパー。
 * テストごとに UserGroup を直接作成することで「特定レベルのメンバーが居るグループ」を素早く準備できる。
 */
function joinGroup(\App\Models\User $user, \App\Models\Group $group, int $level, bool $approved = true): void
{
    \App\Models\UserGroup::factory()->create([
        'user_id' => $user->id,
        'group_id' => $group->id,
        'permission_level' => $level,
        'is_approved' => $approved,
    ]);
}

/**
 * ブラウザを未認証（ゲスト）状態にリセットするヘルパー。
 *
 * Dusk はテスト間でブラウザを使い回すため、前のテストのセッションクッキーが残る。
 * DatabaseTruncation は DB のみリセットするので、セッションを持つクッキーを
 * 明示的に削除しないと CSRF 不整合や guest ミドルウェアの誤動作が起きる。
 * deleteAllCookies はドメイン上でないと no-op になるため、まずトップページへ遷移する。
 */
function freshGuest(\Laravel\Dusk\Browser $browser): void
{
    $browser->visit('/');
    $browser->driver->manage()->deleteAllCookies();
}
