/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'medieval': {
          'gold': '#D4AF37',
          'bronze': '#CD7F32',
          'dark': '#2C1810',
          'brown': '#8B4513',
          'stone': '#8B8680',
        }
      },
      fontFamily: {
        'medieval': ['Cinzel', 'serif'],
        'medieval-decorative': ['Cinzel Decorative', 'cursive'],
        'medieval-gothic': ['UnifrakturMaguntia', 'cursive'],
      }
    },
  },
  plugins: [],
}
