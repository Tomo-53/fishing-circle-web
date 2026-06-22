"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState } from "react";
import { useAuth } from "@/contexts/auth-context";

export function Nav() {
  const { user, logout } = useAuth();
  const router = useRouter();
  const [menuOpen, setMenuOpen] = useState(false);

  const handleLogout = async () => {
    await logout();
    router.push("/");
  };

  return (
    <nav className="bg-white border-b border-gray-200 shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16 items-center">
          {/* ロゴ */}
          <Link
            href="/"
            className="font-display font-bold text-ocean-700 text-lg tracking-wide"
          >
            🎣 新潟大学釣り同好会
          </Link>

          {/* デスクトップメニュー */}
          <div className="hidden md:flex items-center gap-6">
            <Link href="/about" className="text-sm text-gray-600 hover:text-ocean-600 transition-colors">
              サークルについて
            </Link>
            <Link href="/activities" className="text-sm text-gray-600 hover:text-ocean-600 transition-colors">
              活動
            </Link>
            <Link href="/gallery" className="text-sm text-gray-600 hover:text-ocean-600 transition-colors">
              ギャラリー
            </Link>
            {user ? (
              <>
                <Link href="/dashboard" className="text-sm text-gray-600 hover:text-ocean-600 transition-colors">
                  ダッシュボード
                </Link>
                <Link href="/groups" className="text-sm text-gray-600 hover:text-ocean-600 transition-colors">
                  グループ
                </Link>
                <div className="relative">
                  <button
                    onClick={() => setMenuOpen(!menuOpen)}
                    className="flex items-center gap-2 text-sm text-gray-700 hover:text-ocean-600 transition-colors"
                  >
                    <span className="w-8 h-8 rounded-full bg-ocean-100 text-ocean-700 flex items-center justify-center font-medium text-sm">
                      {user.name[0].toUpperCase()}
                    </span>
                    <span>{user.name}</span>
                  </button>
                  {menuOpen && (
                    <div className="absolute right-0 mt-2 w-48 bg-white rounded-card shadow-floating py-1 z-50 border border-gray-100">
                      <Link
                        href="/profile"
                        className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        onClick={() => setMenuOpen(false)}
                      >
                        プロフィール設定
                      </Link>
                      <button
                        onClick={handleLogout}
                        className="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                      >
                        ログアウト
                      </button>
                    </div>
                  )}
                </div>
              </>
            ) : (
              <>
                <Link href="/login" className="btn btn-outline text-sm py-1.5 px-4">
                  ログイン
                </Link>
                <Link href="/register" className="btn btn-primary text-sm py-1.5 px-4">
                  会員登録
                </Link>
              </>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
}
