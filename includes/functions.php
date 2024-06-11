<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Sets up custom filters for the plugin's output.
 */
function add_action_links($links)
{
  $mylinks = ['<a href="' . admin_url('options-general.php?page=sa11y') . '">Advanced Settings</a>'];
  return array_merge($links, $mylinks);
}
add_filter('plugin_action_links_' . SA11Y_BASE, 'add_action_links');

/**
 * Return the default plugin settings.
 */
function sa11y_get_defaultOptions()
{

  /* Get Network defaults. */
  // Target
  $getNetworkTarget = get_site_option('sa11y_network_target');
  $defaultTarget = empty($getNetworkTarget) ? '' : $getNetworkTarget;

  // Readability target
  $getNetworkReadabilityTarget = get_site_option('sa11y_network_readability_target');
  $defaultReadabilityTarget = empty($getNetworkReadabilityTarget) ? '' : $getNetworkReadabilityTarget;

  // Checkboxes
  $defaultPanelPosition = is_multisite() ? get_site_option('sa11y_network_panel_position') : 'right';
  $defaultContrast = is_multisite() ? get_site_option('sa11y_network_contrast') : 1;
  $defaultFormLabels = is_multisite() ? get_site_option('sa11y_network_form_labels') : 1;
  $defaultLinksAdvanced = is_multisite() ? get_site_option('sa11y_network_links_advanced') : 1;
  $defaultColourFilter = is_multisite() ? get_site_option('sa11y_network_colour_filter') : 1;
  $defaultAllChecks = is_multisite() ? get_site_option('sa11y_network_all_checks') : 0;
  $defaultReadability = is_multisite() ? get_site_option('sa11y_network_readability') : 1;

  /* Default options */
  $defaultOptions = [
    // General
    'sa11y_enable' => absint(1),
    'sa11y_target' => esc_html($defaultTarget),
    'sa11y_panel_position' => esc_html($defaultPanelPosition),

    // Additional checks
    'sa11y_contrast' => absint($defaultContrast),
    'sa11y_forms' => absint($defaultFormLabels),
    'sa11y_links_advanced' => absint($defaultLinksAdvanced),
    'sa11y_colour_filter' => absint($defaultColourFilter),
    'sa11y_all_checks' => absint($defaultAllChecks),

    // Readability
    'sa11y_readability' => absint($defaultReadability),
    'sa11y_readability_target' => esc_html($defaultReadabilityTarget),
    'sa11y_readability_ignore' => esc_html(''),

    // Exclusions
    'sa11y_container_ignore' => esc_html('#comments'),
    'sa11y_contrast_ignore' => esc_html(''),
    'sa11y_outline_ignore' => esc_html(''),
    'sa11y_header_ignore' => esc_html(''),
    'sa11y_image_ignore' => esc_html(''),
    'sa11y_link_ignore' => esc_html('nav *, [role="navigation"] *'),
    'sa11y_link_ignore_span' => esc_html(''),
    'sa11y_links_to_flag' => esc_html(''),

    // Embedded content
    'sa11y_video_sources' => esc_html(''),
    'sa11y_audio_sources' => esc_html(''),
    'sa11y_dataviz_sources' => esc_html(''),

    // Advanced settings
    'sa11y_no_run' => esc_html(''),
    'sa11y_export_results' => absint(0),
    'sa11y_shadow_components' => esc_html(''),
    'sa11y_extra_props' => esc_html(''),
  ];

  // Allow dev to filter the default settings.
  return apply_filters('sa11y_defaultOptions', $defaultOptions);
}

/**
 * Admin options: Function for quickly grabbing settings for the plugin without having to call get_option()
 * every time we need a setting.
 */
function sa11y_get_settings($option = '')
{
  $settings = get_option('sa11y_settings', sa11y_get_defaultOptions());
  return $settings[$option] ?? null;
}

/**
 * Loads the scripts for the plugin.
 */
