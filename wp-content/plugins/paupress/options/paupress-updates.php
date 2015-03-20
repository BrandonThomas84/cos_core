<?php 

/**
 * Define the Versions
 *
 * @since 1.0.0
 */
function paupress_versions() {
	
	$paupress_versions = array(
		'0.9.2' => 'paupress_update_v_0_9_2', 
		'0.9.4' => 'paupress_update_v_0_9_4', 
		'1.0.0' => 'paupress_update_v_1_0_0', 
		);
		
	return $paupress_versions;

}

function paupress_update_v_0_9_2() {

	$um = array( '1', '2', '3', '4' );
	foreach ( $um as $m ) {
		
		$meta = paupress_get_option( 'address_' . $m );
		if ( false != $meta ) {
			$address_keys = array();

			foreach ( $meta['options']['choices'] as $k => $v ) {
				if ( false !== strpos( $v, 'address_type' ) ) {
					$address_keys[] = $v;
					
					$loc = '_'.$m;
					$loc_tag = strtoupper( substr( $m, 0, 1 ) );
					
					$address = array();
					// ADDRESS RECIPIENT
					$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_recipient'.$loc, 'tag' => 'ADREP'.$loc_tag, 'name' => __( 'Address Recipient', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'group' => 'address'.$loc, 'help' => __( 'The person receiving deliveries at this address.', 'paupress' ) );
					// ADDRESS ORGANIZATION
					$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_organization'.$loc, 'tag' => 'ADORG'.$loc_tag, 'name' => __( 'Address Organization', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'group' => 'address'.$loc, 'help' => __( 'The company or organization at this address.', 'paupress' ) );
					
					foreach ( $address as $key => $value ) {		
					
						if ( false != get_option( 'paupress_'.$value['meta_key'] ) )
							continue;
						
						// SET A NAMED VALUE FOR THE PAUPRESS_USER_META ARRAY AND 
						$address_keys[] = 'paupress_'.$value['meta_key'];
						// ADD THE OPTION
						add_option( 'paupress_'.$value['meta_key'], $value );		
						
					}
					
				} else {
					$address_keys[] = $v;
				}
			}
			$meta['options']['choices'] = $address_keys;
			update_option( paupress_get_option( 'address_' . $m, true ), $meta );
			
		}
			
	}

	return 'success!';
}


