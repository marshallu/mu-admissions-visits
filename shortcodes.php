<?php
/**
 * Shortcodes for the MU Admissions Visits plugin
 *
 * @package MU Admissions Visits
 */

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
	$output .= '<input x-ref="schoolSearchInput" x-model="schoolSearchTerm" type="text" class="text-input" placeholder="Enter your high school name" />';
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
	$output .= '<td class="text-center" x-text="`${moment(visit.mu_visits_date).format(\'MMMM Do YYYY\')}`"></td>';
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
	$output .= '<input x-ref="schoolSearchInput" x-model="schoolSearchTerm" type="text" class="text-input" placeholder="Enter your high school name" />';
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
	$output .= '<td class="text-center" x-text="`${moment(visit._past_date).format(\'MMMM Do YYYY\')}`"></td>';
	$output .= '<td x-text="visit.title"></td>';
	$output .= '<td class="text-center" x-text="visit._past_start_time"></td>';
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
