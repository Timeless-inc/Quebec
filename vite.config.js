import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',    // Faz o Vite aceitar conexões externas
        port: 5173,         // (opcional) Define a porta se quiser garantir
        hmr: {
            host: '192.168.0.136', // Substitua por seu IP local, tipo '192.168.1.100'
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
