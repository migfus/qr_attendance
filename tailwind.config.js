/** @type {import('tailwindcss').Config} */

const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

export default {
    content: ['./resources/views/**/*.blade.php', './resources/js/**/*.{vue,ts,js}', './node_modules/vue-tailwind-datepicker/**/*.js'],
    darkMode: 'media',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'Open Sans', ...defaultTheme.fontFamily.sans]
            },
            boxShadow: {
                right: 'inset -36px -6px 51px -57px rgba(0,0,0,0.3)',
                left: ' inset 45px -6px 51px -57px rgba(0,0,0,0.3)'
                // swipeRight: 'inset -10px 0px 38px -11px rgba(0,0,0,0.52)'
            },
            colors: {
                // brand: {
                //   '50': '#f5f8f7',
                //   '100': '#dee9e5',
                //   '200': '#bcd3cb',
                //   '300': '#93b5aa',
                //   '400': '#6d9489',
                //   '500': '#527a6f',
                //   '600': '#3c5a53',
                //   '700': '#364f49',
                //   '800': '#2e413c',
                //   '900': '#293835',
                //   '950': '#141f1c',
                // },
                'vtd-primary': colors.sky, // Light mode Datepicker color
                // "vtd-secondary": colors.gray, // Dark mode Datepicker color

                brand: {
                    50: '#eff2ef',
                    100: '#e0e7e0',
                    200: '#c1cec1',
                    300: '#9bad9c',
                    400: '#758c77',
                    500: '#5b715e',
                    600: '#475a49',
                    700: '#3c493e',
                    800: '#323d34',
                    900: '#2d342f',
                    950: '#171c18'
                }
            }
        }
    },
    plugins: [require('@tailwindcss/forms'), require('tailwindcss-primeui')]
}
