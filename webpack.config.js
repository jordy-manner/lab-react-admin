const path = require('path');

const config = {
  mode: 'development',
  entry: {
    'app': './resources/assets/js/app.jsx',
    'admin': './resources/assets/js/admin.jsx'
  },
  output: {
    path: path.resolve(__dirname, 'public/dist'),
    filename: '[name].js',
    publicPath: '/dist/'
  },
  resolve: {
    extensions: ['.js', '.jsx']
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        use: ['babel-loader'],
        exclude: /node_modules/
      },
      {
        test: /\.scss$/,
        exclude: /node_modules/,
        use: [
          'style-loader',
          'css-loader',
          'sass-loader'
        ]
      }
    ]
  },
  devServer: {
    port: 9000,
    watchFiles: ['resources/views/**/*'],
  }
}

module.exports = config