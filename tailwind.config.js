/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      fontFamily: {
        poppins: ['Poppins', 'sans-serif'],
      },
      colors: {
        'primary': '#1F2937',
        'secondary': '#836349',
      },
      height: {
        '50vh': '50vh',
      },
      backgroundColor: {
        'footerBg': '#F8F9FA',
        'navbarBg': '#1F2937',
      },
    },
  },
}