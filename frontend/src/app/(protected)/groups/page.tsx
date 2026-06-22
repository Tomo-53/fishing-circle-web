"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { Alert } from "@/components/ui/alert";
import { Nav } from "@/components/ui/nav";
import { useAuth } from "@/contexts/auth-context";
import { api, ApiError } from "@/lib/api";
import type { Group } from "@/types";

export default function MyGroupsPage() {
  const { user, isLoading } = useAuth();
  const [groups, setGroups] = useState<Group[]>([]);
  const [error, setError] = useState("");
  const [isFetching, setIsFetching] = useState(true);

  useEffect(() => {
    if (!user) return;
    api.get<Group[]>("/api/groups")
      .then(setGroups)
      .catch((e) => { if (e instanceof ApiError) setError(e.message); })
      .finally(() => setIsFetching(false));
  }, [user]);

  if (isLoading || isFetching) {
    return <div className="min-h-screen flex items-center justify-center text-gray-500 text-sm">読み込み中...</div>;
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <Nav />
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <h1 className="text-2xl font-bold text-gray-800">マイグループ</h1>
          <div className="flex gap-3">
            <Link href="/groups/all" className="btn btn-outline">グループを探す</Link>
            <Link href="/groups/create" className="btn btn-primary">新規作成</Link>
          </div>
        </div>

        {error && <div className="mb-6"><Alert type="error" message={error} /></div>}

        {groups.length === 0 ? (
          <div className="card text-center py-16">
            <span className="text-5xl block mb-4">🎣</span>
            <p className="text-gray-500 mb-6">まだグループに参加していません</p>
            <div className="flex gap-4 justify-center">
              <Link href="/groups/create" className="btn btn-primary">グループを作成</Link>
              <Link href="/groups/all" className="btn btn-outline">グループを探す</Link>
            </div>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {groups.map((g) => (
              <Link key={g.id} href={`/groups/${g.id}`} className="card card-hover block">
                <h3 className="text-lg font-semibold text-gray-800 mb-2">{g.name}</h3>
                <p className="text-sm text-gray-500 mb-3">
                  オーナー: {g.master_user?.name ?? "不明"}
                </p>
                {g.approved_users_count != null && (
                  <p className="text-xs text-gray-400">メンバー {g.approved_users_count}人</p>
                )}
                <div className="mt-4 text-ocean-600 text-sm font-medium">詳細を見る →</div>
              </Link>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
