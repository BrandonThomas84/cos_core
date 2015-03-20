<?php

// RECURSIVE UTILITY TO HANDLE DIFFERENT TYPES OF CONTENT AND APPLY SANITIZATION OPERATIONS
function paupress_scrub( $callback, $data ) {
    if ( is_array( $data ) ) {
		$new = array();
		foreach ( $data as $key => $val ) {
			if ( is_array( $val ) ) {
				$new[$key] = paupress_scrub( $callback, $val );
			} else {
				$new[$key] = call_user_func( $callback, $val );
			}
		}
	} else {
		$new = call_user_func( $callback, $data );
	}
    return $new;
}

function paupress_esc_attr( $text ) {
	return esc_attr( stripslashes( $text ) );
}

function paupress_esc_html( $text ) {
	return stripslashes( esc_html( $text ) );
}


// RESTRICT ACCESS TO THE ADMIN AREA TO ONLY ADMINS
function paupress_restrict_redirect(){
    
    // FIND OUT WHAT PAGE THEY'RE REQUESTING
    global $pagenow;
    
    // IF IT'S AN ADMIN REQUEST
    if ( is_admin() ) {
    
    	if ( 'true' === get_option( '_paupress_access' ) && !current_user_can( 'edit_posts' ) ) {
    		if ( $pagenow == 'profile.php' || $pagenow == 'index.php'  || $pagenow == 'admin.php' ) {
    			wp_redirect( site_url() );
    			die();
    		}
    	}
    	
    	// IF THEY'RE TRYING TO ACCESS THE ORIGINAL PROFILE PAGE, REDIRECT THEM
    	if ( $pagenow == 'profile.php' ) {
        	wp_redirect( admin_url( 'admin.php?page=paupress_edit_user_profile' ) );
        	die();
        }
        
        // IF THEY'RE TRYING TO ACCESS THE ORIGINAL CREATE PROFILE PAGE, REDIRECT THEM
        if ( $pagenow == 'user-new.php' ) {
        	wp_redirect( admin_url( 'admin.php?page=paupress_new_user' ) );
        	die();
        }
        
        // IF THEY'RE TRYING TO ACCESS THE ORIGINAL ALL USERS PAGE, REDIRECT THEM
        if ( $pagenow == 'users.php' && !isset( $_GET['page'] ) ) {
        	if ( 'true' != get_option( '_paupress_compatability_mode' ) ) {
	        	wp_redirect( admin_url( 'users.php?page=paupress_reports' ) );
	        	die();
	        }
        }
        	
    }
}


/**
 * Sanitizes title, replacing whitespace with underscores. Taken directly from WP.
 *
 * Limits the output to alphanumeric characters, underscore (_) and dash (-).
 * Whitespace becomes an underscore.
 *
 * @since 1.2.0
 *
 * @param string $title The title to be sanitized.
 * @return string The sanitized title.
 */
function sanitize_title_with_underscores($title) {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	if (seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		$title = utf8_uri_encode($title, 200);
	}

	$title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = str_replace('.', '_', $title);
	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '_', $title);
	$title = preg_replace('|-+|', '_', $title);
	$title = trim($title, '_');

	return $title;
}


/**
 * General sanitizing utility.
 *
 * @since 1.0.0
 *
 * @param string $str The string to be sanitized.
 * @return string The sanitized string.
 */
function paupress_sanitize( $str ) {
	return trim( wp_strip_all_tags( stripslashes( $str ) ) );
}

?>