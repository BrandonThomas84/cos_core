<?php

class WPSC_Registration_Reporting {
	
	//page display
	public function pageDisplay(){

		//set the reportSettings var
		$this->getReportType();

		//check if downloading
		if( $this->reportSettings['output'] == 'dl' ){

			$reportFile = ABSPATH . 'generated_order_report.txt';

			$fields = array(
					'ID' => 'Order ID',
					'first_name' => 'First Name',
					'last_name' => 'Last Name',
					'email' => 'Email Address',
					'transaction_id' => 'Transaction ID',
					'cart_total' => 'Total',
					'ip_address' => 'IP Address',
					'address' => 'Address',
					'line_items' => 'Line Items',
					'post_date' => 'Purchase Date',
					'post_modified' => 'Order Modification Date',
				);

			//header strig for file start
			$header = '';

			//string of data for ouput
			$data = '';

			//manually add paypal fee
			$fields['prd-paypalservicefee29'] = 'Paypal Service Fee (2.9%)';

			//add product name columns to report
			$products = $this->getProducts();
			foreach( $products as $product ){

				//create field value
				$fieldValue = $product->post_title;
				
				//create key
				$key = stringToSlug( $fieldValue );

				//add product to fields array
				$fields[ 'prd-' . $key ] = $fieldValue;

				//check for meta data
				if( !empty( $product->meta_value ) ){

					//convert to array
					$productOpts = json_decode( $product->meta_value );

					//check for options
					if( !empty( $productOpts->item_options ) ){

						foreach( $productOpts->item_options as $option ){
							
							//create key
							$key = stringToSlug( $product->post_title ) . ' - ' . stringToSlug( $option->name );

							//add product to fields array
							$fields[ 'prd-' . $key ] = $product->post_title . ' - ' . $option->name;
						}
					} 
				}
			}
			
			//cycle through fields for header
		    foreach( $fields as $key => $alias ){
		    	$header .= $alias . "\t";
		    }
		    $header .= "\n";
		    //var_dump( $fields );

		    file_put_contents($reportFile, $header);

    		//get the orders
			$orders = $this->getData();

		    //loop through data rows
		    foreach( $orders as $value ){	

		    	//compile data
		    	$line = '';

		    	//cycle through fields
		    	foreach( $fields as $key => $alias){

		    		//check if field is product
		    		if( strpos( $key, 'prd-' ) !== false ){

		    			$columnVal = 0;

		    			$cartInfo = unserialize( $value->cart );

		    			//check if there are products in the cart
						if( !empty( $cartInfo ) ){

							//loop through products to check for purchases
							foreach( $cartInfo as $cItem ){

								if( $cItem['name'] == $alias ){
									$columnVal = $cItem['quantity'];
								} 
							}
						}

						$columnVal = str_replace( '"' , '' , $columnVal );
				        $columnVal = '"' . $columnVal . '"' . "\t";

		    		} else { //normal field values

			    		//if the field is empty
			    		if ( ( !isset( $value->$key ) ) || ( $value->$key == "" ) ){

				            $columnVal =  " \t";

				        } else {
				            
				            $columnVal = str_replace( '"' , '""' , $value->$key );
				            $columnVal = '"' . $columnVal . '"' . "\t";

				        }
				    }

			        $line .= $columnVal;

		    	}

		    	$line .= "\n";
		    	$line = str_replace( "\r" , "" , $line);
		    	file_put_contents($reportFile, $line, FILE_APPEND );
		    }
		    echo '<h2>Report Created Successfully!</h2>';
		    echo '<p class="h4">Click the link below to download</p>';
		    echo '<a class="btn btn-primary" title="Click here to download your report" download="generated_order_report.txt" href="/generated_order_report.txt">Download Report</a>';
		    

		} else {

			//check for current page
			if( $this->reportSettings['output'] == 'home' ){
				
				$title = 'PD Simple Paypal Cart Order Management';
				$subtitle = null;
				$body = $this->homeDisplay();

			} elseif( $this->reportSettings['output'] == 'display' ){

				$title = 'PDSPC Report - ';
				$subtitle = $this->reportSettings['order'][1] . ' in ' . $this->reportSettings['direc'][1];
				$body = $this->displayData();
			}


			echo '<h1>' . $title . '</h1>';

			if( !empty( $subtitle ) ){
				echo '<h2>' . $subtitle . '</h2>';
			}

			echo '<div class="cdg-regReport-main">';
			echo '<div class="cdg-regReport-inner">';

			//output the main content
			echo $body;

			echo '</div><!-- CLOSE INNER-->';
			echo '</div><!-- CLOSE MAIN -->';
		}
	}

	//outputs home page
	public function homeDisplay(){
		
		//reg page html 
		$html = file_get_contents( WP_CART_URL . '/html/order_reporting.html');

		//return string
		return $html;
	}

