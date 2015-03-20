<?php
/*
Plugin Name: Chico Design Group Custom Functions
Plugin URI: http://www.chicodesigngroup.com
Description: A grouping of custom functionalities designed specifically for you by Chico Design Group
Version: 1.0
Author: Brandon Thomas
Author URI: http://www.perspektivedesigns.com
Licence: GPL3
*/

/*  Copyright 2014  Chico Design Group (email : brandon@perspektivedesigns.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
Checking version 2.1 or above
 */
if ( version_compare($GLOBALS['wp_version'], '2.1', '<') || !function_exists( 'add_action' ) ) {
	if ( !function_exists( 'add_action' ) ) {
		$exit_msg = __('I\'m just a plugin, please don\'t call me directly');
	} else {
		$exit_msg = __('This version of your website requires WordPress 3.3 or greater.');
	}
	exit($exit_msg);
}

/**
Function that determines the core functionality of the application
 */
function _cdg_core() {	

	//define constant
	define('_CDG_HOME_', dirname(__FILE__) );	

	require_once( __DIR__ . '/include/functions.php');
	require_once( __DIR__ . '/include/cdg.class.php');
	
	//adding all menu items
	add_action( 'admin_menu', '_cdg_menu');
	add_action( 'admin_enqueue_scripts', '_cdg_get_admin_scripts' );
	add_action( 'wp_enqueue_scripts', '_cdg_get_frontend_scripts' );
	

}
/* ===== WordPress menu registration and scripts ===== */
/**
Hook the admin menu items
*/

function _cdg_menu(){

	//core menu 
	//add_menu_page('Chico Design Group', 'Chico Design Group', 'publish_posts', 'ChicoDesignGroup', '_cdg_start_class', plugin_dir_url(__FILE__) . 'assets/logo/perspektive_25.png');
	
}

/* ===== Admin screen ===== */
/**
Run the class that outputs the html for the admin screens
*/
function _cdg_start_class(){

	new cdg;

}


/**
Starting the application
*/

//define('WP_DEBUG', true);
//define('WP_DEBUG_DISPLAY', true);


//loading admin actions
add_action( 'init', '_cdg_core' );


?>