const config = {
  mode: 'development',
  entry: {
    'app': './resources/js/app.jsx',
    'admin': './resources/js/admin.jsx'
  },
  output: {
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
          "style-loader",
          "css-loader",
          "sass-loader"
        ]
      }
    ]
  },
  devServer: {
    port: 9000
  }
}

module.exports = config