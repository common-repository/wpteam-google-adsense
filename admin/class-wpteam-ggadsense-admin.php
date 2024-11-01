<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       http://wpteam.kr
* @since      1.0.0
*
* @package    WPteam_GoogleAdsense
* @subpackage WPteam_GoogleAdsense/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    WPteam_GoogleAdsense
* @subpackage WPteam_GoogleAdsense/admin
* @author     WPteam <mins9919@naver.com>
*/
class WPteam_GoogleAdsense_Admin {

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
	* The All Action list of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $action_list    The plugin action's name
	*/
	private $action_list;

	/**
	* The Hook slug of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $post_slug    The plugin Hook's slug
	*/
	private $post_slug = 'setup-basic-ads';

	/**
	* The Hook options of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      array    $post_options    The plugin Hook's options
	*/
	private $post_options;

	/**
	* The Hook fields of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      array    $post_fields    The plugin Hook's options
	*/
	private $post_fields;

	/**
	* The WooCommerce slug of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $wc_slug    The plugin WooCommerce's slug
	*/
	private $wc_slug = 'setup-wc';

	/**
	* The WooCommerce options of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      array    $wc_options    The plugin WooCommerce's options
	*/
	private $wc_options;

	/**
	* The WooCommerce fields of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      array    $wc_fields    The plugin WooCommerce's options
	*/
	private $wc_fields;

	/**
	* The WooCommerce Plugin Active
	*
	* @since    1.0.0
	* @access   private
	* @var      boolean    $wc_options    The plugin WooCommerce's options
	*/
	private $wc_active = false;

	/**
	* The Options Setup of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      array    $options_setup    The plugin Hook's slug
	*/
	private $options_setup = array(
		'section_description' => array()
	);

	/**
	* Initialize the class and set its properties.
	*
	* @since    1.0.0
	* @param    string    $plugin_name		The name of this plugin.
	* @param    string    $version    		The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->action_list  = array($this->wc_slug, $this->post_slug);

		/* ---- Base Setup ---- */
		$this->base_options = get_option($this->plugin_name);
		$this->base_fields  = $this->add_fields_helper($this->plugin_name);

		/* ---- Hooks Setup ---- */
		$this->post_options = get_option("{$this->plugin_name}_{$this->post_slug}");
		$this->post_fields  = $this->add_fields_helper($this->post_slug);

		$this->wc_active = $this->check_wc();
		if($this->wc_active) {
			/* ---- WooCommerce Setup ---- */
			$this->wc_options = get_option("{$this->plugin_name}_{$this->wc_slug}");
			$this->wc_fields = $this->add_fields_helper($this->wc_slug);
		}

