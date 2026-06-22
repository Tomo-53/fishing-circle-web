<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // プロキシ信頼設定（Railway用）
        $middleware->trustProxies(at: '*');

        // Sanctum SPA 認証（Cookie セッション）を API ルートに適用
        $middleware->statefulApi();

        // グループ権限チェックミドルウェアを登録
        $middleware->alias([
            'check.group.permission' => \App\Http\Middleware\CheckGroupPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
