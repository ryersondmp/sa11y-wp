<?php

/**
 * Sets up custom filters for the plugin's output.
 */

add_filter( 'plugin_action_links_' . SA11Y_BASE, 'add_action_links' );
function add_action_links ( $links ) {
    $mylinks = array(
        '<a href="' . admin_url( 'options-general.php?page=sa11y' ) . '">Advanced Settings</a>',
    );
    return array_merge( $links, $mylinks );
}

/**
 * Return the default plugin settings.
 */
function sa11y_get_defaultOptions() {

    $defaultOptions = array(
        'sa11y_enable' => absint(1),
        'sa11y_lang' => esc_html__('en'),
        'sa11y_target' => esc_html__(''),
        'sa11y_contrast' => absint(1),
        'sa11y_forms' => absint(1),
        'sa11y_links_advanced' => absint(1),

        'sa11y_readability' => absint(1),
        'sa11y_readability_target' => esc_html__(''),
        'sa11y_readability_ignore' => esc_html__(''),
        
        'sa11y_container_ignore' => esc_html__('#comments'),
        'sa11y_contrast_ignore' => esc_html__(''),
        'sa11y_outline_ignore' => esc_html__(''),
        'sa11y_header_ignore' => esc_html__(''),
        'sa11y_image_ignore' => esc_html__(''),
        'sa11y_link_ignore' => esc_html__('nav *, [role="navigation"] *'),
        'sa11y_link_span_ignore' => esc_html__(''),
        'sa11y_links_to_flag' => esc_html__(''),

        'sa11y_videoContent' => esc_html__('youtube.com, vimeo.com, yuja.com, panopto.com'),
        'sa11y_audioContent' => esc_html__('soundcloud.com, simplecast.com, podbean.com, buzzsprout.com, blubrry.com, transistor.fm, fusebox.fm, libsyn.com'),
        'sa11y_dataVizContent' => esc_html__('datastudio.google.com, tableau'),

        'sa11y_no_run' => esc_html__(''),
        'sa11y_extra_props' => esc_html__('')
    );

    // Allow dev to filter the default settings.
    return apply_filters('sa11y_defaultOptions', $defaultOptions);
}

/**
 * Function for quickly grabbing settings for the plugin without having to call get_option()
 * every time we need a setting.
 */
function sa11y_get_plugin_settings($option = '') {
    $settings = get_option('sa11y_plugin_settings', sa11y_get_defaultOptions());
    return $settings[$option];
}

/**
 * Loads the scripts for the plugin.
 */
function sa11y_load_scripts() {

    // Get the enable option.
    $enable = sa11y_get_plugin_settings('sa11y_enable');
    $user = wp_get_current_user();
    $allowed_roles = array('editor', 'administrator', 'author', 'contributor');
    $allowed_user_roles = array_intersect($allowed_roles, $user->roles);

    // Check if scroll top enable.
    if ($enable === 1
        && is_user_logged_in() 
        && ($allowed_user_roles || current_user_can('edit_posts') || current_user_can('edit_pages'))
    ) {

        wp_enqueue_style('sa11y-wp-css', trailingslashit(SA11Y_ASSETS) . 'src/sa11y.min.css', null);
        wp_enqueue_script('sa11y-wp-tippy', trailingslashit(SA11Y_ASSETS) . 'src/tippy.umd.min.js', null, true);

        $lang = get_locale();
        if ( strlen( $lang ) > 0 ) {
            $lang = explode( '_', $lang )[0];
        }
        
        if ($lang == "fr") {
            wp_enqueue_script('sa11y-wp-lang', trailingslashit(SA11Y_ASSETS) . 'src/lang/fr-ca.min.js', null, true);
        } else if ($lang == "uk") {
            wp_enqueue_script('sa11y-wp-lang', trailingslashit(SA11Y_ASSETS) . 'src/lang/ua.min.js', null, true);
        } else if ($lang == "pl") {
            wp_enqueue_script('sa11y-wp-lang', trailingslashit(SA11Y_ASSETS) . 'src/lang/pl.min.js', null, true);
        } else {
            wp_enqueue_script('sa11y-wp-lang', trailingslashit(SA11Y_ASSETS) . 'src/lang/en.min.js', null, true);
        }
                
        wp_enqueue_script('sa11y-wp-js', trailingslashit(SA11Y_ASSETS) . 'src/sa11y.min.js', null, true);
    }
}
add_action('wp_enqueue_scripts', 'sa11y_load_scripts');

/**
 * Initialize.
 */
