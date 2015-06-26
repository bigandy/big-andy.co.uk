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
	phpcs = require('gulp-phpcs'),
	critical = require('critical'),
	pages = [
		'http://big-andy.dev/contact/',
		'http://big-andy.dev/cv/',
		'http://big-andy.dev/about/',
		'http://big-andy.dev/photos/',
		'http://big-andy.dev/',
		'http://big-andy.dev/blog',
		'http://big-andy.dev/style-guide',
		'http://big-andy.dev/https/',
		'http://big-andy.dev/breaking-borders-3/'
	],
	postcss = require('gulp-postcss'),
	autoprefixer = require('autoprefixer-core'),
	mqpacker = require('css-mqpacker'),
	csswring = require('csswring'),
	mixins = require('postcss-mixins'),
	nestedcss = require('postcss-nested'),
	postcssImport = require('postcss-import'),
	vars = require('postcss-simple-vars'),
	colours = require('./postcss/colours'),
	fs = require('fs'),
	inputCss = fs.readFileSync('postcss/style.css', 'utf8'),
	postcssRoot = require('postcss'),
	cssnext = require('gulp-cssnext');

gulp.task('critical-css', function() {
	penthouseAsync({
		url: pages,
		css: './style.css',
		height: 600,
		width: 400
	}).then( function (criticalCSS){
		require('fs').writeFile('build/css/critical.css', criticalCSS);
	});
});

gulp.task('uncss', function() {
	return gulp.src('./style.css')
		.pipe(uncss({
			html: pages,
			ignore: [
				'[data-visited]',
				'[data-visited] .post-content'
			]
		}))
		.pipe(minifyCSS({
				keepSpecialComments: 0
			}))
		.pipe(autoprefix('last 2 versions'))
		.pipe(gulp.dest('.'));
});

gulp.task('css', function () {
	var processors = [
		autoprefixer({browsers: ['last 1 version']}),
		mixins,
		mqpacker,
		csswring,
		nestedcss,
		vars({ variables: colours }),
		postcssImport,
		cssnext
	];

	return gulp
		.src('postcss/style.css')
		.pipe(postcss(processors))
		.on('error', function (error) {
		  console.log(error)
		})
		.pipe(gulp.dest('build/postcss'))
});

// concat and minify the js
gulp.task('js', ['js-lint'], function () {
	gulp.src([
			// 'js/google-analytics-caller.js',
			'js/lazy-load-css.js',
			'js/main.js',
		])
		.pipe(gutil.env.type === 'production' ? stripDebug() : gutil.noop())
		.pipe(uglify())
		.pipe(concat('script.min.js'))
		.pipe(gulp.dest('build/js'));

	gulp.src([
		'bower_components/jquery/dist/jquery.js',
		'bower_components/picturefill/dist/picturefill.min.js',
		'bower_components/lazyloadxt/dist/jquery.lazyloadxt.js',
		'bower_components/lazyloadxt/dist/jquery.lazyloadxt.srcset.js'
	])
		.pipe(uglify())
		.pipe(concat('picturefill.min.js'))
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
	var processors = [
		autoprefixer({browsers: ['last 1 version']}),
	];

	gulp.src('./scss/**/*.scss')
		.pipe(sass({
			includePaths: ['bower_components/foundation/scss'],
			// errLogToConsole: true,
			// outputStyle: 'compressed'
		}))

		.pipe(cssnext({
			browsers: ('last 1 version'),
			compress: true,
			sourcemap: true
		}))

		.pipe(postcss(processors))
		.pipe(gulp.dest('.'));

	gulp.src('./scss/font.scss')
		.pipe(sass({
			outputStyle: 'compressed'
		}))
		.pipe(gulp.dest('./build/css'));
});

gulp.task('scss-lint', function () {
	gulp.src([
			'scss/**/*.scss',
			'!scss/style.scss', // ignore this file so can include commenting in it.
			'!scss/font.scss'
		])
		.pipe(scsslint({
			'config': '.scss-lint.yml',
		}));
});

// Rerun the task when a file changes
gulp.task('watch', function () {
	gulp.watch('js/*', ['js']);
	gulp.watch('scss/**/*', ['sass']);
	// gulp.watch('images/svg/*.svg', ['sprites']);

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
]);

gulp.task('production', [
	'js', 'sass'
]);

gulp.task('deploy', [
	'sass',
	'uncss',
	'js',
	'lint',
	'critical-css'
]);

gulp.task('lint', ['scss-lint', 'js-lint', 'wordpress-lint']);
