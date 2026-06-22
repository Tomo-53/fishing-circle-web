import { Nav } from "@/components/ui/nav";

export default function GalleryPage() {
  return (
    <div className="min-h-screen">
      <Nav />
      <div className="max-w-6xl mx-auto px-4 py-16">
        <h1 className="text-3xl font-bold text-ocean-900 mb-2">ギャラリー</h1>
        <p className="text-gray-600 mb-10">会員の釣果写真ギャラリーです。</p>
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          {Array.from({ length: 8 }).map((_, i) => (
            <div
              key={i}
              className="aspect-square rounded-card bg-gradient-to-br from-ocean-100 to-nature-100 flex items-center justify-center text-4xl shadow-card"
            >
              🐟
            </div>
          ))}
        </div>
        <p className="text-center text-gray-500 text-sm mt-10">
          釣果写真は会員ログイン後に閲覧・投稿できます
        </p>
      </div>
    </div>
  );
}
