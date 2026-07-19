<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * グループ情報の JSON 表現。
 *
 * @property int $id
 * @property \App\ValueObjects\GroupName $name
 * @property int $master_user_id
 * @property \App\Models\User|null $masterUser
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class GroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => (string) $this->name,
            'master_user_id' => $this->master_user_id,
            'master_user' => $this->whenLoaded('masterUser', fn () => new UserResource($this->masterUser)),
            'approved_users_count' => $this->whenCounted('approvedUsers'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
