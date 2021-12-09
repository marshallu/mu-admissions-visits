<?php
/**
 * Shortcodes for the MU Admissions Visits plugin
 *
 * @package MU Admissions Visits
 */

require WP_PLUGIN_DIR . '/mu-admissions-visits/vendor/autoload.php';
use Carbon\Carbon;


/**
 * Shortcode to list the Visits
 *
 * @param object $atts The attribtes includes with the shortcode.
 * @param string $content The HTML content.
 * @return string
 */
function mu_visits( $atts, $content = null ) {
	$data = shortcode_atts(
		array(
			'department' => null,
			'type'       => 'table',
		),
		$atts
	);

	$output  = '<div class="" x-data="{ visits: [], schoolSearchTerm: \'\' }" x-init="fetch(\'//www.marshall.edu/recruitment/wp-json/marsha/v1/visits\').then(res => {
		return res.json();
	}).then(visitsJson => {
		visits = visitsJson;
	});">';
	$output .= '<div class="my-6 w-full">';
	$output .= '<label for="highschool" class="font-semibold block mb-1">Find My High School</label>';
	$output .= '<input class="text-input w-full lg:w-1/2" x-ref="schoolSearchInput" x-model="schoolSearchTerm" type="text" placeholder="Enter your high school name" />';
	$output .= '</div>';
	$output .= '<div class="large-table">';
	$output .= '<table class="table table-bordered table-striped w-full">';
	$output .= '<thead>';
	$output .= '<tr>';
	$output .= '<th>Date</th>';
	$output .= '<th>High School</th>';
	$output .= '<th>Start Time</th>';
	$output .= '<th>End Time</th>';
	$output .= '<th>Visit Type</th>';
	$output .= '</tr>';
	$output .= '</thead>';
	$output .= '<tbody>';
	$output .= '<template x-for="(visit, index) in visits" :key="index">';
	$output .= '<tr :class="{ \'hidden\' : !visit.title.toLowerCase().includes(schoolSearchTerm.toLowerCase() ) }">';
	$output .= '<td class="text-left" x-text="`${moment(visit.mu_visits_date).format(\'MMMM Do YYYY\')}`"></td>';
	$output .= '<td x-text="visit.title"></td>';
	$output .= '<td class="text-center" x-text="visit.mu_visits_start_time"></td>';
	$output .= '<td class="text-center" x-text="visit.mu_visits_end_time"></td>';
	$output .= '<td x-text="visit.mu_visits_type"></td>';
	$output .= '</tr>';
	$output .= '</template>';
	$output .= '</tbody>';
	$output .= '</table>';
	$output .= '</div>';
	$output .= '</div>';
	return $output;
}
add_shortcode( 'mu_visits', 'mu_visits' );

/**
 * Shortcode to list the Visits
 *
 * @param object $atts The attribtes includes with the shortcode.
 * @param string $content The HTML content.
 * @return string
 */
function mu_visits_past( $atts, $content = null ) {
	$data = shortcode_atts(
		array(
			'department' => null,
			'type'       => 'table',
		),
		$atts
	);

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

	$the_query = new WP_Query( $args );

	$output  = '<div class="">';
	$output .= '<div class="my-6 w-full">';
	$output .= '<form action="' . esc_url( home_url() ) . '/search-visits" method="GET">';
	$output .= '<label for="highschool" class="font-semibold block mb-1">Find My High School</label>';
	$output .= '<input class="text-input w-full lg:w-1/2" name="school" id="school" type="text" placeholder="Enter your high school name" />';
	$output .= '<button type="submit" class="hidden">Search</button>';
	$output .= '</form>';
	$output .= '</div>';
	$output .= '<div class="large-table">';
	$output .= '<table class="table table-bordered table-striped w-full">';
	$output .= '<thead>';
	$output .= '<tr>';
	$output .= '<th>Date</th>';
	$output .= '<th>High School</th>';
	$output .= '<th>Start Time</th>';
	$output .= '<th>End Time</th>';
	$output .= '<th>Visit Type</th>';
	$output .= '</tr>';
	$output .= '</thead>';
	$output .= '<tbody>';

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$output .= '<tr>';
			$output .= '<td class="text-left">' . esc_attr( Carbon::parse( get_field( 'mu_visits_start_time', get_the_ID() ) )->format( 'F j, Y' ) ) . '</td>';
			$output .= '<td>' . esc_attr( get_the_title() ) . '</td>';
			if ( get_field( get_field( 'mu_visits_start_time', get_the_ID() ) ) ) {
				$output .= '<td class="text-center">' . esc_attr( Carbon::parse( get_field( 'mu_visits_start_time', get_the_ID() ) )->format( 'g:i a' ) ) . '</td>';
			} else {
				$output .= '<td class="text-center"></td>';
			}
			if ( get_field( get_field( 'mu_visits_end_time', get_the_ID() ) ) ) {
				$output .= '<td class="text-center">' . esc_attr( Carbon::parse( get_field( 'mu_visits_end_time', get_the_ID() ) )->format( 'g:i a' ) ) . '</td>';
			} else {
				$output .= '<td class="text-center"></td>';
			}
			$output .= '<td>' . esc_attr( get_field( 'mu_visits_type', get_the_ID() ) ) . '</td>';
			$output .= '</tr>';
		}
	}

	wp_reset_postdata();
	$output .= '</tbody>';
	$output .= '</table>';
	$output .= '</div>';
	$output .= '</div>';
	return $output;
}
add_shortcode( 'mu_visits_past', 'mu_visits_past' );

