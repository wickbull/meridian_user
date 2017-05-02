'use strict';

var gulp         = require('gulp'),
    browserSync  = require('browser-sync').create(),
    twig         = require('gulp-twig'),
    data         = require('gulp-data'),
    less         = require('gulp-less'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps   = require('gulp-sourcemaps'),
    spritesmith  = require('gulp.spritesmith'),
    jscs         = require('gulp-jscs'),
    jshint       = require('gulp-jshint'),
    uglify       = require('gulp-uglifyjs'),
    cssnano      = require('gulp-cssnano'),
    rename       = require('gulp-rename'),
    gulpCopy     = require('gulp-copy'),
    rev          = require('gulp-rev'),
    del          = require('del'),
    override     = require('gulp-rev-css-url'),
    gzip         = require('gulp-gzip'),
    modernizr    = require('gulp-modernizr'),
    saveLicense  = require('uglify-save-license'),
    fs           = require('fs'),
    uncss        = require('gulp-uncss'),
    replace      = require('gulp-replace'),
    svgmin       = require('gulp-svgmin'),
    svgSprite    = require('gulp-svg-sprite'),
    cheerio      = require('gulp-cheerio'),
    plumber      = require('gulp-plumber'),
    merge        = require('merge-stream'),
    paths = {
        templateDir: 'static/layouts/',
        sourceDir: 'static/src/',
        publicDir: 'static/pub/'
    },
    uglifyList = [
        // paths.sourceDir + 'js/vendor/jquery-1.12.4.min.js',
        // paths.sourceDir + 'js/vendor/bootstrap/bootstrap.min.js',
        // paths.sourceDir + 'js/vendor/slick.js',
        // paths.sourceDir + 'js/vendor/jquery.parallax.min.js',
        // paths.sourceDir + 'js/vendor/jquery.mCustomScrollbar.concat.min.js',
        // paths.sourceDir + 'js/vendor/jquery.mousewheel.min.js',
        // paths.sourceDir + 'js/vendor/textarea.min.js',
        // paths.sourceDir + 'js/vendor/selectric.min.js',
        // paths.sourceDir + 'js/vendor/inputmask.min.js',
        // paths.sourceDir + 'js/map.js',
        // paths.sourceDir + 'js/carousel.js',
        // paths.sourceDir + 'js/atom/file.js',
        // paths.sourceDir + 'js/ui.js'
    ],
    uncssIgnoreClass = [
        /^.o-/,
        /^.u-/,
        /^.c-/,
        /^.t-/,
        /^.s-/,
        /^.is-/,
        /^.has-/,
        /^.js-/,
        /^.i-/,
        /^.active/, /*bootstrap*/
        /^.open/,
        /^.modal/,
        /^.in/,
        /^.fade/,
        /^.slick/ /*slick*/
    ];
    //demoName = 'GULP',
    //demoDir = '/Users/v10/Dropbox/Public/' + demoName;

// DEV html
gulp.task('templates', function () {
    return gulp.src(['*.twig', '!_*.twig'], {cwd: paths.templateDir + '_includes'})
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(data(function (file) {
            return JSON.parse(fs.readFileSync(paths.templateDir + '_includes/__datafile.json'));
        }))
        .pipe(twig())
        .pipe(gulp.dest(paths.templateDir));
});

// DEV styles
gulp.task('css', function () {
    return gulp.src(paths.sourceDir + 'less/app.less')
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sourcemaps.init())
        .pipe(less())
        .pipe(autoprefixer({
            browsers: ['last 2 versions']
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.sourceDir + 'css'))
        .pipe(browserSync.stream({match: '**/*.css'}));
});

// DEV sprites
gulp.task('spritesheets', function () {
    var spriteData = gulp.src(paths.sourceDir + '/img/ui-icons/**/*.png')
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(spritesmith({
            retinaSrcFilter: paths.sourceDir + '/img/ui-icons/**/*@2x.png',
            imgName: 'ui-icons.png',
            retinaImgName: 'ui-icons@2x.png',
            cssName: '~ui-icons.less',
            cssFormat: 'css_retina',
            padding: 1,
            cssTemplate: function (data) {
                var classMediaRespond = '.icon';
                var classNamespace = '.i-';
                var result =
                    classMediaRespond + ' {\n\tbackground-image: url(../img/sprites/' + data.spritesheet.image + ');\n}' +
                    '\n\n@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {' +
                    '\n\t' + classMediaRespond + ' {' +
                    '\n\t\tbackground-image: url(../img/sprites/' + data.retina_spritesheet.image + ');' +
                    '\n\t\tbackground-size: ' + data.spritesheet.px.width + ' ' + data.spritesheet.px.height + ';\n\t}\n}';
                for (var i = 0; i < data.items.length; i++) {
                    result +=
                        '\n\n@' + data.items[i].name + '-bg-position: ' + data.items[i].offset_x + 'px ' + data.items[i].offset_y + 'px;' +
                        '\n@' + data.items[i].name + '-width: ' + data.items[i].width + 'px;' +
                        '\n@' + data.items[i].name + '-height: ' + data.items[i].height + 'px;' +
                        '\n' + classNamespace + data.items[i].name + ' {' +
                        '\n\tbackground-position: ' + data.items[i].offset_x + 'px ' + data.items[i].offset_y + 'px;' +
                        '\n\twidth: ' + data.items[i].width + 'px;' +
                        '\n\theight: ' + data.items[i].height + 'px;' +
                        '\n}';
                }
                return result;
            }
        })
    );
    spriteData.img.pipe(gulp.dest(paths.sourceDir + '/img/sprites/'));
    spriteData.css.pipe(gulp.dest(paths.sourceDir + '/less/'));
});

gulp.task('svg', function () {
    gulp.src(paths.sourceDir +'img/ui-icons/*.svg')
    .pipe(svgmin({
        js2svg: {
            pretty: true
        }
    }))
    .pipe(cheerio({
        run: function ($) {
            $('[fill]').removeAttr('fill');
            $('[stroke]').removeAttr('stroke');
            $('[style]').removeAttr('style');
        },
        parserOptions: { xmlMode: true }
    }))
    .pipe(replace('&gt;', '>'))
    .pipe(svgSprite({
        shape: {
            id: {
                generator: 'i-%s'
            },
            dimension: {
                //maxWidth: 32,
                //maxHeight: 32,
                precision: 0
            },
            spacing: {
                padding: 3
            },
        },
        mode: {
            symbol: {
                dest: 'img/sprites',
                sprite: 'sprite.svg',
                render: {
                    less: {
                        dest: '../../less/~svg-icons'
                    }
                },
                bust: false,
                dimensions: '-dim',
            }
        }
    }))
    .pipe(replace('<svg', '<svg style="display:none;" aria-hidden="true;"'))
    .pipe(gulp.dest(paths.sourceDir));
    gulp.src(paths.sourceDir + 'js/svg-revision.js')
        .pipe(replace(/revision \= (\d*)/g, 'revision = ' + new Date().getTime()))
        .pipe(gulp.dest(paths.sourceDir + 'js'));
});

// DEV js code style
gulp.task('jscs', function () {
    return gulp.src(paths.sourceDir + 'js/*.js')
        .pipe(jscs())
        .pipe(jscs.reporter());
});

// DEV js lint
gulp.task('lint', function () {
    return gulp.src(paths.sourceDir + 'js/ui.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// DEV browser sync
gulp.task('serve', function () {
    browserSync.init({
        server: {
            baseDir: './static',
            directory: true
        },
        ghostMode: {
            clicks: false,
            forms: false,
            scroll: false
        }
    });
});

// DEV modernizr
gulp.task('modernizr', function () {
    gulp.src(paths.sourceDir + 'js/*.js')
        .pipe(modernizr('modernizr.min.js', {
            crawl: false,
            tests: [/*'touchevents'*/],
            options: [
                'domPrefixes',
                'prefixes',
                'testAllProps',
                'testProp',
                'testStyles',
                'setClasses'
            ]
        }))
        .pipe(uglify({
            output: {
                comments: saveLicense
            }
        }))
        .pipe(gulp.dest(paths.sourceDir + 'js/vendor'))
});

gulp.task('reload-html', ['templates'], function () {
    browserSync.reload();
});

gulp.task('reload-js', ['jscs'], function () {
    browserSync.reload();
});

// DEV watch
gulp.task('watch', function () {
    gulp.watch(paths.sourceDir + 'less/**/*.less', ['css']);
    gulp.watch([paths.sourceDir + 'img/ui-icons/**/*.png'], ['spritesheets']);
    gulp.watch(paths.sourceDir + 'img/ui-icons/**/*.svg', ['svg']);
    gulp.watch(paths.sourceDir + 'js/*.js', ['reload-js']);
    gulp.watch(paths.sourceDir + 'js/ui.js', ['lint']);
    gulp.watch([paths.templateDir + '_includes/**/*.twig', paths.templateDir + '_includes/*.json'], ['reload-html']);
});

// PROD clean
gulp.task('clean', function () {
    return del([
        paths.publicDir + '**/*',
        paths.sourceDir + 'js/*.js.gz'
    ]);
});

// PROD uglify
gulp.task('uglify', ['clean'], function () {
    return gulp.src(uglifyList)
        .pipe(uglify('app.min.js'))
        .pipe(gulp.dest(paths.publicDir + 'js'))
});

// PROD copy
gulp.task('copy-img', ['clean'], function () {
    return gulp.src(['**/*', '!ui-icons', '!ui-icons/**/*', '!zzz', '!zzz/**/*'], {cwd: paths.sourceDir + 'img'})
        .pipe(gulp.dest(paths.publicDir + 'img'))
});
gulp.task('copy-font', ['clean'], function () {
    return gulp.src(['**/*'], {cwd: paths.sourceDir + 'font'})
        .pipe(gulp.dest(paths.publicDir + 'font'))
});

// PROD css min
gulp.task('cssnano', ['css', 'clean'], function () {
    return gulp.src(paths.sourceDir + 'css/app.css')
        /* uncss
        .pipe(uncss({
            html: [paths.templateDir + '*.html'],
            ignore: uncssIgnoreClass
        }))
        uncss */
        .pipe(cssnano())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(paths.publicDir + 'css'));
});

// PROD revision
gulp.task('rev', ['copy-img', 'cssnano', 'uglify'], function () {
    return gulp.src([
            paths.publicDir + 'js/app.min.js',
            paths.publicDir + 'css/app.min.css',
            paths.publicDir + '/img/**/*.{jpg,jpeg,png,gif}'
        ], {base: paths.publicDir})
        .pipe(rev())
        .pipe(override())
        .pipe(gulp.dest(paths.publicDir))
        .pipe(rev.manifest({
            base: paths.publicDir,
            path: paths.publicDir + 'manifest.json',
            merge: true
        }))
        .pipe(gulp.dest(paths.publicDir));
});

// PROD compress
gulp.task('compress', function () {
    gulp.src(paths.publicDir + 'js/*.js')
        .pipe(gzip())
        .pipe(gulp.dest(paths.publicDir + 'js'));
    gulp.src(paths.publicDir + 'css/*.css')
        .pipe(gzip())
        .pipe(gulp.dest(paths.publicDir + 'css'));
});

// PROD chmod
gulp.task('chmod', function () {
    return gulp.src(['**/*', '!node_modules/**/*', '!artisan'])
        .pipe(data(function (file) {
            var path = file.path;
            var stats = fs.statSync(path);
            if (stats.isDirectory()) {
                fs.chmod(path, '755');
            } else {
                fs.chmod(path, '644');
            }
        }))
});

//gulp.task('demo', function () {
//    gulp.src([paths.sourceDir + '**/*'])
//        .pipe(gulp.dest(demoDir + '/src'));
//    gulp.src([paths.templateDir + '*.html'])
//        .pipe(replace('/src', 'src'))
//        .pipe(gulp.dest(demoDir));
//});

gulp.task('default', ['watch', 'serve']);

gulp.task('sprite', ['spritesheets', 'css']);

gulp.task('build', ['clean', 'copy-img', 'copy-font', 'uglify', 'cssnano', 'rev']);
