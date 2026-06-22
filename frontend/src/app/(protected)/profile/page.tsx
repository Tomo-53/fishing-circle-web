"use client";

import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { Alert } from "@/components/ui/alert";
import { Nav } from "@/components/ui/nav";
import { useAuth } from "@/contexts/auth-context";
import { api, ApiError, fetchCsrfCookie } from "@/lib/api";
import type { User } from "@/types";

export default function ProfilePage() {
  const { user, isLoading, revalidate } = useAuth();
  const router = useRouter();
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [deletePassword, setDeletePassword] = useState("");
  const [success, setSuccess] = useState("");
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [deleteError, setDeleteError] = useState("");
  const [isSaving, setIsSaving] = useState(false);
  const [isDeleting, setIsDeleting] = useState(false);
  const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);

  useEffect(() => {
    if (user) {
      setName(user.name);
      setEmail(user.email);
    }
  }, [user]);

  if (isLoading) return <div className="min-h-screen flex items-center justify-center"><div className="text-gray-500">読み込み中...</div></div>;
  if (!user) {
    router.push("/login");
    return null;
  }

  const handleUpdate = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    setSuccess("");
    setIsSaving(true);

    try {
      await fetchCsrfCookie();
      await api.patch<User>("/api/profile", { name, email });
      await revalidate();
      setSuccess("プロフィールを更新しました");
    } catch (err) {
      if (err instanceof ApiError && err.errors) {
        const fe: Record<string, string> = {};
        for (const [k, msgs] of Object.entries(err.errors)) fe[k] = msgs[0];
        setErrors(fe);
      }
    } finally {
      setIsSaving(false);
    }
  };

  const handleDelete = async () => {
    setDeleteError("");
    setIsDeleting(true);
    try {
      await fetchCsrfCookie();
      await api.delete("/api/profile", { password: deletePassword });
      router.push("/");
    } catch (err) {
      if (err instanceof ApiError) setDeleteError(err.message);
    } finally {
      setIsDeleting(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <Nav />
      <div className="max-w-2xl mx-auto px-4 py-10">
        <h1 className="text-2xl font-bold text-gray-800 mb-8">プロフィール設定</h1>

        {/* 基本情報更新 */}
        <div className="card mb-8">
          <h2 className="text-lg font-semibold mb-5">基本情報</h2>
          {success && <div className="mb-4"><Alert type="success" message={success} /></div>}
          <form onSubmit={handleUpdate} className="space-y-5">
            <div>
              <label htmlFor="name" className="label">お名前</label>
              <input id="name" type="text" value={name} onChange={(e) => setName(e.target.value)} className="input" required />
              {errors.name && <p className="mt-1 text-xs text-red-600" role="alert">{errors.name}</p>}
            </div>
            <div>
              <label htmlFor="email" className="label">メールアドレス</label>
              <input id="email" type="email" value={email} onChange={(e) => setEmail(e.target.value)} className="input" required />
              {errors.email && <p className="mt-1 text-xs text-red-600" role="alert">{errors.email}</p>}
            </div>
            <button type="submit" disabled={isSaving} className="btn btn-primary">
              {isSaving ? "保存中..." : "変更を保存"}
            </button>
          </form>
        </div>

        {/* アカウント削除 */}
        <div className="card border border-red-200">
          <h2 className="text-lg font-semibold text-red-700 mb-2">アカウント削除</h2>
          <p className="text-sm text-gray-600 mb-4">削除すると元に戻せません。</p>
          {!showDeleteConfirm ? (
            <button onClick={() => setShowDeleteConfirm(true)} className="btn btn-danger">
              アカウントを削除する
            </button>
          ) : (
            <div className="space-y-4">
              {deleteError && <Alert type="error" message={deleteError} />}
              <div>
                <label htmlFor="delete-password" className="label">パスワードを入力して確認</label>
                <input
                  id="delete-password"
                  type="password"
                  value={deletePassword}
                  onChange={(e) => setDeletePassword(e.target.value)}
                  className="input border-red-300"
                />
              </div>
              <div className="flex gap-3">
                <button onClick={handleDelete} disabled={isDeleting} className="btn btn-danger">
                  {isDeleting ? "削除中..." : "削除を確定する"}
                </button>
                <button onClick={() => setShowDeleteConfirm(false)} className="btn btn-outline">
                  キャンセル
                </button>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
