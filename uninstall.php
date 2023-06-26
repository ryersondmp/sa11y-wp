<?php

/**
 * Uninstall procedure for the plugin.
 */

/* If uninstall not called from WordPress exit. */
if (!defined('WP_UNINSTALL_PLUGIN'))
  exit();

/* Delete plugin settings. */
delete_option('sa11y_settings');

/* Delete all site plugin options if network admin */
function delete_sitemap_keys()
{
  global $wpdb;
  if (is_multisite() && current_user_can('manage_network')) {
    // Delete network settings.
    $keys = $wpdb->get_col("SELECT meta_key FROM {$wpdb->prefix}sitemeta WHERE meta_key LIKE 'sa11y_network_%'");
    if ($keys) {
      foreach ($keys as $key) {
        delete_site_option($key);
      }
    }

    // Delete local settings within all blogs on the network.
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    if ($blog_ids) {
      foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        delete_option('sa11y_settings');
        restore_current_blog();
      }
    }
  }
}
delete_sitemap_keys();
