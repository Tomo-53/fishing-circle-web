"use client";

import Link from "next/link";
import { use, useEffect, useState } from "react";
import { Alert } from "@/components/ui/alert";
import { Nav } from "@/components/ui/nav";
import { PermissionBadge } from "@/components/ui/permission-badge";
import { api, ApiError } from "@/lib/api";
import type { CurrentUserGroup, Group, Member } from "@/types";

interface ShowResponse {
  group: Group;
  members: Member[];
  current_user_group: CurrentUserGroup;
  pending_count: number;
}

export default function GroupShowPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = use(params);
  const [data, setData] = useState<ShowResponse | null>(null);
  const [error, setError] = useState("");

  useEffect(() => {
    api.get<ShowResponse>(`/api/groups/${id}`)
      .then(setData)
      .catch((e) => { if (e instanceof ApiError) setError(e.message); });
  }, [id]);

  if (error) {
    return (
      <div className="min-h-screen bg-gray-50">
        <Nav />
        <div className="max-w-4xl mx-auto px-4 py-10">
          <Alert type="error" message={error} />
        </div>
      </div>
    );
  }

  if (!data) {
    return <div className="min-h-screen flex items-center justify-center text-gray-500">読み込み中...</div>;
  }

  const { group, members, current_user_group, pending_count } = data;

  return (
    <div className="min-h-screen bg-gray-50">
      <Nav />
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {/* ヘッダー */}
        <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
          <div>
            <h1 className="text-2xl font-bold text-gray-800 mb-1">{group.name}</h1>
            <p className="text-sm text-gray-500">
              オーナー: {group.master_user?.name ?? "不明"} ·
              メンバー {group.approved_users_count}人
            </p>
          </div>
          <div className="flex gap-3 items-center">
            <PermissionBadge level={current_user_group.permission_level} />
            {current_user_group.has_admin_perm && (
              <Link href={`/groups/${id}/members`} className="btn btn-outline text-sm relative">
                メンバー管理
                {pending_count > 0 && (
                  <span className="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center">
                    {pending_count}
                  </span>
                )}
              </Link>
            )}
          </div>
        </div>

        {/* メンバー一覧 */}
        <div className="card">
          <h2 className="text-lg font-semibold text-gray-700 mb-5">
            メンバー（{members.length}人）
          </h2>
          {members.length === 0 ? (
            <p className="text-gray-500 text-sm text-center py-8">メンバーがいません</p>
          ) : (
            <ul className="divide-y divide-gray-100">
              {members.map((m) => (
                <li key={m.id} className="flex items-center justify-between py-3">
                  <div className="flex items-center gap-3">
                    <div className="w-9 h-9 rounded-full bg-ocean-100 text-ocean-700 flex items-center justify-center text-sm font-semibold">
                      {m.name[0].toUpperCase()}
                    </div>
                    <div>
                      <p className="text-sm font-medium text-gray-800">{m.name}</p>
                      <p className="text-xs text-gray-400">{m.email}</p>
                    </div>
                  </div>
                  <PermissionBadge level={m.permission_level} />
                </li>
              ))}
            </ul>
          )}
        </div>

        <div className="mt-6">
          <Link href="/groups" className="text-sm text-ocean-600 hover:underline">← グループ一覧に戻る</Link>
        </div>
      </div>
    </div>
  );
}
