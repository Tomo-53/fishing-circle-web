"use client";

import Link from "next/link";
import { use, useEffect, useState } from "react";
import { Alert } from "@/components/ui/alert";
import { Nav } from "@/components/ui/nav";
import { PermissionBadge } from "@/components/ui/permission-badge";
import { api, ApiError, fetchCsrfCookie } from "@/lib/api";
import type { Group, Member } from "@/types";

interface MembersResponse {
  group: Group;
  approved_members: Member[];
  pending_members: Member[];
  current_user_group: { permission_level: number; is_owner: boolean };
}

export default function GroupMembersPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = use(params);
  const [data, setData] = useState<MembersResponse | null>(null);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");
  const [processingId, setProcessingId] = useState<number | null>(null);

  const load = () => {
    api.get<MembersResponse>(`/api/groups/${id}/members`)
      .then(setData)
      .catch((e) => { if (e instanceof ApiError) setError(e.message); });
  };

  useEffect(() => { load(); }, [id]);

  const action = async (
    method: "post" | "delete",
    path: string,
    userId: number,
    successMsg: string
  ) => {
    setError("");
    setSuccess("");
    setProcessingId(userId);
    try {
      await fetchCsrfCookie();
      const res = await api[method]<{ message: string }>(path);
      setSuccess(res.message ?? successMsg);
      load();
    } catch (e) {
      if (e instanceof ApiError) setError(e.message);
    } finally {
      setProcessingId(null);
    }
  };

  if (!data) {
    return <div className="min-h-screen flex items-center justify-center text-gray-500">読み込み中...</div>;
  }

  const { group, approved_members, pending_members, current_user_group } = data;
  const isOwner = current_user_group.is_owner;

  return (
    <div className="min-h-screen bg-gray-50">
      <Nav />
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h1 className="text-2xl font-bold text-gray-800">メンバー管理</h1>
            <p className="text-sm text-gray-500 mt-1">{group.name}</p>
          </div>
          <Link href={`/groups/${id}`} className="btn btn-outline text-sm">← グループ詳細</Link>
        </div>

        {error && <div className="mb-4"><Alert type="error" message={error} onClose={() => setError("")} /></div>}
        {success && <div className="mb-4"><Alert type="success" message={success} onClose={() => setSuccess("")} /></div>}

        {/* 承認待ち */}
        {pending_members.length > 0 && (
          <div className="card mb-6">
            <h2 className="text-base font-semibold text-yellow-700 mb-4 flex items-center gap-2">
              <span className="inline-block w-2 h-2 rounded-full bg-yellow-500" />
              承認待ち（{pending_members.length}人）
            </h2>
            <ul className="divide-y divide-gray-100">
              {pending_members.map((m) => (
                <li key={m.id} className="flex items-center justify-between py-3">
                  <div>
                    <p className="text-sm font-medium text-gray-800">{m.name}</p>
                    <p className="text-xs text-gray-400">{m.email}</p>
                  </div>
                  <div className="flex gap-2">
                    <button
                      onClick={() => action("post", `/api/groups/${id}/members/${m.id}/approve`, m.id, "承認しました")}
                      disabled={processingId === m.id}
                      className="btn btn-secondary text-xs py-1 px-3"
                    >
                      承認
                    </button>
                    <button
                      onClick={() => action("delete", `/api/groups/${id}/members/${m.id}`, m.id, "削除しました")}
                      disabled={processingId === m.id}
                      className="btn btn-danger text-xs py-1 px-3"
                    >
                      却下
                    </button>
                  </div>
                </li>
              ))}
            </ul>
          </div>
        )}

        {/* 承認済みメンバー */}
        <div className="card">
          <h2 className="text-base font-semibold text-gray-700 mb-4">
            承認済みメンバー（{approved_members.length}人）
          </h2>
          <ul className="divide-y divide-gray-100">
            {approved_members.map((m) => (
              <li key={m.id} className="flex items-center justify-between py-3">
                <div className="flex items-center gap-3">
                  <div className="w-8 h-8 rounded-full bg-ocean-100 text-ocean-700 flex items-center justify-center text-sm font-semibold">
                    {m.name[0].toUpperCase()}
                  </div>
                  <div>
                    <p className="text-sm font-medium text-gray-800">{m.name}</p>
                    <p className="text-xs text-gray-400">{m.email}</p>
                  </div>
                </div>
                <div className="flex items-center gap-2">
                  <PermissionBadge level={m.permission_level} />
                  {/* オーナーのみが権限変更できる */}
                  {isOwner && m.permission_level !== 4 && (
                    <>
                      {m.permission_level === 2 ? (
                        <button
                          onClick={() => action("post", `/api/groups/${id}/members/${m.id}/promote`, m.id, "管理者に昇格しました")}
                          disabled={processingId === m.id}
                          className="btn btn-outline text-xs py-0.5 px-2"
                        >
                          管理者に昇格
                        </button>
                      ) : m.permission_level === 3 ? (
                        <button
                          onClick={() => action("post", `/api/groups/${id}/members/${m.id}/demote`, m.id, "降格しました")}
                          disabled={processingId === m.id}
                          className="btn btn-outline text-xs py-0.5 px-2"
                        >
                          メンバーに降格
                        </button>
                      ) : null}
                    </>
                  )}
                  {/* 管理者以上が除名できる（オーナー除く） */}
                  {m.permission_level < 4 && (
                    <button
                      onClick={() => action("delete", `/api/groups/${id}/members/${m.id}`, m.id, "除名しました")}
                      disabled={processingId === m.id}
                      className="btn text-xs py-0.5 px-2 text-red-600 hover:bg-red-50 border border-red-200"
                    >
                      除名
                    </button>
                  )}
                </div>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  );
}
