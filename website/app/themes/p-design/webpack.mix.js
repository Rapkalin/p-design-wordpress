const mix = require('laravel-mix');

let scssOptions = {
    processCssUrls: false
}

mix.options({
		terser: {
		extractComments: false,
		}
	})
  	.js('resources/js/app.js', './')
	.sass('resources/sass/style.scss', './').options(scssOptions);