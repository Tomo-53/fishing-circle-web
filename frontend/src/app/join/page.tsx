import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { Nav } from "@/components/ui/nav";
import { Footer } from "@/components/ui/footer";

export const metadata: Metadata = {
  title: "入部案内",
  description:
    "新潟大学釣り同好会の入部案内。年中メンバー募集中。経験者〜初心者、男女問わず大歓迎！",
};

const scheduleItems = [
  {
    period: "2月〜3月",
    borderColor: "border-sunset-400",
    bgGradient: "from-sunset-50 to-sunset-100",
    titleColor: "text-sunset-600",
    items: [
      "二次試験が終わり合格発表🌸",
      "部員達も新歓に向けて準備に入ります。4月から始まる新歓の情報を見逃さないようにしよう！",
    ],
  },
  {
    period: "4月",
    borderColor: "border-nature-500",
    bgGradient: "from-nature-50 to-nature-100",
    titleColor: "text-nature-600",
    items: ["新歓説明会", "新歓お花見会", "新歓食事会"],
  },
  {
    period: "5月",
    borderColor: "border-ocean-500",
    bgGradient: "from-ocean-50 to-ocean-100",
    titleColor: "text-ocean-600",
    items: ["新歓釣行会（in五頭フィッシングパーク）"],
  },
  {
    period: "6月",
    borderColor: "border-warm-500",
    bgGradient: "from-warm-50 to-warm-100",
    titleColor: "text-warm-600",
    items: [
      "新歓釣行会（in日和山突堤・五十嵐浜）",
      "安全・マナー講習会＆確コン",
    ],
  },
];

export default function JoinPage() {
  return (
    <>
      <Nav />
      <main className="bg-gradient-to-br from-sunset-50 to-warm-50 min-h-screen">
        <div className="max-w-4xl mx-auto px-4 py-8">
          <h1 className="text-4xl font-display font-bold text-sunset-800 mb-6 text-center">
            入部案内
          </h1>

          {/* 2×2 グリッド */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            {/* 左上：入会案内テキスト */}
            <div className="bg-white rounded-card shadow-card p-8 flex flex-col justify-center">
              <h2 className="text-2xl font-bold text-sunset-700 mb-6">
                入会は随時受け付け中！
              </h2>
              <div className="text-gray-700 leading-relaxed space-y-4">
                <p>
                  当サークルでは年中メンバー募集中です。
                  <br />
                  新歓時期以外でもOK！また、例年多くの２年生以上の方も入会してます。
                </p>
                <p className="font-semibold text-sunset-600">
                  経験者〜初心者、女子、男子問わず大歓迎！
                </p>
                <div className="bg-sunset-50 p-4 rounded-lg border border-sunset-200">
                  <p className="font-bold text-sunset-800 mb-2">
                    気になった方はX・InstagramのDMへGo!
                  </p>
                  <p className="text-sunset-700">
                    釣りを始めてみたい君、釣りをもっとしたい釣りキチ、入会を待ってるぞ！
                  </p>
                </div>
              </div>
            </div>

            {/* 右上：join1.jpg */}
            <div className="bg-white rounded-card shadow-card p-4 flex items-center justify-center">
              <div className="w-full h-64 rounded-lg overflow-hidden relative">
                <Image
                  src="/images/join1.jpg"
                  alt="入会案内画像1"
                  fill
                  className="object-cover"
                />
              </div>
            </div>

            {/* 左下：join2.jpg */}
            <div className="bg-white rounded-card shadow-card p-4 flex items-center justify-center">
              <div className="w-full h-64 rounded-lg overflow-hidden relative">
                <Image
                  src="/images/join2.jpg"
                  alt="入会案内画像2"
                  fill
                  className="object-cover"
                />
              </div>
            </div>

            {/* 右下：会費 */}
            <div className="bg-white rounded-card shadow-card p-8 flex flex-col justify-center">
              <h2 className="text-2xl font-bold text-sunset-700 mb-6">
                サークル入会費について
              </h2>
              <div className="text-gray-700 leading-relaxed space-y-4">
                <div className="bg-warm-50 border-l-4 border-warm-400 p-4 rounded-lg">
                  <h3 className="text-lg font-bold text-warm-800 mb-2">
                    ・年会費のみ（5000円以下）
                  </h3>
                  <p className="text-warm-700">
                    いただいた会費は全体活動の費用（エサ代など）や部内貸し出しタックルの整備などに充て、活発な活動や釣り技術向上を目指します。
                  </p>
                </div>
              </div>
            </div>
          </div>

          {/* 新歓の流れ見出し */}
          <div className="bg-gradient-to-r from-sunset-500 to-warm-500 text-white py-8 px-4 rounded-card mb-12">
            <h2 className="text-4xl font-display font-extrabold text-center mb-2 drop-shadow-md">
              新歓の流れ
            </h2>
            <p className="text-center text-xl font-medium drop-shadow-sm">
              年間を通した入会サポート
            </p>
          </div>

          {/* join3 + スケジュール */}
          <div className="grid md:grid-cols-5 gap-6 lg:gap-8 items-start mb-12">
            {/* 左：join3.jpg（3/5幅） */}
            <div className="md:col-span-3 bg-white rounded-card shadow-card p-4 flex items-center">
              <div className="w-full min-h-[500px] relative">
                <Image
                  src="/images/join3.jpg"
                  alt="新歓の流れ画像"
                  fill
                  className="object-contain rounded-lg"
                />
              </div>
            </div>

            {/* 右：スケジュール（2/5幅） */}
            <div className="md:col-span-2 bg-white rounded-card shadow-card p-6">
              <h3 className="text-2xl font-bold text-sunset-700 mb-6 text-center">
                <span aria-hidden="true">📅 </span>新歓スケジュール
              </h3>
              <div className="space-y-4">
                {scheduleItems.map((item) => (
                  <div
                    key={item.period}
                    className={`bg-gradient-to-r ${item.bgGradient} rounded-lg p-4 border-l-4 ${item.borderColor}`}
                  >
                    <h4 className={`text-lg font-bold mb-2 ${item.titleColor}`}>
                      {item.period}：
                    </h4>
                    {item.items.length === 1 ? (
                      <p className="text-gray-700 text-sm">{item.items[0]}</p>
                    ) : (
                      <ul className="text-gray-700 text-sm space-y-1">
                        {item.items.map((text, i) => (
                          <li key={i}>• {text}</li>
                        ))}
                      </ul>
                    )}
                  </div>
                ))}
              </div>
            </div>
          </div>

          <div className="text-center">
            <Link href="/" className="btn btn-accent">
              ホームに戻る
            </Link>
          </div>
        </div>
      </main>
      <Footer />
    </>
  );
}
