<?php


	//controls pretitle meta box saving
	function save_pdesigns_pretitle_meta_box( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['pdesigns_pretitle_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['pdesigns_pretitle_nonce'], 'pdesigns_pretitle' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		
		// Make sure that it is set.
		if ( ! isset( $_POST['pdesigns_pretitle'] ) ) {
			return;
		}

		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['pdesigns_pretitle'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, 'pdesigns_pretitle', $my_data );
	}

	//controls subtitle meta box saving
	function save_pdesigns_subtitle_meta_box( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['pdesigns_subtitle_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['pdesigns_subtitle_nonce'], 'pdesigns_subtitle' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		
		// Make sure that it is set.
		if ( ! isset( $_POST['pdesigns_subtitle'] ) ) {
			return;
		}

		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['pdesigns_subtitle'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, 'pdesigns_subtitle', $my_data );
	}

	//controls fund raising meta box saving
	function save_pdesigns_fund_raising_event_meta_box( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['pdesigns_fund_raising_event_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['pdesigns_fund_raising_event_nonce'], 'pdesigns_fund_raising_event' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		

		//combine user input into array
		$my_data['deadline']				= $_POST['pdesigns_fund_raising_event_deadline'];
		$my_data['price']					= $_POST['pdesigns_fund_raising_event_price'];
		$my_data['location_name']		 	= $_POST['pdesigns_fund_raising_event_location_name'];
		$my_data['location_addr'] 			= $_POST['pdesigns_fund_raising_event_location_addr'];
		$my_data['location_addr_2']		 	= $_POST['pdesigns_fund_raising_event_location_addr_2'];
		$my_data['location_city'] 			= $_POST['pdesigns_fund_raising_event_location_city'];
		$my_data['location_state']		 	= $_POST['pdesigns_fund_raising_event_location_state'];
		$my_data['location_zip'] 			= $_POST['pdesigns_fund_raising_event_location_zip'];
		$my_data['date'] 					= $_POST['pdesigns_fund_raising_event_date'];
		$my_data['time_start']		 		= $_POST['pdesigns_fund_raising_event_time_start'];
		$my_data['time_end'] 				= $_POST['pdesigns_fund_raising_event_time_end'];
		$my_data['html'] 					= $_POST['pdesigns_fund_raising_event_html'];

		//buy link inclusion
		if( !empty( $_POST['pdesigns_fund_raising_event_include_buy_link'] ) ){
			$my_data['include_buy_link'] = 'Y';
		} else {
			$my_data['include_buy_link'] = 'N';
		}

		//advanced sales only inclusion
		if( !empty( $_POST['pdesigns_fund_raising_event_advanced_sales_only'] ) ){
			$my_data['advanced_sales_only'] = 'Y';
		} else {
			$my_data['advanced_sales_only'] = 'N';
		}

		//encode data
		$my_data = json_encode( $my_data );

		//Update the meta field in the database.
		update_post_meta( $post_id, 'pdesigns_fund_raising_event_info', $my_data );
	}

	function save_pdesigns_contacts_meta_box( $post_id ){

		// Check if our nonce is set.
		if ( ! isset( $_POST['pdesigns_contacts_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['pdesigns_contacts_nonce'], 'pdesigns_contacts' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		//get the users
		$u = get_users();

		//combine user IDs into an array
		foreach( $u as $user ){
			$users[$user->data->ID] = $user->data->ID;

			//check for committee group selection
			if( isset( $_POST['c_group_' . $user->data->ID ] ) ){
				//update the selected users
				update_user_meta( $user->data->ID , 'c_group', $_POST['c_group_' . $user->data->ID ] );
			}
		}
		
		//check for checkbox display posts
		if( !empty( $_POST['enable_display_contact'] ) ){
			//loop through selected users
			foreach( $_POST['enable_display_contact'] as $p ){

				//update the selected users
				update_user_meta( $p , 'display_contact', 'true' );	

				//unset from users array
				unset( $users[ $p ] );
			}
		}

		//update the unselected users
		foreach( $users as $user ){
			
			update_user_meta( $user , 'display_contact', 'false' );	
		}
		
	}

	function save_pdesigns_advisors_meta_box( $post_id ){

		// Check if our nonce is set.
		if ( ! isset( $_POST['pdesigns_advisors_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['pdesigns_advisors_nonce'], 'pdesigns_advisors' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		//get the users
		$u = get_users();

		//combine user IDs into an array
		foreach( $u as $user ){

			$user_id = $user->data->ID;
			$users[ $user_id ] = $user_id;

			//check for convention, location and display order
			if( isset( $_POST['a_convention' . $user_id ] ) ){

				//set variables
				$convention = $_POST[ 'a_convention' . $user_id ];
				$location = $_POST[ 'a_location' . $user_id ];
				$displayOrder = $_POST[ 'a_order' . $user_id ];

				//check for display checkbox
				if( isset( $_POST[ 'user_visible' . $user_id ] ) ){
					$visible = 'true';
				} else {
					$visible = 'false';
				}

				//add to array
				$value = array(
					'conv' => $convention,
					'loc' => $location,
					'order' => (int) $displayOrder,
					'active' => $visible,
				);

				//serialize
				$value = serialize( $value );

				//update the selected users
				update_user_meta( $user_id , 'advisory_settings', $value );
			}
		}		
	}

	function save_pdesigns_qr_code_meta_box( $post_id ){

		// Check if our nonce is set.
		if ( ! isset( $_POST['pdesigns_qr_code_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['pdesigns_qr_code_nonce'], 'pdesigns_qr_code' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		//create empty array for the options
		$qr_options = array();

		//check for checkbox controlling the page display
		if( !empty( $_POST['display_qr_code'] ) ){
			$qr_options['display'] = 'true';
		} else {
			$qr_options['display'] = 'false';
		}

		//check for QR dimensions
		if( !empty( $_POST['qr_dims'] ) ){
			$qr_options['dimensions'] = $_POST['qr_dims'];
		} else {
			$qr_options['dimensions'] = 200;
		}

		//encode options
		$my_data = json_encode( $qr_options );

		//update db
		update_post_meta( $post_id, 'pdesigns_qr_options', $my_data );

	}


?>