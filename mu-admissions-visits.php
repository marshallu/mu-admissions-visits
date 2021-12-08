<?php
/**
 * MU Admissions Visits
 *
 * This plugin was built to allow for Marshall University's Admissions office to list high school visits on the
 * recruting website.
 *
 * @package MU Admissions Visits
 *
 * Plugin Name: MU Admissions Visits
 * Plugin URI: https://www.marshall.edu
 * Description: A high school visit listing plugin for Marshall University Admissions.
 * Version: 1.0
 * Author: Christopher McComas
 */

if ( ! class_exists( 'ACF' ) ) {
	return new WP_Error( 'broke', __( 'Advanced Custom Fields is required for this plugin.', 'mu-admissions-visits' ) );
}

require WP_PLUGIN_DIR . '/mu-admissions-visits/vendor/autoload.php';
use Carbon\Carbon;

require plugin_dir_path( __FILE__ ) . '/editor.php';
require plugin_dir_path( __FILE__ ) . '/endpoints.php';
require plugin_dir_path( __FILE__ ) . '/shortcodes.php';
require plugin_dir_path( __FILE__ ) . '/acf-fields.php';

/**
 * Register a custom post type called "Visit".
 *
 * @see get_post_type_labels() for label keys.
 */
function mu_admissions_visits_post_type() {
	$labels = array(
		'name'                  => _x( 'Visits', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Visit', 'Post type singular name', 'textdomain' ),
		'menu_name'             => _x( 'Visits', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar'        => _x( 'Visit', 'Add New on Toolbar', 'textdomain' ),
		'add_new'               => __( 'Add New', 'textdomain' ),
		'add_new_item'          => __( 'Add New Visit', 'textdomain' ),
		'new_item'              => __( 'New Visit', 'textdomain' ),
		'edit_item'             => __( 'Edit Visit', 'textdomain' ),
		'view_item'             => __( 'View Visit', 'textdomain' ),
		'all_items'             => __( 'All Visits', 'textdomain' ),
		'search_items'          => __( 'Search Visits', 'textdomain' ),
		'parent_item_colon'     => __( 'Parent Visits:', 'textdomain' ),
		'not_found'             => __( 'No Visits found.', 'textdomain' ),
		'not_found_in_trash'    => __( 'No Visits found in Trash.', 'textdomain' ),
		'featured_image'        => _x( 'Visit Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'textdomain' ),
		'set_featured_image'    => _x( 'Set image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'textdomain' ),
		'remove_featured_image' => _x( 'Remove image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'textdomain' ),
		'use_featured_image'    => _x( 'Use as image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'textdomain' ),
		'archives'              => _x( 'Visit archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'textdomain' ),
		'insert_into_item'      => _x( 'Insert into Visit', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Visit', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
		'filter_items_list'     => _x( 'Filter Visits list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list". Added in 4.4', 'textdomain' ),
		'items_list_navigation' => _x( 'Visits list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation". Added in 4.4', 'textdomain' ),
		'items_list'            => _x( 'Visits list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list". Added in 4.4', 'textdomain' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'visit' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'custom-fields', 'page-attributes', 'revisions' ),
		'menu_icon'          => 'dashicons-location',
	);

	register_post_type( 'visit', $args );
}

/**
 * Flush rewrites whenever the plugin is activated.
 */
function mu_admissions_visits_activate() {
	flush_rewrite_rules( false );
}
register_activation_hook( __FILE__, 'mu_admissions_visits_activate' );

/**
 * Flush rewrites whenever the plugin is deactivated, also unregister 'visit' post type.
 */
function mu_admissions_visits_deactivate() {
	unregister_post_type( 'visit' );
	flush_rewrite_rules( false );
}
register_deactivation_hook( __FILE__, 'mu_profiles_deactivate' );

/**
 * Proper way to enqueue scripts and styles
 */
function mu_admissions_visits_scripts() {
	wp_enqueue_script( 'momentjs', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js', '', true, true );
}
add_action( 'wp_enqueue_scripts', 'mu_admissions_visits_scripts' );

add_action( 'init', 'mu_admissions_visits_post_type' );
