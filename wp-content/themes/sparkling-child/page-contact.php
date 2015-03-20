<?php
/**
 * Template Name: Contact Page
 *
 * This template displays the contact information for any registered contact person who has been enabled
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
				$executive = array();
				$subcommittee = array();

				//get users where allow contact is true
				$args = array(
					'meta_key'     => 'display_contact',
					'meta_value'   => 'true'
				);
				$users = get_users( $args );

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
							'title' => $title
						);

					//add user to relative group
					if( $committee == 'exec' ){
						$executive[ $userID ] = $value;
					} elseif( $committee == 'sub' ){
						$subcommittee[ $userID ] = $value;
					}
				}

				//display executive committee members
				echo '<div class="committee-members execcomm">';
				echo '<h2>Executive Committee</h2>';
				
				foreach( $executive as $member ){
					
					echo '<div class="exec-member-container">';

					echo '<div class="exec-title">';
					echo '<a href="javascript:void(0)" class="tigger-contact-form" data-title="' . $member['title'] . ' - ' . $member['first'] . ' ' .  $member['last'] . '" data-subtitle="Send an email to our ' . $member['title'] . '" data-user-id="' . $member['id'] . '" data-contact-type="executive" data-all-option="disabled" title="Contact COS ' . $member['title'] . '">' . $member['title'] . '</a>';
					echo '</div>';

					echo '<div class="exec-name">';
					echo '<a href="javascript:void(0)" class="tigger-contact-form" data-title="' . $member['title'] . ' - ' . $member['first'] . ' ' .  $member['last'] . '" data-subtitle="Send an email to our ' . $member['title'] . '" data-user-id="' . $member['id'] . '" data-contact-type="executive" data-all-option="disabled" title="Contact ' . $member['first'] . '">' . $member['first'] . ' ' . $member['last'] . '</a>';
					echo '</div><div class="clear"></div>';

					echo '</div>';
					
				}

				echo '<p>Click on any one of our executive committee members to contact them directly</p></div>';

				//display subcommittee members
				echo '<div class="committee-members subcomm">';
				echo '<h2>Subcommittee</h2>';
				
				foreach( $subcommittee as $member ){
					
					echo '<div class="sub-member-container">';

					echo '<div class="sub-title">';
					echo '<a href="javascript:void(0)" class="tigger-contact-form" data-title="' . $member['title'] . ' - ' . $member['first'] . ' ' .  $member['last'] . '" data-subtitle="Send an email to our ' . $member['title'] . '" data-user-id="' . $member['id'] . '" data-contact-type="subcommittee" data-all-option="disabled" title="Contact COS ' . $member['title'] . '">' . $member['title'] . '</a>';
					echo '</div>';

					echo '<div class="sub-name">';
					echo '<a href="javascript:void(0)" class="tigger-contact-form" data-title="' . $member['title'] . ' - ' . $member['first'] . ' ' .  $member['last'] . '" data-subtitle="Send an email to our ' . $member['title'] . '" data-user-id="' . $member['id'] . '" data-contact-type="subcommittee" data-all-option="disabled" title="Contact ' . $member['first'] . '">' . $member['first'] . ' ' . $member['last'] . '</a>';
					echo '</div><div class="clear"></div>';

					echo '</div>';
					
				}

				echo '<p>Click on any one of our subcommittee members to contact them directly</p></div>';
			?>

			<?php echo the_content(); ?>
		<?php endwhile; // end of the loop. ?>

		

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- #content -->
<?php get_footer(); ?>