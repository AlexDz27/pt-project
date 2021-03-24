const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { VueLoaderPlugin } = require('vue-loader');

module.exports = {
  mode: 'development',
  devtool: 'eval-cheap-source-map',
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
        test: /\.scss$/,
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
    new VueLoaderPlugin(),
  ],
  resolve: {extensions: ['.js', '.vue']} // enable imports without writing .js/.vue file extensions
};
