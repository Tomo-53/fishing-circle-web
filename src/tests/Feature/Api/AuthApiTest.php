<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ========================
// ログイン API
// ========================

test('正しい認証情報でログインできる', function () {
    $user = User::factory()->create();

    $this->postJson('/api/login', [
        'email'    => $user->email,
        'password' => 'password',
    ])->assertOk()
      ->assertJsonPath('email', $user->email);
});

test('誤ったパスワードでは401を返す', function () {
    $user = User::factory()->create();

    $this->postJson('/api/login', [
        'email'    => $user->email,
        'password' => 'wrong-password',
    ])->assertUnprocessable();
});

// ========================
// 登録 API
// ========================

test('新規ユーザーを登録できる', function () {
    $this->postJson('/api/register', [
        'name'                  => 'テストユーザー',
        'email'                 => 'test@example.com',
        'password'              => 'password1234!',
        'password_confirmation' => 'password1234!',
    ])->assertCreated()
      ->assertJsonPath('email', 'test@example.com');

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('メールアドレスが重複している場合422を返す', function () {
    $user = User::factory()->create(['email' => 'dup@example.com']);

    $this->postJson('/api/register', [
        'name'                  => '別ユーザー',
        'email'                 => 'dup@example.com',
        'password'              => 'password1234!',
        'password_confirmation' => 'password1234!',
    ])->assertUnprocessable()
      ->assertJsonValidationErrors(['email']);
});

// ========================
// 認証ユーザー情報 API
// ========================

test('認証済みユーザーは /api/user から自分の情報を取得できる', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/api/user')
        ->assertOk()
        ->assertJsonPath('id', $user->id);
});

test('未認証ユーザーは /api/user で401を返す', function () {
    $this->getJson('/api/user')->assertUnauthorized();
});

// ========================
// ログアウト API
// ========================

test('ログアウトできる', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/logout')
        ->assertOk()
        ->assertJsonPath('message', 'ログアウトしました');
});
