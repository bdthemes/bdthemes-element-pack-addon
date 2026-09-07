<?php

namespace ElementPackAddon\Modules\EddProductCarousel\Widgets;

use ElementPack\Base\Module_Base;
use Elementor\Controls_Manager;
use ElementPack\Traits\Global_Widget_Controls;
use ElementPack\Traits\Global_Swiper_Controls;
use ElementPack\Traits\Global_Controls_Functions;
use ElementPack\Includes\Controls\GroupQuery\Group_Control_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDD_Product_Carousel extends Module_Base {
	use Global_Widget_Controls;
	use Global_Swiper_Controls;
	use Global_Controls_Functions;
	use Group_Control_Query;

	/** @var \WP_Query */
	private $_query = null;

	public function get_name() {
		return 'bdt-edd-product-carousel';
	}

	public function get_title() {
		return BDTEP . esc_html__( 'EDD Product Carousel', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'bdt-wi-edd-product-carousel bdt-new';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_keywords() {
		return [ 'product', 'easy', 'digital', 'download', 'edd', 'grid', 'ecommerce' ];
	}

	public function get_style_depends() {
		if ( $this->ep_is_edit_mode() ) {
			return [ 'swiper', 'ep-addon-styles' ];
		}
		return [ 'swiper', 'ep-font', 'ep-edd-product-carousel' ];
	}

	public function get_script_depends() {
		if ( $this->ep_is_edit_mode() ) {
			return [ 'swiper', 'ep-addon-scripts' ];
		}
		return [ 'swiper', 'ep-edd-product-carousel' ];
	}

	public function get_query() {
		return $this->_query;
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/drn_yEGoC_E?si=Qh5D4urdf-biAinR';
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		$this->register_edd_product_carousel_layout_controls();
		$this->register_edd_product_query_controls();
		$this->register_edd_product_style_item_controls( 'carousel' );
		$this->register_edd_product_style_category_controls( 'carousel' );
		$this->register_edd_product_style_title_controls( 'carousel' );
		$this->register_edd_product_style_price_controls( 'carousel' );
		$this->register_edd_product_style_action_btn_controls( 'carousel' );
		$this->register_edd_product_carousel_navigation_controls();
	}

	public function render_controls_layout() {
		$this->register_edd_product_carousel_layout_controls();
	}

	public function render_controls_query() {
		$this->register_edd_product_query_controls();
	}

	public function render_swiper_navigation() {
		$this->register_edd_product_carousel_navigation_controls();
	}

	public function render_style_controls_item() {
		$this->register_edd_product_style_item_controls( 'carousel' );
	}

	public function render_style_controls_category() {
		$this->register_edd_product_style_category_controls( 'carousel' );
	}

	public function render_style_controls_title() {
		$this->register_edd_product_style_title_controls( 'carousel' );
	}

	public function render_style_controls_price() {
		$this->register_edd_product_style_price_controls( 'carousel' );
	}

	public function render_style_controls_action_btn() {
		$this->register_edd_product_style_action_btn_controls( 'carousel' );
	}

	public function render_header() {
		$this->render_edd_product_carousel_header();
	}

	public function render_loop_item() {
		$this->render_edd_product_carousel_loop();
	}

	public function render() {
		$this->render_edd_product_carousel();
	}

	public function query_product() {
		$this->query_edd_product();
	}
}