function paupress_update_v_0_9_4() {
	
	// UPDATE RESERVED OPTIONS TO UNDERSCORE PREFIXES
	$optcon = array( 
					'paupress_post_types' => '_paupress_post_types', 
					'paupress_error_log' => '_paupress_error_log', 
					'paupress_capabilities' => '_paupress_capabilities', 
					'paupress_user_access_option' => '_paupress_access', 
					'paupress_user_public_option' => '_paupress_public', 
					'paupress_taxonomy_display' => '_paupress_taxonomy_display', 
					'paupress_basic_user_display' => '_paupress_user_display', 
					'paupress_compatability_mode' => '_paupress_compatability_mode', 
					'paupress_user_meta' => '_paupress_user_meta', 
					'paupress_forms' => '_paupress_user_forms', 
					'paupress_user_queries' => '_paupress_user_queries', 
					'paupress_user_imports' => '_paupress_user_imports', 
					'paupress_user_exports' => '_paupress_user_exports', 
					'paupress_reserved_fields' => '_paupress_reserved_fields', 
					'paupress_wp_taxonomies' => '_paupress_wp_taxonomies', 
					'paupress_default_paupay_optional_fields' => '_paupress_form_default_paupay_optional_fields', 
					'paupress_default_paumail_signup_form' => '_paupress_form_default_paumail_signup', 
	);
	foreach ( $optcon as $k => $v ) {
		
		// ESCAPE IF WE'VE ALREADY DONE THE UPDATE
		if ( false !== get_option( $v ) )
			continue;
			
		if ( 'paupress_forms' == $k ) {
			$forms = get_option( $k );
			if ( false === $forms )
				continue;
				
			$formct = count( $forms );
			$formcg = 0;
			foreach ( $forms as $fk => $fv ) {
				// UPDATE
				$cv = get_option( 'paupress_'.$fk );
				$new = update_option( '_paupress_form_'.$fk, $cv );
				// VERIFY
				if ( !$new )
					continue;
					
				if ( $cv !== get_option( '_paupress_form_'.$fk ) )
					continue;
					
				// DELETE
				delete_option( 'paupress_'.$fk );
				$formcg++;
			}
			
			if ( $formct != $formcg )
				continue;
				
		} else if ( 'paupress_user_queries' == $k ) {
			$queries = get_option( $k );
			if ( false === $queries )
				continue;
				
			$querct = count( $queries );
			$quercg = 0;
			foreach ( $queries as $qk => $qv ) {
				// UPDATE
				$cv = get_option( $qv['query'] );
				$new = update_option( '_'.$qv['query'], $cv );
				// VERIFY
				if ( !$new )
					continue;
					
				if ( $cv !== get_option( '_'.$qv['query'] ) )
					continue;
					
				// DELETE
				delete_option( $qv['query'] );
				$qv['query'] = '_'.$qv['query'];
				$new_queries[$qk] = $qv;
				$quercg++;
			}
			
			if ( $querct != $quercg )
				continue;
				
			update_option( $k, $new_queries );
		}
			
		// UPDATE
		$new = update_option( $v, get_option( $k ) );
		
		// VERIFY
		if ( !$new )
			continue;
			
		if ( get_option( $v ) !== get_option( $k ) )
			continue;
			
		// DELETE
		delete_option( $k );
						
	}
	
	// UPDATE USER FIELDS WITH ADDITIONAL DEFAULTS
	$field = array();
	$field_keys = array();
	
	// USERID -- WILL NOT BY SYNCED
	$field[] = array( 'source' => 'wpr', 'meta_key' => 'ID', 'tag' => '', 'name' => __( 'User ID', 'paupress' ), 'options' => array( 'admin' => true, 'user' => false, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '', 'column' => 'section_account_information', 'section' => 'account_information' );
	// USERDATE -- WILL NOT BY SYNCED
	$field[] = array( 'source' => 'wpr', 'meta_key' => 'user_registered', 'tag' => '', 'name' => __( 'Date Registered', 'paupress' ), 'options' => array( 'admin' => true, 'user' => false, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '', 'column' => 'section_account_information', 'section' => 'account_information' );
	
	foreach ( $field as $key => $value ) {		
	
		if ( false != get_option( 'paupress_'.$value['meta_key'] ) )
			continue;
		
		// SET A NAMED VALUE FOR THE PAUPRESS_USER_META ARRAY AND 
		$field_keys[] = $value['meta_key'];
		// ADD THE OPTION
		add_option( 'paupress_'.$value['meta_key'], $value );		
		
	}
	
	if ( !empty( $field_keys ) ) {
		$umo = get_option( '_paupress_user_meta' );
		foreach ( $umo as $uk => $uv ) {
			// COLUMNS
			foreach ( $uv as $suk => $suv ) {
				if ( 'paupress_account_information' == $suv ) {
					$acct = get_option( $suv );
					foreach ( $field_keys as $fk => $fv ) 
						$acct['options']['choices'][] = $fv;
					update_option( $suv, $acct );
					$aok = true;
				}
			}
		}
		// IF NO JOY, PUT IT IN COLUMN 3
		if ( !isset( $aok ) ) {
			foreach ( $field_keys as $fk => $fv ) 
				$umo['column_3'][] = 'paupress_'.$fv;
			
			update_option( '_paupress_user_meta', $umo );
		}
	}
	
	// LASTLY, LET'S ADD A DEFAULT CONTACT FORM
	$default_form = get_option( '_paupress_form_contact_form' );
	if ( false === $default_form )
		$contact_form = paupress_form_create();
	
	$forms_opt = get_option( '_paupress_user_forms' );
	if ( false === $forms_opt ) {
		add_option( '_paupress_user_forms', $contact_form );
	} else if ( isset( $contact_form ) ) {
		foreach ( $contact_form as $cfk => $cfv )
			$forms_opt[$cfk] = $cfv;
		
		update_option( '_paupress_user_forms', $forms_opt );
	}

	return 'success!';
}


function paupress_update_v_1_0_0() {
	
	global $wpdb;
	$q_query = $wpdb->get_col( "SELECT $wpdb->posts.ID from $wpdb->posts where post_type = 'pp_item'" );
	$allct = 0;
	$oldct = 0;
	$newct = 0;
	global $post;
	foreach ( $q_query as $id ) {
		$allct++;
		if ( false == get_post_meta( $id, '_pp_item_quantity', true ) ) {
			$oldct++;
			if ( false != update_post_meta( $id, '_pp_item_quantity', (int) 1 ) )
				$newct++;
		}
	}
	
	return "All is $allct and Old is $oldct and now, new is $newct";
	
}

?>