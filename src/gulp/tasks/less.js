var cssnanoOptions = {
    discardUnused: false,
    grid: false,
    zindex: false
    //reduceIdents: false,
    //mergeIdents: false,
    //mergeRules: false
}

module.exports = function (gulp, plugins, output) {
    return function () {
        gulp.src('src/less/style.less')
            .pipe(plugins.less())
            //.pipe(plugins.autoprefixer(autoprefixerOptions))
            .pipe(plugins.cssnano(cssnanoOptions))
            .pipe(gulp.dest(output))
            //.pipe(plugins.gzip())
            //.pipe(gulp.dest('./'));

        gulp.src('src/less/login.less')
            .pipe(plugins.less())
            //.pipe(plugins.autoprefixer(autoprefixerOptions))
            .pipe(plugins.cssnano(cssnanoOptions))
            .pipe(gulp.dest(output))
            //.pipe(plugins.gzip())
            //.pipe(gulp.dest(output));

    };
};