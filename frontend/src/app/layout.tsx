import type { Metadata } from "next";
import { Noto_Sans_JP, Comfortaa } from "next/font/google";
import "./globals.css";
import { AuthProvider } from "@/contexts/auth-context";

const notoSansJP = Noto_Sans_JP({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600", "700"],
  variable: "--font-noto-sans-jp",
  display: "swap",
});

const comfortaa = Comfortaa({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600", "700"],
  variable: "--font-comfortaa",
  display: "swap",
});

export const metadata: Metadata = {
  title: {
    default: "新潟大学釣り同好会",
    template: "%s - 新潟大学釣り同好会",
  },
  description:
    "新潟大学釣り同好会の公式サイト。釣果情報・活動内容・入部案内など。",
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="ja" className={`${notoSansJP.variable} ${comfortaa.variable}`}>
      <body className="font-sans antialiased bg-white text-gray-900">
        <AuthProvider>{children}</AuthProvider>
      </body>
    </html>
  );
}
