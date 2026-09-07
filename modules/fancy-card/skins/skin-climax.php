<?php
namespace ElementPackAddon\Modules\FancyCard\Skins;

use Elementor\Skin_Base as Elementor_Skin_Base;

use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Climax extends Elementor_Skin_Base {

	public function get_id() {
		return 'climax';
	}

	public function get_title() {
		return __( 'Climax', 'bdthemes-element-pack' );
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

		$toggle_position = isset( $settings['toggle_position'] ) ? $settings['toggle_position'] : 'bottom-right';
		$this->parent->add_render_attribute( 'toggle_input_position', 'class', 'bdt-checkbox bdt-position-' . esc_attr( $toggle_position ) );
		$this->parent->add_render_attribute( 'toggle_position', 'class', 'bdt-ep-fancy-card-toggole bdt-position-' . esc_attr( $toggle_position ) );
		$this->parent->add_render_attribute( 'fancy-card', 'class', 'bdt-ep-fancy-card bdt-ep-fancy-card-climax' );
		?>
		<div <?php $this->parent->print_render_attribute_string( 'fancy-card' ); ?>>
			<input <?php $this->parent->print_render_attribute_string( 'toggle_input_position' ); ?> type="checkbox">
			<div <?php $this->parent->print_render_attribute_string( 'toggle_position' ); ?>>
				<?php
				if ( ! empty( $settings['toggle_icon']['value'] ) ) {
					Icons_Manager::render_icon( $settings['toggle_icon'], [ 'aria-hidden' => 'true' ] );
				}
				?>
			</div>
			<?php $this->parent->render_icon( $settings ); ?>
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
