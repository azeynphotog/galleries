/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

const path = require('path')
const webpack = require('webpack')
const { VueLoaderPlugin } = require('vue-loader')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const CopyPlugin = require('copy-webpack-plugin')

module.exports = {
  entry: {
    gallery: './src/gallery.js',
    preview: './src/preview.js'
  },
  output: {
    path: path.resolve(__dirname, 'assets'),
    filename: 'js/[name].js',
    publicPath: '/',
    chunkFilename: 'js/[name].js'
  },
  resolve: {
    alias: {
      'vue': 'vue/dist/vue.esm-bundler.js'
    },
    extensions: ['.mjs', '.js', '.jsx', '.vue', '.json', '.wasm']
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      },
      {
        test: /\.svg$/,
        use: [
          'vue-loader-v16',
          'vue-svg-loader'
        ]
      },
      {
        test: /\.js$/,
        loader: 'babel-loader'
      },
      {
        test: /\.css$/,
        oneOf: [
          {
            resourceQuery: /module/,
            use: [
              MiniCssExtractPlugin.loader,
              {
                loader: 'css-loader',
                options: {
                  modules: true,
                  localIdentName: '[local]_[hash:base64:5]'
                }
              }
            ]
          },
          {
            use: [
              MiniCssExtractPlugin.loader,
              'css-loader'
            ]
          }
        ]
      },
      {
        test: /\.sc|ass$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'sass-loader'
        ]
      }
    ]
  },
  optimization: {
    splitChunks: {
      cacheGroups: {
        vendors: {
          name: 'chunk-vendors',
          test: /[\\/]node_modules[\\/]/,
          priority: -10,
          chunks: 'initial'
        },
        common: {
          name: 'chunk-common',
          minChunks: 2,
          priority: -20,
          chunks: 'initial',
          reuseExistingChunk: true
        }
      }
    },
  },
  plugins: [
    new VueLoaderPlugin(),
    new MiniCssExtractPlugin(
      {
        filename: 'css/[name].css',
        chunkFilename: 'css/[name].css'
      }
    ),
    new CopyPlugin([
      {
        from: path.resolve(__dirname, 'node_modules/jquery-mousewheel/jquery.mousewheel.js'),
        to: path.resolve(__dirname, 'assets/js/'),
        toType: 'dir'
      }
    ]),
    new webpack.DefinePlugin({
      __VUE_OPTIONS_API__: true,
      __VUE_PROD_DEVTOOLS__: false,
    })
  ]
}
