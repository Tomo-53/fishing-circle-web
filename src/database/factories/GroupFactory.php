<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * モデルのデフォルト状態を定義。
     * Group::boot() の created フックにより、作成と同時にオーナーの
     * UserGroup（level4, is_approved=true）が自動生成される。
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'master_user_id' => User::factory(),
        ];
    }
}
