import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import basicSsl from '@vitejs/plugin-basic-ssl';

export default defineConfig(({mode}) => {
    return {
        plugins: [
            laravel({
                input: 'resources/js/app.js',
                ssr: 'resources/js/ssr.js',
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
            mode === "development" ? basicSsl() : null
        ],
        ssr: {
            noExternal: ['@inertiajs/server'],
        },
        server: {
            https: true,
            hmr: {
                host: 'localhost',
                clientPort: 5174
            },
        }
    }
});
