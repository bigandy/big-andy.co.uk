module.exports = {
	entry: './js/block.js',
	output: {
		path: __dirname,
		filename: './js/bundle.js',
	},
	module: {
		loaders: [
			{
				test: /.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
			},
		],
	},
};
