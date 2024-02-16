<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

/* ************************************************************** */
/*  Sets up the settings page and registers the plugin settings.  */
/* ************************************************************** */
function sa11y_admin_menu()
{
  $settings = add_options_page(
    esc_html__(SA11Y_LABEL["SA11Y_ADVANCED"]),
    esc_html__('Sa11y', 'sa11y-i18n'),
    'manage_options',
    'sa11y',
    'sa11y_settings_render_page'
  );

  if (!$settings) {
    return;
  }
  // Provided hook_suffix that's returned to add scripts only on settings page.
  add_action('load-' . $settings, 'sa11y_styles_scripts');
}
add_action('admin_menu', 'sa11y_admin_menu');

/* ************************************************************ */
/*  Enqueue custom styles & scripts for plugin usage.           */
/* ************************************************************ */
function sa11y_styles_scripts()
{
  wp_enqueue_style('sa11y-wp', trailingslashit(SA11Y_ASSETS) . 'css/sa11y-wp-admin.css', null);
}

/* ************************************************************ */
/*  Register all settings.                                      */
/* ************************************************************ */
function sa11y_register_settings()
{
  register_setting(
    'sa11y_all_settings',
    'sa11y_settings',
    'sa11y_settings_validate'
  );
}
add_action('admin_init', 'sa11y_register_settings');

