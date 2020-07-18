const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
        fontFamily: {
            sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        },
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/ui'),
    require('@tailwindcss/typography'),
  ]
}
