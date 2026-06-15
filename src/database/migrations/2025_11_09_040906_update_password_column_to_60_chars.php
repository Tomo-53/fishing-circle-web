<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * usersテーブルのpasswordカラムを60文字制限に変更
     * 目的: フロントエンドのバリデーション制限と整合性を取る
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password', 60)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
