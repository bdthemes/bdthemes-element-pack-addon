<?php

namespace ElementPackAddon\Modules\EddCategoryGrid\Widgets;

use ElementPack\Base\Module_Base;
use Elementor\Controls_Manager;
use ElementPack\Traits\Global_Controls_Functions;
use ElementPack\Traits\Global_Terms_Query_Controls;

use ElementPack\Traits\Global_Widget_Controls;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDD_Category_Grid extends Module_Base {
	use Global_Widget_Controls;

	use Global_Terms_Query_Controls;
	use Global_Controls_Functions;

	public function get_name() {
		return 'bdt-edd-category-grid';
	}

	public function get_title() {
		return BDTEP . esc_html__( 'EDD Category Grid', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'bdt-wi-edd-category-grid bdt-new';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_keywords() {
		return [ 'easy', 'digital', 'category', 'downloads', 'eshop', 'estore', 'profile', 'editor' ];
	}

	public function get_style_depends() {
		if ( $this->ep_is_edit_mode() ) {
			return [ 'ep-addon-styles' ];
		}
		return [ 'ep-edd-category-grid' ];
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/IKpTcUQk3Uk?si=mCG5liqV0jzKheXj';
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		$this->register_edd_category_layout_controls( 'grid' );
		$this->render_terms_query_controls( 'download_category' );
		$this->register_edd_category_style_controls( 'grid' );
	}

	public function render_query() {
		return $this->render_edd_category_query();
	}

	public function render_image() {
		$this->render_edd_category_image( 'grid' );
	}

	public function render_loop_item() {
		$this->render_edd_category_loop_item( 'grid' );
	}

	public function render() {
		$this->render_edd_category_grid();
	}
}
