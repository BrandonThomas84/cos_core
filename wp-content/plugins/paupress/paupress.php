<?php
/*
Plugin Name: PauPress
Plugin URI: http://paupress.com/
Description: A CRM framework for WordPress and any industry. The prefix acronym stands for Perspectives on the Actions of your Users.
Version: 1.5.6
Author: havahula.org
Author URI: http://havahula.org/
Text Domain: paupress
Domain Path: languages

PauPress is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

PauPress is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with PauPress. If not, see <http://www.gnu.org/licenses/>.
*/



/* -----------------------------------------------------------
	SETUP, OPTIONS & ACTIONS
   ----------------------------------------------------------- */

/**
 * Security: Shut it down if the plugin is called directly
 *
 * @since 1.0.0
 */
if ( !function_exists( 'add_action' ) ) {
	echo "hi there!  i'm just a plugin, not much i can do when called directly.";
	exit;
}


define( 'PAUPRESS_VER', '1.5.6' );
define( 'PAUPRESS_URL', plugin_dir_url( __FILE__ ) );
define( 'PAUPRESS_DIR', plugin_dir_path(__FILE__) );
define( 'PAUPRESS_SLUG', plugin_basename( __FILE__ ) );

 
/**
 * Register the activation and deactivation hooks to keep things tidy.
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'paupress_activate' );


/**
 * Include the necessary supporting scripts.
 *
 * @since 1.0.0
 */
include_once( 'options/paupress-defaults.php' );
include_once( 'options/paupress-settings.php' );
include_once( 'options/paupress-general-settings.php' );
include_once( 'options/paupress-user-settings.php' );
include_once( 'options/paupress-actions-settings.php' );
include_once( 'options/paupress-system-settings.php' );
include_once( 'options/paupress-forms-settings.php' );
include_once( 'options/paupress-panel-settings.php' );
include_once( 'options/paupress-pro-settings.php' );

include_once( 'utilities/paupress-fields.php' );
include_once( 'utilities/paupress-help.php' );
include_once( 'utilities/paupress-general.php' );
include_once( 'utilities/paupress-security.php' );
include_once( 'utilities/paupress-users.php' );

include_once( 'reports/paupress-reports.php' );
include_once( 'reports/paupress-filter-form.php' );
include_once( 'reports/paupress-filter.php' );
include_once( 'reports/paupress-edit.php' );
include_once( 'reports/paupress-actions.php' );
include_once( 'reports/paupress-queries.php' );

include_once( 'posts/paupress-post-actions.php' );
include_once( 'posts/paupress-user-actions.php' );
include_once( 'posts/paupress-post-actions-meta.php' );
include_once( 'posts/paupress-user-actions-meta.php' );

include_once( 'users/paupress-users.php' );
include_once( 'users/paupress-profile.php' );
include_once( 'users/paupress-user-plugins.php' );

include_once( 'fields/paupress-field.php' );
include_once( 'fields/paupress-field-management.php' );
include_once( 'fields/paupress-field-helpers.php' );
include_once( 'fields/paupress-form.php' );

include_once( 'theme/paupanels.php' );
include_once( 'theme/paupanels-incoming.php' );

// SETS TRANSLATION SOURCES
function paupress_textdomain() {
	load_plugin_textdomain( 'paupress', false, dirname( PAUPRESS_SLUG ) . '/languages/' );
	$paupress_t_strings = array(
									'login' => __( 'sign in', 'paupress' ),
									'logout' => __( 'sign out', 'paupress' ),
									'signup' => __( 'sign up', 'paupress' ),
									'profile' => __( 'My Profile', 'paupress' ), 
									'thanks' => __( 'Thank you!', 'paupress' ), 
									'lost' => __( 'How did you get here?', 'paupress' ), 
									'confirm_delete' => __( 'Confirm Deletion?', 'paupress' ), 
									'confirm_submit' => __( 'Confirm Submission?', 'paupress' ), 
									'support_docs' => __( 'Support Documentation', 'paupress' ), 
									'discount' => __( 'Discount', 'paupress' ), 
									'quantity' => __( 'Quantity', 'paupress' ), 
									'amount' => __( 'Amount', 'paupress' ), 
									'tax' => __( 'Tax', 'paupress' ), 
									'shipping' => __( 'Shipping', 'paupress' ), 
									'billing' => __( 'Billing', 'paupress' ), 
									'refund' => __( 'Refund', 'paupress' ), 
									'credit' => __( 'Credit', 'paupress' ), 
									'default' => sprintf( __( '%1$sDefault%2$s', 'paupress' ), '(', ')' ), 
	);
	$paupress_t_strings = apply_filters( 'paupress_t_strings', $paupress_t_strings );
	
	foreach ( $paupress_t_strings as $k => $v ) {
		$paupress_t_reserves = array( 'ver', 'url', 'dir', 'slug' );
		if ( !in_array( $k, $paupress_t_reserves ) )
			define( 'PAUPRESS_' . strtoupper( $k ), $v ); 
	}		
}


