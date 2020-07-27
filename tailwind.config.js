const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  purge: {
    content: [
      './resources/**/*.blade.php',
      './resources/**/*.vue',
    ],
    options: {
      whitelistPatterns: [
        /w-/,
        /float-/,
        /clear-/,
      ],
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
            marginTop: theme('spacing.4'),
            marginBottom: theme('spacing.2'),
          },
          p: {
            marginTop: theme('spacing.2'),
            marginBottom: theme('spacing.2'),
          },
          'p.float-left': {
            marginTop: theme('spacing.0'),
            marginBottom: theme('spacing.0'),
            paddingRight: theme('spacing.2'),
          },
          'p.float-right': {
            marginTop: theme('spacing.0'),
            marginBottom: theme('spacing.0'),
            paddingLeft: theme('spacing.2'),
          },
          '.float-left img, .float-right img': {
            marginTop: theme('spacing.2'),
            marginBottom: theme('spacing.0'),
          }
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
