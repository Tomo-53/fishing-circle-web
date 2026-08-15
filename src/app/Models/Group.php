<?php

namespace App\Models;

use App\Casts\GroupNameCast;
use App\Enums\PermissionLevel;
use App\Exceptions\GroupMembershipException;
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
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'master_user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * name は GroupName 値オブジェクトにキャストし、生成・代入時に
     * 不変条件（1〜50文字）を保証する。
     *
     * @var array<string, string>
     */
    protected $casts = [
        'master_user_id' => 'integer',
        'name' => GroupNameCast::class,
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
            ->using(UserGroup::class)
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
    public function getUsersByPermissionLevel(PermissionLevel $level): BelongsToMany
    {
        return $this->approvedUsers()->wherePivot('permission_level', $level->value);
    }

    /**
     * グループのUserGroupレコード
     */
    public function userGroups(): HasMany
    {
        return $this->hasMany(UserGroup::class);
    }

    /**
     * 指定ユーザーの UserGroup レコードを取得（user_id と group_id 両方でスコープ）
     */
    public function memberRecordOf(User $user): ?UserGroup
    {
        /** @var UserGroup|null */
        return $this->userGroups()
            ->where('user_id', $user->id)
            ->first();
    }

    /**
     * 指定ユーザーがこのグループのオーナーかチェック
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->master_user_id === $user->id;
    }

    /**
     * メンバーを管理者に昇格させる。
     *
     * 業務ルール違反は GroupMembershipException を投げる。
     * コントローラは例外を catch して getMessage() をユーザーに返せばよい。
     */
    public function promote(User $target, User $actor): void
    {
        if (! $this->isOwnedBy($actor)) {
            throw new GroupMembershipException('オーナーのみが管理者を任命できます');
        }

        $record = $this->memberRecordOf($target);
        if (! $record || ! $record->isApproved()) {
            throw new GroupMembershipException('昇格対象のメンバーが見つかりません');
        }
        if ($record->isOwner()) {
            throw new GroupMembershipException('オーナーの権限は変更できません');
        }
        if ($record->isAdmin()) {
            throw new GroupMembershipException("{$target->name}さんは既に管理者です");
        }

        $record->update(['permission_level' => PermissionLevel::Admin]);
    }

    /**
     * 管理者を一般メンバーに降格させる。
     *
     * 業務ルール違反は GroupMembershipException を投げる。
     */
    public function demote(User $target, User $actor): void
    {
        if (! $this->isOwnedBy($actor)) {
            throw new GroupMembershipException('オーナーのみが権限を変更できます');
        }

        $record = $this->memberRecordOf($target);
        if (! $record || ! $record->isApproved()) {
            throw new GroupMembershipException('降格対象のメンバーが見つかりません');
        }
        if ($record->isOwner()) {
            throw new GroupMembershipException('オーナーの権限は変更できません');
        }
        if (! $record->isAdmin()) {
            throw new GroupMembershipException("{$target->name}さんは既に一般メンバーです");
        }

        $record->update(['permission_level' => PermissionLevel::Member]);
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
