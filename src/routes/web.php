<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== グループ関連のルート =====

    // 認証のみ必要なグループ機能
    Route::get('/groups', [GroupController::class, 'myGroups'])->name('groups.myGroups');
    Route::get('/groups/all', [GroupController::class, 'allGroups'])->name('groups.all');
    Route::resource('groups', GroupController::class)->only(['create', 'store']);

    // グループ参加申請（認証のみ必要）
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');

    // ===== 特定グループでの権限が必要なルート =====
    // 以後下記のルートが頭につく
    Route::prefix('groups/{group}')->group(function () {

        // レベル2以上：一般メンバー権限
        Route::middleware('check.group.permission:2')->group(function () {
            Route::get('/', [GroupController::class, 'show'])->name('groups.show');
        });

        // レベル3以上：管理者権限
        Route::middleware('check.group.permission:3')->group(function () {
            Route::get('/members', [GroupController::class, 'members'])->name('groups.members');
            Route::post('/members/{user}/approve', [GroupController::class, 'approveMember'])->name('groups.approve-member');
            Route::delete('/members/{user}', [GroupController::class, 'removeMember'])->name('groups.remove-member');
        });

        // レベル4のみ：オーナー権限
        Route::middleware('check.group.permission:4')->group(function () {
            Route::get('/edit', [GroupController::class, 'edit'])->name('groups.edit');
            Route::put('/', [GroupController::class, 'update'])->name('groups.update');
            Route::delete('/', [GroupController::class, 'destroy'])->name('groups.destroy');
        });

    });
});

require __DIR__.'/auth.php';
