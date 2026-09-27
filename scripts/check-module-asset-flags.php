<?php
/**
 * Checks every module's `has_style` / `has_script` flags against the built files.
 *
 * Element Pack Pro's Manager registers `ep-<slug>` from these flags alone, against
 * assets/css/ep-<slug>.css and assets/js/modules/ep-<slug>.min.js in this plugin,
 * while Pro's asset optimizer bundles whichever of those files exist. So flag and
 * file have to agree: a flag with no file registers a 404, and a file with no flag
 * leaves the `ep-<slug>` handle a widget declares unregistered, so the widget loses
 * that file and the UIkit/helper dependencies that come with the handle.
 *
 * Also checks that assets/ holds exactly what src/ builds (see gruntfile.js), and
 * warns about widget handles that nothing registers.
 *
 *     php scripts/check-module-asset-flags.php [plugin-dir]
 *
 * Exits 1 when a flag or build mismatch is found; widget-handle warnings do not
 * change the exit status.
 */

if ( 'cli' !== PHP_SAPI ) {
	exit;
}

$root = rtrim( isset( $argv[1] ) ? $argv[1] : dirname( __DIR__ ), '/' ) . '/';

if ( ! is_dir( $root . 'modules' ) ) {
	fwrite( STDERR, "No modules/ directory in {$root}\n" );
	exit( 2 );
}

// module.info.php files bail out without ABSPATH and translate their title.
define( 'ABSPATH', $root );

if ( ! function_exists( 'esc_html__' ) ) {
	function esc_html__( $text ) {
		return $text;
	}

	function __( $text ) {
		return $text;
	}
}

$relative = function ( $path ) use ( $root ) {
	return substr( $path, strlen( $root ) );
};

$kinds = [
	'style'  => [ 'flag' => 'has_style', 'file' => 'assets/css/ep-%s.css', 'method' => 'get_style_depends' ],
	'script' => [ 'flag' => 'has_script', 'file' => 'assets/js/modules/ep-%s.min.js', 'method' => 'get_script_depends' ],
];

// Built file => the source gruntfile.js builds it from.
$built_from = [];

foreach ( glob( $root . 'src/css/widgets/*.css' ) as $source ) {
	if ( '.rtl.css' !== substr( $source, -8 ) ) {
		$built_from[ 'assets/css/ep-' . basename( $source ) ] = $relative( $source );
	}
}

foreach ( glob( $root . 'src/css/third-party-widgets/*.css' ) as $source ) {
	$built_from[ 'assets/css/ep-' . basename( $source ) ] = $relative( $source );
}

foreach ( glob( $root . 'src/frontend/js/modules/*.js' ) as $source ) {
	if ( '.min.js' !== substr( $source, -7 ) ) {
		$built_from[ 'assets/js/modules/' . basename( $source, '.js' ) . '.min.js' ] = $relative( $source );
	}
}

$flag_errors  = [];
$build_errors = [];
$warnings     = [];

// Built files a flag mismatch already covers, so the build check does not report them twice.
$reported = [];

// Read each module.info.php the way Pro's ModuleService does: require it, use the array it returns.
$dirs    = glob( $root . 'modules/*', GLOB_ONLYDIR );
$modules = [];

foreach ( $dirs as $dir ) {
	$slug = basename( $dir );
	$file = $dir . '/module.info.php';
	$info = is_file( $file ) ? ( function () use ( $file ) {
		return require $file;
	} )() : null;

	if ( ! is_array( $info ) ) {
		$flag_errors[] = "{$slug}: module.info.php is missing or returns no array, so Pro reads no flags for it";
		continue;
	}

	$modules[ $slug ] = $info;
}

// 1. Flags against built files. Whether src/ builds the file decides which side is wrong.
foreach ( $modules as $slug => $info ) {
	foreach ( $kinds as $spec ) {
		$flag   = $spec['flag'];
		$file   = sprintf( $spec['file'], $slug );
		$source = isset( $built_from[ $file ] ) ? $built_from[ $file ] : null;

		if ( ! empty( $info[ $flag ] ) && ! is_file( $root . $file ) ) {
			$flag_errors[] = $source
				? "{$slug}: {$flag} is set and {$source} exists, but {$file} was never built: run npm run build"
				: "{$slug}: {$flag} is set, but {$file} does not exist and nothing in src/ builds it: remove {$flag}";
		} elseif ( empty( $info[ $flag ] ) && is_file( $root . $file ) ) {
			$empty = 0 === filesize( $root . $file ) ? ' (empty)' : '';

			$flag_errors[] = $source
				? "{$slug}: {$file}{$empty} is built from {$source}, but {$flag} is not set, so ep-{$slug} is never registered: set '{$flag}' => true"
				: "{$slug}: {$file}{$empty} exists, but {$flag} is not set and nothing in src/ builds it: delete the file or restore its source";
		} else {
			continue;
		}

		$reported[ $file ] = true;
	}
}

// 2. assets/ against src/. Grunt only ever adds files, so a removed source leaves its output behind.
foreach ( $built_from as $file => $source ) {
	if ( ! isset( $reported[ $file ] ) && ! is_file( $root . $file ) ) {
		$build_errors[] = "{$source} was never built to {$file}: run npm run build";
	}
}

