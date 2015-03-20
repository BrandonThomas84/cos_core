<?php

function paupress_help_screens() {
	
	$screen = get_current_screen();

	switch( $screen->id ) {
	
		case 'toplevel_page_paupress_options' : 
		
			$screen->add_help_tab( array(
			    'id' 		=> 'op_general',
			    'title'		=> PAUPRESS_SUPPORT_DOCS,
			    'content'	=> '<h4><a href="http://paupress.com/support/documentation/paupress-plugin-documentation/">paupress.com/support/documentation</a></h4>',
			) );
		
		break;
		
		case 'paupress-options_page_paupress_inbounds' : 
		
			$screen->add_help_tab( array(
			    'id' 		=> 'ib_general',
			    'title'		=> PAUPRESS_SUPPORT_DOCS,
			    'content'	=> '<h4><a href="http://paupress.com/support/documentation/paupress-plugin-documentation/paupress-options/inbound-communications/">paupress-plugin-documentation/paupress-options/inbound-communications</a></h4>',
			) );
		
		break;
	
		case 'users_page_paupress_reports' : 
		
			$screen->add_help_tab( array(
			    'id' 		=> 'rp_search',
			    'title'		=> PAUPRESS_SUPPORT_DOCS,
			    'content'	=> '<h4><a href="http://paupress.com/support/documentation/paupress-plugin-documentation/users/user-reports/">paupress-plugin-documentation/users/user-reports</a></h4>',
			) );
			
		break;
		
		case 'users_page_paupress_edit_user' : 
		case 'toplevel_page_paupress_edit_user_profile' : 
		case 'users_page_paupress_new_user' : 
			
			$screen->add_help_tab( array(
			    'id' 		=> 'up_profile',
			    'title'		=> PAUPRESS_SUPPORT_DOCS,
			    'content'	=> '<h4><a href="http://paupress.com/support/documentation/paupress-plugin-documentation/users/my-profile/">paupress-plugin-documentation/users/my-profile</a></h4>',
			) );
			
		break;
	
		case 'paupress-options_page_paupress_meta_options' :
		
			$screen->add_help_tab( array( 
				'id' 		=> 'mf_general',
		        'title'		=> PAUPRESS_SUPPORT_DOCS,
		        'content'	=> '<h4><a href="http://paupress.com/support/documentation/paupress-plugin-documentation/paupress-options/manage-fields/">paupress-plugin-documentation/paupress-options/manage-fields</a></h4>', 
		    ) );
		
		break;
	
	}
}

?>