/* ************************************************************ */
/*  Add sections and fields.                                    */
/* ************************************************************ */
function sa11y_setting_sections_fields()
{
  /* ********** */
  /*  Sections  */
  /* ********** */

  // Section: General.
  add_settings_section(
    'sa11y_general_settings',
    esc_html__(SA11Y_SECTION["GENERAL"]),
    '__return_false',
    'sa11y'
  );

  // Section: Additional checks.
  add_settings_section(
    'sa11y_additional_settings',
    esc_html__(SA11Y_SECTION["ADDITIONAL"]),
    '__return_false',
    'sa11y'
  );

  // Section: Readability.
  add_settings_section(
    'sa11y_readability_settings',
    esc_html__(SA11Y_SECTION["READABILITY"]),
    '__return_false',
    'sa11y'
  );

  // Section: Exclusions.
  add_settings_section(
    'sa11y_exclusions_settings',
    esc_html__(SA11Y_SECTION["EXCLUSIONS"]),
    'exclusions_callback',
    'sa11y'
  );

  // Section: Embedded content.
  add_settings_section(
    'sa11y_embedded_content_settings',
    esc_html__(SA11Y_SECTION["EMBEDDED"]),
    'embedded_content_callback',
    'sa11y'
  );

  // Section: Advanced section.
  add_settings_section(
    'sa11y_advanced_settings',
    esc_html__(SA11Y_SECTION["ADVANCED"]),
    '__return_false',
    'sa11y'
  );

  /* ****** */
  /* Fields */
  /* ****** */

  // Field: Add enable/disable checkbox setting field.
  add_settings_field(
    'sa11y_enable',
    esc_html__(SA11Y_LABEL["ENABLE"]),
    'sa11y_enable_field',
    'sa11y',
    'sa11y_general_settings',
    ['label_for' => 'sa11y_enable']
  );

  // Field: Add 'Target' input setting field.
  add_settings_field(
    'sa11y_target',
    esc_html__(SA11Y_LABEL["TARGET"]),
    'sa11y_target_field',
    'sa11y',
    'sa11y_general_settings',
    ['label_for' => 'sa11y_target']
  );

  // Field: Add panel position.
  add_settings_field(
    'sa11y_panel_position',
    esc_html__(SA11Y_LABEL["POSITION"]),
    'sa11y_panel_position_field',
    'sa11y',
    'sa11y_general_settings',
  );

  // Field: Contrast module.
  add_settings_field(
    'sa11y_contrast',
    esc_html__(SA11Y_LABEL["CONTRAST"]),
    'sa11y_contrast_field',
    'sa11y',
    'sa11y_additional_settings',
    ['label_for' => 'sa11y_contrast']
  );

  // Field: Forms module.
  add_settings_field(
    'sa11y_forms',
    esc_html__(SA11Y_LABEL["FORM_LABELS"]),
    'sa11y_forms_field',
    'sa11y',
    'sa11y_additional_settings',
    ['label_for' => 'sa11y_forms']
  );

  // Field: Links advanced module.
  add_settings_field(
    'sa11y_links_advanced',
    esc_html__(SA11Y_LABEL["LINKS_ADVANCED"]),
    'sa11y_links_advanced_field',
    'sa11y',
    'sa11y_additional_settings',
    ['label_for' => 'sa11y_links_advanced']
  );

  // Field: Colour filter.
  add_settings_field(
    'sa11y_colour_filter',
    esc_html__(SA11Y_LABEL["COLOUR_FILTER"]),
    'sa11y_colour_filter_field',
    'sa11y',
    'sa11y_additional_settings',
    ['label_for' => 'sa11y_colour_filter']
  );

  // Field: Enable all option checks by default.
  add_settings_field(
    'sa11y_all_checks',
    esc_html__(SA11Y_LABEL["ALL_CHECKS"]),
    'sa11y_all_checks_field',
    'sa11y',
    'sa11y_additional_settings',
    ['label_for' => 'sa11y_all_checks']
  );

  // Field: Add Readability checkbox setting.
  add_settings_field(
    'sa11y_readability',
    esc_html__(SA11Y_LABEL["READABILITY"]),
    'sa11y_readability_field',
    'sa11y',
    'sa11y_readability_settings',
    ['label_for' => 'sa11y_readability']
  );

  // Field: Add readability target input setting field.
  add_settings_field(
    'sa11y_readability_target',
    esc_html__(SA11Y_LABEL["READABILITY_TARGET"]),
    'sa11y_readability_target_field',
    'sa11y',
    'sa11y_readability_settings',
    ['label_for' => 'sa11y_readability_target']
  );

  // Field: Add readability ignore field.
  add_settings_field(
    'sa11y_readability_ignore',
    esc_html__(SA11Y_LABEL["READABILITY_EXCLUSIONS"]),
    'sa11y_readability_ignore_field',
    'sa11y',
    'sa11y_readability_settings',
    ['label_for' => 'sa11y_readability_ignore']
  );

  // Field: Add container ignore field.
  add_settings_field(
    'sa11y_container_ignore',
    esc_html__(SA11Y_LABEL["REGION_IGNORE"]),
    'sa11y_container_ignore_field',
    'sa11y',
    'sa11y_exclusions_settings',
    ['label_for' => 'sa11y_container_ignore']
  );

  // Field: Add contrast ignore field.
  add_settings_field(
    'sa11y_contrast_ignore',
    esc_html__(SA11Y_LABEL["CONTRAST_IGNORE"]),
    'sa11y_contrast_ignore_field',
    'sa11y',
    'sa11y_exclusions_settings',
    ['label_for' => 'sa11y_contrast_ignore']
  );

  // Field: Add outline ignore field.
  add_settings_field(
    'sa11y_outline_ignore',
    esc_html__(SA11Y_LABEL["OUTLINE_IGNORE"]),
    'sa11y_outline_ignore_field',
    'sa11y',
    'sa11y_exclusions_settings',
    ['label_for' => 'sa11y_outline_ignore']
  );

  // Field: Add heading ignore field.
  add_settings_field(
    'sa11y_header_ignore',
    esc_html__(SA11Y_LABEL["HEADING_IGNORE"]),
    'sa11y_header_ignore_field',
    'sa11y',
    'sa11y_exclusions_settings',
    ['label_for' => 'sa11y_header_ignore']
  );

  // Field: Add image ignore field.
  add_settings_field(
    'sa11y_image_ignore',
    esc_html__(SA11Y_LABEL["IMAGE_IGNORE"]),
    'sa11y_image_ignore_field',
    'sa11y',
    'sa11y_exclusions_settings',
    ['label_for' => 'sa11y_image_ignore']
  );

  // Field: Add link ignore field.
  add_settings_field(
    'sa11y_link_ignore',
    esc_html__(SA11Y_LABEL["LINK_IGNORE"]),
    'sa11y_link_ignore_field',
    'sa11y',
    'sa11y_exclusions_settings',
    ['label_for' => 'sa11y_link_ignore']
  );

  // Field: Add link span ignore field.
  add_settings_field(
    'sa11y_link_ignore_span',
    esc_html__(SA11Y_LABEL["LINK_IGNORE_SPAN"]),
    'sa11y_link_ignore_span_field',
    'sa11y',
    'sa11y_exclusions_settings',
    ['label_for' => 'sa11y_link_ignore_span']
  );

  // Field: Add link span ignore field.
  add_settings_field(
    'sa11y_links_to_flag',
    esc_html__(SA11Y_LABEL["FLAG_LINKS"]),
    'sa11y_links_to_flag_field',
    'sa11y',
    'sa11y_exclusions_settings',
    ['label_for' => 'sa11y_links_to_flag']
  );

  // Field: Video content.
  add_settings_field(
    'sa11y_video_sources',
    esc_html__(SA11Y_LABEL["VIDEO"]),
    'sa11y_video_sources_field',
    'sa11y',
    'sa11y_embedded_content_settings',
    ['label_for' => 'sa11y_video_sources']
  );

  // Field: Audio content.
  add_settings_field(
    'sa11y_audio_sources',
    esc_html__(SA11Y_LABEL["AUDIO"]),
    'sa11y_audio_sources_field',
    'sa11y',
    'sa11y_embedded_content_settings',
    ['label_for' => 'sa11y_audio_sources']
  );

  // Field: dataviz_sources content.
  add_settings_field(
    'sa11y_dataviz_sources',
    esc_html__(SA11Y_LABEL["DATAVIZ"]),
    'sa11y_dataviz_sources_field',
    'sa11y',
    'sa11y_embedded_content_settings',
    ['label_for' => 'sa11y_dataviz_sources']
  );

  // Field: Add exports feature.
  add_settings_field(
    'sa11y_export_results',
    esc_html__(SA11Y_LABEL["EXPORT_RESULTS"]),
    'sa11y_export_results_field',
    'sa11y',
    'sa11y_advanced_settings',
    ['label_for' => 'sa11y_export_results']
  );

  // Field: Don't run sa11y if these elements exist.
  add_settings_field(
    'sa11y_no_run',
    esc_html__(SA11Y_LABEL["TURN_OFF"]),
    'sa11y_no_run_field',
    'sa11y',
    'sa11y_advanced_settings',
    ['label_for' => 'sa11y_no_run']
  );

  // Field: Shadow components.
  add_settings_field(
    'sa11y_shadow_components',
    esc_html__(SA11Y_LABEL["SHADOW"]),
    'sa11y_shadow_components_field',
    'sa11y',
    'sa11y_advanced_settings',
    ['label_for' => 'sa11y_shadow_components']
  );

  // Field: Add 'Extra Props' textarea setting field.
  add_settings_field(
    'sa11y_extra_props',
    esc_html__(SA11Y_LABEL["PROPS"]),
    'sa11y_extra_props_field',
    'sa11y',
    'sa11y_advanced_settings',
    ['label_for' => 'sa11y_extra_props']
  );
}
add_action('admin_init', 'sa11y_setting_sections_fields');

