'use strict';
module.exports = function(grunt) {

    grunt.initConfig({
        meta: {
          name: "big-andy.co.uk",
          version: '3.3',
          banner: '/* big-andy.co.uk - v<%= meta.version %> - ' +
            '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
            '* http://big-andy.co.uk\n' +
            '* Copyright (c) <%= grunt.template.today("yyyy") %> ' +
            'Your Company; Licensed MIT */',
          wpblock: '/* \n' +
            'Theme Name: Bigandy Bones \n' +
            'Theme URI: http://www.themble.com/bones \n' +
            'Description: This site was built using the Bones Development Theme. \n' +
            'Author: Andrew JD Hudson \n' +
            'Author URI: http://www.big-andy.co.uk \n' +
            'Tags: flexble-width, translation-ready, microformats, rtl-language-support \n' +
            'License: WTFPL \n' +
            'License URI: http://sam.zoy.org/wtfpl/ - ' +
            'Are You Serious? Yes. \n' +
            'big-andy.co.uk - v<%= meta.version %> - ' +
            'Stylesheet generated on <%= grunt.template.today("yyyy-mm-dd") %>\n' +
            '*/ '
        },
        // let us know if our JS is sound
        jshint: {
            options: {
                "bitwise": true,
                "browser": true,
                "curly": true,
                "eqeqeq": true,
                "eqnull": true,
                "es5": true,
                "esnext": true,
                "immed": true,
                "jquery": true,
                "latedef": true,
                "newcap": true,
                "noarg": true,
                "node": true,
                "strict": false,
                "trailing": true,
                "undef": true,
                "globals": {
                    "jQuery": true,
                    "alert": true
                }
            },
            all: [
                'Gruntfile.js',
                'js/source/*.js'
            ]
        },

        // concatenation and minification all in one
        uglify: {
            options: {
                  banner: '/*! <%= meta.name %> - v<%= meta.version %> - <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            dist: {
                files: {
                    // 'js/build/vendor.min.js': [
                    //     'js/vendor/plugin1/jquery.plugin.js',
                    //     'js/vendor/plugin2/js/plugin/plugin.js'
                    // ],
                    'js/build/script.min.js': [
                        'js/libs/google-analytics.js',
                        // 'js/libs/typekit.js',
                        // 'js/libs/lightbox.js',
                        'js/scripts.js',
                    ]
                }
            }
        },

        // style (Sass) compilation via Compass
        compass: {
            dist: {
                options: {
                    sassDir: 'sass',
                    cssDir: '../',
                    imagesDir: 'img',
                    images: 'images',
                    javascriptsDir: '../js/build',
                    fontsDir: 'fonts',
                    environment: 'production',
                    outputStyle: 'compressed',
                    relativeAssets: true,
                    noLineComments: true,
                    force: true
                }
            }
        },


         cssmin: {
          // compress: {

          // },
          with_banner: {
            options: {
              banner: '<%= meta.wpblock %>'
            },
            files: {
              '../style.css': ['../style.css']
            }
          }
        },

        // watch our project for changes
        watch: {
            compass: {
                files: [
                    'sass/*',
                    'sass/plugins/*'
                ],
                tasks: ['compass']
            },
            js: {
                files: [
                    '<%= jshint.all %>'
                ],
                tasks: ['jshint', 'uglify']
            }
        }

    });

    // load tasks
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');


    // register task
    grunt.registerTask('default', [
        'jshint',
        'compass',
        'uglify',
        'watch',
        'cssmin',

    ]);

};