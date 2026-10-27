<?php
/**
 * Uncanny Automator integration registration for WP Zoomy.
 *
 * @package Zoomy_Automator_Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the WP Zoomy integration with Uncanny Automator.
 */
class Zoom_Automator_Integration extends \Uncanny_Automator\Integration {

	/**
	 * Configure integration identity shown in Automator.
	 *
	 * @return void
	 */
	protected function setup() {
		$icon_file = ZOOM_AUTOMATOR_PATH . 'assets/wp-zoomy-icon.png';
		$icon_ver  = file_exists( $icon_file ) ? (string) filemtime( $icon_file ) : ZOOM_AUTOMATOR_VERSION;

		$this->set_integration( 'ZOOM_WP' );
		$this->set_name( esc_html__( 'WP Zoomy', 'zoomy-automator-integration' ) );
		$this->set_icon_url(
			add_query_arg(
				'ver',
				rawurlencode( $icon_ver ),
				ZOOM_AUTOMATOR_URL . 'assets/wp-zoomy-icon.png'
			)
		);
	}
}
