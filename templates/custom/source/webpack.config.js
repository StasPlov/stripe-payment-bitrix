const path = require('path');
const { VueLoaderPlugin } = require('vue-loader');
const webpack = require('webpack');

module.exports = {
	mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
	entry: './src/main.js',
	output: {
		path: path.resolve(__dirname, './dist'),
		publicPath: '/dist/',
		filename: 'build.js'
	},
	module: {
		rules: [
			{
				test: /\.vue$/,
				loader: 'vue-loader'
			},
			{
				test: /\.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/
			},
			{
				test: /\.css$/,
				use: ['vue-style-loader', 'css-loader']
			},
			{
				test: /\.scss$/,
				use: ['vue-style-loader', 'css-loader', 'sass-loader']
			}
		]
	},
	plugins: [
		new VueLoaderPlugin(),
		new webpack.DefinePlugin({
			__VUE_OPTIONS_API__: true,
			__VUE_PROD_DEVTOOLS__: false
		})
	],
	resolve: {
		alias: {
			'vue$': 'vue/dist/vue.esm-bundler.js'
		},
		extensions: ['*', '.js', '.vue', '.json']
	},
	devtool: 'source-map',
	devServer: {
		static: {
			directory: path.join(__dirname, 'public'),
		},
		compress: true,
		port: 8080, // или любой другой порт
		hot: true,
		open: true
	},
};
