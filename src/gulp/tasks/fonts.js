module.exports = function (gulp, plugins, output) {
    return function () {
        gulp.src('src/fonts/**/*.*')
        .pipe(gulp.dest(output));
    };
};
