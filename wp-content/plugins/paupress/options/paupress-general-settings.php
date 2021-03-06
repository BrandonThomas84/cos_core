<?php

function paupress_general_settings() {
	
	return apply_filters( 'paupress_general_settings', array(
			
	));
	
}


function paupress_option_welcome( $active ) {
	if ( 'paupress_general_settings' == $active ) {
?>
	<div class="options-panel farmer">
		<div id="branding"></div>
		<div class="column_holder">
			<h2><?php _e( 'hi there. we\'re really glad you\'re here.', 'paupress' ); ?></h2>
			<p><?php _e( 'We hope this plugin makes what you do in life a little better. To that end, here\'s a few helpful resources.', 'paupress' ); ?></p>
			
			<div id="setup" class="option-block">
				<h3><?php _e( 'Setup', 'paupress' ); ?></h3>
				<ol>
					<li><?php printf( __( '%1$sManage Fields%2$s: Set the fields and permissions to start collecting data.', 'paupress' ), '<a href="'.admin_url('admin.php?page=paupress_meta_options').'">', '</a>' ); ?></li>
					<li><?php printf( __( '%1$sCheck Your Profile%2$s: Make sure you have it the way you want it.', 'paupress' ), '<a href="'.admin_url('admin.php?page=paupress_edit_user_profile').'">', '</a>' ); ?></li>
					<li><?php printf( __( '%1$sTry a Search%2$s: Data mining is a surprising amount of fun.', 'paupress' ), '<a href="'.admin_url('users.php?page=paupress_reports').'">', '</a>' ); ?></li>
					<li><?php printf( __( '%1$sTweak Your Options%2$s: Visit the tabs above to customize paupress for you.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li></li>
					<li><?php printf( __( 'Lastly, there\'s a %1$sGetting Started Guide%2$s on our website that walks you through the above steps.', 'paupress' ), '<a href="http://paupress.com/support/documentation/paupress-plugin-documentation/getting-started/">', '</a>' ); ?></li>
				</ol>
			</div>
			
			<div id="help" class="option-block">
				<h3><?php _e( 'Help', 'paupress' ); ?></h3>
				<ol>
					<li><?php printf( __( '%1$sRight Now%2$s: Most of the help you will need can be found in the in handy %3$s"help" tabs%4$s at the top right of your screen.', 'paupress' ), '<strong>', '</strong>', '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sPro Online%2$s: If you need more help than the help tabs offer, please make sure to visit our site %3$spaupress.com%4$s. Pro users have the added ability to post a new support request which is our first priority.', 'paupress' ), '<strong>', '</strong>', '<a href="http://paupress.com/support">', '</a>'  ); ?></li>
					<li><?php printf( __( '%1$sStandard Online%2$s: If you are not a pro subscriber, you can still visit our site %3$spaupress.com%4$s OR our plugin page support forums. While we do give preference to pro users of the application (see above) we are actively monitoring the plugin page forums.', 'paupress' ), '<strong>', '</strong>', '<a href="http://paupress.com/support">', '</a>'  ); ?></li>
				</ol>
			</div>
			
			<div id="suggestions" class="option-block">
				<h3><?php _e( 'Suggestions', 'paupress' ); ?></h3>
				<ol>
					<li><?php printf( __( '%1$sContact Us%2$s: We want to hear from you! Please make sure to visit our site %3$spaupress.com%4$s or our plugin page support forums to let us know what you think of the application.', 'paupress' ), '<strong>', '</strong>', '<a href="http://paupress.com/">', '</a>'  ); ?></li>
				</ol>
			</div>
		</div>
		<div class="column_holder">
			<?php if ( !is_plugin_active( 'paupro/paupro.php' ) ) { ?>
			<h2><?php _e( 'looking for more? go pro!', 'paupress' ); ?></h2>
			<p><?php printf( __( 'There\'s a lot more %1$sgreat stuff you can do with PauPress%2$s.', 'paupress' ), '<a href="http://paupress.com/">', '</a>' ); ?></p>
			<div class="option-block">
				<h3><?php _e( 'Just a few reasons', 'paupress' ); ?></h3>
				<ol>
					<li><?php printf( __( '%1$sGuaranteed Support%2$s: We do try our best to provide support for all users but as a Pro user it\'s part of the package.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sSearch User History & Actions%2$s: Go beyond the profile information and really dig deep.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sImport Users & Bulk Editing%2$s: Use it once and the application pays for itself in time saved alone.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sSaved Searches%2$s: One-click searching for things you look for the most -- plus, you can apply the results elsewhere in the application.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sGoogle Geolocation%2$s: See all of your users or a segment on a map.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sFront-end Registration, Login and Account Management%2$s: Take over the default WordPress registration and login and optionally keep all user management on the front end of your site.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sRestrict Access to Content (Membership Feature)%2$s: Choose exactly who gets access to what using rules you specify. Change them at any time.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sDisplay and search a member directory (Member Directory Feature)%2$s: Display all or a subset of your members. Have as many directories as you like.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sAccept User-Generated Content (Community Feature)%2$s: Accept content for any post type from whomever you choose -- you set the rules and, again, change them at anytime.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sE-Commerce Engine%2$s: Sell anything anywhere on your site or accept donations. Search for it all using the User Search Engine. And, of course, you can Bulk Edit, Import or Export transactions.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sE-Mail Marketing Engine & MailChimp Synchronization%2$s: Send HTML newsletters using WordPress or MailChimp and if you\'re using MailChimp, enjoy full end-to-end synchronization for complete freedom to use whichever system you like, whenever you like.', 'paupress' ), '<strong>', '</strong>' ); ?></li>
					<li><?php printf( __( '%1$sFind out more%2$s and explore our growing library of extensions. If you don\'t see exactly what you need, drop us a line -- we\'re always open to ideas we\'re growing fast.', 'paupress' ), '<strong><a href="http://paupress.com/">', '</a></strong>' ); ?></li>
				</ol>
			</div>
			<?php } ?>
<?php
	}
}

function paupress_option_goodbye( $active ) {
	if ( 'paupress_general_settings' == $active ) {
		
?>
		</div>
	</div>
<?php
	}
}

?>