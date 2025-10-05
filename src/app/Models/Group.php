<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * このグループのオーナー（作成者）
     */
    public function masterUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'master_user_id');
    }

    /**
     * このグループに所属するユーザー（多対多リレーション）
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_groups')
            ->withPivot(['permission_level', 'is_approved'])
            ->withTimestamps();
    }

    /**
     * このグループのユーザー参加記録
     */
    public function userGroups(): HasMany
    {
        return $this->hasMany(UserGroup::class);
    }

    /**
     * 承認済みメンバーのみを取得
     */
    public function approvedUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('is_approved', true);
    }

    /**
     * 未承認メンバー（レベル1）を取得
     */
    public function pendingUsers(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('is_approved', false)
            ->wherePivot('permission_level', 1);
    }

    /**
     * 指定した権限レベルのユーザーを取得
     */
    public function getUsersByPermissionLevel(int $level): BelongsToMany
    {
        return $this->users()->wherePivot('permission_level', $level);
    }

    /**
     * グループ作成時にオーナーを自動でレベル4として追加
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($group) {
            $group->userGroups()->create([
                'user_id' => $group->master_user_id,
                'permission_level' => 4, // オーナー
                'is_approved' => true,   // 自動承認
            ]);
        });
    }
}
