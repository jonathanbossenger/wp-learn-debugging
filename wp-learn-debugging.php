<?php
/**
 * Plugin Name: WP Learn Debugging
 * Plugin Description: A plugin to learn about debugging in WordPress.
 * Plugin URI: https://learn.wordpress.org
 * Version: 1.0.0
 */

/**
 * Set up the required form submissions table
 */
register_activation_hook( __FILE__, 'wp_learn_setup_table' );
function wp_learn_setup_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name varchar (100) NOT NULL,
	  email varchar (100) NOT NULL,
	  PRIMARY KEY  (id)
	)";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

/**
 * Register the REST API GET route
 */
add_action( 'init', 'wp_learn_register_routes' );
function wp_learn_register_routes() {
	register_rest_route(
		'wp-learn-form-submissions-api/v1',
		'/form-submissions/',
		array(
			'methods'             => 'GET',
			'callback'            => 'wp_learn_get_form_submissions',
			'permission_callback' => '__return_true'
		)
	);
}

/**
 * Fetch the form submissions for the REST API GET Route
 *
 * @return array|object|stdClass[]|null
 */
function wp_learn_get_form_submissions() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$results = $wpdb->get_results( "SELECT * FROM $table_name" );

	return $results;
}
