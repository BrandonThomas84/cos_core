<?php

/**
 * Runs on activation to install options.
 *
 * @since 0.0.0
 */
function paupress_activate() {

	// SET LOCAL VARIABLES
	$dbv = get_option( '_paupress_version' );
	
	if ( false === $dbv )
		$dbv = get_option( 'paupress_version' );
	
	// FIRST, CHECK TO SEE IF THIS IS A FIRST INSTALL
	if ( false === $dbv ) {
		
		include_once( 'paupress-updates.php' );
		
	    $paupress_defaults = array(
	    	'_paupress_version' => PAUPRESS_VER,
	    	'_paupress_user_meta' => paupress_default_user_meta(),
	    	'paupress_pp_public' => paupress_pp_public(), 
	    	'paupress_pp_primary' => paupress_pp_primary(), 
	    	'paupress_pp_billing' => paupress_pp_billing(), 
	    	'paupress_pp_shipping' => paupress_pp_shipping(), 
	    	'_paupress_user_forms' => paupress_form_create(), 
	    );
	    
	    foreach( $paupress_defaults as $key => $value ) {
	    	add_option( $key, $value );
	    }
	    
	// ALTERNATIVELY, COMPARE THE DATABASE AND SCRIPT VERSION OF THE PLUGIN
	} else if ( $dbv != PAUPRESS_VER ) {
		
		include_once( 'paupress-updates.php' );
		$paupress_versions = paupress_versions();
		$update_log = array();
		
		foreach( $paupress_versions as $key => $value ) {
			if ( $key > $dbv ) {
				$update_log[$key] = call_user_func( $value );
				update_option( '_paupress_version', $key );
				update_option( 'paupress_version', $key );
			}
		}
		
		// UPDATE ANY ADMIN MESSAGES
		update_option( '_paupress_admin_messages', $update_log );
		
		// VERIFY THAT WE'RE ON THE CURRENT VERSION AS DB UPDATES ARE INFREQUENT
		$udbv = get_option( '_paupress_version' );
		
		if ( $udbv != PAUPRESS_VER )
			update_option( '_paupress_version', PAUPRESS_VER );	
	}
    
}


/**
 * Define the default User Meta Fields. This will include a reference to the default WordPress fields as well as the default PauPress fields. Doesn't really have parameters but there is a reference for the array below.
 *
 * @since 0.0.0
 *
 * @param 'source' => 
 * @param 'meta_key' =>
 * @param 'tag' =>
 * @param 'name' =>
 * @param 'options' => array( 
 * @param 	'admin' => true, 
 * @param 	'user' => true, 
 * @param 	'reports' => true, 
 * @param 	'public' => false, 
 * @param 	'unique' => false. for imports, allows you to match on this field
 * @param 	'req' => false, 
 * @param 	'field_type' => 'multitext', 
 * @param 	'choices' => array()
 * @param )
 *
 */
function paupress_default_user_meta() {
	
	return array(		
	
		'column_1' => paupress_process_defaults( paupress_column_one(), 'column_1', '' ), 
		'column_2' => paupress_process_defaults( paupress_column_two(), 'column_2', '' ), 
	
	);
	
}

function paupress_process_defaults( $def_arr, $column, $section = false ) {

	foreach ( $def_arr as $key => $value ) {		
		
		// SET THE POSTIONS
		$value['column'] = $column;
		$value['section'] = $section;
		
		// SET A NAMED VALUE FOR THE PAUPRESS_USER_META ARRAY AND 
		$ret_arr[] = 'paupress_'.$value['meta_key'];
		// ADD THE OPTION
		add_option( 'paupress_'.$value['meta_key'], $value );		
		
	}
	
	return $ret_arr;

}


