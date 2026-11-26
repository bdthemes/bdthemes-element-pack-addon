<?php
namespace ElementPackAddon\Modules\FancyCard\Skins;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Flux extends Elementor_Skin_Base {

	public function get_id() {
		return 'flux';
	}

	public function get_title() {
		return __( 'Flux', 'bdthemes-element-pack' );
	}

	public function render() {
		$settings = $this->parent->get_settings_for_display();

		if ( ! empty( $settings['global_link'] ) && $settings['global_link'] === 'yes'
			&& ! empty( $settings['global_link_url']['url'] ) ) {
			$target = ( ! empty( $settings['global_link_url']['is_external'] ) && $settings['global_link_url']['is_external'] )
				? '_blank'
				: '_self';
			// JSON-encoded and scheme-checked: esc_url() inside a quoted JS string is not safe.
			$onclick = element_pack_window_open_handler( $settings['global_link_url']['url'], '_blank' === $target );
			if ( '' !== $onclick ) {
				$this->parent->add_render_attribute( 'fancy-card', 'onclick', $onclick );
			}
		}

		$this->parent->add_render_attribute( 'fancy-card', 'class', 'bdt-ep-fancy-card bdt-ep-fancy-card-flux' );
		?>
		<div <?php $this->parent->print_render_attribute_string( 'fancy-card' ); ?>>
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
