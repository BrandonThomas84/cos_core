<?php
/**
 * Template Name: Advisory Page
 *
 * This template displays the advisory contact information for any registered advisor who has been enabled
 *
 * @package sparkling
 */

get_header(); 


?>

<div id="content" class="site-content container center full-width-with-title-whtbkg">
	<?php while ( have_posts() ) : the_post(); ?>
	<h1 class="full-width">
	<?php 

		//check for pretitle
		$preTitle = get_post_meta( get_the_ID(), 'pdesigns_pretitle', TRUE );
		if( !empty( $preTitle ) ){
			echo $preTitle . ' ';
		}

		//rget the title
		echo the_title(); 
	?>
	</h1>
	<div class="clear"></div>
	<div id="primary" class="content-area col-sm-12 col-md-12 ">
		<?php

			//check for subtitle
			$subTitle = get_post_meta( get_the_ID(), 'pdesigns_subtitle', TRUE );
			if( !empty( $subTitle ) ){
				echo '<p class="h3">' . $subTitle . '</p>';
			}

		?>
		<main id="main" class="site-main" role="main">
			<?php

				//create arrays for each group
				$advisors = array();

				//get users where allow contact is true
				$args = array(
					'meta_key'     	=> 'advisory_settings'
				);
				$users = get_users( $args );

				//combine users into an array of data
				foreach( $users as $user ){

					//set vars for core information
					$userID = $user->data->ID;

					//get user advisory settings data
					$advisory_settings = get_user_meta( $userID, 'advisory_settings', true );
					$advisory_settings = unserialize( $advisory_settings );

					if( !empty( $advisory_settings['active'] ) && $advisory_settings['active'] == 'true') {
						
						//get core user meta data
						$userMeta = get_user_meta( $userID );

						//set meta vars
						$first_name = $userMeta['first_name'][0];
						$last_name = $userMeta['last_name'][0];					
					
						$convention = $advisory_settings['conv'];
						$location = $advisory_settings['loc'];
						$displayOrder = $advisory_settings['order'];

						$value = array(
								'id'	=> $userID,
								'first' => $first_name,
								'last' 	=> $last_name,
								'convention' => $convention,
								'location' => $location,
								'order'	=> $displayOrder
							);

						$advisors[ $userID ] = $value;
						$ordered_advisors[ $displayOrder ] = $userID;
					}
				}


				ksort( $ordered_advisors );

				//display advisory board members
				echo '<div class="advisory-container">';
				echo '	<div class="advisory-contact-element head-section">';
				echo '		<div class="advisory-name title">';
				echo '			<p>Advisor</p>';
				echo '		</div>';
				echo '		<div class="advisory-convention title">';
				echo '			<p>Convention</p>';
				echo '		</div>';
				echo '		<div class="advisory-location title">';
				echo '			<p>Location</p>';
				echo '		</div>';
				echo '		<div class="clear"></div>';
				echo '	</div>';
				
				foreach( $ordered_advisors as $advisor ){

					$member = $advisors[ $advisor ];
					
					echo '<div class="advisory-contact-element">';

					echo '<div class="advisory-name">';
					echo '<p><a href="javascript:void(0)" class="tigger-contact-form" data-title="Advisory Board Member - ' . $member['first'] . ' ' .  $member['last'] . '" data-subtitle="Send an email to any one of our advisory board members or the entire board" data-user-id="' . $member['id'] . '" data-contact-type="advisory" data-all-option="checked" title="Contact COS Advisory Board">' . $member['first'] . ' ' .  $member['last'] . '</a></p>';
					echo '</div>';
					echo '<div class="advisory-convention">';
					echo '	<p><a href="javascript:void(0)" class="tigger-contact-form" data-title="Advisory Board Member - ' . $member['first'] . ' ' .  $member['last'] . '" data-subtitle="Send an email to any one of our advisory board members or the entire board" data-user-id="' . $member['id'] . '" data-contact-type="advisory" data-all-option="checked" title="Contact COS Advisory Board">' . $member['convention'] . '</a></p>';
					echo '</div>';
					echo '<div class="advisory-location">';
					echo '	<p><a href="javascript:void(0)" class="tigger-contact-form" data-title="Advisory Board Member - ' . $member['first'] . ' ' .  $member['last'] . '" data-subtitle="Send an email to any one of our advisory board members or the entire board" data-user-id="' . $member['id'] . '" data-contact-type="advisory" data-all-option="checked" title="Contact COS Advisory Board">' . $member['location'] . '</a></p>';
					echo '</div>';
					echo '<div class="clear"></div>';

					echo '</div>';
					
				}

				echo '<p>Click on any one of our Advisory Members to contact our advisory board</p></div>';

			?>

			<?php echo the_content(); ?>
		<?php endwhile; // end of the loop. ?>

		

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- #content -->
<?php get_footer(); ?>