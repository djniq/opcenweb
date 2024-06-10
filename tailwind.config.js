/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "resources/**/*.blade.php",
    "resources/**/*.js",
    "resources/**/*.vue",
    "resources/**/**/*.vue",
    "resources/**/**/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {},
  },
  plugins: [require('flowbite/plugin')],
}

