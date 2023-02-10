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

mix.autoload({
    jquery: ['$', 'window.jQuery']
}).webpackConfig({
    stats: {
        children: true,
    },})
    .js('resources/js/home.js', 'public/js')
    .js('resources/js/voiture.js', 'public/js')
    .js('resources/js/agence.js', 'public/js')
    .js('resources/js/entretien.js', 'public/js')
    .js('resources/js/reparation.js', 'public/js')
    .js('resources/js/location.js', 'public/js')
    .js('resources/js/assurance.js', 'public/js')
    .js('resources/js/consommation.js', 'public/js')
    .js('resources/js/user.js', 'public/js')
    .js('resources/js/fournisseur.js','public/js')
    .js('resources/js/profil.js','public/js')
    .js('resources/js/commandes.js','public/js')
    .js('resources/js/GestionVoitureFournisseur.js','public/js')
    .js('resources/js/voitureDatatable.js','public/js')
    .js('node_modules/jquery/dist/jquery.js','public/js')
    .js('node_modules/bootstrap/dist/js/bootstrap.bundle.js','public/js')
    .js('node_modules/@fortawesome/fontawesome-free/js/fontawesome.js','public/js')
    .css('node_modules/@fortawesome/fontawesome-free/css/all.css','public/css')
    .css('node_modules/datatables.net-bs5/css/dataTables.bootstrap5.css','public/css')
    .css('resources/css/style.css','public/css')
    .css('resources/css/CustomScrollbar.css','public/css')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
