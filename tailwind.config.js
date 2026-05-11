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
                'entrego-blue': {
                    DEFAULT: '#007BFF',
                    50:  '#E6F2FF',
                    100: '#CCE5FF',
                    500: '#007BFF',
                    600: '#0069D9',
                    700: '#0056B3',
                },
                primary:    '#4F46E5',
                secondary:  '#6B7280',
                accent:     '#10B981',
                background: '#F9FAFB',
                surface:    '#FFFFFF',
            },
            fontFamily: {
                sans: ['Figtree', 'Roboto', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                xl: '0.75rem',
            },
        },
    },

    plugins: [forms],
};
