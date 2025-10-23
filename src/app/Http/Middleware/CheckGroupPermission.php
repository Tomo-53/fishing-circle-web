<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\UserGroup;
use Symfony\Component\HttpFoundation\Response;

class CheckGroupPermission
{
    /**
     * グループ権限チェックミドルウェア
     *
     * このミドルウェアは以下を保証します：
     * 1. ユーザーが指定されたグループに参加している
     * 2. そのグループ内で承認されている
     * 3. 必要な権限レベルを持っている
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int $requiredLevel 必要な権限レベル (1-4)
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function handle(Request $request, Closure $next, int $requiredLevel): Response
    {
        // 1. ユーザーがログインしているかチェック
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'ログインが必要です');
        }

        // 2. URLからgroup_idを取得
        $groupId = $request->route('group');

        // 3. グループIDが数値かチェック
        if (!is_numeric($groupId)) {
            abort(404, 'グループが見つかりません');
        }

        // 4. グループが存在するかチェック
        $group = Group::find($groupId);
        if (!$group) {
            abort(404, 'グループが見つかりません');
        }

        // 5. 現在のユーザーIDを取得
        $currentUserId = Auth::id();

        // 6. 【最重要】特定のグループでの権限をチェック
        $userGroup = UserGroup::where('user_id', $currentUserId)
            ->where('group_id', $groupId)  // ←この行でグループ権限の独立性を保証
            ->first();

        // 7. そのグループに参加していない場合は403エラー
        if (!$userGroup) {
            abort(403, 'このグループにアクセスする権限がありません。グループに参加申請を行ってください。');
        }

        // 8. 承認されていない場合は403エラー
        if (!$userGroup->is_approved) {
            abort(403, 'グループ参加申請が承認されていません。管理者の承認をお待ちください。');
        }

        // 9. 権限レベルが不足している場合は403エラー
        if ($userGroup->permission_level < $requiredLevel) {
            $requiredLabel = $this->getPermissionLabel($requiredLevel);
            $currentLabel = $userGroup->getPermissionLabelAttribute();
            abort(403, "この機能には{$requiredLabel}が必要です。現在の権限: {$currentLabel}");
        }

        // 10. リクエストにグループ情報を追加（コントローラーで使用可能）
        $request->merge([
            'current_group' => $group,
            'current_user_group' => $userGroup
        ]);

        // 11. 全てのチェックをパスした場合のみ次へ進む

        return $next($request);
    }

    /**
     * 権限レベルのラベルを取得
     */
    private function getPermissionLabel(int $level): string
    {
        return match($level) {
            1 => '認証待機',
            2 => '一般メンバー',
            3 => '管理者・幹部',
            4 => 'グループオーナー',
            default => "レベル{$level}",
        };
    }
}
