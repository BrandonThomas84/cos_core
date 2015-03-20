<?php

/**
 * Class to control all the header countdown functions functions
 */
class pdesigns_header_supplement {

	/**
	 * Add menu items
	 */

	public function __construct() {

		global $latestVersion;

		//enqueue admin side scripts
		wp_enqueue_script('_cos_countdown_plugin', get_template_directory_uri() . '-child/js/jquery.plugin.min.js?ver=' . $latestVersion, array('jquery'));
		wp_enqueue_script('_cos_countdown', get_template_directory_uri() . '-child/js/jquery.countdown.min.js?ver=' . $latestVersion, array('jquery', '_cos_countdown_plugin'));
		wp_enqueue_style('_cos_countdown_style', get_template_directory_uri() . '-child/style/jquery.countdown.css');

	}

	public function new_event_display() {

	}

}

?>