<?php
/**
 * Plugin Name:       Zoomy Automator Integration
 * Plugin URI:        https://github.com/WPZoomy/zoom-automator-integration
 * Description:       Connects WP Zoomy with Uncanny Automator — meeting triggers and actions to create meetings or register attendees.
 * Version:           1.2.7
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            wpzoomy
 * Author URI:        https://wpzoomy.com/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       zoomy-automator-integration
 * Requires Plugins:  uncanny-automator
 *
 * @package Zoomy_Automator_Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZOOM_AUTOMATOR_VERSION', '1.2.6' );
define( 'ZOOM_AUTOMATOR_FILE', __FILE__ );
define( 'ZOOM_AUTOMATOR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ZOOM_AUTOMATOR_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', 'zoom_automator_check_dependencies' );
add_action( 'automator_add_integration', 'zoom_automator_load_integration' );
add_action( 'admin_enqueue_scripts', 'zoom_automator_enqueue_admin_assets' );

/**
 * Show an admin notice when required plugins are missing.
 */
function zoom_automator_check_dependencies() {
	if ( ! is_admin() ) {
		return;
	}

	$missing = array();

	if ( ! class_exists( '\Uncanny_Automator\Integration' ) ) {
		$missing[] = 'Uncanny Automator';
	}

	if ( ! function_exists( 'zoom_conference' ) ) {
		$missing[] = 'WP Zoomy (wp-zoom-addon)';
	}

	if ( empty( $missing ) ) {
		return;
	}

	add_action(
		'admin_notices',
		static function () use ( $missing ) {
			printf(
				'<div class="notice notice-warning"><p>%s</p></div>',
				esc_html(
					sprintf(
						/* translators: %s: comma-separated list of plugin names */
						__( 'Zoomy Automator Integration requires %s to be installed and active.', 'zoomy-automator-integration' ),
						implode( ', ', $missing )
					)
				)
			);
		}
	);
}

/**
 * Load admin CSS so the WP Zoomy icon fills Automator circle/square containers.
 *
 * @param string $hook_suffix Current admin page hook.
 * @return void
 */
function zoom_automator_enqueue_admin_assets( $hook_suffix ) {
	if ( ! is_admin() ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$load   = false;

	if ( $screen ) {
		$screen_id   = isset( $screen->id ) ? (string) $screen->id : '';
		$screen_base = isset( $screen->base ) ? (string) $screen->base : '';
		$post_type   = isset( $screen->post_type ) ? (string) $screen->post_type : '';

		if ( 'uo-recipe' === $post_type ) {
			$load = true;
		}

		if ( false !== strpos( $screen_id, 'automator' ) || false !== strpos( $screen_base, 'automator' ) ) {
			$load = true;
		}

		if ( false !== strpos( $hook_suffix, 'automator' ) || false !== strpos( $hook_suffix, 'uo-recipe' ) ) {
			$load = true;
		}
	} elseif ( is_string( $hook_suffix ) && ( false !== strpos( $hook_suffix, 'automator' ) || false !== strpos( $hook_suffix, 'uo-recipe' ) ) ) {
		$load = true;
	}

	if ( ! $load ) {
		return;
	}

	$css_file = ZOOM_AUTOMATOR_PATH . 'assets/wp-zoomy-automator-icon.css';
	if ( ! file_exists( $css_file ) ) {
		return;
	}

	wp_enqueue_style(
		'zoomy-automator-icon',
		ZOOM_AUTOMATOR_URL . 'assets/wp-zoomy-automator-icon.css',
		array(),
		(string) filemtime( $css_file )
	);
}

/**
 * Register Zoom integration with Uncanny Automator.
 */
function zoom_automator_load_integration() {
	if ( ! class_exists( '\Uncanny_Automator\Integration' ) ) {
		return;
	}

	if ( ! function_exists( 'zoom_conference' ) ) {
		return;
	}

	require_once ZOOM_AUTOMATOR_PATH . 'includes/zoom-automator-helpers.php';
	require_once ZOOM_AUTOMATOR_PATH . 'integration/class-zoom-automator-integration.php';
	new Zoom_Automator_Integration();

	require_once ZOOM_AUTOMATOR_PATH . 'triggers/class-zoom-meeting-created-trigger.php';
	new Zoom_Meeting_Created_Trigger();

	require_once ZOOM_AUTOMATOR_PATH . 'triggers/class-zoom-meeting-updated-trigger.php';
	new Zoom_Meeting_Updated_Trigger();

	require_once ZOOM_AUTOMATOR_PATH . 'actions/class-zoom-create-meeting-action.php';
	new Zoom_Create_Meeting_Action();

	require_once ZOOM_AUTOMATOR_PATH . 'actions/class-zoom-register-attendee-action.php';
	new Zoom_Register_Attendee_Action();
}
