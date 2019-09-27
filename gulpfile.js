//var elixir = require('laravel-elixir');

var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');


// Javascript concat and uglify
//var js_input = './resources/assets/js/**/*.js';
var js_input = './resources/assets/js/app.js';
var js_output = './public/js';

gulp.task('build-js', function() {
  
  //console.log(js_input);
  
  var app_result = gulp.src(js_input)
    .pipe(concat('app.js'))
    .pipe(gulp.dest(js_output));

  var director_form_result = gulp.src('./resources/assets/js/director-form.js')
    .pipe(concat('director-form.js'))
    .pipe(gulp.dest(js_output));
  
  var user_person_form_result = gulp.src('./resources/assets/js/user-person-form.js')
    .pipe(concat('user-person-form.js'))
    .pipe(gulp.dest(js_output));
  
  return app_result && director_form_result && user_person_form_result;
  
  //return gulp.src(js_input)
    //.pipe(sourcemaps.init())
      //.pipe(concat('app.js'))
      //only uglify if gulp is ran with '--type production'
      //.pipe(gutil.env.type === 'production' ? uglify() : gutil.noop())
    //.pipe(sourcemaps.write())
    //.pipe(gulp.dest(js_output));
});

var resources = './resources/assets/**/*';

var input = './resources/assets/sass/*.scss';
var output = './public/css';

// source and distribution folder
//var
    //source = 'src/',
    //dest = 'dist/';

// Bootstrap scss source
var bootstrapSass = {
        in: './node_modules/bootstrap-sass/'
    };

var scss = {
    //in: source + 'scss/main.scss',
    //out: dest + 'css/',
    //watch: source + 'scss/**/*',
    sassOpts: {
        //outputStyle: 'nested',
        precison: 8,
        errLogToConsole: true,
        includePaths: [
          bootstrapSass.in + 'assets/stylesheets',
          './node_modules/sass-mediaqueries/'
        ]
    }
};


function swallowError (error) {

  // If you want details of the error in the console
  console.log(error.toString())

  this.emit('end')
}

gulp.task('sass', function () {
  return gulp
    // Find all `.scss` files from the `sass/` folder
    .src(input)
    // Run Sass on those files
    .pipe(sass(scss.sassOpts))
    // Write the resulting CSS in the output folder
    .pipe(gulp.dest(output));
});


gulp.task('watch', function() {
  return gulp
    .watch(resources, gulp.series('sass', 'build-js'))
    .on('change', function(event) {
      console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
    });

});

//gulp.task('default', ['watch','build-js'], function() {

//});
