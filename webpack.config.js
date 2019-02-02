// webpack.config.js
var Encore = require('@symfony/webpack-encore');

Encore
// directory where all compiled assets will be stored
    .setOutputPath('./local/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/local/build/')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // will output as web/build/app.js
    .addEntry('main', './local/assets/scripts/main.js')

    .enableVueLoader()

    // will output as web/build/global.css
    .addStyleEntry('global', './local/assets/styles/global.scss')

    // allow sass/scss files to be processed
    .enableSassLoader(() => {}, {resolveUrlLoader: false})
    .enablePostCssLoader()


  // allow legacy applications to use $/jQuery as a global variable
    //.autoProvidejQuery()

    // you can use this method to provide other common global variables,
    // such as '_' for the 'underscore' library
    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
        BX: 'BX',
        'window.BX': 'BX'
    })

    .enableSourceMaps(!Encore.isProduction())

    // https://webpack.js.org/plugins/define-plugin/
    .configureDefinePlugin((options) => {
        options.DEBUG =  !Encore.isProduction();
    })

    // create hashed filenames (e.g. app.abc123.css)

    .configureFilenames({
      js: '[name].[hash:8].js',
    })
  
    .enableVersioning()
;

var config =  Encore.getWebpackConfig();
config.externals = {
    jquery: 'jQuery',
    BX: 'BX'
};


// export the final configuration
module.exports = config;
