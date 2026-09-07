<?php

namespace ElementPackAddon\Modules\Timeline\Skins;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property \ElementPackAddon\Modules\Timeline\Widgets\Timeline $parent
 */
class Skin_Olivier extends Elementor_Skin_Base {

	public function get_id() {
		return 'bdt-olivier';
	}

	public function get_title() {
		return __( 'Olivier', 'bdthemes-element-pack' );
	}

	public function render_script( $settings ) {
		$this->parent->add_render_attribute( 'timeline', 'class', [ 'bdt-timeline', 'bdt-timeline-skin-olivier' ] );
		$this->parent->add_render_attribute( 'timeline', 'data-visible_items', $settings['visible_items'] );
	}

	public function render_custom( $settings ) {

		$timeline_items = $settings['timeline_items'] ?? [];
		if ( empty( $timeline_items ) ) {
			return;
		}
		
		?>
		<div <?php $this->parent->print_render_attribute_string( 'timeline' ); ?>>
			<div class="bdt-timeline-wrapper">
				<div class="bdt-timeline-items">
					<?php foreach ( $timeline_items as $item ) : ?>
						<div class="bdt-timeline-item">
							<div class="bdt-timeline-content">
								<?php $this->parent->render_item( '', '', $settings, $item ); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_post( $settings ) {
		$posts_per_page = isset( $settings['posts_per_page'] ) ? (int) $settings['posts_per_page'] : 4;
		$wp_query       = $this->parent->query_posts( $posts_per_page );
		if ( ! $wp_query->found_posts ) {
			return;
		}
		?>
		<div <?php $this->parent->print_render_attribute_string( 'timeline' ); ?>>
			<div class="bdt-timeline-wrapper">
				<div class="bdt-timeline-items">
					<?php while ( $wp_query->have_posts() ) : ?>
						<?php $wp_query->the_post(); ?>
						<div class="bdt-timeline-item">
							<div class="bdt-timeline-content">
								<?php $this->parent->render_item( '', '', $settings, [] ); ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
		<?php
		wp_reset_postdata();
	}

	public function render() {

		$settings = $this->parent->get_settings_for_display();
		$this->render_script( $settings );
		$source = $settings['timeline_source'] ?? 'post';

		if ( $source === 'post' ) {
			$this->render_post( $settings );
		} elseif ( $source === 'custom' ) {
			$this->render_custom( $settings );
		}
	}
}