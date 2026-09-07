<?php
namespace ElementPackAddon\Modules\PostBlock\Skins;

use Elementor\Skin_Base as Elementor_Skin_Base;
use ElementPack\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skin_Trinity extends Elementor_Skin_Base {

	public function get_id() {
		return 'trinity';
	}

	public function get_title() {
		return esc_html__( 'Trinity', 'bdthemes-element-pack' );
	}

	public function render() {
		$settings = $this->parent->get_settings_for_display();
		$id       = uniqid( 'bdtpbm_' );

		$this->parent->query_posts( $settings['posts_per_page'] );
		$wp_query = $this->parent->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		if ( $wp_query->have_posts() ) :

			$this->parent->add_render_attribute(
				[
					'post-block' => [
						'id'    => esc_attr( $id ),
						'class' => [
							'bdt-post-block',
							'skin-trinity',
						],
					],
				]
			);

			?>
			<div <?php $this->parent->print_render_attribute_string( 'post-block' ); ?>>

				<div class="bdt-post-block-items bdt-child-width-1-<?php echo esc_attr( $settings['featured_item'] ); ?>@m bdt-grid-<?php echo esc_attr( $settings['trinity_column_gap'] ); ?>" data-bdt-grid>
					<?php
					while ( $wp_query->have_posts() ) :
						$wp_query->the_post();

						$placeholder_image_src = Utils::get_placeholder_image_src();
						$image_src             = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );

						if ( ! $image_src ) {
							$image_src = $placeholder_image_src;
						} else {
							$image_src = $image_src[0];
						}

						?>
						<div class="bdt-post-block-item featured-part">
							<div class="bdt-post-block-thumbnail-wrap bdt-position-relative">
								<div class="bdt-post-block-thumbnail">
									<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
										<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
									</a>
								</div>
								<div class="bdt-overlay-primary bdt-position-cover"></div>
								<div class="bdt-post-block-desc bdt-text-center bdt-position-center bdt-position-medium bdt-position-z-index">
									<?php if ( $settings['featured_show_tag'] === 'yes' ) : ?>
										<div class="bdt-post-block-tag-wrap">
											<?php
											$tags_list = get_the_tag_list( '<span class="bdt-background-primary">', '</span> <span class="bdt-background-primary">', '</span>' );
											if ( $tags_list ) {
												echo wp_kses_post( $tags_list );
											}
											?>
										</div>
									<?php endif; ?>

									<?php if ( $settings['featured_show_title'] === 'yes' ) : ?>
										<?php
										$title_tag = Utils::get_valid_html_tag( $settings['featured_title_size'] );
										?>
										<<?php echo esc_attr( $title_tag ); ?> class="bdt-post-block-title bdt-margin-small-top">
											<a href="<?php echo esc_url( get_permalink() ); ?>" class="bdt-post-block-link" title="<?php echo esc_attr( get_the_title() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
										</<?php echo esc_attr( $title_tag ); ?>>
									<?php endif; ?>

									<?php if ( $settings['featured_show_category'] === 'yes' || $settings['featured_show_date'] === 'yes' ) : ?>
										<div class="bdt-post-block-meta bdt-flex-center bdt-subnav bdt-flex-middle">
											<?php if ( $settings['featured_show_category'] === 'yes' ) : ?>
												<?php echo '<span>' . wp_kses_post( get_the_category_list( ', ' ) ) . '</span>'; ?>
											<?php endif; ?>

											<?php $this->parent->render_featured_date( $settings ); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>

					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
			<?php
		endif;
	}
}
