import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                cream: '#FAF6EC',
                surface: '#FFFFFF',
                denim: {
                    DEFAULT: '#2B3A55',
                    light: '#3E5273',
                    dark: '#1D2940',
                },
                thread: {
                    DEFAULT: '#C97B2E',
                    light: '#E0A25F',
                },
                ink: '#1F2A37',
                moss: '#4B7F52',
                brick: '#B23A2E',
            },
            fontFamily: {
                display: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                sans: ['"Public Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [forms],
};
