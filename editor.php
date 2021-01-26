<?php
/**
 * Customization of the editor for Visits custom post type
 *
 * @package MU Admissions Visits
 */

/**
 * Remove Yoast SEO metaboxes from Visits custom post type
 */
function mu_admissions_visits_remove_yoast_seo_metabox() {
	remove_meta_box( 'wpseo_meta', 'visit', 'normal' );
}
add_action( 'add_meta_boxes', 'mu_admissions_visits_remove_yoast_seo_metabox', 11 );

/**
 * Set custom columns for listings of Visits custom post type
 *
 * @param object $columns The object containing all the columns for posts.
 * @return object
 */
function mu_admissions_visits_custom_admin_columns( $columns ) {
	unset( $columns['date'] );
	unset( $columns['modified'] );
	unset( $columns['wpseo-score'] );
	unset( $columns['wpseo-score-readability'] );
	unset( $columns['wpseo-title'] );
	unset( $columns['wpseo-metadesc'] );
	unset( $columns['wpseo-focuskw'] );
	unset( $columns['wpseo-links'] );
	unset( $columns['wpseo-linked'] );

	$columns = array(
		'cb'         => $columns['cb'],
		'title'      => $columns['title'],
		'visit_date' => __( 'Visit Date' ),
	);

	return $columns;
}
add_filter( 'manage_visit_posts_columns', 'mu_admissions_visits_custom_admin_columns' );

/**
 * Creating the data for the Visit Date column
 *
 * @param string  $column The column name.
 * @param integer $post_id The ID of the post.
 * @return void
 */
function mu_admissions_visits_date_column( $column, $post_id ) {
	if ( 'visit_date' === $column ) {
		echo date( 'F j, Y', strtotime( get_post_meta( $post_id, 'mu_visits_date', true, 1 ) ) );
	}
}
add_action( 'manage_visit_posts_custom_column', 'mu_admissions_visits_date_column', 10, 2 );

/**
 * Changing the placeholder text for post for the Visit custom post type
 *
 * @param string $title The title of the post.
 * @return string
 */
function mu_admissions_visits_change_default_name_for_visits_post_type( $title ) {
	$screen = get_current_screen();

	if ( 'visit' === $screen->post_type ) {
		return 'Enter High School Name';
	}
}
add_filter( 'enter_title_here', 'mu_admissions_visits_change_default_name_for_visits_post_type' );
