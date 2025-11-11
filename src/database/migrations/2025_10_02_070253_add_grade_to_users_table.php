<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * usersテーブルにgradeカラムを追加
     * 目的: 所属学年や役職などの情報を格納
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('grade')->after('email')->comment('所属学年、役職など');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
