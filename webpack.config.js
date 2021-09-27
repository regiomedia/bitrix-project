const path = require('path');
const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where all compiled assets will be stored
    .setOutputPath('./local/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/local/build/')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    .disableSingleRuntimeChunk()

    // will output as web/build/app.js
    .addEntry('main', './local/assets/scripts/main.js')

    // will output as web/build/global.css
    .addStyleEntry('global', './local/assets/styles/global.scss')

    // allow sass/scss files to be processed
    .enableSassLoader()
    .enablePostCssLoader()
    .enableVueLoader()

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    // you can use this method to provide other common global variables,
    // such as '_' for the 'underscore' library
    .autoProvideVariables({
        BX: 'BX',
        'window.BX': 'BX'
    })

    .enableSourceMaps(!Encore.isProduction())

    // https://webpack.js.org/plugins/define-plugin/
    .configureDefinePlugin((options) => {
        options.DEBUG = JSON.stringify(!Encore.isProduction());
    })

    // create hashed filenames (e.g. app.abc123.css)

    .addExternals({
        jquery: 'jQuery',
        BX: 'BX'
    })

    .addAliases({
        '@': path.resolve(__dirname, 'local/assets')
    })

    .enableVersioning();

// export the final configuration
module.exports = Encore.getWebpackConfig();
