/// <binding ProjectOpened='watch' />

'use strict';

var gulp = require('gulp'),
    runSequence = require('run-sequence'),
    plugins = require('gulp-load-plugins')({
        pattern: ['gulp-*', 'gulp.*', 'streamqueue']
    }),
    taskPath = 'src/gulp/tasks/',
    fontmin = require('gulp-fontmin'),
    taskList = require('fs').readdirSync(taskPath),
    fs = require('fs'),
    path = require('path'),
    endsWith = require('path-ends-with'),
    chalk = require('chalk'),
    log = require('fancy-log'),
    minimist = require('minimist'),
    svgSprite = require('gulp-svg-sprite'),
    replace = require('gulp-replace');

// output dir
var outputAssets = './assets';

// Gulp Tasks
var gulpTasks = './src/gulp/tasks';

gulp.task('less', require(gulpTasks + '/less')(gulp, plugins, outputAssets + '/css'));
gulp.task('scripts', require(gulpTasks + '/scripts')(gulp, plugins, outputAssets + '/js'));
gulp.task('images', require(gulpTasks + '/images')(gulp, plugins, outputAssets + '/images'));
gulp.task('favicons', require(gulpTasks + '/favicons')(gulp, plugins, outputAssets + '/favicons'));
gulp.task('fonts', require(gulpTasks + '/fonts')(gulp, plugins, outputAssets + '/fonts'));
gulp.task('svg', require(gulpTasks + '/svg')(gulp, plugins, outputAssets + '/svg'));

gulp.task('watch', ['less'], function () {
    gulp.watch('src/less/**/*.less', ['less']);
    gulp.watch('src/js/**/*.js', ['scripts']);
    gulp.watch('src/images/**/*', ['images']);
    gulp.watch('src/favicons/**/*', ['favicons']);
    gulp.watch('src/fonts/**/*', ['fonts']);
    gulp.watch('src/svg/**/*.svg', ['svg']);
});

gulp.task('default', ['less', 'scripts', 'images', 'favicons', 'fonts', 'svg'], function () { });