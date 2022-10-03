<?php

/**
 * Settings functions for the plugin.
 */

/* Allowed HTML for all translatable text strings */
global $sa11y_allowed_html;
$sa11y_allowed_html = array(
    'em' => array(),
    'strong' => array(),
    'code' => array(),
);

/**
 * Sets up the plugin settings page and registers the plugin settings.
 * @link   http://codex.wordpress.org/Function_Reference/add_options_page
 */
function sa11y_admin_menu() {
    $settings = add_options_page(
        esc_html__('Sa11y - Advanced Settings', 'sa11y-wp'),
        esc_html__('Sa11y', 'sa11y-wp'),
        'manage_options',
        'sa11y',
        'sa11y_plugin_settings_render_page'
    );
    if (!$settings) {
        return;
    }
    // Provided hook_suffix that's returned to add scripts only on settings page.
    add_action('load-' . $settings, 'sa11y_styles_scripts');
}
add_action('admin_menu', 'sa11y_admin_menu');

/**
 * Enqueue custom styles & scripts for plugin usage.
 */
function sa11y_styles_scripts() {
    // Load plugin admin style.
    wp_enqueue_style('sa11y-wp-css', trailingslashit(SA11Y_ASSETS) . 'css/sa11y-wp-admin.css', null);
}

/**
 * Register settings.
 * @link   http://codex.wordpress.org/Function_Reference/register_setting
 */
function sa11y_register_settings() {

    register_setting(
        'sa11y_settings',
        'sa11y_plugin_settings',
        'sa11y_plugin_settings_validate'
    );
}
add_action('admin_init', 'sa11y_register_settings');

/**
 * Register the setting sections and fields.
 * @link   http://codex.wordpress.org/Function_Reference/add_settings_section
 * @link   http://codex.wordpress.org/Function_Reference/add_settings_field
 */
