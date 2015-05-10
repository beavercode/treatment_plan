'use strict';

module.exports = function (grunt) {
    var conf = {
        buildDir: './../assets',
        htmlDir: './../html',
        sassDir: 'sass',
        sassExt: 'scss',
        browserPrefix: ['last 2 versions', '> 1%', 'ie 8'],
        jsDir: 'js',
        imgDir: 'img',
        serverHost: '0.0.0.0',
        serverPort: 7778,
        serverBase: './../',
        serverLiveReload: true
    };

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        conf: conf,
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            dist: {
                options: {
                    // cssmin will minify later because of autoprefixer
                    style: 'expanded',
                    sourcemap: 'none'
                },
                files: [{
                    expand: true,
                    cwd: '<%= conf.sassDir %>/',
                    src: ['*.<%= conf.sassExt %>'],
                    dest: '<%= conf.buildDir %>/css/',
                    ext: '.css'
                }]
            }
        },

        autoprefixer: {
            options: {
                browsers: '<%= conf.browserPrefix %>'
            },
            dist: {
                expand: true,
                flatten: true,
                src: '<%= conf.buildDir %>/css/*.css',
                dest: '<%= conf.buildDir %>/css/'
            }
        },

        cssmin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= conf.buildDir %>/css',
                    src: ['*.css', '!*.min.css'],
                    dest: '<%= conf.buildDir %>/css',
                    ext: '.min.css'
                }]
            }
        },

        jshint: {
            options: {
                reporter: require('jshint-stylish')
            },
            beforeconcat: ['<%= conf.jsDir %>/*.js'],
            afterconcat: ['<%= conf.buildDir %>/js/app.js']
        },

        concat: {
            dist: {
                src: [
                    '<%= conf.jsDir %>/**/*.js'
                ],
                dest: '<%= conf.buildDir %>/js/app.js'
            }
        },

        uglify: {
            dist: {
                src: '<%= conf.buildDir %>/js/*.js',
                dest: '<%= conf.buildDir %>/js/app.min.js'
            }
        },

        imagemin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= conf.imgDir %>/',
                    src: ['**/*.{png,jpg,gif,ico}'],
                    dest: '<%= conf.buildDir %>/img'
                }]
            }
        },

        clean: {
            options: {
                force: true
            },
            old: ['<%= conf.buildDir %>'],
            js: ['<%= conf.buildDir %>/**/*.js', "!<%= conf.buildDir %>/**/*.min.js"],
            old_js: ["<%= conf.buildDir %>/**/*.min.js"],
            css: ['<%= conf.buildDir %>/**/*.css', "!<%= conf.buildDir %>/**/*.min.css"]
        },

        connect: {
            server: {
                options: {
                    hostname: '<%= conf.serverHost %>',
                    port: '<%= conf.serverPort %>',
                    base: '<%= conf.serverBase %>',
                    livereload: '<%= conf.serverLiveReload %>'
                }
            }
        },

        // stub for compass
        /*        focus: {
         sass: {
         include: []
         },
         compass: {
         exclude: []
         }
         },*/

        copy: {
            tb: {
                files: [{
                    expand: true,
                    src: ['fonts/*'],
                    dest: '<%= conf.buildDir %>',
                    filter: 'isFile'
                }]
            }
        },

        watch: {
            scripts: {
                files: ['<%= conf.jsDir %>/**/*.js'],
                tasks: ['jshint:beforeconcat', 'clean:old_js','concat', /*'jshint:afterconcat',*/ 'uglify', 'clean:js'],
                options: {
                    spawn: false,
                    livereload: true
                }
            },
            css: {
                files: ['<%= conf.sassDir %>/*.<%= conf.sassExt %>'],
                tasks: ['sass:dist', 'autoprefixer:dist', 'cssmin:dist', 'clean:css'],
                options: {
                    spawn: false,
                    livereload: true
                }
            },
            images: {
                files: ['<%= conf.imgDir %>/**/*.{png,jpg,gif}'],
                tasks: ['newer:imagemin'],
                options: {
                    spawn: false,
                    livereload: true
                }
            },
            html: {
                files: ['<%= conf.htmlDir %>/**/*.html'],
                tasks: ['newer:copy'],
                options: {
                    spawn: false,
                    livereload: true
                }
            }
        }
    });

    grunt.registerTask('build_template',
        ['clean:old', 'sass', 'autoprefixer', 'cssmin', 'concat', 'uglify', 'imagemin', 'clean:js', 'clean:css']);

    grunt.registerTask('build',
        ['clean:old', 'sass', 'autoprefixer', 'cssmin', 'concat', 'uglify', 'imagemin', 'copy', 'clean:js', 'clean:css']);

    grunt.registerTask('default', ['connect', 'watch']);
};