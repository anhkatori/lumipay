// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     build: {
//         outDir: '../../public/build-admintheme',
//         emptyOutDir: true,
//         manifest: true,
//     },
//     plugins: [
//         laravel({
//             publicDirectory: '../../public',
//             buildDirectory: 'build-admintheme',
//             input: [
//                 __dirname + '/resources/assets/sass/app.scss',
//                 __dirname + '/resources/assets/js/app.js'
//             ],
//             refresh: true,
//         }),
//     ],
// });

export const paths = [
   'Modules/AdminTheme/resources/assets/sass/app.scss',
   'Modules/AdminTheme/resources/assets/js/app.js',
];