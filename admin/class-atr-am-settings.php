<?php

/**
 * The settings of the plugin.
 *
 * @link       http://www.atarimtr.com/
 * @since      1.0.0
 *
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/admin
 */

/**
 * Class Atr_Advanced_Menu_Admin_Settings
 *
 */
class Atr_Advanced_Menu_Admin_Settings {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * This function introduces the plugin options into the 'Appearance' menu and into a top-level
	 * 'Atr Advanced Menu' menu.
	 */
	public function setup_plugin_options_menu() {

		//Add the menu to the Plugins set of menu items
		add_submenu_page( 'themes.php', // The slug for this menu parent item
			'Atr Advanced Menu Options', // The title to be displayed in the browser window for this page.
			'Atr Advanced Menu Options',// The text to be displayed for this menu item
			'manage_options', // Which type of users can see this menu item
			'atr_advanced_menu',// The unique ID - that is, the slug - for this menu item
			array( $this, 'render_settings_page_content'));// The name of the function to call when rendering this menu's page
			
		// add_plugins_page(
			// 'Atr Advanced Menu Options', 					// The title to be displayed in the browser window for this page.
			// 'Atr Advanced Menu Options',					// The text to be displayed for this menu item
			// 'manage_options',					// Which type of users can see this menu item
			// 'atr_advanced_menu',			// The unique ID - that is, the slug - for this menu item
			// array( $this, 'render_settings_page_content')				// The name of the function to call when rendering this menu's page
		// );		
	}
	

	/**
	 * Provides default values for the Display Options.
	 *
	 * @return array
	 */
	public function default_display_options() {

		$defaults = array(
			'panel_default_class'		=>	'atr-am',
			'css_class_prefix'		=>	'atr-am',
			'load_icon_font'		=>	'',
		);

		return $defaults;

	}



	/**
	 * Renders a simple page to display for the plugin menu defined above.
	 */
	public function render_settings_page_content( $active_tab = '' ) {
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e( 'Atr Advanced Menu Options', 'atr-advanced-menu' ); ?></h2>
			<?php settings_errors(); ?>

			<?php if( isset( $_GET[ 'tab' ] ) ) {
				$active_tab = $_GET[ 'tab' ];
			}  else {
				$active_tab = 'display_options';
			} // end if/else ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=atr_advanced_menu_options&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Display Options', 'atr-advanced-menu' ); ?></a>
			</h2>

			<form method="post" action="options.php">
				<?php

				if( $active_tab == 'display_options' ) {

					settings_fields( 'atr_advanced_menu_display_options' );
					do_settings_sections( 'atr_advanced_menu_display_options' );

				}  // end if/else

				submit_button();

				?>
			</form>

		</div><!-- /.wrap -->
	<?php
	}

	/**
	 * This function provides a simple description for the General Options page.
	 *
	 * in the add_settings_section function.
	 */
	public function general_options_callback() {
		$options = get_option('atr_advanced_menu_display_options');
		//var_dump($options);
		echo '<p>' . __( 'You can, among other things, customize your menu classes and icon font from here.', 'atr-advanced-menu' ) . '</p>';
	} // end general_options_callback


