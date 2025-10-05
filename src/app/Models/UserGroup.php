<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGroup extends Model
{
    use HasFactory;

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
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'group_id' => 'integer',
        'permission_level' => 'integer',
        'is_approved' => 'boolean',
    ];

    /**
     * 権限レベルの定数定義
     */
    public const PERMISSION_LEVEL_PENDING = 1;     // 認証待機
    public const PERMISSION_LEVEL_MEMBER = 2;      // 一般メンバー
    public const PERMISSION_LEVEL_ADMIN = 3;       // 幹部・管理者
    public const PERMISSION_LEVEL_OWNER = 4;       // グループオーナー

    /**
     * 権限レベルの説明
     */
    public const PERMISSION_LABELS = [
        self::PERMISSION_LEVEL_PENDING => '認証待機メンバー',
        self::PERMISSION_LEVEL_MEMBER => '一般メンバー',
        self::PERMISSION_LEVEL_ADMIN => '幹部・管理者',
        self::PERMISSION_LEVEL_OWNER => 'グループオーナー',
    ];

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
        return self::PERMISSION_LABELS[$this->permission_level] ?? '不明';
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
        return $this->permission_level === self::PERMISSION_LEVEL_OWNER;
    }

    /**
     * 管理者権限があるかチェック（レベル3以上）
     */
    public function hasAdminPermission(): bool
    {
        return $this->permission_level >= self::PERMISSION_LEVEL_ADMIN;
    }

    /**
     * 指定されたレベル以上の権限があるかチェック
     */
    public function hasPermissionLevel(int $level): bool
    {
        return $this->permission_level >= $level;
    }
}
