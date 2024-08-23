// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     build: {
//         outDir: '../../public/build-stripemanager',
//         emptyOutDir: true,
//         manifest: true,
//     },
//     plugins: [
//         laravel({
//             publicDirectory: '../../public',
//             buildDirectory: 'build-stripemanager',
//             input: [
//                 __dirname + '/resources/assets/sass/app.scss',
//                 __dirname + '/resources/assets/js/app.js'
//             ],
//             refresh: true,
//         }),
//     ],
// });

export const paths = [
   'Modules/StripeManager/resources/assets/sass/app.scss',
   'Modules/StripeManager/resources/assets/js/app.js',
];