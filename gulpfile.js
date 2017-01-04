'use strict';

const 
	gulp = require('gulp'),
	sass = require('gulp-sass'),
	autoprefixer = require('gulp-autoprefixer'),
	concat = require('gulp-concat'),
	notify = require('gulp-notify'),
	sourceMaps = require('gulp-sourcemaps'),
	sequence = require('gulp-sequence'),
	plumber = require('gulp-plumber'),
	browserSync = require('browser-sync').create(),
	rollupStream = require('rollup-stream'),
	source = require('vinyl-source-stream'),
	buffer = require('vinyl-buffer'),
	babel = require('rollup-plugin-buble'),
	rollupCommonJs = require('rollup-plugin-commonjs'),
	rollupNodeResolve = require('rollup-plugin-node-resolve'),
	rollupVue = require('rollup-plugin-vue2'),
	rollupSass = require('rollup-plugin-sass'),
	rollupInject = require('rollup-plugin-inject'),
	replace = require('rollup-plugin-replace'),
	settings = {
		baseUrl: "localhost/alangf"
	};

gulp.task('watch', function() {

  	browserSync.init({
        proxy: settings.baseUrl
    });

    sequence([ 'sass', 'rollup' ])(function (err) {
	    if (err) console.log(err)
	  });

  	gulp.watch([
  		'./src/css/**/*.css',
  		'./src/sass/**/*.sass',
  		'./src/components/**/*.vue'
  	], ['sass']);

  	gulp.watch([
  		'./src/scripts/**/*.js',
  		'./src/components/**/*.vue',
  	], ['rollup']);

    gulp.watch('./**/*.php').on('change', browserSync.reload);

});

gulp.task('sass', function() {

  	return gulp.src([
  		'node_modules/normalize.css/normalize.css',
	  	'./src/sass/main.sass',
  		'./src/css/components.css'
  	])
  	.pipe(sourceMaps.init())
    .pipe(sass().on('error', sass.logError))
  	.pipe(autoprefixer())
  	.pipe(sourceMaps.write())
  	.pipe(concat('main.css'))
    .pipe(gulp.dest('./assets/css'))
    .pipe(notify({ 'message': 'SASS / CSS OK' }))
    .pipe(browserSync.stream());

});



gulp.task('rollup', function() {
  
  	return rollupStream({
    	entry: './src/scripts/main.js',
    	dest: './assets/js/main.js',
      	moduleName: 'app', 
      	plugins: [
	      	rollupVue({
	      		compileTemplate: true
	      	}),
		    rollupNodeResolve({
				jsnext: true,
				main: true,
				browser: true
		    }),
		    rollupCommonJs({
				include: ['node_modules/**', 'src/components/**/*.vue'],  
				extensions: [ '.js', '.vue' ],
				ignoreGlobal: false,  
				sourceMap: true 
 			}),
      		rollupSass({
      			output: './src/css/components.css',
      			options: {
      				indentedSyntax: true
      			}
      		}),
      		babel(),
	        replace({
				'process.env.NODE_ENV': JSON.stringify('development'),
				'process.env.VUE_ENV': JSON.stringify('browser')
	        })
		    
		]
	})
	.pipe(source('main.js', './src/scripts'))
	.pipe(buffer())
    .pipe(sourceMaps.init({loadMaps: true}))
    .pipe(sourceMaps.write('.', {
    	includeContent: false, 
    	sourceRoot: './src/scripts'
    }))
    .pipe(gulp.dest('./assets/js'))
    .pipe(notify({ 'message': 'Rollup OK' }))
    .pipe(browserSync.stream());
});