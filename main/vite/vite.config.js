import { defineConfig } from 'vite'

export default defineConfig({
    root: '.',
    server: {
        host: '0.0.0.0',
        port: 5173,
        proxy: {
            '/api': {
                target: 'http://localhost:80',  // your PHP server
                changeOrigin: true,
            }
        },
        cors: {
            origin: 'http://localhost',
        },
        hmr: {
            host: 'localhost',
        },
    },

    css: {
        preprocessorOptions: {
            scss: {}
        }
    },
    build: {
        outDir: 'css',
        rollupOptions: {
            input: 'scss/main.scss',
        }
    }
})