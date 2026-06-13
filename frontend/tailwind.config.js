/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#BE0199',
          50: '#FCE6F9',
          100: '#F9CCF3',
          200: '#F399E7',
          300: '#ED66DB',
          400: '#E733CF',
          500: '#BE0199',
          600: '#98017A',
          700: '#72015C',
          800: '#4C003D',
          900: '#26001F',
        },
        secondary: {
          DEFAULT: '#2E8B7A',
          50: '#E8F5F2',
          100: '#D1EBE5',
          200: '#A3D7CB',
          300: '#75C3B1',
          400: '#47AF97',
          500: '#2E8B7A',
          600: '#256F62',
          700: '#1C534A',
          800: '#133731',
          900: '#0A1B19',
        },
        safe: {
          bg: '#FEFAEE',
          card: '#FFFFFF',
          text: '#181818',
          muted: '#718096',
        },
        brand: {
          yellow: '#FFE85C',
          green: '#DDFF82',
          'green-light': '#F3FDD8',
          pink: '#F9DBFF',
        },
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'Inter', 'system-ui', '-apple-system', 'sans-serif'],
        display: ['Qaligo', 'serif'],
      },
    },
  },
  plugins: [],
}