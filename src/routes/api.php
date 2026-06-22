<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API ルート定義（Next.js SPA 向け）
|--------------------------------------------------------------------------
|
| 認証: Sanctum SPA 認証（Cookie セッション）。
| /sanctum/csrf-cookie を先に叩いてから POST/PUT/DELETE を行う。
|
| 権限レベル:
|   1 = 認証待機（申請中）
|   2 = 一般メンバー
|   3 = 管理者・幹部
|   4 = グループオーナー
|
*/

// =============================
// 認証不要のエンドポイント
// =============================
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// =============================
// 認証必要なエンドポイント
// =============================
Route::middleware('auth:sanctum')->group(function () {
    // 現在のユーザー情報
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // プロフィール
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // グループ（認証のみ必要）
    Route::get('/groups', [GroupController::class, 'myGroups']);
    Route::get('/groups/all', [GroupController::class, 'allGroups']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::post('/groups/{group}/join', [GroupController::class, 'join']);

    // グループ（グループ権限が必要）
    Route::prefix('groups/{group}')->group(function () {

        // レベル2以上：一般メンバー権限
        Route::middleware('check.group.permission:2')->group(function () {
            Route::get('/', [GroupController::class, 'show']);
        });

        // レベル3以上：管理者権限
        Route::middleware('check.group.permission:3')->group(function () {
            Route::get('/members', [GroupController::class, 'members']);
            Route::post('/members/{user}/approve', [GroupController::class, 'approveMember']);
            Route::delete('/members/{user}', [GroupController::class, 'removeMember']);
        });

        // レベル4のみ：オーナー権限
        Route::middleware('check.group.permission:4')->group(function () {
            Route::put('/', [GroupController::class, 'update']);
            Route::delete('/', [GroupController::class, 'destroy']);
            Route::post('/members/{user}/promote', [GroupController::class, 'promoteToAdmin']);
            Route::post('/members/{user}/demote', [GroupController::class, 'demoteToMember']);
        });
    });
});
