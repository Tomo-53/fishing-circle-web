/** @type {import('next').NextConfig} */
const nextConfig = {
  // Laravel API との通信設定
  async rewrites() {
    return [
      // /sanctum/csrf-cookie は直接 Laravel に転送
      {
        source: '/sanctum/:path*',
        destination: `${process.env.NEXT_PUBLIC_API_URL}/sanctum/:path*`,
      },
    ];
  },
};

export default nextConfig;
