<?php
/**
 * Template Name: Sidebar with Title
 *
 * This is the template that displays full width page without sidebar
 *
 * @package sparkling
 */

get_header(); ?>
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
<div id="primary" class="content-area">
	<main id="main" class="site-main post-inner-content" role="main">
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
<?php get_sidebar(); ?>
<div class="clear"></div>
<?php get_footer(); ?>
