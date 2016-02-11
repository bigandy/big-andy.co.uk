import gulp from 'gulp';
import gutil from 'gulp-util';
import uglify from 'gulp-uglify';
import concat from 'gulp-concat';
import eslint from 'gulp-eslint';
import sass from 'gulp-sass';
import scsslint from 'gulp-scss-lint';
import browserSync from 'browser-sync';
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
import autoprefixer from 'autoprefixer';
import mixins from 'postcss-mixins';
import nestedcss from 'postcss-nested';
import postcssImport from 'postcss-import';
import colorFunction from 'postcss-color-function';
import postcssRoot from 'postcss';
import cssnext from 'gulp-cssnext';
import simpleExtend from 'postcss-simple-extend';
import focus from 'postcss-focus';
import rows from 'postcss-rows';
import customProperties from 'postcss-custom-properties';
import stylelint from 'stylelint';
import reporter from 'postcss-reporter';

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
		])
		.pipe(postcss(processors))
		.pipe(cssnext({
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
		}))
		.pipe(gulp.dest('.'))
		.pipe(browserSync.stream());

	gulp.src('./postcss/font.scss')
		.pipe(cssnext({
			browsers: browsers,
			compress: true,
			sourcemap: false,
		}))
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
		'bower_components/lazyloadxt/dist/jquery.lazyloadxt.widget.js',
		'bower_components/lazyloadxt/dist/jquery.lazyloadxt.srcset.js',
		'js/prism.min.js',
	])
		.pipe(uglify())
		.pipe(concat('singular.min.js'))
		.pipe(gulp.dest('build/js'))
		.pipe(browserSync.stream());

	gulp.src([
		'bower_components/sw-toolbox/sw-toolbox.js',
	])
		.pipe(uglify())
		.pipe(concat('sw-toolbox.min.js'))
		.pipe(gulp.dest('build/js'))
		.pipe(browserSync.stream());

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

// Rerun the task when a file changes
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

gulp.task('production', [
	'js',
	'css'
]);

gulp.task('deploy', [
	'css',
	'uncss',
	// 'js',
	'lint',
	'critical-css',
]);

gulp.task('lint', [
	'js-lint',
	'wordpress-lint'
]);
