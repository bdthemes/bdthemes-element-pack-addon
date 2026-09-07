<?php

namespace ElementPackAddon\Modules\EddCategoryCarousel\Widgets;

use ElementPack\Base\Module_Base;
use Elementor\Controls_Manager;
use ElementPack\Traits\Global_Controls_Functions;
use ElementPack\Traits\Global_Swiper_Controls;
use ElementPack\Traits\Global_Terms_Query_Controls;

use ElementPack\Traits\Global_Widget_Controls;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDD_Category_Carousel extends Module_Base {
	use Global_Widget_Controls;

	use Global_Swiper_Controls;
	use Global_Terms_Query_Controls;
	use Global_Controls_Functions;

	public function get_name() {
		return 'bdt-edd-category-carousel';
	}

	public function get_title() {
		return BDTEP . esc_html__( 'EDD Category Carousel', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'bdt-wi-edd-category-carousel bdt-new';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_keywords() {
		return [ 'easy', 'digital', 'category', 'downloads', 'eshop', 'estore', 'profile', 'editor' ];
	}

	public function get_style_depends() {
		if ( $this->ep_is_edit_mode() ) {
			return [ 'swiper', 'ep-addon-styles' ];
		}
		return [ 'swiper', 'ep-font', 'ep-edd-category-carousel' ];
	}

	public function get_script_depends() {
		if ( $this->ep_is_edit_mode() ) {
			return [ 'swiper', 'ep-addon-scripts' ];
		}
		return [ 'swiper', 'ep-edd-category-carousel' ];
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/z6MSJtvbxPQ';
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		$this->register_edd_category_layout_controls( 'carousel' );
		$this->render_terms_query_controls( 'download_category' );
		$this->register_edd_category_carousel_navigation_controls();
		$this->register_edd_category_style_controls( 'carousel' );
		$this->register_edd_category_carousel_navigation_style_controls();
	}

	public function render_query() {
		return $this->render_edd_category_query();
	}

	public function render_image() {
		$this->render_edd_category_image( 'carousel' );
	}

	public function render_loop_item() {
		$this->render_edd_category_loop_item( 'carousel' );
	}

	public function render_header() {
		$this->render_edd_category_carousel_header();
	}

	public function render() {
		$this->render_edd_category_carousel();
	}
}
