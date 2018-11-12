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
        'resources/js/user.js',
        'resources/js/review.js',
        'resources/js/review-create.js',
        'resources/js/review-delete.js',
        'resources/js/review-restore.js',
        'resources/js/review-edit.js',
        'resources/js/user-edit.js',
        'resources/js/book-edit.js',
        'resources/js/book-list.js'], 'public/js/app.js')
    .js(['resources/js/book-list/book-list.js'], 'public/js/book-list.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .less('resources/less/style.less', 'public/css')
    .less('resources/less/style-1.0/style.less', 'public/css/style-1.0.css')
    .styles('resources/css/fix.css', 'public/css/fix.css')
    .styles('resources/css/404.css', 'public/css/404.css')
    .styles('resources/css/normalize.css', 'public/css/normalize.css')
    .sourceMaps()
    .version()
;

