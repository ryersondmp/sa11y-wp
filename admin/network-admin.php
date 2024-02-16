<?php

/**
 * Thanks to Claude Vedovini's article "Using the WordPress Settings API with
 * helped with the creation of this page!
 * https://vedovini.net/2015/10/04/using-the-wordpress-settings-api-with-network-admin-pages/
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

/* ************************************************************ */
/*  Create/register network admin menu and options.             */
/* ************************************************************ */
function sa11y_network_admin_menu()
{
  /* *********************************** */
  /*  Create options page.               */
  /* *********************************** */
  $networkSettings = add_submenu_page(
    'settings.php',
    esc_html__('Sa11y - Settings for Network Admins', 'sa11y-i18n'),
    esc_html__('Sa11y', 'sa11y-i18n'),
    'manage_network_options',
    'sa11y_network_options_page',
    'sa11y_network_options_page_callback'
  );

  if (!$networkSettings) {
    return;
  }
  // Provided hook_suffix that's returned to add scripts only on settings page.
  add_action('load-' . $networkSettings, 'sa11y_network_styles_scripts');
}
add_action('network_admin_menu', 'sa11y_network_admin_menu');

/* ************************************************************ */
/*  Enqueue plugin admin CSS styles.                            */
/* ************************************************************ */
function sa11y_network_styles_scripts()
{
  wp_enqueue_style('sa11y-wp', trailingslashit(SA11Y_ASSETS) . 'css/sa11y-wp-admin.css', null);
}

