<?php
if (!defined('ABSPATH')) exit;
/* Define an array of translatable strings as a constant. These strings are all used at least twice. */

/* Section labels */
define('SA11Y_SECTION', [
  'GENERAL' => __('General', 'sa11y-i18n'),
  'READABILITY' => __('Readability', 'sa11y-i18n'),
  'ADDITIONAL' => __('Additional checks', 'sa11y-i18n'),
  'EXCLUSIONS' => __('Exclusions', 'sa11y-i18n'),
  'EMBEDDED' => __('Embedded content', 'sa11y-i18n'),
  'ADVANCED' => __('Advanced settings', 'sa11y-i18n'),
]);

/* Setting labels */
define('SA11Y_LABEL', [
  'SA11Y_ADVANCED' => __('Sa11y - Advanced Settings', 'sa11y-i18n'),
  'ENABLE' => __('Enable Sa11y', 'sa11y-i18n'),
  'TARGET' => __('Target area to check', 'sa11y-i18n'),
  'POSITION' => __('Panel position', 'sa11y-i18n'),
  'CONTRAST' => __('Contrast', 'sa11y-i18n'),
  'FORM_LABELS' => __('Form Labels', 'sa11y-i18n'),
  'LINKS_ADVANCED' => __('Links (Advanced)', 'sa11y-i18n'),
  'COLOUR_FILTER' => __('Colour Filter', 'sa11y-i18n'),
  'ALL_CHECKS' => __('Make all additional checks required by default', 'sa11y-i18n'),
  'READABILITY' => __('Readability', 'sa11y-i18n'),
  'READABILITY_TARGET' => __('Readability target area', 'sa11y-i18n'),
  'READABILITY_EXCLUSIONS' => __('Readability exclusions', 'sa11y-i18n'),
  'REGION_IGNORE' => __('Regions to ignore', 'sa11y-i18n'),
  'CONTRAST_IGNORE' => __('Exclude from contrast check', 'sa11y-i18n'),
  'OUTLINE_IGNORE' => __('Exclude headings from outline', 'sa11y-i18n'),
  'HEADING_IGNORE' => __('Exclude headings', 'sa11y-i18n'),
  'IMAGE_IGNORE' => __('Exclude images', 'sa11y-i18n'),
  'LINK_IGNORE' => __('Exclude links', 'sa11y-i18n'),
  'LINK_IGNORE_SPAN' => __('Ignore elements within links', 'sa11y-i18n'),
  'FLAG_LINKS' => __('Flag links as an error', 'sa11y-i18n'),
  'VIDEO' => __('Video sources', 'sa11y-i18n'),
  'AUDIO' => __('Audio sources', 'sa11y-i18n'),
  'DATAVIZ' => __('Data visualization sources', 'sa11y-i18n'),
  'TURN_OFF' => __('Turn off Sa11y if these elements exist', 'sa11y-i18n'),
  'SHADOW' => __('Web components to check', 'sa11y-i18n'),
  'PROPS' => __('Add extra props', 'sa11y-i18n'),
  'EXPORT_RESULTS' => __('Export results', 'sa11y-i18n'),
]);

