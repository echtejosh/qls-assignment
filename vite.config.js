import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',  // Allow external connections
        port: 5173,
        hmr: {
            host: 'localhost',  // HMR host for browser
            port: 5173,
        },
        watch: {
            usePolling: true,  // Important for Docker file watching
        },
    },
});
