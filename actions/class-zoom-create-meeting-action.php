<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Zoom_Create_Meeting_Action extends \Uncanny_Automator\Recipe\Action {

	protected function setup_action() {
		$this->set_integration( 'ZOOM_WP' );
		$this->set_action_code( 'ZOOM_CREATE_MEETING' );
		$this->set_action_meta( 'ZOOM_MEETING_TOPIC' );
		$this->set_author( 'Abdurrehman Abbasi' );
		$this->set_requires_user( false );
		// Fillable token MUST be {{label:ACTION_META}} — this is what makes the config panel open.
		$this->set_sentence(
			sprintf(
				/* translators: %1$s: action meta code */
				esc_html__( 'Create {{a Zoom meeting:%1$s}}', 'zoomy-automator-integration' ),
				$this->get_action_meta()
			)
		);
		$this->set_readable_sentence( esc_html__( 'Create a Zoom meeting', 'zoomy-automator-integration' ) );
	}

	public function options() {
		return array(
			array(
				'option_code'     => $this->get_action_meta(),
				'label'           => esc_html__( 'Meeting Topic', 'zoomy-automator-integration' ),
				'input_type'      => 'text',
				'required'        => true,
				'supports_tokens' => true,
				'relevant_tokens' => array(),
			),
			array(
				'option_code'     => 'ZOOM_MEETING_START_TIME',
				'label'           => esc_html__( 'Start Time (YYYY-MM-DD HH:MM:SS)', 'zoomy-automator-integration' ),
				'input_type'      => 'text',
				'required'        => true,
				'supports_tokens' => true,
				'relevant_tokens' => array(),
				'description'     => esc_html__( 'Example: 2026-07-25 15:00:00 (site timezone).', 'zoomy-automator-integration' ),
			),
			array(
				'option_code'     => 'ZOOM_MEETING_DURATION',
				'label'           => esc_html__( 'Duration (minutes)', 'zoomy-automator-integration' ),
				'input_type'      => 'text',
				'required'        => true,
				'supports_tokens' => true,
				'relevant_tokens' => array(),
			),
		);
	}

	public function define_tokens() {
		return array(
			'ZOOM_MEETING_ID'         => array(
				'name' => esc_html__( 'Meeting ID', 'zoomy-automator-integration' ),
				'type' => 'int',
			),
			'ZOOM_MEETING_TOPIC'      => array(
				'name' => esc_html__( 'Meeting Topic', 'zoomy-automator-integration' ),
				'type' => 'text',
			),
			'ZOOM_MEETING_JOIN_URL'   => array(
				'name' => esc_html__( 'Join URL', 'zoomy-automator-integration' ),
				'type' => 'url',
			),
			'ZOOM_MEETING_START_TIME' => array(
				'name' => esc_html__( 'Start Time', 'zoomy-automator-integration' ),
				'type' => 'text',
			),
			'ZOOM_MEETING_HOST_ID'    => array(
				'name' => esc_html__( 'Host ID', 'zoomy-automator-integration' ),
				'type' => 'text',
			),
		);
	}

	protected function process_action( $user_id, $action_data, $recipe_id, $args, $parsed ) {
		$topic      = isset( $parsed[ $this->get_action_meta() ] ) ? sanitize_text_field( $parsed[ $this->get_action_meta() ] ) : '';
		$start_time = isset( $parsed['ZOOM_MEETING_START_TIME'] ) ? sanitize_text_field( $parsed['ZOOM_MEETING_START_TIME'] ) : '';
		$duration   = isset( $parsed['ZOOM_MEETING_DURATION'] ) ? absint( $parsed['ZOOM_MEETING_DURATION'] ) : 0;

		if ( empty( $topic ) || empty( $start_time ) || empty( $duration ) ) {
			throw new \Exception( esc_html__( 'Meeting topic, start time, and duration are required.', 'zoomy-automator-integration' ) );
		}

		$host_id = zoom_automator_get_zoom_host_id( $user_id );

		if ( empty( $host_id ) ) {
			throw new \Exception( esc_html__( 'No Zoom host ID is assigned to a WordPress user. Assign one in Zoom Meetings settings.', 'zoomy-automator-integration' ) );
		}

		$meeting_data = array(
			'userId'               => $host_id,
			'meetingTopic'         => $topic,
			'start_date'           => $start_time,
			'timezone'             => zoom_automator_get_timezone(),
			'duration'             => $duration,
			'join_before_host'     => 1,
			'meeting_registration' => 0,
		);

		$response = zoom_conference()->createAMeeting( $meeting_data );
		$meeting  = zoom_automator_decode_zoom_response( $response );

		if ( zoom_automator_is_zoom_api_error( $meeting ) ) {
			throw new \Exception(
				esc_html(
					zoom_automator_get_api_error_message(
						$meeting,
						__( 'Zoom API could not create the meeting.', 'zoomy-automator-integration' )
					)
				)
			);
		}

		$this->hydrate_tokens(
			array(
				'ZOOM_MEETING_ID'         => isset( $meeting->id ) ? sanitize_text_field( (string) $meeting->id ) : '',
				'ZOOM_MEETING_TOPIC'      => isset( $meeting->topic ) ? sanitize_text_field( (string) $meeting->topic ) : $topic,
				'ZOOM_MEETING_JOIN_URL'   => isset( $meeting->join_url ) ? esc_url_raw( (string) $meeting->join_url ) : '',
				'ZOOM_MEETING_START_TIME' => isset( $meeting->start_time ) ? sanitize_text_field( (string) $meeting->start_time ) : $start_time,
				'ZOOM_MEETING_HOST_ID'    => isset( $meeting->host_id ) ? sanitize_text_field( (string) $meeting->host_id ) : $host_id,
			)
		);

		return true;
	}
}