	/**
	 * Initializes the plugin's display options page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_display_options() {

		// If the plugin options don't exist, create them.
		if( false == get_option( 'atr_advanced_menu_display_options' ) ) {
			$default_array = $this->default_display_options();
			add_option( 'atr_advanced_menu_display_options', $default_array );
		}


		add_settings_section(
			'general_settings_section',			            // ID used to identify this section and with which to register options
			__( 'Display Options', 'atr-advanced-menu' ),		        // Title to be displayed on the administration page
			array( $this, 'general_options_callback'),	    // Callback used to render the description of the section
			'atr_advanced_menu_display_options'		                // Page on which to add this section of options
		);
		
		add_settings_field(
			'css_file_to_use',
			__( 'Alternate CSS file url', 'atr-advanced-menu' ),
			array( $this, 'css_file_to_use_callback'),
			'atr_advanced_menu_display_options',
			'general_settings_section',			        // The name of the section to which this field belongs
			array(								        // The array of arguments to pass to the callback. In this case, just a description.
				__( '<br /><span class="atr-inline-optional"><strong>Optional</strong></span> - Leave empty in order to use the plugin’s default "megamenu.css" file (stored in /wp-content/plugins/atr-advanced-menu/public/menu-templates/megamenu.css).<br />Or, if you want to use your own CSS file for the menu:<br />1. Create a css file with the styles you want.<br />2. Write here in the textbox the new file URL.<br /><span class="atr-inline-red"><strong>3. Important:</strong></span> Your CSS file must include some basic classes for the menu to work properly. See in the plugin’s "megamenu.css" file what is mandatory for the menu to work.', 'atr-advanced-menu' ),
			)
		); 
		
		add_settings_field(
			'do_not_load_css',
			__( 'Do not load any CSS file', 'atr-advanced-menu' ),
			array( $this, 'do_not_load_css_callback'),
			'atr_advanced_menu_display_options',
			'general_settings_section',			        // The name of the section to which this field belongs
			array(								        // The array of arguments to pass to the callback. In this case, just a description.
				__( '<br /><span class="atr-inline-optional"><strong>Optional</strong></span> - Check this if you included all the css classes needed for the menu in some other file like style.css which is loaded elsewhere.<br />This CSS file <span class="atr-inline-red"><strong>must</strong></span> include some basic classes for the menu to work properly. See in the plugin’s "megamenu.css" file what is mandatory for the menu to work.', 'atr-advanced-menu' ),
			)
		); 
		
		add_settings_field(
			'load_icon_font',
			__( 'Icon font URL', 'atr-advanced-menu' ),
			array( $this, 'load_icon_font_callback'),
			'atr_advanced_menu_display_options',
			'general_settings_section',			        // The name of the section to which this field belongs
			array(								        // The array of arguments to pass to the callback. In this case, just a description.
				__( '<span class="atr-inline-optional"><strong>Optional</strong></span> - The path to the icon font css.<br />If you want to use icon font for icons in the menu (like Font Awesome and others), you can load it here. You can experiment or use https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css.<br /><span class="atr-inline-red"><strong>Note:</span> </strong>If you load the icon font in the theme (or anywhere else), make sure to check the next option in order to load this plugin\'s font only in edit mode and use it only for the backend preview (Your theme will load it for the front end).', 'atr-advanced-menu' ),
			)
		); 		

		add_settings_field(
			'icon_font_from_elsewhere',						        // ID used to identify the field throughout the plugin
			__( 'I load icon fonts for front end elsewhere', 'atr-advanced-menu' ),		// The label to the left of the option interface element
			array( $this, 'icon_font_from_elsewhere_callback'),	// The name of the function responsible for rendering the option interface
			'atr_advanced_menu_display_options',	    // The page on which this option will be displayed
			'general_settings_section',			        // The name of the section to which this field belongs
			array(								        // The array of arguments to pass to the callback. In this case, just a description.
				__( 'Check this if you load icon fonts elsewhere (like from theme). The font will be loaded only for the backend preview.', 'atr-advanced-menu' ),
			)
		);

		add_settings_field(
			'panel_default_class',
			__( 'Menu panel default css class', 'atr-advanced-menu' ),
			array( $this, 'panel_default_class_callback'),
			'atr_advanced_menu_display_options',
			'general_settings_section',			        // The name of the section to which this field belongs
			array(								        // The array of arguments to pass to the callback. In this case, just a description.
				__( 'Write the default class for the Mega Menu panels.<br />This class will be added to all the drop down panel elements.<br />i.e. if you write here a class named "cols-4" and in the menu editor in the top item\'s "Panel class" field write class name "products-panel", the final class set of the panel will be "cols-4 products-panel accessible-megamenu-panel" (accessible-megamenu-panel class is mandatory).<br />This option gives you the ability to control the style of all panels while the "Panel class" field in the menu editor gives you control of single panel.', 'atr-advanced-menu' ),
			)
		); 		
		
		add_settings_field(
			'css_class_prefix',
			__( 'Menu items css class prefix', 'atr-advanced-menu' ),
			array( $this, 'css_class_prefix_callback'),
			'atr_advanced_menu_display_options',
			'general_settings_section',			        // The name of the section to which this field belongs
			array(								        // The array of arguments to pass to the callback. In this case, just a description.
				__( 'Write the css class prefix for the menu items.<br />The prefix is needed to single out your classes from optional conflicts. If you will not write a css class prefix here, the default theme menu classes (if exist) might be used.', 'atr-advanced-menu' ),
			)
		); 		
		
		add_settings_field(
			'style_edit_mode',
			__( 'Display in style edit mode', 'atr-advanced-menu' ),
			array( $this, 'style_edit_mode_callback'),
			'atr_advanced_menu_display_options',
			'general_settings_section',			        // The name of the section to which this field belongs
			array(								        // The array of arguments to pass to the callback. In this case, just a description.
				__( '<span class="atr-inline-red"><strong>For development only:</strong></span> Check this if you want the drop down panel of the menu to stay open after the mouse is out of the top level items.<br />Good for editing the CSS of the menu.', 'atr-advanced-menu' ),
			)
		); 			
		
		


		// Finally, we register the fields with WordPress
		register_setting(
			'atr_advanced_menu_display_options',
			'atr_advanced_menu_display_options'
		);

	} // end initialize_display_options



	
	/**
	 * This function renders the icon_font_from_elsewhere checkbox.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function icon_font_from_elsewhere_callback($args) {

		// First, we read the options collection
		$options = get_option('atr_advanced_menu_display_options');

		// Next, we update the name attribute to access this element's ID in the context of the display options array
		// We also access the show_header element of the options collection in the call to the checked() helper function
		$html = '<input type="checkbox" id="icon_font_from_elsewhere" name="atr_advanced_menu_display_options[icon_font_from_elsewhere]" value="1" ' . checked( 1, isset( $options['icon_font_from_elsewhere'] ) ? $options['icon_font_from_elsewhere'] : 0, false ) . '/>';

		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="icon_font_from_elsewhere">&nbsp;'  . $args[0] . '</label>';

		echo $html;

	} // end icon_font_from_elsewhere_callback	
	
	/**
	 * This function renders the style_edit_mode checkbox.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function style_edit_mode_callback($args) {

		// First, we read the options collection
		$options = get_option('atr_advanced_menu_display_options');

		// Next, we update the name attribute to access this element's ID in the context of the display options array
		// We also access the show_header element of the options collection in the call to the checked() helper function
		$html = '<input type="checkbox" id="style_edit_mode" name="atr_advanced_menu_display_options[style_edit_mode]" value="1" ' . checked( 1, isset( $options['style_edit_mode'] ) ? $options['style_edit_mode'] : 0, false ) . '/>';

		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="style_edit_mode">&nbsp;'  . $args[0] . '</label>';

		echo $html;

	} // end style_edit_mode_callback	
	
	
	/**
	 * This renders the text field for css_file_to_use.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function css_file_to_use_callback($args) {

		$options = get_option( 'atr_advanced_menu_display_options' );
		
		// Render the output
		echo '<input type="text" id="css_file_to_use" name="atr_advanced_menu_display_options[css_file_to_use]" value="' . $options['css_file_to_use'] . '" />' . '<label for="show_header">&nbsp;'  . $args[0] . '</label>';

	} // end css_file_to_use_callback
	
	/**
	 * This function renders the do_not_load_css checkbox.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function do_not_load_css_callback($args) {

		// First, we read the options collection
		$options = get_option('atr_advanced_menu_display_options');

		$html = '<input type="checkbox" id="do_not_load_css" name="atr_advanced_menu_display_options[do_not_load_css]" value="1" ' . checked( 1, isset( $options['do_not_load_css'] ) ? $options['do_not_load_css'] : 0, false ) . '/>';

		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="do_not_load_css">&nbsp;'  . $args[0] . '</label>';

		echo $html;

	} // end do_not_load_css_callback	
	
	/**
	 * This renders the text field for load_icon_font.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function load_icon_font_callback($args) {

		$options = get_option( 'atr_advanced_menu_display_options' );
		
		// Render the output
		echo '<input type="text" id="load_icon_font" name="atr_advanced_menu_display_options[load_icon_font]" value="' . $options['load_icon_font'] . '" />' . '<label for="load_icon_font">&nbsp;'  . $args[0] . '</label>';

	} // end load_icon_font_callback	
	
	
	/**
	 * This renders the text field for panel_default_class.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function panel_default_class_callback($args) {

		$options = get_option( 'atr_advanced_menu_display_options' );
		
		// Render the output
		echo '<input type="text" id="panel_default_class" name="atr_advanced_menu_display_options[panel_default_class]" value="' . $options['panel_default_class'] . '" />' . '<label for="panel_default_class">&nbsp;'  . $args[0] . '</label>';

	} // end panel_default_class_callback

	/**
	 * This renders the text field for css_class_prefix.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function css_class_prefix_callback($args) {

		$options = get_option( 'atr_advanced_menu_display_options' );
		
		// Render the output
		echo '<input type="text" id="css_class_prefix" name="atr_advanced_menu_display_options[css_class_prefix]" value="' . $options['css_class_prefix'] . '" />' . '<label for="css_class_prefix">&nbsp;'  . $args[0] . '</label>';

	} // end css_class_prefix_callback
	
	






}