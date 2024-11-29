import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            './public/css/app.css',
            './public/js/app.js',
        ]),
    ],
});