/**
 * Register the Administration supporting files
 *
 * @since 1.0.0
 */
function paupress_init_register(){
	
	// GENERAL PAUPRESS SCRIPT
	$paupress_ajax_array = array();
	$paupress_ajax_array['ajaxurl'] = admin_url( 'admin-ajax.php' );
	$paupress_ajax_array['paupress_nonce'] = wp_create_nonce( 'paupress-nonce' );
	$paupress_ajax_array['ajaxload'] = plugins_url( '/assets/g/loading.gif', __FILE__ );
	
	// DATEPICKER OPTION FOR LOW YEAR
	$paupress_ajax_array['yearLow'] = 5;
	if ( false != get_option( '_paupress_yearlow' ) )
		$paupress_ajax_array['yearLow'] = get_option( '_paupress_yearlow' );
	
	// DATEPICKER OPTION FOR HIGH YEAR	
	$paupress_ajax_array['yearHigh'] = 10;
	if ( false != get_option( '_paupress_yearhigh' ) )
		$paupress_ajax_array['yearHigh'] = get_option( '_paupress_yearhigh' );
	
	wp_register_script( 'paupressJS', PAUPRESS_URL . 'assets/j/paupress.js', array( 'jquery' ), PAUPRESS_VER, false );
	wp_localize_script( 'paupressJS', 'paupressAjax', $paupress_ajax_array );
	/*
	$user_agent = $_SERVER['HTTP_USER_AGENT']; 
	if (preg_match('/Firefox/i', $user_agent)) { 
	   wp_register_script( 'paupressJS', PAUPRESS_URL . 'assets/j/paupress-ff.js', array( 'jquery' ), PAUPRESS_VER, false );
	} else {
	   wp_register_script( 'paupressJS', PAUPRESS_URL . 'assets/j/paupress.js', array( 'jquery' ), PAUPRESS_VER, false );
	}
	*/
	
	// PAUPRESS ADDITIONAL SCRIPTS
	wp_register_script( 'paupressAdminJS', PAUPRESS_URL . 'assets/j/paupress-admin.js', array( 'jquery' ), PAUPRESS_VER, false );
	wp_register_script( 'paupanelsJS', PAUPRESS_URL . 'assets/j/paupanels.js', array( 'jquery' ), PAUPRESS_VER, false );
	wp_register_script( 'paupressSearchJS', PAUPRESS_URL . 'assets/j/paupress-search.js', array( 'jquery' ), PAUPRESS_VER, false );
	wp_register_script( 'paupressViewsJS', PAUPRESS_URL . 'assets/j/paupress-views.js', array( 'jquery' ), PAUPRESS_VER, false );
	wp_register_script( 'tiptipJS', PAUPRESS_URL . 'assets/j/tiptip/jquery.tiptip.js', array( 'jquery' ), PAUPRESS_VER, false );
	wp_register_script( 'chosenJS', PAUPRESS_URL . 'assets/j/chosen/chosen.jquery.js', array( 'jquery' ), PAUPRESS_VER, false );
	wp_register_script( 'cookieJS', PAUPRESS_URL . 'assets/j/jquery.cookie.js', array( 'jquery' ), PAUPRESS_VER, false );

	// REGISTER PLUGIN STYLES
	wp_register_style( 'paupressCSS', PAUPRESS_URL . 'assets/c/paupress.css', array(), PAUPRESS_VER, 'screen' );
	wp_register_style( 'paupressAdminCSS', PAUPRESS_URL . 'assets/c/paupress-admin.css', array(), PAUPRESS_VER, 'screen' );
	wp_register_style( 'paupressPrintCSS', PAUPRESS_URL . 'assets/c/paupress-print.css', array(), PAUPRESS_VER, 'print' );
	wp_register_style( 'paupanelsCSS', PAUPRESS_URL . 'assets/c/paupanels.css', array(), PAUPRESS_VER, 'screen' );
	wp_register_style( 'paupressGridCSS', PAUPRESS_URL . 'assets/c/paupress-grid.css', array(), PAUPRESS_VER, 'screen' );
	wp_register_style( 'tiptipCSS', PAUPRESS_URL . 'assets/j/tiptip/tiptip.css', array(), PAUPRESS_VER, 'screen' );
	wp_register_style( 'chosenCSS', PAUPRESS_URL . 'assets/j/chosen/chosen.css', array(), PAUPRESS_VER, 'screen' );
	wp_register_style( 'jqueryuiCSS', PAUPRESS_URL . 'assets/c/jquery-ui/jquery-ui.paupress.css', array(), PAUPRESS_VER, 'screen' );
	
	// THIRD PARTY SUPPORT FILES
	if ( defined( 'USER_AVATAR_UPLOAD_PATH' ) )
		wp_register_style( 'user-avatar', plugins_url('/user-avatar/css/user-avatar.css') );
	
}

