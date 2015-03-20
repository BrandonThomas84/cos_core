<?php

	function paupress_forms_settings() {
		
		return apply_filters( 'paupress_forms_settings', array(
						
			array( 'meta' => array( 
									'source' => 'paupress', 
									'meta_key' => '_paupress_forms_setting_title', 
									'name' => __( 'General Forms', 'paupress' ), 
									'help' => '', 
									'description' => __( 'Forms allow you to receive information from your site visitors. You can arrange and place selected user fields for data collection in addition to standard fields like "subject", "message" and the option for a visitor to copy themselves on the messsage. All incoming responses are sent to you and then logged.', 'paupress' ), 
									'options' => array( 
														'field_type' => 'title',
														'req' => false, 
														'public' => false, 
														'choices' => false
									) 
			) ),
			
			array( 'meta' => array( 
									'source' => 'paupress', 
									'meta_key' => '_paupress_user_forms', 
									'name' => __( 'Create and Edit Forms', 'paupress' ), 
									'help' => '', 
									'options' => array( 
														'field_type' => 'plugin',
														'req' => false, 
														'public' => false, 
														'choices' => 'paupress_form_manager'
									) 
			) )
		
		));
		
	}

?>