import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                    'resources/js/app.js',
                    'resources/css/admin.css',
                    'resources/css/bienvenida.css', 
                    'resources/css/despedida.css', 
                    'resources/css/formulario.css',
                    'resources/css/menor.css'],
            refresh: true,
        }),
    ],
});
