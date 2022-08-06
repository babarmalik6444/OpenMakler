const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/mix/js')
    .postCss('resources/css/app.css', 'public/mix/css', [
        require('tailwindcss'),
    ]);

if (mix.inProduction()) {
    mix.version();
}