/**
 * Shortcode to import visits
 *
 * @param object $atts The attribtes includes with the shortcode.
 * @param string $content The HTML content.
 * @return string
 */
function mu_visits_import( $atts, $content = null ) {
	$data = shortcode_atts(
		array(
			'filename' => null,
		),
		$atts
	);

	$file_path = plugin_dir_path( __FILE__ ) . 'csv/' . $data['filename'];
	if ( ! file_exists( $file_path ) ) {
		return 'No such file at ' . $file_path;
	}

	$file = fopen( $file_path, 'r' );

	while ( ( $line = fgetcsv( $file ) ) !== false ) {
		$school_name = trim( $line[0] );
		$visit_date  = trim( $line[1] );
		$visit_type  = trim( $line[2] );

		$posts_with_meta = get_posts(
			array(
				'post_type'      => 'visit',
				'posts_per_page' => 1,
				'meta_key'       => 'mu_visits_date',
				'meta_value'     => Carbon::parse( $visit_date )->format( 'Ymd' ),
				'title'          => $school_name,
				'fields'         => 'ids',
			)
		);

		if ( ! $posts_with_meta ) {
			wp_insert_post(
				array(
					'post_type'   => 'visit',
					'post_status' => 'publish',
					'post_title'  => $school_name,
					'meta_input'  => array(
						'mu_visits_date' => $visit_type,
						'mu_visits_date' => Carbon::parse( $visit_date )->format( 'Ymd' ),
					),
				),
			);
		} else {
			echo 'Visit for ' . esc_attr( $school_name ) . ' already created.<br><br>';
		}
	}

	fclose( $file );
}
add_shortcode( 'mu_visits_import', 'mu_visits_import' );

/**
 * Shortcode to show search results
 *
 * @param object $atts The attribtes includes with the shortcode.
 * @param string $content The HTML content.
 * @return string
 */
