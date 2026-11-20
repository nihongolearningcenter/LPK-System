import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // Minify with esbuild (default, faster than terser)
        minify: 'esbuild',
        // Smaller chunk size warning threshold
        chunkSizeWarningLimit: 500,
        rollupOptions: {
            output: {
                // Manual chunking: split Alpine.js from app bundle
                manualChunks: {
                    'alpine': ['alpinejs'],
                },
                // Cache-busting hashes in filenames
                entryFileNames: 'assets/[name]-[hash].js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash].[ext]',
            },
        },
    },
    // Optimize dev server
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
