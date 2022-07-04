/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        {
            pattern: /bg|text|ring-(red|green|blue|orange)-(100|200|300|400|500|600|700|800)/,
           //  pattern:  /text-(red|green|blue|orange)-(600|700|800|500)/,
             //  pattern: /ring-(red|green|blue|orange)-400/,

            variants: ['dark', 'hover', 'focus', 'lg:hover', 'dark:hover'],

        },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
