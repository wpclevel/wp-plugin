const path = require('path'),
    contextPath = path.resolve(__dirname, 'assets/js');

module.exports = {
    mode: 'development',
    context: contextPath,
    entry: {
        
    },
    output: {
        path: contextPath,
        filename: '[name].min.js'
    },
    module: {
        rules: [{
            test: /\.js$/,
            exclude: /node_modules/,
            loader: 'babel-loader'
        }]
    },
    watch: true,
    watchOptions: {
        ignored: ['**/*.min.js']
    }
}
