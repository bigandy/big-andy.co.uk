/* global require */
var gulp = require('gulp'),
	gutil = require('gulp-util'),
	uglify = require('gulp-uglify'),
	concat = require('gulp-concat'),
	jshint = require('gulp-jshint'),
	stripDebug = require('gulp-strip-debug'),
	sass = require('gulp-sass'),
	scsslint = require('gulp-scss-lint'),
	autoprefix = require('gulp-autoprefixer'),
	minifyCSS = require('gulp-minify-css'),
	livereload = require('gulp-livereload'),
	stylish = require('jshint-stylish'),
	uncss = require('gulp-uncss'),
	penthouse = require('penthouse'),
	Promise = require('bluebird'),
	penthouseAsync = Promise.promisify(penthouse),
	svgSprite = require('gulp-svg-sprites'),
	phpcs = require('gulp-phpcs'),
	svgmin = require('gulp-svgmin');

gulp.task('sprites', function () {
    return gulp.src([
    		'images/svg/*.svg',
    	])
        .pipe(svgSprite({
        	mode: 'symbols',
        	preview: false
        }))
        .pipe(svgmin([{
        	cleanupIDs: false
        }]))
        .pipe(gulp.dest('build'));
});

gulp.task('critical', function() {
	penthouseAsync({
		url : 'https://big-andy.co.uk/',
		css : './style.css',
		height: 600,
		width: 400
	}).then( function (criticalCSS){
		require('fs').writeFile('build/css/critical.css', criticalCSS);
	});
});

gulp.task('uncss', function() {
    return gulp.src('./style.css')
        .pipe(uncss({
            html: [
				'http://big-andy.co.uk/contact/',
				'http://big-andy.co.uk/cv/',
				'http://big-andy.co.uk/about/',
				'http://big-andy.co.uk/photos/',
				'http://big-andy.co.uk/',
				'http://big-andy.co.uk/blog',
				'http://big-andy.co.uk/style-guide',
				'http://big-andy.co.uk/https/',
				'http://big-andy.co.uk/breaking-borders-3/'
            ]
        }))
        .pipe(minifyCSS({
				keepSpecialComments: 0
			}))
        .pipe(autoprefix('last 2 versions'))
        .pipe(gulp.dest('.'));
});

// concat and minify the js
gulp.task('js', ['js-lint'], function () {
	gulp.src([
			'js/google-analytics-caller.js',
		])
		.pipe(gutil.env.type === 'production' ? stripDebug() : gutil.noop())
		.pipe(uglify())
		.pipe(concat('script.min.js'))
		.pipe(gulp.dest('build/js'));

	gulp.src(['bower_components/picturefill/dist/picturefill.min.js'])
		.pipe(gulp.dest('build/js'));
	gulp.src(['js/analytics.js'])
		.pipe(gulp.dest('build/js'));
	gulp.src(['js/font-loader.js'])
		.pipe(uglify())
		.pipe(gulp.dest('build/js'));

});

gulp.task('js-lint', function() {
	gulp.src([
			'js/main.js'
		])
		.pipe(jshint('.jshint'))
		.pipe(jshint.reporter(stylish));
});

gulp.task('wordpress-lint', function () {
	return gulp.src(['./**/*.php', '!node_modules/**/*.php'])
		.pipe(phpcs({
			standard: 'code.ruleset.xml'
		}))
		.pipe(phpcs.reporter('log'));
});

// sass
gulp.task('sass', function () {
    gulp.src('./scss/**/*.scss')
		.pipe(sass({
			includePaths: ['bower_components/foundation/scss'],
			errLogToConsole: true,
			outputStyle: 'compressed'
		}))
        .pipe(gulp.dest('.'));
});

gulp.task('scss-lint', function () {
	gulp.src([
			'scss/**/*.scss',
			'!scss/style.scss', // ignore this file so can include commenting in it.
			'!scss/plugins/**/*.scss'
		])
		.pipe(scsslint({
			'config': '.scss-lint.yml',
		}));
});

gulp.task('livereload', function () {
	gulp.src([
		'style.css',
		'build/**',
		'*.php',
		'scss/*.scss'
	])
	.pipe(livereload());
});

// Rerun the task when a file changes
gulp.task('watch', function () {
	gulp.watch('js/*', ['js']);
	gulp.watch('scss/**/*', ['sass']);
	gulp.watch('images/svg/*.svg', ['sprites']);

	var server = livereload();
	gulp.watch([
		'style.css',
		'build/**',
		'*.php',
		'scss/**'
	]).on('change', function(file) {
		server.changed(file.path);
	});
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', [
	'js',
	'sass',
	'watch',
	'livereload',
	'sprites'
]);
gulp.task('production', [
	'js', 'sass'
]);
gulp.task('deploy', [
	'sass',
	'uncss',
	'js',
	'lint',
]);

gulp.task('lint', ['scss-lint', 'js-lint', 'wordpress-lint'])
