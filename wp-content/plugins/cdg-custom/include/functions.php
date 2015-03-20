<?php

function _cdg_get_admin_scripts() {

	wp_enqueue_script('_cdg_admin_javascript', plugin_dir_url(__FILE__) . 'js/cdg-admin.js', array('jquery'));
	wp_enqueue_style('_cdg_admin_style', plugin_dir_url(__FILE__) . 'css/cdg-admin.css');

}

function _cdg_get_frontend_scripts() {

	wp_enqueue_script('_cdg_frontend_javascript', plugin_dir_url(__FILE__) . 'js/cdg-frontend.js', array('jquery'));
	//http://jquery.malsup.com/cycle/options.htm
	wp_enqueue_script('_cdg_frontend_cycle_javascript', plugin_dir_url(__FILE__) . 'js/cdg-cycle.js', array('jquery', '_cdg_frontend_javascript'));
	wp_enqueue_style('_cdg_frontend_style', plugin_dir_url(__FILE__) . 'css/cdg-frontend.css?ver=' . date('U'));
}
?>