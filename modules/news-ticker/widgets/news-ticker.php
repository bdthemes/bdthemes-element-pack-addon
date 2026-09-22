<?php

namespace ElementPackAddon\Modules\NewsTicker\Widgets;

use ElementPack\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use ElementPack\Includes\Controls\GroupQuery\Group_Control_Query;
use ElementPack\Traits\Global_Widget_Controls;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class News Ticker
 */
class News_Ticker extends Module_Base {
	use Group_Control_Query;
	use Global_Widget_Controls;

	/**
	 * @var \WP_Query
	 */
	private $_query = null;

	public function get_name() {
		return 'bdt-news-ticker';
	}

	public function get_title() {
		return BDTEP . esc_html__( 'News Ticker', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'bdt-wi-news-ticker';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_keywords() {
		return [ 'news', 'ticker', 'report', 'message', 'information', 'blog', 'post ticker', 'newsticker' ];
	}

	public function get_style_depends() {
		return $this->ep_is_edit_mode() ? [ 'ep-addon-styles' ] : [ 'ep-news-ticker' ];
	}

	public function get_script_depends() {
		return $this->ep_is_edit_mode() ? [ 'ep-addon-scripts' ] : [ 'ep-news-ticker' ];
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/FmpFhNTR7uY';
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
			'news_ticker_custom_content',
			[
				'label' => esc_html__( 'Content Type', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'bdthemes-element-pack' ),
					'custom'  => esc_html__( 'Custom', 'bdthemes-element-pack' ),
				],
				'default' => 'dynamic',
			]
		);
		
		$this->add_control(
			'show_label',
			[ 
				'label'   => esc_html__( 'Label', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'news_label',
			[ 
				'label'       => esc_html__( 'Label', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => esc_html__( 'LATEST NEWS', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'LATEST NEWS', 'bdthemes-element-pack' ),
				'condition'   => [ 
					'show_label' => 'yes'
				]
			]
		);

		$this->add_control(
			'news_content',
			[ 
				'label'   => esc_html__( 'News Content', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [ 
					'title'   => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'excerpt' => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				],
				'condition' => [ 
					'news_ticker_custom_content!' => 'custom',
				],
			]
		);

		$this->add_control(
			'show_date',
			[ 
				'label'     => esc_html__( 'Date', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [ 
					'news_content' => 'title',
					'news_ticker_custom_content!' => 'custom',
				],
			]
		);

		$this->add_control(
			'date_reverse',
			[ 
				'label'     => esc_html__( 'Date Reverse', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [ 
					'show_date' => 'yes'
				],
			]
		);

		$this->add_control(
			'show_time',
			[ 
				'label'     => esc_html__( 'Time', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [ 
					'news_content' => 'title',
					'news_ticker_custom_content!' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'news_ticker_height',
			[ 
				'label'     => __( 'Height', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 
					'size' => 42,
				],
				'range'     => [ 
					'px' => [ 
						'min' => 25,
						'max' => 200,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-news-ticker' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'news_ticker_content_section',
			[
				'label' => esc_html__( 'Custom Content', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'news_ticker_custom_content' => 'custom'
				],
			]
		);

		$this->add_control(
			'news_ticker_content_list',
			[
				'label' => esc_html__( 'Items', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::REPEATER,
				'show_label' => false,
				'fields' => [
					[
						'name' => 'news_ticker_title',
						'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
						'type' => Controls_Manager::TEXTAREA,
						'default' => esc_html__( 'News Content' , 'bdthemes-element-pack' ),
						'label_block' => true,
						'dynamic' => [ 'active' => true ],
					],
					[
						'name' => 'news_ticker_link',
						'label' => esc_html__( 'Link', 'bdthemes-element-pack' ),
						'type' => Controls_Manager::URL,
						'placeholder' => 'https://www.example.com',
						'default' => [
							'url' => 'https://www.example.com',
							'is_external' => '',
							'nofollow' => '',
						],
						'label_block' => true,
						'dynamic' => [ 'active' => true ],
					],
				],
				'default' => [
					[
						'news_ticker_title' => esc_html__( 'News Content #1', 'bdthemes-element-pack' ),
						'news_ticker_link' => [
							'url' => 'https://www.example.com/',
							'is_external' => '',
							'nofollow' => '',
						],
					],
					[
						'news_ticker_title' => esc_html__( 'News Content #2', 'bdthemes-element-pack' ),
						'news_ticker_link' => [
							'url' => 'https://www.example.com/',
							'is_external' => '',
							'nofollow' => '',
						],
					],
					[
						'news_ticker_title' => esc_html__( 'News Content #3', 'bdthemes-element-pack' ),
						'news_ticker_link' => [
							'url' => 'https://www.example.com/',
							'is_external' => '',
							'nofollow' => '',
						],
					],
				],
				'condition' => [
					'news_ticker_custom_content' => 'custom'
				],
				'title_field' => '{{{ news_ticker_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_navigation',
			[ 
				'label' => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_navigation',
			[ 
				'label'   => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'play_pause',
			[ 
				'label' => esc_html__( 'Play/Pause Button', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'navigation_size',
			[ 
				'label'     => esc_html__( 'Navigation Size', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 
					'size' => 14,
				],
				'range'     => [ 
					'px' => [ 
						'min' => 3,
						'max' => 26,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [ 
					'show_navigation' => 'yes'
				]
			]
		);

		$this->end_controls_section();
		//New Query Builder Settings
		$this->start_controls_section(
			'section_post_query_builder',
			[ 
				'label' => __( 'Query', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
				'condition' => [
					 'news_ticker_custom_content!' => 'custom'
				],
			]
		);

		$this->register_query_builder_controls();

		$this->update_control(
			'posts_per_page',
			[ 
				'default' => 5,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_animation',
			[ 
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'slider_animations',
			[ 
				'label'   => esc_html__( 'Animations', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [ 
					'scroll'      => esc_html__( 'Scroll', 'bdthemes-element-pack' ),
					'slide-left'  => esc_html__( 'Slide Left', 'bdthemes-element-pack' ),
					'slide-up'    => esc_html__( 'Slide Up', 'bdthemes-element-pack' ),
					'slide-right' => esc_html__( 'Slide Right', 'bdthemes-element-pack' ),
					'slide-down'  => esc_html__( 'Slide Down', 'bdthemes-element-pack' ),
					'fade'        => esc_html__( 'Fade', 'bdthemes-element-pack' ),
					'typography'  => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'autoplay',
			[ 
				'label'   => esc_html__( 'Autoplay', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);


		$this->add_control(
			'autoplay_interval',
			[ 
				'label'     => esc_html__( 'Autoplay Interval', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => [ 
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[ 
				'label'   => esc_html__( 'Pause on Hover', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'speed',
			[ 
				'label'   => esc_html__( 'Animation Speed', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
			]
		);

		$this->add_control(
			'scroll_speed',
			[ 
				'label'     => __( 'Scroll Speed', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 
					'size' => 1,
				],
				'range'     => [ 
					'px' => [ 
						'min' => 1,
						'max' => 10,
					],
				],
				'condition' => [ 
					'slider_animations' => 'scroll',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_news_ticker',
			[ 
				'label' => esc_html__( 'News Ticker', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_label',
			[ 
				'label'     => esc_html__( 'Label', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [ 
					'show_label' => 'yes'
				]
			]
		);

		$this->add_control(
			'label_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-label-inner' => 'color: {{VALUE}};',
				],
				'condition' => [ 
					'show_label' => 'yes'
				]
			]
		);

		$border_side = is_rtl() ? 'right' : 'left';

		$this->add_control(
			'label_background',
			[ 
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-label'       => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-label:after' => 'border-' . $border_side . '-color: {{VALUE}};',
				],
				'condition' => [ 
					'show_label' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'      => 'label_typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				//'scheme'    => Schemes\Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-label-inner',
				'condition' => [ 
					'show_label' => 'yes'
				]
			]
		);

		$this->add_control(
			'heading_content',
			[ 
				'label' => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'content_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [ 
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-content a'    => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-content span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_background',
			[ 
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-news-ticker'                                                                                              => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-content:before, {{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-content:after' => 'box-shadow: 0 0 12px 12px {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'     => 'content_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'selector' => '{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-content',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[ 
				'label'     => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'show_navigation' => 'yes'
				]
			]
		);

		$this->add_control(
			'navigation_background',
			[ 
				'label'     => esc_html__( 'Navigation Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_arrow_style' );

		$this->start_controls_tab(
			'tab_arrow_normal',
			[ 
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'navigation_color',
			[ 
				'label'     => esc_html__( 'Navigation Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation button span svg' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrow_hover',
			[ 
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'hover_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation button:hover span svg' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Main query render for this widget
	 *
	 * @param $posts_per_page number item query limit
	 */
	protected function query_posts( array $settings ) {
		$args = $this->getGroupControlQueryArgs();

		$posts_per_page = isset( $settings['posts_per_page'] ) ? (int) $settings['posts_per_page'] : 0;
		if ( $posts_per_page > 0 ) {
			$args['posts_per_page'] = $posts_per_page;
			$args['paged']          = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
		}

		$this->_query = new WP_Query( $args );
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		$content_type = ! empty( $settings['news_ticker_custom_content'] ) ? $settings['news_ticker_custom_content'] : 'dynamic';

		if ( 'custom' === $content_type ) {
			$this->render_header( $settings );

			if ( ! empty( $settings['news_ticker_content_list'] ) && is_array( $settings['news_ticker_content_list'] ) ) {
				$items = $settings['news_ticker_content_list'];

				foreach ( $items as $index => $item ) {
					$title = isset( $item['news_ticker_title'] ) ? $item['news_ticker_title'] : '';
					
					if ( ! empty( $item['news_ticker_link']['url'] ) ) {
						$this->add_link_attributes( 'news_ticker_link_' . $index, $item['news_ticker_link'] );
					}
					?>
						<li class="bdt-news-ticker-item">
						<a <?php $this->print_render_attribute_string( 'news_ticker_link_' . $index ); ?>>
								<?php echo esc_html( $title ); ?>
							</a>
						</li>
					<?php
				}
			}
			$this->render_footer( $settings );
			return;
		}

		$this->query_posts( $settings );

		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$this->render_header( $settings );

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_loop_item( $settings );
		}

		$this->render_footer( $settings );

		wp_reset_postdata();
	}

	protected function render_title( array $settings ) {
		?>

		<a href="<?php echo esc_url( get_permalink() ); ?>">
			<?php $this->render_date( $settings ); ?>

			<?php $this->render_time( $settings ); ?>

			<?php the_title() ?>
		</a>
		<?php
	}


	protected function render_excerpt() {

		?>
		<a href="<?php echo esc_url( get_permalink() ); ?>">
			<?php the_excerpt(); ?>
		</a>
		<?php
	}

	protected function render_header( $settings ) {

		$this->add_render_attribute(
			[ 
				'slider-settings' => [ 
					'class'         => [ 
						'bdt-news-ticker',
					],
					'data-settings' => [ 
						wp_json_encode(
							array_filter(
								[
									'effect'       => isset( $settings['slider_animations'] ) ? $settings['slider_animations'] : 'fade',
									'autoPlay'     => ! empty( $settings['autoplay'] ),
									'interval'     => isset( $settings['autoplay_interval'] ) ? (int) $settings['autoplay_interval'] : 5000,
									'pauseOnHover' => ! empty( $settings['pause_on_hover'] ),
									'scrollSpeed'  => isset( $settings['scroll_speed']['size'] ) ? $settings['scroll_speed']['size'] : 1,
									'direction'    => is_rtl() ? 'rtl' : false,
								]
							)
						)
					],
				]
			]
		);

		?>
		<div id="newsTicker1" <?php $this->print_render_attribute_string( 'slider-settings' ); ?>>
			<?php if ( isset( $settings['show_label'] ) && $settings['show_label'] === 'yes' ) : ?>
				<div class="bdt-news-ticker-label">
					<div class="bdt-news-ticker-label-inner">
						<?php echo wp_kses( $settings['news_label'], element_pack_allow_tags( 'title' ) ); ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="bdt-news-ticker-content">
				<ul>
					<?php
	}

	public function render_date( $settings ) {
		if ( empty( $settings['show_date'] ) || $settings['show_date'] !== 'yes' ) {
			return;
		}

		$news_month = get_the_date( 'm' );
		$news_day   = get_the_date( 'd' );

		?>

					<span class="bdt-news-ticker-date bdt-margin-small-right"
						title="<?php echo esc_attr__( 'Published on:', 'bdthemes-element-pack' ) . ' ' . esc_attr( get_the_date() ); ?>">
						<?php if ( ! empty( $settings['date_reverse'] ) && $settings['date_reverse'] === 'yes' ) : ?>
							<span class="bdt-news-ticker-date-day">
								<?php echo esc_attr( $news_day ); ?>
							</span>
							<span class="bdt-news-ticker-date-sep">/</span>
							<span class="bdt-news-ticker-date-month">
								<?php echo esc_attr( $news_month ); ?>
							</span>
						<?php else : ?>
							<span class="bdt-news-ticker-date-month">
								<?php echo esc_attr( $news_month ); ?>
							</span>
							<span class="bdt-news-ticker-date-sep">/</span>
							<span class="bdt-news-ticker-date-day">
								<?php echo esc_attr( $news_day ); ?>
							</span>
						<?php endif; ?>
						<span>:</span>
					</span>

					<?php
	}

	public function render_time( $settings ) {
		if ( empty( $settings['show_time'] ) || $settings['show_time'] !== 'yes' ) {
			return;
		}

		$news_hour = get_the_time();

		?>

					<span class="bdt-news-ticker-time bdt-margin-small-right"
						title="<?php echo esc_attr__( 'Published on:', 'bdthemes-element-pack' ) . ' ' . esc_attr( get_the_date() . ' ' . get_the_time() ); ?>">
						<span class="bdt-text-uppercase">
							<?php echo esc_attr( $news_hour ); ?>
						</span>
						<span>:</span>
					</span>

					<?php
	}

	protected function render_footer( $settings ) {
		?>


				</ul>
			</div>
			<?php if ( ! empty( $settings['show_navigation'] ) ) : ?>
				<div class="bdt-news-ticker-controls bdt-news-ticker-navigation">

					<button class="bdt-visible@m">
						<span class="bdt-news-ticker-arrow bdt-news-ticker-prev bdt-icon">
							<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
								data-svg="chevron-left">
								<polyline fill="none" stroke="#000" stroke-width="1.03" points="13 16 7 10 13 4"></polyline>
							</svg>
						</span>
					</button>

					<?php if ( ! empty( $settings['play_pause'] ) ) : ?>
						<button class="bdt-visible@m">
							<span class="bdt-news-ticker-action bdt-icon">
								<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="play"
									class="bdt-news-ticker-play-pause">
									<polygon fill="none" stroke="#000" points="4.9,3 16.1,10 4.9,17 "></polygon>

									<rect x="6" y="2" width="1" height="16" />
									<rect x="13" y="2" width="1" height="16" />
								</svg>
							</span>
						</button>
					<?php endif; ?>

					<button>
						<span class="bdt-news-ticker-arrow bdt-news-ticker-next bdt-icon">
							<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
								data-svg="chevron-right">
								<polyline fill="none" stroke="#000" stroke-width="1.03" points="7 4 13 10 7 16"></polyline>
							</svg>
						</span>
					</button>

				</div>

			<?php endif; ?>
		</div>

		<?php
	}

	public function render_loop_item( $settings ) {
		?>
		<li class="bdt-news-ticker-item">
			<?php if ( isset( $settings['news_content'] ) && $settings['news_content'] === 'title' ) : ?>
				<?php $this->render_title( $settings ); ?>
			<?php endif; ?>

			<?php if ( isset( $settings['news_content'] ) && $settings['news_content'] === 'excerpt' ) : ?>
				<?php $this->render_excerpt(); ?>
			<?php endif; ?>
		</li>
		<?php
	}
}
