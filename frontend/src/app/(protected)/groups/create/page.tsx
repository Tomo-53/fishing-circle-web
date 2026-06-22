"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";
import { Alert } from "@/components/ui/alert";
import { Nav } from "@/components/ui/nav";
import { api, ApiError, fetchCsrfCookie } from "@/lib/api";
import type { Group } from "@/types";

export default function CreateGroupPage() {
  const router = useRouter();
  const [name, setName] = useState("");
  const [error, setError] = useState("");
  const [fieldError, setFieldError] = useState("");
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setFieldError("");
    setIsLoading(true);

    try {
      await fetchCsrfCookie();
      const group = await api.post<Group>("/api/groups", { name });
      router.push(`/groups/${group.id}`);
    } catch (err) {
      if (err instanceof ApiError) {
        if (err.errors?.name) {
          setFieldError(err.errors.name[0]);
        } else {
          setError(err.message);
        }
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <Nav />
      <div className="max-w-lg mx-auto px-4 py-10">
        <h1 className="text-2xl font-bold text-gray-800 mb-8">新規グループ作成</h1>

        <div className="card">
          {error && <div className="mb-4"><Alert type="error" message={error} onClose={() => setError("")} /></div>}
          <form onSubmit={handleSubmit} className="space-y-5">
            <div>
              <label htmlFor="name" className="label">
                グループ名
                <span className="text-gray-400 font-normal text-xs ml-2">（1〜50文字）</span>
              </label>
              <input
                id="name"
                type="text"
                value={name}
                onChange={(e) => { setName(e.target.value); setFieldError(""); }}
                maxLength={50}
                required
                className="input"
                aria-describedby={fieldError ? "name-error" : undefined}
              />
              {fieldError && (
                <p id="name-error" className="mt-1 text-xs text-red-600" role="alert">{fieldError}</p>
              )}
              <p className="mt-1 text-xs text-gray-400">{name.length}/50</p>
            </div>

            <div className="bg-ocean-50 rounded-button p-4 text-sm text-ocean-700">
              <p className="font-medium mb-1">グループ作成時の注意</p>
              <ul className="list-disc list-inside space-y-1 text-ocean-600">
                <li>作成者は自動的にオーナーになります</li>
                <li>メンバーの参加申請を承認できます</li>
              </ul>
            </div>

            <div className="flex gap-3">
              <button type="submit" disabled={isLoading} className="btn btn-primary flex-1 py-2.5">
                {isLoading ? "作成中..." : "グループを作成"}
              </button>
              <button type="button" onClick={() => router.back()} className="btn btn-outline">
                キャンセル
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
