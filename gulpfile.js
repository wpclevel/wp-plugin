'use strict';
var gulp = require('gulp');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var sourcemap = require('gulp-sourcemaps');
var js_minifier = require('gulp-uglify');
var css_minifier = require('gulp-clean-css');

// Compile css
gulp.task('css:compile', function()
{
  return gulp.src('assets/sass/*.scss')
    .pipe(sourcemap.init())
    .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
    .pipe(sourcemap.write('.'))
    .pipe(gulp.dest('assets/css'));
});

// Minify css
gulp.task('css:build', ['css:compile'], function()
{
  return gulp.src(['assets/css/*.css', '!assets/css/*.min.css'])
    .pipe(css_minifier())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('assets/css'));
});

// Watch css
gulp.task('css:watch', function()
{
  return gulp.watch('assets/sass/*.scss', ['css:build']);
});

// Minify js
gulp.task('js:build', function()
{
  return gulp.src(['assets/js/*.js', '!assets/js/*.min.js'])
    .pipe(js_minifier()).on('error', function(err){console.error('Error', err.toString());})
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('assets/js'));
});

// Watch css
gulp.task('js:watch', function()
{
  return gulp.watch(['assets/js/*.js', '!assets/js/*.min.js'], ['js:build']);
});

// Default task
gulp.task('default', ['css:build', 'js:build']);
