<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * groupsテーブルの作成
     * 目的: ユーザーが所属するグループ（サークル、組織など）を管理
     */
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id()->comment('グループID');
            $table->unsignedBigInteger('master_user_id')->comment('レベル4オーナーのユーザーID');
            $table->string('name', 255)->comment('グループ名（例: 新潟大学釣りサークル）');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('master_user_id')->references('id')->on('users')->onDelete('cascade');

            // インデックス
            $table->index('master_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
