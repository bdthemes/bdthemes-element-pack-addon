<?php
/**
 * WPML string-translation nodes for the widgets shipped by Element Pack Addon.
 * Mirrors the entries Element Pack Pro used to carry in
 * includes/class-elements-wpml-compatibility.php.
 */

namespace ElementPackAddon\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpml {

	public function init() {
		if ( defined( 'WPML_ST_VERSION' ) ) {
			add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'add_translatable_nodes' ], 20 );
		}
	}

	/**
	 * Repeater integration classes extend Element Pack Pro's WPML_Module_With_Items.
	 */
	private function load_modules() {
		if ( ! class_exists( '\ElementPack\Includes\WPML_Module_With_Items' ) ) {
			$base = BDTEP_INC_PATH . 'compatiblity/wpml/wpml-module-with-items.php';
			if ( file_exists( $base ) ) {
				require_once $base;
			} else {
				return false;
			}
		}
		foreach ( glob( BDTEPA_INC_PATH . 'wpml/class-wpml-element-pack-*.php' ) as $file ) {
			require_once $file;
		}

		return true;
	}

	/**
	 * @param array $nodes_to_translate
	 * @return array
	 */
	public function add_translatable_nodes( $nodes_to_translate ) {
		if ( ! $this->load_modules() ) {
			return $nodes_to_translate;
		}

		$nodes_to_translate[ 'bdt-post-info' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-info' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Post_Info',
		];

		$nodes_to_translate[ 'bdt-age-gate' ] = [
			'conditions' => [ 'widgetType' => 'bdt-age-gate' ],
			'fields'     => [
				[
					'field'       => 'form_placeholder',
					'type'        => esc_html__( 'Form Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text_yes',
					'type'        => esc_html__( 'Button Text (Yes)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text_no',
					'type'        => esc_html__( 'Button Text (No)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'age_invalid_msg',
					'type'        => esc_html__( 'Age Invalid Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'header',
					'type'        => esc_html__( 'Header Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'content',
					'type'        => esc_html__( 'Modal Content', 'bdthemes-element-pack' ),
					'editor_type' => 'VISUAL',
				],
				[
					'field'       => 'footer',
					'type'        => esc_html__( 'Footer Text', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				'redirect_link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Redirect URL', 'bdthemes-element-pack' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$nodes_to_translate[ 'bdt-call-out' ] = [
			'conditions' => [ 'widgetType' => 'bdt-call-out' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description',
					'type'        => esc_html__( 'Description', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-download-monitor' ] = [
			'conditions' => [ 'widgetType' => 'bdt-download-monitor' ],
			'fields'     => [
				[
					'field'       => 'alt_title',
					'type'        => esc_html__( 'Alternative Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-download-monitor' ] = [
			'conditions' => [ 'widgetType' => 'bdt-download-monitor' ],
			'fields'     => [
				[
					'field'       => 'alt_title',
					'type'        => esc_html__( 'Alternative Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-dropbar' ] = [
			'conditions' => [ 'widgetType' => 'bdt-dropbar' ],
			'fields'     => [
				[
					'field'       => 'content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'VISUAL',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-fancy-card' ] = [
			'conditions' => [ 'widgetType' => 'bdt-fancy-card' ],
			'fields'     => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-edd-register' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-register' ],
			'fields'     => [
				'form_register_redirect_url' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Redirect URL', 'bdthemes-element-pack' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$nodes_to_translate[ 'bdt-honeycombs' ] = [
			'conditions' => [ 'widgetType' => 'bdt-honeycombs' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Honeycombs',
		];

		$nodes_to_translate[ 'bdt-image-accordion' ] = [
			'conditions' => [ 'widgetType' => 'bdt-image-accordion' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Image_Accordion',
		];

		$nodes_to_translate[ 'bdt-member' ] = [
			'conditions' => [ 'widgetType' => 'bdt-member' ],
			'fields'     => [
				[
					'field'       => 'name',
					'type'        => esc_html__( 'Member Name', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Member Description', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'role',
					'type'        => esc_html__( 'Member Role', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],

			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Team_Member',
		];

		$nodes_to_translate[ 'bdt-post-block' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-block' ],
			'fields'     => [
				[
					'field'       => 'read_more_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-block-modern' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-block-modern' ],
			'fields'     => [
				[
					'field'       => 'read_more_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-card' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-card' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-grid' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-grid' ],
			'fields'     => [
				[
					'field'       => 'tags_string',
					'type'        => esc_html__( 'Tags String', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-grid-tab' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-grid-tab' ],
			'fields'     => [
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-slider' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-slider' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-thumb-gallery' ] = [
			'conditions' => [ 'widgetType' => 'bdt-thumb-gallery' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Button Link', 'bdthemes-element-pack' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$nodes_to_translate[ 'bdt-timeline' ] = [
			'conditions' => [ 'widgetType' => 'bdt-timeline' ],
			'fields'     => [
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Timeline',
		];

		$nodes_to_translate[ 'bdt-dark-mode' ] = [
			'conditions' => [ 'widgetType' => 'bdt-dark-mode' ],
			'fields'     => [
				[
					'field'       => 'ignore_element',
					'type'        => __( 'Ignore Elements', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];

		$nodes_to_translate[ 'bdt-edd-cart' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-cart' ],
			'fields'     => [
				[
					'field'       => 'cart_action_button_text',
					'type'        => __( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'bdt-edd-mini-cart' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-mini-cart' ],
			'fields'     => [
				[
					'field'       => 'custom_widget_cart_title',
					'type'        => __( 'Cart Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'custom_content_before',
					'type'        => __( 'Custom Content Before', 'bdthemes-element-pack' ),
					'editor_type' => 'VISUAL'
				],
				[
					'field'       => 'custom_content_after',
					'type'        => __( 'Custom Content After', 'bdthemes-element-pack' ),
					'editor_type' => 'VISUAL'
				],
			],
		];

		$nodes_to_translate[ 'bdt-edd-product' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-product' ],
			'fields'     => [
				[
					'field'       => 'filter_custom_text_all',
					'type'        => __( 'Custom Text (All)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'filter_custom_text_filter',
					'type'        => __( 'Custom Text (Filter)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'bdt-give-receipt' ] = [
			'conditions' => [ 'widgetType' => 'bdt-give-receipt' ],
			'fields'     => [
				[
					'field'       => 'error',
					'type'        => __( 'Error Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'success',
					'type'        => __( 'Success Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];

		$nodes_to_translate[ 'bdt-give-totals' ] = [
			'conditions' => [ 'widgetType' => 'bdt-give-totals' ],
			'fields'     => [
				[
					'field'       => 'total_goal',
					'type'        => __( 'Goal Amount', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'message',
					'type'        => __( 'Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'link_text',
					'type'        => __( 'Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				'link' => [
					'field'       => 'url',
					'type'        => __( 'Link URL', 'bdthemes-element-pack' ),
					'editor_type' => 'LINK'
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-gallery' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-gallery' ],
			'fields'     => [
				[
					'field'       => 'post_link_text',
					'type'        => esc_html__( 'Details Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'lightbox_link_text',
					'type'        => esc_html__( 'Lightbox Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'filter_custom_text_all',
					'type'        => esc_html__( 'Custom Text (All)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'filter_custom_text_filter',
					'type'        => esc_html__( 'Custom Text (Filter)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-list' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-list' ],
			'fields'     => [
				[
					'field'       => 'header_title_text',
					'type'        => esc_html__( 'Filter Bar Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-slinky-vertical-menu' ] = [
			'conditions' => [ 'widgetType' => 'bdt-slinky-vertical-menu' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Slinky_Vertical_Menu',
		];

		$nodes_to_translate[ 'bdt-give-form' ] = [
			'conditions' => [ 'widgetType' => 'bdt-give-form' ],
			'fields'     => [
				[
					'field'       => 'continue_button_title',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-give-donor-wall' ] = [
			'conditions' => [ 'widgetType' => 'bdt-give-donor-wall' ],
			'fields'     => [
				[
					'field'       => 'loadmore_text',
					'type'        => esc_html__( 'Load More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-edd-tabs' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-tabs' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_EDD_Tabs',
		];

		$nodes_to_translate[ 'bdt-edd-checkout' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-checkout' ],
			'fields'     => [
				[
					'field'       => 'checkout_action_button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-charitable-registration' ] = [
			'conditions' => [ 'widgetType' => 'bdt-charitable-registration' ],
			'fields'     => [
				[
					'field'       => 'logged_in_message',
					'type'        => esc_html__( 'Logged In Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'registration_link_text',
					'type'        => esc_html__( 'Registration Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				'redirect' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Redirect Link', 'bdthemes-element-pack' ),
					'editor_type' => 'LINK',
				],
			],
		];

		$nodes_to_translate[ 'bdt-charitable-login' ] = [
			'conditions' => [ 'widgetType' => 'bdt-charitable-login' ],
			'fields'     => [
				[
					'field'       => 'logged_in_message',
					'type'        => esc_html__( 'Logged In Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'registration_link_text',
					'type'        => esc_html__( 'Registration Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		return $nodes_to_translate;
	}
}
