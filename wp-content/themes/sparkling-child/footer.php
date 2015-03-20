<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package sparkling
 */
?>
			</div><!-- close .*-inner (main-content or sidebar, depending if sidebar is used) -->
		</div><!-- close .row -->
	</div><!-- close .container -->
</div><!-- close .main-content -->
	
	<div id="footer-area">
		<div class="container footer-inner">
			<div class="row">
				<?php get_sidebar( 'footer' ); ?>
			</div>
		</div>
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info container">
				<div class="row">
					<?php sparkling_social(); ?>
					<nav role="navigation" class="col-md-12 footer-nav-container">
						<?php sparkling_footer_links(); ?>
					</nav>
				</div>
			</div><!-- .site-info -->
			<div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->
			<div id="web-suggestion-container">
				<p>Suggestions</p>
				<div id="web-suggestion" class="tigger-contact-form" data-title="Contact our Web Administrator" data-subtitle="Have a question? How about a suggestion? Send an email to our Web Administrator regarding your experience." data-user-id="1" data-contact-type="web-admin" data-all-option="disabled">
					<img src="/wp-content/themes/sparkling-child/assets/speech-bubble.png" title="Contact our Web Admin">
				</div>
			</div>
		</footer><!-- #colophon -->
	</div>
</div><!-- #page -->
<?php wp_footer(); ?>
<?php

	//display defaults
	$forceFormDisplay = false;
	$title = null;
	$subtitle = null;
	$userID = null;
	$senderName = null;
	$senderEmail = null;
	$senderPhone = null;
	$subject = null;
	$content = null;

	//check for get messages
	if( isset( $_GET['msg'] ) ){

		//message content
		$messages = array(
			0	=> array( 'success' , 'Thank you for your submission. We will be sure to reach out to you as soon as possible. We have also sent you a message to confirm your submission. Please check your inbox for our correspondence.'),
			1	=> array( 'warning', 'Whoops! It looks like you have incorrectly entered the captcha (pink letters on the form) value. Please try and submit your meesage again'),
			2	=> array( 'error', 'We\'re sorry there was an issue while trying to send your message. A copy of your correspondence have been sent to you and our web technician for further review of the error.')
		);

		if( in_array( $_GET['msg'], array(1) ) ){
			//forces display of contact form
			$forceFormDisplay = true;
		}

		//output the message with the message type
		echo '<div class="contact-form-send-message ' . $messages[ $_GET['msg'] ][0] . '"><span class="close-contact-message">X</span>';
		echo '<p>' . $messages[ $_GET['msg'] ][1] . '</p>';
		echo '</div>';

		$title = base64_decode( $_GET['cnt_title'] );
		$subtitle = base64_decode( $_GET['cnt_subt'] );
		$userID = base64_decode( $_GET['cnt_id'] );
		$senderName = base64_decode( $_GET['cnt_name'] );
		$senderEmail = base64_decode( $_GET['cnt_email'] );
		$senderPhone = base64_decode( $_GET['cnt_phone'] );
		$subject = base64_decode( $_GET['cnt_subj'] );
		$content = base64_decode( $_GET['cnt_msg'] );
		$contactType = base64_decode( $_GET['cnt_typ'] );
	}
?>
<div id="darken-screen" class="<?php if( !$forceFormDisplay){ echo 'hidden'; } ?>"></div>
<div id="contact-form-container" class="<?php if( !$forceFormDisplay){ echo 'hidden'; } ?>">
	<span class="close-contact-form">X</span>
	<h2 id="form-contact-title"><?php echo $title; ?></h2>
	<p class="h4" style="text-align: center;" id="contact-form-subtitle"><?php echo $subtitle; ?></p>
	<form name="contact-form" method="post">
		<input type="hidden" id="contact-form-contact-type" name="contact-form-contact-type" required="required"  value="<?php echo $contactType; ?>">
		<input type="hidden" id="contact-form-userID" name="contact-form-userID" required="required"  value="<?php echo $userID; ?>">
		<input type="hidden" id="contact-form-formtitle" name="contact-form-formtitle" value="<?php echo $title; ?>">
		<input type="hidden" name="contact-form-subtitle" value="<?php echo $subtitle; ?>">
		<label>Name</label><br>
		<input type="text" class="wide-input" name="contact-form-sender-name" placeholder="Your Name" required="required" value="<?php echo $senderName; ?>"><span class="required-indicator">*</span><br>
		<label>Email Address</label><br>
		<input type="text" class="narrow-input" name="contact-form-sender-email" placeholder="you@youremail.com" required="required" value="<?php echo $senderEmail; ?>"><span class="required-indicator">*</span><br>
		<label>Phone Number</label><br>
		<input type="text" class="narrow-input" name="contact-form-sender-phone" placeholder="(123) 555-5555" value="<?php echo $senderPhone; ?>">
		<div class="clear"></div>
		<label>Message Subject</label><br>
		<input type="text" class="wide-input" name="contact-form-subject" placeholder="What is this regarding?" required="required" value="<?php echo $subject; ?>"><span class="required-indicator">*</span><br>
		<label>Message Body</label><br>
		<textarea name="contact-form-message" required="required">
			<?php echo $content; ?>
		</textarea><br>
		<div id="include-all-on-list-container" class="hidden">
			<label for="contact-form-include-all"></label><input type="checkbox" name="contact-form-include-all" ><br>
		</div>
		<div class="realperson-container">
			<label>Enter the text you see to the right</label><br>
			<input type="text" name="realperson-raw" required="required">
			<div id="realperson-check"></div>
			<div class="clear"></div>
		</div>
		<input type="submit" value="Send Message" class="pretty-button">
	</form>
	<div class="direct-contact-notes">
		<span class="required-indicator">* = REQUIRED FIELD</span>
	</div>
	<div class="clear"></div>
</div>

</body>
</html>