/* ************************************************************ */
/*  Add & Register Settings fields.                             */
/* ************************************************************ */
function sa11y_network_fields()
{
  /* ***************** */
  /*  Create Sections  */
  /* ***************** */
  // 1. Create "General" section.
  add_settings_section(
    'general',
    esc_html__(SA11Y_SECTION["GENERAL"]),
    'network_general_callback',
    'sa11y_network_options_page'
  );

  // 2. Create "Additional Checks" section.
  add_settings_section(
    'additional-checks',
    esc_html__(SA11Y_SECTION["ADDITIONAL"]),
    'network_additional_checks_callback',
    'sa11y_network_options_page'
  );

  // 3. Create "Readability" section.
  add_settings_section(
    'readability',
    esc_html__(SA11Y_SECTION["READABILITY"]),
    '__return_false',
    'sa11y_network_options_page'
  );

  // 4. Create "Exclusions" section.
  add_settings_section(
    'exclusions',
    esc_html__(SA11Y_SECTION["EXCLUSIONS"]),
    'network_exclusions_callback',
    'sa11y_network_options_page'
  );

  // 5. Create "Embedded Content" section.
  add_settings_section(
    'embedded-content',
    esc_html__(SA11Y_SECTION["EMBEDDED"]),
    'network_embedded_callback',
    'sa11y_network_options_page'
  );

  // 6. Create "Advanced settings" section.
  add_settings_section(
    'advanced-settings',
    esc_html__(SA11Y_SECTION["ADVANCED"]),
    'network_advanced_settings_callback',
    'sa11y_network_options_page'
  );

  /* **************** */
  /*  Create options  */
  /* **************** */

  // Option: Target area to check
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_target',
    'sa11y_sanitize_target_fields'
  );
  add_settings_field(
    'sa11y_network_target',
    esc_html__(SA11Y_LABEL["TARGET"]),
    'sa11y_network_target_callback',
    'sa11y_network_options_page',
    'general',
    ['label_for' => 'sa11y_network_target']
  );

  // Option: Panel Position
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_panel_position',
    'sa11y_network_validate_panel_position'
  );
  add_settings_field(
    'sa11y_network_panel_position',
    esc_html__(SA11Y_LABEL["POSITION"]),
    'sa11y_network_panel_position_callback',
    'sa11y_network_options_page',
    'general',
  );

  // Option: Contrast (Additional Checks)
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_contrast',
    'sa11y_sanitize_checkboxes'
  );
  add_settings_field(
    'sa11y_network_contrast',
    esc_html__(SA11Y_LABEL["CONTRAST"]),
    'sa11y_network_contrast_callback',
    'sa11y_network_options_page',
    'additional-checks',
    ['label_for' => 'sa11y_network_contrast']
  );

  // Option: Form Labels (Additional Checks)
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_form_labels',
    'sa11y_sanitize_checkboxes'
  );
  add_settings_field(
    'sa11y_network_form_labels',
    esc_html__(SA11Y_LABEL["FORM_LABELS"]),
    'sa11y_network_form_labels_callback',
    'sa11y_network_options_page',
    'additional-checks',
    ['label_for' => 'sa11y_network_form_labels']
  );

  // Option: Links Advanced (Additional Checks)
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_links_advanced',
    'sa11y_sanitize_checkboxes'
  );
  add_settings_field(
    'sa11y_network_links_advanced',
    esc_html__(SA11Y_LABEL["LINKS_ADVANCED"]),
    'sa11y_network_links_advanced_callback',
    'sa11y_network_options_page',
    'additional-checks',
    ['label_for' => 'sa11y_network_links_advanced']
  );

  // Option: Colour Filter
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_colour_filter',
    'sa11y_sanitize_checkboxes'
  );
  add_settings_field(
    'sa11y_network_colour_filter',
    esc_html__(SA11Y_LABEL["COLOUR_FILTER"]),
    'sa11y_network_colour_filter_callback',
    'sa11y_network_options_page',
    'additional-checks',
    ['label_for' => 'sa11y_network_colour_filter']
  );

  // Option: Make all additional checks required by default (Additional Checks)
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_all_checks',
    'sa11y_sanitize_checkboxes'
  );
  add_settings_field(
    'sa11y_network_all_checks',
    esc_html__(SA11Y_LABEL["ALL_CHECKS"]),
    'sa11y_network_all_checks_callback',
    'sa11y_network_options_page',
    'additional-checks',
    ['label_for' => 'sa11y_network_all_checks']
  );

  // Option: Readability plugin
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_readability',
    'sa11y_sanitize_checkboxes'
  );
  add_settings_field(
    'sa11y_network_readability',
    esc_html__(SA11Y_LABEL["READABILITY"]),
    'sa11y_network_readability_callback',
    'sa11y_network_options_page',
    'readability',
    ['label_for' => 'sa11y_network_readability']
  );

  // Option: Readability target area
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_readability_target',
    'sa11y_sanitize_target_fields'
  );
  add_settings_field(
    'sa11y_network_readability_target',
    esc_html__(SA11Y_LABEL["READABILITY_TARGET"]),
    'sa11y_network_readability_target_callback',
    'sa11y_network_options_page',
    'readability',
    ['label_for' => 'sa11y_network_readability_target']
  );

  // Option: Readability exclusions
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_readability_ignore',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_readability_ignore',
    esc_html__(SA11Y_LABEL["READABILITY_EXCLUSIONS"]),
    'sa11y_network_readability_ignore_callback',
    'sa11y_network_options_page',
    'readability',
    ['label_for' => 'sa11y_network_readability_ignore']
  );

  // Option: Region ignore
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_region_ignore',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_region_ignore',
    esc_html__(SA11Y_LABEL["REGION_IGNORE"]),
    'sa11y_network_region_ignore_callback',
    'sa11y_network_options_page',
    'exclusions',
    ['label_for' => 'sa11y_network_region_ignore']
  );

  // Option: Contrast exclusions
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_contrast_ignore',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_contrast_ignore',
    esc_html__(SA11Y_LABEL["CONTRAST_IGNORE"]),
    'sa11y_network_contrast_ignore_callback',
    'sa11y_network_options_page',
    'exclusions',
    ['label_for' => 'sa11y_network_contrast_ignore']
  );

  // Option: Exclude headings from outline
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_outline_ignore',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_outline_ignore',
    esc_html__(SA11Y_LABEL["OUTLINE_IGNORE"]),
    'sa11y_network_outline_ignore_callback',
    'sa11y_network_options_page',
    'exclusions',
    ['label_for' => 'sa11y_network_outline_ignore']
  );

  // Option: Exclude headings
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_header_ignore',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_header_ignore',
    esc_html__(SA11Y_LABEL["HEADING_IGNORE"]),
    'sa11y_network_header_ignore_callback',
    'sa11y_network_options_page',
    'exclusions',
    ['label_for' => 'sa11y_network_header_ignore']
  );

  // Option: Exclude images
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_image_ignore',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_image_ignore',
    esc_html__(SA11Y_LABEL["IMAGE_IGNORE"]),
    'sa11y_network_image_ignore_callback',
    'sa11y_network_options_page',
    'exclusions',
    ['label_for' => 'sa11y_network_image_ignore']
  );

  // Option: Exclude links
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_link_ignore',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_link_ignore',
    esc_html__(SA11Y_LABEL["LINK_IGNORE"]),
    'sa11y_network_link_ignore_callback',
    'sa11y_network_options_page',
    'exclusions',
    ['label_for' => 'sa11y_network_link_ignore']
  );

  // Option: Ignore elements within links
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_link_ignore_span',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_link_ignore_span',
    esc_html__(SA11Y_LABEL["LINK_IGNORE_SPAN"]),
    'sa11y_network_link_ignore_span_callback',
    'sa11y_network_options_page',
    'exclusions',
    ['label_for' => 'sa11y_network_link_ignore_span']
  );

  // Option: Flag links as an error
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_link_flag',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_link_flag',
    esc_html__(SA11Y_LABEL["FLAG_LINKS"]),
    'sa11y_network_link_flag_callback',
    'sa11y_network_options_page',
    'exclusions',
    ['label_for' => 'sa11y_network_link_flag']
  );

  // Option: Video sources
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_video',
    'sa11y_sanitize_extra_text_fields'
  );
  add_settings_field(
    'sa11y_network_video',
    esc_html__(SA11Y_LABEL["VIDEO"]),
    'sa11y_network_video_callback',
    'sa11y_network_options_page',
    'embedded-content',
    ['label_for' => 'sa11y_network_video']
  );

  // Option: Audio sources
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_audio',
    'sa11y_sanitize_extra_text_fields'
  );
  add_settings_field(
    'sa11y_network_audio',
    esc_html__(SA11Y_LABEL["AUDIO"]),
    'sa11y_network_audio_callback',
    'sa11y_network_options_page',
    'embedded-content',
    ['label_for' => 'sa11y_network_audio']
  );

  // Option: Data viz sources
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_dataViz',
    'sa11y_sanitize_extra_text_fields'
  );
  add_settings_field(
    'sa11y_network_dataViz',
    esc_html__(SA11Y_LABEL["DATAVIZ"]),
    'sa11y_network_dataViz_callback',
    'sa11y_network_options_page',
    'embedded-content',
    ['label_for' => 'sa11y_network_dataViz']
  );

  // Option: Do not run
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_noRun',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_noRun',
    esc_html__(SA11Y_LABEL["TURN_OFF"]),
    'sa11y_network_noRun_callback',
    'sa11y_network_options_page',
    'advanced-settings',
    ['label_for' => 'sa11y_network_noRun']
  );

  // Option: Shadow components
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_shadow_components',
    'sa11y_sanitize_text_fields'
  );
  add_settings_field(
    'sa11y_network_shadow_components',
    esc_html__(SA11Y_LABEL["SHADOW"]),
    'sa11y_network_shadow_components_callback',
    'sa11y_network_options_page',
    'advanced-settings',
    ['label_for' => 'sa11y_network_shadow_components']
  );

  // Option: Extra props
  register_setting(
    'sa11y_network_options_page',
    'sa11y_network_extra_props',
    'sa11y_sanitize_textarea_fields'
  );
  add_settings_field(
    'sa11y_network_extra_props',
    esc_html__(SA11Y_LABEL["PROPS"]),
    'sa11y_network_extra_props_callback',
    'sa11y_network_options_page',
    'advanced-settings',
    ['label_for' => 'sa11y_network_extra_props']
  );
}
add_action('network_admin_menu', 'sa11y_network_fields');

