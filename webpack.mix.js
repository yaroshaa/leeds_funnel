const mix = require('laravel-mix');

mix.sass('resources/sass/app.scss', 'public/css/app.css')
  .js('resources/js/app.js', 'public/js/app.js')
  .webpackConfig({
    output: {
      chunkFilename: 'js/chunks/[name].js',
    },
  });

if (mix.inProduction()) {
  mix.version();
}
