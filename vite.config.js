import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig(async () => {
    const laravel = await import('laravel-vite-plugin');

    return {
        plugins: [
            laravel.default({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
    };
});
