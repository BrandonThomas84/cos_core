<?php

/**
 * Class to control all the registration event functions
 */
class pdesigns_registration_events {

	/**
	 * Add menu items
	 */

	public function __construct(){

		//add the registration menu item
		add_menu_page( 'Events', 'COS Registration Events', 'edit_posts', 'cos-registration', array( $this, 'registration_display' ), 'dashicons-clock', 6 );
		add_submenu_page( 'cos-registration', 'Create a New COS Registration Events', 'New Event', 'edit_posts', 'cos-new-event', array( $this, 'new_event_display' ));
	}

	public function new_event_display(){
		
	}

}

?>