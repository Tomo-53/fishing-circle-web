"use client";

import { useState, useEffect, useCallback } from "react";
import Image from "next/image";
import Link from "next/link";
import { Nav } from "@/components/ui/nav";
import { Footer } from "@/components/ui/footer";

const slides = [
  {
    id: 0,
    title: "目指しているもの",
    titleColor: "text-ocean-600",
    content:
      "釣り技術の向上・釣りを通した学生間の交流・釣り文化や自然環境の理解と普及\nみんなで楽しく釣りが出来ることを目指しています！",
  },
  {
    id: 1,
    title: "雰囲気は？",
    titleColor: "text-nature-600",
    content:
      "全体での釣り会では初心者も含めみんなでワイワイと釣りしてます。また、釣り以外でも定期的に宅飲み会や食事会（釣れた魚料理！タコパ！）、冬のゲーム大会⁉なんかもやって楽しんでます！\n\nさらなる釣りバカ達はしょっちゅう一緒に釣りに行ったり、定期的に遠征（佐渡粟島・福島など）に行きます。遠征で苦難・喜びを共にし絆を深めた者達は最高の仲間といえるでしょう。\n\nさぁ、釣りをきっかけに最高の仲間を手に入れよう！",
  },
  {
    id: 2,
    title: "大会実績",
    titleColor: "text-sunset-600",
    content: "",
    achievements: [
      {
        year: "2024",
        items: [
          "第5回 佐渡ビックゲーム FishRankerカップ 出場",
          "第17回 学釣連シーバス大会（GSBC） 第6位・第8位・ベストフォト賞受賞",
        ],
      },
      {
        year: "2025",
        items: [],
      },
    ],
  },
];

export default function Home() {
  const [currentSlide, setCurrentSlide] = useState(0);

  const goNext = useCallback(() => {
    setCurrentSlide((prev) => (prev + 1) % slides.length);
  }, []);

  const goPrev = useCallback(() => {
    setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);
  }, []);

  useEffect(() => {
    const mq = window.matchMedia("(prefers-reduced-motion: reduce)");
    if (mq.matches) return;
    const timer = setInterval(goNext, 5000);
    return () => clearInterval(timer);
  }, [goNext]);

  return (
    <>
      <Nav />
      <main>
        {/* ヒーローセクション：左右分割 */}
        <section className="relative min-h-screen flex flex-col md:flex-row">
          {/* 左側：写真 */}
          <div className="w-full md:w-1/2 relative min-h-[50vh] md:min-h-screen">
            <Image
              src="/images/welcome.jpg"
              alt="釣りの背景"
              fill
              className="object-cover"
              priority
            />
            <div className="absolute inset-0 bg-black/20" />
          </div>

          {/* 右側：テキスト */}
          <div className="w-full md:w-1/2 flex items-center justify-center bg-gradient-to-br from-ocean-50 to-ocean-100">
            <div className="max-w-lg px-8">
              <h1 className="text-4xl md:text-5xl font-display font-bold mb-6 text-gray-800 leading-tight">
                新潟大学
                <br />
                釣り同好会
              </h1>
              <div className="bg-white bg-opacity-95 text-gray-800 p-6 rounded-card shadow-floating">
                <h2 className="text-xl font-bold mb-4 text-ocean-600">
                  新大唯一の釣りサークル
                </h2>
                <p className="text-base leading-relaxed text-gray-700">
                  「新潟大学釣り同好会」のHPへようこそ！
                  <br />
                  当同好会のサークル概要や活動内容、入部方法などについて紹介してます。当メンバーの釣果記録や活動様子もご覧ください！
                </p>
                <div className="mt-6 flex gap-3 flex-wrap">
                  <Link href="/about" className="btn btn-primary text-sm">
                    サークルを知る
                  </Link>
                  <Link href="/join" className="btn btn-outline text-sm">
                    入部案内
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* スライダーセクション */}
        <section className="py-16 bg-gray-50">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 className="text-3xl font-display font-bold text-center text-gray-900 mb-12">
              新大釣りサークルについて
            </h2>

            <div className="relative">
              {/* 左矢印 */}
              <button
                type="button"
                onClick={goPrev}
                className="absolute left-0 top-1/2 -translate-y-1/2 z-10 p-2 text-gray-500 hover:text-ocean-600 transition-colors"
                aria-label="前のスライド"
              >
                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                </svg>
              </button>

              {/* 右矢印 */}
              <button
                type="button"
                onClick={goNext}
                className="absolute right-0 top-1/2 -translate-y-1/2 z-10 p-2 text-gray-500 hover:text-ocean-600 transition-colors"
                aria-label="次のスライド"
              >
                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                </svg>
              </button>

              {/* スライドカード */}
              <div className="px-12">
                {slides.map((slide) => (
                  <div
                    key={slide.id}
                    className={currentSlide === slide.id ? "block" : "hidden"}
                  >
                    <div className="bg-white rounded-card shadow-card p-8 mx-auto max-w-4xl min-h-[200px]">
                      <h3 className={`text-2xl font-bold mb-4 ${slide.titleColor}`}>
                        {slide.title}
                      </h3>
                      {slide.content && (
                        <p className="text-gray-700 text-lg leading-relaxed whitespace-pre-line">
                          {slide.content}
                        </p>
                      )}
                      {slide.achievements && (
                        <div className="text-gray-700 text-lg">
                          {slide.achievements.map((ach) => (
                            <div key={ach.year} className="mb-4">
                              <h4 className="font-bold text-xl mb-2">{ach.year}</h4>
                              {ach.items.length > 0 && (
                                <ul className="space-y-1">
                                  {ach.items.map((item, i) => (
                                    <li key={i}>・{item}</li>
                                  ))}
                                </ul>
                              )}
                            </div>
                          ))}
                        </div>
                      )}
                    </div>
                  </div>
                ))}
              </div>

              {/* ドットインジケーター */}
              <div className="flex justify-center mt-8 gap-3" role="tablist" aria-label="スライド選択">
                {slides.map((slide) => (
                  <button
                    key={slide.id}
                    type="button"
                    role="tab"
                    aria-selected={currentSlide === slide.id}
                    aria-label={`スライド ${slide.id + 1}`}
                    onClick={() => setCurrentSlide(slide.id)}
                    className={`w-2.5 h-2.5 rounded-full transition-colors duration-200 ${
                      currentSlide === slide.id
                        ? "bg-ocean-600"
                        : "bg-gray-300 hover:bg-gray-400"
                    }`}
                  />
                ))}
              </div>
            </div>
          </div>
        </section>

        {/* お問い合わせセクション */}
        <section className="py-16 bg-ocean-900 text-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 className="text-3xl font-display font-bold mb-8">お問い合わせ</h2>
            <div className="space-y-4">
              <div className="flex justify-center flex-wrap gap-6">
                <a
                  href="https://instagram.com/new_river_run"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center gap-2 text-white hover:text-pink-400 transition-colors duration-200"
                >
                  <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                  </svg>
                  instagram.com/new_river_run
                </a>
                <a
                  href="https://x.com/new_river_runs"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center gap-2 text-white hover:text-ocean-300 transition-colors duration-200"
                >
                  <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                  </svg>
                  x.com/new_river_runs
                </a>
              </div>
              <div className="flex justify-center">
                <a
                  href="mailto:newriverruns.projectf@gmail.com"
                  className="flex items-center gap-2 text-white hover:text-gray-300 transition-colors duration-200"
                >
                  <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                  </svg>
                  newriverruns.projectf@gmail.com
                </a>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </>
  );
}
