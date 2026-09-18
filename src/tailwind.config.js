import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "node_modules/preline/dist/*.js",
    ],
    safelist: [
        "!font-bold !text-orange-500 border-rose-300 bg-rose-200 text-rose-950",
        // '',
        // {
        //     pattern: /grid_1fr_2fr/,
        //     variants: ['sm', 'md', 'lg', 'xl', '2xl']
        // },
        // {
        //     pattern: /grid_1fr_1fr_1fr/,
        //     variants: ['sm', 'md', 'lg', 'xl', '2xl']
        // },
        // {
        //     pattern: /grid_2fr_2fr_2fr/,
        //     variants: ['sm', 'md', 'lg', 'xl', '2xl']
        // },
        // {
        //     pattern: /grid_3fr_3fr_3fr/,
        //     variants: ['sm', 'md', 'lg', 'xl', '2xl']
        // },
        // {
        //     pattern: /grid_3fr_3fr/,
        //     variants: ['sm', 'md', 'lg', 'xl', '2xl']
        // }
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                poppins: ["Poppins", ...defaultTheme.fontFamily.sans],
                worksans: ["Work Sans", ...defaultTheme.fontFamily.sans],
                raleway: ["Raleway", ...defaultTheme.fontFamily.sans],
            },

            maxWidth: {
                sm: "600px",
                md: "728px",
                lg: "984px",
                xl: "1280px",
            },
            colors: {
                // black: '#262626',
                // pink: '#FF2374',
                // pinkLight: '#FA75A6',
                // purple: '#9F70FC',
                // grey: '#C4C4C4',
                // lightGrey: '#F7F7F7',
                // lightPurple: '#F8F3FF',
                // darkGrey: '#5F6980',

                // black: '#262626',
                // pink: '#2326ff',
                // pinkLight: '#527cfb',
                // grey: '#C4C4C4',
                // lightGrey: '#F7F7F7',
                // lightPurple: '#F8F3FF',
                // darkGrey: '#5F6980',

                // black: '#464647',
                // pink: '#e85d46',
                // lightOrange: '#fff0ee',
                darkOrange: "#fb2e0c",
                google: "#ea4335",
                facebook: "#1877f2",
                whatsapp: "#25D366",
                // blackOrange: '#dd3215',
                // pinkLight: '#ee8c7c',
                // purple: '#e8644f',
                // grey: '#C4C4C4',
                // lightGrey: '#F7F7F7',
                // lightPurple: '#F8F3FF',
                // darkGrey: '#5F6980',
                // deepPurple: '#edd6ff'
            },
            backgroundImage: {
                check: "url('/images/template/check.svg')",
            },
        },
    },
    plugins: [require("@tailwindcss/forms"), require("preline/plugin")],
};
