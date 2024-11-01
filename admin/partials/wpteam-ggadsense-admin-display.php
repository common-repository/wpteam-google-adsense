<?php

/**
* Provide a admin area view for the plugin
*
* This file is used to markup the admin-facing aspects of the plugin.
*
* @link       http://wpteam.kr
* @since      1.0.0
*
* @package    WPteam_GoogleAdsense
* @subpackage WPteam_GoogleAdsense/admin/partials
*/

$active_section  = isset( $_GET['section'] ) ? $_GET['section'] : 'general';
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <h2 class="wpteam-ggadsense-nav-tab-wrapper nav-tab-wrapper">
    <a href="<?php echo admin_url( 'admin.php?page='.$this->plugin_name ); ?>"
      class="nav-tab<?php if ( !isset( $_GET['section'] ) || isset( $_GET['section'] ) && !in_array($_GET['section'], $this->action_list) ) echo ' nav-tab-active'; ?>"
      >
      <?php esc_html_e( 'General', 'wpteam-ggadsense' ); ?>
    </a>
    <a href="<?php echo esc_url( add_query_arg( array( 'section' => $this->post_slug ), admin_url( 'admin.php?page='.$this->plugin_name ) ) ); ?>"
      class="nav-tab<?php if ( strcmp($active_section,$this->post_slug) == 0 ) echo ' nav-tab-active'; ?>">
      <?php esc_html_e( 'Common Setup', 'wpteam-ggadsense' ); ?>
    </a>
    <?php if ($this->wc_active) :?>
      <a href="<?php echo esc_url( add_query_arg( array( 'section' => $this->wc_slug ), admin_url( 'admin.php?page='.$this->plugin_name ) ) ); ?>"
        class="nav-tab<?php if ( strcmp($active_section,$this->wc_slug) == 0 ) echo ' nav-tab-active'; ?>">
        <?php esc_html_e( 'WooCommerce Setup', 'wpteam-ggadsense' ); ?>
      </a>
    <?php endif; ?>
  </h2>
  <div class="wpteam-ggadsense-column-section">
    <div class="wpteam-ggadsense-column">
      <div class="wpteam-ggadsense-column-block">
        <form method="post" name="wpteam_site_setup" action="options.php">
          <?php if (strcmp($active_section,$this->post_slug) == 0) :?>
            <?php
            settings_fields("{$this->plugin_name}_{$this->post_slug}");
            do_settings_sections( "{$this->plugin_name}-{$this->post_slug}-tab" );
            submit_button();
            ?>
          <?php elseif ($this->wc_active && strcmp($active_section,$this->wc_slug) == 0) :?>
            <?php
            settings_fields("{$this->plugin_name}_{$this->wc_slug}");
            do_settings_sections( "{$this->plugin_name}-{$this->wc_slug}-tab" );
            submit_button();
            ?>
          <?php else : ?>
            <?php
            settings_fields($this->plugin_name);
            do_settings_sections( "{$this->plugin_name}" );
            submit_button();
            ?>
          <?php endif; ?>
        </form>
      </div>
    </div>
    <div class="wpteam-ggadsense-column wpteam-ggadsense-column-side">
      <div class="wpteam-ggadsense-dark-column-block">
        <?php if (strcmp($active_section,$this->post_slug) == 0 || strcmp($active_section,$this->wc_slug) == 0) :?>
          <img class="wpteam-ggadsense-img-400px" src="<?php echo esc_url(plugin_dir_url( __FILE__ ) . "../img/description_01.png");?>" alt="<?php _e("Google Adsense Ads script", 'wpteam-ggadsense' )?>"/><br/>
          <?php printf( __('Follow by the header script, you can copy ads script below <i>%s</i> to the end and paste to the wish position', 'wpteam-ggadsense' ),  esc_html(_x( "<!-- Ads name -->", "Comment Part of ads", 'wpteam-ggadsense'))) ?>
        <?php elseif (strcmp($active_section,'general') == 0): ?>
          <img class="wpteam-ggadsense-img-400px" src="<?php echo esc_url(plugin_dir_url( __FILE__ ) . "../img/description_00.png");?>" alt="<?php _e("Google Adsense Ads Header script", 'wpteam-ggadsense' )?>"/><br/>
          <?php _e('On Google Adsense Ads , click "Copy" then find the Header script to copy paste as image', 'wpteam-ggadsense' ) ?>
        <?php else : ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
