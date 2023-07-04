<?php
/* ************************************************************ */
/*  Database                                                    */
/* ************************************************************ */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

function store_issue($post_id, $issue_type, $issue_details, $issue_selector) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'sa11y_issues';
	$wpdb->insert(
		$table_name,
		array(
			'post_id' => $post_id,
			'issue_type' => $issue_type,
			'issue_details' => $issue_details,
			'issue_selector' => $issue_selector,
			'time' => current_time('mysql', 1)
		)
	);
}

?>