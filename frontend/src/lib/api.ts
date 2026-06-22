/**
 * Laravel Sanctum SPA 認証向け fetch ラッパ。
 *
 * 使い方:
 *   1. /sanctum/csrf-cookie を叩いて Cookie を取得（初回ログイン前に実施）
 *   2. api.post('/api/login', { email, password }) のように呼ぶ
 *
 * 仕組み:
 *   - credentials: 'include' で Cookie（セッション・CSRF）を自動送信
 *   - CSRF Cookie から XSRF-TOKEN を読み取り、X-XSRF-TOKEN ヘッダに付与
 */

const API_URL = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000";

/**
 * ブラウザ Cookie から指定キーの値を取得する
 */
function getCookie(name: string): string | null {
  if (typeof document === "undefined") return null;
  const match = document.cookie.match(new RegExp("(?:^|; )" + name + "=([^;]*)"));
  return match ? decodeURIComponent(match[1]) : null;
}

/**
 * Sanctum の CSRF Cookie を取得する（ログイン前に必ず呼ぶ）
 */
export async function fetchCsrfCookie(): Promise<void> {
  await fetch(`${API_URL}/sanctum/csrf-cookie`, {
    credentials: "include",
  });
}

export class ApiError extends Error {
  status: number;
  errors?: Record<string, string[]>;

  constructor(message: string, status: number, errors?: Record<string, string[]>) {
    super(message);
    this.name = "ApiError";
    this.status = status;
    this.errors = errors;
  }
}

type HttpMethod = "GET" | "POST" | "PATCH" | "PUT" | "DELETE";

async function request<T>(
  method: HttpMethod,
  path: string,
  body?: unknown
): Promise<T> {
  const headers: HeadersInit = {
    "Content-Type": "application/json",
    Accept: "application/json",
    // CSRF トークンを Cookie から読んで X-XSRF-TOKEN ヘッダに付与
    ...(getCookie("XSRF-TOKEN")
      ? { "X-XSRF-TOKEN": getCookie("XSRF-TOKEN")! }
      : {}),
  };

  const res = await fetch(`${API_URL}${path}`, {
    method,
    headers,
    credentials: "include",
    body: body !== undefined ? JSON.stringify(body) : undefined,
  });

  if (!res.ok) {
    let errorData: { message?: string; errors?: Record<string, string[]> } = {};
    try {
      errorData = await res.json();
    } catch {
      // JSON でない場合は空のまま
    }
    throw new ApiError(
      errorData.message ?? `HTTP ${res.status}`,
      res.status,
      errorData.errors
    );
  }

  // 204 No Content など body がない場合
  if (res.status === 204) {
    return undefined as T;
  }

  return res.json() as Promise<T>;
}

export const api = {
  get: <T>(path: string) => request<T>("GET", path),
  post: <T>(path: string, body?: unknown) => request<T>("POST", path, body),
  patch: <T>(path: string, body?: unknown) => request<T>("PATCH", path, body),
  put: <T>(path: string, body?: unknown) => request<T>("PUT", path, body),
  delete: <T>(path: string, body?: unknown) => request<T>("DELETE", path, body),
};
