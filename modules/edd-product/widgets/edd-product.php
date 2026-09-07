<?php

namespace ElementPackAddon\Modules\EddProduct\Widgets;

use ElementPack\Base\Module_Base;
use Elementor\Controls_Manager;
use ElementPack\Traits\Global_Widget_Controls;
use ElementPack\Traits\Global_Controls_Functions;
use ElementPack\Includes\Controls\GroupQuery\Group_Control_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDD_Product extends Module_Base {
	use Global_Widget_Controls;
	use Global_Controls_Functions;
	use Group_Control_Query;

	/** @var \WP_Query */
	private $_query = null;

	public function get_name() {
		return 'bdt-edd-product';
	}

	public function get_title() {
		return esc_html__( 'EDD Product', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'bdt-wi-edd-product bdt-new';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_keywords() {
		return [ 'product', 'easy', 'digital', 'download', 'edd', 'grid', 'ecommerce' ];
	}

	public function get_style_depends() {
		if ( $this->ep_is_edit_mode() ) {
			return [ 'ep-addon-styles' ];
		}
		return [ 'element-pack-font', 'ep-edd-product' ];
	}

	public function get_query() {
		return $this->_query;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		$this->register_edd_product_grid_layout_controls();
		$this->register_edd_product_query_controls();
		$this->register_edd_product_filter_controls();
		$this->register_edd_product_additional_controls();
		$this->register_edd_product_style_item_controls( 'grid' );
		$this->register_edd_product_style_category_controls( 'grid' );
		$this->register_edd_product_style_title_controls( 'grid' );
		$this->register_edd_product_style_price_controls( 'grid' );
		$this->register_edd_product_style_action_btn_controls( 'grid' );
		$this->register_style_controls_filter();
		$this->register_edd_product_style_pagination_controls();
	}

	public function render_controls_layout() {
		$this->register_edd_product_grid_layout_controls();
	}

	public function render_controls_query() {
		$this->register_edd_product_query_controls();
	}

	public function render_controls_filter() {
		$this->register_edd_product_filter_controls();
	}

	public function render_controls_additional() {
		$this->register_edd_product_additional_controls();
	}

	public function render_style_controls_item() {
		$this->register_edd_product_style_item_controls( 'grid' );
	}

	public function render_style_controls_category() {
		$this->register_edd_product_style_category_controls( 'grid' );
	}

	public function render_style_controls_title() {
		$this->register_edd_product_style_title_controls( 'grid' );
	}

	public function render_style_controls_price() {
		$this->register_edd_product_style_price_controls( 'grid' );
	}

	public function render_style_controls_action_btn() {
		$this->register_edd_product_style_action_btn_controls( 'grid' );
	}

	public function render_style_controls_pagination() {
		$this->register_edd_product_style_pagination_controls();
	}

	public function render_filter_menu() {
		$this->render_edd_product_filter_menu();
	}

	public function render_header() {
		$this->render_edd_product_grid_header();
	}

	public function render_footer() {
		$this->render_edd_product_grid_footer();
	}

	public function render_loop_item() {
		$this->render_edd_product_grid_loop();
	}

	public function render() {
		$this->render_edd_product_grid();
	}

	public function query_product() {
		$this->query_edd_product();
	}
}
