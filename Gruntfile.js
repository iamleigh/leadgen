module.exports = function (grunt) {

	require('load-grunt-tasks')(grunt);
	
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-cssmin');

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		sass: {
			options: {
				sourceMap: true,
				outputStyle: 'nested',
				sourceComments: false
			},
			dist: {
				files: {
					'assets/css/leadgen.css': 'assets/sass/leadgen.scss'
				}
			}
		},

		autoprefixer: {
			options: {
				browsers: ['last 2 version', '> 1%', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4']
			},
			dist: {
				files: {
					'assets/css/leadgen.css': 'assets/css/leadgen.css'
				}
			}
		},

		cssmin: {
			options: {
				sourceMap: true
			},
			target: {
				files: {
					'assets/css/leadgen.css': ['assets/css/leadgen.css']
				}
			}
		},

		watch: {
			sass: {
				files: ['**/*.scss'],
				tasks: ['sass', 'autoprefixer', 'cssmin'],
				options: {
					spawn: false
				}
			}
		},

		// TEST - Run the PHPUnit tests.
		phpunit: {
			classes: {
				dir: ''
			},
			options: {
				bin: 'phpunit',
				bootstrap: 'tests/php/bootstrap.php',
				testsuite: 'default',
				configuration: 'tests/php/phpunit.xml',
				colors: true,
				staticBackup: false,
				noGlobalsBackup: false
			}
		},

	});

	grunt.registerTask( 'default', ['watch:sass', 'uglify'] );
	
};