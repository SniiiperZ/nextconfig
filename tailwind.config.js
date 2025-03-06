import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                "gaming-red": "#FF0033",
                "deep-black": "#1A1A1A",
                "led-green": "#00FF8C",
            },
            fontFamily: {
                play: ["Play", "sans-serif"],
                roboto: ["Roboto", "sans-serif"],
                code: ["JetBrains Mono", "monospace"],
            },
        },
    },

    plugins: [forms, typography],
};
