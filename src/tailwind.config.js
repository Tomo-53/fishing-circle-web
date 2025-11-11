import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // カスタムカラーパレット（釣りサークル向け）
            colors: {
                // 海をイメージしたブルー系
                ocean: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',  // メインブルー
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                // 自然をイメージしたグリーン系
                nature: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',  // メイングリーン
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                // 日の出/夕日をイメージしたオレンジ系
                sunset: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f97316',  // メインオレンジ
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                    950: '#431407',
                },
                // アクセント用の暖色系
                warm: {
                    50: '#fefce8',
                    100: '#fef9c3',
                    200: '#fef08a',
                    300: '#fde047',
                    400: '#facc15',
                    500: '#eab308',  // メインイエロー
                    600: '#ca8a04',
                    700: '#a16207',
                    800: '#854d0e',
                    900: '#713f12',
                    950: '#422006',
                },
            },

            // カスタムフォントファミリー
            fontFamily: {
                sans: ['Noto Sans JP', 'Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Noto Serif JP', ...defaultTheme.fontFamily.serif],
                display: ['Comfortaa', 'Nunito', ...defaultTheme.fontFamily.sans],
            },

            // カスタムフォントサイズ
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
                '5xl': ['3rem', { lineHeight: '1' }],
                '6xl': ['3.75rem', { lineHeight: '1' }],
                '7xl': ['4.5rem', { lineHeight: '1' }],
                '8xl': ['6rem', { lineHeight: '1' }],
                '9xl': ['8rem', { lineHeight: '1' }],
                'hero': ['5rem', { lineHeight: '1.1' }],
                'display': ['4rem', { lineHeight: '1.2' }],
            },

            // カスタムスペーシング
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
                '144': '36rem',
            },

            // カスタムグリッドテンプレート
            gridTemplateColumns: {
                '13': 'repeat(13, minmax(0, 1fr))',
                '14': 'repeat(14, minmax(0, 1fr))',
                '15': 'repeat(15, minmax(0, 1fr))',
                '16': 'repeat(16, minmax(0, 1fr))',
                // 'auto-fit'自動調整（アイテムにフィット）
                'auto-fit': 'repeat(auto-fit, minmax(280px, 1fr))',
                // 'auto-fill'自動で埋める（トラックを充填）
                'auto-fill': 'repeat(auto-fill, minmax(280px, 1fr))',
            },

            // ボックスシャドウ
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'floating': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            },

            // カスタムborder-radius
            borderRadius: {
                'card': '0.75rem',
                'button': '0.5rem',
            },

            // アニメーション
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'slide-down': 'slideDown 0.3s ease-out',
                'bounce-gentle': 'bounceGentle 2s infinite',
            },

            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                bounceGentle: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5px)' },
                },
            },
        },
    },

    plugins: [
        forms,
        // カスタムコンポーネントプラグイン
        function({ addComponents, theme }) {
            addComponents({
                // ボタンコンポーネント
                '.btn': {
                    padding: theme('spacing.2') + ' ' + theme('spacing.4'),
                    borderRadius: theme('borderRadius.button'),
                    fontWeight: theme('fontWeight.medium'),
                    transition: 'all 0.2s ease-in-out',
                    display: 'inline-flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: theme('spacing.2'),
                    '&:focus': {
                        outline: '2px solid transparent',
                        outlineOffset: '2px',
                    },
                },
                '.btn-primary': {
                    backgroundColor: theme('colors.ocean.500'),
                    color: theme('colors.white'),
                    '&:hover': {
                        backgroundColor: theme('colors.ocean.600'),
                    },
                    '&:focus': {
                        boxShadow: '0 0 0 3px ' + theme('colors.ocean.200'),
                    },
                },
                '.btn-secondary': {
                    backgroundColor: theme('colors.nature.500'),
                    color: theme('colors.white'),
                    '&:hover': {
                        backgroundColor: theme('colors.nature.600'),
                    },
                    '&:focus': {
                        boxShadow: '0 0 0 3px ' + theme('colors.nature.200'),
                    },
                },
                '.btn-accent': {
                    backgroundColor: theme('colors.sunset.500'),
                    color: theme('colors.white'),
                    '&:hover': {
                        backgroundColor: theme('colors.sunset.600'),
                    },
                    '&:focus': {
                        boxShadow: '0 0 0 3px ' + theme('colors.sunset.200'),
                    },
                },
                '.btn-outline': {
                    backgroundColor: 'transparent',
                    color: theme('colors.ocean.600'),
                    border: '1px solid ' + theme('colors.ocean.300'),
                    '&:hover': {
                        backgroundColor: theme('colors.ocean.50'),
                        borderColor: theme('colors.ocean.400'),
                    },
                    '&:focus': {
                        boxShadow: '0 0 0 3px ' + theme('colors.ocean.200'),
                    },
                },

                // カードコンポーネント
                '.card': {
                    backgroundColor: theme('colors.white'),
                    borderRadius: theme('borderRadius.card'),
                    boxShadow: theme('boxShadow.card'),
                    padding: theme('spacing.6'),
                    transition: 'all 0.2s ease-in-out',
                },
                '.card-hover': {
                    '&:hover': {
                        boxShadow: theme('boxShadow.floating'),
                        transform: 'translateY(-2px)',
                    },
                },

                // コンテナ
                '.container-custom': {
                    width: '100%',
                    marginLeft: 'auto',
                    marginRight: 'auto',
                    paddingLeft: theme('spacing.4'),
                    paddingRight: theme('spacing.4'),
                    '@screen sm': {
                        maxWidth: '640px',
                        paddingLeft: theme('spacing.6'),
                        paddingRight: theme('spacing.6'),
                    },
                    '@screen md': {
                        maxWidth: '768px',
                    },
                    '@screen lg': {
                        maxWidth: '1024px',
                        paddingLeft: theme('spacing.8'),
                        paddingRight: theme('spacing.8'),
                    },
                    '@screen xl': {
                        maxWidth: '1280px',
                    },
                    '@screen 2xl': {
                        maxWidth: '1400px',
                    },
                },

                // セクション
                '.section': {
                    paddingTop: theme('spacing.16'),
                    paddingBottom: theme('spacing.16'),
                    '@screen md': {
                        paddingTop: theme('spacing.20'),
                        paddingBottom: theme('spacing.20'),
                    },
                },
                '.section-sm': {
                    paddingTop: theme('spacing.12'),
                    paddingBottom: theme('spacing.12'),
                    '@screen md': {
                        paddingTop: theme('spacing.16'),
                        paddingBottom: theme('spacing.16'),
                    },
                },
            })
        }
    ],
};
