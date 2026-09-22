<?php
namespace ElementPackAddon\Modules\Carousel\Skins;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Vertical extends Elementor_Skin_Base {
	public function get_id() {
		return 'bdt-vertical';
	}

	public function get_title() {
		return __( 'Vertical', 'bdthemes-element-pack' );
	}

	public function render() {
		$settings = $this->parent->get_settings_for_display();

	    $posts_per_page = $settings['posts_per_page'];
	        
		$this->parent->query_posts($posts_per_page);
		
		$wp_query = $this->parent->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$this->parent->get_posts_tags();

		$this->parent->render_header($settings, "vertical");

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_post($settings);
		}

		$this->parent->render_footer();

		wp_reset_postdata();
	}

	public function render_post($settings) {

		$this->parent->render_loop_header($settings);

			?>
			<div class="bdt-ep-carousel-layout-vertical">
				<div class="bdt-grid bdt-grid-small" bdt-grid>
					<div class="bdt-width-1-2@m">

		<?php $this->parent->render_thumbnail($settings); ?>
		
			</div>
			<div class="bdt-width-expand">
			<?php

		$this->parent->render_overlay_header($settings);
		$this->parent->render_title($settings);
		$this->parent->render_meta_data($settings);
		$this->parent->render_excerpt($settings);
		$this->parent->render_readmore($settings);
		$this->parent->render_overlay_footer();

					?>
					</div>
				</div>
			</div>
			<?php

		$this->parent->render_post_footer();
	}
}

