<?php

/**
 * Sa11y, the accessibility quality assurance assistant.
 *
 * Plugin Name:       Sa11y
 * Plugin URI:        https://sa11y.netlify.app/
 * Description:       Sa11y is your accessibility quality assurance assistant. Geared towards content authors, Sa11y is an accessibility checker that straightforwardly identifies issues at the source.
 * Version:           1.1.7
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            Adam Chaboryk, Toronto Metropolitan University
 * Author URI:        https://sa11y.netlify.app
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sa11y
 * Domain Path:       /languages
 *
 * @package         Sa11y
 * @link            https://sa11y.netlify.app/
 * @author          Adam Chaboryk, Toronto Metropolitan University
 * @copyright       2020 - 2024 Toronto Metropolitan University
 * @license         GPL v2 or later
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Sa11y_WP
{

  const VERSION = '3.2.1';
  const WP_VERSION = '1.1.7';

  /**
   * Defines constants used by the plugin.
   */
  public function constants()
  {

    define('SA11Y_BASE', plugin_basename(__FILE__));

    // Set constant path to the plugin directory.
    define('SA11Y_DIR', trailingslashit(plugin_dir_path(__FILE__)));

    // Set the constant path to the plugin directory URI.
    define('SA11Y_URI', trailingslashit(plugin_dir_url(__FILE__)));

    // Set the constant path to the includes directory.
    define('SA11Y_INCLUDES', SA11Y_DIR . trailingslashit('includes'));

    // Set the constant path to the partials directory.
    define('SA11Y_PARTIALS', SA11Y_DIR . trailingslashit('partials'));

    // Set the constant path to the admin directory.
    define('SA11Y_ADMIN', SA11Y_DIR . trailingslashit('admin'));

    // Set the constant path to the assets directory.
    define('SA11Y_ASSETS', SA11Y_URI . trailingslashit('assets'));

    /* ************** */
    /*  HELPERS       */
    /* ************** */

    // Allowed HTML for all translatable text strings.
    define('SA11Y_ALLOWED_HTML', [
      'em' => [],
      'strong' => [],
      'code' => [],
    ]);

    // Common input field attributes & pattern validation for TEXT fields.
    define(
      'SA11Y_TEXT_FIELD',
      'type="text" autocomplete="off" maxlength="400" class="regular-text" pattern="[^<]*"'
    );

    // Common input field attributes & pattern validation for TEXT fields used for video and audio sources.
    define(
      'SA11Y_TEXT_FIELD_EXTRA',
      'type="text" autocomplete="off" maxlength="400" class="regular-text" pattern="[A-Za-z0-9,.\- ]*"'
    );

    // Common input field attributes & pattern validation for TARGET fields.
    define(
      'SA11Y_TARGET_FIELD',
      'type="text" autocomplete="off" maxlength="400" pattern="[^\s<>]*"'
    );

    // Common input field attributes for TEXTAREA.
    define(
      'SA11Y_TEXTAREA',
      'autocomplete="off" cols="45" rows="3" maxlength="400"'
    );
  }

  /**
   * Register global settings.
   */
  public function sa11y_default_network_options()
  {
    if (is_multisite() && current_user_can('manage_network_options')) {
      update_site_option('sa11y_network_panel_position', 'right');
      update_site_option('sa11y_network_form_labels', 1);
      update_site_option('sa11y_network_contrast', 1);
      update_site_option('sa11y_network_links_advanced', 1);
      update_site_option('sa11y_network_colour_filter', 1);
      update_site_option('sa11y_network_all_checks', 0);
      update_site_option('sa11y_network_readability', 1);
    }
  }

  /**
   * Loads the translation files.
   */
  public function i18n()
  {
    load_plugin_textdomain('sa11y-i18n', false, dirname(plugin_basename(__FILE__)) . '/languages/');
  }

  /**
   * Loads the initial files needed by the plugin.
   */
  public function includes()
  {
    require_once(SA11Y_INCLUDES . 'functions.php');
    require_once(SA11Y_INCLUDES . 'strings.php');
    require_once(SA11Y_INCLUDES . 'sanitize.php');
  }

  /**
   * Construct & add actions.
   */
  public function __construct()
  {
    // Set the constants needed by the plugin.
    add_action('plugins_loaded', array(&$this, 'constants'), 1);

    // Internationalize the text strings used.
    add_action('plugins_loaded', array(&$this, 'i18n'), 2);

    // Load the functions files.
    add_action('plugins_loaded', array(&$this, 'includes'), 3);

    // Load the admin files.
    add_action('plugins_loaded', array(&$this, 'admin'), 4);

    // Register activation hook.
    register_activation_hook(__FILE__, array($this, 'sa11y_default_network_options'));
  }

  /**
   * Loads the admin functions and files.
   */
  public function admin()
  {
    if (is_network_admin()) {
      require_once(SA11Y_ADMIN . 'network-admin.php');
    }

    if (is_admin()) {
      require_once(SA11Y_ADMIN . 'admin.php');
    }
  }
}

new Sa11y_WP;
