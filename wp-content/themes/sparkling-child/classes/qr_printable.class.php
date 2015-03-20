<?php

class qr_printable {
	public function __construct( $link, $title ){

		//set the link to generate the qr code
		$this->link = base64_decode( $link );
		$this->title = base64_decode( $title );

	}

	public function display_output(){

		//start page output
		echo '<!DOCTYPE html>';
		echo '<html>';
		echo '<head>';
		echo '<title>Circle of Sisters - QR Code Printable</title>';
		echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>';
		echo '<script type="text/javascript" src="/wp-content/themes/sparkling-child/js/qr.js"></script>';
		echo '<link href="http://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet" type="text/css">';
		echo '<style> h1 {font-family: \'Poiret One\', cursive; font-size: 50px; color: #999; border-bottom: 1px solid #999; width: 80%; margin: 10px auto 20px auto; text-align: center;} .header {width: 100%; text-align: center; margin: 30px 0; border-bottom: 2px solid #efefef;} #qr-code-display canvas {width: 65%; margin: 40px auto; display: block;}</style>';
		echo '</head>';
		echo '<body>';

		echo '<div class="header"><img src="http://cos/wp-content/uploads/2014/05/cos_logo_head.png"></div><h1>View ' . $this->title . '</h1>';
		echo '<div id="qr-code-display"></div>';
		echo '<script>jQuery( document ).ready(function($){ $("#qr-code-display").qrcode("' . $this->link . '");})</script>';
				
		echo '</body>';
		echo '</html>';
	}
}


//display page output
if( isset( $_GET['link'] ) ){

	//instantiate the class
	$qr = new qr_printable( $_GET['link'], $_GET['title'] );

	//output display
	$qr->display_output();

} else {

	header("HTTP/1.0 404 Not Found");
	//start page output
	echo '<!DOCTYPE html>';
	echo '<html>';
	echo '<head>';
	echo '<title>Circle of Sisters - QR Code Missing Link</title>';
	echo '</head>';
	echo '<body>';

	echo '<h1>Whoops!</h1>';
	echo '<p>Looks like your missing some information. If you were directed here from a link please return to the page and try again. If you still receive an error please report this to your web administrator.</p>';
			
	echo '</body>';
	echo '</html>';
	
}


?>