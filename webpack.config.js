const Encore = require('@symfony/webpack-encore');

Encore
    // Le répertoire où Webpack va placer les fichiers générés
    .setOutputPath('public/build/')
    // L'URL publique à partir de laquelle les fichiers générés peuvent être accessibles
    .setPublicPath('/build')
    // Ajouter l'entrée JavaScript pour ton application
    .addEntry('app', './assets/js/app.js')
    // Activer le gestionnaire de Sass si nécessaire
    .enableSassLoader()
    .enablePostCssLoader()
    .enableImageLoader()
    .enableFontLoader()
    // Activer la version des fichiers pour la gestion du cache
    .enableVersioning()
    // Activer un seul fichier runtime pour la gestion des dépendances
    .enableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();
