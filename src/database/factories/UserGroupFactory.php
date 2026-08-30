<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserGroup>
 */
class UserGroupFactory extends Factory
{
    protected $model = UserGroup::class;

    /**
     * デフォルトは承認済み一般メンバー（level 2）。
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'group_id' => Group::factory(),
            'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
            'is_approved' => true,
        ];
    }

    /**
     * 承認待ち（level 1, is_approved=false）
     */
    public function pending(): static
    {
        return $this->state(fn () => [
            'permission_level' => UserGroup::PERMISSION_LEVEL_PENDING,
            'is_approved' => false,
        ]);
    }

    /**
     * 承認済み一般メンバー（level 2）
     */
    public function member(): static
    {
        return $this->state(fn () => [
            'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
            'is_approved' => true,
        ]);
    }

    /**
     * 承認済み管理者（level 3）
     */
    public function admin(): static
    {
        return $this->state(fn () => [
            'permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN,
            'is_approved' => true,
        ]);
    }
}
