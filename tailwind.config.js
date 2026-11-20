import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Noto Sans JP', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50:  '#fff0f0',
                    100: '#ffdddd',
                    200: '#ffc0c0',
                    300: '#ff9494',
                    400: '#ff5757',
                    500: '#ff2323',
                    600: '#e63946',
                    700: '#c1121f',
                    800: '#9d0208',
                    900: '#6a040f',
                    950: '#370617',
                },
            },
            animation: {
                'fade-in':    'fadeIn 0.5s ease-in-out',
                'slide-up':   'slideUp 0.4s ease-out',
                'slide-down': 'slideDown 0.3s ease-out',
            },
            keyframes: {
                fadeIn:    { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                slideUp:   { '0%': { transform: 'translateY(20px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
                slideDown: { '0%': { transform: 'translateY(-10px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
            },
        },
    },

    plugins: [forms],
};