/* ************************************************************ */
/*  Options                                                     */
/* ************************************************************ */

/* General section description */
function network_general_callback()
{
?>
  <p class="network-admin-note">
    <?php echo wp_kses(SA11Y_DESC["NETWORK_OVERRIDE_SECTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Target area to check
function sa11y_network_target_callback()
{
  $option = get_site_option('sa11y_network_target');
?>
  <input <?php echo SA11Y_TARGET_FIELD ?> id="sa11y_network_target" name="sa11y_network_target" value="<?php echo esc_attr($option); ?>" aria-describedby="target_desc" />
  <div id="target_desc">
    <p>
      <?php echo wp_kses(SA11Y_DESC["CHECK_ROOT"], SA11Y_ALLOWED_HTML); ?>
    </p>
    <p class="network-admin-note">
      <?php echo wp_kses(SA11Y_DESC["CHECK_ROOT_NETWORK"], SA11Y_ALLOWED_HTML); ?>
    </p>
  </div>
<?php
}

// Option: Panel Position
function sa11y_network_panel_position_callback()
{
  $settings = get_site_option('sa11y_network_panel_position');
?>
  <fieldset>
    <legend>
      <p>
        <?php echo wp_kses(SA11Y_DESC["PANEL_POSITION"]["DESCRIPTION"], SA11Y_ALLOWED_HTML); ?>
      </p>
    </legend>
    <label for="sa11y-left">
      <input id="sa11y-left" type="radio" name="sa11y_network_panel_position" value="left" <?php checked('left', $settings); ?> />
      <?php echo esc_html_e(SA11Y_DESC["PANEL_POSITION"]["LEFT"]); ?>
    </label>
    <label for="sa11y-right">
      <input id="sa11y-right" type="radio" name="sa11y_network_panel_position" value="right" <?php checked('right', $settings); ?> />
      <?php echo esc_html_e(SA11Y_DESC["PANEL_POSITION"]["RIGHT"]); ?>
    </label>
  </fieldset>
<?php
}

// Validate Panel Position
function sa11y_network_validate_panel_position($value)
{
  $value = sanitize_key($value);
  $valid_position = ['left', 'right'];
  if (!in_array($value, $valid_position, true)) {
    $value = 'right';
  }
  return $value;
}

/* Network Additional Checks Description */
function network_additional_checks_callback()
{
?>
  <p class="network-admin-note">
    <?php echo wp_kses(SA11Y_DESC["NETWORK_OVERRIDE_SECTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Contrast toggle
function sa11y_network_contrast_callback()
{
  $option = absint(get_site_option('sa11y_network_contrast'));
?>
  <input type="checkbox" id="sa11y_network_contrast" name="sa11y_network_contrast" value="1" <?php checked($option, 1); ?> aria-describedby="contrast_desc" />
  <p id="contrast_desc">
    <?php echo wp_kses(SA11Y_DESC["CONTRAST"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Form Labels
function sa11y_network_form_labels_callback()
{
  $option = absint(get_site_option('sa11y_network_form_labels'));
?>
  <input type="checkbox" id="sa11y_network_form_labels" name="sa11y_network_form_labels" value="1" <?php checked($option, 1); ?> aria-describedby="form_desc" />
  <p id="form_desc">
    <?php echo wp_kses(SA11Y_DESC["FORM_LABELS"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Links Advanced
function sa11y_network_links_advanced_callback()
{
  $option = absint(get_site_option('sa11y_network_links_advanced'));
?>
  <input type="checkbox" id="sa11y_network_links_advanced" name="sa11y_network_links_advanced" value="1" <?php checked($option, 1); ?> aria-describedby="links_adv_desc" />
  <p id="links_adv_desc">
    <?php echo wp_kses(SA11Y_DESC["LINKS_ADVANCED"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Colour Filter
function sa11y_network_colour_filter_callback()
{
  $option = absint(get_site_option('sa11y_network_colour_filter'));
?>
  <input type="checkbox" id="sa11y_network_colour_filter" name="sa11y_network_colour_filter" value="1" <?php checked($option, 1); ?> aria-describedby="colour_filter_desc" />
  <p id="colour_filter_desc">
    <?php echo wp_kses(SA11Y_DESC["COLOUR_FILTER"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Make all additional checks required by default (Additional Checks)
function sa11y_network_all_checks_callback()
{
  $option = absint(get_site_option('sa11y_network_all_checks'));
?>
  <input type="checkbox" id="sa11y_network_all_checks" name="sa11y_network_all_checks" value="1" <?php checked($option, 1); ?> aria-describedby="all_checks_desc" />
  <p id="all_checks_desc">
    <?php echo wp_kses(SA11Y_DESC["ALL_CHECKS"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Readability
function sa11y_network_readability_callback()
{
  $option = get_site_option('sa11y_network_readability');
?>
  <input type="checkbox" id="sa11y_network_readability" name="sa11y_network_readability" value="1" <?php checked($option, 1); ?> aria-describedby="read_desc" />
  <div id="read_desc">
    <p>
      <?php echo wp_kses(SA11Y_DESC["READABILITY"], SA11Y_ALLOWED_HTML); ?>
    </p>
    <p class="network-admin-note">
      <?php echo wp_kses(SA11Y_DESC["NETWORK_CAN_OVERRIDE"], SA11Y_ALLOWED_HTML); ?>
    </p>
  </div>
<?php
}

// Option: Readability target area to check
function sa11y_network_readability_target_callback()
{
  $option = get_site_option('sa11y_network_readability_target');
?>
  <input <?php echo SA11Y_TARGET_FIELD ?> id="sa11y_network_readability_target" name="sa11y_network_readability_target" value="<?php echo esc_attr($option); ?>" aria-describedby="read_target_desc" />
  <div id="read_target_desc">
    <p>
      <?php echo wp_kses(SA11Y_DESC["READABILITY_TARGET"], SA11Y_ALLOWED_HTML); ?>
    </p>
    <p class="network-admin-note">
      <?php echo wp_kses(SA11Y_DESC["NETWORK_CAN_OVERRIDE"], SA11Y_ALLOWED_HTML); ?>
    </p>
  </div>
<?php
}

// Option: Readability ignore
function sa11y_network_readability_ignore_callback()
{
  $option = get_site_option('sa11y_network_readability_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_readability_ignore" name="sa11y_network_readability_ignore" value="<?php echo esc_attr($option); ?>" aria-describedby="read_ignore_desc" />
  <div id="read_ignore_desc">
    <p>
      <?php echo wp_kses(SA11Y_DESC["READABILITY_IGNORE"], SA11Y_ALLOWED_HTML); ?>
    </p>
    <p class="network-admin-note">
      <?php echo wp_kses(SA11Y_DESC["NETWORK_CANNOT_OVERRIDE"], SA11Y_ALLOWED_HTML); ?>
    </p>
  </div>
<?php
}

/* Network Exclusions Description */
function network_exclusions_callback()
{
  $link = 'https://www.w3schools.com/cssref/css_selectors.asp';
?>
  <p>
    <?php echo wp_kses(sprintf(SA11Y_DESC["NETWORK_EXCLUSIONS"], esc_url($link)), ['a' => ['href' => []]]);  ?>
  </p>
  <p class="network-admin-note">
    <?php echo wp_kses(SA11Y_DESC["NETWORK_CANNOT_OVERRIDE_SECTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Container exclusions
function sa11y_network_region_ignore_callback()
{
  $option = get_site_option('sa11y_network_region_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_region_ignore" name="sa11y_network_region_ignore" value="<?php echo esc_attr($option); ?>" aria-describedby="region_ignore_desc" />
  <p id="region_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["NETWORK_CONTAINER_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Contrast ignore
function sa11y_network_contrast_ignore_callback()
{
  $option = get_site_option('sa11y_network_contrast_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_contrast_ignore" name="sa11y_network_contrast_ignore" value="<?php echo esc_attr($option); ?>" aria-describedby="contrast_ignore_desc" />
  <p id="contrast_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["CONTRAST_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Exclude headings from outline
function sa11y_network_outline_ignore_callback()
{
  $option = get_site_option('sa11y_network_outline_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_outline_ignore" name="sa11y_network_outline_ignore" value="<?php echo esc_attr($option); ?>" aria-describedby="outline_ignore_desc" />
  <p id="outline_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["OUTLINE_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Exclude headings
function sa11y_network_header_ignore_callback()
{
  $option = get_site_option('sa11y_network_header_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_header_ignore" name="sa11y_network_header_ignore" value="<?php echo esc_attr($option); ?>" aria-describedby="header_ignore_desc" />
  <p id="header_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["HEADER_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Exclude images
function sa11y_network_image_ignore_callback()
{
  $option = get_site_option('sa11y_network_image_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_image_ignore" name="sa11y_network_image_ignore" value="<?php echo esc_attr($option); ?>" aria-describedby="image_ignore_desc" />
  <p id="image_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["IMAGE_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Exclude links
function sa11y_network_link_ignore_callback()
{
  $option = get_site_option('sa11y_network_link_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_link_ignore" name="sa11y_network_link_ignore" value="<?php echo esc_attr($option); ?>" aria-describedby="link_ignore_desc" />
  <p id="link_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["LINK_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Exclude spans within links
function sa11y_network_link_ignore_span_callback()
{
  $option = get_site_option('sa11y_network_link_ignore_span');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_link_ignore_span" name="sa11y_network_link_ignore_span" value="<?php echo esc_attr($option); ?>" aria-describedby="link_ignore_span_desc" />
  <p id="link_ignore_span_desc">
    <?php echo wp_kses(SA11Y_DESC["LINK_IGNORE_SPAN"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Links to flag
function sa11y_network_link_flag_callback()
{
  $option = get_site_option('sa11y_network_link_flag');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_link_flag" name="sa11y_network_link_flag" value="<?php echo esc_attr($option); ?>" aria-describedby="link_flag_desc" />
  <p id="link_flag_desc">
    <?php echo wp_kses(SA11Y_DESC["LINK_TO_FLAG"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Embedded content callback
function network_embedded_callback()
{
?>
  <p>
    <?php echo wp_kses(SA11Y_DESC["EMBEDDED_CONTENT_DESCRIPTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
  <p class="network-admin-note">
    <?php echo wp_kses(SA11Y_DESC["NETWORK_CANNOT_OVERRIDE_SECTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Video sources
function sa11y_network_video_callback()
{
  $option = get_site_option('sa11y_network_video');
?>
  <input <?php echo SA11Y_TEXT_FIELD_EXTRA ?> id="sa11y_network_video" name="sa11y_network_video" value="<?php echo esc_attr($option); ?>" aria-describedby="video_desc" />
  <p id="video_desc">
    <?php echo wp_kses(SA11Y_DESC["VIDEO"]["DESCRIPTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
  <details>
    <summary>
      <?php echo esc_html_e(SA11Y_DESC["VIDEO"]["SHOW_SOURCES"]); ?>
    </summary>
    <p><?php echo esc_html_e(SA11Y_DESC["VIDEO"]["SOURCES"]); ?></p>
  </details>
<?php
}

// Option: Audio sources
function sa11y_network_audio_callback()
{
  $option = get_site_option('sa11y_network_audio');
?>
  <input <?php echo SA11Y_TEXT_FIELD_EXTRA ?> id="sa11y_network_audio" name="sa11y_network_audio" value="<?php echo esc_attr($option); ?>" aria-describedby="audio_desc" />
  <p id="audio_desc">
    <?php echo wp_kses(SA11Y_DESC["AUDIO"]["DESCRIPTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
  <details>
    <summary>
      <?php echo esc_html_e(SA11Y_DESC["AUDIO"]["SHOW_SOURCES"]); ?>
    </summary>
    <p><?php echo esc_html_e(SA11Y_DESC["AUDIO"]["SOURCES"]); ?></p>
  </details>
<?php
}

// Option: Data visualization sources
function sa11y_network_dataViz_callback()
{
  $option = get_site_option('sa11y_network_dataViz');
?>
  <input <?php echo SA11Y_TEXT_FIELD_EXTRA ?> id="sa11y_network_dataViz" name="sa11y_network_dataViz" value="<?php echo esc_attr($option); ?>" aria-describedby="dataviz_desc" />
  <p id="dataviz_desc">
    <?php echo wp_kses(SA11Y_DESC["DATA_VIZ"]["DESCRIPTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
  <details>
    <summary>
      <?php echo esc_html_e(SA11Y_DESC["DATA_VIZ"]["SHOW_SOURCES"]); ?>
    </summary>
    <p><?php echo esc_html_e(SA11Y_DESC["DATA_VIZ"]["SOURCES"]); ?></p>
  </details>
<?php
}

// Section: Advanced settings callback
function network_advanced_settings_callback()
{
?>
  <p class="network-admin-note">
    <?php echo wp_kses(SA11Y_DESC["NETWORK_CANNOT_OVERRIDE_SECTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: No run
function sa11y_network_noRun_callback()
{
  $option = get_site_option('sa11y_network_noRun');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_network_noRun" name="sa11y_network_noRun" value="<?php echo esc_attr($option); ?>" aria-describedby="no_run_desc" />
  <p id="no_run_desc">
    <?php echo wp_kses(SA11Y_DESC["NO_RUN"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Shadow components to check
function sa11y_network_shadow_components_callback()
{

  $option = get_site_option('sa11y_network_shadow_components');
?>
  <input <?php echo SA11Y_TEXT_FIELD_EXTRA ?> id="sa11y_network_shadow_components" name="sa11y_network_shadow_components" value="<?php echo esc_attr($option); ?>" aria-describedby="shadow_desc" />
  <p id="shadow_desc">
    <?php echo wp_kses(SA11Y_DESC["SHADOW"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Extra props
function sa11y_network_extra_props_callback()
{
  $option = get_site_option('sa11y_network_extra_props');
  $link = 'https://sa11y.netlify.app/developers/props/';
  $string = sprintf(SA11Y_DESC["PROPS"], $link);
?>
  <textarea <?php echo SA11Y_TEXTAREA ?> id="sa11y_network_extra_props" name="sa11y_network_extra_props" aria-describedby="extra_props_desc"><?php echo esc_textarea($option); ?></textarea>
  <p id="extra_props_desc">
    <?php echo wp_kses($string, ['a' => ['href' => []]]); ?>
  </p>
  <?php
}

/* ************************************************************ */
/* Display Network Admin settings page.                         */
/* ************************************************************ */
function sa11y_network_options_page_callback()
{
  if (isset($_GET['updated'])) : ?>
    <div id="message" class="updated notice is-dismissible">
      <p><?php esc_html_e('Options saved for entire network.', 'sa11y-i18n') ?></p>
    </div>
  <?php endif; ?>

  <div class="wrap">
    <h1><?php esc_html_e('Sa11y - Network Settings', 'sa11y-i18n'); ?></h1>
    <div id="poststuff">
      <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
          <?php include SA11Y_PARTIALS . 'intro.php'; ?>
          <form method="POST" action="edit.php?action=sa11y_update_network_options">
            <?php settings_fields('sa11y_network_options_page'); ?>
            <?php do_settings_sections('sa11y_network_options_page'); ?>
            <?php submit_button(esc_html__(SA11Y_DESC["SAVE"])); ?>
          </form>
        </div><!-- #post-body-content -->
        <?php include SA11Y_PARTIALS . 'sidebar.php'; ?>
      </div><!-- #post-body -->
      <br class="clear">
    </div><!-- #poststuff -->
  </div><!-- .wrap -->
<?php
}

/**
 * This function here is hooked up to a special action and necessary to process
 * the saving of the options. This is the big difference with a normal options
 * page.
 */
function sa11y_update_network_options()
{
  check_admin_referer('sa11y_network_options_page-options');

  // List of registered options.
  global $new_allowed_options;
  $options = $new_allowed_options['sa11y_network_options_page'];

  foreach ($options as $option) {
    if (isset($_POST[$option])) {
      update_site_option($option, $_POST[$option]);
    } else {
      // Instead of deleting checkbox options, change to 0.
      $network_checkboxes = [
        'sa11y_network_readability',
        'sa11y_network_contrast',
        'sa11y_network_form_labels',
        'sa11y_network_links_advanced',
        'sa11y_network_colour_filter',
        'sa11y_network_all_checks',
      ];
      if (in_array($option, $network_checkboxes)) {
        update_site_option($option, 0);
      } else {
        // Delete any other keys that are not set.
        delete_site_option($option);
      }
    }
  }

  // At last we redirect back to our options page.
  wp_redirect(add_query_arg([
    'page' => 'sa11y_network_options_page',
    'updated' => 'true'
  ], network_admin_url('settings.php')));
  exit;
}
add_action('network_admin_edit_sa11y_update_network_options',  'sa11y_update_network_options');
