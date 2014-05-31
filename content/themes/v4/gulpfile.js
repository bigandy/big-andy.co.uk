/* global require */
var gulp = require('gulp'),
	gutil = require('gulp-util'),
	uglify = require('gulp-uglify'),
	concat = require('gulp-concat'),
	jshint = require('gulp-jshint'),
	stripDebug = require('gulp-strip-debug'),
	sass = require('gulp-sass'),
	autoprefix = require('gulp-autoprefixer'),
	minifyCSS = require('gulp-minify-css'),
	livereload = require('gulp-livereload'),
	stylish = require('jshint-stylish'),
	uncss = require('gulp-uncss');

gulp.task('uncss', function() {
    return gulp.src('style.css')
        .pipe(uncss({
            html: [
				'html/front-page.html',
				'html/about.html',
				'html/single.html',
				'html/cv.html'
            ]
        }))
        .pipe(gulp.dest('./build'));
});



// concat and minify the js
gulp.task('js', function () {
	gulp.src([
			'bower_components/jquery/dist/jquery.min.js',
			'bower_components/FitVids/jquery.fitvids.js',
			'js/main.js',
		])
		.pipe(gutil.env.type === 'production' ? stripDebug() : gutil.noop())
		.pipe(uglify())
		.pipe(concat('script.min.js'))
		.pipe(gulp.dest('build/js'));
	gulp.src(['bower_components/picturefill/dist/picturefill.min.js'])
		.pipe(gulp.dest('build/js'));

});

gulp.task('lint', function() {
  gulp.src([
			'js/plugins.js',
			'js/main.js',
		])
		.pipe(jshint('.jshint'))
		.pipe(jshint.reporter(stylish));
});

// sass
gulp.task('sass', function () {
    gulp.src('scss/*.scss')
		.pipe(sass({
			errLogToConsole: true,
			outputStyle: 'compressed',
			// sourceComments: 'map'
		}))
        // .pipe(autoprefix('last 5 versions'))
        // .pipe(minifyCSS())
        .pipe(gulp.dest('.'));
});

gulp.task('livereload', function () {
	gulp.src([
		'style.css', 'build/**', '*.php'
	])
	.pipe(livereload()
	);
});

// Rerun the task when a file changes
gulp.task('watch', function () {
	gulp.watch('js/*', ['js']);
	gulp.watch('scss/*', ['sass']);

	var server = livereload();
	gulp.watch(['style.css', 'build/**', '*.php']).on('change', function(file) {
		server.changed(file.path);
	});
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['js', 'sass', 'watch', 'livereload']);
gulp.task('production', ['js', 'sass']);
gulp.task('tidycss', ['uncss']);
