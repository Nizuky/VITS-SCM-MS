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
        // Minification
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.logs in production
                drop_debugger: true,
            },
        },
        // Source maps (disable in production for smaller size)
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
