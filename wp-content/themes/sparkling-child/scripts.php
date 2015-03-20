<?php

	//variable used for insuring newest version of script and stylesheets is always loaded
	$latestVersion = date('U');

	//enqueue uri manipulation javascript
	wp_enqueue_script( 'uri-js', get_template_directory_uri() . '-child/js/uri.js', array('jquery'), $latestVersion, FALSE );

	//enqueue core javascript
	wp_enqueue_script( 'sparkling-child-core-js', get_template_directory_uri() . '-child/js/sparkling-core.js', array('jquery','uri-js'), $latestVersion, FALSE );

	//enqueue debouncing javascript
	wp_enqueue_script( 'debounce-js', get_template_directory_uri() . '-child/js/jquery.ba-dotimeout.js', array('jquery'), $latestVersion, FALSE );

	//enqueue qr code generator javascript
	wp_enqueue_script( 'qr-js', get_template_directory_uri() . '-child/js/qr.js', array('jquery'), $latestVersion, TRUE );

	//core style
	wp_enqueue_style( 'sparkling-child-core-style', get_template_directory_uri() . '-child/style/core.css', NULL, $latestVersion);

	//enqueue responsive styling
	wp_enqueue_style( 'sparkling-child-responsive-style', get_template_directory_uri() . '-child/style/responsive.css', array('sparkling-child-core-style'), $latestVersion);

	//realperson style
	wp_enqueue_style( 'real-person-style', get_template_directory_uri() . '-child/style/realperson.css', NULL, $latestVersion);
	//realperson javascript
	wp_enqueue_script( 'real-person-js', get_template_directory_uri() . '-child/js/realperson.js', array('jquery'), $latestVersion, FALSE );

	//jquery flipper
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script( 'jquery-flipper', get_template_directory_uri() . '-child/js/jquery.flip.min.js', array('jquery','jquery-ui-core'), $latestVersion, TRUE );


	//fancy box linking
	wp_enqueue_script( 'pdesigns-fancybox-mousewheel', get_template_directory_uri() . '-child/js/jquery.mousewheel-3.0.6.pack.js?v=' . $latestVersion, array('jquery'), $latestVersion, TRUE );

	wp_enqueue_script( 'pdesigns-fancybox-core', get_template_directory_uri() . '-child/js/jquery.fancybox.js?v=' . $latestVersion, array('jquery','pdesigns-fancybox-mousewheel'), $latestVersion, TRUE );

	wp_enqueue_script( 'pdesigns-fancybox-buttons', get_template_directory_uri() . '-child/js/jquery.fancybox-buttons.js?v=' . $latestVersion, array('jquery','pdesigns-fancybox-mousewheel','pdesigns-fancybox-core'), $latestVersion, TRUE );

	wp_enqueue_script( 'pdesigns-fancybox-media', get_template_directory_uri() . '-child/js/jquery.fancybox-media.js?v=' . $latestVersion, array('jquery','pdesigns-fancybox-mousewheel','pdesigns-fancybox-core','pdesigns-fancybox-buttons'), $latestVersion, TRUE );

	wp_enqueue_script( 'pdesigns-fancybox-thumbs', get_template_directory_uri() . '-child/js/jquery.fancybox-thumbs.js?v=' . $latestVersion, array('jquery','pdesigns-fancybox-mousewheel','pdesigns-fancybox-core','pdesigns-fancybox-buttons','pdesigns-fancybox-media'), $latestVersion, TRUE );

	wp_enqueue_style( 'pdesigns-fancybox-core-style', get_template_directory_uri() . '-child/style/jquery.fancybox.css?v=' . $latestVersion, NULL, $latestVersion);
	wp_enqueue_style( 'pdesigns-fancybox-thumbs-style', get_template_directory_uri() . '-child/style/jquery.fancybox-thumbs.css?v=' . $latestVersion, NULL, $latestVersion);
	wp_enqueue_style( 'pdesigns-fancybox-buttons-style', get_template_directory_uri() . '-child/style/jquery.fancybox-buttons.css?v=' . $latestVersion, NULL, $latestVersion);

?>