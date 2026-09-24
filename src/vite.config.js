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
    // server: {
    //     host: '0.0.0.0', // Allows connections from outside the container
    //     port: 5173,
    //     hmr: {
    //         host: 'resume.local', // Points the browser back to your host machine for live reloading
    //     },
    //     watch: {
    //         usePolling: true, // Necessary if live reloading doesn't work on Windows/WSL2
    //     },
    // },
});
