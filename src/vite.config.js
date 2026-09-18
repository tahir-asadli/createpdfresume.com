import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    resolve: {
        alias: {
            "/images": "/public/images",
            "/fonts": "/public/fonts",
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/dashboard.css",
                "resources/css/front.css",
                "resources/css/front-v2.css",
                "resources/js/app.js",
                "resources/js/front-v2.js",
                "resources/js/front-bootstrap.js",
                "resources/js/resume-downloader.js",
                "resources/js/modal.js",
                "resources/js/new-builder.js",
                "resources/css/glide-core.css",
                "resources/css/glide-theme.css",
                "resources/css/new-builder.scss",
            ],
            refresh: true,
        }),
    ],
});
