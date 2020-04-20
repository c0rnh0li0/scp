const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js/app.js')
   .sass('resources/sass/app.scss', 'public/css/app.css');
   //.sass('resources/sass/home.scss', 'public/css/style.css');

mix.copyDirectory('resources/homepage/img', 'public/img');
mix.copyDirectory('resources/img', 'public/img');

mix.copy('resources/js/manifest.json', 'public/manifest.json');
mix.copy('resources/js/sw.js', 'public/sw.js');
mix.copy('resources/js/firebase-messaging-sw.js', 'public/firebase-messaging-sw.js');
//mix.copy('resources/homepage/js/bottom.js', 'public/js/home/bottom.js');
