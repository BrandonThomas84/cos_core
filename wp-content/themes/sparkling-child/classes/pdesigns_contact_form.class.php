<?php

class pdesigns_contact_form {

	public function checkSubmission(){

		//get the entered value
		if( isset( $_POST['realperson-raw'] ) ){
			$value = $_POST['realperson-raw'];
		} 

		_cdg_log( 'Raw' . "\n\n" );
		_cdg_log( 'title: ' . $_POST['contact-form-formtitle'] . "\n\n" );
		_cdg_log( 'subtitle: ' . $_POST['contact-form-subtitle'] . "\n\n" );
		_cdg_log( 'userID: ' . $_POST['contact-form-userID'] . "\n\n" );
		_cdg_log( 'senderName: ' . $_POST['contact-form-sender-name'] . "\n\n" );
		_cdg_log( 'senderEmail: ' . $_POST['contact-form-sender-email'] . "\n\n" );
		_cdg_log( 'senderPhone: ' . $_POST['contact-form-sender-phone'] . "\n\n" );
		_cdg_log( 'subject: ' . $_POST['contact-form-subject'] . "\n\n" );
		_cdg_log( 'content: ' . $_POST['contact-form-message'] . "\n\n" );

		$title = base64_encode( $_POST['contact-form-formtitle'] );
		$subtitle = base64_encode( $_POST['contact-form-subtitle'] );
		$userID = base64_encode( $_POST['contact-form-userID'] );
		$senderName = base64_encode( $_POST['contact-form-sender-name'] );
		$senderEmail = base64_encode( $_POST['contact-form-sender-email'] );
		$senderPhone = base64_encode( $_POST['contact-form-sender-phone'] );
		$subject = base64_encode( $_POST['contact-form-subject'] );
		$content = base64_encode( trim( $_POST['contact-form-message'] ) );
		$contactType = base64_encode( $_POST['contact-form-contact-type'] );

		_cdg_log( 'Encoded' . "\n\n" );
		_cdg_log( 'title: ' . $title . "\n\n" );
		_cdg_log( 'subtitle: ' . $subtitle . "\n\n" );
		_cdg_log( 'userID: ' . $userID . "\n\n" );
		_cdg_log( 'senderName: ' . $senderName . "\n\n" );
		_cdg_log( 'senderEmail: ' . $senderEmail . "\n\n" );
		_cdg_log( 'senderPhone: ' . $senderPhone . "\n\n" );
		_cdg_log( 'subject: ' . $subject . "\n\n" );
		_cdg_log( 'content: ' . $content . "\n\n" );

		//check the hash value
		$hashCheck = $this->rpHash( $value );
		if( $hashCheck ) {

			_cdg_log( 'Correct hash' . "\n\n" );

			//try and send message
			$response = $this->send_message();

			//redirect with message response			
			wp_redirect( strtok($_SERVER["REQUEST_URI"],'?') . '?msg=' . $response );
			
			exit;

		} else {

			_cdg_log( 'incorrect hash' . "\n\n" );

			//convert values to base64 for transport
			$title = base64_encode( $_POST['contact-form-formtitle'] );
			$subtitle = base64_encode( $_POST['contact-form-subtitle'] );
			$userID = base64_encode( $_POST['contact-form-userID'] );
			$senderName = base64_encode( $_POST['contact-form-sender-name'] );
			$senderEmail = base64_encode( $_POST['contact-form-sender-email'] );
			$senderPhone = base64_encode( $_POST['contact-form-sender-phone'] );
			$subject = base64_encode( $_POST['contact-form-subject'] );
			$content = base64_encode( $_POST['contact-form-message'] );

			wp_redirect( strtok($_SERVER["REQUEST_URI"],'?') . '?msg=1&cnt_id=' . $userID . '&cnt_title=' . $title . '&cnt_subt=' . $subtitle . '&cnt_name=' . $senderName . '&cnt_email=' . $senderEmail . '&cnt_phone=' . $senderPhone . '&cnt_subj=' . $subject . '&cnt_msg=' . $content . '&cnt_typ=' . $contactType );
			exit;
		}
	}

	private function rpHash($value) {

		$hash = 5381;
		$value = strtoupper($value);
		for($i = 0; $i < strlen($value); $i++) {
			$hash = ($this->leftShift32($hash, 5) + $hash) + ord(substr($value, $i));
		}

		$hash = (string) $hash;	
		$test = (string) $_POST['realperson-check'] ;

		if( $hash == $test ){
			return true;
		} else {
			return false;
		}
	}

