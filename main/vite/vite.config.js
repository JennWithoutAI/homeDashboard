// this has been auto generated
// main/vite.config.js
import { defineConfig } from 'vite'

export default defineConfig({
    root: '.',
    server: {
        host: '0.0.0.0',      // expose outside container
        port: 5173,
        hmr: {
            host: 'localhost',  // browser connects here for HMR websocket
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