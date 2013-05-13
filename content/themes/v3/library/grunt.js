/*global module:false*/
module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    meta: {
      version: '3.2.0',
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
        '*/'
    },
    lint: {
       files: ['grunt.js', 'js/scripts.js']
    },
    concat: {
      dist: {
        src: ['<banner:meta.banner>', 'js/scripts.js', 'js/libs/ga.js'],
        dest: '../js/scripts.min.js'
      }
    },
    min: {
      dist: {
        src: ['<banner:meta.banner>', '<config:concat.dist.dest>'],
        dest: '<config:concat.dist.dest>'
      }
    },
    cssmin: {
      dist: {
        src: ['<banner:meta.wpblock>', '../style.css'],
        dest: '../style.css'
      }
    },
    watch: {
      files: ['<config:lint.files>', 'sass/*.scss', 'sass/plugins/*.scss'],
      tasks: 'default'
    },
    jshint: {
      options: {
        curly: true,
        eqeqeq: true,
        immed: true,
        latedef: true,
        newcap: true,
        noarg: true,
        sub: true,
        undef: true,
        boss: true,
        eqnull: true,
        jquery: true,
        devel: true,
        browser: true
      },
      globals: {}
    },
    uglify: {},
    compass: {
      dist: {
        forcecompile: true
      }
    }
  });

  // Default task.
  grunt.registerTask('default', 'concat min compass cssmin');
  // grunt.registerTask('default', 'lint concat min compass cssmin');

  // Compass tasks
  grunt.loadNpmTasks('grunt-compass');
  // CSS tasks
  grunt.loadNpmTasks('grunt-css');

};