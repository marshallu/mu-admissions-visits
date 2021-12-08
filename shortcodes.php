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

	$output  = '<div class="" x-data="{ visits: [], schoolSearchTerm: \'\' }" x-init="fetch(\'//www.marshall.edu/recruitment/wp-json/marsha/v1/visits-past\').then(res => {
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
		$visit_date  = $line[1];
		$visit_type  = $line[2];

		wp_insert_post(
			array(
				'post_type'   => 'visit',
				'post_status' => 'publish',
				'post_title'  => $school_name,
				'meta_input'  => array(
					'mu_visits_type' => trim( $visit_type ),
					'mu_visits_date' => Carbon::parse( trim( $visit_date ) )->format( 'Ymd' ),
				),
			),
		);
	}

	fclose( $file );
}
add_shortcode( 'mu_visits_import', 'mu_visits_import' );
