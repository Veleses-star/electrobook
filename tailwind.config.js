import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',   // ← обязательно
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                kid: {
                    bg: '#F0F8FF',
                    primary: '#FF9F43',
                    secondary: '#54A0FF',
                    success: '#1DD1A1',
                    danger: '#FF6B6B',
                    card: '#FFFFFF',
                },
                dark: {               // ← обязательно
                    bg: '#1a1a2e',
                    primary: '#f39c12',
                    secondary: '#3498db',
                    success: '#2ecc71',
                    danger: '#e74c3c',
                    card: '#16213e',
                }
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '2rem',
            }
        },
    },
    plugins: [forms],
};