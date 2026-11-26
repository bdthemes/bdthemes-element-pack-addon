<?php

namespace ElementPackAddon\Modules\PostGrid;

use ElementPack\Base\Element_Pack_Module_Base;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Module extends Element_Pack_Module_Base {
	public function __construct() {
		parent::__construct();


		add_action('wp_ajax_nopriv_ep_loadmore_posts', [$this, 'callback_ajax_loadmore_posts']);
		add_action('wp_ajax_ep_loadmore_posts', [$this, 'callback_ajax_loadmore_posts']);
	}

	public function get_name() {
		return 'post-grid';
	}

	public function get_widgets() {

		$widgets = [
			'Post_Grid',
		];

		return $widgets;
	}


	/**
	 * Post types the load-more request may list: public ones that are not
	 * excluded from search, without media and saved templates / builder content.
	 *
	 * @return string[]
	 */
	private static function get_loadmore_post_types() {
		return array_values( array_diff(
			get_post_types( [ 'public' => true, 'exclude_from_search' => false ] ),
			[ 'attachment', 'elementor_library', 'bdt-template-builder', 'ep_megamenu_content', 'e-floating-buttons', 'bdt-custom-template' ]
		) );
	}

	public function callback_ajax_loadmore_posts() {

		if (!isset($_POST['settings']['nonce']) || !wp_verify_nonce( sanitize_key( wp_unslash( $_POST['settings']['nonce'] ) ), 'bdt-post-grid-load-more-nonce')) {
			exit;
		}

		// Visitors call this (the nonce is printed for everyone), and Pro's query
		// helper copies settings[posts_source] into post_type, so the request could
		// list the titles of any post type (coupon codes, form messages). Accept
		// only a public, searchable type or one of the helper's special sources,
		// whatever the installed Pro does.
		$allowed_types = self::get_loadmore_post_types();
		// Checked exactly as the helper reads it, and only compared against the allowlist.
		$posts_source  = isset( $_POST['settings']['posts_source'] ) ? $_POST['settings']['posts_source'] : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		if ( ! is_string( $posts_source ) || ! in_array( $posts_source, array_merge( $allowed_types, [ 'manual_selection', 'current_query', '_related_post_type' ] ), true ) ) {
			wp_send_json( [ 'markup' => '' ] );
		}

		// 'manual_selection' makes the helper query post_type 'any', which WP_Query
		// expands to every type not excluded from search, public or not.
		$limit_any = static function ( $query ) use ( $allowed_types ) {
			if ( 'any' === $query->get( 'post_type' ) ) {
				$query->set( 'post_type', $allowed_types );
			}
		};

		add_action( 'pre_get_posts', $limit_any );
		$ajaxposts = element_pack_ajax_load_query_args();
		remove_action( 'pre_get_posts', $limit_any );
		$markup    = '';
		if ($ajaxposts->have_posts()) {
			$item_index = 1;
			while ($ajaxposts->have_posts()) :
				$ajaxposts->the_post();
				// $title                 = wp_trim_words(get_the_title(), $title_text_limit, '...');
				$post_link             = get_permalink();
				$image_src             = wp_get_attachment_image_url(get_post_thumbnail_id(), 'full');
				// $category              = upk_get_category($post_type);
				$author_url            = get_author_posts_url(get_the_author_meta('ID'));
				$author_name           = get_the_author();
				$date                  = get_the_date();
				$placeholder_image_src = \Elementor\Utils::get_placeholder_image_src();
				$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
				if (!$image_src) {
					$image_src = $placeholder_image_src;
				} else {
					$image_src = $image_src[0];
				}




				$markup .= '<div class="nnn bdt-width-1-3@m bdt-width-1-3@s bdt-width-1-1 bdt-secondary bdt-grid-margin">';
				$markup .= '<div class="bdt-post-grid-item bdt-transition-toggle bdt-position-relative">';
				//image wrap
				$markup .= '<div class="bdt-post-grid-img-wrap bdt-overflow-hidden">';
				$markup .= '<a href="' . esc_url(get_permalink()) . '" class="bdt-transition-scale-up bdt-background-cover bdt-transition-opaque bdt-flex" title="' . esc_attr(get_the_title()) . '" style="background-image: url(' . esc_url($image_src) . ')">';
				$markup .= '</a>';
				$markup .= '</div>';

				$markup .= '<div class="bdt-custom-overlay bdt-position-cover"></div>';

				$markup .= '<div class="bdt-post-grid-desc bdt-position-medium bdt-position-bottom-left">';
				$markup .= '<h2 class="bdt-post-grid-title">';
				$markup .= '<a href="' . esc_url(get_permalink()) . '" class="bdt-post-grid-link" title="' . esc_attr(get_the_title()) . '">';
				$markup .= esc_html(get_the_title());
				$markup .= '</a>';
				$markup .= '</h2>';
				$markup .= '<div class="bdt-post-grid-meta">';
				$markup .= '<span class="bdt-post-grid-meta-item bdt-post-grid-meta-author">';
				$markup .= '<a href="' . esc_url($author_url) . '" class="bdt-post-grid-meta-link">';
				$markup .= esc_html($author_name);
				$markup .= '</a>';
				$markup .= '</span>';
				$markup .= '<span class="bdt-post-grid-meta-item bdt-post-grid-meta-date">';
				$markup .= '<a href="' . esc_url(get_permalink()) . '" class="bdt-post-grid-meta-link">';
				$markup .= esc_html($date);
				$markup .= '</a>';
				$markup .= '</span>';
				$markup .= '</div>';
				/**
				 * Readmore Button
				 */
				if (isset($_POST['settings']['show_readmore']) && $_POST['settings']['show_readmore'] == 'yes') :
					$readMore = isset($_POST['readMore']) ? sanitize_text_field( wp_unslash( $_POST['readMore'] ) ) : '';

					$animation = isset($readmore_hover_animation) ? ' elementor-animation-' . $readmore_hover_animation : '';

					if (!isset($settings['icon']) && !Icons_Manager::is_migration_allowed()) {
						$settings['icon'] = 'fas fa-arrow-right';
					}

					$migrated  = isset($settings['__fa4_migrated'][$readMore['post_grid_icon']]);
					$is_new    = empty($settings['icon']) && Icons_Manager::is_migration_allowed();

					$markup .= '<a href="' . $post_link . '" class="bdt-post-grid-readmore bdt-display-inline-block ' . $animation . '">';
					$markup .= '<span class="bdt-button-text">' . $readMore['readmore_text'] . '</span>';
					if ($readMore['post_grid_icon']['value']) :
						$markup .= '<span class="bdt-button-icon-align-' . $readMore['readmore_icon_align'] . '">';
						if ($is_new || $migrated) :
							ob_start();
							Icons_Manager::render_icon($readMore['post_grid_icon'], ['aria-hidden' => 'true', 'class' => 'fa-fw']);
							$markup .= ob_get_clean();
						else :
							$markup .= '<i class="' . $settings['icon'] . '" aria-hidden="true"></i>';
						endif;
						$markup .= '</span>';
					endif;
					$markup .=	'</a>';
				endif;
				/**
				 * Readmore Button End
				 */

				$markup .= '</div>';
				$markup .= '<div class="bdt-post-grid-category bdt-position-small bdt-position-top-left">';
				$markup .= element_pack_get_category_list( isset($_POST['settings']['posts_source']) ? sanitize_text_field( wp_unslash( $_POST['settings']['posts_source'] ) ) : '' );
				$markup .= '</div>';

				$markup .= '</div>';
				$markup .= '</div>';
				$item_index++;
			endwhile;
		}

		wp_reset_postdata();
		$result = [
			'markup' => $markup,
		];
		wp_send_json($result);
		exit;
	}
}
