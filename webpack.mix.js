let mix = require('laravel-mix');

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

mix.js(['resources/assets/js/app.js',
        'resources/js/library-book.js',
        'resources/js/review-estimate.js'], 'public/js/app.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .less('resources/less/style.less', 'public/css')
    .styles('resources/css/fix.css', 'public/css/fix.css')
    .styles('resources/css/404.css', 'public/css/404.css')
    .sourceMaps()
    .version()
;
