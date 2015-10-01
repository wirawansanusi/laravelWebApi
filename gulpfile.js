var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
	mix.copy('node_modules/bootstrap-sass/assets/fonts/', 'public/fonts');
    mix.sass('app.scss');
    mix.styles(['app.css']);
    mix.scripts(['angular.js'], 'public/script/angular.js')
       .scripts(['jquery.js'], 'public/script/jquery.js')
       .scripts(['app.js'], 'public/script/app.js');
});
