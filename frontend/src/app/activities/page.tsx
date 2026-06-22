import { Nav } from "@/components/ui/nav";

export default function ActivitiesPage() {
  return (
    <div className="min-h-screen">
      <Nav />
      <div className="max-w-4xl mx-auto px-4 py-16">
        <h1 className="text-3xl font-bold text-ocean-900 mb-8">活動紹介</h1>
        <div className="space-y-6">
          {activities.map((a) => (
            <div key={a.title} className="card card-hover flex gap-5 items-start">
              <span className="text-3xl shrink-0">{a.icon}</span>
              <div>
                <h2 className="text-lg font-semibold text-gray-800 mb-1">{a.title}</h2>
                <p className="text-gray-600 text-sm leading-relaxed">{a.desc}</p>
                <div className="mt-2">
                  <span className="inline-block bg-ocean-100 text-ocean-700 text-xs px-2 py-0.5 rounded-full">
                    {a.season}
                  </span>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

const activities = [
  {
    icon: "🌊",
    title: "海釣り（日本海）",
    desc: "新潟の日本海沿岸でのサビキ釣り・投げ釣りなど。アジ・サバ・キス・ヒラメを狙います。",
    season: "5月〜10月",
  },
  {
    icon: "🏞️",
    title: "川釣り（阿賀野川・信濃川）",
    desc: "大河でのコイ・フナ・ウグイ釣り。渓流ではヤマメやイワナを狙うフライフィッシングも。",
    season: "3月〜11月",
  },
  {
    icon: "🦆",
    title: "湖釣り（福島潟・鳥屋野潟）",
    desc: "バス釣りやヘラブナ釣り。静かな水面で集中して楽しめるのが魅力です。",
    season: "通年",
  },
  {
    icon: "🎓",
    title: "初心者講習会",
    desc: "釣り経験ゼロの新入生も安心。道具の選び方・仕掛けの作り方を先輩が丁寧に教えます。",
    season: "4月（新歓期）",
  },
];
