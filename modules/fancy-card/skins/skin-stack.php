<?php
namespace ElementPackAddon\Modules\FancyCard\Skins;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Stack extends Elementor_Skin_Base {

	public function get_id() {
		return 'stack';
	}

	public function get_title() {
		return __( 'Stack', 'bdthemes-element-pack' );
	}


	public function render() {
		$settings = $this->parent->get_settings_for_display();

		if ( ! empty( $settings['global_link'] ) && $settings['global_link'] === 'yes'
			&& ! empty( $settings['global_link_url']['url'] ) ) {
			$target = ( ! empty( $settings['global_link_url']['is_external'] ) && $settings['global_link_url']['is_external'] )
				? '_blank'
				: '_self';
			$this->parent->add_render_attribute(
				'fancy-card',
				'onclick',
				"window.open('" . esc_url( $settings['global_link_url']['url'] ) . "', '" . esc_attr( $target ) . "')"
			);
		}

		$style_map = [
			'style1' => 'bdt-ep-fancy-card bdt-ep-fancy-card-stack bdt-icon-style1',
			'style2' => 'bdt-ep-fancy-card bdt-ep-fancy-card-stack bdt-icon-style2',
			'style3' => 'bdt-ep-fancy-card bdt-ep-fancy-card-stack bdt-icon-style3',
			'style4' => 'bdt-ep-fancy-card bdt-ep-fancy-card-stack bdt-icon-style4',
		];
		$icon_style = isset( $settings['fancy_card_icon_style'] ) ? $settings['fancy_card_icon_style'] : '';
		$card_class = isset( $style_map[ $icon_style ] ) ? $style_map[ $icon_style ] : 'bdt-ep-fancy-card bdt-ep-fancy-card-stack';
		$this->parent->add_render_attribute( 'fancy-card', 'class', $card_class );
		?>
		<div <?php $this->parent->print_render_attribute_string( 'fancy-card' ); ?>>
			<?php $this->parent->render_icon( $settings ); ?>
			<div class="bdt-ep-fancy-card-content-overlay"></div>
			<div class="bdt-ep-fancy-card-content">
				<?php $this->parent->render_title( $settings ); ?>
				<?php $this->parent->render_text( $settings ); ?>
				<?php $this->parent->render_readmore( $settings ); ?>
			</div>
		</div>
		<?php
		$this->parent->render_indicator( $settings );
		$this->parent->render_badge( $settings );
	}
}
