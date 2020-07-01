const path = require('path');
const webpack = require('webpack');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const WebpackAssetsManifest = require('webpack-assets-manifest');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');

module.exports = () => {
  const config = {
    mode: 'staging',
    devtool: 'source-map',
    optimization: {
      minimizer: [
        new TerserPlugin({
          cache: true,
          parallel: true,
          sourceMap: true,
        }),
      ],
    },

    module: {
      rules: [
        {
          test: /\.vue$/,
          loader: [
            {
              loader: 'vue-loader',
              options: {
                loaders: {
                  scss: ['vue-style-loader', MiniCssExtractPlugin.loader, 'css-loader?sourceMap', 'sass-loader?sourceMap', 'postcss-loader'],
                  css: ['vue-style-loader', MiniCssExtractPlugin.loader, 'css-loader?sourceMap', 'postcss-loader'],
                },
              },
            },
          ],
        },
        {
          test: /\.(css|scss)$/,
          use: [MiniCssExtractPlugin.loader, 'css-loader?sourceMap', 'resolve-url-loader', 'sass-loader?sourceMap', 'postcss-loader'],
        },
        {
          test: /\.js$/,
          use: 'babel-loader',
          exclude: /node_modules/,
        },
        {
          test: /\.png$/,
          use: [
            {
              loader: 'url-loader',
              options: {
                mimetype: 'image/png',
              },
            },
          ],
        },
        {
          test: /\.jpg$/,
          use: [
            {
              loader: 'url-loader',
              options: {
                mimetype: 'image/jpg',
              },
            },
          ],
        },
        {
          test: /\.svg$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                outputPath: 'images/',
                publicPath: '/dist/vendor/images',
              },
            },
          ],
        },
        {
          test: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                name: '[name].[ext]',
                outputPath: 'webfonts/',
                publicPath: '/dist/vendor/webfonts',
              },
            },
          ],
        },
      ],
    },

    resolve: {
      extensions: ['.js', '.vue'],
      alias: {
        vue$: 'vue/dist/vue.js',
        jquery: 'jquery/src/jquery',
        '@': path.resolve(__dirname, 'src/App/Client/'),
      },
    },

    plugins: [
      new MiniCssExtractPlugin({
        filename: 'css/[name].css',
        chunkFilename: '[id].css',
        ignoreOrder: false,
      }),
      new OptimizeCssAssetsPlugin({
        assetNameRegExp: /\.css$/,
        cssProcessor: require('cssnano'),
      }),
      new webpack.ProvidePlugin({
        Vue: 'vue',
        BootstrapVue: 'bootstrap-vue',
        axios: 'axios',
        $: 'jquery',
        jQuery: 'jquery',
      }),
      new VueLoaderPlugin(),
      new webpack.DefinePlugin({
        __APP_BASE_URL__: env.appBaseUrl
      }),
    ],
  };

  const loaderPages = Object.assign({}, config, {
    name: 'loader_pages',
    entry: {
      global: './src/App/Client/assets/global/js/global',
      reports: './src/App/Client/pages/Reports/Reports'
    },
    output: {
      path: path.resolve('public/dist/vendor/'),
      filename: '[name].js',
    },
  });

  return [loaderPages];
};
