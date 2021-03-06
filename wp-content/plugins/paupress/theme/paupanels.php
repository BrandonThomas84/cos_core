<?php

/**
 * Actions to hook into WordPress
 *
 * @since 1.0.0
 */
 
// QUEUES SUPPORTING FILES
add_action( 'wp_enqueue_scripts', 'paupanels_scripts' );

// HOOK WORDPRESS BOOTSTRAP
add_filter( 'query_vars', 'paupanels_b_query_vars' );

// HOOK THE USER-FACING ADMIN AREA TO THE CURRENT THEME
add_action( 'wp_footer', 'paupanels_panels' );

// HOOK ADMIN & PUBLIC AJAX SUBMISSION
add_action( 'wp_ajax_paupanels', 'paupanels_submission' );
add_action( 'wp_ajax_nopriv_paupanels', 'paupanels_submission' );

// DISABLE ADMIN BAR FOR NEW USERS
add_action( 'paupress_double_down_insert', 'paupanels_double_down_insert' );

// ADD THE FLUSH FILTER
add_filter( 'paupress_flush_permalinks', 'paupanels_flush_permalinks', 10, 2 );

/**
 * Include the Public supporting files
 *
 * @since 1.0.0
 */

function paupanels_scripts(){
	
	$paustyle = get_option( 'paupanels_style' );
	
	// QUEUE SCRIPTS
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'tiptipJS' );
	wp_enqueue_script( 'paupressJS' );
	wp_enqueue_script( 'paupanelsJS' );
	wp_enqueue_script( 'chosenJS' );
	wp_enqueue_script( 'paupressViewsJS' );

	// QUEUE STYLES
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_style( 'tiptipCSS' );
	wp_enqueue_style( 'paupressGridCSS' );
	wp_enqueue_style( 'paupanelsCSS' );
	if ( false != $paustyle || 'true' == get_option( 'paupanels_embed' ) ) {
		if ( 'true' == get_option( 'paupanels_embed' ) ) $paustyle = 'light';
		wp_enqueue_style( $paustyle.'PaupanelsCSS', PAUPRESS_URL . 'assets/c/paupanels-'.$paustyle.'.css', array(), PAUPRESS_VER, false );
	}
	wp_enqueue_style( 'user-avatar' );
	wp_enqueue_style( 'paupressCSS' );
	wp_enqueue_style( 'chosenCSS' );
	wp_enqueue_style( 'jqueryuiCSS' );
	
	wp_enqueue_media();
	
	
	// PLACE THE PANELS
	if ( false != get_option( 'paupanels_panel_placement' ) ) {
		$placement = sanitize_text_field( get_option( 'paupanels_panel_placement' ) );
	} else {
		$placement = 'body';
	}
	
	if ( 'true' != get_option( 'paupanels_embed' ) ) {
		$embeded = false;
	} else {
		$embeded = true;
	}
	
	// HOOK THE AJAX ENGINE
	$paupanels_ajax_array = array();
	$paupanels_ajax_array['ajaxurl'] = admin_url( 'admin-ajax.php' );
	$paupanels_ajax_array['paupanels_nonce'] = wp_create_nonce( 'paupanels-ajax-nonce' );
	$paupanels_ajax_array['ajaxload'] = plugins_url( '/paupress/assets/g/loading.gif' );
	$paupanels_ajax_array['ajaxhome'] = home_url();
	$paupanels_ajax_array['panel_pre'] = $placement;
	$paupanels_ajax_array['panel_embed'] = $embeded;
	$paupanels_ajax_array['errMsg'] = sprintf( __( 'We found some errors -- please attend to the fields below marked with %1$s', 'paupress' ), '<span class="halt-example">&nbsp;</span>' );
	$paupanels_ajax_array['reqMsg'] = sprintf( __( '%1$s indicates a required field %2$s', 'paupress' ), '<span class="required">*', '</span>' );
	
	// LOCALIZE THE PAUPANELS SCRIPT	
	wp_localize_script( 'paupanelsJS', 'paupanelsAjax', $paupanels_ajax_array );
		
}


function paupanels_flush_permalinks( $flush, $post ) {
	
	if ( empty( $post['_pp_option']['paupanels_embed'] ) ) {
		return $flush;
	}
	
	// TEST THE UPDATE
	$flush_option = get_option( 'paupanels_embed' );
	if ( $flush_option != $post['_pp_option']['paupanels_embed'] ) {
		return true;		
	}
	
	return $flush;
}


