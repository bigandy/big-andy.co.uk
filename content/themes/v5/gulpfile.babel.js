import gulp from 'gulp';
import gutil from 'gulp-util';
import uglify from 'gulp-uglify';
import concat from 'gulp-concat';
import jshint from 'gulp-jshint';
import sass from 'gulp-sass';
import scsslint from 'gulp-scss-lint';
import livereload from 'gulp-livereload';
import stylish from 'jshint-stylish';
import uncss from 'gulp-uncss';
import penthouse from 'penthouse';
import Promise from 'bluebird';
import phpcs from 'gulp-phpcs';
import critical from 'critical';
import nano from 'gulp-cssnano';
import minifyCss from 'gulp-minify-css';
import svgStore from 'gulp-svgstore';
import svgmin from 'gulp-svgmin';

import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer-core';
import mixins from 'postcss-mixins';
import nestedcss from 'postcss-nested';
import postcssImport from 'postcss-import';
import colorFunction from'postcss-color-function';
import postcssRoot from 'postcss';
import cssnext from 'gulp-cssnext';
import simpleExtend from 'postcss-simple-extend';
import focus from 'postcss-focus';
import rows from 'postcss-rows';
import customProperties from 'postcss-custom-properties';

var envLive = 'https://big-andy.co.uk/',
	envDev = 'http://big-andy.dev/',
	env = envDev,
	pages = [
		env + 'contact/',
		env + 'cv/',
		env + 'about/',
		env + 'photos/',
		env + '',
		env + 'blog/',
		env + 'style-guide/',
		env + 'https/',
		env + 'breaking-borders-3/'
	],
	penthouseAsync = Promise.promisify(penthouse),
	browsers = ['last 1 version'];

gulp.task('sprites', () => {
	return gulp.src([
			'images/svg/*.svg'
		])
		.pipe(svgStore({ inlineSvg: true }))

		.pipe(svgmin({
			plugins:
				[{
					cleanupIDs: false
				}]
			}))
		.pipe(gulp.dest('build/svg'));
});

gulp.task('critical-css', () => {
	penthouseAsync({
		url: [
			'http://big-andy.dev/'
		],
		css: './style.css',
		height: 3000, // 600
		width: 400 // 400
	}).then( function (criticalCSS){
		require('fs').writeFile('build/css/critical.css', criticalCSS);
	});
});

gulp.task('uncss', () => {
	return gulp.src('./style.css')
		.pipe(uncss({
			html: pages,
			ignore: [
				'[data-visited]',
				'[data-visited] .post-content',
				'.svg-sprite',
				'.previouspostslink'
			]
		}))

		.pipe(minifyCss({
			keepSpecialComments: 0
		}))
		.pipe(gulp.dest('.'));
});

// sass
gulp.task('sass', () => {
	gulp.src('./scss/font.scss')
		.pipe(sass({
			outputStyle: 'compressed'
		}))
		.pipe(gulp.dest('./build/css'));
});

gulp.task('css', () => {
	var processors = [
		postcssImport,
		mixins,
		customProperties,
		simpleExtend,
		nestedcss,
		focus,
		colorFunction,
		rows({
			multiplier: 16,
			unit: 'rows'
		}),
		autoprefixer({
			browsers: browsers
		})
	];
	gulp.src([
			'./postcss/style.css',
			'./postcss/font.css'
		])
		.pipe(postcss(processors))
		.pipe(cssnext({
			browsers: browsers,
			compress: true,
			sourcemap: false,
			safe: true
		}))
		.pipe(gulp.dest('.'));

	gulp.src('./postcss/font.scss')
		.pipe(cssnext({
			browsers: browsers,
			compress: true,
			sourcemap: false,
		}))
		.pipe(gulp.dest('./build/css'));
});

gulp.task('scss-lint', () => {
	gulp.src([
			'scss/**/*.scss',
			'!scss/style.scss', // ignore this file so can include commenting in it.
			'!scss/font.scss'
		])
		.pipe(scsslint({
			'config': '.scss-lint.yml',
		}));
});

// concat and minify the js
gulp.task('js', ['js-lint'], () => {
	gulp.src([
			'js/lazy-load-css.js',
			'js/main.js',
		])
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

gulp.task('js-lint', () => {
	gulp.src([
			'js/main.js'
		])
		.pipe(jshint('.jshint'))
		.pipe(jshint.reporter(stylish));
});

gulp.task('wordpress-lint', () => {
	return gulp.src(['./**/*.php', '!node_modules/**/*.php'])
		.pipe(phpcs({
			standard: 'code.ruleset.xml'
		}))
		.pipe(phpcs.reporter('log'));
});

// Rerun the task when a file changes
gulp.task('watch', () => {
	gulp.watch('js/*', ['js']);
	gulp.watch('postcss/**/*', ['css']);
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
	'css',
	'watch'
]);

gulp.task('production', [
	'js',
	'css'
]);

gulp.task('deploy', [
	'css',
	'uncss',
	'critical-css',
	'js',
	'lint'
]);

gulp.task('lint', ['scss-lint', 'js-lint', 'wordpress-lint']);
