import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { Nav } from "@/components/ui/nav";
import { Footer } from "@/components/ui/footer";

export const metadata: Metadata = {
  title: "サークル紹介",
  description:
    "新潟大学釣り同好会のサークル紹介。設立10年以上、40名以上が在籍する県内随一の大学釣り団体。",
};

export default function AboutPage() {
  return (
    <>
      <Nav />
        <main className="bg-gradient-to-br from-ocean-50 to-ocean-100 min-h-screen">
        <div className="max-w-6xl mx-auto px-4 py-8">
          <h1 className="text-4xl font-display font-bold text-ocean-800 mb-8 text-center">
            サークル紹介
          </h1>

          {/* 2×2 グリッド */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {/* 左上：テキスト */}
            <div className="bg-white rounded-card shadow-card p-8 flex flex-col justify-center">
              <h2 className="text-2xl font-bold text-ocean-800 mb-6 text-center">
                新潟大学釣り同好会とは
              </h2>
              <div className="text-gray-700 leading-relaxed space-y-4">
                <p className="font-semibold text-lg text-ocean-700">
                  大学公認サークル、設立10年以上、男女合わせて40名以上が在籍する県内随一の大学釣り団体
                </p>
                <p>初心者〜上級者、男女含めて様々なメンバーが在籍。</p>
                <p className="text-ocean-600 font-medium">
                  一緒に楽しく釣りを！そして釣り技術向上を目指して活動中。
                </p>
              </div>
            </div>

            {/* 右上：about1.jpg */}
            <div className="bg-white rounded-card shadow-card p-4 flex items-center justify-center">
              <div className="w-full h-64 rounded-lg overflow-hidden relative">
                <Image
                  src="/images/about1.jpg"
                  alt="サークル活動の様子"
                  fill
                  className="object-cover"
                />
              </div>
            </div>

            {/* 左下：about2.jpg */}
            <div className="bg-white rounded-card shadow-card p-4 flex items-center justify-center">
              <div className="w-full h-64 rounded-lg overflow-hidden relative">
                <Image
                  src="/images/about2.jpg"
                  alt="釣りの風景"
                  fill
                  className="object-cover"
                />
              </div>
            </div>

            {/* 右下：テキスト */}
            <div className="bg-white rounded-card shadow-card p-8 flex flex-col justify-center">
              <h2 className="text-2xl font-bold text-ocean-800 mb-6 text-center">
                なぜ釣りなのか
              </h2>
              <div className="text-gray-700 leading-relaxed space-y-3 text-sm">
                <p>新大に初めて来た人は驚いたはず。その海の近さに。</p>
                <p>
                  大学裏、眼下に広がる日本海。延々とのびるサーフ、水平線の先には鎮座する大いなる島&ldquo;佐渡&rdquo;。
                </p>
                <p>
                  陸を見れば信濃川と阿賀野川が作り出した広大な平野とそこに点在する潟の数々、豊かな河口域や山々の渓流。そしてここに生きる魚達。
                </p>
                <p className="font-semibold text-ocean-700">
                  新潟は多様な水辺の王国だ。
                </p>
                <p>そして新大の海の近さ、多様な水辺環境は釣りに最高の環境だ。</p>
                <p className="text-ocean-600">
                  さぁ、釣りに行こう。水辺とそこに生きる自然を感じに。
                  <br />
                  魚との出会いを求めて。
                  <br />
                  新たな発見と感動を求めて。
                </p>
                <p className="text-center font-bold text-ocean-800 italic mt-4">
                  enjoy nature, enjoy fishing
                </p>
              </div>
            </div>
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
