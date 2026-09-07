<?php

namespace ElementPackAddon;

use Elementor\Plugin;
use ElementPackAddon\Includes\Wpml;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Element Pack Addon bootstrap.
 *
 * Module loading itself is done by Element Pack Pro's Manager: our modules are
 * registered on its filters (see bdthemes-element-pack-addon.php) and Pro
 * resolves their classes/assets from this plugin. What is left for us:
 *   - the PSR-ish autoloader for the ElementPackAddon namespace
 *   - vendor scripts that only our widgets use
 *   - the editor/preview bundles (ep-addon-styles / ep-addon-scripts)
 *   - the Elementor categories our widgets are filed under
 *   - WPML translatable nodes
 */
class Loader {

	/** @var Loader|null */
	private static $_instance = null;

	public static function instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		require_once BDTEPA_INC_PATH . 'class-wpml.php';

		add_action( 'wp_enqueue_scripts', [ $this, 'register_vendor_scripts' ], 99999 );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_bundles' ], 99999 );
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_categories' ] );
		add_action( 'elementor/init', [ $this, 'init_wpml' ] );
	}

	/**
	 * ElementPackAddon\Modules\PostGrid\Widgets\Post_Grid -> modules/post-grid/widgets/post-grid.php
	 * Same mapping Element Pack Pro uses for its own namespace.
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ . '\\' ) ) {
			return;
		}

		$filename = strtolower(
			preg_replace(
				[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z0-9])/', '/_/', '/\\\/' ],
				[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$class
			)
		);

		$filename = BDTEPA_PATH . $filename . '.php';

		if ( is_readable( $filename ) ) {
			include $filename;
		}
	}

	private function is_edit_or_preview() {
		$elementor = Plugin::$instance;

		return $elementor->preview->is_preview_mode() || $elementor->editor->is_edit_mode();
	}

	/**
	 * Third-party libraries our widgets depend on. Handles shared with Pro widgets
	 * are registered from Pro's vendor dir only when Pro did not already register
	 * them (Pro gates those on its own remaining widgets).
	 */
	public function register_vendor_scripts() {
		$ep_vendor = BDTEP_ASSETS_URL . 'vendor/js/';
		$vendor    = BDTEPA_ASSETS_URL . 'vendor/js/';

		$shared = [
			// handle => [ widgets that need it, file in Pro's vendor dir, deps, version ]
			'gridtab'  => [ [ 'post-grid-tab' ], 'gridtab.min.js', [ 'jquery' ], '2.1.1' ],
			'recliner' => [ [ 'post-grid-tab' ], 'recliner.min.js', [ 'jquery' ], '0.2.2' ],
			'tilt'     => [ [ 'post-gallery' ], 'vanilla-tilt.min.js', [ 'jquery' ], BDTEP_VER ],
			'chart'    => [ [ 'crypto-currency-chart', 'crypto-currency-chart-carousel' ], 'chart.min.js', [ 'jquery' ], '3.9.1' ],
			'popper'   => [ [ 'member' ], 'popper.min.js', [ 'jquery' ], BDTEP_VER ],
			'tippyjs'  => [ [ 'member' ], 'tippy.all.min.js', [ 'jquery' ], BDTEP_VER ],
		];

		foreach ( $shared as $handle => $spec ) {
			list( $widgets, $file, $deps, $version ) = $spec;
			if ( wp_script_is( $handle, 'registered' ) || ! $this->any_widget_enabled( $widgets ) ) {
				continue;
			}
			wp_register_script( $handle, $ep_vendor . $file, $deps, $version, true );
		}

		if ( $this->any_widget_enabled( [ 'edd-login', 'edd-register' ] ) && ! wp_script_is( 'recaptcha', 'registered' ) ) {
			wp_register_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', [ 'jquery' ], BDTEP_VER, true );
		}

		$own = [
			'darkmode'   => [ [ 'dark-mode' ], 'darkmode.min.js', '1.1.1' ],
			'timeline'   => [ [ 'timeline' ], 'timeline.min.js', BDTEPA_VER ],
			'slinky'     => [ [ 'slinky-vertical-menu' ], 'slinky.min.js', '4.2.1' ],
			'honeycombs' => [ [ 'honeycombs' ], 'jquery.honeycombs.min.js', '0.0.1' ],
		];

		foreach ( $own as $handle => $spec ) {
			list( $widgets, $file, $version ) = $spec;
			if ( $this->any_widget_enabled( $widgets ) ) {
				wp_register_script( $handle, $vendor . $file, [ 'jquery' ], $version, true );
			}
		}
	}

	private function any_widget_enabled( array $widgets ) {
		foreach ( $widgets as $widget ) {
			if ( element_pack_is_widget_enabled( $widget ) || element_pack_is_third_party_enabled( $widget ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * In the editor/preview Pro loads one bundle (ep-styles / ep-scripts) instead
	 * of per-widget files; our widgets return ep-addon-styles / ep-addon-scripts
	 * there, which are the same kind of bundle built from this plugin's modules.
	 */
	public function register_bundles() {
		wp_register_style( 'ep-addon-styles', BDTEPA_ASSETS_URL . 'css/ep-addon-styles.css', [ 'ep-styles' ], BDTEPA_VER );
		wp_register_script( 'ep-addon-scripts', BDTEPA_ASSETS_URL . 'js/ep-addon-scripts.min.js', [ 'jquery', 'ep-scripts' ], BDTEPA_VER, true );

		if ( $this->is_edit_or_preview() ) {
			wp_enqueue_style( 'ep-addon-styles' );
			wp_enqueue_script( 'ep-addon-scripts' );
		}
	}

	public function register_categories( $elements_manager ) {
		$elements_manager->add_category( BDTEP_SLUG . '-bbpress', [ 'title' => 'BbPress', 'icon' => 'eicon-search' ] );
		// The crypto widgets file themselves under this category.
		$elements_manager->add_category( 'crypto-currency', [ 'title' => esc_html__( 'Crypto Currency', 'bdthemes-element-pack' ), 'icon' => 'font' ] );
	}

	public function init_wpml() {
		( new Wpml() )->init();
	}
}
