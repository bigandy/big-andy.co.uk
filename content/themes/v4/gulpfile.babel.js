import gulp from 'gulp';
import gutil from 'gulp-util';
import uglify from 'gulp-uglify';
import concat from 'gulp-concat';
import jshint from 'gulp-jshint';
import stripDebug from 'gulp-strip-debug';
import sass from 'gulp-sass';
import scsslint from 'gulp-scss-lint';
import autoprefix from 'gulp-autoprefixer';
import minifyCSS from 'gulp-minify-css';
import livereload from 'gulp-livereload';
import stylish from 'jshint-stylish';
import uncss from 'gulp-uncss';
import penthouse from 'penthouse';
import Promise from 'bluebird';
import phpcs from 'gulp-phpcs';
import critical from 'critical';

var pages = [
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
	penthouseAsync = Promise.promisify(penthouse);


import autoprefixer from 'autoprefixer-core';
import cssnext from 'gulp-cssnext';
import postcss from 'gulp-postcss';



gulp.task('critical-css', () => {
	penthouseAsync({
		url: pages,
		css: './style.css',
		height: 600,
		width: 400
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
				'[data-visited] .post-content'
			]
		}))
		.pipe(minifyCSS({
				keepSpecialComments: 0
			}))
		.pipe(autoprefix('last 2 versions'))
		.pipe(gulp.dest('.'));
});

// concat and minify the js
gulp.task('js', ['js-lint'], () => {
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

// sass
gulp.task('sass', () => {
	var processors = [
		autoprefixer({browsers: ['last 1 version']}),
	];

	gulp.src('./scss/**/*.scss')
		.pipe(sass({
			includePaths: ['bower_components/foundation/scss'],
			// errLogToConsole: true,
			// outputStyle: 'compressed'
		}))

		.pipe(postcss(processors))

		.pipe(cssnext({
			browsers: ('last 1 version'),
			compress: false,
			sourcemap: false
		}))


		.pipe(gulp.dest('.'));

	gulp.src('./scss/font.scss')
		.pipe(sass({
			outputStyle: 'compressed'
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

// Rerun the task when a file changes
gulp.task('watch', () => {
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
