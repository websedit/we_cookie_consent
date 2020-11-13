const gulp = require('gulp');

gulp.task('js', () => {
    return gulp.src([
        'node_modules/klaro/dist/klaro.js',
        'node_modules/klaro/dist/klaro-no-css.js'])
        .pipe(gulp.dest('../Resources/Public/Library/klaro'));
});

gulp.task('build', gulp.parallel('js'));
