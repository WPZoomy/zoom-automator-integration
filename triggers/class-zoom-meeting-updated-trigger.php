<?php
/**
 * Trigger: A Zoom meeting is updated.
 *
 * @package Zoomy_Automator_Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fires when WP Zoomy updates a Zoom meeting.
 */
class Zoom_Meeting_Updated_Trigger extends \Uncanny_Automator\Recipe\Trigger {

	/**
	 * Configure the trigger.
	 *
	 * @return void
	 */
	protected function setup_trigger() {
		$this->set_integration( 'ZOOM_WP' );
		$this->set_trigger_code( 'ZOOM_MEETING_UPDATED' );
		$this->set_trigger_meta( 'ZOOM_MEETING_UPDATED_META' );
		$this->set_author( 'Abdurrehman Abbasi' );
		$this->set_sentence( esc_html__( 'A Zoom meeting is updated', 'zoomy-automator-integration' ) );
		$this->set_readable_sentence( esc_html__( 'A Zoom meeting is updated', 'zoomy-automator-integration' ) );
		$this->add_action( 'zoom_wp_after_update_meeting', 10, 3 );
	}

	/**
	 * Validate whether the trigger should fire.
	 *
	 * @param array $trigger   Trigger config.
	 * @param array $hook_args Hook arguments.
	 * @return bool
	 */
	public function validate( $trigger, $hook_args ) {
		$meeting_data = isset( $hook_args[0] ) ? $hook_args[0] : null;

		return is_object( $meeting_data );
	}

	/**
	 * Define tokens available from this trigger.
	 *
	 * @param array $trigger Trigger config.
	 * @param array $tokens  Existing tokens.
	 * @return array
	 */
	public function define_tokens( $trigger, $tokens ) {
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_ID',
			'tokenName' => esc_html__( 'Meeting ID', 'zoomy-automator-integration' ),
			'tokenType' => 'int',
		);
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_TOPIC',
			'tokenName' => esc_html__( 'Meeting Topic', 'zoomy-automator-integration' ),
			'tokenType' => 'text',
		);
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_JOIN_URL',
			'tokenName' => esc_html__( 'Join URL', 'zoomy-automator-integration' ),
			'tokenType' => 'url',
		);
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_START_TIME',
			'tokenName' => esc_html__( 'Start Time', 'zoomy-automator-integration' ),
			'tokenType' => 'text',
		);
		$tokens[] = array(
			'tokenId'   => 'ZOOM_MEETING_HOST_ID',
			'tokenName' => esc_html__( 'Host ID', 'zoomy-automator-integration' ),
			'tokenType' => 'text',
		);

		return $tokens;
	}

	/**
	 * Populate token values from the meeting payload.
	 *
	 * @param array $trigger   Trigger config.
	 * @param array $hook_args Hook arguments.
	 * @return array
	 */
	public function hydrate_tokens( $trigger, $hook_args ) {
		$meeting_data = isset( $hook_args[0] ) && is_object( $hook_args[0] ) ? $hook_args[0] : null;

		return array(
			'ZOOM_MEETING_ID'         => ( $meeting_data && isset( $meeting_data->id ) ) ? sanitize_text_field( (string) $meeting_data->id ) : '',
			'ZOOM_MEETING_TOPIC'      => ( $meeting_data && isset( $meeting_data->topic ) ) ? sanitize_text_field( (string) $meeting_data->topic ) : '',
			'ZOOM_MEETING_JOIN_URL'   => ( $meeting_data && isset( $meeting_data->join_url ) ) ? esc_url_raw( (string) $meeting_data->join_url ) : '',
			'ZOOM_MEETING_START_TIME' => ( $meeting_data && isset( $meeting_data->start_time ) ) ? sanitize_text_field( (string) $meeting_data->start_time ) : '',
			'ZOOM_MEETING_HOST_ID'    => ( $meeting_data && isset( $meeting_data->host_id ) ) ? sanitize_text_field( (string) $meeting_data->host_id ) : '',
		);
	}
}