/* Setting descriptions */
define('SA11Y_DESC', [
  'ENABLE' => __('Enable for all administrators, editors, authors, contributors, and anyone who has permissions to edit posts and pages.', 'sa11y-i18n'),
  'PANEL_POSITION' => [
    'DESCRIPTION' => __('Display Sa11y\'s main panel on the left or right side.', 'sa11y-i18n'),
    'LEFT' => __('Left', 'sa11y-i18n'),
    'RIGHT' => __('Right', 'sa11y-i18n'),
  ],
  'CHECK_ROOT' => __('Input a <strong>single selector</strong> to target a specific region of your website. For example, use <code>main</code> to scan the main content region only.', 'sa11y-i18n'),
  'CHECK_ROOT_NETWORK' => __('<strong>Important ⚠️:</strong> This selector <strong>must</strong> be common to <strong>all</strong> themes.', 'sa11y-i18n'),
  'NETWORK_EXCLUSIONS' => __('Create global exclusions or ignore repetitive elements using CSS selectors. Use a comma to separate multiple selectors. View <a href="%s">CSS selectors reference.</a>', 'sa11y-i18n'),
  'EXCLUSIONS' =>  __('Create exclusions or ignore repetitive elements using CSS selectors. Use a comma to separate multiple selectors. View <a href="%s">CSS selectors reference.</a>', 'sa11y-i18n'),
  'NETWORK_CAN_OVERRIDE' => __('<strong>Note:</strong> Admins can override this setting.', 'sa11y-i18n'),
  'NETWORK_CANNOT_OVERRIDE' => __('<strong>Note:</strong> Admins <strong>cannot</strong> override this setting.', 'sa11y-i18n'),
  'NETWORK_OVERRIDE_SECTION' => __('<strong>Note:</strong> Admins can override the settings in this section.', 'sa11y-i18n'),
  'NETWORK_CANNOT_OVERRIDE_SECTION' => __('<strong>Note:</strong> Admins <strong>cannot</strong> override the settings in this section.', 'sa11y-i18n'),
  'CONTRAST' => __('Show Contrast toggle in Settings panel. Check for WCAG 2.0 Level AA contrast issues between foreground text and background elements.', 'sa11y-i18n'),
  'FORM_LABELS' => __('Show Form Labels toggle in Settings panel. Check for form inputs missing a corresponding label. Not necessarily a content author issue, and usually not an issue when using a reputable, accessible forms plugin.', 'sa11y-i18n'),
  'LINKS_ADVANCED' => __('Show Links (Advanced) toggle in Settings panel. Check for additional issues such as: links that open in a new tab without warning, have identical names but different purpose, or points to a PDF and other files without warning.', 'sa11y-i18n'),
  'COLOUR_FILTER' => __('Show Colour Filter toggle in Settings panel. Colour filters help identify potential color combinations that may be difficult for people to distinguish.', 'sa11y-i18n'),
  'ALL_CHECKS' => __('Enable to visually hide the toggle switches in the Settings panel.', 'sa11y-i18n'),
  'READABILITY' => __('Show Readability switch in the Settings panel. Check readability score of content based on Flesch reading-ease.', 'sa11y-i18n'),
  'READABILITY_TARGET' => __('Input a <strong>single selector</strong> to target a specific region of your website. For example, use <code>main</code> to scan the main content region only.', 'sa11y-i18n'),
  'READABILITY_IGNORE' => __('Exclude specific elements from the readability analysis. Only paragraph or list content is analyzed. Content within navigation elements are ignored by default.', 'sa11y-i18n'),
  'NETWORK_CONTAINER_IGNORE' => __('Ignore entire regions of a page. For example, <code>footer</code> to ignore the footer on all pages. The comments area (<code>#comments</code>) is ignored by default.', 'sa11y-i18n'),
  'CONTAINER_IGNORE' => __('Ignore entire regions of a page. For example, <code>#comments</code> to ignore the Comments section on all pages.', 'sa11y-i18n'),
  'CONTRAST_IGNORE' => __('Ignore specific elements from the contrast check. For example, <code>.sr-only</code> or classes that (intentionally) visually hide elements.', 'sa11y-i18n'),
  'OUTLINE_IGNORE' => __('Ignore specific headings from appearing in the "Show Outline" panel. For example, visually hidden headings that content authors do not see.', 'sa11y-i18n'),
  'HEADER_IGNORE' => __('Exclude specific headings from all checks.', 'sa11y-i18n'),
  'IMAGE_IGNORE' => __('Exclude specific images from all checks.', 'sa11y-i18n'),
  'LINK_IGNORE' => __('Exclude specific links from all checks.', 'sa11y-i18n'),
  'LINK_IGNORE_SPAN' => __('Ignore elements within a link to improve accuracy of link checks. For example, add <code>.sr-only</code> to ignore the text "external link" within the following anchor: <code>&lt;a href=&#34;#&#34;&gt;learn more <strong>&lt;span class=&#34;sr-only&#34;&gt;external link&lt;/span&gt;</strong>&lt;/a&gt;</code>', 'sa11y-i18n'),
  'LINK_TO_FLAG' => __('Flag absolute URLs that point to a development environment as an error. For example, all links that start with "dev": <code>a[href^="https://dev."]</code>', 'sa11y-i18n'),
  'EMBEDDED_CONTENT_DESCRIPTION' => __('Ensure the correct warning message is applied to all embedded content (iframes).', 'sa11y-i18n'),
  'VIDEO' => [
    'DESCRIPTION' => __('Ensure videos within iFrames display a relevant warning to provide accurate closed captions.', 'sa11y-i18n'),
    'SHOW_SOURCES' => __('View default video sources', 'sa11y-i18n'),
    'SOURCES' => 'youtube.com, vimeo.com, yuja.com, panopto.com',
  ],
  'AUDIO' => [
    'DESCRIPTION' => __('Ensure audio within iFrames display a relevant warning to provide a transcript.', 'sa11y-i18n'),
    'SHOW_SOURCES' => __('View default audio sources', 'sa11y-i18n'),
    'SOURCES' => 'soundcloud.com, simplecast.com, podbean.com, buzzsprout.com, blubrry.com, transistor.fm, fusebox.fm, libsyn.com',
  ],
  'DATA_VIZ' => [
    'DESCRIPTION' => __('Ensure data visualizations within iFrames display a relevant warning.', 'sa11y-i18n'),
    'SHOW_SOURCES' => __('View default data visualization sources', 'sa11y-i18n'),
    'SOURCES' => 'datastudio, tableau, lookerstudio, powerbi, qlik',
  ],
  'NO_RUN' => __('Provide a list of selectors that are <strong>unique to pages</strong>. If any of the elements exist on the page, Sa11y will not scan or appear.', 'sa11y-i18n'),
  'SHADOW' => __('Provide a list of all known web components or containers with an open shadow DOM.', 'sa11y-i18n'),
  'PROPS' => __('Pass additional (boolean or integer) properties to customize. Use a comma to seperate multiple key/value pairs. Refer to <a href="%s">documentation.</a>', 'sa11y-i18n'),
  'TIMESTAMP' => __('Settings last updated by %s on %s.', 'sa11y-i18n'),
  'SAVE' => __('Save Settings', 'sa11y-i18n'),
  'EXPORT_RESULTS_DESC' => __('Allow editors to export results as CSV or HTML.', 'sa11y-i18n')
]);
