<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Zoom_Automator_Integration extends \Uncanny_Automator\Integration {

	protected function setup() {
		$this->set_integration( 'ZOOM_WP' );
		$this->set_name( esc_html__( 'Zoom', 'zoomy-automator-integration' ) );
		$this->set_icon_url( ZOOM_AUTOMATOR_URL . 'assets/zoom-icon.svg' );
	}
}