/**
 * Add the ajax-enabled, multi-purpose user form available.
 *
 * @since 1.0.0
 */
 
function paupanels_panels( $embed = false ) {

	global $current_user, $wp_query;
	
	// RETROFIT THE ADMIN BAR IF PANELS ARE SET TO DEFAULTS
	if ( is_admin_bar_showing() && current_user_can( 'list_users' ) ) { 
		//$paupanels_frame = ' style="display: none;"'; 
		$paupanels_frame = ' class="login-bump"';
	} elseif ( is_admin_bar_showing() && !current_user_can( 'list_users' ) ) {
		$paupanels_frame = ' class="login-bump"'; 
	} else {
		$paupanels_frame = '';
	}	
	
	// SET THE REFERENCE
	$ref = array();
	
	// FOURTH PRIORITY: EXTERNAL LINKS
	if ( isset( $wp->query_vars['pauref'] ) ) {
		// UNPACK THE REFERNCE
		$rel = maybe_unserialize( urldecode( $wp->query_vars['pauref'] ) );
		$ref['title'] = 'pauref';
		$ref['ref'] = $wp->query_vars['pauref'];
	}
	
	// THIRD PRIORITY: LET PLUGINS REDIRECT
	$ref = apply_filters( 'paupanels_before_panels_init', $ref );
	
	// SECOND PRIORITY: REDIRECT FOR EMBEDS
	if ( false != $embed && is_array( $embed ) ) {
		$ref['title'] = 'embed';
		$ref['ref'] = urlencode( serialize( $embed ) );
	}
	
	
	// FIRST PRIORITY: REDIRECT FOR PASSWORD RESET
	if ( isset( $wp_query->query_vars['pp_wp_key'] ) && isset( $wp_query->query_vars['pp_wp_login'] ) ) {
		$rel_array = array( 'key' => $wp_query->query_vars['pp_wp_key'], 'login' => $wp_query->query_vars['pp_wp_login'] );
		$ref['title'] = 'reset';
		$rel_array['rel'] = 'reset';
		$ref['ref'] = urlencode( serialize( $rel_array ) );
	}
	
	// MOD THE ACTION OF THE PANELS
	if ( isset( $ref['ref'] ) && !empty( $ref['ref'] ) ) {	
	?>
	<script type="text/javascript">
		jQuery.noConflict();
		jQuery(document).ready(function() {
			var ref = '<?php echo $ref["ref"]; ?>';
			var title = '<?php echo $ref["title"]; ?>';
			jQuery('#paupanel').each(function(){ paupanels_toggle(ref,title); });
		});
	</script>
	<?php
	}
	
	if ( false != $embed ) {
		$emstyle = '-embed';
	} else {
		$emstyle = '';
	}
	
	//if ( false != $embed ) do_action( 'paupanels_pre_frame' );
			
	?>
	<div id="paupanels-frame<?php echo $emstyle; ?>" class="pauf-frame">
		<?php
			// IF THE USER IS EMBEDDING THEIR FORMS AND THIS IS THE DEFAULT
			// DON'T SHOW THE CONTENTS 
			if ( 'true' == get_option( 'paupanels_embed' ) && false == $embed ) { } else {
				$e_class = array();
				if ( false != $embed ) {
					$e_class['show'] = 'display: block;';
					$e_class['height'] = 'min-height: 200px;';
					if ( false != get_option( 'paupanels_embed_height' ) )
						$e_class['height'] = 'min-height: ' . get_option( 'paupanels_embed_height' ) . 'px;';
				}
		?>
		<div id="paupanels-wrapper" class="pauf-wrap">
			<div id="paupanel" class="pauf-pane"<?php if ( !empty( $e_class ) ) echo ' style="' . implode( ' ', $e_class ) . '"'; ?>>
				<div class="container">
					<div id="paupress" class="pauf-press">
						<?php /*if ( false != $embed ) paupanels_submission( true );*/ ?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if ( false == $embed ) do_action( 'paupanels_post_frame' ); ?>
	</div>
	
<?php
}


/**
 * Add the shortcode to embed the panel system.
 *
 * @since 1.5.3
 */
 
function paupanels_forms_shortcode( $atts ) {
	
	if ( 'true' != get_option( 'paupanels_embed' ) ) {
		return false;
	}
	
	extract( shortcode_atts( array( 'id' => false ), $atts ) );
	
	$forms = get_option( '_paupress_user_forms' );
	if ( isset( $forms[$id] ) ) {
		$pauref = urlencode( serialize( array( 'rel' => 'contact', 'pau_form' => $id ) ) );
		$ptitle = 'contact';
	} else {
		$pauref = $id;
		$ptitle = $id;
	}
	
	$output = '';
		
	$output .= '<div class="pauf-frame">';
	$output .= '<div id="paupanels-wrapper" class="pauf-wrap pauf-page">';
	$output .= '<div id="paupanel" style="display: block; min-height: 200px;" class="pauf-pane">';
	$output .= '<div class="container">';
	$output .= '<div id="paupress" class="pauf-press">';
	$output .= '</div></div></div></div></div>';
	$output .= '<script type="text/javascript">jQuery.noConflict();jQuery(document).ready(function() { var ref = "'.$pauref.'";var title = "'.$ptitle.'";jQuery("#paupanel").each(function(){ paupanels_toggle(ref,title); }); });</script>';
	
	return $output;
	
}


/**
 * Accept WordPress query parameters.
 *
 * @since 0.1.0
 */
function paupanels_get_query_vars() {
	return apply_filters( 'paupanels_okget', array( 'rel', 'tit', 'uid', 'view', 'pau_form', 'pauref' ) );
}
function paupanels_b_query_vars( $qvars ) {
	$paupanels_okget = paupanels_get_query_vars();
	foreach ( $paupanels_okget as $okgets )
		array_push( $qvars, $okgets );
	
	return $qvars;
}


/**
 * Process the submissions return the results
 *
 * @since 0.1.0
 */
function paupanels_submission( $embed = false ) {
 	
 	// RUN A SECURITY CHECK
	if ( is_user_logged_in() ) {
		if ( ! check_ajax_referer( 'paupanels-ajax-nonce', 'paupanels_nonce', false ) ) {
 			wp_clear_auth_cookie();
 			die (  __( 'very sorry. there seems to be an error. please refresh the page and try again.', 'paupress' ) );
 		}
 	}
							
	// UNWRAP THE VALUES
	if ( isset( $_POST['data'] ) )
		parse_str( $_POST['data'], $_POST );
			
	// SANITIZE ALL INPUT DATA	
	$_POST = paupress_scrub( 'paupress_sanitize', $_POST );
						
	// DO A SERIALIZED VALUE CHECK
	$rel = maybe_unserialize( urldecode( $_POST['rel'] ) );
	if ( is_array( $rel ) ) {
		$rel_array = $rel;
		$rel = $rel_array['rel'];
		$_POST = array_merge( $_POST, $rel_array );
	} else {
		if ( false !== strpos( $rel, '&' ) ) {
			$rel_pre = explode( '&', $rel );
		} else {
			$rel_pre = array( $rel );
		}

		// REL CAN BE DECLARED BY THE FORMS
		if ( 1 == count( $rel_pre ) && false === strpos( $rel, '=' ) ) {
			$rel_array = array( 'rel' => $rel );
		} else { 
			$rel_array = array();
			foreach ( $rel_pre as $key => $pair ) {
				$pair = explode( '=', $pair );
				$rel_array[$pair[0]] = $pair[1];
			}
		}
		
		// ALLOWED $_GETs
		$okget = paupanels_get_query_vars();
		foreach ( $rel_array as $key => $val ) {
			if ( in_array( $key, $okget ) )
				$_POST[$key] = $val;
		}
		
	}
	
	// SANITIZE ALL INPUT DATA	
	$_POST = paupress_scrub( 'paupress_sanitize', $_POST );
	
	// IF WE'VE SET A TEMPORARY RE-DIRECT, UNSET IT HERE
	paupanels_done_whereto();

	// RUN THE SWITCH
	switch( $_POST['rel'] ) {
		
		// SENDING A CONTACT REQUEST	
		case 'contact' :

			if ( !empty( $_POST['email'] ) ) {
				
				// CONDITIONS FOR NAME
				$fname = '';
				$lname = '';
				
				if ( isset( $_POST['paupress_user_meta']['first_name'] ) )
					$fname =  $_POST['paupress_user_meta']['first_name'];
				
				if ( isset( $_POST['paupress_user_meta']['last_name'] ) )
					$lname =  $_POST['paupress_user_meta']['last_name'];
						
				$name = $fname . ' ' . $lname;
				
				// EMAIL
				$email = $_POST['email'];
				
				// USER TARGET	
				if ( isset( $_POST['uid'] ) ) {
					$uid = $_POST['uid'];
					unset( $_POST['uid'] );
				}
				
				// CODE
				$log_code = false;
				$contact_title = '';
				$form = false;
				if ( isset( $_POST['pau_form'] ) ) {
					$log_code = $_POST['pau_form'];
					$pau_titles = get_option( '_paupress_user_forms' );
					$contact_title = $pau_titles[$_POST['pau_form']] . ': ';
					$form = get_option( '_paupress_form_' . $_POST['pau_form'] );
					$form_notifications = true;
					if ( !empty( $form['notify_enable'] ) && 'false' == $form['notify_enable'] ) {
						$form_notifications = false;
					}
				}
				
				// SUBJECT
				$subject = $contact_title . __( 'Submission', 'paupress' );
				if ( false != $form && isset( $form['subject'] ) ) {
					$subject = $form['subject'];
				}
				if ( isset( $_POST['_pp_post']['_pp_form_subject'] ) ) {
					$subject = $_POST['_pp_post']['_pp_form_subject'];
					unset( $_POST['_pp_post']['_pp_form_subject'] );
				} 
				
				// MESSAGE
				$message = '';
				if ( isset( $_POST['_pp_post']['_pp_form_message'] ) ) {
					$message = $_POST['_pp_post']['_pp_form_message'];
					unset( $_POST['_pp_post']['_pp_form_message'] );
				}
				
				// APPEND ADDITIONAL FIELDS TO THE MESSAGE
				if ( isset( $_POST['paupress_user_meta'] ) ) {
					foreach ( $_POST['paupress_user_meta'] as $k => $v ) {
						$option = paupress_get_option( $k );
						$message .= "\r\n";
						$message .= $option['name'] . ": " . stripslashes( maybe_serialize( $v ) ) . "\r\n";
					}
				}
				
				// CC
				$cc_me = 'false';
				if ( isset( $_POST['_pp_post']['_pp_form_cc'] ) ) {
					$cc_me = $_POST['_pp_post']['_pp_form_cc'];
					unset( $_POST['_pp_post']['_pp_form_cc'] );
				}
								
				$sender = get_user_by( 'email', $email );
				
				// IF THEY DON'T EXIST, ADD THEM!
				// FIRST TIME COMMUNICATIONS ARE GOING TO BE LOGGED NO MATTER WHAT
				if ( !$sender ) {
					// NEED TO DO A SECONDARY CHECK FOR ALTERNATE EMAILS
					$user_id = paupress_insert_user( array( 
															'ivals' => $_POST, 
															'log_type' => 'contact_form', 
															'log_code' => $log_code, 
															'title' => $contact_title . $subject, 
															'content' => $message, 
					) );
					$sender = get_user_by( 'id', $user_id );
				
				// IF THEY DO EXIST, AND ARE CONTACTING AN ADMIN, LOG IT!
				} else if ( !isset( $uid ) ) {
					
					$postdata['post_title'] = $contact_title . $subject;
					$postdata['post_content'] = $message;
					$postdata['post_status'] = 'private';
					$postdata['post_author'] = $sender->ID;
					$postdata['post_type'] = 'pp_log';
						
					$post_id = wp_insert_post( $postdata, true );
					
					// UPDATE THE META
					if ( intval( $post_id ) ) {
						update_post_meta( $post_id, '_pp_log_type', 'contact_form' );
						update_post_meta( $post_id, '_pp_log_code', $log_code );
					}
					
				}
				
				// PREP THE FORM FOR MAIL NOTIFICATIONS
				
				// SENDER INFORMATION
				// EMAIL COMES FROM $_POST['email']
				$_POST['name'] = $name;
				if ( false != $form ) {
					if ( !empty( $form['notify_from'] ) ) {
						$_POST['email'] = $form['notify_from'];
					}
					if ( !empty( $form['notify_from_name'] ) ) {
						$_POST['name'] = $form['notify_from_name'];
					}
				}
				
				// NEED AN OPTION FOR MAIL NOTIFICATIONS
				// IF THIS IS GOING TO ANOTHER SITE USER...
				$to_email = array();
				if ( isset( $uid ) ) {
					$recipient = get_user_by( 'id', $uid );
					$to_email[] = $recipient->user_email;
					$form_notifications = true;
				} else if ( false != $form ) {
					$to_email = explode( ',', $form['notify'] );
					foreach( $to_email as $tek => $tev )
						$to_email[$tek] = trim( $tev );
				}
				$to_email = apply_filters( 'paupanels_contact_form_recipients', $to_email, $sender, $log_code );
				
				// IF WE'RE EMAILING, DO IT NOW
				if ( !empty( $to_email ) ) {
				
					$admin_msg = '';
					$admin_msg .= sprintf( __( 'Origin: %1$s', 'paupress' ), get_option( 'blogname' ) ) . "\r\n";
					if ( !empty( $contact_title ) ) {
						$admin_msg .= sprintf( __( 'Form: %1$s', 'paupress' ), $contact_title ) . "\r\n";
					}
					$admin_msg .= sprintf( __( 'Sender: %1$s <%2$s>', 'paupress' ), $name, $email ) . "\r\n";
					$admin_msg .= "\r\n";
					$admin_msg .= $message;
				
				
					// IF WE'RE MAILING, ADD THE FILTERS	
					add_filter( 'wp_mail_from', 'paupanels_get_from_email', 20 );
					add_filter( 'wp_mail_from_name', 'paupanels_get_from_name', 20 );
						
					// SEND THE MAIL
					if ( false != $form_notifications ) {
						wp_mail( $to_email, $subject, $admin_msg );
					}
					
					// COPY THEM BUT NOT ON THE SAME EMAIL				
					if ( 'true' == $cc_me ) {
						$_POST['email'] = get_option( 'admin_email' );
						$_POST['name'] = get_option( 'blogname' );
						$user_msg = sprintf( __( 'You asked to be copied on the message below that you sent via the website: %1$s', 'paupress' ), get_option( 'blogname' ) ) . "\r\n";
						$user_msg .= "\r\n";
						$user_msg .= $message;
	
						wp_mail( $email, 'Re: ' . $subject, $user_msg );
					}
					
					remove_filter( 'wp_mail_from', 'paupanels_get_from_email', 20 );
					remove_filter( 'wp_mail_from_name', 'paupanels_get_from_name', 20 );
					
				}
				
				// THANK YOU
				$thankyou = __( 'Thank you.', 'paupress' );
				if ( isset( $_POST['pau_form'] ) ) {
					$pau_form = get_option( '_paupress_form_' . $_POST['pau_form'] );
					$thankyou = paupress_scrub( 'paupress_esc_html', $pau_form['confirm'] );
				}
				$signcount = 2 + round( str_word_count( strip_tags( $thankyou ) ) / 4 );
				
				// GIVE PREFERENCE TO THE USER'S PRIOR ACTION
				// LET PLUGINS MODIFY THE SUCCESS ACTION
				do_action( 'paupress_after_contact', $sender, $log_code );
				
				// SET A FILTER FOR REDIRECTION
				$wloc = apply_filters( 'paupress_contact_redirect', '', $sender, $log_code );					
				
				// REFRESH THE BROWSER
				?>
				<p id="tschuss"><?php echo wpautop( $thankyou ); ?></p>
				<script type="text/javascript">
					setTimeout(function() {
						jQuery('#tschuss').fadeOut('slow');
						jQuery('#paupanel').removeClass();
						<?php 
							if ( 'true' != get_option( 'paupanels_embed' ) ) { 
						?>
								jQuery('#paupanel').slideToggle('fast');
						<?php
								if ( !empty( $wloc ) ) {
									echo "window.location = '" . $wloc . "';";
								}
							} else {
								if ( !empty( $wloc ) ) {
									echo "window.location.href = '" . $wloc . "';";
								}
							}
						?>
					},  <?php echo $signcount * 1000; ?>);
				</script>
				<?php
				die();
						
			} else {

				// THE DEFAULT FIELDS
				$form_fields = paupress_form_api_fields();
									
				// USER-DEFINED REPLACEMENT
				if ( isset( $_POST['pau_form'] ) )
					$contact_arr = get_option( '_paupress_form_' . $_POST['pau_form'] );
				
				// THE DEFAULT CONTACT FORM				
				if ( !isset( $contact_arr ) || empty( $contact_arr ) )
					$contact_arr = get_option( '_paupress_form_contact_form' );
					
				// THE SAFETY CONTACT FORM
				if ( !isset( $contact_arr ) || empty( $contact_arr ) )
					$contact_arr = array( 
											'column_1' => array( 'first_name', 'last_name', 'email' ),
											'column_2' => array( '_pp_form_subject', '_pp_form_message', '_pp_form_cc' ),
										);
				
				// DOUBLE-CHECK THAT WE HAVE AN EMAIL AND A MESSAGE
				$c_email = false;
				$c_msg = false;
				foreach ( $contact_arr as $ckey => $cval ) {
					if ( is_array( $cval ) ) {
						
						if ( in_array( 'email', $cval ) )
							$c_email = true;
							
						if ( in_array( '_pp_form_message', $cval ) )
							$c_msg = true;
					
					}
				}
				
				// ALWAYS ENSURE EMAIL IS SET
				if ( false == $c_email ) {
					array_push( $contact_arr['column_1'], 'email' );
				}
				
				//if ( false == $c_msg )
					//array_push( $contact_arr['column_2'], '_pp_form_message' );

				if ( isset( $contact_arr['msg'] ) && !empty( $contact_arr['msg'] ) ) {
					echo '<div id="form-msg">';
					echo wpautop( stripslashes( $contact_arr['msg'] ) );
					echo '</div>';
				}
				
				// LET'S SEE IF THEY WANT ONE OR TWO COLUMNS
				if ( empty( $contact_arr['column_2'] ) ) {
					$colone = ' class="column-holder full"';
					$coltwo = false;
				} else {
					$colone = ' id="column_1_holder"';
					$coltwo = ' id="column_2_holder"';
				}

				 								
				
				?>
				<form class="paupanels-form" enctype="multipart/form-data" action="" method="POST">
					<div<?php echo $colone; ?>>
						<ul>
						<?php
							if ( isset( $contact_arr['column_1'] ) ) {
								foreach ( $contact_arr['column_1'] as $key => $val) { 
									if ( isset( $form_fields[$val] ) ) {
										$meta = $form_fields[$val];
										$args['type'] = 'post';
									} else {
										$meta = paupress_get_option( $val );
									}
									$args['meta'] = $meta;
									$args['action'] = 'register';
									
									if ( is_user_logged_in() ) {
										global $current_user;
										$args['id'] = $current_user->ID;
									}
									paupress_get_field( $args );
								}
							}
						?>
						</ul>
					</div>
					
					<?php if ( false != $coltwo ) { ?>
					<div<?php echo $coltwo; ?>>
						<ul>
						<?php
							if ( isset( $contact_arr['column_2'] ) ) {
								foreach ( $contact_arr['column_2'] as $key => $val) { 
									if ( isset( $form_fields[$val] ) ) {
										$meta = $form_fields[$val];
										$args['type'] = 'post';
									} else {
										$meta = paupress_get_option( $val );
									}
									$args['meta'] = $meta;
									$args['action'] = 'register';
									
									if ( is_user_logged_in() ) {
										global $current_user;
										$args['id'] = $current_user->ID;
									}
									paupress_get_field( $args );
								}
							}
						?>
						</ul>
					</div>
					<?php } ?>
					<div class="continue">
						<input type="hidden" name="rel" value="contact" />
						<?php 
							if ( isset( $_POST['uid'] ) )
								echo '<input type="hidden" name="uid" value="'.$_POST['uid'].'" />';
							
							if ( isset( $_POST['pau_form'] ) )
								echo '<input type="hidden" name="pau_form" value="'.$_POST['pau_form'].'" />';
						?>
						<input type="submit" name="_paupress[submission]" value="<?php _e( 'Submit', 'paupress' ); ?>" class="button" />
						<?php 
							echo   paupanels_build_panel_link( 
								array( 'links' => apply_filters( 'paupress_contact_links', 
									array( 
										//paupanels_get_panel_link( 'action=login' ), 
									) 
								) )
							);
						?>
					</div>
				</form>
				<?php
			}
		break;
		
		// EXTEND THE PANEL SYSTEM
		default :
			do_action( 'paupanels_switch', $rel );
		break;
	
	}
	
	if ( 'true' == get_option( 'paupanels_embed' ) ) { echo '<div id="close-bot"></div>'; } else {
	?>
	<div id="close-top"><?php paupanels_panel_link( 'action=close' ); ?></div>
	<div id="close-bot"><?php paupanels_panel_link( 'action=close' ); ?></div>
	<script type="text/javascript">
	</script>
	<?php	
	}
	
	if ( 'true' == get_option( 'paupanels_google_analytics' ) ) {
	?>
	<script type="text/javascript">
		// "_trackEvent" is the pageview event,
		_gaq.push(['_trackPageview', '/<?php echo $rel; ?>']);
	</script>
	<?php
	}
	
	// ALL DONE!
	die();

}


function paupanels_double_down_insert( $user_id ) {
	update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
}

function paupanels_panel_link( $args = '' ) {
	echo paupanels_get_panel_link( $args );
}

function paupanels_get_panel_link( $args = '' ) {

	// SET THE DEFAULTS TO BE OVERRIDDEN AS DESIRED
	$defaults = array( 
					'action' => '', 
					'title' => false, 
					'text' => '', 
					'href' => false, 
					'rel' => false, 
					'class' => array( 'paupanels-toggle' ), 
					'id' => '', 
					'raw' => false, 
					'textonly' => false, 
				);
	
	// PARSE THE INCOMING ARGS
	$args = wp_parse_args( $args, $defaults );

	// EXTRACT THE VARIABLES
	extract( $args, EXTR_SKIP );
	
	// PRE-EMPT THE RETURN
	if ( false != $raw ) {
		if ( is_array( $raw ) ) {
			$raw = http_build_query( $raw );
			return home_url( '/paupress/?' . $raw );
		} else {
			return home_url( $raw );
		}
	}
	
	// OTHERWISE, SEE IF WE HAVE A SET ACTION	
	switch( $action ) {
		
		case 'login' :
			$href = 'rel=login';
			$title = 'login';
			$text = PAUPRESS_LOGIN;
		break;
		
		case 'logout' :
			$href = 'rel=logout';
			$title = 'logout';
			$text = PAUPRESS_LOGOUT;
		break;
			
		case 'signup' :
			if ( 'true' != get_option( 'paupanels_registration' ) )
				return false;
			$href = 'rel=signup';
			$title = 'signup';
			$text = PAUPRESS_SIGNUP;
		break;
		
		case 'lost' :
			$href = 'rel=lost';
			$title = 'lost';
			$text = __( 'recover password', 'paupress' );
		break;
		
		case 'profile' :
			$href = 'rel=profile';
			$title = 'profile';
			$text = PAUPRESS_PROFILE;
		break;
		
		case 'contact' :
			$href = 'rel=contact';
			$title = 'contact';
			$text = __( 'Contact', 'paupress' );
		break;
		
		case 'try_again' :
			$textonly = true;
			$text = __( 'try again', 'paupress' );
		break;
		
		case 'close' :
			$href = '';
			$text = __( 'close panel', 'paupress' );
			$class = array( 'paupanels-close' );
		break;
			
	}
	
	if ( false != $href ) $href = 'href="' . home_url( '/paupress/?'.$href ) . '"';
	if ( false != $title ) $title = 'title="'.$title.'"';
	if ( false != $rel ) $rel = 'rel="'.$rel.'"';
	if ( false != $id ) $id = 'id="'.$id.'"';
	
	if ( false != $textonly ) return $text;
	
	return ' <a class="' . implode( ' ', $class ) . '" '.$title.' '.$href.' '.$rel.'>'.$text.'</a>';
	
}

/**
 * Gathers the available link to a user-friendly presentation.
 *
 * @since 0.1.0
 */
function paupanels_build_panel_link( $args = '' ) {
	
	// SET THE DEFAULTS TO BE OVERRIDDEN AS DESIRED
	$defaults = array( 
					'links' => array(), 
					'mod' => __( ' or ', 'paupress' ),
					'lpre' => false, 
					'lpost' => false
					 
				);
	
	// PARSE THE INCOMING ARGS
	$args = wp_parse_args( $args, $defaults );

	// EXTRACT THE VARIABLES
	extract( $args, EXTR_SKIP );
	
	//if ( false == $lpre )
		//$lpre = $mod;

	// FILTER OUT ANY FALSE POSITIVES
	$links = array_filter( $links );

	if ( !empty( $links ) )
		return $lpre . implode( $mod, $links ) . $lpost;
	
	return false;
	
}

/**
 * Sets the return vehicle.
 *
 * @param string required $whereto. The panel-system switch destination.
 * @param array $dowhat. Additional approved parameters (paupanels_okget) needed by the destination to process the request.
 *
 * @return null. Sets a cookie named for a panel-system switch and any additional parameters needed to call
 * after a user has been authenticated.
 *
 * @since 0.1.0
 */
function paupanels_set_whereto( $whereto = false, $dowhat = array() ) {
	
	if ( false == $whereto )
		return false;
		
	// PASS THE PANEL SWITCH VALUE TO THE ACTIONABLE ARRAY
	$dowhat['rel'] = $whereto;
	
	// SET THE INDEX-SPECIFIC COOKIE AND ANY ARGUMENTS THAT GO WITH IT	
	setcookie( $whereto, urlencode( serialize( $dowhat ) ), 0, '/' );

}

/**
 * Initiates the return vehicle.
 *
 * @param string required $whereto. The panel-system switch destination.
 * @param string $redirect. If a browser redirection is requested, process that here.
 *
 * @return null. Sets a panel-system cookie that can be acted on after authentication
 * optionally deletes any pre-set cookies once their value has been passed.
 * If you desire a panel-redirection call this through the action 'paupanels_after_login_success'.
 * If you desire a browser-redirection call this through the filter 'paupanels_login_redirect'.
 *
 * @since 0.1.0
 */
function paupanels_do_whereto( $whereto = false, $redirect = false ) {

	// IF AN EXPLICIT ACTION IS PASSED, SET IT FOR PROCESSING
	if ( false != $whereto ) {
	
		// MAKE SURE WHERETO IS EITHER A STRING OR AN ARRAY
		if ( !is_array( $whereto ) )
			$whereto = maybe_unserialize( urldecode( $whereto ) );
		
		// SET THE VALUE
		$do_whereto = $whereto;
		
	}

	// COOKIED (PRIOR) REQUESTS ARE GIVEN PRECEDENCE HERE FOR PROCESSING
	if ( !is_array( $whereto ) && isset( $_COOKIE[$whereto] ) )
		$do_whereto = $_COOKIE[$whereto];
	
	// IF NOTHING IS SET, EXIT		
	if ( !isset( $do_whereto ) )
		return false;
	
	// REMOVE THE EXISTING COOKIE
	if ( isset( $_COOKIE[$whereto] ) )
		setcookie( $whereto, '', 0 - 1, '/' );
	
	// PROCESS THE REDIRECT	
	if ( false != $redirect ) {
		
		// IF THIS IS A PAUPANELS REDIRECT
		if ( false === strpos( $do_whereto, 'http' ) )
			$do_whereto = paupanels_get_panel_link( array( 'raw' => $do_whereto ) );
			
		return $do_whereto;
	
	// OR HAND OFF THE ARRAY FOR PROCESSING
	} else {
		
		setcookie( 'paupanels', $do_whereto, 0, '/' );
		
	}
	

}

/**
 * Cleans up the return.
 *
 * @since 0.1.0
 */
function paupanels_done_whereto() {
	if ( isset( $_COOKIE['paupanels'] ) )
		setcookie( 'paupanels', '', 0 - 1, '/' );
}


function paupanels_get_from_name( $gfn = '' ) {
	if ( isset( $_POST['name'] ) )
		$gfn = $_POST['name'];
	
	return $gfn;
}
function paupanels_get_from_email() {
	return $_POST['email'];
}


function paupanels_link_shortcode( $atts ) {
	extract( shortcode_atts( array( 'id' => false, 'text' => false ), $atts ) );
	
	if ( !$id )
		return false;
	
	$master_ops = array( 'login', 'logout', 'signup', 'profile' );
	$preop = '';
	if ( !in_array( $id, $master_ops ) )
		$preop = 'contact&pau_form=';
		
	return '<a class="paupanels-toggle" title="' . $id . '" href="' . home_url() . '/paupress/?rel=' . $preop . $id . '">' . $text . '</a>';
}
