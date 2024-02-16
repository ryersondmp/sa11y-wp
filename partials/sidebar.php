<div id="postbox-container-1" class="postbox-container sa11y-postbox">
  <div class="postbox">
    <h2 class="screen-reader-text"><?php esc_html_e('More', 'sa11y-i18n'); ?></h2>
    <div class="inside">
      <h3 class="postbox-heading">
        <?php esc_html_e('Admin guide', 'sa11y-i18n'); ?>
      </h3>
      <ul>
        <li>
          <?php esc_html_e('Specify the target area to check. Only check content your authors can edit.', 'sa11y-i18n'); ?>
        </li>
        <li>
          <?php esc_html_e('Turn off checks or features that are not relevant, including issues that cannot be fixed by content authors.', 'sa11y-i18n'); ?>
        </li>
        <li>
          <?php
          $link = 'https://www.w3schools.com/cssref/css_selectors.asp';
          $string = __('Create exclusions or ignore repetitive elements using CSS selectors. Use a comma to separate multiple selectors. View <a href="%s">CSS selectors reference.</a>', 'sa11y-i18n');
          echo wp_kses(sprintf($string, esc_url($link)), ['a' => ['href' => []]]);
          ?>
        </li>
      </ul>

      <h3 class="postbox-heading">
        <?php esc_html_e('Contribute', 'sa11y-i18n'); ?>
      </h3>
      <ul>
        <li class="sa11y-bug">
          <?php
          $link = 'https://forms.gle/sjzK9XykETaoqZv99';
          $string = __('Report a bug or leave feedback', 'sa11y-i18n');
          $print = sprintf('<a href="%s">%s</a>', esc_url($link), esc_html($string));
          echo wp_kses($print, ['a' => ['href' => []]]);
          ?>
        </li>
        <li class="sa11y-review">
          <?php
          $link = 'https://wordpress.org/support/plugin/sa11y/reviews/#new-post';
          $string = __('Write a review', 'sa11y-i18n');
          $print = sprintf('<a href="%s">%s</a>', esc_url($link), esc_html($string));
          echo wp_kses($print, ['a' => ['href' => []]]);
          ?>
        </li>
        <li class="sa11y-translate">
          <?php
          $link = 'https://github.com/ryersondmp/sa11y/blob/master/CONTRIBUTING.md';
          $string = __('Help translate or improve', 'sa11y-i18n');
          $print = sprintf('<a href="%s">%s</a>', esc_url($link), esc_html($string));
          echo wp_kses($print, ['a' => ['href' => []]]);
          ?>
        </li>
      </ul>

      <h3 class="postbox-heading">
        <?php esc_html_e('Version', 'sa11y-i18n'); ?>
      </h3>
      <ul>
        <li>
          <strong><?php esc_html_e('Sa11y', 'sa11y-i18n'); ?>:</strong>
          <?php echo Sa11y_WP::VERSION; ?>
        </li>
        <li>
          <strong><?php esc_html_e('Plugin', 'sa11y-i18n'); ?>:</strong>
          <?php echo Sa11y_WP::WP_VERSION; ?>
        </li>
      </ul>

      <h3 class="postbox-heading">
        <?php esc_html_e('Acknowledgements', 'sa11y-i18n'); ?>
      </h3>
      <p>
        <?php
        $link = 'https://sa11y.netlify.app/acknowledgements/';
        $string = __('Development led by Adam Chaboryk at Toronto Metropolitan University. View <a href="%s">all acknowledgements.</a>', 'sa11y-i18n');
        echo wp_kses(sprintf($string, esc_url($link)), ['a' => ['href' => []]]);
        ?>
      </p>
      <br>
      <p>
        <?php
        $year = date('Y');
        esc_html_e('© 2020 - ' . $year . ' Toronto Metropolitan University.', 'sa11y-i18n');
        ?>
      </p>
    </div>
  </div>
</div><!-- .postbox-container -->