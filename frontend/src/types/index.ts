// ユーザー
export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at: string | null;
  created_at: string;
}

// グループ
export interface Group {
  id: number;
  name: string;
  master_user_id: number;
  master_user: User | null;
  approved_users_count?: number;
  created_at: string;
  updated_at: string;
}

// 権限レベル
export const PERMISSION_LEVEL = {
  PENDING: 1,
  MEMBER: 2,
  ADMIN: 3,
  OWNER: 4,
} as const;

export type PermissionLevel = (typeof PERMISSION_LEVEL)[keyof typeof PERMISSION_LEVEL];

export const PERMISSION_LABEL: Record<PermissionLevel, string> = {
  1: "承認待ち",
  2: "メンバー",
  3: "管理者",
  4: "オーナー",
};

export const PERMISSION_COLOR: Record<PermissionLevel, string> = {
  1: "bg-yellow-100 text-yellow-800",
  2: "bg-blue-100 text-blue-800",
  3: "bg-green-100 text-green-800",
  4: "bg-purple-100 text-purple-800",
};

// グループメンバー
export interface Member {
  id: number;
  name: string;
  email: string;
  permission_level: PermissionLevel;
  permission_label: string;
  permission_short: string;
  is_approved: boolean;
  joined_at: string;
}

// 現在ユーザーのグループ内権限情報
export interface CurrentUserGroup {
  permission_level: PermissionLevel;
  permission_label: string;
  permission_short: string;
  is_approved: boolean;
  is_owner: boolean;
  is_admin: boolean;
  has_admin_perm: boolean;
}

// ページネーション
export interface Pagination {
  total: number;
  per_page: number;
  current_page: number;
  last_page: number;
}

// API エラーレスポンス
export interface ApiError {
  message: string;
  code?: string;
  errors?: Record<string, string[]>;
}
