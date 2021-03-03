/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

const path = require('path')

module.exports = {
  outputDir: 'assets',
  lintOnSave: false,
  filenameHashing: false,
  chainWebpack: config => {
    config.plugins.delete('html')
    config.plugins.delete('preload')
    config.plugins.delete('prefetch')

    config.plugin('copy')
      .use('copy-webpack-plugin')
      .tap(args => {
        return [
          [
            {
              from: path.resolve(__dirname, 'node_modules/jquery-mousewheel/jquery.mousewheel.js'),
              to: path.resolve(__dirname, 'assets/js/'),
              toType: 'dir'
            }
          ]
        ]
      })

    const svgRule = config.module.rule('svg')

    svgRule.uses.clear()
    svgRule
      .use('vue-loader-v16')
      .loader('vue-loader-v16')
      .end()
      .use('vue-svg-loader')
      .loader('vue-svg-loader')
  },
  runtimeCompiler: true
};
