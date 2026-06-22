import Link from "next/link";
import { Nav } from "@/components/ui/nav";

export default function JoinPage() {
  return (
    <div className="min-h-screen">
      <Nav />
      <div className="max-w-2xl mx-auto px-4 py-16">
        <h1 className="text-3xl font-bold text-ocean-900 mb-4">入会について</h1>
        <p className="text-gray-600 mb-10 leading-relaxed">
          新潟大学に在籍している方なら誰でも参加できます。
          まず会員登録をしてからグループに参加申請してください。
        </p>
        <div className="space-y-6 mb-10">
          {steps.map((s, i) => (
            <div key={i} className="card flex gap-5 items-start">
              <div className="w-8 h-8 rounded-full bg-ocean-500 text-white flex items-center justify-center text-sm font-bold shrink-0">
                {i + 1}
              </div>
              <div>
                <h3 className="font-semibold text-gray-800 mb-1">{s.title}</h3>
                <p className="text-sm text-gray-600">{s.desc}</p>
              </div>
            </div>
          ))}
        </div>
        <div className="flex gap-4">
          <Link href="/register" className="btn btn-primary px-8 py-3 flex-1 text-center justify-center">
            今すぐ会員登録
          </Link>
          <Link href="/login" className="btn btn-outline px-8 py-3 flex-1 text-center justify-center">
            ログイン
          </Link>
        </div>
      </div>
    </div>
  );
}

const steps = [
  {
    title: "会員登録",
    desc: "メールアドレスとパスワードで無料登録。1分で完了します。",
  },
  {
    title: "グループ検索・参加申請",
    desc: "参加したいグループを探して参加申請を送ります。",
  },
  {
    title: "管理者の承認を待つ",
    desc: "管理者が申請を承認すると、グループのコンテンツにアクセスできます。",
  },
  {
    title: "釣果情報を楽しむ",
    desc: "釣果情報の閲覧・投稿でグループの仲間と交流しましょう。",
  },
];
