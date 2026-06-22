"use client";

import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useState,
} from "react";
import { api, ApiError, fetchCsrfCookie } from "@/lib/api";
import type { User } from "@/types";

interface AuthContextValue {
  user: User | null;
  isLoading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, email: string, password: string, passwordConfirmation: string) => Promise<void>;
  logout: () => Promise<void>;
  revalidate: () => Promise<void>;
}

const AuthContext = createContext<AuthContextValue | null>(null);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  const revalidate = useCallback(async () => {
    try {
      const u = await api.get<User>("/api/user");
      setUser(u);
    } catch (e) {
      if (e instanceof ApiError && e.status === 401) {
        setUser(null);
      }
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => {
    revalidate();
  }, [revalidate]);

  const login = async (email: string, password: string) => {
    await fetchCsrfCookie();
    const u = await api.post<User>("/api/login", { email, password });
    setUser(u);
  };

  const register = async (
    name: string,
    email: string,
    password: string,
    passwordConfirmation: string
  ) => {
    await fetchCsrfCookie();
    const u = await api.post<User>("/api/register", {
      name,
      email,
      password,
      password_confirmation: passwordConfirmation,
    });
    setUser(u);
  };

  const logout = async () => {
    await api.post("/api/logout");
    setUser(null);
  };

  return (
    <AuthContext.Provider
      value={{ user, isLoading, login, register, logout, revalidate }}
    >
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth(): AuthContextValue {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used within AuthProvider");
  return ctx;
}
