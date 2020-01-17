import { src, dest, watch, series, parallel } from 'gulp';
import yargs from 'yargs';
import sass from 'gulp-sass';
import uglify from 'gulp-uglify';
import concat from 'gulp-concat';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import imagemin from 'gulp-imagemin';
import del from 'del';
import browserSync from "browser-sync";

const PROD = yargs.argv.prod;
const server = browserSync.create();

export const serve = done => {
  server.init({
    proxy: "http://sandbox.local",
    open: false
  });
  done();
};

export const reload = done => {
  server.reload();
  done();
};

export const styles = () => {
  return src(['src/sass/main.scss', 'src/sass/no-mq.scss'])
    .pipe(gulpif(!PROD, sourcemaps.init()))
    .pipe(sass().on('error', sass.logError))
    .pipe(gulpif(PROD, postcss([ autoprefixer ])))
    .pipe(gulpif(PROD, cleanCss({compatibility:'ie8'})))
    .pipe(gulpif(!PROD, sourcemaps.write()))
    .pipe(dest('dist/css'))
    .pipe(server.stream());
}

export const scripts = () => {
  return src(['src/js/vendor/**/*.js', 'src/js/modules/**/*.js', 'src/js/main.js'])
    .pipe(concat('main.js'))
    .pipe(gulpif(PROD, uglify()))
    .pipe(dest('dist/js'));
}

export const images = () => {
  return src('src/img/**/*.{jpg,jpeg,png,svg,gif}')
    .pipe(gulpif(PROD, imagemin()))
    .pipe(dest('dist/img'));
}

export const copy = () => {
  return src(['src/**/*','!src/{img,js,sass}','!src/{img,js,sass}/**/*'])
    .pipe(dest('dist'));
}

export const clean = () => del(['dist']);

export const change = () => {
  watch('src/sass/**/*.scss', styles);
  watch('src/img/**/*.{jpg,jpeg,png,svg,gif}', series(images, reload));
  watch(['src/**/*','!src/{img,js,sass}','!src/{img,js,sass}/**/*'], series(copy, reload));
  // watch('src/js/**/*.js', series(scripts, reload));
  watch("**/*.php", reload);
}

export const dev = series(clean, parallel(styles, scripts, images, copy), serve, change);
export const build = series(clean, parallel(styles, scripts, images, copy));
export default dev;
