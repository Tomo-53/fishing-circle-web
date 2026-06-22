"use client";

import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { Suspense, useEffect, useRef, useState } from "react";
import { Alert } from "@/components/ui/alert";
import { Nav } from "@/components/ui/nav";
import { api, ApiError, fetchCsrfCookie } from "@/lib/api";
import type { Group, Pagination } from "@/types";

interface AllGroupsResponse {
  groups: Group[];
  pagination: Pagination;
  user_group_ids: number[];
}

function AllGroupsContent() {
  const searchParams = useSearchParams();
  const [data, setData] = useState<AllGroupsResponse | null>(null);
  const [search, setSearch] = useState(searchParams.get("search") ?? "");
  const [error, setError] = useState("");
  const [successMsg, setSuccessMsg] = useState("");
  const [joiningId, setJoiningId] = useState<number | null>(null);
  const [userGroupIds, setUserGroupIds] = useState<number[]>([]);
  const debounceRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  const fetchGroups = (q: string, page = 1) => {
    const params = new URLSearchParams({ page: String(page) });
    if (q) params.set("search", q);
    api.get<AllGroupsResponse>(`/api/groups/all?${params}`)
      .then((res) => {
        setData(res);
        setUserGroupIds(res.user_group_ids);
      })
      .catch((e) => { if (e instanceof ApiError) setError(e.message); });
  };

  useEffect(() => {
    fetchGroups(search);
  }, []);

  const handleSearch = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSearch(e.target.value);
    if (debounceRef.current) clearTimeout(debounceRef.current);
    debounceRef.current = setTimeout(() => fetchGroups(e.target.value), 400);
  };

  const handleJoin = async (groupId: number, groupName: string) => {
    setError("");
    setSuccessMsg("");
    setJoiningId(groupId);
    try {
      await fetchCsrfCookie();
      const res = await api.post<{ message: string }>(`/api/groups/${groupId}/join`);
      setSuccessMsg(res.message);
      setUserGroupIds((prev) => [...prev, groupId]);
    } catch (e) {
      if (e instanceof ApiError) setError(e.message);
    } finally {
      setJoiningId(null);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <Nav />
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <h1 className="text-2xl font-bold text-gray-800">グループを探す</h1>
          <Link href="/groups" className="btn btn-outline text-sm">← マイグループ</Link>
        </div>

        {error && <div className="mb-4"><Alert type="error" message={error} onClose={() => setError("")} /></div>}
        {successMsg && <div className="mb-4"><Alert type="success" message={successMsg} onClose={() => setSuccessMsg("")} /></div>}

        <div className="mb-6">
          <input
            type="search"
            value={search}
            onChange={handleSearch}
            placeholder="グループ名で検索..."
            className="input max-w-md"
            aria-label="グループ検索"
          />
        </div>

        {data === null ? (
          <div className="text-center text-gray-500 py-20">読み込み中...</div>
        ) : data.groups.length === 0 ? (
          <div className="card text-center py-16">
            <p className="text-gray-500">グループが見つかりませんでした</p>
          </div>
        ) : (
          <>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
              {data.groups.map((g) => {
                const isMember = userGroupIds.includes(g.id);
                return (
                  <div key={g.id} className="card">
                    <h3 className="text-lg font-semibold text-gray-800 mb-1">{g.name}</h3>
                    <p className="text-sm text-gray-500 mb-1">オーナー: {g.master_user?.name ?? "不明"}</p>
                    {g.approved_users_count != null && (
                      <p className="text-xs text-gray-400 mb-4">メンバー {g.approved_users_count}人</p>
                    )}
                    {isMember ? (
                      <Link href={`/groups/${g.id}`} className="btn btn-outline text-sm w-full justify-center">
                        詳細を見る
                      </Link>
                    ) : (
                      <button
                        onClick={() => handleJoin(g.id, g.name)}
                        disabled={joiningId === g.id}
                        className="btn btn-secondary text-sm w-full justify-center"
                      >
                        {joiningId === g.id ? "申請中..." : "参加申請する"}
                      </button>
                    )}
                  </div>
                );
              })}
            </div>

            {/* ページネーション */}
            {data.pagination.last_page > 1 && (
              <div className="flex justify-center gap-2">
                {Array.from({ length: data.pagination.last_page }, (_, i) => i + 1).map((p) => (
                  <button
                    key={p}
                    onClick={() => fetchGroups(search, p)}
                    className={`w-9 h-9 rounded-button text-sm font-medium transition-colors ${
                      p === data.pagination.current_page
                        ? "bg-ocean-500 text-white"
                        : "bg-white text-gray-700 hover:bg-gray-100 border border-gray-300"
                    }`}
                  >
                    {p}
                  </button>
                ))}
              </div>
            )}
          </>
        )}
      </div>
    </div>
  );
}

export default function AllGroupsPage() {
  return (
    <Suspense>
      <AllGroupsContent />
    </Suspense>
  );
}