function paupress_column_two() {

	return array(		
	
		// ACCOUNT INFORMATION -- WILL NOT BE SYNCED DUE TO FIELD TYPE CONSTRAINT
		array( 'source' => 'user', 'meta_key' => 'account_information', 'tag' => '', 'name' => __( 'Account Information', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => true, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'section', 'choices' => paupress_process_defaults( paupress_account_information_fields(), 'section_account_information', 'account_information' ) ), 'help' => '' ),
		
		// PREFERENCES -- WILL NOT BE SYNCED DUE TO FIELD TYPE CONSTRAINT
		array( 'source' => 'user', 'meta_key' => 'preferences', 'tag' => '', 'name' => __( 'Preferences', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'section', 'choices' => paupress_process_defaults( paupress_preferences_fields(), 'section_preferences', 'preferences' ) ), 'help' => '' ),
	
		
	);

}


function paupress_column_one() {

	return array(		
	
		// BASIC INFORMATION -- WILL NOT BE SYNCED DUE TO FIELD TYPE CONSTRAINT
		array( 'source' => 'user', 'meta_key' => 'basic_information', 'tag' => '', 'name' => __( 'Basic Information', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => true, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'section', 'choices' => paupress_process_defaults( paupress_basic_information_fields(), 'section_basic_information', 'basic_information' ) ), 'help' => '' ),
	
		// CONTACT INFORMATION -- WILL NOT BE SYNCED DUE TO FIELD TYPE CONSTRAINT
		array( 'source' => 'user', 'meta_key' => 'contact_information', 'tag' => '', 'name' => __( 'Contact Information', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'section', 'choices' => paupress_process_defaults( paupress_contact_information_fields(), 'section_contact_information', 'contact_information' ) ), 'help' => '' ),
		
	);

}
	
