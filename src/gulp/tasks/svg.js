module.exports = function (gulp, plugins, output) {
    return function () {
        gulp.src('src/svg/**/*.svg')
            .pipe(gulp.dest(output))
    };
};
