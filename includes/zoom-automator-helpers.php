<?php
/**
 * Shared helpers for Zoomy Automator Integration.
 *
 * @package Zoomy_Automator_Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WordPress timezone used by WP Zoomy when creating meetings.
 *
 * @return string
 */
function zoom_automator_get_timezone() {
	$timezone = get_option( 'timezone_string' );

	if ( empty( $timezone ) ) {
		$timezone = 'UTC';
	}

	return sanitize_text_field( $timezone );
}

/**
 * Resolve a Zoom host ID from recipe user, current user, or any administrator.
 *
 * @param int $user_id Optional recipe user ID.
 * @return string
 */
function zoom_automator_get_zoom_host_id( $user_id = 0 ) {
	$user_ids = array();

	$user_id = absint( $user_id );
	if ( $user_id ) {
		$user_ids[] = $user_id;
	}

	$current_user_id = get_current_user_id();
	if ( $current_user_id ) {
		$user_ids[] = absint( $current_user_id );
	}

	$admin_ids = get_users(
		array(
			'role'   => 'administrator',
			'fields' => 'ID',
		)
	);

	if ( ! empty( $admin_ids ) ) {
		$user_ids = array_merge( $user_ids, array_map( 'absint', $admin_ids ) );
	}

	foreach ( array_unique( array_filter( $user_ids ) ) as $candidate_user_id ) {
		$host_id = get_user_meta( $candidate_user_id, 'user_zoom_hostid', true );

		if ( is_array( $host_id ) ) {
			$host_id = reset( $host_id );
		}

		$host_id = sanitize_text_field( (string) $host_id );

		if ( ! empty( $host_id ) ) {
			return $host_id;
		}
	}

	return '';
}

/**
 * Normalize WP Zoomy API responses to an object.
 *
 * @param mixed $response API response.
 * @return object|null
 */
function zoom_automator_decode_zoom_response( $response ) {
	if ( false === $response || null === $response || '' === $response ) {
		return null;
	}

	if ( is_object( $response ) ) {
		return $response;
	}

	if ( ! is_string( $response ) ) {
		return null;
	}

	$decoded = json_decode( $response );

	return is_object( $decoded ) ? $decoded : null;
}

/**
 * Check whether a decoded Zoom API response represents an error.
 *
 * @param object|null $response Decoded response.
 * @return bool
 */
function zoom_automator_is_zoom_api_error( $response ) {
	if ( ! is_object( $response ) ) {
		return true;
	}

	return ! empty( $response->code );
}

/**
 * Return a safe error message from a Zoom API response.
 *
 * @param object|null $response Decoded response.
 * @param string      $fallback Translated fallback message.
 * @return string
 */
function zoom_automator_get_api_error_message( $response, $fallback ) {
	if ( is_object( $response ) && isset( $response->message ) && is_string( $response->message ) && '' !== $response->message ) {
		return sanitize_text_field( $response->message );
	}

	return $fallback;
}

/**
 * Register an attendee for a Zoom meeting.
 * WP Zoomy exposes webinar registration but not meeting registration publicly.
 *
 * @param int|string $meeting_id Zoom meeting ID.
 * @param string     $first_name Attendee first name.
 * @param string     $last_name  Attendee last name.
 * @param string     $email      Attendee email.
 * @return mixed
 */
function zoom_automator_register_meeting_participant( $meeting_id, $first_name, $last_name, $email ) {
	if ( ! function_exists( 'zoom_conference' ) ) {
		return null;
	}

	$meeting_id = absint( $meeting_id );
	$first_name = sanitize_text_field( $first_name );
	$last_name  = sanitize_text_field( $last_name );
	$email      = sanitize_email( $email );

	if ( ! $meeting_id || empty( $first_name ) || empty( $email ) || ! is_email( $email ) ) {
		return null;
	}

	$api = zoom_conference(
		array(
			'meeting_id' => $meeting_id,
		)
	);

	$request_url = 'https://api.zoom.us/v2/meetings/' . $meeting_id . '/registrants';
	$args        = array(
		'headers'   => array(
			'Authorization' => 'Bearer ' . $api->generateJWTKey(),
			'Content-Type'  => 'application/json',
		),
		'timeout'   => 60,
		'sslverify' => true,
		'compress'  => true,
	);
	$data        = array(
		'email'      => $email,
		'first_name' => $first_name,
		'last_name'  => $last_name,
	);

	return $api->makeRequest( $request_url, $args, $data, 'POST' );
}
