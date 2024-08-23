// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     build: {
//         outDir: '../../public/build-airwalletmanager',
//         emptyOutDir: true,
//         manifest: true,
//     },
//     plugins: [
//         laravel({
//             publicDirectory: '../../public',
//             buildDirectory: 'build-airwalletmanager',
//             input: [
//                 __dirname + '/resources/assets/sass/app.scss',
//                 __dirname + '/resources/assets/js/app.js'
//             ],
//             refresh: true,
//         }),
//     ],
// });

export const paths = [
   'Modules/AirwalletManager/resources/assets/sass/app.scss',
   'Modules/AirwalletManager/resources/assets/js/app.js',
];