foreach ( array_merge( glob( $root . 'assets/css/ep-*.css' ), glob( $root . 'assets/js/modules/ep-*.min.js' ) ) as $path ) {
	$file = $relative( $path );

	// ep-addon-styles.css is the editor bundle, built from every stylesheet.
	if ( isset( $reported[ $file ] ) || 'assets/css/ep-addon-styles.css' === $file ) {
		continue;
	}

	$slug   = preg_replace( '/^ep-(.+?)(\.min\.js|\.css)$/', '$1', basename( $file ) );
	$module = is_dir( $root . 'modules/' . $slug );
	$flag   = '.css' === substr( $file, -4 ) ? 'has_style' : 'has_script';

	if ( ! isset( $built_from[ $file ] ) ) {
		$build_errors[] = $module
			? "{$file} has no source in src/: restore its source, or delete it and {$slug}'s {$flag}"
			: "{$file} has no source in src/ and belongs to no module: delete it";
	} elseif ( ! $module ) {
		$build_errors[] = "{$file} is built from {$built_from[ $file ]}, but belongs to no module, so nothing registers it outside the editor bundle";
	}
}

// 3. Widget handles. Reads the string literals in each widget's get_*_depends() methods.
$method_strings = function ( array $tokens, $name ) {
	$count = count( $tokens );

	for ( $i = 0; $i < $count; $i++ ) {
		if ( ! is_array( $tokens[ $i ] ) || T_FUNCTION !== $tokens[ $i ][0] ) {
			continue;
		}

		$j = $i + 1;
		while ( $j < $count && is_array( $tokens[ $j ] ) && T_WHITESPACE === $tokens[ $j ][0] ) {
			$j++;
		}

		if ( ! isset( $tokens[ $j ] ) || ! is_array( $tokens[ $j ] ) || T_STRING !== $tokens[ $j ][0] || $name !== $tokens[ $j ][1] ) {
			continue;
		}

		while ( $j < $count && '{' !== $tokens[ $j ] ) {
			$j++;
		}

		$strings = [];
		$depth   = 0;

		for ( ; $j < $count; $j++ ) {
			$token = $tokens[ $j ];

			if ( '{' === $token || ( is_array( $token ) && in_array( $token[0], [ T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES ], true ) ) ) {
				$depth++;
			} elseif ( '}' === $token && 0 === --$depth ) {
				break;
			} elseif ( is_array( $token ) && T_CONSTANT_ENCAPSED_STRING === $token[0] ) {
				$strings[] = substr( $token[1], 1, -1 );
			}
		}

		return $strings;
	}

	return [];
};

$declared = [ 'style' => [], 'script' => [] ]; // handle => widget files naming it

foreach ( glob( $root . 'modules/*/widgets/*.php' ) as $widget ) {
	$tokens = token_get_all( file_get_contents( $widget ) );

	foreach ( $kinds as $kind => $spec ) {
		foreach ( array_unique( $method_strings( $tokens, $spec['method'] ) ) as $handle ) {
			$declared[ $kind ][ $handle ][] = $relative( $widget );
		}
	}
}

foreach ( $kinds as $kind => $spec ) {
	foreach ( $declared[ $kind ] as $handle => $widgets ) {
		$slug = substr( $handle, 3 );

		// Only ep-<slug> handles of this plugin's modules; ep-font, ep-addon-styles etc. are Pro's or the loader's.
		if ( 0 !== strpos( $handle, 'ep-' ) || ! isset( $modules[ $slug ] ) || ! empty( $modules[ $slug ][ $spec['flag'] ] ) ) {
			continue;
		}

		foreach ( $widgets as $widget ) {
			$warnings[] = "{$widget}: {$spec['method']}() names '{$handle}', but {$slug} has no {$spec['flag']}, so that {$kind} is never registered";
		}
	}

	foreach ( $modules as $slug => $info ) {
		$file = sprintf( $spec['file'], $slug );

		if ( ! empty( $info[ $spec['flag'] ] ) && is_file( $root . $file ) && ! isset( $declared[ $kind ][ 'ep-' . $slug ] ) ) {
			$empty = 0 === filesize( $root . $file ) ? ' (it is empty)' : '';

			$warnings[] = "{$slug}: no widget's {$spec['method']}() names 'ep-{$slug}', so {$file}{$empty} reaches pages only through Pro's bundles";
		}
	}
}

$section = function ( $title, array $lines ) {
	echo $title, "\n";
	echo $lines ? '  ' . implode( "\n  ", $lines ) : '  none', "\n\n";
};

printf( "Module asset flags in %s (%d modules)\n\n", $root, count( $dirs ) );

$section( 'Flag mismatches', $flag_errors );
$section( 'Build mismatches', $build_errors );
$section( 'Widget handles (warnings)', $warnings );

$mismatches = count( $flag_errors ) + count( $build_errors );

echo $mismatches ? "{$mismatches} mismatch(es).\n" : "No mismatches.\n";

exit( $mismatches ? 1 : 0 );
