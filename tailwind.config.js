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
    customForms: theme => ({
      default: {
        'checkbox, radio': {
          iconColor: theme('colors.yellow.200'),
        },
      },
    }),
    typography: theme => ({
      default: {
        css: {
          color: theme('colors.blue.900'),
          'h1, h2, h3, h4, h5, p': {
            color: theme('colors.blue.900'),
          },
          'h1, h2, h3, h4, h5': {
            marginTop: theme('margin.4'),
            marginBottom: theme('margin.2'),
          },
          p: {
            marginTop: theme('margin.2'),
            marginBottom: theme('margin.2'),
          },
        }
      }
    }),
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