function sa11y_setting_sections_fields() {

    /* Sections */

    // Add General section.
    add_settings_section(
        'sa11y_general_settings',
        '',
        '__return_false',
        'sa11y'
    );

    // Add General section.
    add_settings_section(
        'sa11y_readability_settings',
        __( 'Readability', 'sa11y-wp' ),
        '__return_false',
        'sa11y'
    );

    // Add General section.
    add_settings_section(
        'sa11y_exclusions_settings',
        __( 'Exclusions', 'sa11y-wp' ),
        '__return_false',
        'sa11y'
    );

    // Add Embedded content section.
    add_settings_section(
        'sa11y_embedded_content_settings',
        __( 'Embedded content', 'sa11y-wp' ),
        '__return_false',
        'sa11y'
    );

    // Add Advanced section.
    add_settings_section(
        'sa11y_advanced_settings',
        __( 'Advanced', 'sa11y-wp' ),
        '__return_false',
        'sa11y'
    );

    /* Fields */

    // Add enable/disable checkbox setting field.
    add_settings_field(
        'sa11y_enable',
        esc_html__('Enable Sa11y', 'sa11y-wp'),
        'sa11y_enable_field',
        'sa11y',
        'sa11y_general_settings',
        array( 'label_for' => 'sa11y_enable' )
    );

     /* Add readability target input setting field.
     add_settings_field(
        'sa11y_lang',
        esc_html__('Language', 'sa11y-wp'),
        'sa11y_lang_field',
        'sa11y',
        'sa11y_general_settings'
    );*/

    // Add 'Target' input setting field.
    add_settings_field(
        'sa11y_target',
        esc_html__('Target area to check', 'sa11y-wp'),
        'sa11y_target_field',
        'sa11y',
        'sa11y_general_settings',
        array( 'label_for' => 'sa11y_target' )
    );

     // Contrast module.
     add_settings_field(
        'sa11y_contrast',
        esc_html__('Show Contrast toggle', 'sa11y-wp'),
        'sa11y_contrast_field',
        'sa11y',
        'sa11y_general_settings',
        array( 'label_for' => 'sa11y_contrast' )
    );

     // Forms module.
     add_settings_field(
        'sa11y_forms',
        esc_html__('Show Form Labels toggle', 'sa11y-wp'),
        'sa11y_forms_field',
        'sa11y',
        'sa11y_general_settings',
        array( 'label_for' => 'sa11y_forms' )
    );

    // Links advanced module.
    add_settings_field(
        'sa11y_links_advanced',
        esc_html__('Show Links (Advanced) toggle', 'sa11y-wp'),
        'sa11y_links_advanced_field',
        'sa11y',
        'sa11y_general_settings',
        array( 'label_for' => 'sa11y_links_advanced' )
    );

     // Add Readability checkbox setting.
     add_settings_field(
        'sa11y_readability',
        esc_html__('Show Readability toggle', 'sa11y-wp'),
        'sa11y_readability_field',
        'sa11y',
        'sa11y_readability_settings',
        array( 'label_for' => 'sa11y_readability' )
    );

     // Add readability target input setting field.
     add_settings_field(
        'sa11y_readability_target',
        esc_html__('Readability scan area', 'sa11y-wp'),
        'sa11y_readability_target_field',
        'sa11y',
        'sa11y_readability_settings',
        array( 'label_for' => 'sa11y_readability_target' )
    );

    // Add readability ignore field.
    add_settings_field(
        'sa11y_readability_ignore',
        esc_html__('Readability exclusions', 'sa11y-wp'),
        'sa11y_readability_ignore_field',
        'sa11y',
        'sa11y_readability_settings',
        array( 'label_for' => 'sa11y_readability_ignore' )
    );

    // Add container ignore field.
    add_settings_field(
        'sa11y_container_ignore',
        esc_html__('Regions to ignore', 'sa11y-wp'),
        'sa11y_container_ignore_field',
        'sa11y',
        'sa11y_exclusions_settings',
        array( 'label_for' => 'sa11y_container_ignore' )
    );

    // Add contrast ignore field.
    add_settings_field(
        'sa11y_contrast_ignore',
        esc_html__('Exclude from contrast check', 'sa11y-wp'),
        'sa11y_contrast_ignore_field',
        'sa11y',
        'sa11y_exclusions_settings',
        array( 'label_for' => 'sa11y_contrast_ignore' )
    );

     // Add outline ignore field.
     add_settings_field(
        'sa11y_outline_ignore',
        esc_html__('Exclude headings from outline', 'sa11y-wp'),
        'sa11y_outline_ignore_field',
        'sa11y',
        'sa11y_exclusions_settings',
        array( 'label_for' => 'sa11y_outline_ignore' )
    );

     // Add heading ignore field.
     add_settings_field(
        'sa11y_header_ignore',
        esc_html__('Exclude headings', 'sa11y-wp'),
        'sa11y_header_ignore_field',
        'sa11y',
        'sa11y_exclusions_settings',
        array( 'label_for' => 'sa11y_header_ignore' )
    );

     // Add image ignore field.
     add_settings_field(
        'sa11y_image_ignore',
        esc_html__('Exclude images', 'sa11y-wp'),
        'sa11y_image_ignore_field',
        'sa11y',
        'sa11y_exclusions_settings',
        array( 'label_for' => 'sa11y_image_ignore' )
    );

    // Add link ignore field.
    add_settings_field(
        'sa11y_link_ignore',
        esc_html__('Exclude links', 'sa11y-wp'),
        'sa11y_link_ignore_field',
        'sa11y',
        'sa11y_exclusions_settings',
        array( 'label_for' => 'sa11y_link_ignore' )
    );

    // Add link span ignore field.
    add_settings_field(
        'sa11y_link_ignore_span',
        esc_html__('Ignore elements within links', 'sa11y-wp'),
        'sa11y_link_ignore_span_field',
        'sa11y',
        'sa11y_exclusions_settings',
        array( 'label_for' => 'sa11y_link_ignore_span' )
    );

    // Add link span ignore field.
    add_settings_field(
        'sa11y_links_to_flag',
        esc_html__('Flag links as an error', 'sa11y-wp'),
        'sa11y_links_to_flag_field',
        'sa11y',
        'sa11y_exclusions_settings',
        array( 'label_for' => 'sa11y_links_to_flag' )
    );

    // Video content
    add_settings_field(
        'sa11y_videoContent',
        esc_html__('Video sources', 'sa11y-wp'),
        'sa11y_videoContent_field',
        'sa11y',
        'sa11y_embedded_content_settings',
        array( 'label_for' => 'sa11y_videoContent' )
    );
    // Audio content
    add_settings_field(
        'sa11y_audioContent',
        esc_html__('Audio sources', 'sa11y-wp'),
        'sa11y_audioContent_field',
        'sa11y',
        'sa11y_embedded_content_settings',
        array( 'label_for' => 'sa11y_audioContent' )
    );
    // dataVizContent content
    add_settings_field(
        'sa11y_dataVizContent',
        esc_html__('Data visualization sources', 'sa11y-wp'),
        'sa11y_dataVizContent_field',
        'sa11y',
        'sa11y_embedded_content_settings',
        array( 'label_for' => 'sa11y_dataVizContent' )
    );

    // Don't run sa11y if these elements exist
    add_settings_field(
        'sa11y_no_run',
        esc_html__('Turn off Sa11y if these elements exist', 'sa11y-wp'),
        'sa11y_no_run_field',
        'sa11y',
        'sa11y_advanced_settings',
        array( 'label_for' => 'sa11y_no_run' )
    );

    // Add 'Extra Props' textarea setting field.
    add_settings_field(
        'sa11y_extra_props',
        esc_html__('Add extra props', 'sa11y-wp'),
        'sa11y_extra_props_field',
        'sa11y',
        'sa11y_advanced_settings',
        array( 'label_for' => 'sa11y_extra_props' )
    );
}
add_action('admin_init', 'sa11y_setting_sections_fields');

