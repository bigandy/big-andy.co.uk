/* global module */
module.exports = function (grunt) {
    'use strict';
    grunt.initConfig({
        sass: {
            dist: {
                options: {
                    sourcemap: true, // [true|false] (needs sass 3.3.0, gem install sass--pre)
                    style: 'compressed', // [nested|compact|compressed|expanded]
                    trace: true,
                    precision: 8,
                    // compass: false, // can be true, config.rb should be in the same dir as Gruntfile.js
                    debugInfo: false, // if you're using FireSass Firebug plugin
                    // lineNumbers: true, // attache the line numbers in the generated css
                    // loadPath: true, // attach the source file path into the css
                    // require: [], // require ruby libraries (specify version numbers, et al)
                    // cacheLocation: '.sass-cache', // if you want to change the cache location
                    // noCache: false, // if you don't want to use the .sass-cache
                    // banner: 'Generated by Awesome' // prepend the specified string to the output file. Licensing
                },
                files: {
                    'style.css': 'sass/style.sass'
                }
            }
        },
        uglify: {
            dist: {
                files: {
                    'js/build/app.min.js': [
                        // 'js/vendor/chosen.jquery.js',
                        // 'js/vendor/custom.modernizr.js',
                        // 'js/foundation/*.js',
                        'js/plugins/**/*.js',
                        // 'js/bower-components/requirejs/require.js',
                        'js/main.js'
                    ]
                }
            }
        },
        jshint: {
            files: [
                'Gruntfile.js',
                'js/foundation/*.js',
                'js/plugins/**/*.js',
                'js/main.js'
            ],
            options: {
                jshintrc: '.jshintrc',
                ignores: [
                    // 'js/vendor/custom.modernizr.js',
                    // 'js/vendor/chosen.jquery.js',
                    // 'js/foundation/*.js',
                    'js/app.min.js'
                ]
            }
        },
        watch: {
            css: {
                files: [
                    'sass/*.sass',
                    'sass/plugins/*.sass',
                    'sass/media-queries/*.sass'
                ],
                tasks: ['sass'],
                options: {
                    spawn: true
                }
            },
            js: {
                files: [
                    'js/plugins/**/*.js',
                    'js/bower-components/*.js',
                    'js/main.js'
                ],


                tasks: ['jshint', 'uglify'],
                options: {
                    spawn: true
                },
                ignores: [
                    'js/vendor/custom.modernizr.js',
                    'js/vendor/chosen.jquery.js',
                    'js/foundation/*.js',
                    'js/app.min.js'
                ]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('d', ['sass', 'jshint', 'uglify']);
};