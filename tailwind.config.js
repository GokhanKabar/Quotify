/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#FFA500',
        'secondary': '#836349',
      },
      height: {
        '50vh': '50vh',
      },
      backgroundImage: {
        'homepage': "url('../../public/images/3D_desktop_computer_computer_mouse-29771.jpg')",
      },
      backgroundColor: {
        'footerBg': '#F8F9FA',
        'navbarBg': '#1F2937',
      },
    },
  },
}