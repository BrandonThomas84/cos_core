<?php

class pdesigns_user_management {

	public function password_recovery( $userID, $length ){

		//get user email address
		$user = get_user_by( 'id', $userID );
		$email = $user->data->user_email;
		$username = $user->data->user_login;

		//testing overwrite email
		//$email = 'brandon@perspektivedesigns.com';

		//get user meta data
		$userMeta = get_user_meta( $userID );

		//string of characters
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

		//create a new password that is the requested length
		$new_pass = substr(str_shuffle( $characters ), 0, $length );

		//create email message
		$message = '<table style="width:500px;"><tbody><tr><td><h1>A copy of Your COS Submission</h1><img src="http://cos.perspektivedesigns.com/wp-content/uploads/2014/05/550x215xcopy-cos_logo_head1.png.pagespeed.ic.Uz_tqV9tfJ.png"><hr></td></tr><tr><td><h2 style="border-bottom: 1px dotted #999999;color: red">Warning:</h2><p style="color: red;">It is recommended that you login and change your password to something easily remembered.</p></td></tr><tr><td><p>A password reset request has been issued and your new password has been listed below. If you did not request this password change please email the COS web administrator as they may have done it for you.</p><p><a href="' . home_url('/wp-admin') . '" title="login with your new password">LOGIN BY CLICKING HERE</a></p></td></tr><tr><td><p style="border-bottom: 1px dotted #999999;">Password Change:</p><p>User Name: ' . $username . '</p><p>New Password: ' . $new_pass . '</p><p>Date: ' . date('Y-m-d H:i:s') . '</p></td></tr></tbody></table>';

		//set headers
		$headers[] = 'Content-type: text/html';
		$headers[] = 'From: password_recovery@circleofsisters.org';
		$headers[] = 'Bcc: Web Admin <brandon@perspektivedesigns.com>';
		//$headers[] = 'Cc: iluvwp@wordpress.org'; 

		//send message
		wp_mail( $email, 'Password Recovery', $message, $headers );

		//reset database password
		wp_set_password( $new_pass, $userID );
	}
	
	public function main_display(){
		echo '<h1>COS User Management Screen</h1>';

		//create arrays for each group
		$executive = array();
		$subcommittee = array();
		$advisors = array();
		$admins = array();
		$noGroup = array();

		//get users
		$users = get_users();

		//combine users into an array of data
		foreach( $users as $user ){

			//set vars for core information
			$userID = $user->data->ID;

			//get user meta data
			$userMeta = get_user_meta( $userID );

			//set meta vars
			$first_name = $userMeta['first_name'][0];
			$last_name = $userMeta['last_name'][0];
			$title = $userMeta['title'][0];
			$committee = $userMeta['c_group'][0];

			$value = array(
					'id'	=> $userID,
					'first' => $first_name,
					'last' 	=> $last_name,
					'title' => $title,
					'group' => $committee
				);

			//add user to relative group
			if( $committee == 'exec' ){

				$executive[ $userID ] = $value;

			} elseif( $committee == 'sub' ){

				$subcommittee[ $userID ] = $value;

			} elseif( $committee == 'adv' ){

				$advisors[ $userID ] = $value;

			} elseif( $committee == 'admin' ){

				$admins[ $userID ] = $value;

			} else {

				$noGroup[ $userID ] = $value;

			}
		}
		
		//start executive members section
		echo '<div id="cos-executive-members">';
		echo '<h2>Executive Members</h2>';

		//loop through executive members
		foreach( $executive as $member ){
			echo '<div class="member-container">';
			echo '<p class="h2">' . $member['first'] . ' ' . $member['last'] . '</p>';

			//change association
			self::user_manage_forms( $member['id'], $member['group'] );

			echo '</div>';
		}

		echo '</div>';

		//start subcommitte members section
		echo '<div id="cos-subcomittee-members">';
		echo '<h2>Subcommittee Members</h2>';

		//loop through subcommittee members
		foreach( $subcommittee as $member ){
			echo '<div class="member-container">';
			echo '<p class="h2">' . $member['first'] . ' ' . $member['last'] . '</p>';

			//change association
			self::user_manage_forms( $member['id'], $member['group'] );

			echo '</div>';
		}

		echo '</div>';

		//start advisory board members section
		echo '<div id="cos-advisory-members">';
		echo '<h2>Advisory Board Members</h2>';

		//loop through advisors members
		foreach( $advisors as $member ){
			echo '<div class="member-container">';
			echo '<p class="h2">' . $member['first'] . ' ' . $member['last'] . '</p>';

			//change association
			self::user_manage_forms( $member['id'], $member['group'] );

			echo '</div>';
		}

		echo '</div>';

		//start admin board members section
		echo '<div id="cos-advisory-members">';
		echo '<h2>Administrator Members</h2>';

		//loop through admin members
		foreach( $admins as $member ){
			echo '<div class="member-container">';
			echo '<p class="h2">' . $member['first'] . ' ' . $member['last'] . '</p>';

			//change association
			self::user_manage_forms( $member['id'], $member['group'] );

			echo '</div>';
		}
		
		echo '</div>';

		//start unassigned members section
		echo '<div id="cos-advisory-members">';
		echo '<h2>Unassigned Members</h2>';

		//loop through unassigned members
		foreach( $noGroup as $member ){
			echo '<div class="member-container">';
			echo '<p class="h2">' . $member['first'] . ' ' . $member['last'] . '</p>';

			//change association
			self::user_manage_forms( $member['id'], $member['group'] );

			echo '</div>';
		}
		
		echo '</div>';

	}

	public static function user_manage_forms( $userID, $group ){

		//change association
		echo '<form name="cos-change-assoc" id="cos-change-assoc" method="post">';
		echo '<input type="hidden" name="user-change-assoc" value="' . $userID . '">';
		echo '<label for="c_group">Change User Group Association</label><br>';
		echo '<select name="c_group_' . $userID . '">';
		echo '<option value="" ' . selected( '', $group, FALSE ) . '>Unassociated</option>';
		echo '<option value="exec" ' . selected( 'exec', $group, FALSE ) . '>Executive Committee</option>';
		echo '<option value="sub" ' . selected( 'sub', $group, FALSE ) . '>Subcommittee</option>';
		echo '<option value="adv" ' . selected( 'adv', $group, FALSE ) . '>Advisory Committee</option>';
		echo '<option value="admin" ' . selected( 'admin', $group, FALSE ) . '>Web Administrators</option>';
		echo '</select>';
		echo '</form>';

		//echo password reset
		echo '<form name="cos-password-reset" id="cos-password-reset" method="post">';
		echo '<input type="hidden" name="cos-password-reset" value="' . $userID . '">';
		echo '<label for="c_group">Reset User Password</label><br>';
		echo '<input id="cos-reset-pass" type="submit" value="Reset User Password" class="button button-primary">';
		echo '</form>';
		echo '<div class="clear"></div>';
	}
}

?>