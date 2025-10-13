<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'grade', // 追加: 所属学年・役職
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * このユーザーが作成したグループ（オーナーとして）
     */
    public function ownedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'master_user_id');
    }

    /**
     * このユーザーが所属するグループ（多対多リレーション）
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_groups')
            ->withPivot(['permission_level', 'is_approved'])
            ->withTimestamps();
    }

    /**
     * 承認済みのグループのみ取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function approvedGroups(): BelongsToMany
    {
        return $this->groups()->wherePivot('is_approved', true);
    }

    /**
     * このユーザーのグループ参加記録
     */
    public function userGroups(): HasMany
    {
        return $this->hasMany(UserGroup::class);
    }

    /**
     * 指定したグループでの権限レベルを取得
     */
    public function getPermissionLevel(Group $group): ?int
    {
        $userGroup = $this->userGroups()
            ->where('group_id', $group->id)
            ->first();

        return $userGroup ? $userGroup->permission_level : null;
    }

    /**
     * 指定したグループで承認済みかチェック
     */
    public function isApprovedInGroup(Group $group): bool
    {
        $userGroup = $this->userGroups()
            ->where('group_id', $group->id)
            ->first();

        return $userGroup ? $userGroup->is_approved : false;
    }
}
