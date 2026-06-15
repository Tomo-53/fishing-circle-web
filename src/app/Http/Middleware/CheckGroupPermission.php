<?php

namespace App\Http\Middleware;

use App\Models\Group;
use App\Models\UserGroup;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * @param  int  $requiredLevel  必要な権限レベル (1-4)
     */
    public function handle(Request $request, Closure $next, int $requiredLevel): Response
    {
        // 1. ユーザーがログインしているかチェック
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'ログインが必要です');
        }

        // 2. URLからgroupを取得（ルートモデルバインディング）
        $group = $request->route('group');

        // 3. Groupモデルかチェック
        if (! $group instanceof Group) {
            abort(404, 'グループが見つかりません');
        }

        // 4. 現在のユーザーIDを取得
        $currentUserId = Auth::id();

        // 5. 【最重要】特定のグループでの権限をチェック
        $userGroup = UserGroup::where('user_id', $currentUserId)
            ->where('group_id', $group->id)  // ←この行でグループ権限の独立性を保証
            ->first();

        // 7. そのグループに参加していない場合は403エラー
        if (! $userGroup) {
            abort(403, 'このグループにアクセスする権限がありません。グループに参加申請を行ってください。');
        }

        // 8. 承認されていない場合は403エラー
        if (! $userGroup->is_approved) {
            abort(403, 'グループ参加申請が承認されていません。管理者の承認をお待ちください。');
        }

        // 9. 権限レベルが不足している場合は403エラー
        if ($userGroup->permission_level < $requiredLevel) {
            $requiredLabel = $this->getPermissionLabel($requiredLevel);
            $currentLabel = $this->getPermissionLabel($userGroup->permission_level);
            abort(403, "この機能には{$requiredLabel}が必要です。現在の権限: {$currentLabel}");
        }

        // 10. リクエストにグループ情報を追加（コントローラーで使用可能）
        $request->merge([
            'current_group' => $group,
            'current_user_group' => $userGroup,
        ]);

        // 11. 全てのチェックをパスした場合のみ次へ進む

        return $next($request);
    }

    /**
     * 権限レベルのラベルを取得（UserGroup の定数を唯一の定義として参照）
     */
    private function getPermissionLabel(int $level): string
    {
        return UserGroup::PERMISSION_LABELS[$level] ?? "レベル{$level}";
    }
}
