<?php
/**
 * Template Name: Full-width with Title (White Background)
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

				<?php echo the_post_thumbnail(); ?>

				<?php echo the_content(); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>