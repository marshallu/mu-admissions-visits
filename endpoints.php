<?php
/**
 * Custom API endpoints for the MU Admissions Visits plugin
 *
 * @package MU Admissions Visits
 */

require WP_PLUGIN_DIR . '/mu-admissions-visits/vendor/autoload.php';
use Carbon\Carbon;

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'marsha/v1',
			'visits',
			array(
				'methods'  => 'GET',
				'callback' => 'mu_get_latest_visits',
			)
		);
	}
);

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'marsha/v1',
			'visits-past',
			array(
				'methods'  => 'GET',
				'callback' => 'mu_get_past_visits',
			)
		);
	}
);

/**
 * Get the latest Visits.
 *
 * @return object
 */
function mu_get_latest_visits() {
	$args = array(
		'post_type'   => 'visit',
		'numberposts' => 150,
		'meta_key'    => 'mu_visits_date',
		'orderby'     => 'meta_value_num',
		'order'       => 'asc',
		'meta_query'  => array(
			array(
				'key'     => 'mu_visits_date',
				'value'   => date( 'Y-m-d' ),
				'compare' => '>',
				'type'    => 'DATE',
			),
		),
	);

	$posts  = get_posts( $args );
	$visits = array();

	if ( $posts ) {
		foreach ( $posts as $post ) {
			array_push(
				$visits,
				array(
					'title'                => $post->post_title,
					'mu_visits_date'       => get_post_meta( $post->ID, 'mu_visits_date', 1 ),
					'mu_visits_start_time' => Carbon::parse( get_field( 'mu_visits_start_time', $post->ID ) )->format( 'g:i a' ),
					'mu_visits_end_time'   => get_field( 'mu_visits_end_time', $post->ID ),
					'mu_visits_type'       => get_post_meta( $post->ID, 'mu_visits_type', 1 ),
				)
			);
		}
		wp_reset_postdata();
	}

	return $visits;
}

/**
 * Get the latest Visits.
 *
 * @return object
 */
function mu_get_past_visits() {
	$args = array(
		'post_type'   => 'visit',
		'numberposts' => 150,
		'meta_key'    => 'mu_visits_date',
		'orderby'     => 'meta_value_num',
		'order'       => 'desc',
		'meta_query'  => array(
			array(
				'key'     => 'mu_visits_date',
				'value'   => date( 'Y-m-d' ),
				'compare' => '<',
				'type'    => 'DATE',
			),
		),
	);

	$posts  = get_posts( $args );
	$visits = array();

	if ( $posts ) {
		foreach ( $posts as $post ) {
			array_push(
				$visits,
				array(
					'title'                => $post->post_title,
					'mu_visits_date'       => get_post_meta( $post->ID, 'mu_visits_date', 1 ),
					'mu_visits_start_time' => get_field( 'mu_visits_start_time', $post->ID ),
					'mu_visits_end_time'   => get_field( 'mu_visits_end_time', $post->ID ),
					'mu_visits_type'       => get_post_meta( $post->ID, 'mu_visits_type', 1 ),
				)
			);
		}
		wp_reset_postdata();
	}

	return $visits;
}
