<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Zoom_Register_Attendee_Action extends \Uncanny_Automator\Recipe\Action {

	protected function setup_action() {
		$this->set_integration( 'ZOOM_WP' );
		$this->set_action_code( 'ZOOM_REGISTER_ATTENDEE' );
		$this->set_action_meta( 'ZOOM_MEETING_ID' );
		$this->set_author( 'Abdurrehman Abbasi' );
		$this->set_requires_user( false );
		// Multi-field sentence: FIELD:META and META — same pattern as Automator's own actions.
		$this->set_sentence(
			sprintf(
				/* translators: 1: attendee email field + meta group, 2: meeting ID meta */
				esc_html__( 'Register {{an attendee:%1$s}} for {{a Zoom meeting:%2$s}}', 'wpzoomy-integration-zoom-uncanny-automator' ),
				'ZOOM_ATTENDEE_EMAIL:' . $this->get_action_meta(),
				$this->get_action_meta()
			)
		);
		$this->set_readable_sentence( esc_html__( 'Register an attendee for a Zoom meeting', 'wpzoomy-integration-zoom-uncanny-automator' ) );
	}

	public function options() {
		return array(
			array(
				'option_code'     => $this->get_action_meta(),
				'label'           => esc_html__( 'Meeting ID', 'wpzoomy-integration-zoom-uncanny-automator' ),
				'input_type'      => 'text',
				'required'        => true,
				'supports_tokens' => true,
				'relevant_tokens' => array(),
				'description'     => esc_html__( 'Zoom Meeting ID, or a token from Create a Zoom meeting.', 'wpzoomy-integration-zoom-uncanny-automator' ),
			),
			array(
				'option_code'     => 'ZOOM_ATTENDEE_EMAIL',
				'label'           => esc_html__( 'Attendee Email', 'wpzoomy-integration-zoom-uncanny-automator' ),
				'input_type'      => 'email',
				'required'        => true,
				'supports_tokens' => true,
				'relevant_tokens' => array(),
			),
			array(
				'option_code'     => 'ZOOM_ATTENDEE_FIRST_NAME',
				'label'           => esc_html__( 'First Name', 'wpzoomy-integration-zoom-uncanny-automator' ),
				'input_type'      => 'text',
				'required'        => true,
				'supports_tokens' => true,
				'relevant_tokens' => array(),
			),
			array(
				'option_code'     => 'ZOOM_ATTENDEE_LAST_NAME',
				'label'           => esc_html__( 'Last Name', 'wpzoomy-integration-zoom-uncanny-automator' ),
				'input_type'      => 'text',
				'required'        => false,
				'supports_tokens' => true,
				'relevant_tokens' => array(),
			),
		);
	}

	public function define_tokens() {
		return array(
			'ZOOM_REGISTRANT_ID'       => array(
				'name' => esc_html__( 'Registrant ID', 'wpzoomy-integration-zoom-uncanny-automator' ),
				'type' => 'text',
			),
			'ZOOM_REGISTRANT_JOIN_URL' => array(
				'name' => esc_html__( 'Registrant Join URL', 'wpzoomy-integration-zoom-uncanny-automator' ),
				'type' => 'url',
			),
		);
	}

	protected function process_action( $user_id, $action_data, $recipe_id, $args, $parsed ) {
		$meeting_id = isset( $parsed[ $this->get_action_meta() ] ) ? absint( $parsed[ $this->get_action_meta() ] ) : 0;
		$email      = isset( $parsed['ZOOM_ATTENDEE_EMAIL'] ) ? sanitize_email( $parsed['ZOOM_ATTENDEE_EMAIL'] ) : '';
		$first_name = isset( $parsed['ZOOM_ATTENDEE_FIRST_NAME'] ) ? sanitize_text_field( $parsed['ZOOM_ATTENDEE_FIRST_NAME'] ) : '';
		$last_name  = isset( $parsed['ZOOM_ATTENDEE_LAST_NAME'] ) ? sanitize_text_field( $parsed['ZOOM_ATTENDEE_LAST_NAME'] ) : '';

		if ( empty( $meeting_id ) || empty( $email ) || empty( $first_name ) || ! is_email( $email ) ) {
			throw new \Exception( esc_html__( 'Meeting ID, email, and first name are required.', 'wpzoomy-integration-zoom-uncanny-automator' ) );
		}

		$response   = zoom_automator_register_meeting_participant( $meeting_id, $first_name, $last_name, $email );
		$registrant = zoom_automator_decode_zoom_response( $response );

		if ( zoom_automator_is_zoom_api_error( $registrant ) ) {
			throw new \Exception(
				zoom_automator_get_api_error_message(
					$registrant,
					esc_html__( 'Zoom API could not register the attendee.', 'wpzoomy-integration-zoom-uncanny-automator' )
				)
			);
		}

		$this->hydrate_tokens(
			array(
				'ZOOM_REGISTRANT_ID'       => isset( $registrant->registrant_id ) ? sanitize_text_field( (string) $registrant->registrant_id ) : '',
				'ZOOM_REGISTRANT_JOIN_URL' => isset( $registrant->join_url ) ? esc_url_raw( (string) $registrant->join_url ) : '',
			)
		);

		return true;
	}
}
