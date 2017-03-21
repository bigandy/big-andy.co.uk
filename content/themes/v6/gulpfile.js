/* eslint-env node */
/* eslint no-console: 0 */

'use strict';

const gulp = require('gulp');
// const gutil = require('gulp-util');
// const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const eslint = require('gulp-eslint');
const sass = require('gulp-sass');
const browserSync = require('browser-sync');
// const uncss = require('gulp-uncss');
// const penthouse = require('penthouse');
// const Promise = require('bluebird');
// const phpcs = require('gulp-phpcs');
// const critical = require('critical');
// const nano = require('gulp-cssnano');
// const cleanCSS = require('clean-css');
// const svgStore = require('gulp-svgstore');
// const svgmin = require('gulp-svgmin');
const babel = require('gulp-babel');

const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const stylelint = require('stylelint');
const syntax_scss = require('postcss-scss');
const reporter = require('postcss-reporter');
const babili = require("gulp-babili");
// var
	// envLive = 'https://big-andy.co.uk/',
	// envDev = 'http://big-andy.dev/',
	// env = envLive,
	// pages = [
	// 	env + 'contact/',
	// 	env + 'cv/',
	// 	env + 'about/',
	// 	env + 'photos/',
	// 	env + '',
	// 	env + 'blog/',
	// 	env + 'style-guide/',
	// 	env + 'https/',
	// 	env + 'breaking-borders-3/'
	// ],
	// penthouseAsync = Promise.promisify(penthouse),
	// browsers = ['last 1 version'];

// gulp.task('sprites', () => {
// 	return gulp.src([
// 			'images/svg/*.svg'
// 		])
// 		.pipe(svgStore({ inlineSvg: true }))
//
// 		.pipe(svgmin({
// 			plugins:
// 				[{
// 					cleanupIDs: false
// 				}]
// 			}))
// 		.pipe(gulp.dest('build/svg'));
// });

// gulp.task('critical-css', () => {
// 	const fs = require('fs');
//
// 	const outputCSS = (criticalCSS, file) => {
// 		const output = new cleanCSS().minify(criticalCSS).styles;
// 		fs.writeFile(`build/css/${file}.css`, output, (err) => {
// 			if (err) {
// 				console.log('outputcss error: ', err);
// 			}
// 		});
// 	};
//
// 	const runPenthouse = (outputFile, envExtra = '') => {
// 		penthouseAsync({
// 			url: [
// 				env + envExtra
// 			],
// 			css: './style.css',
// 			height: 1200, // 600
// 			width: 400, // 400
// 		    minify: true,
// 		}).then(criticalCSS => {
// 			outputCSS(criticalCSS, outputFile);
// 		});
// 	}
//
// 	// TODO way of writing this so don't call twice
// 	runPenthouse('critical');
// 	runPenthouse('post', '/30-running-days-september/');
// });

// gulp.task('uncss', ['sass'], () => {
// 	return gulp.src('./style.css')
// 		.pipe(uncss({
// 			html: pages,
// 			ignore: [
// 				'[data-visited]',
// 				'[data-visited] .post-content',
// 				'.svg-sprite',
// 				'.previouspostslink',
// 				/pre.*/,
// 				/code.*/,
// 				/token.*/,
// 				'.article__header__image'
// 			]
// 		}))
// 		.pipe(gulp.dest('.'));
// });

gulp.task('sass', ['sass-lint'], () => {
	gulp.src([
		'./scss/style.scss',
	])
		.pipe( sourcemaps.init() )
		.pipe(sass({
			'outputStyle': 'compressed',
		}).on('error', sass.logError))
		.pipe(postcss([ autoprefixer() ]))
		.pipe(sourcemaps.write('.'))
		// .pipe(autoprefixer(browsers))
		.pipe(gulp.dest('.'))
		.pipe(browserSync.stream());

	// gulp.src('./scss/fonts/opensans.scss')
	// 	.pipe(sass({
	// 		'outputStyle': 'compressed',
	// 	}).on('error', sass.logError))
	// 	.pipe(gulp.dest('./build/css/fonts'));
});

gulp.task('sass-lint', () => {
	gulp.src([
		'./scss/**/*.scss',
		'!./scss/fonts/*.scss'
	])
		.pipe(postcss([
			stylelint(),
			reporter()
		],
		{syntax: syntax_scss}));
});


// concat and minify the js
gulp.task('js', ['js-lint'], () => {
	gulp.src([
		'js/script.js',
	])
		.pipe(babel())
		.pipe(babili({
			mangle: {
				keepClassNames: true
			}
		}))
		.pipe(concat('script.min.js'))
		.pipe(gulp.dest('build/js'));

	// gulp.src([
	// 	'node_modules/jquery/dist/jquery.min.js',
	// 	'node_modules/picturefill/dist/picturefill.min.js',
	// 	'node_modules/lazyloadxt/dist/jquery.lazyloadxt.min.js',
	// 	'node_modules/lazyloadxt/dist/jquery.lazyloadxt.widget.min.js',
	// 	'node_modules/lazyloadxt/dist/jquery.lazyloadxt.srcset.min.js',
	// 	'js/prism.min.js',
	// ])
	// 	.pipe(uglify())
	// 	.pipe(concat('singular.min.js'))
	// 	.pipe(gulp.dest('build/js'))
	// 	.pipe(browserSync.stream());
	//
	// gulp.src([
	// 	'node_modules/sw-toolbox/sw-toolbox.js',
	// ])
	// 	.pipe(uglify())
	// 	.pipe(concat('sw-toolbox.min.js'))
	// 	.pipe(gulp.dest('build/js'));
	//
	// gulp.src(['js/async-await-test.js'])
    //     .pipe(babel())
    //     .pipe(concat('async-await-test.min.js'))
	// 	.pipe(gulp.dest('build/js'));
});

gulp.task('js-lint', () => {
	gulp.src([
		'js/script.js'
	])
		// eslint() attaches the lint output to the eslint property
		// of the file object so it can be used by other modules.
		.pipe(eslint())
		// eslint.format() outputs the lint results to the console.
		// Alternatively use eslint.formatEach() (see Docs).
		.pipe(eslint.format())
		// To have the process exit with an error code (1) on
		// lint error, return the stream and pipe to failAfterError last.
		.pipe(eslint.failAfterError());
});

// gulp.task('wordpress-lint', () => {
// 	return gulp.src(['./**/*.php', '!node_modules/**/*.php'])
// 		.pipe(phpcs({
// 			standard: 'code.ruleset.xml'
// 		}))
// 		.pipe(phpcs.reporter('log'));
// });

gulp.task('browser-sync', function() {
	browserSync.init({
		proxy: 'big-andy.dev'
	});

	gulp.watch('**/*.php').on('change', browserSync.reload);
});

// // Rerun the task when a file changes
gulp.task('watch', () => {
	gulp.watch('js/*', ['js']);
	gulp.watch('scss/**/*', ['sass']);
	// gulp.watch('images/svg/*.svg', ['sprites']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', [
	'browser-sync',
	'js',
	'sass',
	'watch'
]);

// gulp.task('build', [
// 	'uncss',
// 	'js',
// 	'critical-css',
// 	'sprites'
// ]);
//

gulp.task('lint', [
	'sass-lint',
	'js-lint',
	// 'wordpress-lint'
]);