function sa11y_init() {

    // Get the plugin settings value
    $enable = absint(sa11y_get_plugin_settings('sa11y_enable'));
    $target = esc_html__(sa11y_get_plugin_settings('sa11y_target'));
    $contrast = absint(sa11y_get_plugin_settings('sa11y_contrast'));
    $forms = absint(sa11y_get_plugin_settings('sa11y_forms'));
    $linksAdvanced = absint(sa11y_get_plugin_settings('sa11y_links_advanced'));

    $readability = absint(sa11y_get_plugin_settings('sa11y_readability'));
    $readabilityTarget = esc_html__(sa11y_get_plugin_settings('sa11y_readability_target'));
    $readabilityIgnore = esc_html__(sa11y_get_plugin_settings('sa11y_readability_ignore'));

    $containerIgnore = esc_html__(sa11y_get_plugin_settings('sa11y_container_ignore'));
    $contrastIgnore = esc_html__(sa11y_get_plugin_settings('sa11y_contrast_ignore'));
    $outlineIgnore = esc_html__(sa11y_get_plugin_settings('sa11y_outline_ignore'));
    $headerIgnore = esc_html__(sa11y_get_plugin_settings('sa11y_header_ignore'));
    $imageIgnore = esc_html__(sa11y_get_plugin_settings('sa11y_image_ignore'));
    $linkIgnore = esc_html__(sa11y_get_plugin_settings('sa11y_link_ignore'));
    $linkIgnoreSpan = esc_html__(sa11y_get_plugin_settings('sa11y_link_ignore_span'));
    $linksToFlag = esc_html__(sa11y_get_plugin_settings('sa11y_links_to_flag'));

    //Embedded content.
    $videoContent = wp_filter_nohtml_kses(sa11y_get_plugin_settings('sa11y_videoContent'));
    $audioContent = wp_filter_nohtml_kses(sa11y_get_plugin_settings('sa11y_audioContent'));
    $dataVizContent = wp_filter_nohtml_kses(sa11y_get_plugin_settings('sa11y_dataVizContent'));

    //Advanced settings.
    $sa11yNoRun = esc_html__(sa11y_get_plugin_settings('sa11y_no_run'));
    $extraProps = wp_filter_nohtml_kses(sa11y_get_plugin_settings('sa11y_extra_props'));

    // Target area
    if (empty($target)) {
        $target = 'body';
    }
    // Readability target area
    if (empty($readabilityTarget)) {
        $readabilityTarget = 'body';
    }

    // Readability plugin
    $readabilityOn = '';
    if ($readability === 1) {
        $readabilityOn = 'true';
    } else {
        $readabilityOn = 'false';
    }

    // Contrast plugin
    $contrastOn = '';
    if ($contrast === 1) {
        $contrastOn = 'true';
    } else {
        $contrastOn = 'false';
    }

    // Forms plugin
    $formsOn = '';
    if ($forms === 1) {
        $formsOn = 'true';
    } else {
        $formsOn = 'false';
    }

    $linksAdvancedOn = '';
    if ($linksAdvanced === 1) {
        $linksAdvancedOn = 'true';
    } else {
        $linksAdvancedOn = 'false';
    }

    // Allowed roles.
    $user = wp_get_current_user();
    $allowed_roles = array('editor', 'administrator', 'author', 'contributor');
    $allowed_user_roles = array_intersect($allowed_roles, $user->roles);

    // Instantiates Sa11y on the page for allowed users.
    if ($enable === 1
        && is_user_logged_in() 
        && ($allowed_user_roles || current_user_can('edit_posts') || current_user_can('edit_pages'))
    ) {

        //Allowed characters before echoing.
        $r = array('&gt;' => '>', '&quot;' => '"', '&#039;' => '"');

        echo '
		<script id="sa11y-wp-init">
            const instantiateSa11y = new Sa11y({
                checkRoot:  \'' . strtr($target, $r) . '\',
                containerIgnore: \'' . strtr($containerIgnore, $r) . '\',
                contrastIgnore: \'' . strtr($contrastIgnore, $r) . '\',
                outlineIgnore: \'' . strtr($outlineIgnore, $r) . '\',
                headerIgnore: \'' . strtr($headerIgnore, $r) . '\',
                imageIgnore: \'' . strtr($imageIgnore, $r) . '\',
                linkIgnore: \'' . strtr($linkIgnore, $r) . '\',
                linkIgnoreSpan: \'' . strtr($linkIgnoreSpan, $r) . '\',
                linksToFlag: \'' . strtr($linksToFlag, $r) . '\',
                readabilityPlugin: ' . $readabilityOn . ',
                readabilityRoot: \'' . strtr($readabilityTarget, $r) . '\',
                readabilityIgnore: \'' . strtr($readabilityIgnore, $r) . '\',
                contrastPlugin: ' . $contrastOn . ',
                formLabelsPlugin: ' . $formsOn . ',
                linksAdvancedPlugin: ' . $linksAdvancedOn . ',
                videoContent: \'' . $videoContent . '\',
                audioContent: \'' . $audioContent . '\',
                dataVizContent: \'' . $dataVizContent . '\',
                doNotRun: \'' . $sa11yNoRun . '\',
                ' . $extraProps . '
            });
		</script>';
    }
}
add_action('wp_footer', 'sa11y_init');