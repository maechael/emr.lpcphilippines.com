import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'public/backend/assets/css/app.css',
                'public/backend/assets/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
