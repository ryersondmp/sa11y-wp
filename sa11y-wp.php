<?php

/**
 * Sa11y, the accessibility quality assurance assistant.
 * 
 * Plugin Name:       Sa11y
 * Plugin URI:        https://ryersondmp.github.io/sa11y
 * Description:       Sa11y is your accessibility quality assurance assistant. Geared towards content authors, Sa11y straightforwardly identifies accessibility issues at the source.
 * Version:           1.0.3
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            Adam Chaboryk, Ryerson University
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sa11y
 * Domain Path:       /languages
 * 
 * @package         Sa11y
 * @link            https://ryersondmp.github.io/sa11y
 * @author          Adam Chaboryk, Ryerson University
 * @copyright       2022 Ryerson University
 * @license         GPL v2 or later
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Sa11y_WP {

    /**
     * PHP5 constructor method.
     */
    public function __construct() {

        // Set the constants needed by the plugin.
        add_action('plugins_loaded', array(&$this, 'constants'), 1);

        // Internationalize the text strings used.
        add_action('plugins_loaded', array(&$this, 'i18n'), 2);

        // Load the functions files.
        add_action('plugins_loaded', array(&$this, 'includes'), 3);

        // Load the admin files.
        add_action('plugins_loaded', array(&$this, 'admin'), 4);
    }

    /**
     * Defines constants used by the plugin.
     */
    public function constants() {

        define( 'SA11Y_BASE', plugin_basename( __FILE__ ));

        // Set constant path to the plugin directory.
        define('SA11Y_DIR', trailingslashit(plugin_dir_path(__FILE__)));

        // Set the constant path to the plugin directory URI.
        define('SA11Y_URI', trailingslashit(plugin_dir_url(__FILE__)));

        // Set the constant path to the inc directory.
        define('SA11Y_INCLUDES', SA11Y_DIR . trailingslashit('inc'));

        // Set the constant path to the admin directory.
        define('SA11Y_ADMIN', SA11Y_DIR . trailingslashit('admin'));

        // Set the constant path to the assets directory.
        define('SA11Y_ASSETS', SA11Y_URI . trailingslashit('assets'));
    }

    /**
     * Loads the translation files.
     */
    public function i18n() {
        load_plugin_textdomain('sa11y-wp', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Loads the initial files needed by the plugin.
     */
    public function includes() {
        require_once(SA11Y_INCLUDES . 'functions.php');
    }

    /**
     * Loads the admin functions and files.
     */
    public function admin() {
        if (is_admin()) {
            require_once(SA11Y_ADMIN . 'admin.php');
        }
    }

}

new Sa11y_WP;
