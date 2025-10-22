<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGroupPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, $minLevel = 2): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // ユーザーがいずれかのグループで承認済みかつ最低権限レベルを満たしているかチェック
        $hasPermission = $user->userGroups()
            ->where('is_approved', true)
            ->where('permission_level', '>=', $minLevel)
            ->exists();

        if (!$hasPermission) {
            abort(403, 'この機能にアクセスする権限がありません。グループの承認をお待ちください。');
        }


        return $next($request);
    }
}
