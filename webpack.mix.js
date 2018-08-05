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
        'resources/js/review-estimate.js',
        'resources/js/auth.js',
        'resources/js/registration.js',
        'resources/js/user.js'], 'public/js/app.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .less('resources/less/style.less', 'public/css')
    .less('resources/less/style-1.0/style.less', 'public/css/style-1.0.css')
    .styles('resources/css/fix.css', 'public/css/fix.css')
    .styles('resources/css/404.css', 'public/css/404.css')
    .styles('resources/css/normalize.css', 'public/css/normalize.css')
    .sourceMaps()
    .version()
;
