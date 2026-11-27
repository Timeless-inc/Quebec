import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Support/*.php',
    ],

    theme: {
        extend: {
            // Paleta do Manual de Marca SRE v1.0
            colors: {
                floresta: '#063B2E',
                esmeralda: '#047857',
                campus: '#10A557',
                menta: '#DCF3E6',
                papel: '#F5F8F4',
                lima: '#C6F36B',
                musgo: '#3D5A4E',
                tinta: '#0C1A15',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            transitionTimingFunction: {
                'out-expo': 'cubic-bezier(0.16, 1, 0.3, 1)',
            },
        },
    },

    plugins: [forms],
};
