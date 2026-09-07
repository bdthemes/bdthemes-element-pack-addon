<?php

namespace ElementPackAddon\Modules\PostContent\Widgets;

use ElementPack\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use ElementPack\Traits\Global_Widget_Controls;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Content extends Module_Base {

	use Global_Widget_Controls;

	public function get_name() {
		return 'bdt-post-content';
	}

	public function get_title() {
		return BDTEP . esc_html__( 'Post Content', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'bdt-wi-post-content';
	}

	public function get_categories() {
		return [ 'bdt-template-builder' ];
	}

	public function get_keywords() {
		return [ 'post', 'content', 'blog', 'recent', 'news', 'alter' ];
	}

	public function show_in_panel() {
		return get_post_type() === 'elementor_library' || get_post_type() === 'post';
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'content_type',
			[
				'label'   => esc_html__( 'Content Type', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'excerpt',
				'options' => [
					'excerpt' => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
					'full'    => esc_html__( 'Full Content', 'bdthemes-element-pack' ),
				],
			]
		);
		$this->add_control(
			'limit',
			[
				'label'     => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 10,
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'content_type' => 'excerpt',
				],
			]
		);
		$this->add_responsive_control(
			'upk_content_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'   => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);
		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => esc_html__( 'Margin', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'link_tabs' );

		$this->start_controls_tab(
			'link_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'     => esc_html__( 'Links Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'link_color_hover',
			[
				'label'     => esc_html__( 'Link Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		$this->bdt_set_single_post_preview_data();

		$post = $this->get_theme_builder_queried_post();
		if ( ! $post instanceof \WP_Post ) {
			if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				echo wp_kses_post(
					__( 'This is a dummy text to demonstration purpose. It will be replaced with the post content or excerpt.', 'bdthemes-element-pack' )
				);
			}
			return;
		}

		if ( post_password_required( $post ) ) {
			echo get_the_password_form( $post ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		$limit = ( 'excerpt' === $settings['content_type'] && ! empty( $settings['limit']['size'] ) )
			? (int) $settings['limit']['size']
			: 10;

		if ( 'excerpt' === $settings['content_type'] ) {
			$post_excerpt = get_the_excerpt( $post );
			$post_content = get_post_field( 'post_content', $post->ID );

			if ( '' !== trim( (string) $post_excerpt ) ) {
				echo wp_kses_post( wp_trim_words( wp_strip_all_tags( (string) $post_excerpt ), $limit ) );
			} else {
				echo wp_kses_post( wp_trim_words( wp_strip_all_tags( (string) $post_content ), $limit ) );
			}

			return;
		}

		$previous_post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

		setup_postdata( $post );

		$post_content = get_post_field( 'post_content', $post->ID );
		if ( '' === trim( (string) $post_content ) ) {
			echo wp_kses_post(
				__( 'This is a dummy text to demonstration purpose. It will be replaced with the post content or excerpt.', 'bdthemes-element-pack' )
			);
			wp_reset_postdata();
			return;
		}

		// Match `the_content()`: keeps oEmbed iframes (e.g. YouTube) and avoids nested Elementor document layout breaks.
		$content = apply_filters( 'the_content', get_the_content( null, false, $post ) );
		echo str_replace( ']]>', ']]&gt;', $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		wp_reset_postdata();

		if ( $previous_post instanceof \WP_Post ) {
			$GLOBALS['post'] = $previous_post;
		}
	}
}
