const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';

    return {
        entry: {
            app: [
                path.resolve(__dirname, 'resources/js/app.js'),
                path.resolve(__dirname, 'resources/css/app.css')
            ]
        },
        output: {
            path: path.resolve(__dirname, 'public'),
            filename: 'assets/js/[name].js',
            clean: false // Preservar imágenes, .htaccess y archivos públicos
        },
        devtool: isProduction ? false : 'source-map',
        module: {
            rules: [
                {
                    test: /\.css$/i,
                    use: [
                        MiniCssExtractPlugin.loader,
                        {
                            loader: 'css-loader',
                            options: {
                                url: false // Mantener URLs relativas para fuentes e imágenes estáticas
                            }
                        }
                    ]
                }
            ]
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'assets/css/[name].css'
            })
        ],
        optimization: {
            minimize: isProduction,
            minimizer: [
                new TerserPlugin({
                    extractComments: false
                }),
                new CssMinimizerPlugin()
            ]
        },
        performance: {
            hints: false
        },
        stats: 'minimal'
    };
};
