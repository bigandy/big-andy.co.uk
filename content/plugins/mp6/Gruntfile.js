module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    meta: {
      colors: 'components/color-schemes',
      customizer: 'components/customizer'
    },

    sass: {
      admin: {
        options: {
          style: 'compact',
          lineNumbers: false
        },
        files : {
          '<%= meta.colors %>/picker/style.css'           : '<%= meta.colors %>/picker/style.scss',
          '<%= meta.customizer %>/customize-controls.css' : '<%= meta.customizer %>/scss/customize-controls.scss',
        }
      },
      colors: {
      	options: {
      	  style: 'compact',
      	  lineNumbers: false
      	},
        files : {
          '<%= meta.colors %>/schemes/blue/colors.css'      : '<%= meta.colors %>/schemes/blue/colors.scss',
          '<%= meta.colors %>/schemes/malibu-dreamhouse/colors.css' : '<%= meta.colors %>/schemes/malibu-dreamhouse/colors.scss',
          '<%= meta.colors %>/schemes/seaweed/colors.css'   : '<%= meta.colors %>/schemes/seaweed/colors.scss',
          '<%= meta.colors %>/schemes/pixel/colors.css'     : '<%= meta.colors %>/schemes/pixel/colors.scss',
          '<%= meta.colors %>/schemes/ectoplasm/colors.css' : '<%= meta.colors %>/schemes/ectoplasm/colors.scss',
          '<%= meta.colors %>/schemes/80s-kid/colors.css'   : '<%= meta.colors %>/schemes/80s-kid/colors.scss',
          '<%= meta.colors %>/schemes/lioness/colors.css'   : '<%= meta.colors %>/schemes/lioness/colors.scss',
          '<%= meta.colors %>/schemes/mp6-light/colors.css' : '<%= meta.colors %>/schemes/mp6-light/colors.scss',
        }
      }
    },

    watch: {
      sass: {
        files: ['<%= meta.path %>/**/*.scss', ],
        tasks: ['sass:colors']
      },
      sass: {
        files: ['<%= meta.colors %>/picker/style.scss', '<%= meta.customizer %>/scss/*.scss' ],
        tasks: ['sass:admin']
      }
    }

  });

  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task(s).
  grunt.registerTask('default', ['sass']);

};
