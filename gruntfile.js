module.exports = function (grunt) {
	require('jit-grunt')(grunt);

	// Every widget/third-party stylesheet goes into the editor bundle.
	var all_styles = [
		'src/css/widgets/*.css',
		'src/css/third-party-widgets/*.css',
	];

	// Every module script goes into the editor bundle.
	var all_js = [
		'src/frontend/js/modules/*.js',
	];

	grunt.initConfig({
		less: {
			development: {
				options: {
					compress: true,
					yuicompress: true,
					optimization: 2,
				},
				files: [
					{
						// Editor/preview bundle (loaded next to Pro's ep-styles).
						'assets/css/ep-addon-styles.css': all_styles,
					},
					// per-widget files: src/css/widgets/foo.css -> assets/css/ep-foo.css
					{
						expand: true,
						cwd: 'src/css/widgets/',
						src: ['**/*.css', '!**/*.rtl.css'],
						dest: 'assets/css/',
						ext: '.css',
						rename: function (dest, src) {
							return dest + src.replace(/(.+)\.css$/, 'ep-$1.css');
						},
					},
					{
						expand: true,
						cwd: 'src/css/third-party-widgets/',
						src: ['**/*.css', '!vendor/**/*.css'],
						dest: 'assets/css/',
						ext: '.css',
						rename: function (dest, src) {
							return dest + src.replace(/(.+)\.css$/, 'ep-$1.css');
						},
					},
				],
			},
		},

		concat: {
			scripts: {
				src: all_js,
				dest: 'src/editor/js/ep-addon-scripts.js',
			},
		},

		terser: {
			options: {
				mangle: true,
			},
			static_files: {
				files: {
					'assets/js/ep-addon-scripts.min.js': ['src/editor/js/ep-addon-scripts.js'],
				},
			},
			dynamic_files: {
				files: [
					{
						expand: true,
						cwd: 'src/frontend/js/modules/',
						src: ['**/*.js', '!**/*.min.js'],
						dest: 'assets/js/modules/',
						ext: '.min.js',
					},
					{
						expand: true,
						cwd: 'src/vendor/js/',
						src: ['**/*.js', '!**/*.min.js'],
						dest: 'assets/vendor/js/',
						ext: '.min.js',
					},
				],
			},
		},

		copy: {
			build: {
				files: [
					{
						expand: true,
						cwd: 'src/vendor/',
						src: ['**', '!js/*.js', 'js/*.min.js'],
						dest: 'assets/vendor/',
					},
				],
			},
		},

		watch: {
			styles: {
				files: ['src/css/**/*.css'],
				tasks: ['less'],
				options: { spawn: false },
			},
			scripts: {
				files: ['src/frontend/js/**/*.js', 'src/vendor/js/**/*.js'],
				tasks: ['concat', 'terser', 'copy'],
				options: { spawn: false },
			},
		},
	});

	grunt.loadNpmTasks('grunt-terser');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-concat');

	if (process.env.NODE_ENV === 'development') {
		grunt.registerTask('default', ['less', 'concat', 'terser', 'copy', 'watch']);
	} else {
		grunt.registerTask('default', ['less', 'concat', 'terser', 'copy']);
	}
};
