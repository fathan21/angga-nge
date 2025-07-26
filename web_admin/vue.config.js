module.exports = {
  transpileDependencies: ['birpc','vue-toast-notification'],
  productionSourceMap: false,
  publicPath:"/warkop/",
  chainWebpack: (config) => {
    config.plugins.delete("preload");
    config.plugins.delete("prefetch");
    // config.plugins.delete('prefetch')
    config.plugins.delete('pwa');
    config.plugins.delete('workbox');
  },
  configureWebpack: {
    performance: {
      // hints: "warning",
      // maxEntrypointSize: 512000,
      // maxAssetSize: 512000,

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
