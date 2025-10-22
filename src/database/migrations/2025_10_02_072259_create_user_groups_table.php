<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * user_groupsテーブルの作成
     * 目的: ユーザーとグループの関連付け、権限レベルの管理
     */
    public function up(): void
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id()->comment('ユーザーグループ関連ID');
            $table->unsignedBigInteger('user_id')->comment('所属するユーザーのID');
            $table->unsignedBigInteger('group_id')->comment('所属するグループのID');
            $table->integer('permission_level')->comment('権限レベル (1:認証待機, 2:一般メンバー, 3:幹部, 4:オーナー)');
            $table->boolean('is_approved')->default(false)->comment('参加承認フラグ (FALSE:未承認, TRUE:承認済み)');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            // 複合ユニーク制約（同じユーザーが同じグループに重複参加防止）
            $table->unique(['user_id', 'group_id']);

            // インデックス
            $table->index(['user_id', 'permission_level']);
            $table->index(['group_id', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
