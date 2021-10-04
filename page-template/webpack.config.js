const path = require("path");
const webpack = require("webpack");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");


module.exports = {
    mode: process.env.NODE_ENV,
    resolve: {
        modules: [
            path.resolve(__dirname, "assets/js"),
            path.resolve(__dirname, "assets"),
            path.resolve(__dirname, "node_modules"),
            //"node_modules"
        ],
        extensions: [".js"]
    },
    entry: {
        main: [
            //path.resolve(__dirname, "assets/js/main.js"),
            path.resolve(__dirname, "src/scss/init.scss"),
            //path.resolve(__dirname, "assets/js/media-imports.js"), //import icons for sprite
        ],
    },
    output: {
        path: path.resolve(__dirname, "public"),
        publicPath: "/",
        filename: "[name].js"
    },
    module: {
        rules: [{
                test: /\.js$/,
                exclude: /(node_modules\/(?!(body-scroll-lock))|bower_components)\/.*/,
                loader: "babel-loader"
            },
            //scss
            {
                test: /\.scss$/,
                use: [{
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            //hmr: process.env.NODE_ENV === "development"
                        }
                    },
                    "css-loader",
                    {
                        loader: "postcss-loader",
                        options: {
                            postcssOptions: {
                                plugins: [require('css-mqpacker')({
                                    sort: true
                                }), require("autoprefixer")(), require("cssnano")()]
                            }
                        }
                    },
                    {
                        loader: "sass-loader"
                    }
                ]
            },
            //font files
            {
                test: /\.(woff|ttf|otf|eot|woff2|svg)$/i,
                exclude: [
                    path.resolve("./src/images"),
                    path.resolve("./src/icons"),
                ],
                use: [{
                    loader: "file-loader",
                    options: {
                        name: "[folder]/[name].[ext]",
                        //outputPath: "fonts/"
                    }
                }]
            },
            //videos
            {
                test: /\.(mp4|webm)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: 'video/',
                        publicPath: 'video/'
                    }
                }
            },
            //images
            {
                test: /\.(jpg|png|svg)$/,
                exclude: [
                    path.resolve("./assets/fonts"),
                    path.resolve("./assets/icons"),
                ],
                use: {
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: 'images/',
                        publicPath: 'images/'
                    }
                }
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
            chunkFilename: "[name].css"
        })
    ],
    optimization: {
        splitChunks: {
            cacheGroups: {
                styles: {
                    name: "styles",
                    test: /\.css$/,
                    chunks: "all",
                    enforce: true
                }
            }
        }
    }
};
