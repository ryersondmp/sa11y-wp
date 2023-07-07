<?php
/* ************************************************************ */
/*  Database                                                    */
/* ************************************************************ */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

function store_issue($post_id, $issue_type, $issue_details, $issue_selector)
{
  global $wpdb;

  if (is_multisite()) {
    $blog_id = get_current_blog_id();
    $table_name = $wpdb->prefix . $blog_id . '_sa11y_issues';
  } else {
    $table_name = $wpdb->prefix . 'sa11y_issues';
  }

  global $wpdb;
  $wpdb->insert(
    $table_name,
    [
      'post_id' => $post_id,
      'issue_type' => $issue_type,
      'issue_details' => $issue_details,
      'issue_selector' => $issue_selector,
      'time' => current_time('mysql', 1)
    ]
  );
}
