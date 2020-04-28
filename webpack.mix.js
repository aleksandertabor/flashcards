const mix = require('laravel-mix');
const path = require('path');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')

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

mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js/')
        }
    },
    output: {
        chunkFilename: "js/chunks/[id].chunk.js"
    },
    module: {
        rules: [{
                test: /\.(graphql|gql)$/,
                exclude: /node_modules/,
                loader: 'graphql-tag/loader',
            },
            {
                test: /\.js$/,
                exclude: [
                    /node_modules\/core-js/,
                    /node_modules\/webpack\/buildin/,
                    /node_modules\/regenerator-runtime/,
                ],
                use: [{
                    loader: 'babel-loader',
                    options: mix.config.babel()
                }]
            }
        ],
    },
    plugins: [
        new VuetifyLoaderPlugin(),
    ]
})

mix.browserSync({
    proxy: 'localhost:8000',
    watch: true,
});

mix.options({
    extractVueStyles: true,
    processCssUrls: false
});


mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
