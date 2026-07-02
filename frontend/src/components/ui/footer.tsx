import Link from "next/link";

export function Footer() {
  return (
    <footer className="bg-ocean-900 text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {/* サークル情報 */}
          <div>
            <h3 className="font-display font-bold text-xl text-ocean-200 mb-4">
              新潟大学釣り同好会
            </h3>
            <p className="text-ocean-300 text-sm leading-relaxed">
              新潟大学公認サークル。設立10年以上、男女合わせて40名以上が在籍する県内随一の大学釣り団体。
            </p>
          </div>

          {/* リンク */}
          <div>
            <h4 className="font-semibold text-ocean-200 mb-4">メニュー</h4>
            <ul className="space-y-2">
              {[
                { href: "/", label: "ホーム" },
                { href: "/about", label: "サークル紹介" },
                { href: "/activities", label: "活動内容" },
                { href: "/gallery", label: "ギャラリー" },
                { href: "/join", label: "入部案内" },
              ].map((link) => (
                <li key={link.href}>
                  <Link
                    href={link.href}
                    className="text-ocean-400 hover:text-white text-sm transition-colors duration-200"
                  >
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* お問い合わせ */}
          <div>
            <h4 className="font-semibold text-ocean-200 mb-4">お問い合わせ</h4>
            <div className="space-y-3">
              <a
                href="https://instagram.com/new_river_run"
                target="_blank"
                rel="noopener noreferrer"
                className="flex items-center gap-2 text-ocean-400 hover:text-pink-400 text-sm transition-colors duration-200"
              >
                <svg className="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
                instagram.com/new_river_run
              </a>
              <a
                href="https://x.com/new_river_runs"
                target="_blank"
                rel="noopener noreferrer"
                className="flex items-center gap-2 text-ocean-400 hover:text-ocean-200 text-sm transition-colors duration-200"
              >
                <svg className="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                </svg>
                x.com/new_river_runs
              </a>
              <a
                href="mailto:newriverruns.projectf@gmail.com"
                className="flex items-center gap-2 text-ocean-400 hover:text-white text-sm transition-colors duration-200"
              >
                <svg className="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                </svg>
                newriverruns.projectf@gmail.com
              </a>
            </div>
          </div>
        </div>

        <div className="border-t border-ocean-800 mt-10 pt-6 text-center">
          <p className="text-ocean-500 text-sm">
            &copy; {new Date().getFullYear()} 新潟大学釣り同好会. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  );
}
