<?php

namespace App\Models;

use App\Enums\PermissionLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserGroup extends Pivot
{
    use HasFactory;

    /**
     * Pivot はデフォルトでテーブル名を推測しないため明示する。
     */
    protected $table = 'user_groups';

    /**
     * user_groups は id を持つため auto-increment を有効化する。
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'group_id',
        'permission_level',
        'is_approved',
    ];

    /**
     * The attributes that should be cast.
     *
     * permission_level は PermissionLevel 値オブジェクト（enum）にキャストし、
     * 生の int として引き回さない。
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'group_id' => 'integer',
        'permission_level' => PermissionLevel::class,
        'is_approved' => 'boolean',
    ];

    /**
     * 権限レベルの定数定義（書き込み・テスト・ファクトリ用の数値エイリアス）。
     * 比較・ラベル化のロジックは PermissionLevel enum に集約している。
     */
    public const PERMISSION_LEVEL_PENDING = 1;     // 認証待機

    public const PERMISSION_LEVEL_MEMBER = 2;      // 一般メンバー

    public const PERMISSION_LEVEL_ADMIN = 3;       // 幹部・管理者

    public const PERMISSION_LEVEL_OWNER = 4;       // グループオーナー

    /**
     * このレコードに関連するユーザー
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * このレコードに関連するグループ
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * 権限レベルの説明を取得
     */
    public function getPermissionLabelAttribute(): string
    {
        return $this->permission_level?->label() ?? '不明';
    }

    /**
     * 承認済みかどうかをチェック
     */
    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    /**
     * オーナーかどうかをチェック
     */
    public function isOwner(): bool
    {
        return $this->permission_level === PermissionLevel::Owner;
    }

    /**
     * 管理者（レベル3）かどうかをチェック
     */
    public function isAdmin(): bool
    {
        return $this->permission_level?->isAdmin() ?? false;
    }

    /**
     * 管理者権限があるかチェック（レベル3以上）
     */
    public function hasAdminPermission(): bool
    {
        return $this->permission_level?->hasAdminPermission() ?? false;
    }

    /**
     * 指定されたレベル以上の権限があるかチェック
     */
    public function hasPermissionLevel(PermissionLevel $level): bool
    {
        return $this->permission_level?->atLeast($level) ?? false;
    }

    /**
     * $target ユーザーを除名できるかチェック
     * オーナーは全員除名可能、管理者は一般メンバー以下のみ除名可能
     */
    public function canRemove(self $target): bool
    {
        return $this->permission_level?->canRemove($target->permission_level) ?? false;
    }
}
