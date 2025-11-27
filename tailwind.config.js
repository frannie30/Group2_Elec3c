import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Custom Colors from Mockup
                'dark-green': '#21510A',  // For headings and titles
                'light-green': '#88A82A',    // For the light green accents
                'magenta-secondary' : '#894D5B', // Secondary color
                'red-heart' : '#F44336', // For Liking
                'yellow-star' : '#FFC200', // For stars
                'pink-logo' : '#F89EA4', // Pink logo
                'seiun-sky' : '#F0FBF7', // Sky background
                'cinderella-gray' : '#E4E3E3' // Gray background

            },
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
