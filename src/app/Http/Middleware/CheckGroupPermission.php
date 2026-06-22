<?php

namespace App\Http\Middleware;

use App\Enums\PermissionLevel;
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
            if ($request->expectsJson()) {
                return response()->json(['message' => 'ログインが必要です'], 401);
            }

            return redirect()->route('login')->with('error', 'ログインが必要です');
        }

        // 2. URLからgroupを取得（ルートモデルバインディング）
        $group = $request->route('group');

        // 3. Groupモデルかチェック
        if (! $group instanceof Group) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'グループが見つかりません'], 404);
            }
            abort(404, 'グループが見つかりません');
        }

        // 4. 現在のユーザーIDを取得
        $currentUserId = Auth::id();

        // 5. 【最重要】特定のグループでの権限をチェック（user_id × group_id の二重スコープ）
        $userGroup = UserGroup::where('user_id', $currentUserId)
            ->where('group_id', $group->id)
            ->first();

        // 6. そのグループに参加していない場合は403エラー
        if (! $userGroup) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'このグループにアクセスする権限がありません。グループに参加申請を行ってください。',
                    'code'    => 'NOT_MEMBER',
                ], 403);
            }
            abort(403, 'このグループにアクセスする権限がありません。グループに参加申請を行ってください。');
        }

        // 7. 承認されていない場合は403エラー
        if (! $userGroup->is_approved) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'グループ参加申請が承認されていません。管理者の承認をお待ちください。',
                    'code'    => 'PENDING_APPROVAL',
                ], 403);
            }
            abort(403, 'グループ参加申請が承認されていません。管理者の承認をお待ちください。');
        }

        // 8. 権限レベルが不足している場合は403エラー
        $required = PermissionLevel::from($requiredLevel);
        if (! $userGroup->permission_level->atLeast($required)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "この機能には{$required->label()}が必要です。現在の権限: {$userGroup->permission_level->label()}",
                    'code'    => 'INSUFFICIENT_PERMISSION',
                    'required_level' => $required->value,
                    'current_level'  => $userGroup->permission_level->value,
                ], 403);
            }
            abort(403, "この機能には{$required->label()}が必要です。現在の権限: {$userGroup->permission_level->label()}");
        }

        // 9. リクエストにグループ情報を追加（コントローラーで使用可能）
        $request->merge([
            'current_group'      => $group,
            'current_user_group' => $userGroup,
        ]);

        return $next($request);
    }
}
