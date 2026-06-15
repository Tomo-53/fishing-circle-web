<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'master_user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'master_user_id' => 'integer',
    ];

    /**
     * グループのオーナー
     */
    public function masterUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'master_user_id');
    }

    /**
     * グループに参加しているユーザー（全て）
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_groups')
            ->withPivot(['permission_level', 'is_approved'])
            ->withTimestamps();
    }

    /**
     * 承認済みのユーザーのみ
     */
    public function approvedUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('is_approved', true);
    }

    /**
     * 承認待ちのユーザー
     */
    public function pendingUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('is_approved', false);
    }

    /**
     * 特定の権限レベルのユーザーを取得
     */
    public function getUsersByPermissionLevel(int $level): BelongsToMany
    {
        return $this->approvedUsers()->wherePivot('permission_level', $level);
    }

    /**
     * グループのUserGroupレコード
     */
    public function userGroups(): HasMany
    {
        return $this->hasMany(UserGroup::class);
    }

    /**
     * グループ作成時の処理
     */
    protected static function boot()
    {
        parent::boot();

        // グループ作成時にオーナーを自動登録
        static::created(function ($group) {
            UserGroup::create([
                'user_id' => $group->master_user_id,
                'group_id' => $group->id,
                'permission_level' => UserGroup::PERMISSION_LEVEL_OWNER,
                'is_approved' => true,
            ]);
        });

        // グループ削除時に関連レコードも削除
        static::deleting(function ($group) {
            $group->userGroups()->delete();
        });
    }
}
