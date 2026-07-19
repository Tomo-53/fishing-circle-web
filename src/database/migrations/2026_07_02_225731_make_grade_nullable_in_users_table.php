<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * usersテーブルのgradeカラムをNULL許容に変更。
     * 登録時にgradeを必須としない（後から設定可能）。
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('grade')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('grade')->nullable(false)->change();
        });
    }
};
