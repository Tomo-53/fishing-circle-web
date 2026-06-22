"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState } from "react";
import { Alert } from "@/components/ui/alert";
import { useAuth } from "@/contexts/auth-context";
import { ApiError } from "@/lib/api";

export default function RegisterPage() {
  const { register } = useAuth();
  const router = useRouter();
  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [generalError, setGeneralError] = useState("");
  const [isLoading, setIsLoading] = useState(false);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));
    setErrors((prev) => ({ ...prev, [e.target.name]: "" }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    setGeneralError("");
    setIsLoading(true);

    try {
      await register(
        form.name,
        form.email,
        form.password,
        form.password_confirmation
      );
      router.push("/dashboard");
    } catch (err) {
      if (err instanceof ApiError) {
        if (err.status === 422 && err.errors) {
          const fieldErrors: Record<string, string> = {};
          for (const [key, msgs] of Object.entries(err.errors)) {
            fieldErrors[key] = msgs[0];
          }
          setErrors(fieldErrors);
        } else {
          setGeneralError(err.message);
        }
      }
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
          <h1 className="mt-4 text-xl font-semibold text-gray-800">会員登録</h1>
        </div>

        <div className="card">
          {generalError && (
            <div className="mb-4">
              <Alert type="error" message={generalError} onClose={() => setGeneralError("")} />
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-5">
            {[
              { id: "name", label: "お名前", type: "text", autoComplete: "name" },
              { id: "email", label: "メールアドレス", type: "email", autoComplete: "email" },
              { id: "password", label: "パスワード", type: "password", autoComplete: "new-password" },
              {
                id: "password_confirmation",
                label: "パスワード（確認）",
                type: "password",
                autoComplete: "new-password",
              },
            ].map((field) => (
              <div key={field.id}>
                <label htmlFor={field.id} className="label">
                  {field.label}
                </label>
                <input
                  id={field.id}
                  name={field.id}
                  type={field.type}
                  value={form[field.id as keyof typeof form]}
                  onChange={handleChange}
                  className="input"
                  required
                  autoComplete={field.autoComplete}
                  aria-describedby={errors[field.id] ? `${field.id}-error` : undefined}
                />
                {errors[field.id] && (
                  <p id={`${field.id}-error`} className="mt-1 text-xs text-red-600" role="alert">
                    {errors[field.id]}
                  </p>
                )}
              </div>
            ))}

            <button
              type="submit"
              disabled={isLoading}
              className="btn btn-primary w-full py-2.5 text-base"
            >
              {isLoading ? "登録中..." : "会員登録する"}
            </button>
          </form>

          <p className="mt-6 text-center text-sm text-gray-600">
            既にアカウントをお持ちの方は{" "}
            <Link href="/login" className="text-ocean-600 hover:underline font-medium">
              ログイン
            </Link>
          </p>
        </div>
      </div>
    </div>
  );
}