		$this->setup_fields_title();
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
	* Dashboard Options setup Helper
	*
	* @since    1.0.0
	*/
	public function add_fields_helper($key) {
		$setup_locale = get_locale();
		$setup_reader = json_decode(file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wpteam-ggadsense-setup.json'), true);
		return (!empty($setup_reader) && array_key_exists($key,$setup_reader))? $setup_reader[$key]: null;
	}

	/**
	* Dashboard Options setup Helper
	*
	* @since    1.0.0
	*/
	public function add_options_helper($key, $value, $sep = ',') {
		$value = (is_array($value)) ? $value : explode($sep,$value);
		if(array_key_exists($key, $this->options_setup)) {
			$this->options_setup[$key] = array_merge($this->options_setup[$key], $value);
		} else {
			$this->options_setup[$key] = $value;
		}
	}

	/**
	* Final Field Title with translate support
	*
	* @since    1.0.0
	*/
	public function setup_fields_title() {
		$this->add_options_helper($this->plugin_name, array(
			'page'     => $this->plugin_name,
			'sections' => array(
				0         => array(
					'id'     => "{$this->plugin_name}_section",
					'title'  => __( 'Google Adsense Header', 'wpteam-ggadsense')
				)
			)
		));
		$this->add_options_helper('section_description', array( "{$this->plugin_name}_section" => __( 'Google Adsense Common Header Script', 'wpteam-ggadsense') ));

		$this->add_options_helper($this->post_slug, array(
			'page'     => "{$this->plugin_name}-{$this->post_slug}-tab",
			'sections' => array(
				0         => array(
					'id'     => "{$this->plugin_name}_{$this->post_slug}_section",
					'title'  => __( 'Common Settings' , 'wpteam-ggadsense')
				),
				1         => array(
					'id'     => "{$this->plugin_name}_{$this->post_slug}_post_section",
					'title'  => __( 'Post Settings' , 'wpteam-ggadsense')
				),
				2         => array(
					'id'     => "{$this->plugin_name}_{$this->post_slug}_page_section",
					'title'  => __( 'Page Settings' , 'wpteam-ggadsense')
				)
			)
		));
		$this->add_options_helper('section_description', array(
			"{$this->plugin_name}_{$this->post_slug}_section"      => __( 'Common Settings Description' , 'wpteam-ggadsense') ,
			"{$this->plugin_name}_{$this->post_slug}_post_section" => __( 'Post Settings Description' , 'wpteam-ggadsense'),
			"{$this->plugin_name}_{$this->post_slug}_page_section" => __( 'Page Settings Description' , 'wpteam-ggadsense')
		));
		if($this->wc_active){
			$this->add_options_helper($this->wc_slug, array(
				'page'     => "{$this->plugin_name}-{$this->wc_slug}-tab",
				'sections' => array(
					0         => array(
						'id'     => "{$this->plugin_name}_{$this->wc_slug}_section",
						'title'  => __( 'Product Settings' , 'wpteam-ggadsense')
					),
					1         => array(
						'id'     => "{$this->plugin_name}_{$this->wc_slug}_cart_section",
						'title'  => __( 'Cart Settings' , 'wpteam-ggadsense')
					),
					2         => array(
						'id'     => "{$this->plugin_name}_{$this->wc_slug}_checkout_section",
						'title'  => __( 'Checkout Settings' , 'wpteam-ggadsense')
					)
				)
			));
			$this->add_options_helper('section_description', array(
				"{$this->plugin_name}_{$this->wc_slug}_section"          => __( 'Product Settings Description' , 'wpteam-ggadsense'),
				"{$this->plugin_name}_{$this->wc_slug}_cart_section"     => __( 'Cart Settings Description' , 'wpteam-ggadsense'),
				"{$this->plugin_name}_{$this->wc_slug}_checkout_section" => __( 'Checkout Settings Description' , 'wpteam-ggadsense')
			));
		}

		$this->base_fields[0]['js_header']['title']                = __('Google Adsense Header Script', 'wpteam-ggadsense');

		$this->post_fields[0]['common_enable']['title']            = sprintf( __('Use <i>%s</i>', 'wpteam-ggadsense'), __('Common Ads', 'wpteam-ggadsense'));
		$this->post_fields[0]['common_enable']['args']['label']    = __('Enable ads', 'wpteam-ggadsense');
		$this->post_fields[0]['js_common_before_script']['title']  = sprintf( __('Ads Before Content of %s', 'wpteam-ggadsense'), __('Common Ads', 'wpteam-ggadsense'));
		$this->post_fields[0]['js_common_after_script']['title']   = sprintf( __('Ads After Content of %s', 'wpteam-ggadsense'), __('Common Ads', 'wpteam-ggadsense'));
		$this->post_fields[1]['post_enable']['title']              = sprintf( __('Use <i>%s</i>', 'wpteam-ggadsense'), __('Post Ads', 'wpteam-ggadsense'));
		$this->post_fields[1]['post_enable']['args']['label']      = __('Enable ads', 'wpteam-ggadsense');
		$this->post_fields[1]['js_post_before_script']['title']    = sprintf( __('Ads Before Content of %s', 'wpteam-ggadsense'), __('Post Ads', 'wpteam-ggadsense'));
		$this->post_fields[1]['js_post_after_script']['title']     = sprintf( __('Ads After Content of %s', 'wpteam-ggadsense'), __('Post Ads', 'wpteam-ggadsense'));
		$this->post_fields[2]['page_enable']['title']              = sprintf( __('Use <i>%s</i>', 'wpteam-ggadsense'), __('Page Ads', 'wpteam-ggadsense'));
		$this->post_fields[2]['page_enable']['args']['label']      = __('Enable ads', 'wpteam-ggadsense');
		$this->post_fields[2]['home_enable']['title']              = sprintf( __('Use <i>%s</i> on Home Page', 'wpteam-ggadsense'), __('Page Ads', 'wpteam-ggadsense'));
		$this->post_fields[2]['home_enable']['args']['label']      = __('Enable ads', 'wpteam-ggadsense');
		$this->post_fields[2]['js_page_before_script']['title']    = sprintf( __('Ads Before Content of %s', 'wpteam-ggadsense'), __('Common Ads', 'wpteam-ggadsense'));
		$this->post_fields[2]['js_page_after_script']['title']     = sprintf( __('Ads After Content of %s', 'wpteam-ggadsense'), __('Common Ads', 'wpteam-ggadsense'));

		if($this->wc_active){
			$this->wc_fields[0]['product_enable']['title']            = sprintf( __('Use <i>%s</i>', 'wpteam-ggadsense'), __('Product Ads', 'wpteam-ggadsense'));
			$this->wc_fields[0]['product_enable']['args']['label']    = __('Enable ads', 'wpteam-ggadsense');
			$this->wc_fields[0]['js_product_before_script']['title']  = sprintf( __('Ads Before Content of %s', 'wpteam-ggadsense'), __('Product Ads', 'wpteam-ggadsense'));
			$this->wc_fields[0]['js_product_after_script']['title']   = sprintf( __('Ads After Content of %s', 'wpteam-ggadsense'), __('Product Ads', 'wpteam-ggadsense'));
			$this->wc_fields[1]['cart_enable']['title']               = sprintf( __('Use <i>%s</i>', 'wpteam-ggadsense'), __('Cart Ads', 'wpteam-ggadsense'));
			$this->wc_fields[1]['cart_enable']['args']['label']       = __('Enable ads', 'wpteam-ggadsense');
			$this->wc_fields[1]['js_cart_before_script']['title']     = sprintf( __('Ads Before Content of %s', 'wpteam-ggadsense'), __('Cart Ads', 'wpteam-ggadsense'));
			$this->wc_fields[1]['js_cart_after_script']['title']      = sprintf( __('Ads After Content of %s', 'wpteam-ggadsense'), __('Cart Ads', 'wpteam-ggadsense'));
			$this->wc_fields[2]['checkout_enable']['title']           = sprintf( __('Use <i>%s</i>', 'wpteam-ggadsense'), __('Checkout Ads', 'wpteam-ggadsense'));
			$this->wc_fields[2]['checkout_enable']['args']['label']   = __('Enable ads', 'wpteam-ggadsense');
			$this->wc_fields[2]['js_checkout_before_script']['title'] = sprintf( __('Ads Before Content of %s', 'wpteam-ggadsense'), __('Checkout Ads', 'wpteam-ggadsense'));
			$this->wc_fields[2]['js_checkout_after_script']['title']  = sprintf( __('Ads After Content of %s', 'wpteam-ggadsense'), __('Checkout Ads', 'wpteam-ggadsense'));
		}
	}

	/**
	* Register the stylesheets for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpteam-ggadsense-admin.css', array(), $this->version, 'all' );
	}

	/**
	* Register the JavaScript for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpteam-ggadsense-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	* Add an options page under the Settings submenu
	*
	* @since  1.0.0
	*/
	public function add_options_page() {
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'WPteam Google Adsense Settings', 'wpteam-ggadsense' ), _x( 'WPteam Google Adsense', 'Plugin Menu', 'wpteam-ggadsense' ),
			'manage_options', $this->plugin_name, array( $this, 'display_options_page'), 'dashicons-megaphone'
		);
	}

	/**
	* Render the options page for plugin
	*
	* @since  1.0.0
	*/
	public function display_options_page() {
		include_once 'partials/wpteam-ggadsense-admin-display.php';
	}

	/**
	* Section setup Helper
	*
	* @since    1.0.0
	*/
	public function setup_section_helper($slug, $fields = array(), $options = array()) {
		if(!empty($slug) && array_key_exists($slug, $this->options_setup)){
			$field_name = (strcmp($slug, $this->plugin_name) == 0) ? "{$this->plugin_name}" : "{$this->plugin_name}_{$slug}";
			foreach ($this->options_setup[$slug]['sections'] as $section_key => $section_value) {
				add_settings_section(
					$section_value['id'],
					$section_value['title'],
					array( $this, 'print_section_info' ),
					$this->options_setup[$slug]['page']
				);
				if(is_array($fields[$section_key])) {
					foreach ($fields[$section_key] as $field_key => $field_value) {

						add_settings_field(
							"{$field_name}_{$field_key}",
							$field_value['title'],
							array( $this, $field_value['callback'] ),
							$this->options_setup[$slug]['page'],
							$section_value['id'],
							array_merge( array(
								'label_for' => "{$field_name}_{$field_key}",
								'name'      => "{$field_name}[{$field_key}]",
								'id'        => "{$field_name}_{$field_key}",
								'value'     => ($options && isset($options[$field_key]))? $options[$field_key] : ((preg_match('#^checkbox#i', $field_value['callback']) == 1)? false : '')
							), (isset($field_value['args']) && is_array($field_value['args']))?  $field_value['args']: array())
						);
					}
				}
			}
		}
	}

	/**
	* WooCommerce Options Cleaner
	*
	* @since  1.0.0
	*/
	public function clean_empty_text_options($input) {
		foreach( $input as $key => $value){
			if(empty($value)) {
				unset($input[$key]);
			}
			// if(preg_match('#^js#i',$key) && (strlen($value) == 0)) {
			// 	unset($input[$key]);
			// }
		}
		return empty($input)? false:$input;
	}

	/**
	* Update Option
	*
	* @since  1.0.0
	*/
	public function options_init() {
		/* ---- Translate Helper ---- */
		$this->setup_fields_title();

		register_setting($this->plugin_name, $this->plugin_name, array('sanitize_callback' => array($this, 'clean_empty_text_options')));
		$this->setup_section_helper($this->plugin_name, $this->base_fields, $this->base_options);

		/* ---- Hook Options ---- */
		register_setting("{$this->plugin_name}_{$this->post_slug}", "{$this->plugin_name}_{$this->post_slug}", array('sanitize_callback' => array($this, 'clean_empty_text_options')));
		$this->setup_section_helper($this->post_slug, $this->post_fields, $this->post_options);

		if($this->wc_active) {
			/* ---- WooCommerce Options ---- */
			register_setting("{$this->plugin_name}_{$this->wc_slug}", "{$this->plugin_name}_{$this->wc_slug}", array('sanitize_callback' => array($this, 'clean_empty_text_options')));
			$this->setup_section_helper($this->wc_slug, $this->wc_fields, $this->wc_options);
		}
	}

	/**
	* Check box setting field Callback
	*
	* @since  1.0.0
	*/
	function print_section_info( $arg ) {
		echo "<i>{$this->options_setup['section_description'][$arg['id']]}</i>";
	}

	/**
	* Check box setting field Callback
	*
	* @since  1.0.0
	*/
	public function checkbok_callback( $args ) {
		echo '<input name="'.$args['name'].'" id="'.$args['id'].'" type="checkbox" value="1" class="code" '
		.checked( 1, $args['value'], false ).((isset($args['disabled']) && $args['disabled'] == true)? ' disabled':'' ). ' /> '.$args['label'];
	}


	/**
	* Input setting field Callback
	*
	* @since  1.0.0
	*/
	public function text_callback( $args ) {
		echo '<input name="'.$args['name'].'" id="'.$args['id'].'" type="text" value="'.$args['value'].'" class="code" placeholder="'.$args['placeholder'].'"'
		.((isset($args['disabled']) && $args['disabled'] == true)? ' disabled':'' ). ' /> ';
	}

	/**
	* Number setting field Callback
	*
	* @since  1.0.0
	*/
	public function number_callback( $args ) {
		echo '<input name="'.$args['name'].'" id="'.$args['id'].'" type="number" value="'.$args['value'].'" class="code" placeholder="'.$args['placeholder'].'"'
		.'min="'.$args['min'].'"'.'step="'.$args['step'].'"'.((isset($args['disabled']) && $args['disabled'] == true)? ' disabled':'' ). ' /> ';
	}

	/**
	* Number setting field Callback
	*
	* @since  1.0.0
	*/
	public function textarea_callback( $args ) {
		echo '<textarea name="'.$args['name'].'" id="'.$args['id'].'" class="code" '. (isset($args['placeholder'])? 'placeholder="'.$args['placeholder'].'"': '')
		.((isset($args['disabled']) && $args['disabled'] == true)? ' disabled':'' ). ' rows="3">'.esc_html($args['value']).'</textarea>';
	}

}
