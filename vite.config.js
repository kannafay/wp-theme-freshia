/* eslint-disable no-undef */
import { defineConfig } from 'vite'
import { resolve } from 'path'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import copy from 'rollup-plugin-copy'

export default defineConfig({
    resolve: { alias: { '@': resolve(__dirname, 'src') } },
    plugins: [
        vue(),
        tailwindcss(),
        copy({
            targets: [
                { src: 'src/images', dest: 'assets' },
            ],
            hook: 'writeBundle', // 打包完成后执行
            copyOnce: false,
        }),
    ],
    build: {
        outDir: resolve(__dirname, 'assets'),
        emptyOutDir: true, // 每次构建前清空输出目录
        rollupOptions: {
            input: {
                main: resolve(__dirname, 'src/main.js'),
            },
            output: {
                entryFileNames: 'js/main.min.js',
                chunkFileNames: 'js/[name]-[hash].min.js',
                assetFileNames: () => 'css/style.min.css',
            },
        },
    },
    server: {
        port: 777,
        strictPort: true,
        cors: true,
        origin: 'http://localhost:777',
    },
})
