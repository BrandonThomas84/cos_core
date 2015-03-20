<?php
	
	//contact persons meta box
	function contact_person_meta_box( $post ){

		//warn users that they must enable the contact functionality
		echo '<div class="warning"><span>NOTE:</span><p>If you do not see a user that you know should be there it is most likely that they\'re contact functionality has not been enabled. To enable this please contact the Administrator for the site to have them enable this functionality.</p></div>';

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pdesigns_contacts', 'pdesigns_contacts_nonce' );

		//get users where allow contact is true
		$args = array(
			'meta_key'     => 'paupress_pp_contact',
			'meta_value'   => 'true'
		);

		$users = get_users( $args );

		echo '<div class="meta-contact-main">';

		foreach( $users as $user ){

			//set vars for core information
			$userID = $user->data->ID;
			$reg_date = $user->data->user_registered;
			$display_name = $user->data->display_name;

			//get user meta data
			$userMeta = get_user_meta( $userID );

			//set meta vars
			$first_name = $userMeta['first_name'][0];
			$last_name = $userMeta['last_name'][0];
			$title = $userMeta['title'][0];
			$display_user = $userMeta['display_contact'][0];
			$committee = $userMeta['c_group'][0];

			//start html
			echo '<div class="meta-contact-container">';

			//person ID
			echo '<p class="meta-user-id">ID:<span>' . $userID . '</span></p>';

			//person name
			echo '<p class="meta-user-name"><span>' . $first_name . ' ' . $last_name . '</span></p>';
			//person title
			echo '<p class="meta-user-title">Title:<br><span>' . $title . '</span></p>';

			//display on contact page
			echo '<label for="c_group">Committee Group</label>';
			echo '<select name="c_group_' . $userID . '">';
			echo '<option value="" ' . selected( '', $committee, FALSE ) . '></option>';
			echo '<option value="exec" ' . selected( 'exec', $committee, FALSE ) . '>Executive Committee</option>';
			echo '<option value="sub" ' . selected( 'sub', $committee, FALSE ) . '>Subcommittee</option>';
			echo '<option value="adv" ' . selected( 'adv', $committee, FALSE ) . '>Advisory</option>';
			echo '</select>';

			//display on contact page
			echo '<label for="enable_display_contact" class="inline-label">Display on Contact Page</label>';
			echo '<input type="checkbox" ' . checked('true', $display_user, FALSE) . ' name="enable_display_contact[]" value="' . $userID . '">';

			//end contact container
			echo '</div>';
			
		}
		echo '<div class="clear"></div>';

		//close container
		echo '</div>';
	}

	//contact persons meta box
	function advisor_person_meta_box( $post ){

		//warn users that they must enable the contact functionality
		echo '<div class="warning"><span>NOTE:</span><p>If you do not see a user that you know should be there it is most likely that they\'re contact functionality has not been enabled. To enable this please contact the Administrator for the site to have them enable this functionality.</p></div>';

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pdesigns_advisors', 'pdesigns_advisors_nonce' );

		//get users where allow contact is true
		$args = array(
			'meta_key'     => 'paupress_pp_contact',
			'meta_value'   => 'true'
		);

		$users = get_users( $args );

		//total advisors
		$count = count( $users );

		echo '<div class="meta-contact-main">';

		foreach( $users as $user ){

			//set vars for core information
			$userID = $user->data->ID;
			$reg_date = $user->data->user_registered;
			$display_name = $user->data->display_name;

			//get core user meta data
			$userMeta = get_user_meta( $userID );

			//set meta vars
			$first_name = $userMeta['first_name'][0];
			$last_name = $userMeta['last_name'][0];
			$title = $userMeta['title'][0];

			//get user advisory settings data
			$advisory_settings = get_user_meta( $userID, 'advisory_settings', true );
			$advisory_settings = unserialize( $advisory_settings );

			$display_user = $advisory_settings['active'];
			$convention = $advisory_settings['conv'];
			$location = $advisory_settings['loc'];
			$displayOrder = $advisory_settings['order'];

			//start html
			echo '<div class="meta-contact-container">';

			//person ID
			echo '<p class="meta-user-id">ID:<span>' . $userID . '</span></p>';

			//person name
			echo '<p class="meta-user-name"><span>' . $first_name . ' ' . $last_name . '</span></p>';

			//person title
			echo '<p class="meta-user-title">Title:<br><span>' . $title . '</span></p>';

			//person convention 
			echo '<p class="meta-user-title">Convention</p>';
			echo '<input type="text" name="a_convention' . $userID . '" placeholder="Ex: COS X" value="' . $convention . '">';

			//person location 
			echo '<p class="meta-user-title">Location</p>';
			echo '<input type="text" name="a_location' . $userID . '" placeholder="Ex: Fort Worth, TX" value="' . $location . '">';

			//order to display the users in
			echo '<label for="a_order">Display Order</label>';
			echo '<select name="a_order' . $userID . '">';
			echo '<option></option>';	

			//output the order select box
			for( $i = 1; $i <= $count; $i++ ){
				echo '<option value="' . $i . '"' . selected( $displayOrder, $i, false ) . '>' . $i . '</option>';	
			}

			echo '</select><br>';

			//display on contact page
			echo '<label for="user_visible' . $userID . '" class="inline-label">Display on Advisory Page</label>';
			echo '<input type="checkbox" ' . checked('true', $display_user, FALSE) . ' name="user_visible' . $userID . '" value="' . $userID . '">';

			//end contact container
			echo '</div>';
			
		}
		echo '<div class="clear"></div>';

		//close container
		echo '</div>';
	}

	//fund raising meta box
	function fund_raising_meta_box( $post ){
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pdesigns_fund_raising_event', 'pdesigns_fund_raising_event_nonce' );

		$fr_event = get_post_meta( $post->ID, 'pdesigns_fund_raising_event_info', true );
		$fr_event = json_decode( $fr_event );

		echo '<div class="normal-meta-container">';
		echo '<p class="normal-meta-title">Date / Time Information:</p>';

		//deadline
		echo '<div style="float: left;text-align: left;width: 50%;margin: 0">';
		echo '<label for="pdesigns_fund_raising_event_deadline">Event Registration Deadline:</label> ';
		echo '<input type="date" id="pdesigns_fund_raising_event_deadline" name="pdesigns_fund_raising_event_deadline" value="' . esc_attr( $fr_event->deadline ) . '" /><br>';

		echo '</div>';
		echo '<div style="float: left;text-align: left;width: 50%;margin: 0">';

		//date
		echo '<label for="pdesigns_fund_raising_event_date">Date of the Event:</label> ';
		echo '<input type="date" id="pdesigns_fund_raising_event_date" name="pdesigns_fund_raising_event_date" value="' . esc_attr( $fr_event->date ) . '" /><br>';

		echo '</div><div class="clear"></div>';
		
		//time start
		echo '<div style="float: left;text-align: left;width: 50%;margin: 0">';
		echo '<label for="pdesigns_fund_raising_event_time_start">Event Start:</label> ';
		echo '<input type="time" id="pdesigns_fund_raising_event_time_start" name="pdesigns_fund_raising_event_time_start" value="' . esc_attr( $fr_event->time_start ) . '" />';

		echo '</div>';
		
		//time end 
		echo '<div style="float: left;text-align: left;width: 50%;margin: 0">';
		echo '<label for="pdesigns_fund_raising_event_time_end">Event End:</label> ';
		echo '<input type="time" id="pdesigns_fund_raising_event_time_end" name="pdesigns_fund_raising_event_time_end" value="' . esc_attr( $fr_event->time_end ) . '" />';

		echo '</div><div class="clear"></div>';
		echo '</div><!--end row-->';

		echo '<div class="normal-meta-container">';
		echo '<p class="normal-meta-title">Pricing Information:</p><br>';
		
		//pricing
		echo '<div style="float: left;text-align: left;width: 50%;margin: 0">';
		echo '<label for="pdesigns_fund_raising_event_price">Pricing:</label> ';
		echo '$<input type="number" id="pdesigns_fund_raising_event_price" name="pdesigns_fund_raising_event_price" value="' . esc_attr( $fr_event->price ) . '" /><br>';
		echo '</div>';

		
		//advanced sales only
		echo '<div style="float: left;text-align: left;width: 50%;margin: 0">';
		echo '<label for="pdesigns_fund_raising_event_advanced_sales_only">Advance Tickets Only:</label> ';
		echo '<input type="checkbox" id="pdesigns_fund_raising_event_advanced_sales_only" name="pdesigns_fund_raising_event_advanced_sales_only" ' . checked($fr_event->advanced_sales_only, 'Y', FALSE ) . ' /><br>';

		//buy ticket link
		echo '<label for="pdesigns_fund_raising_event_include_buy_link">Include Buy Link:</label> ';
		echo '<input type="checkbox" id="pdesigns_fund_raising_event_include_buy_link" name="pdesigns_fund_raising_event_include_buy_link" ' . checked($fr_event->include_buy_link, 'Y', FALSE ) . ' /><br>';
		echo '</div><div class="clear"></div>';
		echo '</div><!--end row-->';

		echo '<div class="normal-meta-container">';
		
		//location
		echo '<div style="float: left;text-align: left;width: 100%;margin:0;">';
		echo '<p class="normal-meta-title">Event Location Information:</p>';
		echo '<label for="pdesigns_fund_raising_event_location">Location Name:</label></br>';
		echo '<input type="text" style="width: 100%;" id="pdesigns_fund_raising_event_location_name" name="pdesigns_fund_raising_event_location_name" value="' . esc_attr( $fr_event->location_name ) . '" /><br>';

		echo '<div style="float: left;width: 50%;margin: 0;">';
		echo '<label for="pdesigns_fund_raising_event_location_addr">Address</label></br>';
		echo '<input type="text" id="pdesigns_fund_raising_event_location_addr" name="pdesigns_fund_raising_event_location_addr" value="' . esc_attr( $fr_event->location_addr ) . '" /><br>';
		
		echo '<label for="pdesigns_fund_raising_event_location_addr_2">Address (2):</label></br>';
		echo '<input type="text" id="pdesigns_fund_raising_event_location_addr_2" name="pdesigns_fund_raising_event_location_addr_2" value="' . esc_attr( $fr_event->location_addr_2 ) . '" />';
		echo '</div>';

		echo '<div style="float: left;width: 50%;margin:0;">';
		echo '<label for="pdesigns_fund_raising_event_location_city" style="margin-right: 20px;">City:</label>';
		echo '<input type="text" id="pdesigns_fund_raising_event_location_city" name="pdesigns_fund_raising_event_location_city" value="' . esc_attr( $fr_event->location_city ) . '" /><br>';

		echo '<label for="pdesigns_fund_raising_event_location_state" style="margin-right: 20px;">State:</label>';
		echo '<input type="text" id="pdesigns_fund_raising_event_location_state" name="pdesigns_fund_raising_event_location_state" value="' . esc_attr( $fr_event->location_state ) . '" /><br>';

		echo '<label for="pdesigns_fund_raising_event_location_zip" style="margin-right: 20px;">Zip:</label>';
		echo '<input type="text" id="pdesigns_fund_raising_event_location_zip" name="pdesigns_fund_raising_event_location_zip" value="' . esc_attr( $fr_event->location_zip ) . '" />';
		echo '</div>';

		echo '</div><div class="clear"></div>';
		echo '</div><!--end row-->';

		echo '<hr>';

		echo '<div>';
		echo '<div style="float: left;text-align: left;width: 100%;margin: 0">';

		//additional html
		echo '<label for="pdesigns_fund_raising_event_html">Additional HTML:</label></br>';
		echo '<textarea style="width: 100%;" id="pdesigns_fund_raising_event_html" name="pdesigns_fund_raising_event_html">' . esc_attr( $fr_event->add_html ) . '</textarea>';

		echo '</div><div class="clear"></div>';
		echo '</div>';

	}

	//pre title meta box
	function pretitle_meta_box( $post ){
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pdesigns_pretitle', 'pdesigns_pretitle_nonce' );

		$value = get_post_meta( $post->ID, 'pdesigns_pretitle', true );

		echo '<label for="pdesigns_pretitle">Insert this text before the page title</label> ';
		echo '<input type="text" id="pdesigns_pretitle" name="pdesigns_pretitle" value="' . esc_attr( $value ) . '" />';
	}

	//subtitle meta box
	function subtitle_meta_box( $post ){
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pdesigns_subtitle', 'pdesigns_subtitle_nonce' );

		$value = get_post_meta( $post->ID, 'pdesigns_subtitle', true );

		echo '<label for="pdesigns_subtitle">Insert this text as a subtitle to the page</label> ';
		echo '<input type="text" id="pdesigns_subtitle" name="pdesigns_subtitle" value="' . esc_attr( $value ) . '" />';
	}

	//QR code generator
	function qr_code_meta_box( $post ){

		//check if editing a post / page
		if( isset( $_GET['post'] ) ){
			//get the page permalink
			$link = get_permalink( $post->ID );

			//get the display options
			$options = get_post_meta( $post->ID, 'pdesigns_qr_options', true );

			//decode the options
			$options = json_decode( $options, TRUE );
			
			//check if dimensions are set
			if( !empty( $options['dimensions'] ) ){
				$dimensions = 'value="' . $options['dimensions'] . '"';
			} else {
				$dimensions = null;
			}

			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'pdesigns_qr_code', 'pdesigns_qr_code_nonce' );

			echo '<p>Display QR code on page:';
			echo '<input style="margin-left: 15px;" type="checkbox" name="display_qr_code" ' . checked('true', $options['display'], FALSE) . '></p>';
			echo '<div class="admin-qr-dimensions">';
			echo '<label>Display Width / Height:</label>';
			echo '<input name="qr_dims" ' . $dimensions . ' placeholder="Default: 200">PX';
			echo '</div>';
			echo '<p>QR Code Preview</p>';
			echo '<div id="admin-qr-display"></div>';
			echo '<script>jQuery( document ).ready(function($){ $("#admin-qr-display").qrcode("' . $link . '");})</script>';
			echo '<div id="view-qr-printable"><a href="/wp-content/themes/sparkling-child/classes/qr_printable.class.php?link=' . base64_encode( $link ) . '&title=' . base64_encode( $post->post_title ) . '" target="_blank" class="button button-primary">Click here to display printable version</a></div>';
		} else {
			echo '<p>you must first save this post / page before this option will be enabled</p>';
		}

			
	}

?>