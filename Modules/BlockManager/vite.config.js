// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     build: {
//         outDir: '../../public/build-blockmanager',
//         emptyOutDir: true,
//         manifest: true,
//     },
//     plugins: [
//         laravel({
//             publicDirectory: '../../public',
//             buildDirectory: 'build-blockmanager',
//             input: [
//                 __dirname + '/resources/assets/sass/app.scss',
//                 __dirname + '/resources/assets/js/app.js'
//             ],
//             refresh: true,
//         }),
//     ],
// });

export const paths = [
   'Modules/BlockManager/resources/assets/sass/app.scss',
   'Modules/BlockManager/resources/assets/js/app.js',
];