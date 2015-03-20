<?php

	// Enable WP_DEBUG mode
	//define('WP_DEBUG', true);

	// Enable Debug logging to the /wp-content/debug.log file
	//define('WP_DEBUG_LOG', true);

	// Disable display of errors and warnings 
	//define('WP_DEBUG_DISPLAY', false);
	//@ini_set('display_errors',1);

	// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
	//define('SCRIPT_DEBUG', true);

	//include meta boxes display functions
	require_once( 'classes/meta_box_functions.php');

	//include meta boxes save function
	require_once( 'classes/meta_save_functions.php');

	//include script and style sheets
	require_once( 'scripts.php');


	//get the current page
	$currentPage = explode('/' , $_SERVER['REQUEST_URI']);
	if( !empty( $currentPage[2] ) ){
		$currentPage = $currentPage[2];
	} else {
		$currentPage = 'home';
	}

	//create custom post types
	add_action( 'init', 'pdesigns_custom_post_types' );

	//check front end submissions
	add_action( 'init', 'frontend_submissions');

	//admin scripts
	add_action( 'admin_enqueue_scripts', 'pdesigns_admins_scripts' );

	//check for backen submissions
	add_action( 'admin_init', 'pdesigns_admin_submissions' );

	//create menu items
	add_action( 'admin_menu', 'pdesigns_get_menu_elements');

	//hook the meta boxes
	add_action( 'add_meta_boxes', 'add_pdesigns_meta_boxes' );

	//hook the meta box saves
	add_action( 'save_post', 'save_pdesigns_pretitle_meta_box' );
	add_action( 'save_post', 'save_pdesigns_subtitle_meta_box' );
	add_action( 'save_post', 'save_pdesigns_fund_raising_event_meta_box' );
	add_action( 'save_post', 'save_pdesigns_contacts_meta_box' );
	add_action( 'save_post', 'save_pdesigns_qr_code_meta_box' );
	add_action( 'save_post', 'save_pdesigns_advisors_meta_box' );

	//admin scripts hook
	add_action( 'admin_enqueue_scripts', 'pdesigns_admin_scripts' );


	//adds custom columns to the registered events page
	function custom_registered_event( $column, $post_id ) {
	    switch ( $column ) {

	        case 'dorder' :
	        	
	        	$displayOrder = get_post_meta( $post_id, 'pdesigns_registered_event_info', true );
	        	$displayOrder = json_decode( $displayOrder );
	        	$displayOrder = $displayOrder->display_order;

	            echo $displayOrder;

	            break;
	    }
	}


	//adds columns to the registered events page
	function add_new_registered_event_columns($registered_event){
		
	    $new_columns['cb'] = '<input type="checkbox" />';
	    $new_columns['title'] = 'Registration Event';
	    $new_columns['dorder'] = 'Display Order';
	    $new_columns['author'] = 'Author';
	    $new_columns['date'] = 'Creation Date';
	 
	    return $new_columns;
	}
	add_filter( 'manage_edit-registered_event_columns', 'add_new_registered_event_columns' );
	add_action( 'manage_registered_event_posts_custom_column' , 'custom_registered_event', 10, 2 );

	//create custom post types
	function pdesigns_custom_post_types(){

	}

	//create menu elements
	function pdesigns_get_menu_elements(){

		//get user role information
		$userRole = get_user_meta( get_current_user_id(), 'wp_capabilities', TRUE);
		$userInfo = get_role( $userRole[0] );

		//check if user is administrator
		if( empty( $userRole['administrator'] ) ||  !$userRole['administrator'] ){

			//if user is not admin remove menu items
			remove_menu_page( 'edit.php' ); 
			remove_menu_page( 'edit-comments.php' );
			remove_menu_page( 'themes.php' );
			remove_menu_page( 'tools.php' );
			remove_menu_page( 'options-general.php' );
			remove_menu_page( 'paupress_options' );


			remove_submenu_page( 'users.php', 'user-new.php' );
			remove_submenu_page( 'users.php', 'users.php' );
			remove_submenu_page( 'users.php', 'paupress_new_user' );
			remove_submenu_page( 'users.php', 'paupress_reports' );

			//check for permission to output order reports
			if( get_user_meta( get_current_user_id(), 'pd_order_reporting', true) !== 'true'){
				remove_submenu_page( 'edit.php?post_type=wpsc_cart_orders', 'edit.php?post_type=wpsc_cart_orders&page=wpsc-order-reporting' );
			}

			//check for permission to edit orders
			if( get_user_meta( get_current_user_id(), 'edit_orders', true) !== 'true'){
				remove_submenu_page( 'edit.php?post_type=wpsc_cart_orders', 'edit.php?post_type=wpsc_cart_orders' );
			}
		} else {
			//add the users addon menu
			users_addon();

		}
	}
	
	//assign the meta boxes to given pages
	function add_pdesigns_meta_boxes() {

		//post and page meta box
		$screens = array( 'post', 'page' );
		foreach ( $screens as $screen ) {

			//add pretitle meta box
			add_meta_box( 'pdesigns_pretitle', 'Insert Before Page Title', 'pretitle_meta_box', $screen, 'side','core');

			//add subtitle meta box
			add_meta_box( 'pdesigns_subtitle', 'Add a Subtitle', 'subtitle_meta_box', $screen, 'side','core');

			//add QR code meta box
			add_meta_box( 'pdesigns_qr_code', 'Display QR Code on Page', 'qr_code_meta_box', $screen, 'side','core');

			//check template
			if( isset( $_GET['post'] ) && get_post_meta( $_GET['post'], '_wp_page_template', TRUE) == 'page-contact.php' ) {
				//contact person meta box
				add_meta_box( 'pdesigns_contacts', 'Include Contact Persons', 'contact_person_meta_box', $screen, 'normal', 'high' );	
			}

			//check template
			if( isset( $_GET['post'] ) && get_post_meta( $_GET['post'], '_wp_page_template', TRUE) == 'page-advisory.php' ) {
				//contact person meta box
				add_meta_box( 'pdesigns_advisors', 'Include Advisors', 'advisor_person_meta_box', $screen, 'normal', 'high' );	
			}
		}

		//fund raising event information meta box
		add_meta_box( 'pdesigns_fund_raising', 'Fund Raising Event Info', 'fund_raising_meta_box', 'fund_raising_event', 'normal', 'high' );
	}

	function frontend_submissions(){

		//check for contact form submission
		if( isset( $_POST['realperson-raw'] ) ){
			require_once('classes/pdesigns_contact_form.class.php');
			$contact = new pdesigns_contact_form;
			$contact->checkSubmission();
		}
	}

	function pdesigns_admin_submissions(){

		//check for user association change
		if( isset( $_POST['user-change-assoc'] ) ){
			$userID = $_POST['user-change-assoc'];
			update_user_meta( $userID , 'c_group', $_POST['c_group_' . $userID  ] );
		}

		//check for password_reset
		if( isset( $_POST['cos-password-reset'] ) ){
			require_once('classes/pdesigns_user_management.class.php');
			$password = new pdesigns_user_management;
			$password->password_recovery( $_POST['cos-password-reset'], 10 );
		}

		
	}

	function pdesigns_admin_scripts(){
		//enqueue qr code generator javascript
		wp_enqueue_script( 'admin-qr-js', get_template_directory_uri() . '-child/js/qr.js', array('jquery'), $latestVersion, FALSE );
	}

	function users_addon(){

		require_once( 'classes/pdesigns_user_management.class.php' );

		//add menu item
		add_submenu_page( 'users.php', 'User Management', 'Manage Users', 'list_users', 'pd-user-manage', array('pdesigns_user_management','main_display') );
	}

	function pdesigns_admins_scripts(){

		global $latestVersion;

		//enqueue admin side scripts
		wp_enqueue_script( 'pdesigns-admin-js', get_template_directory_uri() . '-child/js/pdesigns-admin.js', array('jquery'), $latestVersion, FALSE );


		//enqueue admin side stylesheet
		wp_enqueue_style( 'sparkling-child-admin-style', get_template_directory_uri() . '-child/style/admin-style.css', null, $latestVersion);
	}

	add_action('template_redirect','template_download_file');
	function template_download_file() {

		if( $_SERVER['SCRIPT_URL'] == '/reports/download_reg_report' || $_SERVER['SCRIPT_URL'] == '/reports/download_reg_report/' ) {

			require_once( 'classes' . DIRECTORY_SEPARATOR . 'registration_report_download.class.php' );

			$download = new cdg_download;
			$download->downloadReport();
		} 
	}

	 function _cdg_log($content=null,$maintainFontSize=null){

        $time = date('Y-m-d - H:i:s');

        //active log filename
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        $fileName = 'cdg_log.txt';
        $file = $filePath . $fileName;      

        //attempt to open /and or create file
        $available = fopen( $file, 'a+' );
        if( $available ){
        	// Open the file to get existing content
	        $current = file_get_contents($file);

	        //if no content insert separator
	        if( empty( $content ) ){

	            $current .= "\n--------=======================================--------\n" . " " . $time .  "\n\n";

	        } else {
	            
	            //fixes array to string conversions
	            if( is_array( $content ) ){
	                $content = implode(' - ',$content);
	            }

	            //allows for noncapital letters
	            if( empty( $maintainFontSize ) ){
	                $content = strtoupper( $content );
	            } 

	            // Append a new person to the file
	            $current .= $content . " - " . " @ " . $time . " \n\n";
	        }

	        if( isset( $_FILES) ){

	            ob_start();
	            var_dump($_FILES);
	            $contents = ob_get_contents();
	            

	            if( !empty( $_FILES['error'] ) ){

	                var_dump($_FILES['error']);
	                $contents = ob_get_contents();
	                
	            }

	            ob_end_clean();
	            $current .= "\n\n" . $contents;

	        }

	        // Write the contents back to the file
	        file_put_contents($file, $current);

	        fclose( $available );
        }	        
    }



?>