const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  purge: {
    content: [
      './resources/**/*.blade.php',
      './resources/**/*.vue',
    ],
    options: {
      whitelistPatterns: [/w-/],
    }
  },
  theme: {
    extend: {
      zIndex: {
        1000: '1000',
      },
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
