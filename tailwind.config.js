/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./public/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        navy: {
          800: '#1e3a8a',
          900: '#172554', // Warna biru navy untuk header/navbar
        }
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'], // Font profesional dan bersih
      }
    },
  },
  plugins: [],
}