/**
 * Include the Administration supporting files
 *
 * @since 1.0.0
 */
function paupress_admin_scripts(){
	
	// QUEUE WORDPRESS SCRIPTS	
	wp_enqueue_media();

	// QUEUE PLUGIN SCRIPTS
	wp_enqueue_script( 'paupressSearchJS' );
	wp_enqueue_script( 'paupressViewsJS' );
	//wp_enqueue_script( 'tiptipJS', PAUPRESS_URL . 'assets/j/tiptip/jquery.tiptip.js', array( 'jquery' ), PAUPRESS_VER, false );
	//wp_enqueue_script( 'chosenJS', PAUPRESS_URL . 'assets/j/chosen/chosen.jquery.js', array( 'jquery' ), PAUPRESS_VER, false );

	// QUEUE PLUGIN STYLES
	wp_enqueue_style( 'paupressCSS' );
	wp_enqueue_style( 'paupressAdminCSS' );
	wp_enqueue_style( 'paupressPrintCSS' );
	//wp_enqueue_style( 'tiptipCSS', PAUPRESS_URL . 'assets/j/tiptip/tiptip.css', array(), PAUPRESS_VER, 'screen' );
	//wp_enqueue_style( 'chosenCSS', PAUPRESS_URL . 'assets/j/chosen/chosen.css', array(), PAUPRESS_VER, 'screen' );
	wp_enqueue_style( 'jqueryuiCSS' );
	
	// QUEUE WORDPRESS STYLES
	wp_enqueue_style( 'thickbox' );
	
	// QUEUE THIRD-PARTY STYLES
	if ( defined( 'USER_AVATAR_UPLOAD_PATH' ) )
		wp_enqueue_style( 'user-avatar' );
	
	// HOOK THE AJAX ENGINE FOR SEARCH 
	wp_localize_script( 'paupressSearchJS', 'paupressSearchAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'paupress_search_nonce' => wp_create_nonce( 'paupress-search-nonce' ), 'ajaxload' => plugins_url('/assets/g/loading.gif', __FILE__) ) );
	
	// HOOK THE AJAX ENGINE FOR REPORTS 
	wp_localize_script( 'paupressSearchJS', 'paupressReportAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'paupress_report_nonce' => wp_create_nonce( 'paupress-report-nonce' ), 'ajaxload' => plugins_url('/assets/g/loading.gif', __FILE__) ) );
	
	// HOOK THE AJAX ENGINE FOR POLLING 
	wp_localize_script( 'paupressSearchJS', 'paupressPollAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'paupress_poll_nonce' => wp_create_nonce( 'paupress-poll-nonce' ), 'ajaxload' => plugins_url('/assets/g/loading.gif', __FILE__) ) );
	
	// HOOK THE AJAX ENGINE FOR ELEMENT CHOICES 
	wp_localize_script( 'paupressAdminJS', 'paupressElemChoicesAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'paupress_element_choices_nonce' => wp_create_nonce( 'paupress-element-choices-nonce' ), 'ajaxload' => plugins_url('/assets/g/loading.gif', __FILE__) ) );
	
	
	/* DEPRECATED */
	
	/* QUEUE WORDPRESS SCRIPTS
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'thickbox' );
	*/
	
	
	/* QUEUE PLUGIN SCRIPTS
	//wp_enqueue_script( 'paupressJS' );
	//wp_enqueue_script( 'paupressAdminJS', PAUPRESS_URL . 'assets/j/paupress-admin.js', array( 'jquery' ), PAUPRESS_VER, false );
	//wp_enqueue_script( 'paupressJS', PAUPRESS_URL . 'assets/j/paupress.js', array( 'jquery' ), PAUPRESS_VER, false );
	
	$user_agent = $_SERVER['HTTP_USER_AGENT']; 
	if (preg_match('/Firefox/i', $user_agent)) { 
	   //wp_enqueue_script( 'paupressJS', PAUPRESS_URL . 'assets/j/paupress-ff.js', array( 'jquery' ), PAUPRESS_VER, false );
	} else {
	   wp_enqueue_script( 'paupressJS', PAUPRESS_URL . 'assets/j/paupress.js', array( 'jquery' ), PAUPRESS_VER, false );
	}
	*/
	
	/* HOOK THE AJAX ENGINE FOR GENERAL OPS
	wp_localize_script( 'paupressJS', 'paupressAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'paupress_nonce' => wp_create_nonce( 'paupress-nonce' ), 'ajaxload' => plugins_url('/assets/g/loading.gif', __FILE__) ) );
	*/
	
	/* HOOK THE AJAX ENGINE FOR ADMIN ELEMENTS 
	$admin_ajax_array = array();
	$admin_ajax_array['ajaxurl'] = admin_url( 'admin-ajax.php' );
	$admin_ajax_array['paupress_admin_nonce'] = wp_create_nonce( 'paupress-admin-nonce' );
	$admin_ajax_array['ajaxload'] = plugins_url('/assets/g/loading.gif', __FILE__);
	
	// PASS A VALUE IF FIREFOX IS DETECTED
	$user_agent = $_SERVER['HTTP_USER_AGENT']; 
	if ( preg_match( '/Firefox/i', $user_agent ) )
		$admin_ajax_array['firefox'] = true;
		
	wp_localize_script( 'paupressAdminJS', 'paupressAdminAjax', $admin_ajax_array);
	*/
		
}


function paupress_admin_globals() {
		
	// QUEUE WORDPRESS SCRIPTS
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'thickbox' );

	// QUEUE PAUPRESS SCRIPTS
	wp_enqueue_script( 'tiptipJS' );
	wp_enqueue_script( 'chosenJS' );
	wp_enqueue_script( 'paupressJS' );
	wp_enqueue_script( 'paupressAdminJS' );
	
	// QUEUE PLUGIN SCRIPTS
	//wp_enqueue_script( 'paupressAdminJS', PAUPRESS_URL . 'assets/j/paupress-admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-datepicker', 'thickbox', 'chosenJS' ), PAUPRESS_VER, false );
	
	// SET UP VARIABLES FOR ADMIN SCREENS
	$get_merge_tags = paupress_get_user_metadata( array( 'return_val' => true, 'include' => array( 'text' ) ) );
	//$merge_tags = unserialize( urldecode( $_GET['mt'] ) );
	$merge_tags = array();
	foreach ( $get_merge_tags as $val ) {
		$merge_tags[$val['meta_key']] = $val['name'];
	}
	$merge_tags = apply_filters( 'paupress_merge_filter', $merge_tags );
	$merged_tags = urlencode( serialize( $merge_tags ) );
	
	// HOOK THE AJAX ENGINE FOR ADMIN ELEMENTS
	$admin_ajax_array = array();
	$admin_ajax_array['ajaxurl'] = admin_url( 'admin-ajax.php' );
	$admin_ajax_array['paupress_admin_nonce'] = wp_create_nonce( 'paupress-admin-nonce' );
	$admin_ajax_array['ajaxload'] = plugins_url( '/assets/g/loading.gif', __FILE__ );
	$admin_ajax_array['ajaxhome'] = home_url();
	$admin_ajax_array['metaKeyTaken'] = __( 'duplicate key!', 'paupress' );
	$admin_ajax_array['oneMoment'] = __( 'One Moment Please...', 'paupress' );
	$admin_ajax_array['mergeref'] = plugins_url( '/assets/j/tmce/merge-ref.php?tags='.$merged_tags, __FILE__ );
	$admin_ajax_array['errMsg'] = sprintf( __( 'We found some errors -- please attend to the fields below marked with %1$s', 'paupress' ), '<span class="halt-example">&nbsp;</span>' );
	$admin_ajax_array['confirmDelete'] = PAUPRESS_CONFIRM_DELETE;
	
	// PASS A VALUE IF FIREFOX IS DETECTED
	$user_agent = $_SERVER['HTTP_USER_AGENT']; 
	if ( preg_match( '/Firefox/i', $user_agent ) )
		$admin_ajax_array['firefox'] = true;
	
	// LOCALIZE THE ADMIN SCRIPT	
	wp_localize_script( 'paupressAdminJS', 'paupressAdminAjax', $admin_ajax_array );
	
	// QUEUE STYLES
	//wp_enqueue_style( 'chosenCSS', PAUPRESS_URL . 'assets/j/chosen/chosen.css', array(), PAUPRESS_VER, false );
	wp_enqueue_style( 'chosenCSS' );
	wp_enqueue_style( 'tiptipCSS' );
	
	/*
	wp_localize_script( 'paupressAdminJS', 'paupressAdminAjax', array( 
		'ajaxurl' 						=> admin_url( 'admin-ajax.php' ), 
		'paupress_admin_nonce' 			=> wp_create_nonce( 'paupress-admin-nonce' ), 
		'ajaxload' 						=> plugins_url( '/assets/g/loading.gif', __FILE__ ), 
		'metaKeyTaken'					=> __( 'duplicate key!', 'paupress' ), 
		'oneMoment'						=> __( 'One Moment Please...', 'paupress' ), 
		'mergeref'						=> plugins_url( '/assets/j/tmce/merge-ref.php?tags='.$merged_tags, __FILE__ ), 
		'errMsg' 						=> sprintf( __( 'We found some errors -- please attend to the fields below marked with %1$s', 'paupress' ), '<span class="halt-example">&nbsp;</span>' ), 
	) );
	*/
}


/**
 * Modify the WordPress Administration Menus
 *
 * @since 1.0.0
 */
 
function paupress_menu() {
	
	// SET THE ARRAY
	$paupress_menu = array();
	
	// REMOVE THE DEFAULT EDIT USER MENU OPTION AND REPLACE IT
	remove_submenu_page( 'users.php', 'profile.php' );
	$paupress_menu['paupress_edit_user'] = add_submenu_page( 'users.php', 'Paupress User', 'My Profile', 'add_users', 'paupress_edit_user', 'paupress_edit_user');
	
	// REMOVE THE DEFAULT EDIT USER MENU OPTION FOR NON-ADMINS AND REPLACE IT
	remove_menu_page( 'profile.php' );
	$paupress_menu['paupress_edit_user_profile'] = add_menu_page( __( 'PauPress User', 'paupress' ), __( 'My Profile', 'paupress' ), 'read', 'paupress_edit_user_profile', 'paupress_edit_user');
	
	// REMOVE THE DEFAULT ADD USER MENU OPTION AND REPLACE IT
	remove_submenu_page( 'users.php', 'user-new.php' );
	$paupress_menu['paupress_new_user'] = add_submenu_page( 'users.php', __( 'PauPress User', 'paupress' ), __( 'Add New User', 'paupress' ), 'add_users', 'paupress_new_user', 'paupress_new_user');
	
	// ADD THE SEARCH UTILITY TO THE WORDPRESS SYSTEM
	$paupress_menu['paupress_reports'] = add_submenu_page( 'users.php', __( 'PauPress Reports', 'paupress' ), __( 'User Reports', 'paupress' ), 'add_users', 'paupress_reports', 'paupress_search');
	
	// CREATE THE ADMINISTRATIVE MENU
	$paupress_menu['paupress_caps_options'] = add_menu_page( __( 'PauPress', 'paupress' ), __( 'PauPress', 'paupress' ), 'manage_options', 'paupress_options', 'paupress_options', PAUPRESS_URL.'/assets/g/paupress.png', '70.1' );
	
	// ADD THE USER OPTIONS PAGES TO THE WORDPRESS SYSTEM
	$paupress_menu['paupress_meta_options'] = add_submenu_page( 'paupress_options', __( 'Manage Fields', 'paupress' ), __( 'Manage Fields', 'paupress' ), 'activate_plugins', 'paupress_meta_options', 'paupress_meta_options_form' );
	
	// ADD THE INBOUND COMMUNICATION DASHBOARD
	$paupress_menu['paupress_inbound'] = add_submenu_page( 'paupress_options', __( 'Inbound Activity', 'paupress' ), __( 'Inbound Activity', 'paupress' ), 'add_users', 'paupress_inbounds', 'paupress_inbounds' );
	
	// ADD THE MODAL WINDOW
	$paupress_menu['paupress_modal'] = add_submenu_page( NULL, __( 'Actions', 'paupress' ), '', 'add_users', 'paupress_modal_action', 'paupress_modal_action' );
	
	$paupress_menu['users'] = 'users.php';
	
	$paupress_menu = apply_filters( 'paupress_push_menu', $paupress_menu );

	foreach ( $paupress_menu as $key => $value ) {
		add_action( 'admin_print_styles-' . $value, 'paupress_admin_scripts', 9 );
		add_action( 'load-' . $value, 'paupress_help_screens' );
	}
			
}


function paupress_actions_link( $links ) { 
	$settings_link = '<a href="' . admin_url() . '?page=paupress_options">Settings</a>'; 
  	array_unshift($links, $settings_link); 
  	return $links; 
}


/**
 * WordPress Actions & Filters
 *
 * @since 1.0.0
 */
// ADDS THE GLOBAL MENU STRUCTURE
add_action( 'admin_menu', 'paupress_menu', 9  );
add_filter( 'admin_body_class', 'paupress_admin_body_class' );
//add_action( 'admin_bar_menu', 'paupress_add_nodes', 999 );

// RUN FIRST
add_action( 'init', 'paupress_restrict_redirect' );
add_action( 'init', 'paupress_minified_admin' );
add_action( 'init', 'paupress_options_save' );
add_action( 'init', 'paupress_init_register' );
add_action( 'init', 'paupress_init_roles' );
add_action( 'init', 'paupress_init_user_actions' );
add_action( 'init', 'paupress_init_post_actions' );
add_action( 'init', 'paupress_wp_taxonomies' );
add_action( 'init', 'paupress_add_merge_tags_button', 20 );
add_action( 'admin_init', 'paupress_listener' );
//add_action( 'admin_init', 'paupress_init_register' );
add_action( 'admin_init', 'paupress_activate' );
add_action( 'admin_init', 'paupress_update_user' );
//add_action( 'admin_init', 'paupress_action_minified' );
add_action( 'plugins_loaded', 'paupress_textdomain' );
add_action( 'paupress_options_save_ext','paupress_save_capabilities' );
add_action( 'admin_enqueue_scripts', 'paupress_admin_globals' );

// QUEUES SUPPORTING FILES
add_action( 'admin_head', 'paupress_admin_fixes' );

// ADD SHORTCODES
add_shortcode( 'ppmt', 'paupress_merge_tags_shortcode' );
add_shortcode( 'pauf', 'paupanels_forms_shortcode' );
add_filter('widget_text', 'do_shortcode');

// DISPLAYS THE ADDITIONAL DATA FOR EACH USER
add_action( 'paupress_action_search_meta', 'paupress_action_search_meta', 10, 2 );

// OPENS UP A RESOURCE FOR EXTERNAL APIS
add_filter( 'query_vars', 'paupress_query_vars' );
add_action( 'generate_rewrite_rules', 'paupress_rewrite_rules' );
add_action( 'parse_request', 'paupress_parse_request' );

// HOOK INTO THE USER ACTIONS META API
add_filter( 'paupress_get_user_actions', 'paupress_push_user_actions' );
add_filter( 'paupress_get_user_actions_meta', 'paupress_push_user_actions_meta' );

// MODIFY USER HISTORY PANEL
add_filter( 'paupress_ai_class_filter', 'pp_log_ai_class_filter', 10, 2 );

// FILTER OUT PAUCONTENT
add_filter( 'paucontent_exclude_accepted_post_types', 'paupress_exclude_accepted_post_types' );
add_filter( 'paucontent_exclude_restricted_post_types', 'paupress_exclude_restricted_post_types' );

// SAVES THE ACTION META DATA
add_action( 'save_post', 'paupress_save_action_meta' ); // action_save_meta
add_filter( 'attachment_fields_to_save', 'paupress_attachment_save', 10, 2);

// MODIFIES DEFAULT USER COLUMNS
//add_action('manage_users_custom_column', 'paupress_manage_users_custom_column', 15, 3);

// MODIFY THE LOGIN FORM FOR EMAIL LABELS
add_action( 'login_form', 'paupress_email_login' );

// HOOK ADMIN AJAX SUBMISSION FOR SAVING QUERIES
add_action( 'wp_ajax_paupress_process_queries', 'paupress_process_saved_queries' );

// HOOK ADMIN AJAX SUBMISSION FOR ADMINISTERING SAVED IMPORTS
add_action( 'wp_ajax_paupress_process_saved_imports', 'paupress_saved_import_process' );

// HOOK ADMIN AJAX SUBMISSION FOR SEARCHES
add_action( 'wp_ajax_paupress_search_form', 'paupress_search_form' );

// HOOK ADMIN AJAX SUBMISSION FOR REPORTS
add_action( 'wp_ajax_paupress_report_form', 'paupress_report_process' );

// HOOK ADMIN AJAX SUBMISSION FOR NEW ELEMENTS
add_action( 'wp_ajax_paupress_new_elements_form', 'paupress_new_elements_forms' );

// HOOK ADMIN AJAX SUBMISSION FOR INLINE POSTS
add_action( 'wp_ajax_paupress_get_post_to_edit', 'paupress_get_post_to_edit' );
add_action( 'wp_ajax_paupress_save_new_post', 'paupress_save_new_post' );

// HOOK ADMIN AJAX SUBMISSION FOR ELEMENT CHOICES
add_action( 'wp_ajax_paupress_element_choices_form', 'paupress_element_choices_forms' );

// HOOK ADMIN AJAX SUBMISSION FOR ELEMENT CHOICES
add_action( 'wp_ajax_paupress_poll_status', 'paupress_poll_statuses' );
add_action( 'wp_ajax_paupress_new_search_row', 'paupress_add_search_row' );

// SWAPS TARGET USER IN AS POST AUTHOR
add_filter( 'wp_insert_post_data' , 'paupress_log_user_action_meta' , '99', 2 );

// AUTHENTICATE BY EMAIL
remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
add_filter( 'authenticate', 'paupress_email_authenticate', 20, 3 );

add_action( 'wp_ajax_paupress_load_form', 'paupress_load_form' );
add_action( 'wp_ajax_paupress_new_form', 'paupress_new_form' );
add_action( 'wp_ajax_paupress_delete_form', 'paupress_delete_form' );
add_action( 'wp_ajax_paupress_profile_fields_update', 'paupress_profile_fields_update' );

// EXTEND THE ADDRESS OPTIONS FOR A PRIMARY MARKER
add_action( 'paupress_group_markers', 'paupress_primary_marker', 3, 10 );

// EXTEND UI FOR SETTINGS
add_filter( 'plugin_action_links_'.PAUPRESS_SLUG, 'paupress_actions_link' );
add_action( 'paupress_options_pre', 'paupress_option_welcome' );
add_action( 'paupress_options_post', 'paupress_option_goodbye' );

// PAUPANELS SHORT CODES
add_shortcode( 'ppf_link', 'paupanels_link_shortcode' );

// SETS THE MAIL DEFAULTS
add_filter( 'wp_mail_from', 'paupress_mail_from' );
add_filter( 'wp_mail_from_name', 'paupress_mail_from_name' );

/*add_action( 'welcome_panel', 'paupress_welcome', 18 );
function paupress_welcome() {
?>
</div>
<div id="paupress-welcome-panel" class="welcome-panel">
	<h3>PauPress</h3>
	<p class="about-description">hi there. we're glad you're here!</p>
	<div class="welcome-panel-column-container">
		<div class="welcome-panel-column"><h4>Howdy!</h4></div>
		<div class="welcome-panel-column"><h4>Hej!</h4></div>
		<div class="welcome-panel-column"><h4>Wilkomen!</h4></div>
	</div>

<?php
}
*/