/* ************************************************************ */
/*  Options                                                     */
/* ************************************************************ */

// Option: Enable/disable field.
function sa11y_enable_field()
{
  $settings = sa11y_get_settings('sa11y_enable');
?>
  <input type="checkbox" id="sa11y_enable" name="sa11y_settings[sa11y_enable]" value="1" <?php checked(1, $settings); ?> aria-describedby="enable_desc" />
  <p id="enable_desc">
    <?php echo wp_kses(SA11Y_DESC["ENABLE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Target field
function sa11y_target_field()
{
  $settings = sa11y_get_settings('sa11y_target');
?>
  <input <?php echo SA11Y_TARGET_FIELD ?> name="sa11y_settings[sa11y_target]" id="sa11y_target" value="<?php echo esc_attr($settings); ?>" aria-describedby="target_desc" />
  <p id="target_desc">
    <?php echo wp_kses(SA11Y_DESC["CHECK_ROOT"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Panel Position
function sa11y_panel_position_field()
{
  $settings = sa11y_get_settings('sa11y_panel_position');
?>
  <fieldset>
    <legend>
      <?php echo wp_kses(SA11Y_DESC["PANEL_POSITION"]["DESCRIPTION"], SA11Y_ALLOWED_HTML); ?>
    </legend>
    <label for="sa11y-left">
      <input id="sa11y-left" type="radio" name="sa11y_settings[sa11y_panel_position]" value="left" <?php checked('left', $settings); ?> />
      <?php echo esc_html_e(SA11Y_DESC["PANEL_POSITION"]["LEFT"]); ?>
    </label>
    <label for="sa11y-right">
      <input id="sa11y-right" type="radio" name="sa11y_settings[sa11y_panel_position]" value="right" <?php checked('right', $settings); ?> />
      <?php echo esc_html_e(SA11Y_DESC["PANEL_POSITION"]["RIGHT"]); ?>
    </label>
  </fieldset>
<?php
}

// Option: Contrast
function sa11y_contrast_field()
{
  $settings = sa11y_get_settings('sa11y_contrast');
?>
  <input type="checkbox" id="sa11y_contrast" name="sa11y_settings[sa11y_contrast]" value="1" <?php checked(1, $settings); ?> aria-describedby="contrast_desc" />
  <p id="contrast_desc">
    <?php echo wp_kses(SA11Y_DESC["CONTRAST"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Form labels
function sa11y_forms_field()
{
  $settings = sa11y_get_settings('sa11y_forms');
?>
  <input type="checkbox" id="sa11y_forms" name="sa11y_settings[sa11y_forms]" value="1" <?php checked(1, $settings); ?> aria-describedby="fl_desc" />
  <p id="fl_desc">
    <?php echo wp_kses(SA11Y_DESC["FORM_LABELS"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Links Advanced
function sa11y_links_advanced_field()
{
  $settings = sa11y_get_settings('sa11y_links_advanced');
?>
  <input type="checkbox" id="sa11y_links_advanced" name="sa11y_settings[sa11y_links_advanced]" value="1" <?php checked(1, $settings); ?> aria-describedby="la_desc" />
  <p id="la_desc">
    <?php echo wp_kses(SA11Y_DESC["LINKS_ADVANCED"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Colour Filter
function sa11y_colour_filter_field()
{
  $settings = sa11y_get_settings('sa11y_colour_filter');
?>
  <input type="checkbox" id="sa11y_colour_filter" name="sa11y_settings[sa11y_colour_filter]" value="1" <?php checked(1, $settings); ?> aria-describedby="colour-filter_desc" />
  <p id="colour-filter_desc">
    <?php echo wp_kses(SA11Y_DESC["COLOUR_FILTER"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Make all optional checks required by default.
function sa11y_all_checks_field()
{
  $settings = sa11y_get_settings('sa11y_all_checks');
?>
  <input type="checkbox" id="sa11y_all_checks" name="sa11y_settings[sa11y_all_checks]" value="1" <?php checked(1, $settings); ?> aria-describedby="all_checks_desc" />
  <p id="all_checks_desc">
    <?php echo wp_kses(SA11Y_DESC["ALL_CHECKS"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Readability enable/disable field.
function sa11y_readability_field()
{
  $settings = sa11y_get_settings('sa11y_readability');
?>
  <input type="checkbox" id="sa11y_readability" name="sa11y_settings[sa11y_readability]" value="1" <?php checked(1, $settings); ?> aria-labelledby="read_desc" />
  <p id="read_desc">
    <?php echo wp_kses(SA11Y_DESC["READABILITY"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Readability target field.
function sa11y_readability_target_field()
{
  $settings = sa11y_get_settings('sa11y_readability_target');
?>
  <input <?php echo SA11Y_TARGET_FIELD ?> name="sa11y_settings[sa11y_readability_target]" id="sa11y_readability_target" value="<?php echo esc_attr($settings); ?>" aria-describedby="read_target_desc" />
  <p id="read_target_desc">
    <?php echo wp_kses(SA11Y_DESC["READABILITY_TARGET"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Readability ignore field.
function sa11y_readability_ignore_field()
{
  $settings = sa11y_get_settings('sa11y_readability_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_readability_ignore" name="sa11y_settings[sa11y_readability_ignore]" value="<?php echo esc_attr($settings); ?>" aria-describedby="read_ignore_desc" />
  <p id="read_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["READABILITY_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Section: Exclusions section description.
function exclusions_callback()
{
  $link = 'https://www.w3schools.com/cssref/css_selectors.asp';
?>
  <p>
    <?php echo wp_kses(sprintf(SA11Y_DESC["EXCLUSIONS"], esc_url($link)), ['a' => ['href' => []]]); ?>
  </p>
<?php
}

// Option: Container ignore field.
function sa11y_container_ignore_field()
{
  $settings = sa11y_get_settings('sa11y_container_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_container_ignore" name="sa11y_settings[sa11y_container_ignore]" value="<?php echo esc_attr($settings); ?>" aria-describedby="region_ignore_desc" />
  <p id="region_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["CONTAINER_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Contrast ignore field.
function sa11y_contrast_ignore_field()
{
  $settings = sa11y_get_settings('sa11y_contrast_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_contrast_ignore" name="sa11y_settings[sa11y_contrast_ignore]" value="<?php echo esc_attr($settings); ?>" aria-describedby="contrast_ignore_desc" />
  <p id="contrast_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["CONTRAST_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Outline ignore field.
function sa11y_outline_ignore_field()
{
  $settings = sa11y_get_settings('sa11y_outline_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_outline_ignore" name="sa11y_settings[sa11y_outline_ignore]" value="<?php echo esc_attr($settings); ?>" aria-describedby="outline_ignore_desc" />
  <p id="outline_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["OUTLINE_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Heading ignore field.
function sa11y_header_ignore_field()
{
  $settings = sa11y_get_settings('sa11y_header_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_header_ignore" name="sa11y_settings[sa11y_header_ignore]" value="<?php echo esc_attr($settings); ?>" aria-describedby="head_ignore_desc" />
  <p id="head_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["HEADER_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Image ignore field.
function sa11y_image_ignore_field()
{
  $settings = sa11y_get_settings('sa11y_image_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_image_ignore" name="sa11y_settings[sa11y_image_ignore]" value="<?php echo esc_attr($settings); ?>" aria-describedby="image_ignore_desc" />
  <p id="image_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["IMAGE_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Link ignore field.
function sa11y_link_ignore_field()
{
  $settings = sa11y_get_settings('sa11y_link_ignore');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_link_ignore" name="sa11y_settings[sa11y_link_ignore]" value="<?php echo esc_attr($settings); ?>" aria-describedby="link_ignore_desc" />
  <p id="link_ignore_desc">
    <?php echo wp_kses(SA11Y_DESC["LINK_IGNORE"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Link span ignore field.
function sa11y_link_ignore_span_field()
{
  $settings = sa11y_get_settings('sa11y_link_ignore_span');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_link_ignore_span" name="sa11y_settings[sa11y_link_ignore_span]" value="<?php echo esc_attr($settings); ?>" aria-describedby="link_span_desc" />
  <p id="link_span_desc">
    <?php echo wp_kses(SA11Y_DESC["LINK_IGNORE_SPAN"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Links to flag as error field.
function sa11y_links_to_flag_field()
{
  $settings = sa11y_get_settings('sa11y_links_to_flag');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_links_to_flag" name="sa11y_settings[sa11y_links_to_flag]" value="<?php echo esc_attr($settings); ?>" aria-describedby="link_flag_desc" />
  <p id="link_flag_desc">
    <?php echo wp_kses(SA11Y_DESC["LINK_TO_FLAG"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Section: Embedded Content section description.
function embedded_content_callback()
{
?>
  <p>
    <?php echo wp_kses(SA11Y_DESC["EMBEDDED_CONTENT_DESCRIPTION"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Video field.
function sa11y_video_sources_field()
{
  $settings = sa11y_get_settings('sa11y_video_sources');
?>
  <input <?php echo SA11Y_TEXT_FIELD_EXTRA ?> id="sa11y_video_sources" name="sa11y_settings[sa11y_video_sources]" value="<?php echo esc_attr($settings); ?>" aria-describedby="video_desc" />
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

// Option: Audio field.
function sa11y_audio_sources_field()
{
  $settings = sa11y_get_settings('sa11y_audio_sources');
?>
  <input <?php echo SA11Y_TEXT_FIELD_EXTRA ?> id="sa11y_audio_sources" name="sa11y_settings[sa11y_audio_sources]" value="<?php echo esc_attr($settings); ?>" aria-describedby="audio_desc" />
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

// Option: Data visualizations sources.
function sa11y_dataviz_sources_field()
{
  $settings = sa11y_get_settings('sa11y_dataviz_sources');
?>
  <input <?php echo SA11Y_TEXT_FIELD_EXTRA ?> id="sa11y_dataviz_sources" name="sa11y_settings[sa11y_dataviz_sources]" value="<?php echo esc_attr($settings); ?>" aria-describedby="dataviz_desc" />
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

// Option: Export results option.
function sa11y_export_results_field()
{
  $settings = sa11y_get_settings('sa11y_export_results');
?>
  <input type="checkbox" id="sa11y_export_results" name="sa11y_settings[sa11y_export_results]" value="1" <?php checked(1, $settings); ?> aria-describedby="export_desc" />
  <p id="export_desc">
    <?php echo wp_kses(SA11Y_DESC["EXPORT_RESULTS_DESC"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Turn off Sa11y if these elements are detected.
function sa11y_no_run_field()
{
  $settings = sa11y_get_settings('sa11y_no_run');
?>
  <input <?php echo SA11Y_TEXT_FIELD ?> id="sa11y_no_run" name="sa11y_settings[sa11y_no_run]" value="<?php echo esc_attr($settings); ?>" aria-describedby="norun_desc" />
  <p id="norun_desc">
    <?php echo wp_kses(SA11Y_DESC["NO_RUN"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Web components field.
function sa11y_shadow_components_field()
{
  $settings = sa11y_get_settings('sa11y_shadow_components');
?>
  <input <?php echo SA11Y_TEXT_FIELD_EXTRA ?> id="sa11y_shadow_components" name="sa11y_settings[sa11y_shadow_components]" value="<?php echo esc_attr($settings); ?>" aria-describedby="shadow_desc" />
  <p id="shadow_desc">
    <?php echo wp_kses(SA11Y_DESC["SHADOW"], SA11Y_ALLOWED_HTML); ?>
  </p>
<?php
}

// Option: Extra props.
function sa11y_extra_props_field()
{
  $settings = sa11y_get_settings('sa11y_extra_props');
?>
  <textarea <?php echo SA11Y_TEXTAREA ?> name="sa11y_settings[sa11y_extra_props]" id="sa11y_extra_props" aria-describedby="props_desc"><?php echo esc_textarea($settings); ?></textarea>
  <p id="props_desc">
    <?php
    $link = 'https://sa11y.netlify.app/developers/props/';
    $string = sprintf(SA11Y_DESC["PROPS"], $link);
    echo wp_kses($string, ['a' => ['href' => []]]);
    ?>
  </p>
<?php
}

/* ************************************************************ */
/*  Render the plugin settings page.                            */
/* ************************************************************ */
function sa11y_settings_render_page()
{
?>
  <div class=" wrap">
    <h1><?php esc_html_e(SA11Y_LABEL["SA11Y_ADVANCED"]); ?></h1>
    <div id="poststuff">
      <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
          <?php include SA11Y_PARTIALS . 'intro.php'; ?>
          <form method="post" action="options.php" autocomplete="off" class="sa11y-form-admin">
            <?php settings_fields('sa11y_all_settings'); ?>
            <?php do_settings_sections('sa11y'); ?>
            <?php submit_button(esc_html__(SA11Y_DESC["SAVE"])); ?>
          </form>
        </div><!-- .post-body-content -->
        <?php include SA11Y_PARTIALS . 'sidebar.php'; ?>
      </div><!-- #post-body -->
      <br class="clear">
    </div><!-- #poststuff -->
  </div><!-- .wrap -->
<?php
}

/* ************************************************************ */
/*  Sanitize and validate settings                              */
/* ************************************************************ */
function sa11y_settings_validate($settings)
{
  /* Validate: Checkboxes */
  $checkboxes = [
    'sa11y_enable',
    'sa11y_contrast',
    'sa11y_forms',
    'sa11y_links_advanced',
    'sa11y_colour_filter',
    'sa11y_all_checks',
    'sa11y_readability',
    'sa11y_export_results',
  ];
  foreach ($checkboxes as $key) {
    if (isset($settings[$key])) {
      $settings[$key] = sa11y_sanitize_checkboxes($settings[$key]);
    } else {
      $settings[$key] = 0;
    }
  }

  /* Validate: Panel position */
  if (isset($settings['sa11y_panel_position'])) {
    $sanitized = sanitize_key($settings['sa11y_panel_position']);
    $valid_position = ['left', 'right'];
    if (!in_array($sanitized, $valid_position, true)) {
      $settings['sa11y_panel_position'] = $settings['sa11y_panel_position'] ?? 'right';
    }
  }

  /* Sanitize: Target fields */
  $targetKeys = [
    'sa11y_target',
    'sa11y_readability_target',
    'sa11y_no_run',
  ];
  foreach ($targetKeys as $key) {
    $settings[$key] = sa11y_sanitize_target_fields($settings[$key]);
  }

  /* Sanitize: Text fields */
  $textfields = [
    'sa11y_readability_ignore',
    'sa11y_container_ignore',
    'sa11y_contrast_ignore',
    'sa11y_outline_ignore',
    'sa11y_header_ignore',
    'sa11y_image_ignore',
    'sa11y_link_ignore',
    'sa11y_link_ignore_span',
    'sa11y_links_to_flag',
    'sa11y_shadow_components',
  ];
  foreach ($textfields as $key) {
    $settings[$key] = sa11y_sanitize_text_fields($settings[$key]);
  }

  /* Sanitize: fields (extra) */
  $extraSanitizeKeys = [
    'sa11y_video_sources',
    'sa11y_audio_sources',
    'sa11y_dataviz_sources',
  ];
  foreach ($extraSanitizeKeys as $key) {
    $settings[$key] = sa11y_sanitize_extra_text_fields($settings[$key]);
  }

  /* Sanitize: textareas */
  $textareaKeys = [
    'sa11y_extra_props',
  ];
  foreach ($textareaKeys as $key) {
    $settings[$key] = sa11y_sanitize_textarea_fields($settings[$key]);
  }

  /* Return all settings. */
  return $settings;
}
