import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import VueRouter from "unplugin-vue-router/vite";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        VueRouter({}),
        vue(),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
            "@resources": path.resolve(__dirname, "resources"),
            "@src": path.resolve(__dirname, "src"),
            "@": path.resolve(__dirname),
            "@store": path.resolve(__dirname, "resources/js/store"),
            "@components": path.resolve(__dirname, "resources/js/components"),
        },
    },
    optimizeDeps: {
        include: ["@fawmi/vue-google-maps", "fast-deep-equal"],
    },
});