function sa11y_load_scripts()
{
  // Get the enable option.
  $enable = sa11y_get_settings('sa11y_enable');
  $user = wp_get_current_user();
  $allowed_roles = ['editor', 'administrator', 'author', 'contributor'];
  $allowed_user_roles = array_intersect($allowed_roles, $user->roles);

  // Check if enabled, user is logged in, and has ability to edit pages/posts.
  if (
    $enable === 1
    && is_user_logged_in()
    && ($allowed_user_roles || current_user_can('edit_posts') || current_user_can('edit_pages'))
  ) {
    global $sa11yLangPrefix;

    // Get page language.
    $splitLang = explode('_', get_locale());
    $lang      = $splitLang[0];
    $country   = $splitLang[1] ?? '';
    $languages = [
      'bg',
      'cs',
      'da',
      'de',
      'el',
      'en',
      'es',
      'et',
      'fi',
      'fr',
      'hu',
      'id',
      'it',
      'ja',
      'ko',
      'lt',
      'lv',
      'nb',
      'nl',
      'pl',
      'pt',
      'ro',
      'sl',
      'sk',
      'sv',
      'tr',
      'ua',
      'uk',
      'zh',
    ];

    // Check if Sa11y supports language.
    if (!in_array($lang, $languages)) {
      $lang = "en";
    } else if ($lang === "pt") {
      $lang = ($country === "BR") ? "ptBR" : "ptPT";
    } else if ($lang === "uk") {
      $lang = "ua";
    } else if ($lang === "en") {
      $lang = ($country === "US") ? "enUS" : "en";
    }

    // Enqueue language file, CSS, and main Javascript file.
    wp_enqueue_style(
      'sa11y',
      trailingslashit(SA11Y_ASSETS) . 'src/sa11y.min.css',
      null,
      Sa11y_WP::VERSION
    );

    wp_enqueue_script(
      'sa11y-lang',
      trailingslashit(SA11Y_ASSETS) . 'src/lang/' . $lang . '.umd.js',
      null,
      Sa11y_WP::VERSION,
      false,
    );

    wp_enqueue_script(
      'sa11y',
      trailingslashit(SA11Y_ASSETS) . 'src/sa11y.umd.min.js',
      null,
      Sa11y_WP::VERSION,
      false,
    );

    // Populate props within <script>
    $sa11yLangPrefix = ucfirst($lang);
  }
}
add_action('wp_enqueue_scripts', 'sa11y_load_scripts');

/**
 * Initialize: Get the plugin settings value
 */
