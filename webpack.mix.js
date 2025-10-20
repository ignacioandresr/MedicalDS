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
    .sass('resources/sass/app.scss', 'public/css', {
        // Pass options to sass-loader -> sass to silence dependency deprecation warnings
        // quietDeps hides deprecation warnings from third-party dependencies (e.g., vendor bootstrap SCSS)
        sassOptions: {
            quietDeps: true
        }
    });
