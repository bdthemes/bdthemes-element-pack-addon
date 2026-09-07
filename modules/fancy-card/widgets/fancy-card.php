<?php

namespace ElementPackAddon\Modules\FancyCard\Widgets;

use ElementPack\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use ElementPack\Utils;

use ElementPackAddon\Modules\FancyCard\Skins;

use ElementPack\Traits\Global_Widget_Controls;
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Fancy_Card extends Module_Base {

	use Global_Widget_Controls;

	public function get_name() {
		return 'bdt-fancy-card';
	}

	public function get_title() {
		return BDTEP . esc_html__('Fancy Card', 'bdthemes-element-pack');
	}

	public function get_icon() {
		return 'bdt-wi-fancy-card';
	}

	public function get_categories() {
		return ['element-pack'];
	}

	public function get_keywords() {
		return ['fancy', 'advanced', 'icon', 'features', 'card'];
	}

	public function get_style_depends() {
		return $this->ep_is_edit_mode() ? ['ep-addon-styles'] : ['ep-fancy-card'];
	}

	public function register_skins() {
		$this->add_skin(new Skins\Skin_Stack($this));
		$this->add_skin(new Skins\Skin_Batty($this));
		$this->add_skin(new Skins\Skin_Climax($this));
		$this->add_skin(new Skins\Skin_Flux($this));
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/BXdVB1pLfXE';
	}

	public function has_widget_inner_wrapper(): bool {
        return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }
	protected function is_dynamic_content(): bool {
		return false;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content_icon_box',
			[
				'label' => __('Icon Box', 'bdthemes-element-pack'),
			]
		);


		$this->add_control(
			'icon_type',
			[
				'label'        => esc_html__('Icon Type', 'bdthemes-element-pack'),
				'type'         => Controls_Manager::CHOOSE,
				'toggle'       => false,
				'default'      => 'icon',
				'prefix_class' => 'bdt-icon-type-',
				'render_type'  => 'template',
				'options'      => [
					'icon' => [
						'title' => esc_html__('Icon', 'bdthemes-element-pack'),
						'icon'  => 'far fa-laugh-wink'
					],
					'image' => [
						'title' => esc_html__('Image', 'bdthemes-element-pack'),
						'icon'  => 'far fa-image'
					]
				]
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => __('Icon', 'bdthemes-element-pack'),
				'type'             => Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa-laugh',
					'library' => 'fa-regular',
				],
				'condition'        => [
					'icon_type' => 'icon',
				],
				'label_block' => false,
				'skin' => 'inline'
			]
		);

		$this->add_control(
			'image',
			[
				'label'       => __('Image Icon', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => ['active' => true],
				'render_type' => 'template',
				'default'     => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'icon_type' => 'image'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'label'     => __('Thumbnail Size', 'bdthemes-element-pack'),
				'exclude'   => ['custom'], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- Elementor control option, not WP_Query.
				'default'   => 'full',
				'condition' => [
					'icon_type' => 'image'
				]
			]
		);

		$this->add_control(
			'title_text',
			[
				'label'   => __('Title', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default'     => __('Laugh', 'bdthemes-element-pack'),
				'placeholder' => __('Enter your title', 'bdthemes-element-pack'),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title_link',
			[
				'label'        => __('Title Link', 'bdthemes-element-pack'),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'bdt-ep-fancy-card-title-link-'
			]
		);


		$this->add_control(
			'title_link_url',
			[
				'label'       => __('Title Link URL', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::URL,
				'dynamic'     => ['active' => true],
				'placeholder' => 'http://your-link.com',
				'condition'   => [
					'title_link' => 'yes'
				]
			]
		);

		$this->add_control(
			'description_text',
			[
				'label'   => __('Description', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default'     => __('Click edit button to change this text. If you are going to use you need to be sure there text.', 'bdthemes-element-pack'),
				'placeholder' => __('Enter your description', 'bdthemes-element-pack'),
				'rows'        => 10,
				'separator'   => 'before',
			]
		);


		$this->add_responsive_control(
			'text_align',
			[
				'label'   => __('Text Alignment', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __('Justified', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label'   => __('Icon Alignment', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => __('Right', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'_skin!' => 'flux',
				],
			]
		);

		$this->add_control(
			'fancy_card_icon_position',
			[
				'label'   => __('Fancy Icon Position', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''   => __('Default', 'bdthemes-element-pack'),
					'left'   => __('Left', 'bdthemes-element-pack'),
					'right'  => __('Right', 'bdthemes-element-pack'),
				],
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->add_control(
			'fancy_card_icon_style',
			[
				'label'   => __('Select Style', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1'   => __('Style 01', 'bdthemes-element-pack'),
					'style2'   => __('Style 02', 'bdthemes-element-pack'),
					'style3'   => __('Style 03', 'bdthemes-element-pack'),
					'style4'   => __('Style 04', 'bdthemes-element-pack'),
				],
				'condition' => [
					'_skin!' => ['', 'batty', 'climax', 'flux'],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_additional',
			[
				'label' => __('Additional Settings', 'bdthemes-element-pack'),
			]
		);

		$this->add_responsive_control(
			'top_icon_vertical_offset',
			[
				'label' => esc_html__('Icon Vertical Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'condition' => [
					'position' => 'top',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-icon' => 'margin-top: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'top_icon_horizontal_offset',
			[
				'label' => esc_html__('Icon Horizontal Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 0,
				],
				'condition' => [
					'position' => 'top',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-icon' => 'transform: translateX({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'left_icon_horizontal_offset',
			[
				'label' => esc_html__('Icon Horizontal Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 0,
				],
				'condition' => [
					'position' => 'left',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-icon' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'right_icon_horizontal_offset',
			[
				'label' => esc_html__('Icon Horizontal Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 0,
				],
				'condition' => [
					'position' => 'right',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-icon' => 'margin-right: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'left_right_icon_vertical_offset',
			[
				'label' => esc_html__('Icon Vertical Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'condition' => [
					'position' => ['left', 'right'],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'title_size',
			[
				'label'   => __('Title HTML Tag', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => element_pack_title_tags(),
			]
		);

		$this->add_control(
			'readmore',
			[
				'label'     => __('Read More Button', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'toggle_icon',
			[
				'label'            => __('Toggle Icon', 'bdthemes-element-pack'),
				'type'             => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'condition' => [
					'_skin' => 'climax'
				],
				'label_block' => false,
				'skin' => 'inline'
			]
		);

		$this->add_control(
			'toggle_position',
			[
				'label'   => __('Position', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom-right',
				'options' => [
					'top-left'      => esc_html__('Top Left', 'bdthemes-element-pack'),
					'top-right'     => esc_html__('Top Right', 'bdthemes-element-pack'),
					'bottom-left'   => esc_html__('Bottom Left', 'bdthemes-element-pack'),
					'bottom-right'  => esc_html__('Bottom Right', 'bdthemes-element-pack'),
				],
				'separator' => 'after',
				'condition' => [
					'_skin' => 'climax'
				]
			]
		);

		$this->add_control(
			'show_data_label',
			[
				'label' => __('Show Data Label', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'indicator',
			[
				'label' => __('Indicator', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'badge',
			[
				'label' => __('Badge', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'global_link',
			[
				'label'        => __('Global Link', 'bdthemes-element-pack'),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'bdt-global-link-',
				'description'  => __('Be aware! When Global Link activated then title link and read more link will not work', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'global_link_url',
			[
				'label'       => __('Global Link URL', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::URL,
				'dynamic'     => ['active' => true],
				'placeholder' => 'http://your-link.com',
				'condition'   => [
					'global_link' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_readmore',
			[
				'label'     => __('Read More', 'bdthemes-element-pack'),
				'condition' => [
					'readmore' => 'yes',
				],
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label'       => __('Text', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => ['active' => true],
				'default'     => __('Read More', 'bdthemes-element-pack'),
				'placeholder' => __('Read More', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'readmore_link',
			[
				'label'     => __('Link to', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::URL,
				'dynamic'   => [
					'active' => true,
				],
				'placeholder' => __('https://your-link.com', 'bdthemes-element-pack'),
				'default'     => [
					'url' => '#',
				],
				'condition' => [
					'readmore' => 'yes',
				]
			]
		);

		$this->add_control(
			'advanced_readmore_icon',
			[
				'label'       => __('Icon', 'bdthemes-element-pack'),
				'type'             => Controls_Manager::ICONS,
				'label_block' => false,
				'condition'   => [
					'readmore'       => 'yes'
				],
				'skin' => 'inline'
			]
		);

		$this->add_control(
			'readmore_icon_align',
			[
				'label'   => __('Icon Position', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'   => __('Left', 'bdthemes-element-pack'),
					'right'  => __('Right', 'bdthemes-element-pack'),
				],
				'condition' => [
					'advanced_readmore_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'readmore_icon_indent',
			[
				'label' => __('Icon Spacing', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 8,
				],
				'condition' => [
					'advanced_readmore_icon[value]!' => '',
					'readmore_text!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-readmore' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_indicator',
			[
				'label'     => __('Indicator', 'bdthemes-element-pack'),
				'condition' => [
					'indicator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'indicator_width',
			[
				'label' => __('Width', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 10,
						'step' => 2,
						'max'  => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-indicator-svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'indicator_offset_toggle',
			[
				'label' => __('Offset', 'bdthemes-element-pack'),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __('None', 'bdthemes-element-pack'),
				'label_on' => __('Custom', 'bdthemes-element-pack'),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'indicator_horizontal_offset',
			[
				'label' => __('Horizontal Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -300,
						'step' => 1,
						'max'  => 300,
					],
				],
				'condition' => [
					'indicator_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-indicator-h-offset: {{SIZE}}px;'
				],
			]
		);

		$this->add_responsive_control(
			'indicator_vertical_offset',
			[
				'label' => __('Vertical Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -300,
						'step' => 1,
						'max'  => 300,
					],
				],
				'condition' => [
					'indicator_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-indicator-v-offset: {{SIZE}}px;'
				],
			]
		);

		$this->add_responsive_control(
			'indicator_rotate',
			[
				'label'   => esc_html__('Rotate', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -360,
						'max'  => 360,
						'step' => 5,
					],
				],
				'condition' => [
					'indicator_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-indicator-rotate: {{SIZE}}deg;'
				],
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_badge',
			[
				'label'     => __('Badge', 'bdthemes-element-pack'),
				'condition' => [
					'badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_text',
			[
				'label'       => __('Badge Text', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'POPULAR',
				'placeholder' => 'Type Badge Title',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'badge_position',
			[
				'label'   => esc_html__('Position', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top-right',
				'options' => element_pack_position(),
			]
		);

		$this->add_control(
			'badge_offset_toggle',
			[
				'label' => __('Offset', 'bdthemes-element-pack'),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __('None', 'bdthemes-element-pack'),
				'label_on' => __('Custom', 'bdthemes-element-pack'),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'badge_horizontal_offset',
			[
				'label' => __('Horizontal Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -300,
						'step' => 1,
						'max'  => 300,
					],
				],
				'condition' => [
					'badge_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-badge-h-offset: {{SIZE}}px;'
				],
			]
		);

		$this->add_responsive_control(
			'badge_vertical_offset',
			[
				'label' => __('Vertical Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -300,
						'step' => 1,
						'max'  => 300,
					],
				],
				'condition' => [
					'badge_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-badge-v-offset: {{SIZE}}px;'
				],
			]
		);

		$this->add_responsive_control(
			'badge_rotate',
			[
				'label'   => esc_html__('Rotate', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -360,
						'max'  => 360,
						'step' => 5,
					],
				],
				'condition' => [
					'badge_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-badge-rotate: {{SIZE}}deg;'
				],
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon_box',
			[
				'label'      => __('Icon/Image', 'bdthemes-element-pack'),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'selected_icon[value]',
							'operator' => '!=',
							'value'    => ''
						],
						[
							'name'     => 'image[url]',
							'operator' => '!=',
							'value'    => ''
						],
					]
				]
			]
		);

		$this->start_controls_tabs('icon_colors');

		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => __('Normal', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'glassmorphism_effect',
			[
				'label' => esc_html__('Glassmorphism', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
				// translators: %1s and %2s are the opening and closing anchor tags for the Firefox browser compatibility documentation link.
				'description' => sprintf(__('This feature will not work in the Firefox browser untill you enable browser compatibility so please %1$s look here %2$s', 'bdthemes-element-pack'), '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/backdrop-filter#Browser_compatibility" target="_blank">', '</a>'),
				'condition' => [
					'_skin' => '',
					'fancy_card_icon_position' => ['left', 'right']
				],

			]
		);

		$this->add_control(
			'glassmorphism_blur_level',
			[
				'label'       => __('Blur Level', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
					]
				],
				'default'     => [
					'size' => 5
				],
				'selectors'   => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'
				],
				'condition' => [
					'_skin' => '',
					'glassmorphism_effect' => 'yes',
					'fancy_card_icon_position' => ['left', 'right']
				]
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label'     => __('Primary Background', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon' => 'background: {{VALUE}};',
				],
				'condition' => [
					'_skin!' => 'batty',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'icon_primary_background',
				'label'     => __('Primary Background', 'bdthemes-element-pack'),
				'types'     => ['gradient'],
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-batty .bdt-batty-face.bdt-batty-face2',
				'condition' => [
					'_skin' => 'batty',
				],
			]
		);

		$this->add_responsive_control(
			'icon_primary_radius',
			[
				'label'      => esc_html__('Border Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_skin!' => 'batty',
				],
			]
		);

		$this->add_control(
			'icon_heading',
			[
				'label'     => __('Icon/Image', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __('Icon Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'icon_type!' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'icon_background',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner',

			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label'      => esc_html__('Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'icon_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner'
			]
		);

		$this->add_responsive_control(
			'icon_radius',
			[
				'label'      => esc_html__('Border Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'separator'  => 'after',
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
				'condition' => [
					'icon_radius_advanced_show!' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_radius_advanced_show',
			[
				'label' => __('Advanced Radius', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_responsive_control(
			'icon_radius_advanced',
			[
				'label'       => esc_html__('Radius', 'bdthemes-element-pack'),
				// translators: %1s is an example radius value in bold, %2s is a link to a border-radius generator site.
				'description' => sprintf(__('For example: <b>%1$s</b> or Go <a href="%2$s" target="_blank">this link</a> and copy and paste the radius value.', 'bdthemes-element-pack'), '75% 25% 43% 57% / 46% 29% 71% 54%', 'https://9elements.github.io/fancy-border-radius/'),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'size_units'  => ['px', '%'],
				'separator'   => 'after',
				'default'     => '75% 25% 43% 57% / 46% 29% 71% 54%',
				'selectors'   => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner'     => 'border-radius: {{VALUE}}; overflow: hidden;',
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner img' => 'border-radius: {{VALUE}}; overflow: hidden;'
				],
				'condition' => [
					'icon_radius_advanced_show' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_shadow',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'icon_typography',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner',
				'condition' => [
					'icon_type!' => 'image',
				],
			]
		);

		$this->add_control(
			'image_fullwidth',
			[
				'label' => __('Image Fullwidth', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner' => 'width: 100%; box-sizing: border-box;',
				],
				'condition' => [
					'icon_type' => 'image'
				]
			]
		);

		$this->add_responsive_control(
			'card_image_size',
			[
				'label'   => __('Image Size', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_fullwidth' => '',
					'icon_type' => 'image'
				]
			]
		);

		$this->add_control(
			'rotate',
			[
				'label'   => __('Rotate', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'max'  => 360,
						'min'  => -360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner i, {{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner svg, {{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner img' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'icon_background_rotate',
			[
				'label'   => __('Background Rotate', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'max'  => 360,
						'min'  => -360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon-inner' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'image_icon_heading',
			[
				'label'     => __('Image Effect', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'css_filters',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card img',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => __('Opacity', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card img' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label' => __('Transition Duration', 'bdthemes-element-pack'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card img' => 'transition-duration: {{SIZE}}s',
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'label_heading',
			[
				'label'     => __('Label', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'_skin!' => ['batty']
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_skin!' => ['batty']
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'label_typography',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-icon:before',
				'condition' => [
					'_skin!' => ['batty']
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover',
			[
				'label' => __('Hover', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'icon_primary_hover_color',
			[
				'label'     => __('Primary Background', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon' => 'background: {{VALUE}};',
				],
				'condition' => [
					'_skin!' => 'batty',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'icon_hover_primary_background',
				'label'     => __('Primary Background', 'bdthemes-element-pack'),
				'types'     => ['gradient'],
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-batty:hover .bdt-batty-face.bdt-batty-face2',
				'condition' => [
					'_skin' => 'batty',
				],
			]
		);

		$this->add_responsive_control(
			'icon_primary_hover_radius',
			[
				'label'      => esc_html__('Border Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_skin!' => 'batty',
				],
			]
		);

		$this->add_control(
			'icon_hover_heading',
			[
				'label'     => __('Icon/Image', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => __('Icon Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'icon_type!' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'icon_hover_background',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner',
			]
		);

		$this->add_control(
			'icon_hover_border_color',
			[
				'label'     => __('Border Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner'  => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'icon_border_border!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_hover_radius',
			[
				'label'      => esc_html__('Border Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'separator'  => 'after',
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner, {{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_hover_shadow',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'icon_hover_typography',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner',
				'condition' => [
					'icon_type!' => 'image',
					'_skin'		 => 'batty',
				],
			]
		);

		$this->add_control(
			'icon_hover_rotate',
			[
				'label'   => __('Rotate', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'max'  => 360,
						'min'  => -360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner i, {{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner svg, {{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner img'   => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'icon_hover_background_rotate',
			[
				'label'   => __('Background Rotate', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'max'  => 360,
						'min'  => -360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'image_icon_hover_heading',
			[
				'label'     => __('Image Effect', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'css_filters_hover',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner img',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'image_opacity_hover',
			[
				'label' => __('Opacity', 'bdthemes-element-pack'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon-inner img' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'label_hover_heading',
			[
				'label'     => __('Label', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'_skin!' => 'batty'
				],
			]
		);

		$this->add_control(
			'label_hover_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_skin!' => 'batty'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'label_hover_typography',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-icon:before',
				'condition' => [
					'_skin!' => 'batty'
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __('Title', 'bdthemes-element-pack'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_title_style');

		$this->start_controls_tab(
			'tab_title_style_normal',
			[
				'label' => __('Normal', 'bdthemes-element-pack'),
				'condition' => [
					'_skin!' => 'batty',
				]
			]
		);

		$this->add_responsive_control(
			'title_bottom_space',
			[
				'label' => __('Spacing', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-content .bdt-ep-fancy-card-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card-content .bdt-ep-fancy-card-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_style_hover',
			[
				'label' => __('Hover', 'bdthemes-element-pack'),
				'condition' => [
					'_skin!' => 'batty',
				]


			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-content .bdt-ep-fancy-card-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_skin!' => 'batty',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography_hover',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-content .bdt-ep-fancy-card-title',
				'condition' => [
					'_skin!' => 'batty',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			[
				'label' => __('Text', 'bdthemes-element-pack'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_description_style');

		$this->start_controls_tab(
			'tab_description_style_normal',
			[
				'label' => __('Normal', 'bdthemes-element-pack'),
				'condition' => [
					'_skin!' => 'batty'
				]

			]
		);

		$this->add_responsive_control(
			'description_bottom_space',
			[
				'label'     => esc_html__('Spacing', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-content .bdt-ep-fancy-card-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-content .bdt-ep-fancy-card-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card-content .bdt-ep-fancy-card-text',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_description_style_hover',
			[
				'label' => __('Hover', 'bdthemes-element-pack'),
				'condition' => [
					'_skin!' => 'batty'
				]
			]
		);

		$this->add_control(
			'description_color_hover',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-content .bdt-ep-fancy-card-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_skin!' => 'batty',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography_hover',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card:hover .bdt-ep-fancy-card-content .bdt-ep-fancy-card-text',
				'condition' => [
					'_skin!' => 'batty',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_readmore',
			[
				'label'     => __('Read More', 'bdthemes-element-pack'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'readmore'       => 'yes',
				],
			]
		);

		$this->add_control(
			'readmore_attention',
			[
				'label' => __('Attention', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_responsive_control(
			'readmore_alignment',
			[
				'label' => __('Alignment', 'bdthemes-element-pack'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'bdthemes-element-pack'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'bdthemes-element-pack'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'bdthemes-element-pack'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-readmore-wrap' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs('tabs_readmore_style');

		$this->start_controls_tab(
			'tab_readmore_normal',
			[
				'label' => __('Normal', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'readmore_text_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'readmore_background',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'readmore_border',
				'separator'   => 'before',
				'selector'    => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore'
			]
		);

		$this->add_responsive_control(
			'readmore_radius',
			[
				'label'      => __('Border Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'separator'  => 'after',
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'readmore_shadow',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore',
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label'      => __('Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'readmore_typography',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_readmore_hover',
			[
				'label' => __('Hover', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'readmore_hover_text_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'readmore_hover_background',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore:hover',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'readmore_hover_border_color',
			[
				'label'     => __('Border Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'readmore_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'readmore_hover_shadow',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content .bdt-ep-fancy-card-readmore:hover',
			]
		);

		$this->add_control(
			'readmore_hover_animation',
			[
				'label' => __('Hover Animation', 'bdthemes-element-pack'),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_toggle',
			[
				'label'     => __('Toggle Icon', 'bdthemes-element-pack'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin'       => 'climax',
				],
			]
		);

		$this->add_control(
			'icon_active_background_color',
			[
				'label'     => __('Background Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax input:checked~.bdt-ep-fancy-card-toggole' => 'box-shadow: 0 0 0 1920px {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax .bdt-ep-fancy-card-toggole' => 'box-shadow: 0 0 0 0 {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs('tabs_toggle_style');

		$this->start_controls_tab(
			'tab_toggle_normal',
			[
				'label' => __('Normal', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'toggle_icon_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax .bdt-ep-fancy-card-toggole' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax .bdt-ep-fancy-card-toggole svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'toggle_background',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax .bdt-ep-fancy-card-toggole',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'toggle_border',
				'selector'    => '{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax .bdt-ep-fancy-card-toggole'
			]
		);

		$this->add_responsive_control(
			'toggle_radius',
			[
				'label'      => __('Border Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax .bdt-ep-fancy-card-toggole' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_padding',
			[
				'label' => __('Padding', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 20,
						'step' => 1,
						'max'  => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax input[type="checkbox"], {{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax .bdt-ep-fancy-card-toggole' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toggle_typography',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax .bdt-ep-fancy-card-toggole',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_active',
			[
				'label' => __('Active', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'toggle_active_text_color',
			[
				'label'     => __('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax input:checked~.bdt-ep-fancy-card-toggole' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax input:checked~.bdt-ep-fancy-card-toggole svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_active_shadow_color',
			[
				'label'     => __('Toggle Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax .bdt-ep-fancy-card-toggole' => 'box-shadow: 0 0 0 0 {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax input:checked~.bdt-ep-fancy-card-toggole' => 'box-shadow: 0 0 0 1920px {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'toggle_active_background',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax input:checked~.bdt-ep-fancy-card-toggole',
			]
		);

		$this->add_control(
			'toggle_active_border_color',
			[
				'label'     => __('Border Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-climax input:checked~.bdt-ep-fancy-card-toggole' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'toggle_border_border!' => ''
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_indicator',
			[
				'label'     => __('Indicator', 'bdthemes-element-pack'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'indicator_style',
			[
				'label'   => __('Indicator Style', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1'   => __('Style 1', 'bdthemes-element-pack'),
					'2'   => __('Style 2', 'bdthemes-element-pack'),
					'3'   => __('Style 3', 'bdthemes-element-pack'),
					'4'   => __('Style 4', 'bdthemes-element-pack'),
					'5'   => __('Style 5', 'bdthemes-element-pack'),
				],
			]
		);

		$this->add_control(
			'indicator_fill_color',
			[
				'label'     => __('Fill Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-indicator-svg svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'indicator_stroke_color',
			[
				'label'     => __('Stroke Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-indicator-svg svg' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_badge',
			[
				'label'     => __('Badge', 'bdthemes-element-pack'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_text_color',
			[
				'label'     => __('Text Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card-badge span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'badge_background',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card-badge span',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'badge_border',
				'placeholder' => '1px',
				'separator'   => 'before',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-ep-fancy-card-badge span'
			]
		);

		$this->add_responsive_control(
			'badge_radius',
			[
				'label'      => __('Border Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'separator'  => 'after',
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card-badge span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'badge_shadow',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card-badge span',
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label'      => __('Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card-badge span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card-badge span',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_additional',
			[
				'label' => __('Additional', 'bdthemes-element-pack'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_heading',
			[
				'label'     => __('Inner Item', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs('tabs_content_style');

		$this->start_controls_tab(
			'tab_content_normal',
			[
				'label' => __('Normal', 'bdthemes-element-pack'),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'content_background',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card',
				'condition' => [
					'_skin' => ['', 'flux'],
				],
			]
		);

		$this->add_control(
			'skin_content_background',
			[
				'label'     => __('Background Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-stack .bdt-ep-fancy-card-content-overlay:before'  => 'background: {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-fancy-card.bdt-ep-fancy-card-batty'  => 'background: {{VALUE}} !important;',
				],
				'condition' => [
					'_skin!' => ['', 'climax', 'flux'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'content_border',
				'selector'    => '{{WRAPPER}} .bdt-ep-fancy-card'
			]
		);

		$this->add_responsive_control(
			'content_radius',
			[
				'label'      => __('Border Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'content_hover_shadow',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card, {{WRAPPER}} .bdt-ep-fancy-card-batty',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_hover',
			[
				'label' => __('Hover', 'bdthemes-element-pack'),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'content_hover_background',
				'selector'  => '{{WRAPPER}} .bdt-ep-fancy-card:hover',
				'condition' => [
					'_skin' => ['', 'flux'],
				],
			]
		);

		$this->add_control(
			'content_hover_border_color',
			[
				'label'     => __('Border Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-fancy-card:hover'  => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'content_border_border!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'content_shadow',
				'selector' => '{{WRAPPER}} .bdt-ep-fancy-card:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__('Content Inner Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-fancy-card .bdt-ep-fancy-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Renders the icon or image for the fancy card.
	 *
	 * @param array $settings Optional. Widget settings. Fetched via get_settings_for_display() if not passed.
	 */
	public function render_icon( $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$icon_type = isset( $settings['icon_type'] ) ? $settings['icon_type'] : 'icon';
		$has_icon  = ( 'icon' === $icon_type && ! empty( $settings['selected_icon']['value'] ) );
		$has_image = ( 'image' === $icon_type && ! empty( $settings['image']['url'] ) );

		if ( ! $has_icon && ! $has_image ) {
			return;
		}

		$data_label = ! empty( $settings['show_data_label'] ) && $settings['show_data_label'] === 'yes'
			? $settings['title_text']
			: '';
		?>
		<div class="bdt-ep-fancy-card-icon" data-label="<?php echo esc_attr( $data_label ); ?>">
			<span class="bdt-ep-fancy-card-icon-inner">
				<?php
				if ( $has_icon ) {
					Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				} elseif ( $has_image && ! empty( $settings['image']['id'] ) ) {
					$thumb_url = Group_Control_Image_Size::get_attachment_image_src(
						$settings['image']['id'],
						'thumbnail',
						$settings
					);
					$size      = isset( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'full';
					$alt       = isset( $settings['title_text'] ) ? $settings['title_text'] : '';
					if ( $thumb_url ) {
						echo wp_get_attachment_image(
							$settings['image']['id'],
							$size,
							false,
							[ 'alt' => esc_attr( $alt ) ]
						);
					} else {
						printf(
							'<img src="%1$s" alt="%2$s">',
							esc_url( $settings['image']['url'] ),
							esc_attr( $alt )
						);
					}
				} else {
					printf(
						'<img src="%1$s" alt="%2$s">',
						esc_url( $settings['image']['url'] ),
						esc_attr( isset( $settings['title_text'] ) ? $settings['title_text'] : '' )
					);
				}
				?>
			</span>
		</div>
		<?php
	}

	/**
	 * Renders the card title.
	 *
	 * @param array $settings Optional. Widget settings. Fetched via get_settings_for_display() if not passed.
	 */
	public function render_title( $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$this->add_inline_editing_attributes( 'title_text', 'none' );
		$this->add_render_attribute( 'fancy-card-title', 'class', 'bdt-ep-fancy-card-title' );

		if ( ! empty( $settings['title_link'] ) && $settings['title_link'] === 'yes'
			&& ! empty( $settings['title_link_url']['url'] ) ) {
			$target = ( ! empty( $settings['title_link_url']['is_external'] ) && $settings['title_link_url']['is_external'] )
				? '_blank'
				: '_self';
			$this->add_render_attribute(
				'fancy-card-title',
				'onclick',
				"window.open('" . esc_url( $settings['title_link_url']['url'] ) . "', '" . esc_attr( $target ) . "')"
			);
		}

		if ( empty( $settings['title_text'] ) ) {
			return;
		}

		$title_tag = Utils::get_valid_html_tag( isset( $settings['title_size'] ) ? $settings['title_size'] : 'h3' );
		?>
		<<?php echo esc_attr( $title_tag ); ?> <?php $this->print_render_attribute_string( 'fancy-card-title' ); ?>>
			<span <?php $this->print_render_attribute_string( 'title_text' ); ?>>
				<?php echo wp_kses( $settings['title_text'], element_pack_allow_tags( 'title' ) ); ?>
			</span>
		</<?php echo esc_attr( $title_tag ); ?>>
		<?php
	}

	/**
	 * Renders the card description text.
	 *
	 * @param array $settings Optional. Widget settings. Fetched via get_settings_for_display() if not passed.
	 */
	public function render_text( $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$this->add_render_attribute( 'description_text', 'class', 'bdt-ep-fancy-card-text' );
		$this->add_inline_editing_attributes( 'description_text' );

		if ( empty( $settings['description_text'] ) ) {
			return;
		}
		?>
		<div <?php $this->print_render_attribute_string( 'description_text' ); ?>>
			<?php echo wp_kses( $settings['description_text'], element_pack_allow_tags( 'text' ) ); ?>
		</div>
		<?php
	}

	/**
	 * Renders the read more button.
	 *
	 * @param array $settings Optional. Widget settings. Fetched via get_settings_for_display() if not passed.
	 */
	public function render_readmore( $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		if ( empty( $settings['readmore'] ) || $settings['readmore'] !== 'yes' ) {
			return;
		}

		$this->add_render_attribute( 'readmore', 'class', [ 'bdt-ep-fancy-card-readmore' ] );

		if ( ! empty( $settings['readmore_link']['url'] ) ) {
			$this->add_link_attributes( 'readmore', $settings['readmore_link'] );
		}

		if ( ! empty( $settings['readmore_attention'] ) && $settings['readmore_attention'] === 'yes' ) {
			$this->add_render_attribute( 'readmore', 'class', 'bdt-ep-attention-button' );
		}

		if ( ! empty( $settings['readmore_hover_animation'] ) ) {
			$this->add_render_attribute(
				'readmore',
				'class',
				'elementor-animation-' . sanitize_html_class( $settings['readmore_hover_animation'] )
			);
		}
		$icon       = $settings['advanced_readmore_icon'] ?? [];
		$has_icon   = ! empty( $icon['library'] );
		$icon_align = $settings['readmore_icon_align'] ?? 'right';
		$read_label = isset( $settings['readmore_text'] ) ? $settings['readmore_text'] : __( 'Read More', 'bdthemes-element-pack' );
		?>
		<div class="bdt-ep-fancy-card-readmore-wrap">
			<a <?php $this->print_render_attribute_string( 'readmore' ); ?>>
				<?php if ( $has_icon && 'left' === $icon_align ) : ?>
					<?php Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true', 'class' => 'fa-fw' ] ); ?>
				<?php endif; ?>
				<span class="bdt-ep-fancy-card-readmore-text">
					<?php echo esc_html( $read_label ); ?>
				</span>
				<?php if ( $has_icon && 'right' === $icon_align ) : ?>
					<?php Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true', 'class' => 'fa-fw' ] ); ?>
				<?php endif; ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Renders the indicator SVG.
	 *
	 * @param array $settings Optional. Widget settings. Fetched via get_settings_for_display() if not passed.
	 */
	public function render_indicator( $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		if ( empty( $settings['indicator'] ) || $settings['indicator'] !== 'yes' ) {
			return;
		}

		$style = isset( $settings['indicator_style'] ) ? $settings['indicator_style'] : '1';
		?>
		<div class="bdt-indicator-svg bdt-svg-style-<?php echo esc_attr( $style ); ?>">
			<?php echo wp_kses( element_pack_svg_icon( 'arrow-' . $style ), element_pack_allow_tags( 'svg' ) ); ?>
		</div>
		<?php
	}

	/**
	 * Renders the badge.
	 *
	 * @param array $settings Optional. Widget settings. Fetched via get_settings_for_display() if not passed.
	 */
	public function render_badge( $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		if ( empty( $settings['badge'] ) || $settings['badge'] !== 'yes' || empty( $settings['badge_text'] ) ) {
			return;
		}

		$position = isset( $settings['badge_position'] ) ? $settings['badge_position'] : 'top-right';
		?>
		<div class="bdt-ep-fancy-card-badge bdt-position-<?php echo esc_attr( $position ); ?>">
			<span class="bdt-badge bdt-padding-small"><?php echo esc_html( $settings['badge_text'] ); ?></span>
		</div>
		<?php
	}

	/**
	 * Renders the widget output (default skin).
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		// Global link (entire card clickable).
		if ( ! empty( $settings['global_link'] ) && $settings['global_link'] === 'yes'
			&& ! empty( $settings['global_link_url']['url'] ) ) {
			$target = ( ! empty( $settings['global_link_url']['is_external'] ) && $settings['global_link_url']['is_external'] )
				? '_blank'
				: '_self';
			$this->add_render_attribute(
				'fancy-card',
				'onclick',
				"window.open('" . esc_url( $settings['global_link_url']['url'] ) . "', '" . esc_attr( $target ) . "')"
			);
		}

		// Base and icon position classes.
		$icon_position = isset( $settings['fancy_card_icon_position'] ) ? $settings['fancy_card_icon_position'] : '';
		$position_map  = [
			'left'   => 'bdt-ep-fancy-card bdt-ep-fancy-card-default bdt-ep-fancy-card-icon-left',
			'right'  => 'bdt-ep-fancy-card bdt-ep-fancy-card-default bdt-ep-fancy-card-icon-right',
			''       => 'bdt-ep-fancy-card bdt-ep-fancy-card-default bdt-ep-fancy-card-icon-default',
		];
		$card_classes  = isset( $position_map[ $icon_position ] )
			? $position_map[ $icon_position ]
			: 'bdt-ep-fancy-card bdt-ep-fancy-card-default';
		$this->add_render_attribute( 'fancy-card', 'class', $card_classes );
		?>
		<div <?php $this->print_render_attribute_string( 'fancy-card' ); ?>>
			<?php $this->render_icon( $settings ); ?>
			<div class="bdt-ep-fancy-card-content">
				<?php $this->render_title( $settings ); ?>
				<?php $this->render_text( $settings ); ?>
				<?php $this->render_readmore( $settings ); ?>
			</div>
		</div>
		<?php
		$this->render_indicator( $settings );
		$this->render_badge( $settings );
	}

	protected function content_template() {
		?>
		<#
		var skinId = settings._skin || '';
		var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
		var iconType = settings.icon_type ? settings.icon_type : 'icon';
		var hasIcon = 'icon' === iconType && settings.selected_icon && settings.selected_icon.value;
		var hasImage = 'image' === iconType && settings.image && settings.image.url;

		var iconPosition = settings.fancy_card_icon_position ? settings.fancy_card_icon_position : '';
		var cardClasses = 'bdt-ep-fancy-card bdt-ep-fancy-card-default';
		if ( 'left' === iconPosition ) {
			cardClasses += ' bdt-ep-fancy-card-icon-left';
		} else if ( 'right' === iconPosition ) {
			cardClasses += ' bdt-ep-fancy-card-icon-right';
		} else {
			cardClasses += ' bdt-ep-fancy-card-icon-default';
		}

		var stackStyle = settings.fancy_card_icon_style || 'style1';
		var stackClassMap = {
			style1: 'bdt-ep-fancy-card bdt-ep-fancy-card-stack bdt-icon-style1',
			style2: 'bdt-ep-fancy-card bdt-ep-fancy-card-stack bdt-icon-style2',
			style3: 'bdt-ep-fancy-card bdt-ep-fancy-card-stack bdt-icon-style3',
			style4: 'bdt-ep-fancy-card bdt-ep-fancy-card-stack bdt-icon-style4'
		};
		var stackClasses = stackClassMap[ stackStyle ] || 'bdt-ep-fancy-card bdt-ep-fancy-card-stack';

		var titleTag = settings.title_size ? settings.title_size : 'h3';
		var dataLabel = ( settings.show_data_label === 'yes' ) ? settings.title_text : '';

		var readmoreClasses = 'bdt-ep-fancy-card-readmore';
		if ( settings.readmore_attention === 'yes' ) {
			readmoreClasses += ' bdt-ep-attention-button';
		}
		if ( settings.readmore_hover_animation ) {
			readmoreClasses += ' elementor-animation-' + settings.readmore_hover_animation;
		}

		var readmoreIconHTML = elementor.helpers.renderIcon( view, settings.advanced_readmore_icon, { 'aria-hidden': true, 'class': 'fa-fw' }, 'i', 'object' );
		var readmoreHasIcon = settings.advanced_readmore_icon && settings.advanced_readmore_icon.library;
		var readmoreIconAlign = settings.readmore_icon_align ? settings.readmore_icon_align : 'right';
		var readLabel = settings.readmore_text ? settings.readmore_text : 'Read More';
		var readmoreHref = ( settings.readmore_link && settings.readmore_link.url ) ? settings.readmore_link.url : '#';

		var badgePosition = settings.badge_position ? settings.badge_position : 'top-right';
		var indicatorStyle = settings.indicator_style ? settings.indicator_style : '1';

		var toggleIconHTML = elementor.helpers.renderIcon( view, settings.toggle_icon, { 'aria-hidden': true }, 'i', 'object' );
		var togglePos = settings.toggle_position ? settings.toggle_position : 'bottom-right';
		#>
		<# if ( skinId === 'batty' ) { #>
		<div class="bdt-ep-fancy-card bdt-ep-fancy-card-batty"<# if ( settings.global_link === 'yes' && settings.global_link_url && settings.global_link_url.url ) {
			var gT = settings.global_link_url.is_external ? '_blank' : '_self';
		#> onclick="window.open('{{ settings.global_link_url.url }}', '{{ gT }}')"<# } #>>
			<div class="bdt-ep-fancy-card-face bdt-ep-fancy-card-face1">
				<div class="bdt-ep-fancy-card-content">
					<# if ( settings.title_text ) { #>
					<{{ titleTag }} class="bdt-ep-fancy-card-title"<# if ( settings.title_link === 'yes' && settings.title_link_url && settings.title_link_url.url ) { var tt = settings.title_link_url.is_external ? '_blank' : '_self'; #> onclick="window.open('{{ settings.title_link_url.url }}', '{{ tt }}')"<# } #>>
						<span>{{{ settings.title_text }}}</span>
					</{{ titleTag }}>
					<# } #>
					<# if ( settings.description_text ) { #>
					<div class="bdt-ep-fancy-card-text">{{{ settings.description_text }}}</div>
					<# } #>
					<# if ( 'yes' === settings.readmore ) { #>
					<div class="bdt-ep-fancy-card-readmore-wrap">
						<a class="{{ readmoreClasses }}" href="{{ readmoreHref }}"<# if ( settings.readmore_link && settings.readmore_link.is_external ) { #> target="_blank"<# } #><# if ( settings.readmore_link && settings.readmore_link.nofollow ) { #> rel="nofollow"<# } #>>
							<# if ( readmoreHasIcon && 'left' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
							<span class="bdt-ep-fancy-card-readmore-text">{{ readLabel }}</span>
							<# if ( readmoreHasIcon && 'right' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
						</a>
					</div>
					<# } #>
				</div>
			</div>
			<div class="bdt-ep-fancy-card-face bdt-ep-fancy-card-face2">
				<# if ( hasIcon ) { #>
				<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner">{{{ iconHTML.value }}}</span></div>
				<# } else if ( hasImage ) { #>
				<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner"><img src="{{ settings.image.url }}" alt="{{ settings.title_text }}"></span></div>
				<# } #>
			</div>
		</div>
		<# } else if ( skinId === 'climax' ) { #>
		<div class="bdt-ep-fancy-card bdt-ep-fancy-card-climax"<# if ( settings.global_link === 'yes' && settings.global_link_url && settings.global_link_url.url ) {
			var gT2 = settings.global_link_url.is_external ? '_blank' : '_self';
		#> onclick="window.open('{{ settings.global_link_url.url }}', '{{ gT2 }}')"<# } #>>
			<input class="bdt-checkbox bdt-position-{{ togglePos }}" type="checkbox">
			<div class="bdt-ep-fancy-card-toggole bdt-position-{{ togglePos }}">
				<# if ( toggleIconHTML && toggleIconHTML.rendered && settings.toggle_icon && settings.toggle_icon.value ) { #>{{{ toggleIconHTML.value }}}<# } #>
			</div>
			<# if ( hasIcon ) { #>
			<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner">{{{ iconHTML.value }}}</span></div>
			<# } else if ( hasImage ) { #>
			<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner"><img src="{{ settings.image.url }}" alt="{{ settings.title_text }}"></span></div>
			<# } #>
			<div class="bdt-ep-fancy-card-content">
				<# if ( settings.title_text ) { #>
				<{{ titleTag }} class="bdt-ep-fancy-card-title"<# if ( settings.title_link === 'yes' && settings.title_link_url && settings.title_link_url.url ) { var tt2 = settings.title_link_url.is_external ? '_blank' : '_self'; #> onclick="window.open('{{ settings.title_link_url.url }}', '{{ tt2 }}')"<# } #>>
					<span>{{{ settings.title_text }}}</span>
				</{{ titleTag }}>
				<# } #>
				<# if ( settings.description_text ) { #>
				<div class="bdt-ep-fancy-card-text">{{{ settings.description_text }}}</div>
				<# } #>
				<# if ( 'yes' === settings.readmore ) { #>
				<div class="bdt-ep-fancy-card-readmore-wrap">
					<a class="{{ readmoreClasses }}" href="{{ readmoreHref }}"<# if ( settings.readmore_link && settings.readmore_link.is_external ) { #> target="_blank"<# } #><# if ( settings.readmore_link && settings.readmore_link.nofollow ) { #> rel="nofollow"<# } #>>
						<# if ( readmoreHasIcon && 'left' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
						<span class="bdt-ep-fancy-card-readmore-text">{{ readLabel }}</span>
						<# if ( readmoreHasIcon && 'right' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
					</a>
				</div>
				<# } #>
			</div>
		</div>
		<# } else if ( skinId === 'stack' ) { #>
		<div class="{{ stackClasses }}"<# if ( settings.global_link === 'yes' && settings.global_link_url && settings.global_link_url.url ) {
			var gT3 = settings.global_link_url.is_external ? '_blank' : '_self';
		#> onclick="window.open('{{ settings.global_link_url.url }}', '{{ gT3 }}')"<# } #>>
			<# if ( hasIcon ) { #>
			<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner">{{{ iconHTML.value }}}</span></div>
			<# } else if ( hasImage ) { #>
			<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner"><img src="{{ settings.image.url }}" alt="{{ settings.title_text }}"></span></div>
			<# } #>
			<div class="bdt-ep-fancy-card-content-overlay"></div>
			<div class="bdt-ep-fancy-card-content">
				<# if ( settings.title_text ) { #>
				<{{ titleTag }} class="bdt-ep-fancy-card-title"<# if ( settings.title_link === 'yes' && settings.title_link_url && settings.title_link_url.url ) { var tt3 = settings.title_link_url.is_external ? '_blank' : '_self'; #> onclick="window.open('{{ settings.title_link_url.url }}', '{{ tt3 }}')"<# } #>>
					<span>{{{ settings.title_text }}}</span>
				</{{ titleTag }}>
				<# } #>
				<# if ( settings.description_text ) { #>
				<div class="bdt-ep-fancy-card-text">{{{ settings.description_text }}}</div>
				<# } #>
				<# if ( 'yes' === settings.readmore ) { #>
				<div class="bdt-ep-fancy-card-readmore-wrap">
					<a class="{{ readmoreClasses }}" href="{{ readmoreHref }}"<# if ( settings.readmore_link && settings.readmore_link.is_external ) { #> target="_blank"<# } #><# if ( settings.readmore_link && settings.readmore_link.nofollow ) { #> rel="nofollow"<# } #>>
						<# if ( readmoreHasIcon && 'left' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
						<span class="bdt-ep-fancy-card-readmore-text">{{ readLabel }}</span>
						<# if ( readmoreHasIcon && 'right' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
					</a>
				</div>
				<# } #>
			</div>
		</div>
		<# } else if ( skinId === 'flux' ) { #>
		<div class="bdt-ep-fancy-card bdt-ep-fancy-card-flux"<# if ( settings.global_link === 'yes' && settings.global_link_url && settings.global_link_url.url ) {
			var gT4 = settings.global_link_url.is_external ? '_blank' : '_self';
		#> onclick="window.open('{{ settings.global_link_url.url }}', '{{ gT4 }}')"<# } #>>
			<# if ( hasIcon ) { #>
			<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner">{{{ iconHTML.value }}}</span></div>
			<# } else if ( hasImage ) { #>
			<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner"><img src="{{ settings.image.url }}" alt="{{ settings.title_text }}"></span></div>
			<# } #>
			<div class="bdt-ep-fancy-card-content">
				<# if ( settings.title_text ) { #>
				<{{ titleTag }} class="bdt-ep-fancy-card-title"<# if ( settings.title_link === 'yes' && settings.title_link_url && settings.title_link_url.url ) { var tt4 = settings.title_link_url.is_external ? '_blank' : '_self'; #> onclick="window.open('{{ settings.title_link_url.url }}', '{{ tt4 }}')"<# } #>>
					<span>{{{ settings.title_text }}}</span>
				</{{ titleTag }}>
				<# } #>
				<# if ( settings.description_text ) { #>
				<div class="bdt-ep-fancy-card-text">{{{ settings.description_text }}}</div>
				<# } #>
				<# if ( 'yes' === settings.readmore ) { #>
				<div class="bdt-ep-fancy-card-readmore-wrap">
					<a class="{{ readmoreClasses }}" href="{{ readmoreHref }}"<# if ( settings.readmore_link && settings.readmore_link.is_external ) { #> target="_blank"<# } #><# if ( settings.readmore_link && settings.readmore_link.nofollow ) { #> rel="nofollow"<# } #>>
						<# if ( readmoreHasIcon && 'left' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
						<span class="bdt-ep-fancy-card-readmore-text">{{ readLabel }}</span>
						<# if ( readmoreHasIcon && 'right' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
					</a>
				</div>
				<# } #>
			</div>
		</div>
		<# } else { #>
		<div class="{{ cardClasses }}"<# if ( settings.global_link === 'yes' && settings.global_link_url && settings.global_link_url.url ) {
			var gT5 = settings.global_link_url.is_external ? '_blank' : '_self';
		#> onclick="window.open('{{ settings.global_link_url.url }}', '{{ gT5 }}')"<# } #>>
			<# if ( hasIcon ) { #>
			<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner">{{{ iconHTML.value }}}</span></div>
			<# } else if ( hasImage ) { #>
			<div class="bdt-ep-fancy-card-icon" data-label="{{ dataLabel }}"><span class="bdt-ep-fancy-card-icon-inner"><img src="{{ settings.image.url }}" alt="{{ settings.title_text }}"></span></div>
			<# } #>
			<div class="bdt-ep-fancy-card-content">
				<# if ( settings.title_text ) { #>
				<{{ titleTag }} class="bdt-ep-fancy-card-title"<# if ( settings.title_link === 'yes' && settings.title_link_url && settings.title_link_url.url ) { var tt5 = settings.title_link_url.is_external ? '_blank' : '_self'; #> onclick="window.open('{{ settings.title_link_url.url }}', '{{ tt5 }}')"<# } #>>
					<span>{{{ settings.title_text }}}</span>
				</{{ titleTag }}>
				<# } #>
				<# if ( settings.description_text ) { #>
				<div class="bdt-ep-fancy-card-text">{{{ settings.description_text }}}</div>
				<# } #>
				<# if ( 'yes' === settings.readmore ) { #>
				<div class="bdt-ep-fancy-card-readmore-wrap">
					<a class="{{ readmoreClasses }}" href="{{ readmoreHref }}"<# if ( settings.readmore_link && settings.readmore_link.is_external ) { #> target="_blank"<# } #><# if ( settings.readmore_link && settings.readmore_link.nofollow ) { #> rel="nofollow"<# } #>>
						<# if ( readmoreHasIcon && 'left' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
						<span class="bdt-ep-fancy-card-readmore-text">{{ readLabel }}</span>
						<# if ( readmoreHasIcon && 'right' === readmoreIconAlign ) { #>{{{ readmoreIconHTML.value }}}<# } #>
					</a>
				</div>
				<# } #>
			</div>
		</div>
		<# } #>
		<# if ( 'yes' === settings.indicator ) { #>
		<div class="bdt-indicator-svg bdt-svg-style-{{ indicatorStyle }}">
			<?php echo wp_kses( element_pack_svg_icon( 'arrow-1' ), element_pack_allow_tags( 'svg' ) ); ?>
		</div>
		<# } #>
		<# if ( 'yes' === settings.badge && settings.badge_text ) { #>
		<div class="bdt-ep-fancy-card-badge bdt-position-{{ badgePosition }}">
			<span class="bdt-badge bdt-padding-small">{{ settings.badge_text }}</span>
		</div>
		<# } #>
		<?php
	}
}
