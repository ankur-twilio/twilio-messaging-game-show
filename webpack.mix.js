const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/welcome-se-event/js/app.js', 'public/welcome-se-event/js')
    .sass('resources/welcome-se-event/css/app.scss', 'public/welcome-se-event/css');