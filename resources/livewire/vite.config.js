import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['livewire.js'],
            refresh: true,
            publicDirectory: '../../publish/assets/js',
            buildDirectory: '../../publish/assets/js',
        }),
    ],
});
