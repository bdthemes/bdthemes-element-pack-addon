<?php

namespace ElementPackAddon\Modules\PostList\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use ElementPack\Utils;
use ElementPack\Traits\Global_Terms_Query_Controls;

use ElementPack\Base\Module_Base;
use ElementPack\Traits\Global_Widget_Controls;
use ElementPack\Includes\Controls\GroupQuery\Group_Control_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_List extends Module_Base {
	use Group_Control_Query;
	use Global_Widget_Controls;
	use Global_Terms_Query_Controls;

	public $_query = null;

	public function get_name() {
		return 'bdt-post-list';
	}

	public function get_title() {
		return BDTEP . esc_html__( 'Post List', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'bdt-wi-post-list';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_keywords() {
		return [ 'post', 'list', 'blog', 'recent', 'news' ];
	}

	public function get_style_depends() {
		return $this->ep_is_edit_mode() ? [ 'ep-addon-styles' ] : [ 'ep-post-list' ];
	}

	public function get_script_depends() {
		return $this->ep_is_edit_mode() ? [ 'ep-addon-scripts' ] : [ 'ep-post-list' ];
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/5aQTAsLRF0o';
	}

	public function get_query() {
		return $this->_query;
	}

	public function has_widget_inner_wrapper(): bool {
        return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }
	
	
	protected function register_controls() {


		$this->start_controls_section(
			'section_content_layout',
			[ 
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_title',
			[ 
				'label'   => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title_tags',
			[
				'label'     => __( 'Title HTML Tag', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h4',
				'options'   => element_pack_title_tags(),
				'condition' => [ 
					'show_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_image',
			[ 
				'label'   => esc_html__( 'Featured Image', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'default'   => 'thumbnail',
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_horizontal',
			[ 
				'label' => esc_html__( 'Multiple Columns', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_responsive_control(
			'column',
			[ 
				'label'          => esc_html__( 'Columns', 'bdthemes-element-pack' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '2',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'description'    => esc_html__( 'For good looking set it 1 for default skin and 2 for another skin', 'bdthemes-element-pack' ),
				'options'        => [ 
					'1' => esc_html__( 'One', 'bdthemes-element-pack' ),
					'2' => esc_html__( 'Two', 'bdthemes-element-pack' ),
					'3' => esc_html__( 'Three', 'bdthemes-element-pack' ),
					'4' => esc_html__( 'Four', 'bdthemes-element-pack' ),
				],
				'render_type'    => 'template',
				'condition'      => [ 
					'show_horizontal' => 'yes',
				],
				'selectors'      => [ 
					'{{WRAPPER}} .bdt-post-list' => 'grid-template-columns: repeat({{SIZE}}, 1fr);',
				],
			]
		);

		$this->add_responsive_control(
			'space_between',
			[ 
				'label'      => esc_html__( 'Gap', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [ 
					'px' => [ 
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list' => 'gap: {{SIZE}}{{UNIT}};', 
					'{{WRAPPER}}.bdt-has-divider--yes .bdt-item-wrap' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2); margin-bottom: calc(-{{SIZE}}{{UNIT}}/2);',
				],
			]
		);

		$this->add_control(
			'show_date',
			[ 
				'label'     => esc_html__( 'Date', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'human_diff_time',
			[ 
				'label'     => esc_html__( 'Human Different Time', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [ 
					'show_date' => 'yes'
				]
			]
		);

		$this->add_control(
			'human_diff_time_short',
			[ 
				'label'     => esc_html__( 'Time Short Format', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [ 
					'human_diff_time' => 'yes',
					'show_date'       => 'yes'
				]
			]
		);

		$this->add_control(
			'show_category',
			[ 
				'label'   => esc_html__( 'Category', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_divider',
			[ 
				'label'        => esc_html__( 'Divider', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'prefix_class' => 'bdt-has-divider--',
				'render_type'  => 'template',
				'condition'    => [ 
					'show_horizontal' => ''
				],
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();

		//New Query Builder Settings
		$this->start_controls_section(
			'section_post_query_builder',
			[
				'label' => __( 'Query', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->register_query_builder_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[ 
				'label' => esc_html__( 'Additional Options', 'bdthemes-element-pack' ),
			]
		);
		$this->add_control(
			'show_filter_bar',
			[ 
				'label'     => esc_html__( 'Filter Bar', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);
		$post_types = $this->getGroupControlQueryPostTypes();

		foreach ( $post_types as $key => $post_type ) {
			$taxonomies = $this->get_taxonomies( $key );
			if ( ! $taxonomies[ $key ] ) {
				continue;
			}
			$this->add_control(
				'taxonomy_' . $key,
				[
					'label'     => __( 'Taxonomies', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $taxonomies[ $key ],
					'default'   => key( $taxonomies[ $key ] ),
					'condition' => [ 
						'posts_source'    => $key,
						'show_filter_bar' => 'yes'
					],
				]
			);
		}
		$this->add_control(
			'header_title_text',
			[
				'label'     => esc_html__('Filter Bar Title', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => esc_html__('Trending Articles', 'bdthemes-element-pack'),
				'condition' => [
					'show_filter_bar' => 'yes'
				],
				'label_block' => true
			]
		);
		$this->add_control(
			'follow_descendants',
			[ 
				'label'     => esc_html__( 'Follow Descendants', 'bdthemes-element-pack' ),
				'description'     => esc_html__( 'When enabled, this option ensures that selected parent categories automatically include or exclude all their child and grandchild categories', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_filter_bar' => 'yes'
				],
			]
		);
		$this->add_control(
			'icon',
			[
				'label'            => esc_html__('Icon', 'bdthemes-element-pack'),
				'type'             => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'icon_link_enable',
			[ 
				'label'     => esc_html__( 'Icon Link Enable', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'icon[value]!' => '',
				],
			]
		);
		
		/**
		 * Global bdt_link_new_tab control
		 */
		$this->register_bdt_link_new_tab_controls();

		$this->add_responsive_control(
			'content_position',
			[ 
				'label'     => esc_html__( 'Content Direction', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [ 
					'row' => [ 
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-align-start-h',
					],
					'row-reverse' => [ 
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-align-end-h',
					],
					'space-between' => [ 
						'title' => esc_html__( 'Space Between', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-justify-space-between-h',
					],
				],
				'selectors_dictionary' => [ 
					'row'         => 'flex-direction: row;',
					'row-reverse' => 'flex-direction: row-reverse; text-align: right;',
					'space-between' => 'flex-direction: row-reverse; justify-content: space-between;',
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-item' => '{{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'content_y_align',
			[ 
				'label'     => esc_html__( 'Content Vertical Align', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [ 
					'flex-start' => [ 
						'title' => esc_html__( 'Top', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center' => [ 
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end' => [ 
						'title' => esc_html__( 'Bottom', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-item' => 'align-items: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'show_pagination',
			[ 
				'label'     => esc_html__( 'Pagination', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [ 
					'show_filter_bar' => ''
				],
			]
		);
		$this->end_controls_section();

		/**
		 * Style Tab
		 */
		$this->start_controls_section(
			'section_filter_bar',
			[ 
				'label' => esc_html__( 'Filter Bar', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'show_filter_bar' => 'yes'
				],
			]
		);
		$this->add_control(
			'header_title_color',
			[
				'label'     => esc_html__('Title Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-list-header .bdt-title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'header_title_text!' => ''
				]
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'header_background',
				'label'    => esc_html__('Background', 'bdthemes-element-pack'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .bdt-post-list-header',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'header_border',
				'label'    => esc_html__('Border', 'bdthemes-element-pack'),
				'selector' => '{{WRAPPER}} .bdt-post-list-header',
				'separator' => 'before'
			]
		);
		$this->add_responsive_control(
			'header_border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-list-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'header_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-list-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'header_margin',
			[
				'label'      => __( 'Margin', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-list-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'header_box_shadow',
				'label'    => esc_html__('Box Shadow', 'bdthemes-element-pack'),
				'selector' => '{{WRAPPER}} .bdt-post-list-header',
			]
		);
		$this->add_responsive_control(
			'header_gap',
			[
				'label'      => esc_html__('Title Spacing', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-list-header' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'header_title_text!' => ''
				]
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'header_typography',
				'label'    => esc_html__('Title Typography', 'bdthemes-element-pack'),
				'selector' => '{{WRAPPER}} .bdt-post-list-header .bdt-title',
				'condition' => [
					'header_title_text!' => ''
				]
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter_category',
			[
				'label' => esc_html__('Filter Category', 'bdthemes-element-pack'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_filter_bar' => 'yes'
				]
			]
		);
		$this->start_controls_tabs(
			'filter_tabs'
		);

		$this->start_controls_tab(
			'filter_tab_normal',
			[
				'label' => esc_html__('Normal', 'bdthemes-element-pack'),
			]
		);
		$this->add_control(
			'header_filter_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-list-header .bdt-filter-list a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'filter_background',
				'label'    => esc_html__('Background', 'bdthemes-element-pack'),
				'types'    => ['classic', 'gradient'],
				'exclude'  => ['image'], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- Elementor control option, not WP_Query.
				'selector' => '{{WRAPPER}} .bdt-post-list-header .bdt-filter-list a',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'filter_border',
				'label'     => esc_html__('Border', 'bdthemes-element-pack'),
				'selector'  => '{{WRAPPER}} .bdt-post-list-header .bdt-filter-list a',
				'separator' => 'before'
			]
		);
		$this->add_responsive_control(
			'filter_border_radius',
			[
				'label'                 => esc_html__('Border Radius', 'bdthemes-element-pack'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => ['px', '%', 'em'],
				'selectors'             => [
					'{{WRAPPER}} .bdt-post-list-header .bdt-filter-list a'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'header_filter_padding',
			[
				'label'      => esc_html__('Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-list-header .bdt-filter-list a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'header_filter_gap',
			[
				'label'      => esc_html__('Gap', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-post-list-header .bdt-filter-wrap' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'header_filter_typography',
				'label'    => esc_html__('Typography', 'bdthemes-element-pack'),
				'selector' => '{{WRAPPER}} .bdt-post-list-header .bdt-filter-list a',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'filter_tab_hover',
			[
				'label' => esc_html__('Hover', 'bdthemes-element-pack'),
			]
		);
		$this->add_control(
			'filter_hover_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-list-header .bdt-filter-list a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'filter_hover_background',
				'label'    => esc_html__('Background', 'bdthemes-element-pack'),
				'types'    => ['classic', 'gradient'],
				'exclude'  => ['image'], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- Elementor control option, not WP_Query.
				'selector' => '{{WRAPPER}} .bdt-post-list-header .bdt-filter-list a:hover',
			]
		);
		$this->add_control(
			'filter_hover_border_color',
			[
				'label'     => esc_html__('Border Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-list-header .bdt-filter-list a:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'filter_border_border!' => '',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'filter_tab_active',
			[
				'label' => esc_html__('Active', 'bdthemes-element-pack'),
			]
		);
		$this->add_control(
			'filter_active_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-list-header .bdt-filter-list.bdt-active a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'filter_active_background',
				'label'    => esc_html__('Background', 'bdthemes-element-pack'),
				'types'    => ['classic', 'gradient'],
				'exclude'  => ['image'], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- Elementor control option, not WP_Query.
				'selector' => '{{WRAPPER}} .bdt-post-list-header .bdt-filter-list.bdt-active a',
			]
		);
		$this->add_control(
			'filter_active_border_color',
			[
				'label'     => esc_html__('Border Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-list-header .bdt-filter-list.bdt-active a' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'filter_border_border!' => '',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_items',
			[ 
				'label' => esc_html__( 'Items', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'divider_color',
			[ 
				'label'     => esc_html__( 'Divider Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}}.bdt-has-divider--yes .bdt-item-wrap' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [ 
					'show_divider' => 'yes',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[ 
				'name'     => 'item_background',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-item',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[ 
				'name'     => 'item_border',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-item',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'item_border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'item_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'space_between_items',
			[
				'label'      => __( 'Space Between', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-item' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [ 
					'relation' => 'or', 
					'terms' => [
						[
							'name'     => 'icon[value]',
							'operator' => '!=',
							'value'    => '',
						],
						[
							'name'     => 'show_image',
							'operator' => '==',
							'value'    => 'yes',
						],
					] 
				],

			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[ 
				'name'     => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-item',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			[
				'label'     => __( 'Icon', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'icon[value]!' => ''
				],
			]
		);

		$this->start_controls_tabs( 'icon_style_tabs' );
		$this->start_controls_tab(
			'icon_style_normal_tab',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-list-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-post-list .bdt-list-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[ 
				'name'     => 'icon_background',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-list-icon',
				'exclude'  => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- Elementor control option, not WP_Query.
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[ 
				'name'     => 'icon_border',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-list-icon',
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-list-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_margin',
			[
				'label'      => __( 'Margin', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-list-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Size', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'icon_style_hover_tab',
			[
				'label' => __( 'Hover', 'bdthemes-element-pack' ),
			]
		);
		$this->add_control(
			'icon_hover_color',
			[
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-list-icon:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-post-list .bdt-list-icon:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[ 
				'name'     => 'icon_hover_background',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-list-icon:hover',
				'exclude'  => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- Elementor control option, not WP_Query.
			]
		);
		$this->add_control(
			'icon_hover_border_color',
			[
				'label'     => __( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-list-icon:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [ 
					'icon_border_border!' => '',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label'     => __( 'Image', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'show_image' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_responsive_control(
			'list_layout_image_size',
			[ 
				'label'     => esc_html__( 'Width (px)', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [ 
					'px' => [ 
						'min'  => 50,
						'max'  => 200,
						'step' => 1,
					]
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-image img' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'list_layout_image_height',
			[
				'label'     => esc_html__( 'Height (px)', 'bdthemes-element-pack' ) . BDTEP_NC,
				'type'      => Controls_Manager::SLIDER,
				'range'     => [ 
					'px' => [ 
						'min'  => 50,
						'max'  => 200,
						'step' => 1,
					]
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-image img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[ 
				'name'      => 'button_background',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .bdt-post-list .bdt-image img'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[ 
				'name'        => 'image_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-post-list .bdt-image img',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'image_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'after',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;'
				]
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label'      => __( 'Margin', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[ 
				'name'     => 'image_shadow',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-image img'
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[ 
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-image img',
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label'     => __( 'Opacity', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [ 
					'px' => [ 
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label'     => __( 'Transition Duration', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 
					'size' => 0.3,
				],
				'range'     => [ 
					'px' => [ 
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-image img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			[
				'label' => __( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[ 
				'name'      => 'image_hover_background',
				'selector'  => '{{WRAPPER}} .bdt-post-list .bdt-image:hover img'
			]
		);

		$this->add_control(
			'image_hover_border_color',
			[
				'label'     => __( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-image:hover img' => 'border-color: {{VALUE}};'
				],
				'condition' => [ 
					'image_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[ 
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-image:hover img',
			]
		);

		$this->add_control(
			'image_opacity_hover',
			[
				'label'     => __( 'Opacity', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [ 
					'px' => [ 
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-image:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[ 
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'show_title' => 'yes',
				],
			]
		);
		$this->add_control(
			'list_layout_title_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'list_layout_title_hover_color',
			[ 
				'label'     => esc_html__( 'Hover Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .bdt-post-list .bdt-title',
			]
		);
		
		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => __( 'Margin', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_meta',
			[ 
				'label' => esc_html__( 'Meta', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'conditions' => [ 
					'relation' => 'or',
					'terms' => [ 
						[ 
							'name'  => 'show_date',
							'value' => 'yes',
						],
						[ 
							'name'  => 'show_category',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'separator_color',
			[ 
				'label'     => esc_html__( 'Separator Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-subnav span:after' => 'background: {{VALUE}};',
				],
				'condition' => [ 
					'show_date' => 'yes',
					'show_category' => 'yes',
				],
			]
		);
		
		$this->start_controls_tabs( 'meta_style' );
		
		$this->start_controls_tab(
			'meta_date',
			[ 
				'label' => esc_html__( 'Date', 'bdthemes-element-pack' ),
				'condition' => [ 
					'show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-meta span' => 'color: {{VALUE}};',
				],
				'condition' => [ 
					'show_date' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'      => 'date_typography',
				'selector'  => '{{WRAPPER}} .bdt-post-list .bdt-meta span',
				'condition' => [ 
					'show_date' => 'yes',
				],

			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'meta_category',
			[ 
				'label' => esc_html__( 'Category', 'bdthemes-element-pack' ),
				'condition' => [ 
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-meta a' => 'color: {{VALUE}};',
				],
				'condition' => [ 
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_hover_color',
			[ 
				'label'     => esc_html__( 'Hover Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-list .bdt-meta a:hover' => 'color: {{VALUE}};',
				],
				'condition' => [ 
					'show_category' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'      => 'category_typography',
				'selector'  => '{{WRAPPER}} .bdt-post-list .bdt-meta a',
				'condition' => [ 
					'show_category' => 'yes',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		//Pagination
		$this->start_controls_section(
			'section_style_pagination',
			[ 
				'label'     => esc_html__( 'Pagination', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'show_pagination' => 'yes',
				],
			]
		);

		$this->register_pagination_controls();

		$this->end_controls_section();
	}

	public function get_taxonomies( $post_type = '' ) {
		$result = [];
		if ( $post_type !== '' ) {
			$taxonomies = get_taxonomies( [ 'public' => true, 'object_type' => [ $post_type ] ], 'object' );
			$labels    = array_diff_key( wp_list_pluck( $taxonomies, 'label', 'name' ), [] );
			$result[ $post_type ] = $labels != [] ? $labels : '';
		}
		return $result;
	}

	public function get_posts_tags() {
		$taxonomy = $this->get_settings( 'taxonomy' );
		if ( ! $this->_query || empty( $this->_query->posts ) ) {
			return;
		}
		foreach ( $this->_query->posts as $post ) {
			if ( empty( $taxonomy ) ) {
				$post->tags = [];
				continue;
			}
			$terms = wp_get_post_terms( $post->ID, $taxonomy );
			$term_ids   = wp_list_pluck( $terms, 'term_id' );
			$post->tags = array_combine( $term_ids, $terms );
		}
	}

	public function query_posts( $settings ) {
		$posts_per_page = isset( $settings['posts_per_page'] ) ? (int) $settings['posts_per_page'] : 0;
		$args           = $this->getGroupControlQueryArgs();
		$is_current_query = ( ! empty( $settings['posts_source'] ) && $settings['posts_source'] === 'current_query' );

		if ( $is_current_query ) {
			unset( $args['offset'] );
			unset( $args['no_found_rows'] );
			$posts_per_page = 0;
		}

		if ( $posts_per_page > 0 ) {
			$args['posts_per_page'] = $posts_per_page;
		} else {
			$args['posts_per_page'] = (int) get_option( 'posts_per_page', 10 );
		}

		if ( ! empty( $settings['show_pagination'] ) ) {
			$args['paged'] = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
		}

		$this->_query = new \WP_Query( $args );
	}

	public function render_date( $settings ) {
		if ( empty( $settings['show_date'] ) ) {
			return;
		}
		echo '<span>';
		if ( ( $settings['human_diff_time'] ?? '' ) === 'yes' ) {
			$format = ( $settings['human_diff_time_short'] ?? '' ) === 'yes' ? 'short' : '';
			echo wp_kses_post( element_pack_post_time_diff( $format ) );
		} else {
			echo wp_kses_post( get_the_date() );
		}
		echo '</span>';
	}

	public function render_category( $settings ) {
		if ( empty( $settings['show_category'] ) ) {
			return;
		}
		$post_type = $settings['posts_source'] ?? 'post';
		echo '<span>';
		echo wp_kses_post( element_pack_get_category_list( $post_type, ', ' ) );
		echo '</span>';
	}

	protected function bdt_get_categorized_terms( $settings ) {

		$post_type         = $settings['posts_source'] ?? $this->get_settings( 'posts_source' );
		$follow_descendants = ! empty( $settings['follow_descendants'] );
		$selected_taxonomy  = $settings[ 'taxonomy_' . $post_type ] ?? '';
		$taxonomy           = $selected_taxonomy ?: element_pack_get_taxonomy_by_post_type( $post_type );
		$include_terms      = $settings['posts_include_term_ids'] ?? [];
		$exclude_terms      = $settings['posts_exclude_term_ids'] ?? [];

		if ( ! empty( $include_terms ) && $follow_descendants ) {
			$included_terms = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'include'    => $include_terms,
			] );
			if ( ! is_wp_error( $included_terms ) && ! empty( $included_terms ) ) {
				foreach ( $included_terms as $included_term ) {
					$include_terms = array_merge( $include_terms, $this->bdt_get_descendants_categories( $taxonomy, $included_term->term_id ) );
				}
			}
		}

		if ( ! empty( $exclude_terms ) && $follow_descendants ) {
			$excluded_terms = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'include'    => $exclude_terms,
			] );
			if ( ! is_wp_error( $excluded_terms ) && ! empty( $excluded_terms ) ) {
				foreach ( $excluded_terms as $excluded_term ) {
					$exclude_terms = array_merge( $exclude_terms, $this->bdt_get_descendants_categories( $taxonomy, $excluded_term->term_id ) );
				}
			}
		}

		$post_categories = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'include'    => $include_terms ?: '',
			'exclude'    => $exclude_terms, // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- Elementor control option, not WP_Query.
		] );

		$post_options = [];
		if ( ! is_wp_error( $post_categories ) && ! empty( $post_categories ) ) {
			foreach ( $post_categories as $category ) {
				$post_options[ $category->slug ] = $category->name;
			}
		}
		return $post_options;
	}

	public function bdt_get_descendants_categories( $taxonomy, $parent_id ) {
		$child_terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'parent'     => $parent_id,
		] );
		if ( is_wp_error( $child_terms ) || empty( $child_terms ) ) {
			return [];
		}
		$descendants = [];
		foreach ( $child_terms as $child ) {
			$descendants[] = $child->term_id;
			$descendants   = array_merge( $descendants, $this->bdt_get_descendants_categories( $taxonomy, $child->term_id ) );
		}
		return $descendants;
	}

	public function bdt_get_filtered_terms( $settings ) {
		$post_type  = $settings['posts_source'] ?? '';
		$taxonomies = $post_type ? get_object_taxonomies( $post_type, 'objects' ) : [];
		if ( empty( $taxonomies ) ) {
			return [];
		}
		$selected_taxonomy = $settings[ 'taxonomy_' . $post_type ] ?? '';
		if ( $selected_taxonomy === '' || ! isset( $taxonomies[ $selected_taxonomy ] ) ) {
			return [];
		}
		$args = [
			'taxonomy'   => $selected_taxonomy,
			'hide_empty' => true,
		];
		if ( ! empty( $settings['posts_include_term_ids'] ) ) {
			$args['include'] = $settings['posts_include_term_ids'];
		}
		if ( ! empty( $settings['posts_exclude_term_ids'] ) ) {
			$args['exclude'] = $settings['posts_exclude_term_ids']; // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- Term exclusion for post list filter.
		}
		$found_terms = get_terms( $args );
		if ( is_wp_error( $found_terms ) || empty( $found_terms ) ) {
			return [];
		}
		$terms = [];
		foreach ( $found_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		return $terms;
	}

	public function render_post_filter( $settings ) {
		if ( empty( $settings['show_filter_bar'] ) ) {
			return;
		}
		$terms = $this->bdt_get_filtered_terms( $settings );
		if ( empty( $terms ) ) {
			return;
		}
		$header_title = $settings['header_title_text'] ?? '';
		?>
		<div class="bdt-post-list-header bdt-flex bdt-flex-between bdt-flex-wrap bdt-flex-middle">
			<div class="bdt-header-title">
				<?php if ( $header_title !== '' ) : ?>
					<h3 class="bdt-title"><?php echo esc_html( $header_title ); ?></h3>
				<?php endif; ?>
			</div>
			<div class="bdt-filter-wrap bdt-flex bdt-flex-wrap">
				<div class="bdt-filter-list bdt-active">
					<a class="bdt-option bdt-active" href="javascript:void(0)" data-slug=""><?php esc_html_e( 'All', 'bdthemes-element-pack' ); ?></a>
				</div>
				<?php foreach ( $terms as $slug => $name ) : ?>
					<div class="bdt-filter-list">
						<a class="bdt-option" href="javascript:void(0)" data-slug="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();
		$post_type = $settings['posts_source'] ?? 'post';
		$selected_taxonomy = $settings[ 'taxonomy_' . $post_type ] ?? '';
		$taxonomy = $selected_taxonomy !== '' ? $selected_taxonomy : element_pack_get_taxonomy_by_post_type( $post_type );

		$this->add_render_attribute( 'bdt-title', 'class', 'bdt-title' );
		$this->add_render_attribute(
			[
				'post-wrap' => [
					'class' => [ 'bdt-post-list-wrap' ],
					'data-show-hide' => [
						wp_json_encode( array_filter( [
							'show_title'            => $settings['show_title'] ?? 'no',
							'show_category'         => $settings['show_category'] ?? 'no',
							'show_image'            => $settings['show_image'] ?? 'no',
							'icon'                  => $settings['icon']['value'] ?? '',
							'show_date'             => $settings['show_date'] ?? 'no',
							'bdt_link_new_tab'      => $settings['bdt_link_new_tab'] ?? [],
							'human_diff_time'       => $settings['human_diff_time'] ?? 'no',
							'human_diff_time_short' => $settings['human_diff_time_short'] ?? 'no',
						] ) ),
					],
				],
			]
		);
		$this->add_render_attribute( 'post-list', 'class', 'bdt-post-list', true );
		$this->add_render_attribute( 'post-list', 'id', 'bdt-post-list-' . esc_attr( $id ) );
		$this->add_render_attribute(
			[
				'post-list' => [
					'data-settings' => [
						wp_json_encode( array_filter( [
							'taxonomy'               => $taxonomy,
							'post-type'              => $post_type,
							'order'                  => $settings['posts_order'] ?? '',
							'orderby'                => $settings['posts_orderby'] ?? '',
							'posts_per_page'         => $settings['posts_per_page'] ?? '',
							'posts_include_term_ids' => $settings['posts_include_term_ids'] ?? [],
							'posts_exclude_term_ids' => $settings['posts_exclude_term_ids'] ?? [],
						] ) ),
					],
				],
			]
		);

		$this->query_posts( $settings );
		$wp_query = $this->get_query();

		if ( ! $wp_query->have_posts() ) {
			return;
		}
		?>
		<div <?php $this->print_render_attribute_string( 'post-wrap' ); ?>>
			<?php $this->render_post_filter( $settings ); ?>
			<div <?php $this->print_render_attribute_string( 'post-list' ); ?>>
				<?php
				$thumbnail_size = $settings['thumbnail_size'] ?? 'thumbnail';
				$placeholder_src = Utils::get_placeholder_image_src();
				$title_tag      = Utils::get_valid_html_tag( $settings['title_tags'] ?? 'h4' );
				$icon_value     = $settings['icon']['value'] ?? '';
				$link_new_tab   = ! empty( $settings['bdt_link_new_tab'] );

				while ( $wp_query->have_posts() ) {
					$wp_query->the_post();
					$post_id        = get_the_ID();
					$image_src      = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $thumbnail_size );
					$permalink      = get_permalink();
					$the_title      = get_the_title();
					?>
					<div class="bdt-item-wrap bdt-flex">
						<div class="bdt-item bdt-flex bdt-flex-middle">
							<?php if ( $icon_value !== '' ) : ?>
								<div class="bdt-list-icon bdt-position-relative">
									<?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true', 'class' => 'fa-fw' ] ); ?>
									<?php if ( ! empty( $settings['icon_link_enable'] ) ) : ?>
										<a href="<?php echo esc_url( $permalink ); ?>" class="bdt-list-icon-link bdt-position-cover"
											<?php echo $link_new_tab ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
											title="<?php echo esc_attr( $the_title ); ?>">
										</a>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $settings['show_image'] ) ) : ?>
								<div class="bdt-image bdt-flex">
									<a href="<?php echo esc_url( $permalink ); ?>" title="<?php echo esc_attr( $the_title ); ?>">
										<?php
										if ( empty( $image_src ) ) {
											printf( '<img src="%1$s" alt="%2$s">', esc_url( $placeholder_src ), esc_attr( $the_title ) );
										} else {
											echo wp_get_attachment_image(
												get_post_thumbnail_id(),
												$thumbnail_size,
												false,
												[ 'alt' => esc_attr( $the_title ) ]
											);
										}
										?>
									</a>
								</div>
							<?php endif; ?>

							<div class="bdt-content">
								<?php if ( ! empty( $settings['show_title'] ) ) : ?>
									<<?php echo esc_attr( $title_tag ); ?> <?php $this->print_render_attribute_string( 'bdt-title' ); ?>>
										<a href="<?php echo esc_url( $permalink ); ?>" class="bdt-link" title="<?php echo esc_attr( $the_title ); ?>">
											<?php echo esc_html( $the_title ); ?>
										</a>
									</<?php echo esc_attr( $title_tag ); ?>>
								<?php endif; ?>

								<?php if ( ! empty( $settings['show_category'] ) || ! empty( $settings['show_date'] ) ) : ?>
									<div class="bdt-meta bdt-subnav bdt-flex-middle">
										<?php $this->render_date( $settings ); ?>
										<?php $this->render_category( $settings ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php
				}
				wp_reset_postdata();
				?>
				<!-- GRADIENT SPINNER -->
				<div id="bdt-loading-image" style="display: none;">
					<div class="bdt-spinner-box">
						<div class="bdt-circle-border">
							<div class="bdt-circle-core"></div>
						</div>
					</div>
				</div>
				<!-- GRADIENT SPINNER -->
			</div>
		</div>
		<?php
		if ( ! empty( $settings['show_pagination'] ) ) {
			echo '<div class="ep-pagination">';
			element_pack_post_pagination( $wp_query );
			echo '</div>';
		}
	}
}