function mu_visits_search( $atts, $content = null ) {
	$data = shortcode_atts(
		array(
			'department' => null,
			'type'       => 'table',
		),
		$atts
	);

	if ( get_query_var( 'school' ) ) {
		$school = esc_attr( get_query_var( 'school' ) );
	} else {
		$school = '';
	}

	$future_args = array(
		'post_type'   => 'visit',
		'numberposts' => 200,
		'meta_key'    => 'mu_visits_date',
		'orderby'     => 'meta_value_num',
		'order'       => 'asc',
		's'           => $school,
		'meta_query'  => array(
			array(
				'key'     => 'mu_visits_date',
				'value'   => date( 'Y-m-d' ),
				'compare' => '>=',
				'type'    => 'DATE',
			),
		),
	);

	$future_query = new WP_Query( $future_args );

	$output  = '<div class="">';
	$output .= '<div class="my-6 w-full">';
	$output .= '<form action="' . esc_url( home_url() ) . '/search-visits" method="GET">';
	$output .= '<label for="highschool" class="font-semibold block mb-1">Find My High School</label>';
	$output .= '<input class="text-input w-full lg:w-1/2" name="school" id="school" value="' . esc_attr( $school ) . '" type="text" placeholder="Enter your high school name" />';
	$output .= '<button type="submit" class="hidden">Search</button>';
	$output .= '</form>';
	$output .= '</div>';
	$output .= '<div>';
	$output .= '<h2>Upcoming Visits</h2>';
	if ( $future_query->have_posts() ) {
		$output .= '<div class="large-table">';
		$output .= '<table class="table table-bordered table-striped w-full">';
		$output .= '<thead>';
		$output .= '<tr>';
		$output .= '<th>Date</th>';
		$output .= '<th>High School</th>';
		$output .= '<th>Start Time</th>';
		$output .= '<th>End Time</th>';
		$output .= '<th>Visit Type</th>';
		$output .= '</tr>';
		$output .= '</thead>';
		$output .= '<tbody>';

		while ( $future_query->have_posts() ) {
			$future_query->the_post();
			$output .= '<tr>';
			$output .= '<td class="text-left">' . esc_attr( Carbon::parse( get_field( 'mu_visits_start_time', get_the_ID() ) )->format( 'F j, Y' ) ) . '</td>';
			$output .= '<td>' . esc_attr( get_the_title() ) . '</td>';
			if ( get_field( get_field( 'mu_visits_start_time', get_the_ID() ) ) ) {
				$output .= '<td class="text-center">' . esc_attr( Carbon::parse( get_field( 'mu_visits_start_time', get_the_ID() ) )->format( 'g:i a' ) ) . '</td>';
			} else {
				$output .= '<td class="text-center"></td>';
			}
			if ( get_field( get_field( 'mu_visits_end_time', get_the_ID() ) ) ) {
				$output .= '<td class="text-center">' . esc_attr( Carbon::parse( get_field( 'mu_visits_end_time', get_the_ID() ) )->format( 'g:i a' ) ) . '</td>';
			} else {
				$output .= '<td class="text-center"></td>';
			}
			$output .= '<td>' . esc_attr( get_field( 'mu_visits_type', get_the_ID() ) ) . '</td>';
			$output .= '</tr>';
		}

		wp_reset_postdata();
		$output .= '</tbody>';
		$output .= '</table>';
		$output .= '</div>';
		$output .= '</div>';
	} else {
		$output .= 'No future visits scheduled matching that search term.';
	}
	$output .= '</div>';

	$past_args = array(
		'post_type'   => 'visit',
		'numberposts' => 200,
		'meta_key'    => 'mu_visits_date',
		'orderby'     => 'meta_value_num',
		'order'       => 'asc',
		's'           => $school,
		'meta_query'  => array(
			array(
				'key'     => 'mu_visits_date',
				'value'   => date( 'Y-m-d' ),
				'compare' => '<',
				'type'    => 'DATE',
			),
		),
	);

	$past_query = new WP_Query( $past_args );

	$output .= '<div class="mt-12">';
	$output .= '<h2>Past Visits</h2>';
	if ( $past_query->have_posts() ) {
		$output .= '<div class="large-table">';
		$output .= '<table class="table table-bordered table-striped w-full">';
		$output .= '<thead>';
		$output .= '<tr>';
		$output .= '<th>Date</th>';
		$output .= '<th>High School</th>';
		$output .= '<th>Start Time</th>';
		$output .= '<th>End Time</th>';
		$output .= '<th>Visit Type</th>';
		$output .= '</tr>';
		$output .= '</thead>';
		$output .= '<tbody>';

		while ( $past_query->have_posts() ) {
			$past_query->the_post();
			$output .= '<tr>';
			$output .= '<td class="text-left">' . esc_attr( Carbon::parse( get_field( 'mu_visits_start_time', get_the_ID() ) )->format( 'F j, Y' ) ) . '</td>';
			$output .= '<td>' . esc_attr( get_the_title() ) . '</td>';
			if ( get_field( get_field( 'mu_visits_start_time', get_the_ID() ) ) ) {
				$output .= '<td class="text-center">' . esc_attr( Carbon::parse( get_field( 'mu_visits_start_time', get_the_ID() ) )->format( 'g:i a' ) ) . '</td>';
			} else {
				$output .= '<td class="text-center"></td>';
			}
			if ( get_field( get_field( 'mu_visits_end_time', get_the_ID() ) ) ) {
				$output .= '<td class="text-center">' . esc_attr( Carbon::parse( get_field( 'mu_visits_end_time', get_the_ID() ) )->format( 'g:i a' ) ) . '</td>';
			} else {
				$output .= '<td class="text-center"></td>';
			}
			$output .= '<td>' . esc_attr( get_field( 'mu_visits_type', get_the_ID() ) ) . '</td>';
			$output .= '</tr>';
		}

		wp_reset_postdata();
		$output .= '</tbody>';
		$output .= '</table>';
		$output .= '</div>';
	} else {
		$output .= 'No past visits scheduled matching that search term.';
	}
	$output .= '</div>';

	return $output;
}
add_shortcode( 'mu_visits_search', 'mu_visits_search' );
