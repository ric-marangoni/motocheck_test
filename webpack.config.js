const path = require('path');
const webpack = require('webpack');
const webpackEnv = require('./webpack.env');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const WebpackAssetsManifest = require('webpack-assets-manifest');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = params => {
  const isDev = params.NODE_ENV === 'development';
  const env = webpackEnv(params.NODE_ENV);

  let cssLoader = 'css-loader';
  let sassLoader = 'sass-loader';

  if (!isDev) {
    const sourceMap = '?sourceMap';

    cssLoader += sourceMap;
    sassLoader += sourceMap;
  }

  const config = {
    mode: params.NODE_ENV,
    module: {
      rules: [
        {
          test: /\.vue$/,
          loader: [
            {
              loader: 'vue-loader',
              options: {
                loaders: {
                  scss: ['vue-style-loader', MiniCssExtractPlugin.loader, cssLoader, sassLoader, 'postcss-loader'],
                  css: ['vue-style-loader', MiniCssExtractPlugin.loader, cssLoader, 'postcss-loader'],
                },
              },
            },
          ],
        },
        {
          test: /\.(css|scss)$/,
          use: [MiniCssExtractPlugin.loader, cssLoader, 'resolve-url-loader', 'sass-loader?sourceMap', 'postcss-loader'],
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

  switch (true) {
    case isDev: {
      const WebpackNotifierPlugin = require('webpack-notifier');

      config.watch = true;
      config.watchOptions = {
        ignored: /node_modules/,
      };
      config.plugins.push(
        new WebpackNotifierPlugin({
          alwaysNotify: true,
        })
      );

      break;
    }
    default: {
      const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
      const TerserPlugin = require('terser-webpack-plugin');

      config.plugins.push(
        new OptimizeCssAssetsPlugin({
          assetNameRegExp: /\.css$/,
          cssProcessor: require('cssnano'),
        })
      );

      config.devtool = 'source-map';
      config.optimization = {
        minimizer: [
          new TerserPlugin({
            cache: true,
            parallel: true,
            sourceMap: true,
          }),
        ],
      };

      break;
    }
  }

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
