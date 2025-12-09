import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // CRITICAL: Explicitly using esbuild minification, NOT terser
        minify: 'esbuild',
        // Explicitly disable terser (should not be used)
        terserOptions: undefined,
        // Optimize chunk size
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                // Manual chunks for better caching
                manualChunks: {
                    'vendor': ['axios'],
                },
            },
        },
        // Source maps disabled for smaller production builds
        sourcemap: false,
    },
    server: {
        // Bind to all interfaces so hosting platforms (Render, Docker, etc.) can reach the dev server
        host: '0.0.0.0',
        // Allow overriding port via the PORT environment variable (common on hosting providers)
        port: Number(process.env.PORT) || 5173,
        strictPort: false,
        cors: true,
    },
});