	//sets the report settings
	public function getReportType(){

		//set var defaults
		$this->reportSettings = array(
			'post_type' => 'wpsc_cart_orders',
			'page' 		=> 'cdg-reg-reporting',
			'output' 	=> 'home',
			'order' 	=> array( 'post_date', 'Order Date'),
			'direc' 	=> array( 'asc', 'Ascending Order'),
			'cdgFormat' => array( 'csv', 'CSV File')
		);

		//set vars based on URL
		if( isset( $_GET['page'] ) ){
			$this->reportSettings['page'] = $_GET['page'];
		}
		if( isset( $_GET['output'] ) ){
			$this->reportSettings['output'] = $_GET['output'];
		}
		if( isset( $_GET['direc'] ) ){
			if( $_GET['direc'] == 'asc'){ $friendly = 'Ascending Order'; } else { $friendly = 'Descending Order'; }
			$this->reportSettings['direc'] = array( $_GET['direc'], $friendly );
		}
		if( isset( $_GET['cdgFormat'] ) ){
			if( $_GET['cdgFormat'] == 'csv'){ $friendly = 'CSV File'; } else { $friendly = 'CSV File (excel)'; }
			$this->reportSettings['cdgFormat'] = array( $_GET['cdgFormat'], $friendly );
		}
		if( isset( $_GET['order'] ) ){

			if( $_GET['order'] == 'f' ){
				$this->reportSettings['order'] = array( 'first_name', 'First Name');	
			} elseif( $_GET['order'] == 'l' ){
				$this->reportSettings['order'] = array( 'last_name', 'Last Name');	
			} elseif( $_GET['order'] == 'r' ){
				$this->reportSettings['order'] = array( 'post_date', 'Order Date');	
			} 
		}
	}

	//returns data set object 
	public function getData( $start = 0, $run = 100000 ){

		//set max execution time
		set_time_limit(0); 

		//include wordpress database connection
		global $wpdb;

		//if is display add limits
		if( $this->reportSettings['output'] == 'display' ){

			//set per page
			if( isset( $GET['ppg'] ) ){

				$this->perPage = $GET['ppg'];
			} else {

				$this->perPage = 50;
			}

			//set the run to equal the per page amount
			$run = $this->perPage;

			//set page number
			if( isset( $GET['pg'] ) ){

				$this->pageNum = $GET['pg'];
				
			} else {

				$this->pageNum = 1;
			}

			//set the start number
			$start = ( $this->pageNum * $this->perPage ) - $this->perPage;

		}
		
		//create query for data set
		$sql = 'SELECT DISTINCT `order`.* ,`m_cart`.`meta_value` AS \'cart\',`m_first`.`meta_value` AS \'first_name\',`m_last`.`meta_value` AS \'last_name\',`m_email`.`meta_value` AS \'email\',`m_transaction_id`.`meta_value` AS \'transaction_id\',`m_total`.`meta_value` AS \'cart_total\',`m_ip`.`meta_value` AS \'ip_address\',`m_address`.`meta_value` AS \'address\',`m_items`.`meta_value` AS \'line_items\'
			FROM `wp_posts` AS `order` 
			INNER JOIN `wp_postmeta` AS `m_cart` ON `order`.`ID` = `m_cart`.`post_id` AND `m_cart`.`meta_key` = \'wpsc_cart_items\'
			INNER JOIN `wp_postmeta` AS `m_first` ON `order`.`ID` = `m_first`.`post_id` AND `m_first`.`meta_key` = \'wpsc_first_name\'
			INNER JOIN `wp_postmeta` AS `m_last` ON `order`.`ID` = `m_last`.`post_id` AND `m_last`.`meta_key` = \'wpsc_last_name\'
			INNER JOIN `wp_postmeta` AS `m_email` ON `order`.`ID` = `m_email`.`post_id` AND `m_email`.`meta_key` = \'wpsc_email_address\'
			INNER JOIN `wp_postmeta` AS `m_transaction_id` ON `order`.`ID` = `m_transaction_id`.`post_id` AND `m_transaction_id`.`meta_key` = \'wpsc_txn_id\'
			INNER JOIN `wp_postmeta` AS `m_total` ON `order`.`ID` = `m_total`.`post_id` AND `m_total`.`meta_key` = \'wpsc_total_amount\'
			INNER JOIN `wp_postmeta` AS `m_ip` ON `order`.`ID` = `m_ip`.`post_id` AND `m_ip`.`meta_key` = \'wpsc_ipaddress\'
			INNER JOIN `wp_postmeta` AS `m_address` ON `order`.`ID` = `m_address`.`post_id` AND `m_address`.`meta_key` = \'wpsc_address\'
			INNER JOIN `wp_postmeta` AS `m_items` ON `order`.`ID` = `m_items`.`post_id` AND  `m_items`.`meta_key` = \'wpspsc_items_ordered\'
			WHERE `order`.`post_type` = \'' . $this->reportSettings['post_type'] . '\' AND `order`.`post_status` = \'publish\'';

		//add order by
		$sql .= ' ORDER BY ' . $this->reportSettings['order'][0] . ' ' . $this->reportSettings['direc'][0];

		//add limit
		$sql .= ' LIMIT ' . $start . ',' . $run;

		//retrieve results
		$results = $wpdb->get_results( $sql );

		//return results
		return $results;
	}

