import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { Nav } from "@/components/ui/nav";
import { Footer } from "@/components/ui/footer";

export const metadata: Metadata = {
  title: "活動内容",
  description:
    "新潟大学釣り同好会の活動内容。年間行事・定例会・月例釣行会など。",
};

export default function ActivitiesPage() {
  return (
    <>
      <Nav />
      <main className="bg-gradient-to-br from-nature-50 to-ocean-50 min-h-screen">
        <div className="max-w-6xl mx-auto px-4 py-8">
          <h1 className="text-4xl font-display font-bold text-nature-800 mb-8 text-center">
            活動内容
          </h1>

          {/* 8セルグリッド：写真とテキスト交互 */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 items-start">
            {/* 1. 画像1 */}
            <div className="bg-white rounded-card shadow-card p-4">
              <div className="w-full aspect-[4/3] rounded-lg overflow-hidden relative">
                <Image
                  src="/images/active1.jpg"
                  alt="活動の様子"
                  fill
                  className="object-cover"
                />
              </div>
            </div>

            {/* 2. 活動内容テキスト */}
            <div className="bg-white rounded-card shadow-card p-6 flex flex-col justify-center">
              <h2 className="text-xl font-bold text-nature-800 mb-4">活動内容</h2>
              <div className="text-gray-700 leading-relaxed space-y-3 text-sm">
                <p>
                  魚を見て、釣って、学んで、食べて……。釣りや魚に関することは何でもやります！海や川、自然と触れ合い、地球人として成長してみませんか。
                </p>
                <p>
                  兼部している人、バイトが忙しい人も大歓迎！テスト期間1週間前からは活動はありません。マイペースに釣りに行けます。基本的に活動は自由参加です。
                </p>
                <p>
                  企画は、一人で行くのが難しい釣りや車での釣行、離島での合宿からまったりハゼ釣りまで季節に合ったバラエティに富んだものとなっています。
                </p>
              </div>
            </div>

            {/* 3. 年間行事テキスト */}
            <div className="bg-white rounded-card shadow-card p-6 flex flex-col justify-center">
              <h2 className="text-xl font-bold text-nature-800 mb-4">年間行事</h2>
              <div className="text-gray-700 leading-relaxed space-y-1.5 text-xs">
                {[
                  { month: "4月", text: "お花見会、新歓説明会、新歓食事会" },
                  { month: "5月", text: "新歓釣行会（海釣り、マス釣り）" },
                  { month: "6月", text: "新歓釣行会（海釣り、マス釣り）、確コン、安全マナー講習会" },
                  { month: "7月", text: "部内戦" },
                  { month: "8月", text: "浜コン" },
                  { month: "9月", text: "夏合宿（粟島などの離島など）" },
                  { month: "10月", text: "新大祭出店、佐渡ビックゲーム" },
                  { month: "11月", text: "個人遠征など" },
                  { month: "12月", text: "忘年会、忘年釣行会（マス釣り）" },
                  { month: "1月", text: "水族館、冬合宿" },
                  { month: "2月", text: "新潟フィッシングショー" },
                  { month: "3月", text: "追いコン" },
                ].map((item) => (
                  <p key={item.month}>
                    <strong>{item.month}:</strong> {item.text}
                  </p>
                ))}
                <p className="text-nature-700 font-medium mt-2">
                  大まかにはこんな感じ！釣り会は普段の活動として毎月行ってます！
                </p>
                <p className="text-xs text-gray-500">
                  ※行事の時期やその内容は年度によって変ります。
                </p>
              </div>
            </div>

            {/* 4. 画像2 */}
            <div className="bg-white rounded-card shadow-card p-4">
              <div className="w-full aspect-[4/3] rounded-lg overflow-hidden relative">
                <Image
                  src="/images/active2.jpg"
                  alt="年間行事の様子"
                  fill
                  className="object-cover"
                />
              </div>
            </div>

            {/* 5. 画像3 */}
            <div className="bg-white rounded-card shadow-card p-4">
              <div className="w-full aspect-[4/3] rounded-lg overflow-hidden relative">
                <Image
                  src="/images/active3.jpg"
                  alt="普段の活動"
                  fill
                  className="object-cover"
                />
              </div>
            </div>

            {/* 6. 普段の活動テキスト */}
            <div className="bg-white rounded-card shadow-card p-6 flex flex-col justify-center">
              <h2 className="text-xl font-bold text-nature-800 mb-4">普段の活動</h2>
              <div className="text-gray-700 leading-relaxed space-y-3 text-sm">
                <div>
                  <h3 className="font-semibold text-nature-700 mb-1">〈定例会〉</h3>
                  <p className="text-xs">
                    直近の部員の釣果報告やミーティング、勉強会。月に2回、平日の5限後。場所は図書館グループ学習室。ここで意気投合して即日釣りに！？なんてことも！
                  </p>
                </div>
                <div>
                  <h3 className="font-semibold text-nature-700 mb-1">〈月例釣行会〉</h3>
                  <p className="text-xs">
                    月に1回、県内（主に新潟市内）の釣り場でみんなで仲良く釣り！＆めざせスキルアップ！五十嵐浜キス釣り、日和山堤防釣り、ハゼ釣り、船タイラバ…etc
                  </p>
                </div>
              </div>
            </div>

            {/* 7. 雰囲気・特徴テキスト */}
            <div className="bg-white rounded-card shadow-card p-6 flex flex-col justify-center">
              <h2 className="text-xl font-bold text-nature-800 mb-4">
                新大釣りサーの雰囲気・特徴
              </h2>
              <div className="text-gray-700 leading-relaxed space-y-2 text-xs">
                <p>
                  基本的に活動は自由参加。マイペースに参加する人、毎回の活動に参加する人など様々。目標の魚を目指して情熱を燃やす人や、近場でマイペースな釣りをする人、はたまた飲みだけ参加する人も（笑）
                </p>
                <p>
                  経験者〜初心者まで様々な人がいます。また、多くの人が兼部や、バイトとの掛け持ちをしています。
                </p>
                <p>
                  学生間の交流も盛んで、飲み会や食事会は良く行います。誘い合って一緒に釣り行くことは日常茶飯事。
                </p>
                <div className="mt-3">
                  <h3 className="font-semibold text-nature-700 mb-1">釣りの様子</h3>
                  <p>
                    月例釣行会や新歓釣行・忘年釣行ではみんなでワイワイと楽しく釣りを。その後はみんなでご飯食べに行ったり、釣った魚を料理して食事会をしたり。
                  </p>
                  <p className="text-nature-700 font-medium mt-1">
                    苦難・喜びを共にし、絆を深めた者達は最高の仲間です！さぁ、釣りをきっかけに最高の仲間をつくろう！
                  </p>
                </div>
              </div>
            </div>

            {/* 8. 画像4 */}
            <div className="bg-white rounded-card shadow-card p-4">
              <div className="w-full aspect-[4/3] rounded-lg overflow-hidden relative">
                <Image
                  src="/images/active4.jpg"
                  alt="サークルの雰囲気"
                  fill
                  className="object-cover"
                />
              </div>
            </div>
          </div>

          <div className="text-center">
            <Link href="/" className="btn btn-secondary">
              ホームに戻る
            </Link>
          </div>
        </div>
      </main>
      <Footer />
    </>
  );
}
