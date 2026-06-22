import Link from "next/link";
import { Nav } from "@/components/ui/nav";

export default function WelcomePage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-ocean-50 via-white to-nature-50">
      <Nav />

      {/* ヒーロー */}
      <section className="relative py-20 md:py-32 text-center overflow-hidden">
        <div className="absolute inset-0 bg-[url('/ocean-bg.jpg')] bg-cover bg-center opacity-5 pointer-events-none" />
        <div className="relative max-w-4xl mx-auto px-4">
          <span className="text-6xl mb-6 block">🎣</span>
          <h1 className="text-4xl md:text-6xl font-display font-bold text-ocean-900 mb-6 leading-tight">
            新潟大学
            <br />
            <span className="text-ocean-500">釣り同好会</span>
          </h1>
          <p className="text-lg md:text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
            新潟の海・川・湖で釣りを楽しむサークルです。
            釣果情報を仲間と共有し、技術を磨き合いましょう。
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link href="/join" className="btn btn-primary text-base px-8 py-3">
              参加を検討する
            </Link>
            <Link href="/about" className="btn btn-outline text-base px-8 py-3">
              サークルを知る
            </Link>
          </div>
        </div>
      </section>

      {/* 特徴 */}
      <section className="py-16 bg-white">
        <div className="max-w-6xl mx-auto px-4">
          <h2 className="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-12">
            釣り同好会の魅力
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {features.map((f) => (
              <div key={f.title} className="card text-center card-hover">
                <span className="text-4xl block mb-4">{f.icon}</span>
                <h3 className="text-lg font-semibold text-gray-800 mb-2">{f.title}</h3>
                <p className="text-sm text-gray-600 leading-relaxed">{f.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-16 bg-ocean-600 text-white text-center">
        <div className="max-w-2xl mx-auto px-4">
          <h2 className="text-2xl md:text-3xl font-bold mb-4">一緒に釣りを楽しもう</h2>
          <p className="text-ocean-100 mb-8">
            会員登録してグループに参加することで、釣果情報の投稿・閲覧ができます。
          </p>
          <Link href="/register" className="btn bg-white text-ocean-700 hover:bg-ocean-50 px-8 py-3 text-base font-semibold">
            無料で会員登録
          </Link>
        </div>
      </section>

      <footer className="py-8 bg-gray-900 text-center text-gray-400 text-sm">
        © 2024 新潟大学釣り同好会
      </footer>
    </div>
  );
}

const features = [
  {
    icon: "🐟",
    title: "釣果共有",
    desc: "グループ内で釣果情報を共有。ポイント・仕掛け・サイズを記録して仲間に伝えましょう。",
  },
  {
    icon: "👥",
    title: "グループ管理",
    desc: "釣り場ごとや好みごとにグループを作成。会員制で情報を守りながら仲間と繋がれます。",
  },
  {
    icon: "📍",
    title: "新潟の釣り場",
    desc: "日本海・阿賀野川・信濃川など、新潟ならではの多様な釣り場でシーズンを楽しめます。",
  },
];
