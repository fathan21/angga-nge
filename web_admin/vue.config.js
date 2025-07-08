
const { version } = require("./package.json");

module.exports = {
  productionSourceMap: false,
  devServer: {
    //   open: process.platform === 'darwin',
    //   host: '0.0.0.0',
    //   port: 8067, // CHANGE YOUR PORT HERE!
    // https: true,
    //   hotOnly: false,
  },
  filenameHashing: false,

  chainWebpack: (config) => {

    if (config.plugins.has('extract-css')) {
      const extractCSSPlugin = config.plugin('extract-css')
      extractCSSPlugin && extractCSSPlugin.tap(() => [{
        filename: `css/[name].${version}.css?v=${version}`,
        chunkFilename: `css/[name].${version}.css?v=${version}`
      }])
    }

    // config.plugins.delete("preload");
    // config.plugins.delete("prefetch");
    // config.plugins.delete('prefetch')

    // hide pwa dulu
    // config.plugins.delete("pwa");
    // config.plugins.delete("workbox");

    // rename file
    config.output
      .filename('[name].' + version + '.js');
    config.optimization.merge({
      splitChunks: {
        automaticNameDelimiter: '-'
      }
    });

  },
  configureWebpack: {
    output: {
      filename: `js/[name].${version}.js?v=${version}`,
      chunkFilename: `js/[name].${version}.js?v=${version}`
    },
    performance: {
      // hints: "warning",
      maxEntrypointSize: 512000,
      maxAssetSize: 512000,
    },
    optimization: {
      splitChunks: {
        minSize: 10000,
        maxSize: 250000,
      },
    },
  },
};
