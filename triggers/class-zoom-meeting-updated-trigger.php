<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Zoom_Meeting_Updated_Trigger extends \Uncanny_Automator\Recipe\Trigger {

	protected function setup_trigger() {
		$this->set_integration( 'ZOOM_WP' );
		$this->set_trigger_code( 'ZOOM_MEETING_UPDATED' );
		$this->set_trigger_meta( 'ZOOM_MEETING_UPDATED_META' );
		$this->set_author( 'Abdurrehman Abbasi' );
		$this->set_sentence( esc_html__( 'A Zoom meeting is updated', 'wpzoomy-integration-zoom-uncanny-automator' ) );
		$this->set_readable_sentence( esc_html__( 'A Zoom meeting is updated', 'wpzoomy-integration-zoom-uncanny-automator' ) );
		$this->add_action( 'zoom_wp_after_update_meeting', 10, 3 );
	}

	public function validate( $trigger, $hook_args ) {
		$meeting_data = isset( $hook_args[0] ) ? $hook_args[0] : null;
		if ( empty( $meeting_data ) ) {
			return false;
		}

		return true;
	}

	public function define_tokens( $trigger, $tokens ) {
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_ID',
			'tokenName' => esc_html__( 'Meeting ID', 'wpzoomy-integration-zoom-uncanny-automator' ),
			'tokenType' => 'int',
		);
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_TOPIC',
			'tokenName' => esc_html__( 'Meeting Topic', 'wpzoomy-integration-zoom-uncanny-automator' ),
			'tokenType' => 'text',
		);
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_JOIN_URL',
			'tokenName' => esc_html__( 'Join URL', 'wpzoomy-integration-zoom-uncanny-automator' ),
			'tokenType' => 'url',
		);
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_START_TIME',
			'tokenName' => esc_html__( 'Start Time', 'wpzoomy-integration-zoom-uncanny-automator' ),
			'tokenType' => 'text',
		);
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_HOST_ID',
			'tokenName' => esc_html__( 'Host ID', 'wpzoomy-integration-zoom-uncanny-automator' ),
			'tokenType' => 'text',
		);

		return $tokens;
	}

	public function hydrate_tokens( $trigger, $hook_args ) {
		$meeting_data = isset( $hook_args[0] ) ? $hook_args[0] : null;

		return array(
			'ZOOM_MEETING_ID'         => isset( $meeting_data->id ) ? sanitize_text_field( (string) $meeting_data->id ) : '',
			'ZOOM_MEETING_TOPIC'      => isset( $meeting_data->topic ) ? sanitize_text_field( (string) $meeting_data->topic ) : '',
			'ZOOM_MEETING_JOIN_URL'   => isset( $meeting_data->join_url ) ? esc_url_raw( (string) $meeting_data->join_url ) : '',
			'ZOOM_MEETING_START_TIME' => isset( $meeting_data->start_time ) ? sanitize_text_field( (string) $meeting_data->start_time ) : '',
			'ZOOM_MEETING_HOST_ID'    => isset( $meeting_data->host_id ) ? sanitize_text_field( (string) $meeting_data->host_id ) : '',
		);
	}
}
