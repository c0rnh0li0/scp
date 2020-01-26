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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/homepage/js/all.js', 'public/js/home/all.js')
    .sass('resources/sass/home.scss', 'public/css/home/all.css');

mix.copyDirectory('resources/homepage/img', 'public/img');

mix.copy('resources/homepage/js/bottom.js', 'public/js/home/bottom.js');