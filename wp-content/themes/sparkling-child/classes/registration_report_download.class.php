<?php



class cdg_download {

	public function downloadReport(){	

		//create the file
		$this->createCSV( $this->getData() );

		//set the filename
		$filename = 'COS_Registration_Report.csv';
		$filePointer = str_replace('classes','reports/', dirname(__FILE__) ) . $filename;

		header( 'Content-Type: application/octet-stream',true,200 );
		header( 'Content-Description: File Transfer' );	    
	    header( 'Content-Disposition: attachment; filename="' . basename( $filePointer ) . '"' );
	    header( 'Content-Transfer-Encoding: binary' );
	    header( 'Expires: 0' );
	    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	    header( 'Pragma: public' );
	    header( 'Content-Length: ' . filesize( $filePointer ) );

	    readfile($filePointer);
	    exit;
		
	}

	public function getData(){

		global $wpdb;

		$orderArray = array(
			'f' => 'first_name',
			'l' => 'last_name',
			'r' => '`order`.`post_date`'
			);

		$sql = 'SELECT DISTINCT `order`.* ,`m_status`.`meta_value` AS \'order_status\',`m_cart`.`meta_value` AS \'cart\',`m_first`.`meta_value` AS \'first_name\',`m_last`.`meta_value` AS \'last_name\',`m_email`.`meta_value` AS \'email\',`m_transaction_id`.`meta_value` AS \'transaction_id\',`m_total`.`meta_value` AS \'cart_total\',`m_ip`.`meta_value` AS \'ip_address\',`m_address`.`meta_value` AS \'address\',`m_coupon`.`meta_value` AS \'coupon_code\',`m_items`.`meta_value` AS \'line_items\'
			FROM `wp_posts` AS `order` 
			INNER JOIN `wp_postmeta` AS `m_status` ON `order`.`ID` = `m_status`.`post_id` AND `m_status`.`meta_key` = \'wpsc_order_status\'
			INNER JOIN `wp_postmeta` AS `m_cart` ON `order`.`ID` = `m_cart`.`post_id` AND `m_cart`.`meta_key` = \'wpsc_cart_items\'
			INNER JOIN `wp_postmeta` AS `m_first` ON `order`.`ID` = `m_first`.`post_id` AND `m_first`.`meta_key` = \'wpsc_first_name\'
			INNER JOIN `wp_postmeta` AS `m_last` ON `order`.`ID` = `m_last`.`post_id` AND `m_last`.`meta_key` = \'wpsc_last_name\'
			INNER JOIN `wp_postmeta` AS `m_email` ON `order`.`ID` = `m_email`.`post_id` AND `m_email`.`meta_key` = \'wpsc_email_address\'
			INNER JOIN `wp_postmeta` AS `m_transaction_id` ON `order`.`ID` = `m_transaction_id`.`post_id` AND `m_transaction_id`.`meta_key` = \'wpsc_txn_id\'
			INNER JOIN `wp_postmeta` AS `m_total` ON `order`.`ID` = `m_total`.`post_id` AND `m_total`.`meta_key` = \'wpsc_total_amount\'
			INNER JOIN `wp_postmeta` AS `m_ip` ON `order`.`ID` = `m_ip`.`post_id` AND `m_ip`.`meta_key` = \'wpsc_ipaddress\'
			INNER JOIN `wp_postmeta` AS `m_address` ON `order`.`ID` = `m_address`.`post_id` AND `m_address`.`meta_key` = \'wpsc_address\'
			INNER JOIN `wp_postmeta` AS `m_coupon` ON `order`.`ID` = `m_coupon`.`post_id` AND `m_coupon`.`meta_key` = \'wpsc_applied_coupon\'
			INNER JOIN `wp_postmeta` AS `m_items` ON `order`.`ID` = `m_items`.`post_id` AND  `m_items`.`meta_key` = \'wpspsc_items_ordered\'
			WHERE `order`.`post_type` = \'wpsc_cart_orders\' AND `order`.`post_status` = \'publish\'';

			//add order by
			$sql .= ' ORDER BY \'' . $orderArray[ $_GET['order'] ] . '\' ' . strtoupper( $_GET['direc'] );

			//retrieve results
			$data = $wpdb->get_results( $sql, ARRAY_A );
			return $data;
	}

	public function createCSV( $data ){

		
		//set file pointer
		$file = str_replace('classes','reports/', dirname(__FILE__) ) . 'COS_Registration_Report.csv';

		//attempts to open the file
		$resource = fopen( $file, 'w+' );

		//check for data
		if ( count( $data ) ) {
	        
	        // get header from keys
	        fputcsv( $resource, array_keys( $data[0] ) );
	        
	        //add the data to the csv
	        foreach( $data as $row ) {
	            fputcsv( $resource, $row );
	        }
	    }
	}
}
