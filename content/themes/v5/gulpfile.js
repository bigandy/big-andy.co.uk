'use strict';

const gulp = require('gulp');
const gutil = require('gulp-util');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const eslint = require('gulp-eslint');
const sass = require('gulp-sass');
const scsslint = require('gulp-scss-lint');
const browserSync = require('browser-sync');
const uncss = require('gulp-uncss');
const penthouse = require('penthouse');
const Promise = require('bluebird');
const phpcs = require('gulp-phpcs');
const critical = require('critical');
const nano = require('gulp-cssnano');
const cleanCSS = require('clean-css');
const svgStore = require('gulp-svgstore');
const svgmin = require('gulp-svgmin');

const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const stylelint = require('stylelint');
const reporter = require('postcss-reporter');
const rename = require('gulp-rename');

var envLive = 'https://big-andy.co.uk/',
	envDev = 'http://big-andy.dev/',
	env = envLive,
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
	browsers = ['last 1 version'],
	reload = browserSync.reload;

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
	const fs = require('fs');

	const outputCSS = (criticalCSS, file) => {
		const output = new cleanCSS().minify(criticalCSS);
		fs.writeFile(`build/css/${file}.css`, output.styles, (err) => {
			if (err) {
				console.log('outputcss error: ', err);
			}
		});
	};

	const runPenthouse = (outputFile, envExtra = '') => {
		penthouseAsync({
			url: [
				env + envExtra
			],
			css: './style.css',
			height: 1200, // 600
			width: 400, // 400
		    minify: true,
		}).then(criticalCSS => {
			outputCSS(criticalCSS, outputFile);
		});
	}

	runPenthouse('critical');
	runPenthouse('post', '/30-running-days-september/');
});

gulp.task('uncss', ['sass'], () => {
	return gulp.src('./style.css')
		.pipe(uncss({
			html: pages,
			ignore: [
				'[data-visited]',
				'[data-visited] .post-content',
				'.svg-sprite',
				'.previouspostslink',
				/pre.*/,
				/code.*/,
				/token.*/,
				'.article__header__image'
			]
		}))
		.pipe(gulp.dest('.'));
});

gulp.task('sass', () => {
	gulp.src([
		'./scss/style.scss',
		])
		.pipe( sourcemaps.init() )
		.pipe(sass({
			'outputStyle': 'compressed',
		}).on('error', sass.logError))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('.'))
		.pipe(browserSync.stream());

	gulp.src('./scss/fonts/opensans.scss')
		.pipe(sass({
			'outputStyle': 'compressed',
		}).on('error', sass.logError))
		.pipe(gulp.dest('./build/css/fonts'));
});

gulp.task('css-lint', () => {
	gulp.src([
		'./scss/**/*.scss',
		'!./scss/font/*.scss'
		])
		.pipe(postcss([
			stylelint({ // an example config that has four rules
				"rules": {
					"block-no-empty": 2,
					"color-no-invalid-hex": 2,
					"declaration-colon-space-before": [2, "never"],
					"declaration-colon-space-after": [2, "always"],
					"indentation": [2, "tab"],
					"number-leading-zero": [2, "never"]
				}
			}),
			reporter({
				clearMessages: true,
			})
		]))
});


// concat and minify the js
gulp.task('js', () => {
	gulp.src([
			'js/lazy-load-css.js',
			'js/main.js',
		])
		.pipe(uglify().on('error', e => {
            console.log(e);
         }))
		.pipe(concat('script.min.js'))
		.pipe(gulp.dest('build/js'));

	gulp.src([
		'node_modules/jquery/dist/jquery.min.js',
		'node_modules/picturefill/dist/picturefill.min.js',
		'node_modules/lazyloadxt/dist/jquery.lazyloadxt.min.js',
		'node_modules/lazyloadxt/dist/jquery.lazyloadxt.widget.min.js',
		'node_modules/lazyloadxt/dist/jquery.lazyloadxt.srcset.min.js',
		'js/prism.min.js',
	])
		.pipe(uglify())
		.pipe(concat('singular.min.js'))
		.pipe(gulp.dest('build/js'))
		.pipe(browserSync.stream());

	gulp.src([
		'node_modules/sw-toolbox/sw-toolbox.js',
	])
		.pipe(uglify())
		.pipe(concat('sw-toolbox.min.js'))
		.pipe(gulp.dest('build/js'));
});

gulp.task('js-lint', () => {
	gulp.src([
			'js/main.js'
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

gulp.task('wordpress-lint', () => {
	return gulp.src(['./**/*.php', '!node_modules/**/*.php'])
		.pipe(phpcs({
			standard: 'code.ruleset.xml'
		}))
		.pipe(phpcs.reporter('log'));
});

gulp.task('browser-sync', function() {
	browserSync.init({
		proxy: 'big-andy.dev'
	})

	gulp.watch('**/*.php').on('change', browserSync.reload);
});

// // Rerun the task when a file changes
gulp.task('watch', () => {
	gulp.watch('js/*', ['js']);
	gulp.watch('scss/**/*', ['sass']);
	gulp.watch('images/svg/*.svg', ['sprites']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', [
	'browser-sync',
	'js',
	'sass',
	'watch'
]);

gulp.task('build', [
	'uncss',
	'js',
	'critical-css',
	'sprites'
]);

gulp.task('lint', [
	'js-lint',
	'wordpress-lint'
]);
