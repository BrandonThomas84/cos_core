<?php
/**
 * Template Name: Gallery Page (White Background)
 *
 * This is the template that displays full width page with the gallery on a white background
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

				<div class="fancybox-container">
					<div class="fancybox-element">
						<p class="h5">COS I</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-1_600.jpg" title="Circle of Sisters I Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-1_600.jpg" alt="Circle of Sisters I Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS II</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-2_600.jpg" title="Circle of Sisters II Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-2_600.jpg" alt="Circle of Sisters II Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS III</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-3_600.jpg" title="Circle of Sisters III Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-3_600.jpg" alt="Circle of Sisters III Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS IV</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-4_600.jpg" title="Circle of Sisters IV Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-4_600.jpg" alt="Circle of Sisters IV Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS V</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-5_600.jpg" title="Circle of Sisters V Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-5_600.jpg" alt="Circle of Sisters V Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS VI</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-6_600.jpg" title="Circle of Sisters VI Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-6_600.jpg" alt="Circle of Sisters VIK Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS VII</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-7_600.jpg" title="Circle of Sisters VII Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-7_600.jpg" alt="Circle of Sisters VI Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS VIII</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-8_600.jpg" title="Circle of Sisters VIII Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-8_600.jpg" alt="Circle of Sisters VIII Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS IX</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-9_600.jpg" title="Circle of Sisters IX Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-9_600.jpg" alt="Circle of Sisters IX Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS X</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-10_600.jpg" title="Circle of Sisters X Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-10_600.jpg" alt="Circle of Sisters X Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS XI</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-11_600.jpg" title="Circle of Sisters XI Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-11_600.jpg" alt="Circle of Sisters XI Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS XII</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-12_600.jpg" title="Circle of Sisters XII Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-12_600.jpg" alt="Circle of Sisters XII Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS XIII</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-13_600.jpg" title="Circle of Sisters XIII Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-13_600.jpg" alt="Circle of Sisters XIII Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS XIV</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-14_600.jpg" title="Circle of Sisters XIV Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-14_600.jpg" alt="Circle of Sisters XIV Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS XV</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-15_600.jpg" title="Circle of Sisters XV Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-15_600.jpg" alt="Circle of Sisters XV Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS XVI</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-16_600.jpg" title="Circle of Sisters XVI Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-16_600.jpg" alt="Circle of Sisters XVI Convention Poster" />
						</a>
					</div>
					<div class="fancybox-element">
						<p class="h5">COS XVII</p>
						<a class="fancybox" rel="cos_banners" href="/wp-content/uploads/2014/05/circle-of-sister-17_600.jpg" title="Circle of Sisters XVII Convention Poster">
							<img src="/wp-content/uploads/2014/05/circle-of-sister-17_600.jpg" alt="Circle of Sisters XVII Convention Poster" />
						</a>
					</div>
					<div class="clear"></div>
				</div>

					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$(".fancybox").fancybox({
								'openEffect' 	: 'fade',
								'closeEffect' 	: 'fade',
								'nextEffect' 	: 'elastic',
								'prevEffect' 	: 'elastic',
								'padding'		: 45,
								helpers			: {
									
									thumbs	: {
										width	: 50,
										height	: 50
									}
								},
							});
						});
					</script>

				<?php echo the_content(); ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
