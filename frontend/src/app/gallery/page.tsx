import type { Metadata } from "next";
import Link from "next/link";
import { Nav } from "@/components/ui/nav";
import { Footer } from "@/components/ui/footer";

export const metadata: Metadata = {
  title: "ギャラリー",
  description: "新潟大学釣り同好会の活動写真ギャラリー。釣行の様子や釣果をご覧ください。",
};

const galleryItems = [
  {
    title: "2024年夏合宿",
    caption: "佐渡島での海釣り",
    label: "海釣りの様子",
    gradient: "from-ocean-200 to-ocean-300",
    textColor: "text-ocean-700",
  },
  {
    title: "信濃川釣行",
    caption: "アユ釣りに挑戦",
    label: "川釣りの風景",
    gradient: "from-nature-200 to-nature-300",
    textColor: "text-nature-700",
  },
  {
    title: "大物ゲット！",
    caption: "70cm級のブリ",
    label: "釣果自慢",
    gradient: "from-sunset-200 to-sunset-300",
    textColor: "text-sunset-700",
  },
  {
    title: "釣行後のBBQ",
    caption: "みんなで釣果を味わう",
    label: "BBQの様子",
    gradient: "from-warm-200 to-warm-300",
    textColor: "text-warm-700",
  },
  {
    title: "2024年春の歓迎会",
    caption: "新メンバーと一緒に",
    label: "新入生歓迎会",
    gradient: "from-ocean-100 to-nature-200",
    textColor: "text-ocean-800",
  },
  {
    title: "サークル室での活動",
    caption: "道具の手入れ",
    label: "装備メンテナンス",
    gradient: "from-nature-100 to-ocean-200",
    textColor: "text-nature-800",
  },
];

function CameraIcon({ className }: { className?: string }) {
  return (
    <svg className={className} fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
      <path
        fillRule="evenodd"
        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
        clipRule="evenodd"
      />
    </svg>
  );
}

export default function GalleryPage() {
  return (
    <>
      <Nav />
      <main className="bg-gradient-to-br from-ocean-50 to-nature-50 min-h-screen">
        <div className="max-w-6xl mx-auto px-4 py-8">
          <h1 className="text-4xl font-display font-bold text-ocean-800 mb-6 text-center">
            ギャラリー
          </h1>

          {/* ヘッダーカード */}
          <div className="bg-white rounded-card shadow-card p-8 mb-8">
            <h2 className="text-2xl font-semibold text-ocean-700 mb-4">
              <span aria-hidden="true">📸 </span>活動の思い出
            </h2>
            <p className="text-gray-700 leading-relaxed">
              サークルメンバーが撮影した釣行の様子や、釣果の写真を掲載しています。
              みんなの素敵な瞬間をお楽しみください！
            </p>
          </div>

          {/* ギャラリーグリッド */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            {galleryItems.map((item) => (
              <div
                key={item.title}
                className="bg-white rounded-card shadow-card overflow-hidden transition-all duration-200 hover:shadow-floating hover:-translate-y-0.5"
              >
                <div
                  className={`h-48 bg-gradient-to-br ${item.gradient} flex items-center justify-center`}
                >
                  <div className={`text-center ${item.textColor}`}>
                    <CameraIcon className="w-12 h-12 mx-auto mb-2" />
                    <p className="text-sm font-medium">{item.label}</p>
                  </div>
                </div>
                <div className="p-4">
                  <h3 className="font-semibold text-gray-800">{item.title}</h3>
                  <p className="text-sm text-gray-600 mt-1">{item.caption}</p>
                </div>
              </div>
            ))}
          </div>

          {/* 写真投稿について */}
          <div className="bg-white rounded-card shadow-card p-6 mb-8">
            <h2 className="text-xl font-semibold text-ocean-700 mb-4">
              <span aria-hidden="true">📝 </span>写真投稿について
            </h2>
            <p className="text-gray-700">
              メンバーの皆さんは、活動中に撮影した写真をサークルのギャラリーに投稿できます。
              ログイン後、マイページから簡単に投稿可能です。素敵な瞬間をみんなでシェアしましょう！
            </p>
          </div>

          <div className="text-center">
            <Link href="/" className="btn btn-primary">
              ホームに戻る
            </Link>
          </div>
        </div>
      </main>
      <Footer />
    </>
  );
}