	private function leftShift32($number, $steps) { 
	    // convert to binary (string) 
	    $binary = decbin($number); 
	    // left-pad with 0's if necessary 
	    $binary = str_pad($binary, 32, "0", STR_PAD_LEFT); 
	    // left shift manually 
	    $binary = $binary.str_repeat("0", $steps); 
	    // get the last 32 bits 
	    $binary = substr($binary, strlen($binary) - 32); 
	    // if it's a positive number return it 
	    // otherwise return the 2's complement 
	    return ($binary{0} == "0" ? bindec($binary) : 
	        -(pow(2, 31) - bindec(substr($binary, 1)))); 
	} 

	public function send_message(){

		//create empty array for header values
		$headers = array();
		$headers2 = array();

		//set html type header
		$headers[] = 'Content-type: text/html';
		$headers[] = 'From: no-reply@circleofsisters.org';
		$headers2[] = 'Content-type: text/html';
		

		//get the person actually clicked on information
		$userID = $_POST['contact-form-userID'];
		$clickedUser = get_user_by( 'id', $userID );

		//set them as the respond to
		$respondEmail = $clickedUser->data->user_email;	
		$headers[] = 'Reply-To:' . $respondEmail;

		_cdg_log( 'respond email set to: ' . $respondEmail );

		//get the chairperson
		$args = array(
			'meta_key'     	=> 'title',
			'meta_value'   	=> 'Chairperson'
		);
		$chairperson = get_users( $args );

		//loop through chairpeople if not empty
		if( !empty( $chairperson ) ){

			foreach( $chairperson as $adds ){

				//set the email var
				$email = $adds->data->user_email;

				//make sure its not the same as the primary respond email
				if( $email !== $respondEmail ){
					
					//add the chairpersons to the email
					$headers[] = 'Bcc: ' . $email;
				}
			}
		}


		//check if this is advisory page
		if( $_POST['contact-form-contact-type'] == 'advisory'){

			//check to see if include all was selected
			if( isset( $_POST['contact-form-include-all'] ) ){

				//get the advisors
				$args = array(
					'meta_key'     	=> 'c_group',
					'meta_value'   	=> 'adv'
				);
				$advisors = get_users( $args );

				//loop through advisors
				foreach( $advisors as $adv ){
					$email = $adv->data->user_email;

					//make sure its not the same as the primary respond email
					if( $email !== $respondEmail ){
						
						//add the advisor to the email
						$headers[] = 'Bcc: ' . $email;
					}
				}
			}
		}

		//add the headers to the notes
		$headerNotes = implode(null, $headers);
		_cdg_log( $headerNotes );	

		//set message
		$message = '<table style="width:500px;"><tbody><tr><td><h1>COS Website Generated Message</h1><img src="http://cos.perspektivedesigns.com/wp-content/uploads/2014/05/550x215xcopy-cos_logo_head1.png.pagespeed.ic.Uz_tqV9tfJ.png"><hr></td></tr><tr><td><p style="border-bottom: 1px dotted #999999;">Sender Information:</p><p>Name: ' . $_POST['contact-form-sender-name'] . '</p><p>Email: ' . $_POST['contact-form-sender-email'] . '</p><p>Date: ' . date('Y-m-d H:i:s') . '</p></td></tr><tr><td><h2 style="border-bottom: 1px dotted #999999;">Message Content:</h2><p>' . str_replace("\n", '<br>', $_POST['contact-form-message'] ) . '</p></td></tr></tbody></table>';

		$userMessage = '<table style="width:500px;"><tbody><tr><td><h1>A copy of Your COS Submission</h1><img src="http://cos.perspektivedesigns.com/wp-content/uploads/2014/05/550x215xcopy-cos_logo_head1.png.pagespeed.ic.Uz_tqV9tfJ.png"><hr></td></tr><tr><td><p style="border-bottom: 1px dotted #999999;">Sender Information:</p><p>Name: ' . $_POST['contact-form-sender-name'] . '</p><p>Email: ' . $_POST['contact-form-sender-email'] . '</p><p>Date: ' . date('Y-m-d H:i:s') . '</p></td></tr><tr><td><h2 style="border-bottom: 1px dotted #999999;">Message Content:</h2><p>' . str_replace("\n", '<br>', $_POST['contact-form-message'] ) . '</p></td></tr></tbody></table>';

				 

		//send message
		$firstMessage = wp_mail( $respondEmail, $_POST['contact-form-subject'], $message, $headers );
		if( !$firstMessage ){
			_cdg_log('There was an error while trying to send the first message');
			$response = 2;
		} else {
			$response = 0;
		}

		//send copy of message to user who submitted form
		$headers2[] = 'From: ' . $_POST['contact-form-sender-email'];
		$secondMessage = wp_mail( $_POST['contact-form-sender-email'], $_POST['contact-form-subject'], $userMessage, $headers2 );
		if( !$secondMessage ){
			_cdg_log('There was an error while trying to send the second message');
			$response = 2;
		} else {
			$response = 0;
		}

		return $response;
	}

}

?>