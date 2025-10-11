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

    // グループ管理機能（基本的な認証のみ必要）
    Route::resource('groups', GroupController::class);
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');

    // グループメンバー管理（権限チェック付き）
    Route::middleware('check.group.permission:3')->group(function () {
        Route::post('/groups/{group}/members/{user}/approve', [GroupController::class, 'approveMember'])->name('groups.members.approve');
        Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.remove');
    });
});

require __DIR__.'/auth.php';
