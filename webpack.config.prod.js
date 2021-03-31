const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const PurgeCSSPlugin = require('purgecss-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const glob = require('glob-all');
const { VueLoaderPlugin } = require('vue-loader');

const PURGE_PATHS = {
  scss: path.join(__dirname, 'resources/scss'),
  viewsDir: path.join(__dirname, 'resources/views'),
  componentsDir: path.join(__dirname, 'resources/js/components'),
};

module.exports = {
  mode: 'production',
  optimization: {
    minimizer: ['...', new CssMinimizerPlugin()]
  },
  entry: './resources/js/index.js',
  output: {
    path: path.resolve('./public'),
    filename: 'js/index.js',
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
            plugins: ['@babel/plugin-transform-runtime'] // enable async/await
          }
        }
      },
      {
        test: /\.(scss|css)$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              url: false // enable being able to write relative CSS urls like "bg-image: url('/img/my-img.jpg')"
            }
          },
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: [
                  [
                    'tailwindcss', {important: true}
                  ]
                ]
              }
            }
          },
          'sass-loader',
        ]
      },
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'css/style.css'
    }),
    new PurgeCSSPlugin({
      paths: glob.sync([
        `${PURGE_PATHS.src}/**/*`,
        `${PURGE_PATHS.viewsDir}/**/*`,
        `${PURGE_PATHS.componentsDir}/**/*`,
      ], { nodir: true }),
      defaultExtractor: content => content.match(/[^<>"'`\s]*[^<>"'`\s:]/g) || [],
      keyframes: true, // do not purge keyframes rules
    }),
    new VueLoaderPlugin(),
  ],
  resolve: {extensions: ['.js', '.vue']} // enable imports without writing .js/.vue file extensions
};
