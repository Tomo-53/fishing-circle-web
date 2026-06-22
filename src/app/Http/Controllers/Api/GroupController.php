<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MemberResource;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

/**
 * グループ機能の API コントローラ（Next.js SPA 向け）。
 *
 * 認可はルートの check.group.permission ミドルウェアで行い、
 * このコントローラは薄く保つ。
 */
class GroupController extends Controller
{
    /**
     * ユーザーが参加しているグループ一覧
     */
    public function myGroups(): AnonymousResourceCollection
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $groups = $user->groups()
            ->wherePivot('is_approved', true)
            ->with(['masterUser'])
            ->withCount(['approvedUsers'])
            ->get();

        return GroupResource::collection($groups);
    }

    /**
     * 全グループ一覧（検索・ページネーション付き）
     */
    public function allGroups(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $search = $request->get('search');

        $query = Group::with(['masterUser'])->withCount(['approvedUsers']);

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $allGroups = $query->orderBy('created_at', 'desc')->paginate(10);

        $userGroupIds = $user->groups()->pluck('groups.id')->toArray();

        return response()->json([
            'groups'       => GroupResource::collection($allGroups->items()),
            'pagination'   => [
                'total'        => $allGroups->total(),
                'per_page'     => $allGroups->perPage(),
                'current_page' => $allGroups->currentPage(),
                'last_page'    => $allGroups->lastPage(),
            ],
            'user_group_ids' => $userGroupIds,
        ]);
    }

    /**
     * グループ作成
     */
    public function store(StoreGroupRequest $request): JsonResponse
    {
        $group = Group::create([
            'name'           => $request->validated('name'),
            'master_user_id' => Auth::id(),
        ]);

        $group->load('masterUser');

        return response()->json(new GroupResource($group), 201);
    }

    /**
     * グループ詳細（レベル2以上）
     */
    public function show(Request $request, Group $group): JsonResponse
    {
        $currentUserGroup = $request->current_user_group;

        $members = $group->approvedUsers()
            ->withPivot(['permission_level', 'created_at'])
            ->orderByPivot('permission_level', 'desc')
            ->orderByPivot('created_at', 'asc')
            ->get();

        $pendingCount = $currentUserGroup->hasAdminPermission()
            ? $group->pendingUsers()->count()
            : 0;

        return response()->json([
            'group'              => new GroupResource($group->load('masterUser')->loadCount('approvedUsers')),
            'members'            => MemberResource::collection($members),
            'current_user_group' => [
                'permission_level' => $currentUserGroup->permission_level->value,
                'permission_label' => $currentUserGroup->permission_level->label(),
                'permission_short' => $currentUserGroup->permission_level->shortLabel(),
                'is_approved'      => $currentUserGroup->is_approved,
                'is_owner'         => $currentUserGroup->isOwner(),
                'is_admin'         => $currentUserGroup->isAdmin(),
                'has_admin_perm'   => $currentUserGroup->hasAdminPermission(),
            ],
            'pending_count' => $pendingCount,
        ]);
    }

    /**
     * グループ参加申請
     */
    public function join(Request $request, Group $group): JsonResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        $existingRecord = $group->memberRecordOf($authUser);

        if ($existingRecord) {
            $message = $existingRecord->isApproved()
                ? '既にこのグループに参加しています'
                : '既に参加申請済みです。承認をお待ちください';

            return response()->json(['message' => $message], 409);
        }

        UserGroup::create([
            'user_id'          => $authUser->id,
            'group_id'         => $group->id,
            'permission_level' => UserGroup::PERMISSION_LEVEL_PENDING,
            'is_approved'      => false,
        ]);

        return response()->json(['message' => 'グループ「'.$group->name.'」への参加申請を送信しました'], 201);
    }

    /**
     * メンバー一覧（レベル3以上）
     */
    public function members(Request $request, Group $group): JsonResponse
    {
        $approvedMembers = $group->approvedUsers()
            ->withPivot(['permission_level', 'created_at'])
            ->orderByPivot('permission_level', 'desc')
            ->orderByPivot('created_at', 'asc')
            ->get();

        $pendingMembers = $group->pendingUsers()
            ->withPivot(['created_at'])
            ->orderByPivot('created_at', 'desc')
            ->get();

        return response()->json([
            'group'           => new GroupResource($group->load('masterUser')),
            'approved_members' => MemberResource::collection($approvedMembers),
            'pending_members'  => MemberResource::collection($pendingMembers),
            'current_user_group' => [
                'permission_level' => $request->current_user_group->permission_level->value,
                'is_owner'         => $request->current_user_group->isOwner(),
            ],
        ]);
    }

    /**
     * メンバー承認（レベル3以上）
     */
    public function approveMember(Request $request, Group $group, User $user): JsonResponse
    {
        $userGroup = $group->memberRecordOf($user);

        if (! $userGroup || $userGroup->isApproved()) {
            return response()->json(['message' => '承認対象のメンバーが見つかりません'], 404);
        }

        $userGroup->update([
            'is_approved'      => true,
            'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
        ]);

        return response()->json(['message' => $user->name.'さんを承認しました']);
    }

    /**
     * メンバーを管理者に昇格（レベル4のみ）
     */
    public function promoteToAdmin(Request $request, Group $group, User $user): JsonResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if (! $group->isOwnedBy($authUser)) {
            return response()->json(['message' => 'オーナーのみが管理者を任命できます'], 403);
        }

        $userGroup = $group->memberRecordOf($user);

        if (! $userGroup || ! $userGroup->isApproved()) {
            return response()->json(['message' => '昇格対象のメンバーが見つかりません'], 404);
        }

        if ($userGroup->isOwner()) {
            return response()->json(['message' => 'オーナーの権限は変更できません'], 422);
        }

        if ($userGroup->isAdmin()) {
            return response()->json(['message' => $user->name.'さんは既に管理者です'], 422);
        }

        $userGroup->update(['permission_level' => UserGroup::PERMISSION_LEVEL_ADMIN]);

        return response()->json(['message' => $user->name.'さんを管理者に昇格させました']);
    }

    /**
     * 管理者をメンバーに降格（レベル4のみ）
     */
    public function demoteToMember(Request $request, Group $group, User $user): JsonResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if (! $group->isOwnedBy($authUser)) {
            return response()->json(['message' => 'オーナーのみが権限を変更できます'], 403);
        }

        $userGroup = $group->memberRecordOf($user);

        if (! $userGroup || ! $userGroup->isApproved()) {
            return response()->json(['message' => '降格対象のメンバーが見つかりません'], 404);
        }

        if ($userGroup->isOwner()) {
            return response()->json(['message' => 'オーナーの権限は変更できません'], 422);
        }

        if (! $userGroup->isAdmin()) {
            return response()->json(['message' => $user->name.'さんは既に一般メンバーです'], 422);
        }

        $userGroup->update(['permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER]);

        return response()->json(['message' => $user->name.'さんを一般メンバーに降格させました']);
    }

    /**
     * メンバー削除（レベル3以上）
     */
    public function removeMember(Request $request, Group $group, User $user): JsonResponse
    {
        if ($group->isOwnedBy($user)) {
            return response()->json(['message' => 'グループオーナーは削除できません'], 422);
        }

        $userGroup = $group->memberRecordOf($user);

        if (! $userGroup) {
            return response()->json(['message' => '削除対象のメンバーが見つかりません'], 404);
        }

        $userName = $user->name;
        $userGroup->delete();

        return response()->json(['message' => $userName.'さんをグループから削除しました']);
    }

    /**
     * グループ更新（レベル4のみ）
     */
    public function update(UpdateGroupRequest $request, Group $group): JsonResponse
    {
        $group->update($request->validated());

        return response()->json(new GroupResource($group->load('masterUser')));
    }

    /**
     * グループ削除（レベル4のみ）
     */
    public function destroy(Request $request, Group $group): JsonResponse
    {
        $groupName = (string) $group->name;
        $group->delete();

        return response()->json(['message' => 'グループ「'.$groupName.'」を削除しました']);
    }
}
