const path = require("path"), miniSVG = require("mini-svg-data-uri"), miniCSS = require("mini-css-extract-plugin");

module.exports = {
    mode: "production",
    context: __dirname + "/assets",
    entry: {

    },
    output: {
        path: __dirname + "/assets/js",
        filename: "[name].min.js"
    },
    plugins: [
        new miniCSS({
            filename: "../css/[name].min.css"
        }),
    ],
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [{
                        loader: miniCSS.loader,
                        options: {
                            hmr: false
                        },
                    },
                    "css-loader",
                    {
                        loader: "sass-loader",
                        options: {
                            sourceMap: false,
                            sassOptions: {
                                outputStyle: "compressed"
                            }
                        }
                    }
                ]
            },
            {
                test: /\.svg$/i,
                use: [{
                    loader: "url-loader",
                    options: {
                        generator: v => miniSVG(v.toString()),
                    }
                }]
            },
            {
                test: /\.(ttf|eot|woff|woff2)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: 'fonts/'
                    },
                },
            }
        ]
    },
    externals: {
        "jQuery": "jQuery",
        "lodash": "lodash",
        "@wordpress/data": "wp.data",
        "@wordpress/i18n": "wp.i18n",
        "@wordpress/hooks": "wp.hooks",
        "@wordpress/blocks": "wp.blocks",
        "@wordpress/editor": "wp.editor",
        "@wordpress/plugins": "wp.plugins",
        "@wordpress/compose": "wp.compose",
        "@wordpress/element": "wp.element",
        "@wordpress/api-fetch": "wp.apiFetch",
        "@wordpress/edit-post": "wp.editPost",
        "@wordpress/components": "wp.components",
        "@wordpress/block-editor": "wp.blockEditor",
        "@wordpress/html-entities": "wp.htmlEntities"
    },
    watch: true,
    watchOptions: {
        ignored: ["**/*.min.js"]
    }
}
