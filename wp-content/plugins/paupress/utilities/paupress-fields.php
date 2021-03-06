<?php

function paupress_get_option( $key, $ret_key = false ) {
	
	// DIFFERENTIATE WORDPRESS AND THIRD-PARTY DEFAULTS
	if ( 'paupress' != substr( $key, 0, 8 ) ) {
		$option_key = 'paupress_'.$key;
	} else {
		$option_key = $key;	
	}
	
	// IF $ret_key IS TRUE, JUST RETURN THE KEY
	if ( false != $ret_key )
		return $option_key;
		
	// ELSE, RETURN THE OPTIONS CONTENTS
	return get_option( $option_key );

}

function paupress_get_user_metadata_key( $key, $ret_val = false ) {
	
	$user_meta = paupress_get_option( $key ); 
	
	// DIFFERENTIATE WORDPRESS AND THIRD-PARTY DEFAULTS
	if ( 'paupress' == $user_meta['source'] ) {
		$option_key = 'paupress_'.$key;
	} else {
		$option_key = $key;	
	}
	
	return $option_key;
		
}

function paupress_get_user_meta( $user_id, $key, $single = false ) {
	
	$user_meta = paupress_get_option( $key ); 
	
	// DIFFERENTIATE WORDPRESS AND THIRD-PARTY DEFAULTS
	if ( 'paupress' == $user_meta['source'] ) {
		$option_key = 'paupress_'.$key;
	} else {
		$option_key = $key;	
	}
	
	return get_user_meta( $user_id, $option_key, $single );
		
}

function paupress_key( $key, $source ) {
	
	if ( 'paupress' != $source ) {
		return $key;
	} else {
		return 'paupress_' . $key;
	}
	
	return false;
	
}

function paupress_val( $data_arr, $val = true ) {
	
	if ( false == $val )
		return 'paupress_' . $data_arr['meta_key'];
	
	return $data_arr;
	
}

/**
 * Add Merge Tags to TinyMCE
 *
 * @param mixed $plugin_array
 * @return array
 */
function paupress_add_merge_tags_tinymce_plugin($plugin_array) {
	//$merge_tags = urlencode( serialize( array( 'first_name' => 'First Name' ) ) );
	// paupress_get_user_metadata( array( 'return_val' => true, 'include' => array( 'text' ) ) )
	$plugin_array['PauPressMergeTags'] = PAUPRESS_URL . '/assets/j/tmce/merge-tags-plugin.php';
	return $plugin_array;
}

function paupress_register_merge_tags_button($buttons) {
	array_push($buttons, "paupress_merge_tags_button");
	return $buttons;
}

function paupress_add_merge_tags_button() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
	if ( get_user_option('rich_editing') == 'true') :
		add_filter('mce_external_plugins', 'paupress_add_merge_tags_tinymce_plugin');
		add_filter('mce_buttons', 'paupress_register_merge_tags_button');
	endif;
}

/**
 * Form Shortcode.
 *
 * @since 0.1
 */
function paupress_merge_tags_shortcode( $atts ){
	extract( shortcode_atts( array( 'k' => '', 'alt' => '', ), $atts ) );
	return paupress_get_merge_tag( $k, $args = array( 'alt' => $alt ) );
}

function paupress_get_merge_tag( $meta_key, $args = null ) {

	/*
	Need to abstract this more for general use
	need to make an action flag to switch on
	need an action at the end of the switch to append to for plugins
	*/
	
	if ( !isset( $_POST['_pp_post']['_pp_mail_segment'] ) )
		return false;
	
	// SET THE DEFAULTS TO BE OVERRIDDEN AS DESIRED
	$defaults = array( 
					'alt' => false, 
				);
	
	// PARSE THE INCOMING ARGS
	$args = wp_parse_args( $args, $defaults );

	// EXTRACT THE VARIABLES
	extract( $args, EXTR_SKIP );
	
	if ( false == $meta_key )
		return false;
	
	// FOR POST-PROCESSING & ACTION TAGS, EXPECT TO RETURN AN ADDITIONAL ARRAY ELEMENT "DATA"
	$option = apply_filters( 'paupress_filter_merge_option', paupress_get_option( $meta_key ), $meta_key, $_POST['_pp_post']['_pp_mail_method'] );
	
	// FOR A MAILCHIMP CAMPAIGN
	if ( isset( $_POST['_pp_post']['_pp_mail_method'] ) && 'mc_html' == $_POST['_pp_post']['_pp_mail_method'] ) {
	
		if ( 
			is_array( $option ) && 
			isset( $option['options']['mailchimp'] ) && 
			false != $option['options']['mailchimp'] 
			) {
			
			return '*|'.$option['tag'].'|*';
		
		} else {
			
			global $segmems;
			if ( !empty( $segmems ) ) {
				
				$ret_stuff = array();
				foreach ( $segmems as $key => $val ) {
					// IF NON-USER META
					if ( isset( $option['data'] ) ) {
						$ret_stuff[] = "*|IF:EMAIL=$val|* " . $option['data'][$key] . " *|END:IF|*";
					// IF USER DATA
					} else if ( 'wpr' == $option['source'] ) {
						$tempuser = get_userdata( $key );
						$tempval = 'user_'.$option['meta_key'];
						$ret_stuff[] = "*|IF:EMAIL=$val|* " . $tempuser->{$tempval} . " *|END:IF|*";
					// IF USER META
					} else {
						if ( false != get_user_meta( $key, $option['meta_key'], true ) ) {
							$output = get_user_meta( $key, $option['meta_key'], true );
						} else {
							$output = $alt;
						}
						$ret_stuff[] = "*|IF:EMAIL=$val|* " . $output . " *|END:IF|*";
					}
				}
				
				return implode( ' ', $ret_stuff );
				
			}
			
		}

	} else {
		global $current_mail_user;
		$output = false;
		if ( isset( $option['source'] ) && 'wpr' == $option['source'] ) {
			$tempval = 'user_'.$option['meta_key'];
			$output = $current_mail_user->{$tempval};
		} else {
			if ( isset( $option['data'] ) ) {
				if ( isset( $option['data'][$current_mail_user->ID] ) )
					$output = $option['data'][$current_mail_user->ID];
				
			} else {
				$output = get_user_meta( $current_mail_user->ID, paupress_key( $meta_key, $option['source'] ), true );
			}
		}
		if ( false == $output || empty( $output ) )
			$output = $alt;
			
		return $output;
	}
	
	return false;
	
}


?>