/** @type {import('tailwindcss').Config} */
module.exports = {
  presets: [
    require('tailwindcss-preset-email'),
  ],
  content: [
    './components/**/*.html',
    './emails/**/*.html',
    './layouts/**/*.html',
  ],
  theme: {
    extend: {
      colors: {
        'blumine': {
          '50': '#f4f7fb',
          '100': '#e8eff6',
          '200': '#ccdeeb',
          '300': '#9ec2db',
          '400': '#6aa3c6',
          '500': '#4887af',
          '600': '#366c93',
          '700': '#2f5c7e',
          '800': '#284a64',
          '900': '#264054',
          '950': '#192a38',
        },
      },
    },
  },
}
