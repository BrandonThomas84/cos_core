<?php
/**
 * Template Name: Under Construction
 *
 * This is the template that displays full width page without sidebar and a white background
 *
 * @package sparkling
 */

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<div id="content" class="site-content container center full-width-with-title-whtbkg">
	<h1>
	<?php 

		//check for pretitle
		$preTitle = get_post_meta( get_the_ID(), 'pdesigns_pretitle', TRUE );
		if( !empty( $preTitle ) ){
			echo $preTitle . ' ';
		}

		//get the title
		echo the_title(); 
	?>
	</h1>

	<div id="primary" class="content-area col-sm-12 col-md-12">
		<main id="main" class="site-main" role="main">

			<?php 

				//check for pretitle
				$subTitle = get_post_meta( get_the_ID(), 'pdesigns_subtitle', TRUE );
				if( !empty( $subTitle ) ){
					echo '<h2>' . $subTitle . '</h2>';
				}
			?>
			<?php endwhile; // end of the loop. ?>

			<img src="/wp-content/uploads/2014/05/under-construction.png" alt="This page is Currently Under Construction">

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
