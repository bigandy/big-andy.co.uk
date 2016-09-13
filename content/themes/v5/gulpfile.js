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
const cleanCSS = require('gulp-clean-css');
const svgStore = require('gulp-svgstore');
const svgmin = require('gulp-svgmin');

const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const mixins = require('postcss-mixins');
const nestedcss = require('postcss-nested');
const postcssImport = require('postcss-import');
const colorFunction = require('postcss-color-function');
const postcssRoot = require('postcss');
const cssnext = require('postcss-cssnext');
const simpleExtend = require('postcss-simple-extend');
const focus = require('postcss-focus');
const rows = require('postcss-rows');
const customProperties = require('postcss-custom-properties');
const stylelint = require('stylelint');
const reporter = require('postcss-reporter');

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
	penthouseAsync({
		url: [
			env
		],
		css: './style.css',
		height: 3000, // 600
		width: 400, // 400
	    minify: true,
	}).then(function (criticalCSS){
		require('fs').writeFile('build/css/critical.css', criticalCSS);
	});

	penthouseAsync({
		url: [
			env + '/using-forecast-io-with-wordpress/'
		],
		css: './style.css',
		height: 3000, // 600
		width: 400, // 400
		minify: true,
	}).then(function (criticalCSS){
		require('fs').writeFile('build/css/post.css', criticalCSS);
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
				'.previouspostslink',
				/pre.*/,
				/code.*/,
				/token.*/,
				'.article__header__image'
			]
		}))

		.pipe(cleanCSS({
			keepSpecialComments: 0
		}))
		.pipe(gulp.dest('.'));
});

gulp.task('css', () => {
	var cssNextOptions = {
			browsers: browsers,
			sourcemap: false,
			safe: true,
			compress: {
				calc: false,
				colormin: false,
				convertValues: false,
				discardComments: false,
				discardDuplicates: false,
				discardEmpty: false,
				discardUnused: false,
				filterOptimiser: false,
				filterPlugins: false,
				functionOptimiser: false,
				mergeIdents: false,
				mergeLonghand: false,
				mergeRules: false,
				minifyFontValues: false,
				minifySelectors: false,
				normalizeCharset: false,
				normalizeUrl: false,// this one is the cause
				orderedValues: true,
				reduceIdents: true,
				styleCache: true,
				svgo: true,
				uniqueSelectors: true,
				zindex: true
			}
		};

	var processors = [
		postcssImport,
		mixins,
		customProperties,
		simpleExtend,
		nestedcss,
		focus,
		colorFunction,
		cssnext(cssNextOptions),
		rows({
			multiplier: 16,
			unit: 'rows'
		}),
	];

	gulp.src('./postcss/style.css')
		.pipe(postcss(processors))
		.pipe(gulp.dest('.'))
		.pipe(browserSync.stream());

	gulp.src('./postcss/font.scss')
		.pipe(postcss(processors))
		.pipe(gulp.dest('./build/css'));
});

gulp.task('css-lint', () => {
	gulp.src([
		'./postcss/**/*.css',
		'!./postcss/font.css'
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
		.pipe(uglify().on('error', function(e){
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
		// .pipe(browserSync.stream());

	gulp.src(['js/font-loader.js'])
		.pipe(uglify())
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
	gulp.watch('postcss/**/*', ['css']);
	gulp.watch('images/svg/*.svg', ['sprites']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', [
	'browser-sync',
	'js',
	'css',
	'watch'
]);

gulp.task('deploy', [
	'css',
	'uncss',
	'js',
	'critical-css',
]);

gulp.task('lint', [
	'js-lint',
	'wordpress-lint'
]);
