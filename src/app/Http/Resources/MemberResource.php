<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * グループメンバー（UserGroup ピボット付きユーザー）の JSON 表現。
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \App\Models\UserGroup|null $pivot
 */
class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'permission_level' => $this->pivot?->permission_level?->value,
            'permission_label' => $this->pivot?->permission_level?->label(),
            'permission_short' => $this->pivot?->permission_level?->shortLabel(),
            'is_approved' => (bool) $this->pivot?->is_approved,
            'joined_at' => $this->pivot?->created_at,
        ];
    }
}
