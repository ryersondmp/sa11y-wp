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
  $defaultDeveloperChecks = is_multisite() ? get_site_option('sa11y_network_developer_checks') : 1;
  $defaultReadability = is_multisite() ? get_site_option('sa11y_network_readability') : 1;
  $defaultShowImageEditLink = get_site_option('sa11y_edit_image_link');

  /* Default options */
  $defaultOptions = [
    // General
    'sa11y_enable' => absint(1),
    'sa11y_target' => esc_html($defaultTarget),
    'sa11y_developer_checks' => absint($defaultDeveloperChecks),
    'sa11y_edit_image_link' => absint($defaultShowImageEditLink),

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
  $getDeveloperChecks = absint(sa11y_get_settings('sa11y_developer_checks'));
  $getImageEditLink = absint(sa11y_get_settings('sa11y_edit_image_link'));

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

  // Advanced settings
  $getExportResults = absint(sa11y_get_settings('sa11y_export_results'));
  $getNoRun = esc_html(sa11y_get_settings('sa11y_no_run'));
  $getShadowComponents = esc_html(sa11y_get_settings('sa11y_shadow_components'));
  $getExtraProps = wp_kses(sa11y_get_settings('sa11y_extra_props'), [
    'strong' => [
      'class' => true,
    ],
    'em' => true,
    'hr' => true,
    'a' => [
      'href' => true,
    ],
  ]);

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
  $networkNoRun = esc_html(get_site_option('sa11y_network_noRun'));
  $networkShadowComponents = esc_html(get_site_option('sa11y_network_shadow_components'));
  $networkExtraProps = wp_kses(
    get_site_option('sa11y_network_extra_props'),
    [
      'strong' => [
        'class' => true,
      ],
      'em' => true,
      'hr' => true,
      'a' => [
        'href' => true,
      ],
    ]
  );

  /* ********************************************** */
  /*  Combine defaults, local and network options.  */
  /* ********************************************** */

  // Global defaults
  $defaultIgnore = '#query-monitor-main, #wpadminbar';

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

  // Advanced
  $noRun = $networkNoRun
    ? "{$networkNoRun}, {$getNoRun}"
    : $getNoRun;

  $shadowComponents = $networkShadowComponents
    ? "{$networkShadowComponents}, {$getShadowComponents}"
    : $getShadowComponents;

  // Show "Edit" image link in Images panel.
  $getImageEditLink = $getImageEditLink === 1
    ? ['relativePathImageID' => 'wp-image-', 'editImageURLofCMS' => '/wp-admin/upload.php?item=']
    : [];

  /* ******************************** */
  /* Final prep before instantiation. */
  /* ******************************** */

  // Prepare object.
  $sa11yOptionsArray = array_merge([
    'checkRoot' => empty($checkRoot) ? 'body' : $checkRoot,
    'developerChecksOnByDefault' => ($getDeveloperChecks === 1) ? 1 : 0,
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
    'doNotRun' => $noRun,
    'shadowComponents' => $shadowComponents
  ], $getImageEditLink);

  // Remove trailing commas and empty space at beginning or end of values.
  foreach ($sa11yOptionsArray as $key => $value) {
    $cleanValue = is_string($value) ? trim($value, ', ') : $value;
    unset($sa11yOptionsArray[$key]);
    $sa11yOptionsArray[$key] = $cleanValue;
  }

  /* ******************************** */
  /*  Prepare extra props.            */
  /* ******************************** */

  // JSONify extra props.
  function parseExtraProps(string $input)
  {
    // Wrap in curly braces if not already.
    $input = trim($input);
    if ($input[0] !== '{') $input = "{ $input }";

    // Ensure keys are properly quoted.
    $input = preg_replace('/(\w+):/i', '"$1":', $input);

    // Remove unnecessary escape characters and fix trailing commas.
    $input = str_replace(['\"', ',}'], ['"', '}'], $input);
    $input = preg_replace('/,\s*([\]}])/', '$1', $input);

    // Decode as an associative array.
    $parsed = json_decode($input, true);

    // Return an empty array if parsing fails.
    if (json_last_error() !== JSON_ERROR_NONE) {
      $errorMessage = 'Sa11y: There was an error parsing the "Additional props" field. Please review and correct the syntax.';
      echo "<script>console.error(" . json_encode($errorMessage) . ");</script>";
      return [];
    }
    return $parsed ?: [];
  }

  // Process extra props for textareas.
  $localExtraProps = !empty($getExtraProps)
    ? parseExtraProps($getExtraProps)
    : [];
  $networkExtraProps = empty($localExtraProps) && !empty($networkExtraProps)
    ? parseExtraProps($networkExtraProps)
    : [];

  // Prioritize local extra props over network extra props.
  $allSa11yOptions = !empty($getExtraProps)
    ? array_merge($sa11yOptionsArray, $localExtraProps)
    : array_merge($sa11yOptionsArray, $networkExtraProps);

  // Encode final options as JSON.
  $allSa11yOptions = json_encode($allSa11yOptions, JSON_UNESCAPED_SLASHES);

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

/* ******************* */
/*  Code Mirror        */
/* ******************* */
add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts');
add_action('network_admin_enqueue_scripts', 'codemirror_enqueue_scripts');
function codemirror_enqueue_scripts()
{
  $settings = wp_enqueue_code_editor(['type' => 'application/json']);
  if ($settings) {
    wp_localize_script('wp-theme-plugin-editor', 'sa11y_extra_props_textarea', $settings);
    wp_enqueue_script('wp-theme-plugin-editor');
    wp_enqueue_style('wp-codemirror');
    add_action('admin_footer', 'my_init_codemirror');
  }
}

function my_init_codemirror()
{
?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const textarea = document.getElementById('sa11y_extra_props') || document.getElementById('sa11y_network_extra_props');
      if (textarea) {
        const customConfig = Object.assign({}, sa11y_extra_props_textarea, {
          codemirror: {
            mode: "javascript",
            lineNumbers: true,
            indentUnit: 2,
            tabSize: 2,
            theme: "default",
            autoCloseBrackets: true,
            matchBrackets: true,
            lineWrapping: true,
          },
        });
        wp.codeEditor.initialize(textarea, customConfig);
      }
    });
  </script>
<?php
}