function paupress_account_information_fields() {
	
	return array(		
		
		// WORDPRESS RESERVED DEFAULTS
		// EMAIL
		array( 'source' => 'wpr', 'meta_key' => 'email', 'tag' => 'EMAIL', 'name' => __( 'Email', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => true, 'reports' => true, 'public' => false, 'req' => true, 'unique' => true, 'field_type' => 'text', 'choices' => array() ), 'help' => '' ),
		// USERNAME -- WILL NOT BY SYNCED
		array( 'source' => 'wpr', 'meta_key' => 'user_login', 'tag' => '', 'name' => __( 'Username', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '' ),
		// USERID -- WILL NOT BY SYNCED
		array( 'source' => 'wpr', 'meta_key' => 'ID', 'tag' => '', 'name' => __( 'User ID', 'paupress' ), 'options' => array( 'admin' => true, 'user' => false, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '' ),
		// USERDATE -- WILL NOT BY SYNCED
		array( 'source' => 'wpr', 'meta_key' => 'user_registered', 'tag' => '', 'name' => __( 'Date Registered', 'paupress' ), 'options' => array( 'admin' => true, 'user' => false, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '' ),
		// TYPE OF USER
		array( 'source' => 'user', 'meta_key' => 'user_type', 'tag' => 'TYPE', 'name' => __( 'User Type', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'radio', 'choices' => array( __( 'person', 'paupress' ) => __( 'Person', 'paupress' ), __( 'organization', 'paupress' ) => __( 'Organization', 'paupress' ), __( 'household', 'paupress' ) => __( 'Household', 'paupress' ) ) ), 'help' => '' ),
		// ROLE -- WILL NOT BY SYNCED
		array( 'source' => 'wpr', 'meta_key' => 'role', 'tag' => '', 'name' => __( 'Role', 'paupress' ), 'options' => array( 'admin' => true, 'user' => false, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'select', 'choices' => array() ), 'help' => '' ),
		// DISPLAY NAME -- WILL NOT BY SYNCED
		array( 'source' => 'wpr', 'meta_key' => 'display_name', 'tag' => '', 'name' => __( 'Display Name', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'select', 'choices' => array() ), 'help' => '' ),
		// PASSWORD -- WILL NOT BY SYNCED
		array( 'source' => 'wpr', 'meta_key' => 'pass', 'tag' => '', 'name' => __( 'Password', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => true, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'password', 'choices' => array( '1', '2' ) ), 'help' => '' ),
		
	);

}

function paupress_basic_information_fields() {
	
	return array(		
		
		// TITLE
		array( 'source' => 'user', 'meta_key' => 'title', 'tag' => 'TITLE', 'name' => __( 'Title', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '' ),
		// FIRST NAME
		array( 'source' => 'wp', 'meta_key' => 'first_name', 'tag' => 'FNAME', 'name' => __( 'First Name', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => true, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '' ),
		// MIDDLE NAME
		array( 'source' => 'user', 'meta_key' => 'middle_name', 'tag' => 'MNAME', 'name' => __( 'Middle Name', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '' ),
		// LAST NAME
		array( 'source' => 'wp', 'meta_key' => 'last_name', 'tag' => 'LNAME', 'name' => __( 'Last Name', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => true, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '' ),
		// ORGANIZATION
		array( 'source' => 'user', 'meta_key' => 'organization', 'tag' => 'ORG', 'name' => __( 'Organization', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => '' ),
		// NICKNAME -- WILL NOT BY SYNCED
		array( 'source' => 'wp', 'meta_key' => 'nickname', 'tag' => '', 'name' => __( 'Nickname', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => 'an alternate name to use' ),
		// BIO -- WILL NOT BY SYNCED
		array( 'source' => 'wp', 'meta_key' => 'description', 'tag' => 'DESC', 'name' => __( 'Biography', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'textarea', 'choices' => array() ) ),
		
	);

}



function paupress_contact_information_fields() {
	
	return array(		
		
		// WEBSITE
		array( 'source' => 'wpr', 'meta_key' => 'url', 'tag' => 'WEB', 'name' => __( 'Website', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'help' => 'http://www.example.com' ),
		// TELEPHONE -- WILL NOT BE SYNCED DUE TO FIELD TYPE CONSTRAINT
		array( 'source' => 'user', 'meta_key' => 'telephone', 'tag' => 'TEL', 'name' => __( 'Telephone', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'multitext', 'choices' => array( __( 'home', 'paupress' ) => __( 'Home', 'paupress' ), __( 'work', 'paupress' ) => __( 'Work', 'paupress' ), __( 'mobile', 'paupress' ) => __( 'Mobile', 'paupress' ), __( 'other', 'paupress' ) => __( 'Other', 'paupress' ) ) ), 'help' => '' ),
		// ADDITIONAL EMAIL -- WILL NOT BE SYNCED DUE TO FIELD TYPE CONSTRAINT
		array( 'source' => 'user', 'meta_key' => 'additional_email', 'tag' => '', 'name' => __( 'Additional Email Addresses', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'multitext', 'choices' => array( __( 'home', 'paupress' ) => __( 'Home', 'paupress' ), __( 'work', 'paupress' ) => __( 'Work', 'paupress' ), __( 'other', 'paupress' ) => __( 'Other', 'paupress' ) ) ), 'help' => '' ),
		// AIM -- WILL NOT BY SYNCED
		array( 'source' => 'wp', 'meta_key' => 'aim', 'tag' => 'AIM', 'name' => __( 'AIM', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ) ),
		// YIM -- WILL NOT BY SYNCED
		array( 'source' => 'wp', 'meta_key' => 'yim', 'tag' => 'YIM', 'name' => __( 'Yahoo IM', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ) ),
		// JABBER -- WILL NOT BY SYNCED
		array( 'source' => 'wp', 'meta_key' => 'jabber', 'tag' => 'JABBER', 'name' => __( 'Jabber / Google Talk', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ) ),
		
		// ADDRESS ONE
		array( 'source' => 'paupress', 'meta_key' => 'address_1', 'tag' => '', 'name' => __( 'Address One', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'group', 'choices' => paupress_default_address( '1' ) ), 'group_type' => 'address', 'help' => __( 'Click to expand', 'paupress' ) ),
		// ADDRESS TWO
		array( 'source' => 'paupress', 'meta_key' => 'address_2', 'tag' => '', 'name' => __( 'Address Two', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'group', 'choices' => paupress_default_address( '2' ) ), 'group_type' => 'address', 'help' => __( 'Click to expand', 'paupress' ) ),
		// ADDRESS THREE
		array( 'source' => 'paupress', 'meta_key' => 'address_3', 'tag' => '', 'name' => __( 'Address Three', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'group', 'choices' => paupress_default_address( '3' ) ), 'group_type' => 'address', 'help' => __( 'Click to expand', 'paupress' ) ),
		
	);

}

function paupress_preferences_fields() {
	
	return array(		
		
		// WORDPRESS UNRESERVED DEFAULTS
		// ADMIN BAR FRONT -- WILL NOT BY SYNCED
		array( 'source' => 'wp', 'meta_key' => 'show_admin_bar_front', 'tag' => '', 'name' => __( 'Show Admin Bar: when viewing the site', 'paupress' ), 'options' => array( 'admin' => true, 'user' => false, 'signup' => false, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'checkbox', 'choices' => array( 'true' ) ) ),
		
		// KEYBOARD SHORTCUTS -- WILL NOT BE SYNCED
		array( 'source' => 'wp', 'meta_key' => 'comment_shortcuts', 'tag' => '', 'name' => __( 'Keyboard Shortcuts', 'paupress' ), 'options' => array( 'admin' => true, 'user' => false, 'signup' => false, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'checkbox', 'choices' => array( 'false' ) ) ),
		
		// EMAIL PREFERENCES -- WILL NOT BE SYNCED
		array( 'source' => 'paupress', 'meta_key' => 'pp_subscription', 'tag' => '', 'name' => __( 'Subscribe to email updates', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'checkbox', 'choices' => array( 'false' ) ), 'help' => false ),
		
		// CONTACT PREFERENCES -- WILL NOT BE SYNCED
		array( 'source' => 'paupress', 'meta_key' => 'pp_contact', 'tag' => '', 'name' => __( 'Allow others to contact me', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'checkbox', 'choices' => array( 'false' ) ), 'help' => false ),
		
	);

}

function paupress_default_address( $location ) {
	
	// APPEND THE LOCATION
	$loc = '_'.$location;
	$loc_tag = strtoupper( substr( $location, 0, 1 ) );
	
	// ADDRESS TYPE
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_type'.$loc, 'tag' => 'ADDTYPE'.$loc_tag, 'name' => __( 'Address Location', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'select', 'choices' => array( __( 'home', 'paupress' ) => __( 'Home', 'paupress' ), __( 'work', 'paupress' ) => __( 'Work', 'paupress' ), __( 'other', 'paupress' ) => __( 'Other', 'paupress' ) ) ), 'group' => 'address'.$loc, 'help' => '' );
	// ADDRESS RECIPIENT
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_recipient'.$loc, 'tag' => 'ADREP'.$loc_tag, 'name' => __( 'Address Recipient', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'group' => 'address'.$loc, 'help' => __( 'The person receiving deliveries at this address.', 'paupress' ) );
	// ADDRESS ORGANIZATION
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_organization'.$loc, 'tag' => 'ADORG'.$loc_tag, 'name' => __( 'Address Organization', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'group' => 'address'.$loc, 'help' => __( 'The company or organization at this address.', 'paupress' ) );
	// ADDRESS LINE ONE
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_one'.$loc, 'tag' => 'ADD1'.$loc_tag, 'name' => __( 'Address Line One', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'group' => 'address'.$loc, 'help' => __( 'Typically, this is a street address', 'paupress' ) );
	// ADDRESS LINE TWO
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_two'.$loc, 'tag' => 'ADD2'.$loc_tag, 'name' => __( 'Address Line Two', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'group' => 'address'.$loc, 'help' => __( 'Typically, this is a unit, suite or apartment', 'paupress' ) );
	// ADDRESS LINE THREE
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_three'.$loc, 'tag' => 'ADD3'.$loc_tag, 'name' => __( 'Address Line Three', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'group' => 'address'.$loc, 'help' => __( 'Typically, this is the title of a business or in-care-of notation', 'paupress' ) );
	// CITY
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_city'.$loc, 'tag' => 'CITY'.$loc_tag, 'name' => __( 'City', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'group' => 'address'.$loc );
	// STATE OR PROVINCE
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_state'.$loc, 'tag' => 'STATE'.$loc_tag, 'name' => __( 'State or Province', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'select', 'choices' => 'paupress_helper_state' ), 'group' => 'address'.$loc );
	// COUNTRY
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_country'.$loc, 'tag' => 'COUNTRY'.$loc_tag, 'name' => __( 'Country', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'select', 'choices' => 'paupress_helper_country' ), 'group' => 'address'.$loc );
	// POSTAL CODE
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_postal_code'.$loc, 'tag' => 'POSTAL'.$loc_tag, 'name' => __( 'Postal Code', 'paupress' ), 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'text', 'choices' => array() ), 'group' => 'address'.$loc );
	// LATITUDE -- HIDDEN BY DEFAULT -- WILL NOT BE SYNCED
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_latitude'.$loc, 'tag' => '', 'name' => __( 'Latitude', 'paupress' ), 'options' => array( 'admin' => false, 'user' => false, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'hidden', 'choices' => array() ), 'group' => 'address'.$loc );
	// LONGITUDE -- HIDDEN BY DEFAULT -- WILL NOT BE SYNCED
	$address[] = array( 'source' => 'paupress', 'meta_key' => 'address_longitude'.$loc, 'tag' => '', 'name' => __( 'Longitude', 'paupress' ), 'options' => array( 'admin' => false, 'user' => false, 'signup' => false, 'reports' => true, 'public' => false, 'req' => false, 'field_type' => 'hidden', 'choices' => array() ), 'group' => 'address'.$loc );
	
	// SET THE MASTER USER META ARRAY TO BE USED FOR SORTING
	$address_keys = array();
	
	foreach ( $address as $key => $value ) {		

		// SET A NAMED VALUE FOR THE PAUPRESS_USER_META ARRAY AND 
		$address_keys[] = 'paupress_'.$value['meta_key'];
		// ADD THE OPTION
		add_option( 'paupress_'.$value['meta_key'], $value );		
		
	}
	
	return $address_keys;

}


function paupress_pp_public() {
		
	return array( 'source' => 'paupress', 'meta_key' => 'pp_public', 'tag' => '', 'name' => __( 'Public Option', 'paupress' ), 'options' => array( 'admin' => false, 'user' => false, 'signup' => false, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'plugin', 'choices' => 'paupress_public_status' ), 'help' => __( 'Use this to toggle the public status of all available fields.', 'paupress' ) );
			
}

function paupress_pp_primary() {
		
	return array( 'source' => 'paupress', 'meta_key' => 'pp_primary', 'tag' => '', 'name' => 'Primary', 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'radio', 'choices' => array( array( 'label' => 'primary', 'value' => '' ) ) ), 'help' => '' );
			
}

function paupress_pp_billing() {
		
	return array( 'source' => 'paupress', 'meta_key' => 'pp_billing', 'tag' => '', 'name' => 'Billing', 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'radio', 'choices' => array( array( 'label' => 'billing', 'value' => '' ) ) ), 'help' => '' );
			
}

function paupress_pp_shipping() {
		
	return array( 'source' => 'paupress', 'meta_key' => 'pp_shipping', 'tag' => '', 'name' => 'Shipping', 'options' => array( 'admin' => true, 'user' => true, 'signup' => false, 'reports' => false, 'public' => false, 'req' => false, 'field_type' => 'radio', 'choices' => array( array( 'label' => 'shipping', 'value' => '' ) ) ), 'help' => '' );
			
}

function paupress_form_create() {
	
	$contact_form = array( 'source' => 'paupress', 'msg' => '', 'confirm' => '', 'column_1' => array( 'first_name', 'last_name', 'email' ), 'column_2' => array( '_pp_form_subject', '_pp_form_message', '_pp_form_cc' ) );
	add_option( '_paupress_form_contact_form', $contact_form );
	
	return array( 'contact_form' => __( 'Contact Form', 'paupress' ) );
	
}

?>