function sa11y_init()
{
  /* ******************* */
  /*  Get local values   */
  /* ******************* */
  // General
  $enable = absint(sa11y_get_settings('sa11y_enable'));
  $checkRoot = esc_html__(sa11y_get_settings('sa11y_target'));
  $panelPosition = esc_html__(sa11y_get_settings('sa11y_panel_position'));

  // Additional checks
  $getContrast = absint(sa11y_get_settings('sa11y_contrast'));
  $getForms = absint(sa11y_get_settings('sa11y_forms'));
  $getLinksAdvanced = absint(sa11y_get_settings('sa11y_links_advanced'));
  $getColourFilter = absint(sa11y_get_settings('sa11y_colour_filter'));
  $getAllChecks = absint(sa11y_get_settings('sa11y_all_checks'));

  // Readability
  $getReadability = absint(sa11y_get_settings('sa11y_readability'));
  $getReadabilityTarget = esc_html(sa11y_get_settings('sa11y_readability_target'));
  $getReadabilityIgnore = esc_html(sa11y_get_settings('sa11y_readability_ignore'));

  // Exclusions
  $getContainerIgnore = esc_html(sa11y_get_settings('sa11y_container_ignore'));
  $getContrastIgnore = esc_html(sa11y_get_settings('sa11y_contrast_ignore'));
  $getOutlineIgnore = esc_html(sa11y_get_settings('sa11y_outline_ignore'));
  $getHeaderIgnore = esc_html(sa11y_get_settings('sa11y_header_ignore'));
  $getImageIgnore = esc_html(sa11y_get_settings('sa11y_image_ignore'));
  $getLinkIgnore = esc_html(sa11y_get_settings('sa11y_link_ignore'));
  $getLinkIgnoreSpan = esc_html(sa11y_get_settings('sa11y_link_ignore_span'));
  $getLinksToFlag = esc_html(sa11y_get_settings('sa11y_links_to_flag'));

  // Embedded content
  $getVideoContent = wp_filter_nohtml_kses(sa11y_get_settings('sa11y_video_sources'));
  $getAudioContent = wp_filter_nohtml_kses(sa11y_get_settings('sa11y_audio_sources'));
  $getDataVizContent = wp_filter_nohtml_kses(sa11y_get_settings('sa11y_dataviz_sources'));

  // Advanced settings
  $getExportResults = absint(sa11y_get_settings('sa11y_export_results'));
  $getNoRun = esc_html(sa11y_get_settings('sa11y_no_run'));
  $getShadowComponents = esc_html(sa11y_get_settings('sa11y_shadow_components'));
  $getExtraProps = wp_filter_nohtml_kses(sa11y_get_settings('sa11y_extra_props'));

  /* ******************** */
  /*  Get network values  */
  /* ******************** */
  $networkContainerIgnore = esc_html(get_site_option('sa11y_network_region_ignore'));
  $networkReadabilityIgnore = esc_html(get_site_option('sa11y_network_readability_ignore'));
  $networkContrastIgnore = esc_html(get_site_option('sa11y_network_contrast_ignore'));
  $networkOutlineIgnore = esc_html(get_site_option('sa11y_network_outline_ignore'));
  $networkHeaderIgnore = esc_html(get_site_option('sa11y_network_header_ignore'));
  $networkImageIgnore = esc_html(get_site_option('sa11y_network_image_ignore'));
  $networkLinkIgnore = esc_html(get_site_option('sa11y_network_link_ignore'));
  $networkLinkIgnoreSpan = esc_html(get_site_option('sa11y_network_link_ignore_span'));
  $networkLinkFlag = esc_html(get_site_option('sa11y_network_link_flag'));

  $networkVideo = wp_filter_nohtml_kses(get_site_option('sa11y_network_video'));
  $networkAudio = wp_filter_nohtml_kses(get_site_option('sa11y_network_audio'));
  $networkDataViz = wp_filter_nohtml_kses(get_site_option('sa11y_network_dataViz'));

  $networkNoRun = esc_html(get_site_option('sa11y_network_noRun'));
  $networkShadowComponents = esc_html(get_site_option('sa11y_network_shadow_components'));
  $networkExtraProps = wp_filter_nohtml_kses(get_site_option('sa11y_network_extra_props'));

  /* ********************************************** */
  /*  Combine defaults, local and network options.  */
  /* ********************************************** */

  // Global defaults
  $defaultIgnore = '#query-monitor-main, #wpadminbar';
  $videoDefault = 'youtube.com, vimeo.com, yuja.com, panopto.com';
  $audioDefault = 'soundcloud.com, simplecast.com, podbean.com, buzzsprout.com, blubrry.com, transistor.fm, fusebox.fm, libsyn.com';
  $dataVizDefault = 'datastudio.google.com, tableau';

  // Exclusions
  $containerIgnore = $networkContainerIgnore
    ? "{$defaultIgnore}, {$networkContainerIgnore}, {$getContainerIgnore}"
    : "{$defaultIgnore}, {$getContainerIgnore}";

  $readabilityIgnore = $networkReadabilityIgnore
    ? "{$networkReadabilityIgnore}, {$getReadabilityIgnore}"
    : $getReadabilityIgnore;

  $contrastIgnore = $networkContrastIgnore
    ? "{$networkContrastIgnore}, {$getContrastIgnore}"
    : $getContrastIgnore;

  $outlineIgnore = $networkOutlineIgnore
    ? "{$networkOutlineIgnore}, {$getOutlineIgnore}"
    : $getOutlineIgnore;

  $headerIgnore = $networkHeaderIgnore
    ? "{$networkHeaderIgnore}, {$getHeaderIgnore}"
    : $getHeaderIgnore;

  $imageIgnore = $networkImageIgnore
    ? "{$networkImageIgnore}, {$getImageIgnore}"
    : $getImageIgnore;

  $linkIgnore = $networkLinkIgnore
    ? "{$networkLinkIgnore}, {$getLinkIgnore}"
    : $getLinkIgnore;

  $linkIgnoreSpan = $networkLinkIgnoreSpan
    ? "{$networkLinkIgnoreSpan}, {$getLinkIgnoreSpan}"
    : $getLinkIgnoreSpan;

  $linksToFlag = $networkLinkFlag
    ? "{$networkLinkFlag}, {$getLinksToFlag}"
    : $getLinksToFlag;

  // Embedded content
  $videoContent = $networkVideo
    ? "{$videoDefault}, {$networkVideo}, {$getVideoContent}"
    : "{$videoDefault}, {$getVideoContent}";

  $audioContent = $networkAudio
    ? "{$audioDefault}, {$networkAudio}, {$getAudioContent}"
    : "{$audioDefault}, {$getAudioContent}";

  $dataVizContent = $networkDataViz
    ? "{$dataVizDefault}, {$networkDataViz}, {$getDataVizContent}"
    : "{$dataVizDefault}, {$getDataVizContent}";

  // Advanced
  $noRun = $networkNoRun
    ? "{$networkNoRun}, {$getNoRun}"
    : $getNoRun;

  $shadowComponents = $networkShadowComponents
    ? "{$networkShadowComponents}, {$getShadowComponents}"
    : $getShadowComponents;

  $extraProps = $networkExtraProps
    ? "{$networkExtraProps}, {$getExtraProps}"
    : $getExtraProps;

  /* ******************************** */
  /* Final prep before instantiation. */
  /* ******************************** */

  // Prepare object.
  $sa11yOptionsArray = [
    'checkRoot' => empty($checkRoot) ? 'body' : $checkRoot,
    'panelPosition' => $panelPosition,
    'contrastPlugin' => ($getContrast === 1) ? 1 : 0,
    'formLabelsPlugin' => ($getForms === 1) ? 1 : 0,
    'linksAdvancedPlugin' => ($getLinksAdvanced === 1) ? 1 : 0,
    'colourFilterPlugin' => ($getColourFilter === 1) ? 1 : 0,
    'checkAllHideToggles' => ($getAllChecks === 1) ? 1 : 0,
    'readabilityPlugin' => ($getReadability === 1) ? 1 : 0,
    'readabilityRoot' => empty($getReadabilityTarget) ? 'body' : $getReadabilityTarget,
    'readabilityIgnore' => $readabilityIgnore,
    'exportResultsPlugin' => ($getExportResults === 1) ? 1 : 0,
    'containerIgnore' => $containerIgnore,
    'contrastIgnore' => $contrastIgnore,
    'outlineIgnore' => $outlineIgnore,
    'headerIgnore' => $headerIgnore,
    'imageIgnore' => $imageIgnore,
    'linkIgnore' => $linkIgnore,
    'linkIgnoreSpan' => $linkIgnoreSpan,
    'linksToFlag' => $linksToFlag,
    'videoContent' => $videoContent,
    'audioContent' => $audioContent,
    'dataVizContent' => $dataVizContent,
    'doNotRun' => $noRun,
    'shadowComponents' => $shadowComponents,
  ];

  // Remove trailing commas and empty space at beginning or end of values.
  foreach ($sa11yOptionsArray as $key => $value) {
    $cleanValue = is_string($value) ? trim($value, ', ') : $value;
    unset($sa11yOptionsArray[$key]);
    $sa11yOptionsArray[$key] = $cleanValue;
  }

  // Convert extra props into array.
  $getExtraPropsOptions = !empty($extraProps) ? explode(', ', $extraProps) : [];
  $extraPropsArray = [];
  foreach ($getExtraPropsOptions as $pair) {
    if (!empty($pair)) {
      list($key, $value) = explode(':', $pair, 2);
      $value = trim($value);
      if (is_numeric($value)) {
        $value = (int)$value;
      } elseif ($value === 'true') {
        $value = 1;
      } elseif ($value === 'false') {
        $value = 0;
      }
      $extraPropsArray[$key] = $value;
    }
  }

  // Merge options and encode into JSON.
  $allSa11yOptions = array_merge($sa11yOptionsArray, $extraPropsArray);
  $allSa11yOptions = json_encode($allSa11yOptions);

  //Allowed characters.
  $replace = [
    '&gt;' => ">",
    '&quot;' => "'",
    '&#039;' => "'",
  ];
  $allSa11yOptions = str_replace(array_keys($replace), array_values($replace), $allSa11yOptions);

  /* ******************* */
  /* Allowed roles       */
  /* ******************* */
  $user = wp_get_current_user();
  $allowed_roles = ['editor', 'administrator', 'author', 'contributor'];
  $allowed_user_roles = array_intersect($allowed_roles, $user->roles);

  /* ******************* */
  /* Instantiate script  */
  /* ******************* */
  if (
    $enable === 1
    && is_user_logged_in()
    && ($allowed_user_roles || current_user_can('edit_posts') || current_user_can('edit_pages'))
  ) {
    global $sa11yLangPrefix;
    echo <<<EOT
      <script id="sa11y-wp-init">
        Sa11y.Lang.addI18n(Sa11yLang$sa11yLangPrefix.strings);
        /* Do not run when Elementors page builder is active. */
        if (!(window.frameElement && window.frameElement.id === "elementor-preview-iframe")) {
          const sa11y = new Sa11y.Sa11y({$allSa11yOptions});
        }
      </script>
    EOT;
  }
}
add_action('wp_footer', 'sa11y_init');
