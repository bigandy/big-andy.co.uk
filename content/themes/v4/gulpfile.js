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
	Promise = require("bluebird"),
	penthouseAsync = Promise.promisify(penthouse),
	svgSprite = require("gulp-svg-sprites"),
	phpcs = require('gulp-phpcs');

gulp.task('sprites', function () {
    return gulp.src('images/svg/*.svg')
        .pipe(svgSprite({
        	mode: 'symbols',
        	preview: false
        }))
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
				'http://big-andy.local/contact/',
				'http://big-andy.local/cv/',
				'http://big-andy.local/about/',
				'http://big-andy.local/photos/',
				'http://big-andy.local/',
				'http://big-andy.local/blog',
				'http://big-andy.local/style-guide',
				'http://big-andy.local/https/',
				'http://big-andy.local/breaking-borders-3/'
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
			// 'bower_components/jquery/dist/jquery.min.js',
			// 'js/font-loader.js',
			'js/google-analytics-caller.js',
			// 'js/lazy-load-css.js',
			// 'js/main.js',
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
			// bin: 'phpcs',
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
			'!scss/style.scss' // ignore this file so can include commenting in it.
		])
		.pipe(scsslint({
			'config': '.scss-lint.yml',
			// 'customReport': myCustomReporter
		}));
});

gulp.task('livereload', function () {
	gulp.src([
		'style.css', 'build/**', '*.php', 'scss/*.scss'
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
gulp.task('default', ['js', 'sass', 'watch', 'livereload', 'sprites']);
gulp.task('production', ['js', 'sass']);
gulp.task('deploy', ['uncss', 'js']);

gulp.task('lint', ['scss-lint', 'js-lint', 'wordpress-lint'])
