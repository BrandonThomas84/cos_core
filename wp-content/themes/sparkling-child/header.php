<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package sparkling
 */
?><!doctype html>
	<!--[if !IE]>
	<html class="no-js non-ie" <?php language_attributes();?>> <![endif]-->
	<!--[if IE 7 ]>
	<html class="no-js ie7" <?php language_attributes();?>> <![endif]-->
	<!--[if IE 8 ]>
	<html class="no-js ie8" <?php language_attributes();?>> <![endif]-->
	<!--[if IE 9 ]>
	<html class="no-js ie9" <?php language_attributes();?>> <![endif]-->
	<!--[if gt IE 9]><!-->
<html class="no-js" <?php language_attributes();?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo('charset');?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title('|', true, 'right');?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo('pingback_url');?>">

<!-- Google Font -->
<link href='http://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>

<!-- favicon -->

<?php if (of_get_option('custom_favicon')) {?>
<link rel="icon" href="<?php echo of_get_option('custom_favicon');?>" />
<?php }?>

<!--[if IE]><?php if (of_get_option('custom_favicon')) {?><link rel="shortcut icon" href="<?php echo of_get_option('custom_favicon');?>" /><?php }?><![endif]-->

<?php wp_head();?>

</head>

<body <?php body_class();?>>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="row">
					<div class="site-navigation-inner col-sm-12">
				        <div class="navbar-header">
				        	<?php if (get_header_image() != ''): ?>

								<div id="logo">
									<a href="<?php echo esc_url(home_url('/'));?>"><img src="<?php header_image();?>"  height="<?php echo get_custom_header()->height;?>" width="<?php echo get_custom_header()->width;?>" alt="<?php bloginfo('name');?>"/></a>
								</div><!-- end of #logo -->

							<?php endif; // header image was removed ?>

							<?php if (!get_header_image()): ?>

								<div id="logo">
									<span class="site-name"><a class="navbar-brand" href="<?php echo esc_url(home_url('/'));?>" title="<?php echo esc_attr(get_bloginfo('name', 'display'));?>" rel="home"><?php bloginfo('name');?></a></span>
								</div><!-- end of #logo -->

							<?php endif; // header image was removed (again) ?>
				            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				                <span class="sr-only">Toggle navigation</span>
				                <span class="icon-bar"></span>
				                <span class="icon-bar"></span>
				                <span class="icon-bar"></span>
				            </button>
				        </div>
					<?php sparkling_header_menu();?>
					</div>
		    </div>
		  </div>
		</nav><!-- .site-navigation -->
	</header><!-- #masthead -->
<?php
//countdown script
require_once __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'header_countdown.class.php';
$pd_header = new pdesigns_header_supplement;

?>
	<div id="content" class="site-content">
		<span id="countdown"></span>
		<span id="countdown-message"></span>
		<span id="header-message">

		</span>
		<div class="top-section">
			<?php sparkling_featured_slider();?>
			<?php sparkling_call_for_action();?>
		</div>

		<div class="container main-content-area">
			<div class="row">
				<div id="content" class="main-content-inner col-sm-12 col-md-8 <?php echo of_get_option('site_layout');?>">1