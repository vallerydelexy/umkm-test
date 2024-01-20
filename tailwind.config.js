/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.{js,ts,jsx,tsx,vue,blade.php}",
        "./src/pages/**/*.{js,ts,jsx,tsx,vue}",
    ],
    theme: {
        extend: {},
    },
    plugins: [require("@tailwindcss/forms")],
};
