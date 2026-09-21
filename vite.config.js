import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import { resolve } from 'path';

// Dentro do container o dev server precisa escutar em 0.0.0.0 e anunciar o
// HMR como "localhost", que e o endereco que o browser do host enxerga.
const inDocker = process.env.VITE_USE_POLLING !== undefined;

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
    server: inDocker
        ? {
              host: '0.0.0.0',
              port: 5173,
              strictPort: true,
              hmr: { host: 'localhost', protocol: 'ws', clientPort: 5173 },
              watch: {
                  usePolling: process.env.VITE_USE_POLLING === 'true',
                  interval: 300,
              },
          }
        : undefined,
});
