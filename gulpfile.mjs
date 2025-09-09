'use strict';

import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';
import gulp from 'gulp';
import rename from 'gulp-rename';
import cleanCSS from 'gulp-clean-css';
import inlineSvg from 'gulp-inline-svg';
import svgMin from 'gulp-svgmin';
import prefix from 'gulp-autoprefixer';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';
import babel from 'gulp-babel';
import fs from 'fs';
import path from 'path';
import merge from 'merge-stream';

const ASSETS = 'assets';
const SOURCE = 'src';

const sass = gulpSass(dartSass);
const sassOptions = {
  outputStyle: 'compressed'
};

const cssOptions = {
  level: 2,
  format: {
    semicolonAfterLastProperty: true
  }
};

// https://stackoverflow.com/questions/45446626/concatenate-and-rename-files-based-on-directory-name-with-gulp
// https://github.com/gulpjs/gulp/blob/v3.9.1/docs/recipes/running-task-steps-per-folder.md
function getFolders(dir) {
  return fs.readdirSync(dir)
    .filter(function(file) {
      return fs.statSync(path.join(dir, file)).isDirectory();
    });
}

function mapFolders(callback) {
	return merge(
		getFolders(SOURCE).map(callback)
	);
}

gulp.task('scripts', function () {
	return mapFolders(function(folder) {
    return gulp.src(path.join(SOURCE, folder, '/js/**/*.js'))
      .pipe(concat(folder + '/js/scripts.js'))
			.pipe(babel({
	      presets: ["@babel/preset-env"]
	    }))
      .pipe(gulp.dest(ASSETS))
      .pipe(uglify())
      .pipe(rename(folder + '/js/scripts.min.js'))
      .pipe(gulp.dest(ASSETS));
   });
});

gulp.task('inline-svg', function() {
    return gulp.src(path.join(ASSETS, '/icons/**/*.svg'))
      .pipe(svgMin())
      .pipe(inlineSvg({
        template: path.join(SOURCE, '/common/inline-svg.mustache')
      }))
      .pipe(gulp.dest(path.join(SOURCE, '/common/scss/common')));
});

gulp.task('styles', function () {
	return mapFolders(function(folder) {
		return gulp.src(path.join(SOURCE, folder, '/scss/**/*.scss'))
			.pipe(sass(sassOptions).on('error', sass.logError))
			.pipe(concat(folder + '/css/styles.css'))
			.pipe(prefix())
      .pipe(gulp.dest(ASSETS))
			.pipe(cleanCSS(cssOptions))
			.pipe(rename(folder + '/css/styles.min.css'))
			.pipe(gulp.dest(ASSETS));
	});
});

gulp.task('watch', function () {
  gulp.watch(SOURCE, gulp.parallel('styles'));
  gulp.watch(SOURCE, gulp.parallel('scripts'));
});

gulp.task('default', gulp.parallel('inline-svg', 'styles', 'scripts'));
