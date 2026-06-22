"use client";

import Link from "next/link";
import { useState } from "react";
import { Alert } from "@/components/ui/alert";
import { api, ApiError } from "@/lib/api";

export default function ForgotPasswordPage() {
  const [email, setEmail] = useState("");
  const [success, setSuccess] = useState("");
  const [error, setError] = useState("");
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setSuccess("");
    setIsLoading(true);

    try {
      const res = await api.post<{ message: string }>("/api/forgot-password", { email });
      setSuccess(res.message);
    } catch (err) {
      if (err instanceof ApiError) setError(err.message);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-ocean-50 to-white flex items-center justify-center px-4">
      <div className="w-full max-w-md">
        <div className="text-center mb-8">
          <Link href="/" className="text-3xl font-display font-bold text-ocean-700">
            🎣 釣り同好会
          </Link>
          <h1 className="mt-4 text-xl font-semibold text-gray-800">パスワードリセット</h1>
          <p className="mt-2 text-sm text-gray-600">
            登録メールアドレスにリセットリンクを送信します
          </p>
        </div>

        <div className="card">
          {success && <div className="mb-4"><Alert type="success" message={success} /></div>}
          {error && <div className="mb-4"><Alert type="error" message={error} onClose={() => setError("")} /></div>}

          <form onSubmit={handleSubmit} className="space-y-5">
            <div>
              <label htmlFor="email" className="label">メールアドレス</label>
              <input
                id="email"
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="input"
                required
                autoComplete="email"
              />
            </div>
            <button type="submit" disabled={isLoading} className="btn btn-primary w-full py-2.5">
              {isLoading ? "送信中..." : "リセットリンクを送信"}
            </button>
          </form>

          <p className="mt-6 text-center text-sm text-gray-600">
            <Link href="/login" className="text-ocean-600 hover:underline">← ログインに戻る</Link>
          </p>
        </div>
      </div>
    </div>
  );
}
