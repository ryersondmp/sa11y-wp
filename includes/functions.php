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
  return $settings[$option];
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
    global $sa11y_lang;

    // Get page language.
    $lang = explode('_', get_locale())[0];
    $country = explode('_', get_locale())[1];
    $languages = [
      'cs',
      'da',
      'de',
      'el',
      'en',
      'es',
      'et',
      'fi',
      'fr',
      'id',
      'it',
      'ja',
      'lt',
      'lv',
      'nb',
      'nl',
      'pl',
      'pt',
      'ro',
      'sl',
      'sv',
      'tr',
      'ua',
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
    wp_enqueue_style('sa11y-wp-css', trailingslashit(SA11Y_ASSETS) . 'src/sa11y.min.css', null);
    wp_enqueue_script('sa11y-wp-lang', trailingslashit(SA11Y_ASSETS) . 'src/lang/' . $lang . '.umd.js', null, true);
    wp_enqueue_script('sa11y-wp-js', trailingslashit(SA11Y_ASSETS) . 'src/sa11y.umd.min.js', null, true);

    // Populate props within <script>
    $sa11y_lang = 'Sa11y.Lang.addI18n(Sa11yLang' . ucfirst($lang) . '.strings);';
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

  /* ******************* */
  /* Prep before echoing */
  /* ******************* */

  //Allowed characters before echoing.
  $r = [
    '&gt;' => '>',
    '&quot;' => '"',
    '&#039;' => '"'
  ];

  // Check root
  $checkRoot = empty($checkRoot) ? 'body' : strtr($checkRoot, $r);

  // Global defaults
  $defaultIgnore = '#query-monitor-main, #wpadminbar';
  $videoDefault = 'youtube.com, vimeo.com, yuja.com, panopto.com';
  $audioDefault = 'soundcloud.com, simplecast.com, podbean.com, buzzsprout.com, blubrry.com, transistor.fm, fusebox.fm, libsyn.com';
  $dataVizDefault = 'datastudio.google.com, tableau';

  // Additional Checks
  $contrastOn = ($getContrast === 1) ? 'true' : 'false';
  $formsOn = ($getForms === 1) ? 'true' : 'false';
  $linksAdvancedOn = ($getLinksAdvanced === 1) ? 'true' : 'false';
  $colourFilterOn = ($getColourFilter === 1) ? 'true' : 'false';
  $allChecksOn = ($getAllChecks === 1) ? 'true' : 'false';
  $readabilityOn = ($getReadability === 1) ? 'true' : 'false';

  // Readability
  $readabilityTarget = empty($getReadabilityTarget) ? 'body' : strtr($getReadabilityTarget, $r);

  // Exclusions
  $containerIgnore = $networkContainerIgnore ? "{$defaultIgnore}, {$networkContainerIgnore}, {$getContainerIgnore}"
    : "{$defaultIgnore}, {$getContainerIgnore}";

  $readabilityIgnore = $networkReadabilityIgnore ? "{$networkReadabilityIgnore}, {$getReadabilityIgnore}"
    : $getReadabilityIgnore;

  $contrastIgnore = $networkContrastIgnore ? "{$networkContrastIgnore}, {$getContrastIgnore}"
    : $getContrastIgnore;

  $outlineIgnore = $networkOutlineIgnore ? "{$networkOutlineIgnore}, {$getOutlineIgnore}"
    : $getOutlineIgnore;

  $headerIgnore = $networkHeaderIgnore ? "{$networkHeaderIgnore}, {$getHeaderIgnore}"
    : $getHeaderIgnore;

  $imageIgnore = $networkImageIgnore ? "{$networkImageIgnore}, {$getImageIgnore}"
    : $getImageIgnore;

  $linkIgnore = $networkLinkIgnore ? "{$networkLinkIgnore}, {$getLinkIgnore}"
    : $getLinkIgnore;

  $linkIgnoreSpan = $networkLinkIgnoreSpan ? "{$networkLinkIgnoreSpan}, {$getLinkIgnoreSpan}"
    : $getLinkIgnoreSpan;

  $linksToFlag = $networkLinkFlag ? "{$networkLinkFlag}, {$getLinksToFlag}"
    : $getLinksToFlag;

  // Embedded content
  $videoContent = $networkVideo ? "{$videoDefault}, {$networkVideo}, {$getVideoContent}"
    : "{$videoDefault}, {$getVideoContent}";

  $audioContent = $networkAudio ? "{$audioDefault}, {$networkAudio}, {$getAudioContent}"
    : "{$audioDefault}, {$getAudioContent}";

  $dataVizContent = $networkDataViz ? "{$dataVizDefault}, {$networkDataViz}, {$getDataVizContent}"
    : "{$dataVizDefault}, {$getDataVizContent}";

  // Advanced
  $noRun = $networkNoRun ? "{$networkNoRun}, {$getNoRun}"
    : $getNoRun;

  $shadowComponents = $networkShadowComponents ? "{$networkShadowComponents}, {$getShadowComponents}"
    : $getShadowComponents;

  $extraProps = $networkExtraProps ? "{$networkExtraProps}, {$getExtraProps}"
    : $getExtraProps;

  /* ******************************** */
  /* Final prep before instantiation. */
  /* ******************************** */
  $readabilityIgnore = rtrim(strtr($readabilityIgnore, $r), ', ');
  $containerIgnore = rtrim(strtr($containerIgnore, $r), ', ');
  $contrastIgnore = rtrim(strtr($contrastIgnore, $r), ', ');
  $outlineIgnore = rtrim(strtr($outlineIgnore, $r), ', ');
  $headerIgnore = rtrim(strtr($headerIgnore, $r), ', ');
  $imageIgnore = rtrim(strtr($imageIgnore, $r), ', ');
  $linkIgnore = rtrim(strtr($linkIgnore, $r), ', ');
  $linkIgnoreSpan = rtrim(strtr($linkIgnoreSpan, $r), ', ');
  $linksToFlag = rtrim(strtr($linksToFlag, $r), ', ');
  $videoContent = rtrim(strtr($videoContent, $r), ', ');
  $audioContent = rtrim(strtr($audioContent, $r), ', ');
  $dataVizContent = rtrim(strtr($dataVizContent, $r), ', ');
  $noRun = rtrim(strtr($noRun, $r), ', ');
  $shadowComponents = rtrim(strtr($shadowComponents, $r), ', ');
  $extraProps = rtrim(strtr($extraProps, $r), ', ');

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
    global $sa11y_lang;

    $post_id = get_the_ID();
    $site_url = esc_url(get_site_url());

    echo <<<EOT
      <script id="sa11y-wp-init">
        // add event listener to send results to rest api endpoint
        document.addEventListener('sa11y-check-complete', function (e) {
          const results = e.detail.results;
          const post_id = e.detail.post_id;
          const data = {
            'results': results,
            'post_id': $post_id
          };
          fetch('$site_url/wp-json/sa11y/v1/results', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
          })
            .then(response => response.json())
            .then(data => {
              console.log('Success:', data);
            })
            .catch((error) => {
              console.error('Error:', error);
            });
        });

        $sa11y_lang
        /* Do not run when Elementors page builder is active. */
        if (!(window.frameElement && window.frameElement.id === "elementor-preview-iframe")) {
          const sa11y = new Sa11y.Sa11y({
            checkRoot: '$checkRoot',
            panelPosition: '$panelPosition',
            contrastPlugin: $contrastOn,
            formLabelsPlugin: $formsOn,
            linksAdvancedPlugin: $linksAdvancedOn,
            colourFilterPlugin: $colourFilterOn,
            checkAllHideToggles: $allChecksOn,
            readabilityPlugin: $readabilityOn,
            readabilityRoot: '$readabilityTarget',
            readabilityIgnore: '$readabilityIgnore',
            containerIgnore: '$containerIgnore',
            contrastIgnore: '$contrastIgnore',
            outlineIgnore: '$outlineIgnore',
            headerIgnore: '$headerIgnore',
            imageIgnore: '$imageIgnore',
            linkIgnore: '$linkIgnore',
            linkIgnoreSpan: '$linkIgnoreSpan',
            linksToFlag: '$linksToFlag',
            videoContent: '$videoContent',
            audioContent: '$audioContent',
            dataVizContent: '$dataVizContent',
            doNotRun: '$noRun',
            shadowComponents: '$shadowComponents',
            selectorPath: true,
            $extraProps
          });
        }
      </script>
    EOT;
  }
}
add_action('wp_footer', 'sa11y_init');

// REST API endpoint to store results
function store_results($request)
{
  $body = $request->get_body();
  $data = json_decode($body, true);
  $results = $data['results'];
  $post_id = $data['post_id'];

  // for each result, store in the database
  foreach ($results as $result) {
    $issue_type = $result['type'];
    $issue_details = $result['content'];
    $issue_selector = $result['cssPath'];
    store_issue($post_id, $issue_type, $issue_details, $issue_selector);
  }

  return $results;
}

// Register REST API endpoint for each site in the network.
function register_sa11y_results_endpoint()
{
  if (is_multisite()) {
    $blog_ids = get_sites(['fields' => 'ids']);
    foreach ($blog_ids as $blog_id) {
      switch_to_blog($blog_id);
      register_rest_route('sa11y/v1', '/results', [
        'methods' => 'POST',
        'callback' => 'store_results',
      ]);
      restore_current_blog();
    }
  } else {
    // Register REST API endpoint for single site.
    register_rest_route('sa11y/v1', '/results', [
      'methods' => 'POST',
      'callback' => 'store_results',
    ]);
  }
}
add_action('rest_api_init', 'register_sa11y_results_endpoint');
