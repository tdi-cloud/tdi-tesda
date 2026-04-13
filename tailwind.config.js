/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'class', // ← THIS IS THE KEY
  theme: {
    extend: {},
  },
  plugins: [],
}