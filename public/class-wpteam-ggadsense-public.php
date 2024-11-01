<?php

/**
* The public-facing functionality of the plugin.
*
* @link       http://wpteam.kr
* @since      1.0.0
*
* @package    WPteam_GoogleAdsense
* @subpackage WPteam_GoogleAdsense/public
*/

/**
* The public-facing functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the public-facing stylesheet and JavaScript.
*
* @package    WPteam_GoogleAdsense
* @subpackage WPteam_GoogleAdsense/public
* @author     WPteam <mins9919@naver.com>
*/
class WPteam_GoogleAdsense_Public {

	/**
	* The ID of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $plugin_name    The ID of this plugin.
	*/
	private $plugin_name;

	/**
	* The version of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $version    The current version of this plugin.
	*/
	private $version;

	/**
	* The WooCommerce Plugin Active
	*
	* @since    1.0.0
	* @access   private
	* @var      boolean    $wc_options    The plugin WooCommerce's options
	*/
	private $wc_active = false;

	/**
	* The WooCommerce Currency slug of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $wc_slug    The plugin WooCommerce's slug
	*/
	private $wc_slug = 'setup-wc';

	/**
	* The Post slug of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $post_slug    The plugin WooCommerce's slug
	*/
	private $post_slug = 'setup-basic-ads';

	/**
	* Initialize the class and set its properties.
	*
	* @since    1.0.0
	* @param    string    $plugin_name		The name of this plugin.
	* @param    string    $version    		The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->wc_active = $this->check_wc();
	}

	/**
	* Check WooCommerce Active
	*
	* @since    1.0.0
	*/
	public function check_wc() {
		return ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) );
	}

	/**
	* Register the stylesheets for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpteam-ggadsense-public.css', array(), $this->version, 'all' );
	}

	/**
	* Register the JavaScript for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpteam-ggadsense-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	* Register Google Adsense Javascript.
	*
	* @since    1.0.0
	*/
	public function ggadsense_header() {
		$options = get_option( "{$this->plugin_name}" );
		if(!empty($options['js_header'])){
			echo $options['js_header'];
		}
	}

	/**
	* Register Google Adsense Javascript.
	*
	* @since    1.0.0
	*/
	public function the_content_filter($content) {
		$options = get_option( "{$this->plugin_name}" );
		if(!empty($options['js_header'])) {
			$post_options = get_option( "{$this->plugin_name}_{$this->post_slug}" );
			if(!empty($post_options)) {
				if($post_options['common_enable']) {
					$content = $post_options['js_common_before_script'] . $content . $post_options['js_common_after_script'];
				} else {
					if(is_singular( 'post' ) && $post_options['post_enable']){
						$content = $post_options['js_post_before_script'] . $content . $post_options['js_post_after_script'];
					}
					if($this->wc_active) {
						$product_options = get_option( "{$this->plugin_name}_{$this->wc_slug}" );
						/* ---- WooCommerce Setup ---- */
						if(!empty($product_options)){
							if(is_singular( 'product' ) && $product_options['product_enable']){
								$content = $product_options['js_product_before_script'] . $content . $product_options['js_product_after_script'];
							}
							if(is_singular( 'page' )) {
								if(is_cart() && $product_options['cart_enable']) {
									$content = $product_options['js_cart_before_script'] . $content . $product_options['js_cart_after_script'];
								} else if(is_checkout() && $product_options['checkout_enable']) {
									$content = $product_options['js_checkout_before_script'] . $content . $product_options['js_checkout_after_script'];
								} else if($post_options['page_enable']) {
									$content = $post_options['js_page_before_script'] . $content . $post_options['js_page_after_script'];
								}
							}
						}
					} else {
						if(is_singular( 'page' ) && $post_options['page_enable']){
							if( is_home() && $post_options['home_enable']) {
								$content = $post_options['js_page_before_script'] . $content . $post_options['js_page_after_script'];
							} else {
								$content = $post_options['js_page_before_script'] . $content . $post_options['js_page_after_script'];
							}
						}
					}
				}
			}
		}
		return $content;
	}


}
