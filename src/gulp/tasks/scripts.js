var gulpif = require('gulp-if');

module.exports = function (gulp, plugins, output) {
    return function () {

        var shouldMinify = true; //process.env.NODE_ENV === "production";

        // Custm scripts
        gulp.src('src/js/scripts.js')
            //.pipe(changed(output))
            .pipe(plugins.concat('scripts.min.js'))
            .pipe(gulpif(shouldMinify, plugins.uglify()))
            .pipe(gulp.dest(output))
            //.pipe(plugins.gzip({ gzipOptions: { level: 5 } }))
            //.pipe(gulp.dest(output));
    };
};