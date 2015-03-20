<?php

//registered event meta box
	function wpsc_product_meta_box( $post ){

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pdesigns_registered_event', 'pdesigns_registered_event_nonce' );

		$reg_event = get_post_meta( $post->ID, 'pdesigns_registered_event_info', true );
		$reg_event = json_decode( $reg_event );

		//open row
		echo '<div class="normal-meta-container">';

		//display order
		echo '<p class="normal-meta-title">Display Order:</p>';
		echo '<input type="number" id="reg_event_display_order" name="pdesigns_registered_event_display_order" class="inline-field" value="' . esc_attr( $reg_event->display_order ) . '">';

		echo '</div>';
		echo '<div class="normal-meta-container">';

		echo '<p class="normal-meta-title">User Entered Pricing:</p>';
		
		echo '<div class="normal-meta-elements wide">';
		echo '<p class="note">Selecting this option will prompt the user to enter a value for the price. This will also disable the Early Registration and Pricing fields </p>';
		echo '<label for="pdesigns_variable_price" class="inline-label">Variable Pricing:</label>';		
		echo '<input type="checkbox" id="pdesigns_variable_price" name="pdesigns_variable_price" ' . checked( $reg_event->variable_price, 'true', false ) . '/><br>';
		echo '</div><div class="clear"></div>';

		echo '</div>';
		echo '<div class="normal-meta-container">';

		//check for variable pricing
		if( $reg_event->variable_price == 'true' ){
			$var_field_disable = ' disabled ';
		} else {
			$var_field_disable = null;
		}

		echo '<p class="normal-meta-title">Pricing:</p>';

		//early reg info
		echo '<div class="normal-meta-elements">';

		echo '<label for="pdesigns_registered_reg_price" class="block-label">Normal Registration Price:</label>';
		echo '$ <input type="number" id="pdesigns_registered_reg_price" name="pdesigns_registered_reg_price" value="' . esc_attr( $reg_event->reg_price ) . '" placeholder="ex: 10.00" ' . $var_field_disable . '/><br>';
		
		echo '<label for="pdesigns_registered_event_show_early_price" class="block-label">Show Early Price After Deadline:</label> ';
		echo '<input type="checkbox" id="pdesigns_registered_event_show_early_price" name="pdesigns_registered_event_show_early_price" ' . checked('Y', $reg_event->show_early_price, FALSE ) . '" ' . $var_field_disable . '/>';

		echo '</div>';
		
		//reg prices
		echo '<div class="normal-meta-elements">';
		echo '<label for="pdesigns_registered_early_price" class="block-label">Early Registration Price:</label>';
		echo '$ <input type="number" id="pdesigns_registered_early_price" name="pdesigns_registered_early_price" value="' . esc_attr( $reg_event->early_price ) . '" placeholder="ex: 10.00" ' . $var_field_disable . '/><br>';

		echo '<label for="pdesigns_registered_event_early_deadline" class="block-label">Early Registration Deadline:</label> ';
		echo '<input type="date" id="pdesigns_registered_event_early_deadline" name="pdesigns_registered_event_early_deadline" value="' . esc_attr( $reg_event->early_deadline ) . '" ' . $var_field_disable . '/><br>';

		echo '</div>';

		//close row
		echo '<div class="clear"></div></div>';


		//open row
		echo '<div class="normal-meta-container">';
		echo '<p class="normal-meta-title">Item Options:</p>';

		//early reg info
		echo '<div class="normal-meta-elements wide">';
		echo '<input type="text" id="reg_event_add_option" class="inline-field" placeholder="Option Name">';
		echo '$ <input type="text" id="reg_event_add_option_price" class="inline-field" placeholder="Price">';
		echo '<div id="reg_add_option_button" class="button button-primary">Add Option</div><br>';

		//display registration events
		echo '<div id="reg_options_display">';
		echo '<p style="font-size: 14px; margin: 0 0 8px 0;">Configured Options</p>';

		//check for exitsing reg items
		$optionsInput = '';
		if( empty( $reg_event->item_options ) ){

			echo '<p id="no-options-display">No Options Selected</p>';

		} else {			

			//cycle through reg options
			foreach( $reg_event->item_options as $option ){
				echo '<div class="reg-option-element">
						<span class="remove-reg-option">X</span>
						<p class="option-name">' . $option->name . '</p>
						<p class="option-price">' . $option->price . '</p>
					</div>';

				//create option array for input box
				$optionsInput .= $option->name . ',' . $option->price . ';';
			}
		}

		echo '<div class="clear"></div></div>';
		echo '</div>';

		//close row
		echo '<div class="clear"></div></div>';
		echo '<input type="hidden" id="pdesigns_registered_event_item_options" name="pdesigns_registered_event_item_options" value="' . $optionsInput . '" />';

		//open row
		echo '<div class="normal-meta-container">';
		echo '<p class="normal-meta-title">Pop Up Description</p>';

		//post linking
		echo '<div class="normal-meta-elements wide">';
		echo '<p>If you have created a post that contains the information you would like to display please select it here.</p>';
		echo '<select id="reg_event_post_link" name="pdesigns_registered_event_post_link">';
		echo '<option value="">Select a Post</option>';

		//get the posts
		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'post_title',
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'suppress_filters' => true );

		$post_links = get_posts( $args );
		foreach( $post_links as $key => $post_link ){
			echo '<option value="' . $post_link->ID . '"' . selected($reg_event->post_link, $post_link->ID, false) . '>' . $post_link->post_title . '</option>';
		}
		echo '</select>';

		echo '</div><div class="clear"></div>';
		//close row
		echo '<div class="clear"></div></div>';
	}

	function wpsc_save_product_meta_box( $post_id ){

		// Check if our nonce is set.
		if ( ! isset( $_POST['pdesigns_registered_event_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['pdesigns_registered_event_nonce'], 'pdesigns_registered_event' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		//combine user input into array
		$my_data['early_deadline']	= $_POST['pdesigns_registered_event_early_deadline'];
		$my_data['early_price']		= $_POST['pdesigns_registered_early_price'];
		$my_data['reg_price'] 		= $_POST['pdesigns_registered_reg_price'];
		$my_data['display_order'] 	= $_POST['pdesigns_registered_event_display_order'];
		$my_data['post_link'] 	= $_POST['pdesigns_registered_event_post_link'];

		//display the early price after the deadline
		if( !empty( $_POST['pdesigns_registered_event_show_early_price'] ) ){
			$my_data['show_early_price'] = 'Y';
		} else {
			$my_data['show_early_price'] = 'N';
		}

		//variable pricing
		if( !empty( $_POST['pdesigns_variable_price'] ) ){
			$my_data['variable_price'] = 'true';
		} else {
			$my_data['variable_price'] = 'false';
		}

		//create array for item options
		$item_options = array();

		//explode item options to each element
		$submitted_options = explode(';', $_POST['pdesigns_registered_event_item_options'] );

		//cycle through item options to create array
		foreach( $submitted_options as $option ){

			//corrects issue of last element
			if( !empty( $option ) ){

				//explode the individual elements
				$option = explode(',', $option );
				array_push( $item_options, array( 'name' => $option[0], 'price' => $option[1] ) );
			}
		}

		//add item options to data aset
		$my_data['item_options'] = $item_options;

		//encode data
		$my_data = json_encode( $my_data );

		//Update the meta field in the database.
		update_post_meta( $post_id, 'pdesigns_registered_event_info', $my_data );
	}
	
?>