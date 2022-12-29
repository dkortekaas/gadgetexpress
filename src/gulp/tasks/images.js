// var pngquant = require('imagemin-pngquant');
// var changed = require('gulp-changed');
// var rename = require("gulp-rename");

module.exports = function (gulp, plugins, output) {
    return function () {
        gulp.src('src/images/**/*')
            //.pipe(changed(output))
            .pipe(plugins.imagemin())
            .pipe(gulp.dest(output));
    };
};

