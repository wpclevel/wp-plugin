/*!
 * Gulp Worker
 */
'use strict';
const src = 'assets/'; // Working folder which contains sub-folders such as js, css, scss...
const Gulp = require('gulp');
const GulpSass = require('gulp-sass');
const GulpRename = require('gulp-rename');
const GulpUglify = require('gulp-uglify');
const GulpCleanCss = require('gulp-clean-css');

// Build CSS.
Gulp.task('css:minify', () => Gulp.src([src + 'css/*.css', '!' + src + 'css/*.min.css'])
    .pipe(GulpSass({outputStyle: 'expanded'})
    .on('error', GulpSass.logError))
    .pipe(GulpCleanCss())
    .pipe(GulpRename({suffix: '.min'}))
    .pipe(Gulp.dest(src + 'css'))
);

// Watch SCSS.
Gulp.task('sass:watch', () => Gulp.watch(src + 'scss/*.scss', Gulp.series('sass:compile', 'css:minify')));

// Build JS .
Gulp.task('js:minify', () => Gulp.src([src + 'js/*.js', '!' + src + 'js/*.min.js'])
    .pipe(GulpUglify())
    .on('error', e => console.error(e.toString()))
    .pipe(GulpRename({suffix: '.min'}))
    .pipe(Gulp.dest(src + 'js'))
);

// Watch JS.
Gulp.task('js:watch', () => Gulp.watch([src + 'js/*.js', '!' + src + 'js/*.min.js'], Gulp.series('js:minify')));

// Default
Gulp.task('default', Gulp.parallel('sass:watch', 'js:watch'));
