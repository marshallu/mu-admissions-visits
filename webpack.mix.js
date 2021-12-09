const mix = require('laravel-mix');

mix.setPublicPath('./');

mix.postCss('./source/css/mu-admissions-visits.css', 'css/mu-admissions-visits.css', [
    require('postcss-import'),
    require('postcss-nesting'),
    require('tailwindcss'),
		require('autoprefixer')
  ]
);

if (mix.inProduction()) {
    mix.version();
}