/**
 * Enable/disable field
 */
function sa11y_enable_field() {
    $settings = sa11y_get_plugin_settings('sa11y_enable');
?>
    <input id="sa11y_enable" type="checkbox" name="sa11y_plugin_settings[sa11y_enable]" aria-describedby="enable_description" value="1" <?php checked(1, $settings); ?> />
    <p id="enable_description">
        <?php
            $string = 'Enable for all administrators, editors, authors, contributors, and anyone who has permissions to edit posts and pages.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Target field
 */
function sa11y_target_field() {
    $settings = sa11y_get_plugin_settings('sa11y_target');
?>
    <input autocomplete="off" name="sa11y_plugin_settings[sa11y_target]" type="text" id="sa11y_target" value="<?php echo esc_attr($settings); ?>" aria-describedby="target_description" pattern="[^<>\\\x27;|@&\s]+"/>
    <p id="target_description">
        <?php
            $string = 'Input a <strong>single selector</strong> to target a specific region of your website. For example, use <code>main</code> to scan the main content region only.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Enable/disable field
 */
function sa11y_contrast_field() {
    $settings = sa11y_get_plugin_settings('sa11y_contrast');
?>
    <input id="sa11y_contrast" type="checkbox" name="sa11y_plugin_settings[sa11y_contrast]" value="1" <?php checked(1, $settings); ?> />
<?php
}

/**
 * Enable/disable field
 */
function sa11y_forms_field() {
    $settings = sa11y_get_plugin_settings('sa11y_forms');
?>
    <input id="sa11y_forms" type="checkbox" name="sa11y_plugin_settings[sa11y_forms]" value="1" <?php checked(1, $settings); ?> />
<?php
}

/**
 * Enable/disable field
 */
function sa11y_links_advanced_field() {
    $settings = sa11y_get_plugin_settings('sa11y_links_advanced');
?>
    <input id="sa11y_links_advanced" type="checkbox" name="sa11y_plugin_settings[sa11y_links_advanced]" value="1" <?php checked(1, $settings); ?> />
<?php
}

/**
 * Readability enable/disable field
 */
function sa11y_readability_field() {
    $settings = sa11y_get_plugin_settings('sa11y_readability');
?>
    <input id="sa11y_readability" type="checkbox" name="sa11y_plugin_settings[sa11y_readability]" value="1" <?php checked(1, $settings); ?> />
<?php
}

/**
 * Readability target field
 */
function sa11y_readability_target_field() {
    $settings = sa11y_get_plugin_settings('sa11y_readability_target');
?>
    <input autocomplete="off" name="sa11y_plugin_settings[sa11y_readability_target]" type="text" id="sa11y_readability_target" value="<?php echo esc_attr($settings); ?>" aria-describedby="readability_target_description" pattern="[^<>\\\x27;|@&\s]+"/>
    <p id="readability_target_description">
        <?php
            $string = 'Input a <strong>single selector</strong> to target a specific region of your website. For example, use <code>main</code> to scan the main content region only.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Container ignore field
 */
function sa11y_readability_ignore_field() {
    $settings = sa11y_get_plugin_settings('sa11y_readability_ignore');
?>
    <input autocomplete="off" class="regular-text" id="sa11y_readability_ignore" aria-describedby="readability_exclusions_description" type="text" name="sa11y_plugin_settings[sa11y_readability_ignore]" value="<?php echo esc_attr($settings); ?>" />
    <p id="readability_exclusions_description">
        <?php
            $string = 'Exclude specific elements from the readability analysis. Only paragraph or list content is analyzed. Content within navigation elements are ignored.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Container ignore field
 */
function sa11y_container_ignore_field() {
    $settings = sa11y_get_plugin_settings('sa11y_container_ignore');
?>
    <input autocomplete="off" class="regular-text" id="sa11y_container_ignore" aria-describedby="exclusions_description" type="text" name="sa11y_plugin_settings[sa11y_container_ignore]" value="<?php echo esc_attr($settings); ?>" pattern="[^<\\\x27;|@&]+"/>
    <p id="exclusions_description">
        <?php
            $string = 'Ignore entire regions of a page. For example, <code>#comments</code> to ignore the Comments section on all pages.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Contrast ignore field
 */
function sa11y_contrast_ignore_field() {
    $settings = sa11y_get_plugin_settings('sa11y_contrast_ignore');
?>

    <input autocomplete="off" class="regular-text" id="sa11y_contrast_ignore" aria-describedby="contrast_description" type="text" name="sa11y_plugin_settings[sa11y_contrast_ignore]" value="<?php echo esc_attr($settings); ?>" pattern="[^<\\\x27;|@&]+"/>
    <p id="contrast_description">
        <?php
            $string = 'Ignore specific elements from the contrast check. For example, <code>.sr-only</code> or classes that (intentionally) visually hide elements.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>

<?php
}

/**
 * Outline ignore field
 */
function sa11y_outline_ignore_field() {
    $settings = sa11y_get_plugin_settings('sa11y_outline_ignore');
?>
    <input autocomplete="off" class="regular-text" id="sa11y_outline_ignore" aria-describedby="outline_description" type="text" name="sa11y_plugin_settings[sa11y_outline_ignore]" value="<?php echo esc_attr($settings); ?>" pattern="[^<\\\x27;|@&]+"/>
    <p id="outline_description">
        <?php
            $string = 'Ignore specific headings from appearing in the "Show Outline" panel. For example, visually hidden headings that content authors do not see.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>

<?php
}

/**
 * Heading ignore field
 */
function sa11y_header_ignore_field() {
    $settings = sa11y_get_plugin_settings('sa11y_header_ignore');
?>
    <input autocomplete="off" class="regular-text" id="sa11y_header_ignore" aria-describedby="header_description" type="text" name="sa11y_plugin_settings[sa11y_header_ignore]" value="<?php echo esc_attr($settings); ?>" pattern="[^<\\\x27;|@&]+"/>
    <p id="header_description">
        <?php
            $string = 'Exclude specific headings from all checks.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Image ignore field
 */
function sa11y_image_ignore_field() {
    $settings = sa11y_get_plugin_settings('sa11y_image_ignore');
?>
    <input autocomplete="off" class="regular-text" id="sa11y_image_ignore" aria-describedby="image_description" type="text" name="sa11y_plugin_settings[sa11y_image_ignore]" value="<?php echo esc_attr($settings); ?>" pattern="[^<\\\x27;|@&]+"/>
    <p id="image_description">
        <?php
            $string = 'Exclude specific images from all checks. For example, add <code>.avatar</code> to ignore all avatar images within the Comments section.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Link ignore field
 */
function sa11y_link_ignore_field() {
    $settings = sa11y_get_plugin_settings('sa11y_link_ignore');
?>
    <input autocomplete="off" class="regular-text" id="sa11y_link_ignore" aria-describedby="link_description" type="text" name="sa11y_plugin_settings[sa11y_link_ignore]" value="<?php echo esc_attr($settings); ?>" pattern="[^<\\\x27;|@&]+"/>
    <p id="link_description">
        <?php
            $string = 'Exclude specific links from all checks.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Link span ignore field
 */
function sa11y_link_ignore_span_field() {
    $settings = sa11y_get_plugin_settings('sa11y_link_ignore_span');
?>
    <input autocomplete="off" class="regular-text" id="sa11y_link_ignore_span" aria-describedby="link_description" type="text" name="sa11y_plugin_settings[sa11y_link_ignore_span]" value="<?php echo esc_attr($settings); ?>" pattern="[^<\\\x27;|@&]+"/>
    <p id="link_span_description">
        <?php
            $string = 'Ignore elements within a link to improve accuracy of link checks. For example: <code>&lt;a href=&#34;#&#34;&gt;learn more <strong>&lt;span class=&#34;sr-only&#34;&gt;external link&lt;/span&gt;</strong>&lt;/a&gt;</code>';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Links to flag as error field
 */
function sa11y_links_to_flag_field() {
    $settings = sa11y_get_plugin_settings('sa11y_links_to_flag');
?>
    <input autocomplete="off" class="regular-text" id="sa11y_links_to_flag" aria-describedby="links_to_flag_description" type="text" name="sa11y_plugin_settings[sa11y_links_to_flag]" value="<?php echo esc_attr($settings); ?>" pattern="[^<\\\x27;|@&]+"/>
    <p id="links_to_flag_description">
        <?php
            $string = 'Flag absolute URLs that point to a development environment as an error. For example, all links that start with "dev": <code>a[href^="https://dev."]</code>';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>

<?php
}

/**
 * Video field
 */
function sa11y_videoContent_field() {
    $settings = sa11y_get_plugin_settings('sa11y_videoContent');
?>
   <textarea required id="sa11y_videoContent" name="sa11y_plugin_settings[sa11y_videoContent]" cols="45" rows="3"><?php echo esc_html($settings); ?></textarea>
<?php
}

/**
 * Audio field
 */
function sa11y_audioContent_field() {
    $settings = sa11y_get_plugin_settings('sa11y_audioContent');
?>
    <textarea required id="sa11y_audioContent" name="sa11y_plugin_settings[sa11y_audioContent]" cols="45" rows="3"><?php echo esc_html($settings); ?></textarea>
<?php
}

/**
 * dataVizContent
 */
function sa11y_dataVizContent_field() {
    $settings = sa11y_get_plugin_settings('sa11y_dataVizContent');
?>
   <textarea required id="sa11y_dataVizContent" name="sa11y_plugin_settings[sa11y_dataVizContent]" cols="45" rows="3"><?php echo esc_html($settings); ?></textarea>
<?php
}

/**
 * Turn off Sa11y if these elements are detected
 */
function sa11y_no_run_field() {
    $settings = sa11y_get_plugin_settings('sa11y_no_run');
?>
    <input autocomplete="off" class="regular-text" id="sa11y_no_run" aria-describedby="sa11y_no_run_description" type="text" name="sa11y_plugin_settings[sa11y_no_run]" value="<?php echo esc_attr($settings); ?>" pattern="[^<>\\\x27;|@&]+"/>
    <p id="sa11y_no_run_description">
        <?php
            $string = 'Provide a list of selectors that are <strong>unique to pages</strong>. If any of the elements exist on the page, Sa11y will not scan or appear.';
            global $sa11y_allowed_html;
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);
        ?>
    </p>
<?php
}

/**
 * Extra props
 */
function sa11y_extra_props_field() {
    $settings = sa11y_get_plugin_settings('sa11y_extra_props');
?>
    <textarea name="sa11y_plugin_settings[sa11y_extra_props]" aria-describedby="extra_props_description" id="sa11y_extra_props" cols="45" rows="3"><?php echo esc_html($settings); ?></textarea>
    <p id="extra_props_description">
        <?php
            $domain = esc_url( __('https://sa11y.netlify.app/developers/props/', 'sa11y-wp'));
            $string = 'Pass additional (boolean) properties to customize. Refer to ';
            global $sa11y_allowed_html;
            $anchor = esc_html__( 'documentation.', 'sa11y-wp' );
            echo wp_kses( __($string, 'sa11y-wp'), $sa11y_allowed_html);

            $link = sprintf( '<a href="%s">%s</a>', $domain, $anchor );
            echo sprintf( esc_html__( '%1$s', 'sa11y-wp' ), $link );
        ?>
    </p>
<?php
}

/**
 * Render the plugin settings page.
 */
function sa11y_plugin_settings_render_page() { ?>

    <div class="wrap">
        <h1><?php esc_html_e('Sa11y - Advanced Settings', 'sa11y-wp'); ?></h1>

        <div id="poststuff">
            <div id="post-body" class="sa11y-wp-settings metabox-holder columns-2">
                <div id="post-body-content">

                <div class="announcement-component">
                    <h2 class="announcement-heading"><?php
                        $current_user = wp_get_current_user();
                        esc_html_e('Howdy ' . $current_user->nickname . ',' , 'sa11y-wp' );
                    ?></h2>
                    <p><?php
                            global $sa11y_allowed_html;
                            $welcome = 'Sa11y is your accessibility quality assurance assistant. Sa11y works in <strong>Preview</strong> mode on all pages and posts. Use this settings page to customize the experience for website authors. Please note, Sa11y is not a comprehensive code analysis tool. You should make sure you are using an accessible theme.';
                            echo wp_kses( __($welcome, 'sa11y-wp'), $sa11y_allowed_html);
                    ?></p>
                    <p style="padding-top:8px"><?php
                        esc_html_e('To learn more about Sa11y, please visit the ', 'sa11y-wp');
                        $domain = esc_url( __('https://sa11y.netlify.app/', 'sa11y-wp'));
                        $anchor = esc_html__( 'project website.', 'sa11y-wp' );
                        $link = sprintf( '<a href="%s">%s</a>', $domain, $anchor );
                        echo sprintf( esc_html__( '%1$s', 'sa11y-wp' ), $link );
                    ?></p>
                </div>

                <form method="post" action="options.php" autocomplete="off" class="sa11y-form-admin">
                    <?php settings_fields('sa11y_settings'); ?>
                    <?php do_settings_sections('sa11y'); ?>
                    <?php submit_button(esc_html__('Save Settings', 'sa11y-wp'), 'primary large'); ?>
                </form>
            </div><!-- .post-body-content -->

            <div id="postbox-container-1" class="postbox-container">
                <div class="postbox">
                    <h2 class="screen-reader-text"><?php esc_html_e( 'More', 'sa11y-wp' ); ?></h2>
                    <div class="inside">
                        <h3 class="postbox-heading"><?php esc_html_e( 'Administrator guide', 'sa11y-wp' ); ?></h3>
                        <ul>
                            <li><?php esc_html_e( 'Specify the target area to check. Only check content your authors can edit.', 'sa11y-wp' ); ?></li>
                            <li><?php esc_html_e( 'Turn off checks or features that are not relevant, including issues that cannot be fixed by content authors.', 'sa11y-wp' ); ?></li>
                            <li><?php
                                $anchor = esc_html__( 'CSS selectors reference.', 'sa11y-wp' );
                                $domain = esc_url( __( 'https://www.w3schools.com/cssref/css_selectors.asp', 'sa11y-wp'));
                                $link = sprintf( '<a href="%s">%s</a>', $domain, $anchor );
                                echo sprintf( esc_html__( 'Create exclusions or ignore repetitive elements using CSS selectors. Use a comma to seperate multiple selectors. View %1$s', 'sa11y-wp' ), $link );
                            ?></li>
                        </ul>

                        <h3 class="postbox-heading"><?php esc_html_e( 'Contribute', 'sa11y-wp' ); ?></h3>
                        <ul>
                            <li>
                                <?php
                                $anchor = esc_html__( 'Report a bug or leave feedback', 'sa11y-wp' );
                                $domain = esc_url( __( 'https://forms.gle/sjzK9XykETaoqZv99', 'sa11y-wp' ) );
                                $link   = sprintf( '<a href="%s">%s</a>', $domain, $anchor );
                                echo sprintf( esc_html_x( '%1$s', 'sa11y-wp' ), $link );
                                ?>
                            </li>
                            <li>
                                <?php
                                    $anchor = esc_html__( 'Help translate or improve', 'sa11y-wp' );
                                    $domain = esc_url( __( 'https://github.com/ryersondmp/sa11y/blob/master/CONTRIBUTING.md', 'sa11y-wp' ) );
                                    $link   = sprintf( '<a href="%s">%s</a>', $domain, $anchor );
                                    echo sprintf( esc_html_x( '%1$s', 'sa11y-wp' ), $link );
                                    ?>
                            </li>
                        </ul>

                        <h3 class="postbox-heading"><?php esc_html_e( 'Version', 'sa11y-wp' ); ?></h3>
                        <ul>
                            <li>
                                <strong><?php esc_html_e( 'Sa11y', 'sa11y-wp' ); ?>:</strong>
                                <?php echo Sa11y_WP::SA11Y_VERSION; ?>
                            </li>
                            <li>
                                <strong><?php esc_html_e( 'Plugin', 'sa11y-wp' ); ?>:</strong>
                                <?php echo Sa11y_WP::WP_VERSION; ?>
                                <strong class="sa11y-admin-badge">Beta</strong>
                            </li>
                        </ul>

                        <h3 class="postbox-heading"><?php esc_html_e( 'Acknowledgements', 'sa11y-wp' ); ?></h3>
                        <p><?php
                            $anchor = esc_html__( 'all acknowledgements.', 'sa11y-wp' );
                            $domain = esc_url( __( 'https://sa11y.netlify.app/acknowledgements/', 'sa11y-wp' ) );
                            $link   = sprintf( '<a href="%s">%s</a>', $domain, $anchor );
                            echo sprintf( esc_html__( 'Development led by Adam Chaboryk at Toronto Metropolitan University. View %1$s', 'sa11y-wp' ), $link );
                            ?></p>
                        <br>
                        <p><?php esc_html_e( '© 2022 Toronto Metropolitan University.', 'sa11y-wp' ); ?></p>
                    </div>
                </div>
            </div><!-- .postbox-container -->

            </div><!-- .sa11y-wp-settings -->
            <br class="clear">
        </div>
    </div>
<?php
}

/**
 * Validates/sanitizes the plugins settings after they've been submitted.
 */
function sa11y_plugin_settings_validate($settings) {

    /* Deep cleaning to help with error handling and security */
    $remove = array(
        '&lt;' => '',
        '&apos;' => '',
        '&amp;' => '',
        '&percnt;' => '',
        '&#96;' => '',
        '`' => '');
    $removeExtra = array(
        '&gt;' => '',
        '>' => '');
    $targetRemove = array_merge($remove, $removeExtra);

    /* Basic settings */
    $settings['sa11y_enable'] = (isset(
        $settings['sa11y_enable']) && 1 == $settings['sa11y_enable'] ? 1 : 0);

    $settings['sa11y_target'] = strtr(
        sanitize_text_field($settings['sa11y_target']), $targetRemove);

    $settings['sa11y_contrast'] = (isset(
        $settings['sa11y_contrast']) && 1 == $settings['sa11y_contrast'] ? 1 : 0);

    $settings['sa11y_forms'] = (isset(
        $settings['sa11y_forms']) && 1 == $settings['sa11y_forms'] ? 1 : 0);

    $settings['sa11y_links_advanced'] = (isset(
        $settings['sa11y_links_advanced']) && 1 == $settings['sa11y_links_advanced'] ? 1 : 0);

    /* Readability */
    $settings['sa11y_readability'] = (isset(
        $settings['sa11y_readability']) && 1 == $settings['sa11y_readability'] ? 1 : 0);

    $settings['sa11y_readability_target'] = strtr(
        sanitize_text_field($settings['sa11y_readability_target']), $targetRemove);

    $settings['sa11y_readability_ignore'] = strtr(
        sanitize_text_field($settings['sa11y_readability_ignore']), $remove);

    // Set up an array of valid settings.
    $valid_language = array('en', 'fr', 'es', 'de', 'nl', 'it');
    // If the option is NOT in the array, set it to a default option. Do nothing if the option is valid.
    if (!in_array($settings['sa11y_lang'], $valid_language)) {
        esc_html($settings['sa11y_lang'] = 'en');
    }

    /* Exclusions */
    $settings['sa11y_container_ignore'] = strtr(
        sanitize_text_field($settings['sa11y_container_ignore']), $remove);

    $settings['sa11y_contrast_ignore'] = strtr(
        sanitize_text_field($settings['sa11y_contrast_ignore']), $remove);

    $settings['sa11y_outline_ignore'] = strtr(
        sanitize_text_field($settings['sa11y_outline_ignore']), $remove);

    $settings['sa11y_header_ignore'] = strtr(
        sanitize_text_field($settings['sa11y_header_ignore']), $remove);

    $settings['sa11y_image_ignore'] = strtr(
        sanitize_text_field($settings['sa11y_image_ignore']), $remove);

    $settings['sa11y_link_ignore'] = strtr(
        sanitize_text_field($settings['sa11y_link_ignore']), $remove);

    $settings['sa11y_link_ignore_span'] = strtr(
        sanitize_text_field($settings['sa11y_link_ignore_span']), $remove);

    $settings['sa11y_links_to_flag'] = strtr(
        sanitize_text_field($settings['sa11y_links_to_flag']), $remove);

    /* Regex match for deep cleaning */
    /* Allowed characters: , . : empty space */
    $specialChars = '/[^.,:a-zA-Z0-9 ]/';

    /* Video */
    $settings['sa11y_videoContent'] = preg_replace($specialChars, '',
        sanitize_text_field($settings['sa11y_videoContent']));

    /* Audio */
    $settings['sa11y_audioContent'] = preg_replace($specialChars, '',
        sanitize_text_field($settings['sa11y_audioContent']));

    /* Data Visualizations */
    $settings['sa11y_dataVizContent'] =  preg_replace($specialChars, '',
        sanitize_text_field($settings['sa11y_dataVizContent']));

    /* Don't run Sa11y */
    $settings['sa11y_no_run'] = strtr(
        sanitize_text_field($settings['sa11y_no_run']), $targetRemove);

    /* Advanced props */
    $settings['sa11y_extra_props'] =  preg_replace($specialChars, '',
        sanitize_text_field($settings['sa11y_extra_props']));

    return $settings;
}
