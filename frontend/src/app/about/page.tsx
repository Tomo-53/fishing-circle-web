import { Nav } from "@/components/ui/nav";

export default function AboutPage() {
  return (
    <div className="min-h-screen">
      <Nav />
      <div className="max-w-4xl mx-auto px-4 py-16">
        <h1 className="text-3xl font-bold text-ocean-900 mb-8">サークルについて</h1>
        <div className="card mb-8">
          <h2 className="text-xl font-semibold mb-4">活動方針</h2>
          <p className="text-gray-600 leading-relaxed">
            新潟大学釣り同好会は、釣りを通じて自然と親しみ、仲間と楽しい時間を過ごすことを目的としています。
            初心者から上級者まで、幅広いメンバーが互いに技術を教え合いながら活動しています。
          </p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {[
            { label: "設立", value: "2018年" },
            { label: "メンバー数", value: "約30名" },
            { label: "主な活動場所", value: "新潟県内各所" },
            { label: "活動頻度", value: "月2〜4回" },
          ].map((item) => (
            <div key={item.label} className="card">
              <div className="text-sm text-gray-500 mb-1">{item.label}</div>
              <div className="text-lg font-semibold text-gray-800">{item.value}</div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
