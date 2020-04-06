/*!
 * Gulp Worker
 */
const env = 'development';
const src = 'assets/'; // Working assets folder (contains /js, /css, /scss, /fonts and /images).
const gulp = require('gulp');
const gulpIf = require('gulp-if');
const gulpSass = require('gulp-sass');
const gulpRename = require('gulp-rename');
const gulpUglify = require('gulp-uglify-es').default;

// Build CSS.
gulp.task('css:build', () => gulp.src([src + 'scss/**/*.scss'])
    .pipe(gulpSass({
        outputStyle: 'compressed'
    }).on('error', gulpSass.logError))
    .pipe(gulpRename({
        suffix: '.min'
    }))
    .pipe(gulp.dest(src + 'css'))
);

// Observe SCSS.
gulp.task('css:watch', () => gulp.watch(src + 'scss/**/*.scss', gulp.series('css:build')));

// Build JS
gulp.task('js:build', () => gulp.src([src + 'js/**/*.js', '!' + src + 'js/**/*.min.js'])
    .pipe(gulpIf('production' === env, gulpUglify()))
    .pipe(gulpRename({
        suffix: '.min'
    }))
    .pipe(gulp.dest(src + 'js'))
);

// Observe JS.
gulp.task('js:watch', () => gulp.watch([src + 'js/**/*.js', '!' + src + 'js/**/*.min.js'], gulp.series('js:build')));

// Default
gulp.task('default', gulp.parallel('css:watch', 'js:watch'));
