<?php



function paupress_user_settings() {
	
	return apply_filters( 'paupress_user_settings', array(
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_user_admin_title', 
								'name' => __( 'Administrator Controls', 'paupress' ), 
								'help' => '', 
								'description' => '', 
								'options' => array( 
													'field_type' => 'title',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_capabilities', 
								'name' => __( 'Grant database access to:', 'paupress' ), 
								'help' => __( 'Determine who can create, edit, and delete users from your system.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'plugin',
													'req' => false, 
													'public' => false, 
													'choices' => 'paupress_capabilities' 
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_user_user_title', 
								'name' => __( 'User Controls', 'paupress' ), 
								'help' => '', 
								'description' => '', 
								'options' => array( 
													'field_type' => 'title',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_access', 
								'name' => __( 'Prevent Subscribers from accessing the WordPress Admin Area', 'paupress' ), 
								'help' => __( 'This applies to the Subscriber role and restricts all operations to the front end of the site.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'checkbox',
													'req' => false, 
													'public' => false, 
													'choices' => false 
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_public', 
								'name' => __( 'Default display of public fields to "on"', 'paupress' ), 
								'help' => __( 'Public fields by default are hidden. If a user has not stated a preference on their profile, you can change the default to show their public fields.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'checkbox',
													'req' => false, 
													'public' => false, 
													'choices' => false 
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'paupanels_user_contact', 
								'name' => __( 'Default display of "contact me" to "on"', 'paupress' ), 
								'help' => __( 'Individual contact forms are disabled by default. If a user has not stated a preference on their profile, you can change the default to enable their contact form.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'checkbox',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_in_page_link', 
								'name' => __( 'Enable links to in-page profiles', 'paupress' ), 
								'help' => __( 'User profiles are automatically enabled via the panel system. This option, however, is an additional resource for users to link back directly to a page on your site containing their profile and public content.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'checkbox',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
				
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_fields_title', 
								'name' => __( 'Profile Controls', 'paupress' ), 
								'help' => '', 
								'description' => '', 
								'options' => array( 
													'field_type' => 'title',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_username_prefix', 
								'name' => __( 'Set defaults for randomized usernames', 'paupress' ), 
								'help' => __( 'Usernames are required by WordPress and they must be unique. You may not always get to choose them so, these defaults will stand-in.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'plugin',
													'req' => false, 
													'public' => false, 
													'choices' => 'paupress_username_prefix', 
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_taxonomy_display', 
								'name' => __( 'Alternate display of user taxonomies', 'paupress' ), 
								'help' => __( 'The select list option uses an advanced type-as-you-go option.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'select',
													'req' => false, 
													'public' => false, 
													'choices' => array( 
																		'checkboxes' => 'Checkboxes', 
																		'select' => 'Select List', 
													)
								) 
		) ),
		/*
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_user_display', 
								'name' => __( 'Basic Information Layout', 'paupress' ), 
								'help' => '', 
								'options' => array( 
													'field_type' => 'multiselect',
													'req' => false, 
													'public' => false, 
													'choices' => paupress_get_user_metadata( array( 'group_break' => 'true' ) ), 
								) 
		) ),
		*/
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_compatability_title', 
								'name' => __( 'WordPress Compatability Mode', 'paupress' ), 
								'help' => '', 
								'description' => __( 'Preferences to ease the transition.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'title',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_paupress_compatability_mode', 
								'name' => __( 'Enable WordPress Default User View', 'paupress' ), 
								'help' => __( 'By default, PauPress replaces the WordPress default list view. You can re-enable it with this option.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'checkbox',
													'req' => false, 
													'public' => false, 
													'choices' => false 
								) 
		) ),
	
	));
	
}

function paupress_capabilities( $args = null ) {

	// SET THE DEFAULTS TO BE OVERRIDDEN AS DESIRED
	$defaults = array( 
					'fdata' => false, 
					'fvalue' => false, 
					'faction' => false, 
					'ftype' => false
				);
	
	// PARSE THE INCOMING ARGS
	$args = wp_parse_args( $args, $defaults );

	// EXTRACT THE VARIABLES
	extract( $args, EXTR_SKIP );

	if ( !isset( $wp_roles ) )
		$wp_roles = new WP_Roles();
		
	echo '<ul>';

	foreach ( $wp_roles->roles as $key => $val ) {
?>
		<li>
			<?php if ( 'administrator' == $key ) { ?>
			<a class="umtf admin on"><?php printf( __( '%1$s Enabled by default', 'paupress' ), $val['name'] ); ?></a>
			<?php } else { ?>
			<a class="umt admin <?php if ( !empty( $fvalue[$key] ) ) { echo 'on'; } else { echo 'off'; } ?>" title="admin_<?php echo 'paupress_' . $key; ?>"><input type="hidden" id="admin_<?php echo 'paupress_' . $key; ?>"  name="_pp_option[_paupress_capabilities][<?php echo $key; ?>]" value="<?php if ( !empty( $fvalue[$key] ) ) { echo $fvalue[$key]; } else { echo '0'; } ?>" /><?php echo $val['name']; ?></a>
			<?php } ?>
		</li>
<?php 
	}

	echo '</ul>';

}


function paupress_save_capabilities() {
	if ( empty( $_POST['_pp_option']['_paupress_capabilities'] ) )
		return false;
		
	foreach ( $_POST['_pp_option']['_paupress_capabilities'] as $key => $val ) {
		if ( 'administrator' != $key ) {
			if ( false != $val ) {
				paupress_role_capabilities( 'add', $key );
			} else {
				paupress_role_capabilities( 'remove', $key );
			}
		}		
	}
}


function paupress_username_prefix( $args = null ) {

	// SET THE DEFAULTS TO BE OVERRIDDEN AS DESIRED
	$defaults = array( 
					'fdata' => false, 
					'fvalue' => false, 
					'faction' => false, 
					'ftype' => false
				);
	
	// PARSE THE INCOMING ARGS
	$args = wp_parse_args( $args, $defaults );

	// EXTRACT THE VARIABLES
	extract( $args, EXTR_SKIP );
	
	echo '<input type="text" class="input-short" name="_pp_option[_paupress_username_prefix]" value="' . $fvalue . '" />' . paupress_random( array( 'compact' => true ) ) . '<br />';
	echo '<span class="example-text">' . sprintf( __( 'You may use this code %1$s for the Year or any text string as a prefix.', 'paupress' ), '%y%' ) . '</span>';
	
}