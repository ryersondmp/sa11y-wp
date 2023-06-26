<div class="sa11y-intro">
  <h2>
    <?php
    $current_user = wp_get_current_user();
    $nickname = $current_user->nickname;
    esc_html_e("Hey $nickname,", 'sa11y-i18n');
    ?>
  </h2>
  <p>
    <?php
    global $allowHTML;
    $welcome = 'Sa11y is your accessibility quality assurance assistant. Sa11y works in <strong>Preview</strong> mode on all pages and posts. Use this settings page to customize the experience for website authors. Please note that Sa11y is not a comprehensive code analysis tool, nor will it make your website automatically accessible. You should make sure you are using an accessible theme.';
    echo wp_kses(__($welcome, 'sa11y-i18n'), $allowHTML);
    ?>
  </p>
  <p style="padding-top:8px">
    <?php
    $link = esc_url('https://sa11y.netlify.app/');
    $string = __('To learn more about Sa11y, please visit the <a href="%s">project website.</a>', 'sa11y-i18n');
    echo wp_kses(sprintf($string, $link), ['a' => ['href' => []]]);
    ?>
  </p>
</div>

<?php if (is_network_admin()) : ?>
  <div class="sa11y-intro">
    <h2><?php esc_html_e('Multi-site ready', 'sa11y-i18n'); ?></h2>
    <p><?php esc_html_e('Set custom defaults for your network of websites.', 'sa11y-i18n'); ?></p>
  </div>
<?php endif; ?>