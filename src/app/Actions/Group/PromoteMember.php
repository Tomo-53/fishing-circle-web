<?php

namespace App\Actions\Group;

use App\Models\Group;
use App\Models\User;

/**
 * メンバーを管理者に昇格させるユースケース。
 *
 * 業務ルールは Group::promote() に委譲する。
 * コントローラ・ジョブ・テストから同一ロジックを再利用できる。
 */
final class PromoteMember
{
    public function __invoke(Group $group, User $target, User $actor): void
    {
        $group->promote($target, $actor);
    }
}
