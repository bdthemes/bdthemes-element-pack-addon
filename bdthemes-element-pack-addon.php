<?php
/**
 * Plugin Name: Element Pack Addon
 * Plugin URI: https://elementpack.pro/
 * Description: Companion plugin for Element Pack Pro. It carries the legacy widgets that are no longer bundled with Element Pack Pro (Post widgets, Crypto Currency, bbPress, BuddyPress, Charitable, Give, Easy Digital Downloads, Timeline and more) so existing pages keep working.
 * Version: 1.0.0
 * Author: BdThemes
 * Author URI: https://bdthemes.com/
 * Text Domain: bdthemes-element-pack
 * Domain Path: /languages
 * License: GPL3
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Elementor requires at least: 4.0.0
 * Elementor tested up to: 4.2.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'BDTEPA_VER', '1.0.0' );
define( 'BDTEPA__FILE__', __FILE__ );
define( 'BDTEPA_PATH', plugin_dir_path( __FILE__ ) );
define( 'BDTEPA_URL', plugin_dir_url( __FILE__ ) );
define( 'BDTEPA_INC_PATH', BDTEPA_PATH . 'includes/' );
define( 'BDTEPA_MODULES_PATH', BDTEPA_PATH . 'modules/' );
define( 'BDTEPA_ASSETS_PATH', BDTEPA_PATH . 'assets/' );
define( 'BDTEPA_ASSETS_URL', BDTEPA_URL . 'assets/' );

/**
 * First Element Pack Pro release with the companion-module hooks
 * (element_pack/modules/external_sources + element_pack/modules/settings_fields).
 * Used for the admin message only; the real gate is a capability check, see
 * bdthemes_element_pack_addon_is_ep_ready().
 */
define( 'BDTEPA_REQUIRED_EP_VER', '10.0.0' );

require_once BDTEPA_INC_PATH . 'modules-list.php';

/**
 * Register our modules with Element Pack Pro.
 *
 * Both filters are added at file load so they are in place no matter when Pro
 * builds its module lists (Pro boots on plugins_loaded priority 9 and reads the
 * lists on elementor/init and on admin/editor requests).
 */
add_filter( 'element_pack/modules/external_sources', function ( $sources ) {
	foreach ( \ElementPackAddon\Modules_List::get_slugs() as $slug ) {
		$sources[ $slug ] = [
			'dir'       => BDTEPA_PATH,
			'url'       => BDTEPA_URL,
			'namespace' => 'ElementPackAddon',
			'version'   => BDTEPA_VER,
		];
	}

	return $sources;
} );

add_filter( 'element_pack/modules/settings_fields', function ( $settings_fields ) {
	// Entries go back to the positions they had in Pro's lists (see
	// Modules_List::get_anchors() for why the order matters).
	return \ElementPackAddon\Modules_List::merge_into( (array) $settings_fields );
} );

/**
 * Boot after Element Pack Pro (which loads on plugins_loaded priority 9).
 */
function bdthemes_element_pack_addon_load() {
	if ( ! bdthemes_element_pack_addon_is_ep_ready() ) {
		add_action( 'admin_notices', 'bdthemes_element_pack_addon_fail_load' );
		return;
	}

	require_once BDTEPA_PATH . 'loader.php';

	\ElementPackAddon\Loader::instance();
}
add_action( 'plugins_loaded', 'bdthemes_element_pack_addon_load', 10 );

/**
 * Pro's optimized asset bundle (uploads/element-pack/minified) is built from
 * the modules registered at build time. Forget the fingerprint it was built
 * from on (de)activation so Pro rebuilds it on the next request, otherwise our
 * widgets render without their CSS/JS until someone saves the EP settings.
 */
function bdthemes_element_pack_addon_invalidate_optimized_assets() {
	delete_option( 'element_pack_optimized_assets_fingerprint' );
}
register_activation_hook( __FILE__, 'bdthemes_element_pack_addon_invalidate_optimized_assets' );
register_deactivation_hook( __FILE__, 'bdthemes_element_pack_addon_invalidate_optimized_assets' );

/**
 * Element Pack Pro is active and exposes the companion-module hooks.
 *
 * A capability check rather than a version compare, so development builds of
 * Pro that carry the hooks under an older version number still work.
 *
 * @return bool
 */
function bdthemes_element_pack_addon_is_ep_ready() {
	if ( ! defined( 'BDTEP_VER' ) || ! class_exists( '\ElementPack\Admin\ModuleService' ) ) {
		return false;
	}

	return method_exists( '\ElementPack\Admin\ModuleService', 'get_external_modules' );
}

function bdthemes_element_pack_addon_fail_load() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( defined( 'BDTEP_VER' ) ) {
		$message = sprintf(
			/* translators: 1: required Element Pack Pro version, 2: installed version */
			esc_html__( 'Element Pack Addon needs Element Pack Pro %1$s or newer (you have %2$s). Please update Element Pack Pro.', 'bdthemes-element-pack' ),
			BDTEPA_REQUIRED_EP_VER,
			BDTEP_VER
		);
	} else {
		$message = esc_html__( 'Element Pack Addon needs the Element Pack Pro plugin to be installed and activated.', 'bdthemes-element-pack' );
	}

	printf( '<div class="notice notice-error"><p>%s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above
}
