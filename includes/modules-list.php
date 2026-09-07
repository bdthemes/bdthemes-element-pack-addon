<?php
/**
 * The settings entries for every module shipped by Element Pack Addon.
 *
 * These are byte-for-byte the entries Element Pack Pro used to carry in
 * ModuleService::get_widget_settings(); they are appended back into the same
 * three tiers through the `element_pack/modules/settings_fields` filter, so the
 * dashboard toggles, option keys, permission manager and asset optimizer keep
 * working exactly as before the move.
 */

namespace ElementPackAddon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Modules_List {

	/**
	 * Slugs of all modules in this plugin (folder names under modules/).
	 *
	 * @return string[]
	 */
	public static function get_slugs() {
		static $slugs = null;

		if ( $slugs === null ) {
			$slugs = [];
			foreach ( self::get_settings_fields() as $tier ) {
				foreach ( $tier as $entry ) {
					$slugs[ $entry['name'] ] = true;
				}
			}
			$slugs = array_keys( $slugs );
		}

		return $slugs;
	}

	/**
	 * @param string $plugin_path e.g. easy-digital-downloads/easy-digital-downloads.php
	 * @return bool
	 */
	private static function is_plugin_installed( $plugin_path ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$installed = get_plugins();

		return isset( $installed[ $plugin_path ] );
	}

