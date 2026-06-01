import { defineConfig } from 'vite'

export default defineConfig({
    root: '.',
    server: {
        host: '0.0.0.0',
        port: 5173,
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