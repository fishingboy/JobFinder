// var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

// elixir(function(mix) {
// 	mix.sass('app.scss');
// });


/*
 |--------------------------------------------------------------------------
 |custom set
 |--------------------------------------------------------------------------
 |
 */

var gulp = require('gulp'),
	sass = require('gulp-ruby-sass'),
	autoprefixer = require('gulp-autoprefixer'),
	minifycss = require('gulp-minify-css'),
	jshint = require('gulp-jshint'),
	uglify = require('gulp-uglify'),
	imagemin = require('gulp-imagemin'),
	rename = require('gulp-rename'),
	concat = require('gulp-concat'),
	notify = require('gulp-notify'),
	cache = require('gulp-cache'),
	livereload = require('gulp-livereload'),
	del = require('del');

/*jobFinder相關scripts 不含套件 */
gulp.task('projectScripts', function() {
	return gulp.src(['./public/js/jobFinder.js', './public/js/urlParams.js']) //src js路徑 可用字串、陣列傳遞。
		.pipe(jshint())
		.pipe(jshint.reporter('default'))
		.pipe(concat('jobFinder.js'))
		.pipe(gulp.dest('./public/assets/js'))
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(uglify())
		.pipe(gulp.dest('./public/assets/js'))
		.pipe(livereload())
		.pipe(notify({
			message: "projectScripts done!!"
		}));
});

gulp.task('clean', function(cb) {
	del(['./public/assets/js'], cb);
});

gulp.task('default', ['clean'], function() {
	gulp.start('projectScripts');
});

gulp.task('watch', function() {
	gulp.watch(['./public/js/jobFinder.js', './public/js/urlParams.js'], [
		'projectScripts'
	]);
});
