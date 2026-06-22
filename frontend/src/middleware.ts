import { NextRequest, NextResponse } from "next/server";

/**
 * Next.js ミドルウェア — 認証必須ルートの保護。
 *
 * Sanctum Cookie セッションはサーバ間で転送できないため、
 * Cookie の有無だけを確認してリダイレクトを判断する。
 * 実際の認証確認は各ページの Client Component 側で /api/user を叩く。
 */
export function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl;

  // 保護ルートのプレフィックス
  const isProtectedRoute =
    pathname.startsWith("/dashboard") ||
    pathname.startsWith("/profile") ||
    pathname.startsWith("/groups");

  // ゲスト専用ルート（ログイン済みなら / へ）
  const isGuestOnlyRoute =
    pathname.startsWith("/login") || pathname.startsWith("/register");

  // Laravel のセッション Cookie の存在確認（名前は SESSION_DRIVER=database 時の laravel_session）
  const hasSession =
    request.cookies.has("laravel_session") ||
    request.cookies.has("XSRF-TOKEN");

  if (isProtectedRoute && !hasSession) {
    return NextResponse.redirect(new URL("/login", request.url));
  }

  if (isGuestOnlyRoute && hasSession) {
    return NextResponse.redirect(new URL("/dashboard", request.url));
  }

  return NextResponse.next();
}

export const config = {
  matcher: [
    "/dashboard/:path*",
    "/profile/:path*",
    "/groups/:path*",
    "/login",
    "/register",
  ],
};
