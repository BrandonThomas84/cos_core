<?php
/*
Plugin Name: Side Slider Widget
Plugin URI: http://www.perspektivedesigns.com
Description: Easily add a side slider to any page
Version: 1.0
Author: Brandon Thomas
Author URI: http://www.perspektivedesigns.com
Licence: GPL3
*/

/*  Copyright 2014  Brandon Thomas  (email : brandon@perspektivedesigns.com)

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
		$exit_msg = __('This version of side slider requires WordPress 2.1 or greater.');
	}
	exit($exit_msg);
}

/**
Hooking the custom post type
 */
function _side_slider_core() {	

	$latestVersion = date('U');

	//add css and styling to the theme pages
	wp_enqueue_script( 'side-slider-js', plugins_url('side-slider') . '/side-slider-script.js', array('jquery') ); 
	wp_enqueue_style( 'side-slider-style', plugins_url('side-slider') . '/side-slider-style.css', null, $latestVersion ); 

}

/* ===== Slider widgets ===== */
/**
Function to add widgets to necessary pages
 */
function _side_slider_widgets(){
	require_once('_side_slider_widgets.class.php');
	register_widget( '_side_slider_widgets' );	
}

//loading admin actions
add_action( 'init', '_side_slider_core' );
add_action( 'widgets_init', '_side_slider_widgets' );



?>