	/**
	 * Settings entries keyed by tier, same shape as Element Pack Pro's.
	 *
	 * @return array{element_pack_active_modules: array, element_pack_third_party_widget: array}
	 */
	public static function get_settings_fields() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$fields = [
			'element_pack_active_modules'     => [],
			'element_pack_elementor_extend'   => [],
			'element_pack_third_party_widget' => [],
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'age-gate',
			'label'        => esc_html__('Age Gate', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'free',
			'content_type' => 'custom',
			'demo_url'     => 'https://elementpack.pro/demo/element/age-gate/',
			'video_url'    => 'https://youtu.be/I32wKLfNIes',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'air-pollution',
			'label'        => esc_html__('Air Pollution', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'custom',
			'demo_url'     => 'https://elementpack.pro/demo/element/air-pollution/',
			'video_url'    => 'https://youtu.be/m38ddVi52-Q',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'call-out',
			'label'        => esc_html__('Call Out', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'free',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/call-out/',
			'video_url'    => 'https://youtu.be/1tNppRHvSvQ',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'crypto-currency-card',
			'label'        => esc_html__('Crypto Currency Card', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/crypto-currency-card/',
			'video_url'    => 'https://youtu.be/F13YPkFkLso',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'crypto-currency-table',
			'label'        => esc_html__('Crypto Currency Table', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/crypto-currency-table/',
			'video_url'    => 'https://youtu.be/F13YPkFkLso',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'crypto-currency-grid',
			'label'        => esc_html__('Crypto Currency Grid', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/crypto-currency-grid/',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'crypto-currency-carousel',
			'label'        => esc_html__('Crypto Currency Carousel', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/crypto-currency-carousel/',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'crypto-currency-ticker',
			'label'        => esc_html__('Crypto Currency Ticker', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/crypto-currency-ticker/',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'crypto-currency-chart',
			'label'        => esc_html__('Crypto Currency Chart', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/crypto-currency-chart/',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'crypto-currency-chart-carousel',
			'label'        => esc_html__('Crypto Currency Chart Carousel', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/crypto-currency-chart/',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'crypto-currency-list',
			'label'        => esc_html__('Crypto Currency list', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/crypto-currency-list/',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'dark-mode',
			'label'        => esc_html__('Dark Mode', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'free',
			'content_type' => 'custom',
			'demo_url'     => 'https://elementpack.pro/demo/element/dark-mode',
			'video_url'    => 'https://youtu.be/nuYa-0sWFxU',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'dropbar',
			'label'        => esc_html__('Dropbar', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'free',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/dropbar/',
			'video_url'    => 'https://youtu.be/cXMq8nOCdqk',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'fancy-card',
			'label'        => esc_html__('Fancy Card', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'custom',
			'demo_url'     => 'https://elementpack.pro/demo/element/fancy-card/',
			'video_url'    => 'https://youtu.be/BXdVB1pLfXE',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'honeycombs',
			'label'        => esc_html__('Honeycombs', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'custom',
			'demo_url'     => 'https://elementpack.pro/demo/element/honeycombs/',
			'video_url'    => 'https://youtu.be/iTWXzc329vQ',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'image-accordion',
			'label'        => esc_html__('Image Accordion', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'free',
			'content_type' => 'custom',
			'demo_url'     => 'https://elementpack.pro/demo/element/image-accordion/',
			'video_url'    => 'https://youtu.be/jQWU4kxXJpM',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'member',
			'label'        => esc_html__('Member', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'free',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/member/',
			'video_url'    => 'https://youtu.be/m8_KOHzssPA',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-slider',
			'label'        => esc_html__('Post Slider', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post slider',
			'demo_url'     => 'https://elementpack.pro/demo/element/post-slider',
			'video_url'    => 'https://youtu.be/oPYzWVLPF7A',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-card',
			'label'        => esc_html__('Post Card', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post',
			'demo_url'     => 'https://elementpack.pro/demo/element/post-card/',
			'video_url'    => 'https://youtu.be/VKtQCjnEJvE',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-block',
			'label'        => esc_html__('Post Block', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post',
			'demo_url'     => 'https://elementpack.pro/demo/element/post-block/',
			'video_url'    => 'https://youtu.be/bFEyizMaPmw',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-block-modern',
			'label'        => esc_html__('Post Block Modern', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post',
			'demo_url'     => 'https://elementpack.pro/demo/element/post-block/',
			'video_url'    => 'https://youtu.be/bFEyizMaPmw',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-gallery',
			'label'        => esc_html__('Post Gallery', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post gallery',
			'demo_url'     => 'https://elementpack.pro/demo/element/post-gallery',
			'video_url'    => 'https://youtu.be/iScykjTKlNA',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-grid',
			'label'        => esc_html__('Post Grid', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post',
			'demo_url'     => 'https://elementpack.pro/demo/element/post%20grid/',
			'video_url'    => 'https://youtu.be/z3gWwPIsCkg',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-grid-tab',
			'label'        => esc_html__('Post Grid Tab', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post',
			'demo_url'     => 'https://elementpack.pro/demo/element/post-grid-tab',
			'video_url'    => 'https://youtu.be/kFEL4AGnIv4',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-list',
			'label'        => esc_html__('Post List', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post',
			'demo_url'     => 'https://elementpack.pro/demo/element/post-list/',
			'video_url'    => 'https://youtu.be/5aQTAsLRF0o',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-title',
			'label'        => esc_html__('Post Title', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => "off",
			'widget_type'  => 'pro',
			'content_type' => 'template-builder new',
			'demo_url'     => '',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-comments',
			'label'        => esc_html__('Post Comments', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => "off",
			'widget_type'  => 'pro',
			'content_type' => 'template-builder new',
			'demo_url'     => '',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-content',
			'label'        => esc_html__('Post Content', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => "off",
			'widget_type'  => 'pro',
			'content_type' => 'template-builder new',
			'demo_url'     => '',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-featured-image',
			'label'        => esc_html__('Post Featured Image', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => "off",
			'widget_type'  => 'pro',
			'content_type' => 'template-builder new',
			'demo_url'     => '',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'post-info',
			'label'        => esc_html__('Post Info', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => "off",
			'widget_type'  => 'pro',
			'content_type' => 'template-builder new',
			'demo_url'     => '',
			'video_url'    => '',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'slinky-vertical-menu',
			'label'        => esc_html__('Slinky Vertical Menu', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/slinky-vertical-menu/',
			'video_url'    => 'https://youtu.be/5RE9w-JqKwk',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'single-post',
			'label'        => esc_html__('Single Post', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post',
			'demo_url'     => 'https://elementpack.pro/demo/element/single-post',
			'video_url'    => 'https://youtu.be/32g-F4_Avp4',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'timeline',
			'label'        => esc_html__('Timeline', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post',
			'demo_url'     => 'https://elementpack.pro/demo/element/timeline/',
			'video_url'    => 'https://youtu.be/lp4Zqn6niXU',
		];

		$fields['element_pack_active_modules'][] = [
			'name'         => 'thumb-gallery',
			'label'        => esc_html__('Thumb Gallery', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'widget_type'  => 'pro',
			'content_type' => 'post gallery',
			'demo_url'     => 'https://elementpack.pro/demo/element/thumb-gallery/',
			'video_url'    => 'https://youtu.be/NJ5ZR-9ODus',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-forum-form',
			'label'       => esc_html__('bbPress Forum Form', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-forum-form',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-forum-index',
			'label'       => esc_html__('bbPress Forum Index', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-forum-index',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-single-forum',
			'label'       => esc_html__('bbPress Single Forum', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-single-forum',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-topic-index',
			'label'       => esc_html__('bbPress Topic Index', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-topic-index',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-topic-form',
			'label'       => esc_html__('bbPress Topic Form', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-topic-form',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-single-topic',
			'label'       => esc_html__('bbPress Single Topic', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-single-topic',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-reply-form',
			'label'       => esc_html__('bbPress Reply Form', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-reply-form',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-single-reply',
			'label'       => esc_html__('bbPress Single Reply', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-single-reply',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-topic-tags',
			'label'       => esc_html__('bbPress Topic Tags', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-topic-tags',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-single-tag',
			'label'       => esc_html__('bbPress Single Tag', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-single-tag',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-single-view',
			'label'       => esc_html__('bbPress Single View', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-single-view',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'bbpress-stats',
			'label'       => esc_html__('bbPress Stats', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'off',
			'plugin_name' => 'bbpress',
			'plugin_path' => 'bbpress/bbpress.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/bbpress-stats',
			'video_url'   => '',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'buddypress-friends',
			'label'       => esc_html__('BuddyPress Friends', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'on',
			'plugin_name' => 'buddypress',
			'plugin_path' => 'buddypress/bp-loader.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/buddypress-friends/',
			'video_url'   => 'https://youtu.be/CLV9RCdq09k',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'buddypress-group',
			'label'       => esc_html__('BuddyPress Group', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'on',
			'plugin_name' => 'buddypress',
			'plugin_path' => 'buddypress/bp-loader.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/buddypress-group/',
			'video_url'   => 'https://youtu.be/CccODcBw_9w',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'buddypress-member',
			'label'       => esc_html__('BuddyPress Member', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'on',
			'plugin_name' => 'buddypress',
			'plugin_path' => 'buddypress/bp-loader.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/buddypress-member/',
			'video_url'   => 'https://youtu.be/t6t0M5kGEig',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'charitable-campaigns',
			'label'        => esc_html__('Charitable Campaigns', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'charitable',
			'plugin_path'  => 'charitable/charitable.php',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/charitable-campaigns/',
			'video_url'    => 'https://youtu.be/ugKfZyvSbGA',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'charitable-donations',
			'label'        => esc_html__('Charitable Donations', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'charitable',
			'plugin_path'  => 'charitable/charitable.php',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/charitable-donations/',
			'video_url'    => 'https://youtu.be/C38sbaKx9x0',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'charitable-donors',
			'label'        => esc_html__('Charitable Donors', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'charitable',
			'plugin_path'  => 'charitable/charitable.php',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/charitable-donors/',
			'video_url'    => 'https://youtu.be/ljnbE8JVg7w',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'charitable-donation-form',
			'label'        => esc_html__('Charitable Donation Form', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'charitable',
			'plugin_path'  => 'charitable/charitable.php',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/charitable-donation-form/',
			'video_url'    => 'https://youtu.be/aufVwEUZJhY',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'charitable-stat',
			'label'        => esc_html__('Charitable Stat', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'charitable',
			'plugin_path'  => 'charitable/charitable.php',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/charitable-stat/',
			'video_url'    => 'https://youtu.be/54cw85jmhtg',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'charitable-login',
			'label'        => esc_html__('Charitable Login', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'charitable',
			'plugin_path'  => 'charitable/charitable.php',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/charitable-login/',
			'video_url'    => 'https://youtu.be/c0A90DdfGGM',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'charitable-registration',
			'label'        => esc_html__('Charitable Registration', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'charitable',
			'plugin_path'  => 'charitable/charitable.php',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/charitable-registration/',
			'video_url'    => 'https://youtu.be/N-IMBmjGJsA',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'charitable-profile',
			'label'        => esc_html__('Charitable Profile', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'charitable',
			'plugin_path'  => 'charitable/charitable.php',
			'widget_type'  => 'pro',
			'content_type' => 'others',
			'demo_url'     => 'https://elementpack.pro/demo/element/charitable-profile/',
			'video_url'    => 'https://youtu.be/DD7ZiMpxK-w',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'        => 'download-monitor',
			'label'       => esc_html__('Download Monitor', 'bdthemes-element-pack'),
			'type'        => 'checkbox',
			'default'     => 'on',
			'plugin_name' => 'download-monitor',
			'plugin_path' => 'download-monitor/download-monitor.php',
			'widget_type' => 'pro',
			'demo_url'    => 'https://elementpack.pro/demo/element/download-monitor',
			'video_url'   => 'https://youtu.be/7LaBSh3_G5A',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'everest-forms',
			'label'        => esc_html__('Everest Forms', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'everest-forms',
			'plugin_path'  => 'everest-forms/everest-forms.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/everest-forms/',
			'video_url'    => 'https://youtu.be/jfZhIFpdvcg',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-donation-history',
			'label'        => esc_html__('Give Donation History', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-donation-history/',
			'video_url'    => 'https://youtu.be/n2Cnlubi-E8',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-donor-wall',
			'label'        => esc_html__('Give Donor Wall', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-donor-wall/',
			'video_url'    => 'https://youtu.be/W_RRrE4cmEo',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-form-grid',
			'label'        => esc_html__('Give Form Grid', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-form-grid/',
			'video_url'    => 'https://youtu.be/hq4ElaX0nrE',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-form',
			'label'        => esc_html__('Give Form', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-form/',
			'video_url'    => 'https://youtu.be/k18Mgivy9Mw',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-goal',
			'label'        => esc_html__('Give Goal', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-goal/',
			'video_url'    => 'https://youtu.be/WdRBJL7fOvk',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-login',
			'label'        => esc_html__('Give Login', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-login/',
			'video_url'    => 'https://youtu.be/_mgg8ms12Gw',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-profile-editor',
			'label'        => esc_html__('Give Profile Editor', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-profile-editor/',
			'video_url'    => 'https://youtu.be/oaUUPA7eX2A',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-receipt',
			'label'        => esc_html__('Give Receipt', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-receipt/',
			'video_url'    => 'https://youtu.be/2xoXNi_Hx3k',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-register',
			'label'        => esc_html__('Give Register', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-register/',
			'video_url'    => 'https://youtu.be/4pO-fTXuW3Q',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'give-totals',
			'label'        => esc_html__('Give Totals', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'off',
			'plugin_name'  => 'give',
			'plugin_path'  => 'give/give.php',
			'widget_type'  => 'free',
			'content_type' => 'forms',
			'demo_url'     => 'https://elementpack.pro/demo/element/give-totals/',
			'video_url'    => 'https://youtu.be/fZMljNFdvKs',
		];

		$fields['element_pack_third_party_widget'][] = [
			'name'         => 'layer-slider',
			'label'        => esc_html__('Layer Slider', 'bdthemes-element-pack'),
			'type'         => 'checkbox',
			'default'      => 'on',
			'plugin_name'  => 'LayerSlider',
			'plugin_path'  => 'LayerSlider/layerslider.php',
			'paid'         => 'https://layerslider.com/pricing/',
			'widget_type'  => 'pro',
			'content_type' => 'slider',
			'demo_url'     => 'https://elementpack.pro/demo/element/layer-slider/',
			'video_url'    => 'https://youtu.be/I2xpXLyCkkE',
		];

		if ( (self::is_plugin_installed( 'easy-digital-downloads/easy-digital-downloads.php' ) === true && is_plugin_active('easy-digital-downloads-pro/easy-digital-downloads.php') !== true) ) {
			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-cart',
				'label'       => esc_html__('EDD Cart', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-cart/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-category-grid',
				'label'       => esc_html__('EDD Category Grid', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-category-grid/',
				'video_url'   => 'https://youtu.be/IKpTcUQk3Uk?si=mCG5liqV0jzKheXj',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-category-carousel',
				'label'       => esc_html__('EDD Category Carousel', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-category-carousel/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-checkout',
				'label'       => esc_html__('EDD Checkout', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-checkout/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-login',
				'label'       => esc_html__('EDD Login', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-login/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-mini-cart',
				'label'       => esc_html__('EDD Mini Cart', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-mini-cart/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-product',
				'label'       => esc_html__('EDD Product', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-product/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-product-carousel',
				'label'       => esc_html__('EDD Product Carousel', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-product-carousel/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-product-reviews',
				'label'       => esc_html__('EDD Product Reviews', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-product-reviews/',
				'video_url'   => 'https://youtu.be/drn_yEGoC_E?si=Qh5D4urdf-biAinR',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-product-review-carousel',
				'label'       => esc_html__('EDD Product Review Carousel', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-product-review-carousel/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-profile-editor',
				'label'       => esc_html__('EDD Profile Editor', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'on',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-profile-editor/',
				'video_url'   => 'https://youtu.be/f2v7EFla94c',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-purchase-history',
				'label'       => esc_html__('EDD Purchase History', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'on',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-purchase-history/',
				'video_url'   => 'https://youtu.be/oUppcuQTB7M',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-register',
				'label'       => esc_html__('EDD Register', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-register/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-tabs',
				'label'       => esc_html__('EDD Tabs', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-tabs/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-download-history',
				'label'       => esc_html__('EDD History', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'on',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-download-history/',
				'video_url'   => 'https://youtu.be/taM7whXxmNY',
			];

		}

		if ( ! ( (self::is_plugin_installed( 'easy-digital-downloads/easy-digital-downloads.php' ) === true && is_plugin_active('easy-digital-downloads-pro/easy-digital-downloads.php') !== true) ) ) {
			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-cart',
				'label'       => esc_html__('EDD Cart', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-cart/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-category-grid',
				'label'       => esc_html__('EDD Category Grid', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-category-grid/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-category-carousel',
				'label'       => esc_html__('EDD Category Carousel', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-category-carousel/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-checkout',
				'label'       => esc_html__('EDD Checkout', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-checkout/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-login',
				'label'       => esc_html__('EDD Login', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-login/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-mini-cart',
				'label'       => esc_html__('EDD Mini Cart', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-mini-cart/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-product',
				'label'       => esc_html__('EDD Product', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-product/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-product-carousel',
				'label'       => esc_html__('EDD Product Carousel', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-product-carousel/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-product-reviews',
				'label'       => esc_html__('EDD Product Reviews', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-product-reviews/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-product-review-carousel',
				'label'       => esc_html__('EDD Product Review Carousel', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-product-review-carousel/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-profile-editor',
				'label'       => esc_html__('EDD Profile Editor', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'on',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-profile-editor/',
				'video_url'   => 'https://youtu.be/f2v7EFla94c',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-purchase-history',
				'label'       => esc_html__('EDD Purchase History', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'on',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-purchase-history/',
				'video_url'   => 'https://youtu.be/oUppcuQTB7M',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-register',
				'label'       => esc_html__('EDD Register', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-register/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-tabs',
				'label'       => esc_html__('EDD Tabs', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'off',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-tabs/',
				'video_url'   => '',
			];

			$fields['element_pack_third_party_widget'][] = [
				'name'        => 'edd-download-history',
				'label'       => esc_html__('EDD History', 'bdthemes-element-pack'),
				'type'        => 'checkbox',
				'default'     => 'on',
				'plugin_name' => 'easy-digital-downloads',
				'plugin_path' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
				'widget_type' => 'pro',
				'demo_url'    => 'https://elementpack.pro/demo/element/edd-download-history/',
				'video_url'   => 'https://youtu.be/taM7whXxmNY',
			];

		}

		return $fields;
	}
}
