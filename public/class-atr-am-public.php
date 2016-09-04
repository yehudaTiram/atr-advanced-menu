<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.atarimtr.com/
 * @since      1.0.0
 *
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/public
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Advanced_Menu_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
    public $_menu_template_token;

    /**
     * The menu templates URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */	 
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->_menu_template_token = 'ATR_All_Menu_template';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Atr_Advanced_Menu_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Atr_Advanced_Menu_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/atr-am-public.css', array(), $this->version, 'all' );

        $menu_css_file = 'megamenu';
        if ((get_option('atr_all_menu_css_file_to_use')) && (get_option('atr_all_menu_css_file_to_use') != $menu_css_file) ) {
            $menu_css_file = get_option('atr_all_menu_css_file_to_use');
            $home_path =  home_url( '/' );
            wp_register_style($this->_menu_template_token . '-css', $home_path . 'wp-content/uploads/atr-advanced-menu/' . $menu_css_file . '.css', array(), $this->_version);
            
        }
        else{
            wp_register_style($this->_menu_template_token . '-css', plugin_dir_url( __FILE__ ) . 'menu-templates/' . $menu_css_file . '.css', array(), $this->_version);
        }
        
        wp_enqueue_style($this->_menu_template_token . '-css');

        //$icon_font_file = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css';
		 if ( ! get_option('atr_all_menu_icon_font_from_elsewhere')) {
			if (get_option('atr_all_menu_load_icon_font')) {
				$icon_font_file = get_option('atr_all_menu_load_icon_font');
				wp_register_style($this->_menu_template_token . '-icon_font', $icon_font_file, array(), $this->_version);
				wp_enqueue_style($this->_menu_template_token . '-icon_font');				
			}			 
		 }		

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Atr_Advanced_Menu_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Atr_Advanced_Menu_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/atr-am-public.js', array( 'jquery' ), $this->version, false );
		
		
        $do_not_load_js = get_option('atr_all_menu_do_not_load_js');
        if (!$do_not_load_js == 'on') {
			$style_edit_mode = get_option('atr_all_menu_style_edit_mode');
			// Check if we want to edit the styles and make the dropdown panels fixed
			if ($style_edit_mode == 'on'){
				$menu_js_file = 'jquery-accessibleMegaMenuStyleEdit';
			}
			else{
				$menu_js_file = 'jquery-accessibleMegaMenu';
			}
			
			// Future option - Let the admin select different js file.
            // if (get_option('atr_all_menu_js_file_to_use'))
                // $menu_js_file = get_option('atr_all_menu_js_file_to_use');
            wp_register_script($this->_menu_template_token . '-js', plugin_dir_url( __FILE__ ) . 'menu-templates/' . $menu_js_file . '.js', array('jquery'), $this->_version);
            wp_enqueue_script($this->_menu_template_token . '-js');
        }
        $do_notload__initjs_file = get_option('atr_all_menu_do_not_load_initjs');
        if (!$do_notload__initjs_file == 'on') {
            wp_register_script($this->_menu_template_token . '-init-js', plugin_dir_url( __FILE__ ) . 'menu-templates/init.js', array('jquery'), $this->_version);
            wp_enqueue_script($this->_menu_template_token . '-init-js');
        }		

	}

}
