<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroupController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the groups.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Alternative approach using userGroups relationship
        $groupIds = $user->userGroups()->pluck('group_id');
        $groups = Group::whereIn('id', $groupIds)->with('masterUser')->get();

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created group in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'master_user_id' => Auth::id(),
        ]);

        // グループ作成者を自動的にオーナーとして追加
        UserGroup::create([
            'user_id' => Auth::id(),
            'group_id' => $group->id,
            'permission_level' => UserGroup::PERMISSION_LEVEL_OWNER,
            'is_approved' => true,
        ]);

        return redirect()->route('groups.index')
            ->with('success', 'グループが正常に作成されました。');
    }

    /**
     * Display the specified group.
     */
    public function show(Group $group)
    {
        $this->authorize('view', $group);

        $group->load(['users', 'masterUser']);
        $pendingMembers = $group->pendingMembers()->get();

        return view('groups.show', compact('group', 'pendingMembers'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group)
    {
        $this->authorize('update', $group);

        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group->update([
            'name' => $request->name,
        ]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'グループ情報が更新されました。');
    }

    /**
     * Remove the specified group from storage.
     */
    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'グループが削除されました。');
    }

    /**
     * Join a group (request membership)
     */
    public function join(Request $request, Group $group)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 既に参加している場合はエラー（userGroupsを使用）
        if ($user->userGroups()->where('group_id', $group->id)->exists()) {
            return back()->with('error', '既にこのグループに参加しています。');
        }

        // 参加申請を作成（承認待ち状態）
        UserGroup::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'permission_level' => UserGroup::PERMISSION_LEVEL_PENDING,
            'is_approved' => false,
        ]);

        return back()->with('success', 'グループへの参加申請を送信しました。承認をお待ちください。');
    }

    /**
     * Approve a member's request
     */
    public function approveMember(Request $request, Group $group, User $user)
    {
        $this->authorize('manageMember', $group);

        $userGroup = UserGroup::where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->first();

        if (!$userGroup) {
            return back()->with('error', 'ユーザーが見つかりません。');
        }

        $userGroup->update([
            'permission_level' => UserGroup::PERMISSION_LEVEL_MEMBER,
            'is_approved' => true,
        ]);

        return back()->with('success', $user->name . 'さんの参加を承認しました。');
    }

    /**
     * Remove a member from the group
     */
    public function removeMember(Request $request, Group $group, User $user)
    {
        $this->authorize('manageMember', $group);

        UserGroup::where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->delete();

        return back()->with('success', $user->name . 'さんをグループから削除しました。');
    }
}