	//returns array of product names for linear reporting
	public function getProducts(){

		//set max execution time
		set_time_limit(0); 

		//include wordpress database connection
		global $wpdb;
		
		//create query for data set
		$sql = 'SELECT DISTINCT `products`.`post_title`, `options`.`meta_value` FROM `wp_posts` AS `products` INNER JOIN `wp_postmeta` AS `options` ON `options`.`meta_key` = \'pdesigns_registered_event_info\' AND `options`.`post_id` = `products`.`ID` WHERE `products`.`post_type` = \'wpsc_product\' ORDER BY `products`.`post_title`';

		//retrieve results
		$results = $wpdb->get_results( $sql );

		//return results
		return $results;
	}

	public function displayData(){

		//retrieve database information
		$data = $this->getData();

		$return = array();

		$return[] = '<table id="cos-reg-report-display">';
		$return[] = '<thead>';
		$return[] = '<tr>';

		//output the table header
		$return[] = '<th id="cos-regtable-oid">Order ID</th>';
		$return[] = '<th id="cos-regtable-tid">Transaction ID</th>';
		$return[] = '<th id="cos-regtable-rdate">Registration Date</th>';
		$return[] = '<th id="cos-regtable-mdate">Modified Date</th>';
		$return[] = '<th id="cos-regtable-recp">Receipt</th>';
		$return[] = '<th id="cos-regtable-status">Status</th>';
		$return[] = '<th id="cos-regtable-cpn">Coupon Code Used</th>';
		$return[] = '<th id="cos-regtable-crt">Cart Info</th>';		
		$return[] = '<th id="cos-regtable-person">Registrar Info</th>';

		$return[] = '</tr>';

		//output the data
		$return[] = '<tbody>';

		
		foreach( $data as $order ){

			$return[] = '<tr>';

			$return[] = '<td class="cos-regcol-oid"><p>' . $order->ID . '</p></td>';
			$return[] = '<td class="cos-regcol-tid"><p>' . $order->transaction_id . '</p></td>';
			$return[] = '<td class="cos-regcol-rdate"><p>' . date('M d, Y', strtotime( $order->post_date ) ) . '</p></td>';
			$return[] = '<td class="cos-regcol-mdate"><p>' . date('M d, Y', strtotime( $order->post_modified ) ) . '</p></td>';
			$return[] = '<td class="cos-regcol-recp"><a class="button button-primary" href="/registration/order-receipt/?oid=' . $order->ID . '" target="_blank"> View Receipt</a></td>';
			$return[] = '<td class="cos-regcol-status"><p>' . $order->order_status . '</p></td>';
			$return[] = '<td class="cos-regcol-cpn"><p>' . $order->coupon_code . '</p></td>';

			//create the cart info
			$cartInfo = unserialize( $order->cart );
			$cartItems = '<div class="cos-reg-cart-items">';
			$cartItems .= '<table class="cos-reg-item"><thead><tr><th class="item-name">Item:</th><th class="item-price">Price</th><th class="item-qty">Qty</th></tr></thead><tbody>';
			
			//check if there are products in the cart
			if( !empty( $cartInfo ) ){
				foreach( $cartInfo as $cItem ){
					
					$cartItems .= '<tr class="cos-reg-item-row">';

					$cartItems .= '<td class="item-name">' . $cItem['name'] . '</td>';
					$cartItems .= '<td class="item-price">' . $cItem['price'] . '</td>';
					$cartItems .= '<td class="item-qty">' . $cItem['quantity'] . '</td>';

					$cartItems .= '</tr>';
				}
			}

			$cartItems .= '</tbody></table>';
			$cartItems .= '</div>';


			$return[] = '<td class="cos-regcol-crt">' . $cartItems . '</td>';
			$return[] = '<td class="cos-regcol-person">' . $order->first_name . '</td>';

			$return[] = '</tr>';
		}

		/* SQL RESULT FIELDS
		ID	post_author	post_date	post_date_gmt	post_content	post_title	post_excerpt	post_status	comment_status	ping_status	post_password	post_name	to_ping	pinged	post_modified	post_modified_gmt	post_content_filtered	post_parent	guid	menu_order	post_type	post_mime_type	comment_count	order_status	cart	first_name	last_name	email	transaction_id	cart_total	ip_address	address	coupon_code	line_items
		*/

		$return[] = '</tbody>';
		$return[] = '</table>';

		//implode array
		$return = implode( null, $return );

		return $return;
	}
}

?>