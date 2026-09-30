<?php

namespace App\Actions\Group;

use App\Models\Group;
use App\Models\User;

/**
 * 管理者を一般メンバーに降格させるユースケース。
 *
 * 業務ルールは Group::demote() に委譲する。
 * コントローラ・ジョブ・テストから同一ロジックを再利用できる。
 */
final class DemoteMember
{
    public function __invoke(Group $group, User $target, User $actor): void
    {
        $group->demote($target, $actor);
    }
}
