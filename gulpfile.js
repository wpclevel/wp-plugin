/*!
 * Gulp Worker
 */
'use strict';
const src = 'assets/'; // Working assets folder which contains sub-folders such as js, css, scss...
const Gulp = require('gulp');
const GulpSass = require('gulp-sass');
const GulpRename = require('gulp-rename');
const GulpUglify = require('gulp-uglify');
const GulpCleanCss = require('gulp-clean-css');

// Compile SCSS to CSS
Gulp.task('sass:compile', () => Gulp.src([src + 'scss/*.scss'])
    .pipe(GulpSass({outputStyle: 'expanded'})
    .on('error', GulpSass.logError))
    .pipe(Gulp.dest(src + 'css'))
);

// Minify CSS, make it ready for production.
Gulp.task('css:minify', () => Gulp.src([src + 'css/*.css', '!' + src + 'css/*.min.css'])
    .pipe(GulpCleanCss())
    .pipe(GulpRename({suffix: '.min'}))
    .pipe(Gulp.dest(src + 'css'))
);

// SCSS observer.
Gulp.task('sass:watch', () => Gulp.watch(src + 'scss/*.scss', Gulp.series('sass:compile', 'css:minify')));

// Minify JS, make it ready for production.
Gulp.task('js:minify', () => Gulp.src([src + 'js/*.js', '!' + src + 'js/*.min.js'])
    .pipe(GulpUglify())
    .on('error', e => console.error(e.toString()))
    .pipe(GulpRename({suffix: '.min'}))
    .pipe(Gulp.dest(src + 'js'))
);

// JS observer.
Gulp.task('js:watch', () => Gulp.watch([src + 'js/*.js', '!' + src + 'js/*.min.js'], Gulp.series('js:minify')));

// Default
Gulp.task('default', Gulp.parallel('sass:watch', 'js:watch'));
