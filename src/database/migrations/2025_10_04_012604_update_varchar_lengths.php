<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * usersテーブルのVARCHAR長を50に統一
     * 対象: name, email, password, grade
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 50)->change();
            $table->string('email', 50)->change();
            $table->string('password', 50)->change();
            $table->string('grade', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
