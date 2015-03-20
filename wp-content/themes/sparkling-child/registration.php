<?php
/**
 * Template Name: Registration Page
 *
 * This is the template that displays full width page without sidebar
 *
 * @package sparkling
 */

get_header(); ?>
<p class="h2 reg-head-msg">Pre-registration extended to February 15, 2015</p>
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
		<h2>Pre-Register for Convention Events</h2>
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
<h2 id="above-sidebar" class="full-width reg-cart-title">Your Registered Items</h2>
<?php get_sidebar('WP_PayPal_Cart_Widget'); ?>
<div class="clear"></div>
<?php get_footer(); ?>

<!-- Registration Page JS -->
<script>
jQuery(document).ready(function($){
	//remove the search
	$("aside[id^='search']").remove();
	//add the title
	$("aside[id^='wp_paypal_shopping_cart_widgets']").children("h3").text("Registration Cart");

	//remove the ability to remove the paypal fee
	var feeQty = $('input[value="Paypal Service Fee"]').parent('form[name="pcquantity"]');
	var feeRmv = $( feeQty ).parent().parent().find("form");

	$( feeQty ).remove();
	$( feeRmv ).remove();

	//clean up cart area
	//$("aside[id^='wp_paypal_shopping_cart_widgets']").children(".shopping_cart").children("h2").remove();
	//$("aside[id^='wp_paypal_shopping_cart_widgets']").children(".shopping_cart").children().first("br").remove();

	//view cart link
	$(".prd-view-cart").click(function(){
		$('body,html').animate({
			scrollTop: $("#secondary").offset().top
		}, 1000);
	});

	if( $( window ).width() >= 997 ){
		$( window ).scroll(function(){

			var position = $("#secondary").offset().top;
			var windowPos = $( window ).scrollTop();	
			var pageStart = $("#content").offset().top;	
			console.log( pageStart );

			//chjeck if header is hidden
			if( windowPos > pageStart ){

				$.doTimeout( 'scroll', 250, function(){
					$("#secondary").css({"position" : "fixed",});
					$("#secondary").animate({
						"top" : "50px",
					}, 500);
				});			
			} else {
				console.log("reset");
				$("#secondary").css({"top":"initial","position":"absolute"});
			}
		});
	};
});
</script>
