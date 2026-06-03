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
          DEFAULT: '#1A5C7A',
          50: '#E6F2F7',
          100: '#CCE5EF',
          200: '#99CBDF',
          300: '#66B1CF',
          400: '#3397BF',
          500: '#1A5C7A',
          600: '#154A62',
          700: '#10384A',
          800: '#0B2631',
          900: '#051319',
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
          bg: '#F0F7F4',
          card: '#FFFFFF',
          text: '#2D3748',
          muted: '#718096',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
