/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./app/**/*.{js,vue,ts}",
    "./error.vue"
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
        mono: ['"JetBrains Mono"', 'monospace'],
      },
      colors: {
        brand: {
          50: '#eef2ff',
          100: '#e0e7ff',
          200: '#c7d2fe',
          500: '#6366f1',
          600: '#4f46e5',
          700: '#4338ca',
          800: '#3730a3',
          900: '#312e81',
          dark: '#0f172a',
        },
        primary: {
          DEFAULT: '#4f46e5',
          container: '#3730a3',
          fixed: '#e0e7ff',
          'fixed-dim': '#c7d2fe',
        },
        'on-primary': '#ffffff',
        surface: {
          DEFAULT: '#f8fafc',
          container: '#f1f5f9',
          'container-low': '#f8fafc',
          'container-high': '#e2e8f0',
          'container-lowest': '#ffffff',
          variant: '#cbd5e1',
          dim: '#94a3b8',
          bright: '#ffffff',
        },
        'on-surface': '#0f172a',
        'on-surface-variant': '#475569',
        'inverse-surface': '#0f172a',
        'inverse-on-surface': '#f8fafc',
        accent: {
          emerald: '#10b981',
          amber: '#f59e0b',
          rose: '#ef4444',
        },
        secondary: {
          DEFAULT: '#059669',
          container: '#d1fae5',
        },
        error: {
          DEFAULT: '#ef4444',
          container: '#fee2e2',
        }
      }
    },
  },
  plugins: [],
}
