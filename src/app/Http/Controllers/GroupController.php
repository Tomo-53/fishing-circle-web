<?php

namespace App\Http\Controllers;

use App\Actions\Group\DemoteMember;
use App\Actions\Group\PromoteMember;
use App\Exceptions\GroupMembershipException;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    use AuthorizesRequests;

    /**
     * ユーザーが参加しているグループ一覧を表示
     * 認証のみ必要（グループ権限チェック不要）
     */
    public function myGroups()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $groups = $user->groups()
            ->wherePivot('is_approved', true)
            ->with(['masterUser'])
            ->withCount(['approvedUsers'])
            ->get();

        return view('groups.index', compact('groups'));
    }

    /**
     * 全グループ一覧を表示（検索機能付き）
     * 認証のみ必要（グループ権限チェック不要）
     */
    public function allGroups(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $search = $request->get('search');

        $query = Group::with(['masterUser'])
            ->withCount(['approvedUsers']);

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $allGroups = $query->orderBy('created_at', 'desc')->paginate(10);

        $userGroupIds = $user->groups()->pluck('groups.id')->toArray();

        return view('groups.all', compact('allGroups', 'userGroupIds', 'search'));
    }

    /**
     * グループ作成フォームを表示
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * 新しいグループを作成
     * 作成者は自動でオーナー（レベル4）になる
     */
    public function store(StoreGroupRequest $request)
    {
        // グループ作成（Group.phpのboot()メソッドで自動的にオーナー登録される）
        $group = Group::create([
            'name' => $request->validated('name'),
            'master_user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'グループ「'.$group->name.'」が作成されました');
    }

    /**
     * グループ詳細を表示
     * レベル2以上（一般メンバー以上）が必要
     */
    public function show(Request $request, Group $group)
    {
        $currentUserGroup = $request->current_user_group;

        $members = $group->approvedUsers()
            ->withPivot(['permission_level', 'created_at'])
            ->orderByPivot('permission_level', 'desc')
            ->orderByPivot('created_at', 'asc')
            ->get();

        // 申請中メンバー数（管理者以上のみ表示）
        $pendingCount = $currentUserGroup->hasAdminPermission()
            ? $group->pendingUsers()->count()
            : 0;

        return view('groups.show', compact('group', 'members', 'currentUserGroup', 'pendingCount'));
    }

    /**
     * グループ参加申請
     * 認証のみ必要（グループ権限チェック不要）
     */
    public function join(Request $request, Group $group)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        $existingRecord = $group->memberRecordOf($authUser);

        if ($existingRecord) {
            $message = $existingRecord->isApproved()
                ? '既にこのグループに参加しています'
                : '既に参加申請済みです。承認をお待ちください';

            return back()->with('error', $message);
        }

        UserGroup::create([
            'user_id' => $authUser->id,
            'group_id' => $group->id,
            'permission_level' => UserGroup::PERMISSION_LEVEL_PENDING,
            'is_approved' => false,
        ]);

        return back()->with('success', 'グループ「'.$group->name.'」への参加申請を送信しました');
    }

    /**
     * メンバー一覧を表示
     * レベル3以上（管理者以上）が必要
     */
    public function members(Request $request, Group $group)
    {
        $currentUserGroup = $request->current_user_group;

        $approvedMembers = $group->approvedUsers()
            ->withPivot(['permission_level', 'created_at'])
            ->orderByPivot('permission_level', 'desc')
            ->orderByPivot('created_at', 'asc')
            ->get();

        $pendingMembers = $group->pendingUsers()
            ->withPivot(['created_at'])
            ->orderByPivot('created_at', 'desc')
            ->get();

        return view('groups.members', compact('group', 'approvedMembers', 'pendingMembers', 'currentUserGroup'));
    }

    /**
     * メンバー承認
     * レベル3以上（管理者以上）が必要
     */
    public function approveMember(Request $request, Group $group, User $user)
    {
        $userGroup = $group->memberRecordOf($user);

        if (! $userGroup || $userGroup->isApproved()) {
            return back()->with('error', '承認対象のメンバーが見つかりません');
        }

        $userGroup->update([
            'is_approved' => true,
            'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
        ]);

        return back()->with('success', $user->name.'さんを承認しました');
    }

    /**
     * メンバーを管理者に昇格
     * レベル4のみ（オーナーのみ）が必要
     */
    public function promoteToAdmin(Request $request, Group $group, User $user, PromoteMember $action): RedirectResponse
    {
        /** @var \App\Models\User $actor */
        $actor = $request->user();

        try {
            $action($group, $user, $actor);
        } catch (GroupMembershipException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $user->name.'さんを管理者に昇格させました');
    }

    /**
     * 管理者をメンバーに降格
     * レベル4のみ（オーナーのみ）が必要
     */
    public function demoteToMember(Request $request, Group $group, User $user, DemoteMember $action): RedirectResponse
    {
        /** @var \App\Models\User $actor */
        $actor = $request->user();

        try {
            $action($group, $user, $actor);
        } catch (GroupMembershipException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $user->name.'さんを一般メンバーに降格させました');
    }

    /**
     * メンバー削除
     * レベル3以上（管理者以上）が必要
     */
    public function removeMember(Request $request, Group $group, User $user)
    {
        if ($group->isOwnedBy($user)) {
            return back()->with('error', 'グループオーナーは削除できません');
        }

        $userGroup = $group->memberRecordOf($user);

        if (! $userGroup) {
            return back()->with('error', '削除対象のメンバーが見つかりません');
        }

        $userName = $user->name;
        $userGroup->delete();

        return back()->with('success', $userName.'さんをグループから削除しました');
    }

    // /**
    //  * グループ編集フォーム表示
    //  * レベル4のみ（オーナーのみ）
    //  */
    // public function edit(Request $request, Group $group)
    // {
    //     // ミドルウェアで権限チェック済み
    //     return view('groups.edit', compact('group'));
    // }

    /**
     * グループ情報更新
     * レベル4のみ（オーナーのみ）
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        // ミドルウェアで権限チェック済み・検証は UpdateGroupRequest に集約
        $group->update($request->validated());

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'グループ情報を更新しました');
    }

    /**
     * グループ削除
     * レベル4のみ（オーナーのみ）
     */
    public function destroy(Request $request, Group $group)
    {
        $groupName = $group->name;

        $group->delete();

        return redirect()
            ->route('groups.myGroups')
            ->with('success', 'グループ「'.$groupName.'」を削除しました');
    }
}
