"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { Alert } from "@/components/ui/alert";
import { Nav } from "@/components/ui/nav";
import { useAuth } from "@/contexts/auth-context";
import { api, ApiError } from "@/lib/api";
import type { Group } from "@/types";

export default function DashboardPage() {
  const { user, isLoading } = useAuth();
  const [groups, setGroups] = useState<Group[]>([]);
  const [error, setError] = useState("");

  useEffect(() => {
    if (!user) return;
    api.get<Group[]>("/api/groups")
      .then(setGroups)
      .catch((e) => {
        if (e instanceof ApiError) setError(e.message);
      });
  }, [user]);

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-gray-500 text-sm">読み込み中...</div>
      </div>
    );
  }

  if (!user) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <p className="text-gray-600 mb-4">ログインが必要です</p>
          <Link href="/login" className="btn btn-primary">ログイン</Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <Nav />
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 className="text-2xl font-bold text-gray-800 mb-2">
          ようこそ、{user.name}さん
        </h1>
        <p className="text-gray-500 text-sm mb-8">新潟大学釣り同好会へ</p>

        {error && (
          <div className="mb-6">
            <Alert type="error" message={error} onClose={() => setError("")} />
          </div>
        )}

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          <Link href="/groups" className="card card-hover text-center group">
            <span className="text-3xl block mb-2">👥</span>
            <div className="text-2xl font-bold text-ocean-600 mb-1">{groups.length}</div>
            <div className="text-sm text-gray-600">参加中のグループ</div>
          </Link>
          <Link href="/groups/all" className="card card-hover text-center">
            <span className="text-3xl block mb-2">🔍</span>
            <div className="text-sm font-semibold text-gray-700 mt-2">グループを探す</div>
            <div className="text-xs text-gray-500 mt-1">新しいグループに参加</div>
          </Link>
          <Link href="/groups/create" className="card card-hover text-center">
            <span className="text-3xl block mb-2">➕</span>
            <div className="text-sm font-semibold text-gray-700 mt-2">グループを作る</div>
            <div className="text-xs text-gray-500 mt-1">仲間を集めよう</div>
          </Link>
        </div>

        {groups.length > 0 && (
          <>
            <h2 className="text-lg font-semibold text-gray-700 mb-4">参加中のグループ</h2>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
              {groups.map((g) => (
                <Link
                  key={g.id}
                  href={`/groups/${g.id}`}
                  className="card card-hover block"
                >
                  <h3 className="font-semibold text-gray-800 mb-1">{g.name}</h3>
                  <p className="text-sm text-gray-500">
                    オーナー: {g.master_user?.name ?? "不明"}
                  </p>
                  {g.approved_users_count != null && (
                    <p className="text-xs text-gray-400 mt-1">
                      メンバー {g.approved_users_count}人
                    </p>
                  )}
                </Link>
              ))}
            </div>
          </>
        )}
      </div>
    </div>
  );
}
