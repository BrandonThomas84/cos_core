<?php

function wp_cart_button_handler($atts){
	extract(shortcode_atts(array(
		'name' => '',
                'item_number' =>'',
		'price' => '',
		'shipping' => '0',
		'var1' => '',
		'var2' => '',
		'var3' => '',
                'button_image' => '',
	), $atts));

	if(empty($name)){
		return '<div style="color:red;">'.(__("Error! You must specify a product name in the shortcode.", "WSPSC")).'</div>';
	}
	if(empty($price)){
		return '<div style="color:red;">'.(__("Error! You must specify a price for your product in the shortcode.", "WSPSC")).'</div>';
	}
	return print_wp_cart_button_for_product($name, $price, $shipping, $var1, $var2, $var3, $atts);
}

function wp_cart_display_product_handler($atts)
{
    extract(shortcode_atts(array(
        'name' => '',
        'item_number' =>'',
        'price' => '',
        'shipping' => '0',
        'var1' => '',
        'var2' => '',
        'var3' => '',    
        'thumbnail' => '',
        'description' => '',
        'button_image' => '',
    ), $atts));

    if(empty($name)){
            return '<div style="color:red;">'.(__("Error! You must specify a product name in the shortcode.", "WSPSC")).'</div>';
    }
    if(empty($price)){
            return '<div style="color:red;">'.(__("Error! You must specify a price for your product in the shortcode.", "WSPSC")).'</div>';
    }
    if(empty($thumbnail)){
            return '<div style="color:red;">'.(__("Error! You must specify a thumbnail image for your product in the shortcode.", "WSPSC")).'</div>';
    }
    $currency_symbol = get_option('cart_currency_symbol');
    $button_code = print_wp_cart_button_for_product($name, $price, $shipping, $var1, $var2, $var3, $atts);
    $display_code = <<<EOT
    <div class="wp_cart_product_display_box">
        <div class="wp_cart_product_thumbnail">
            <img src="$thumbnail" />
        </div>
        <div class="wp_cart_product_display_bottom">
	        <div class="wp_cart_product_name">
	            $name
	        </div>
	        <div class="wp_cart_product_description">
	            $description
	        </div>
			<div class="wp_cart_product_price">
	        	{$currency_symbol}{$price}
	        </div>
			<div class="wp_cart_product_button">
	        	$button_code
			</div>
		</div>
    </div>
EOT;
    return $display_code; 
}

function wspsc_compact_cart_handler($args)
{
    $num_items = wpspc_get_total_cart_qty();
    $curSymbol = get_option('cart_currency_symbol');
    $checkout_url = get_option('cart_checkout_page_url');

    $output = "";
    $output .= '<div class="wpsps_compact_cart wpsps-cart-wrapper">';
    $output .= '<div class="wpsps_compact_cart_container">';	
    if($num_items>0){
            $cart_total = wpspc_get_total_cart_sub_total();
            $item_message = ($num_items == 1)? "Item" : "Items";
            $output .= $num_items . " " . $item_message;		
            $output .= '<span class="wpsps_compact_cart_price"> '. print_payment_currency($cart_total,$curSymbol).'</span>';
            if(!empty($checkout_url)){
                $output .= '<a class="wpsps_compact_cart_co_btn" href="'.$checkout_url.'">'.__("View Cart", "WSPSC").'</a>';
            }
    }
    else{
            $cart_total = 0;
            $output .= __("Cart is empty", "WSPSC");
            $output .= '<span class="wpsps_compact_cart_price"> '. print_payment_currency($cart_total,$curSymbol).'</span>';
    }
    $output .= '</div>';
    $output .= '</div>';
    return $output;
}

function wpsc_product_display( $atts=null){

    $return = '';

    //get the registered events
    $args = array(
        'post_type' => 'wpsc_product',
        'posts_per_page' => -1,
    );
    $registred_events = get_posts( $args );

    //counter for ordering the array
    $i = 0;

    //empty array for ordered events
    $ordered_events = array();

    //reorder by display order
    foreach ($registred_events as $event ) {

        //get display order
        $event_info = get_post_meta( $event->ID, 'pdesigns_registered_event_info', true );
        $display_order = json_decode( $event_info );

        //get display order
        $display_order = $display_order->display_order;

        //if display order is empty then make it the next counter
        if( empty( $display_order ) || array_key_exists( $display_order, $ordered_events ) ){
            $display_order = $i + 1000;
        } 

        //add event to ordered events with new key
        $ordered_events[ $display_order ] = array( $event_info, $event );

        //increase counter
        $i++;
    }
    
    //reorder events by display order
    ksort( $ordered_events );

    //start container
    $return .= '<div class="reg-prd-container reg-container">';
    $i = 1;
    foreach ( $ordered_events as $event ) {
        
        //get the event settings
        $event_info = $event[0];
        $event_info = json_decode( $event_info );

        $return .= '    <div class="reg-product">';
        $return .= '        <div class="reg-prd-title">';

        //the title
        $return .= '            <h3>' . $event[1]->post_title . '</h3>';
        $return .= '        </div>';
        $return .= '        <div class="reg-prd-img">';

        //check for thumb
        $thumb = get_the_post_thumbnail( $event[1]->ID );
        if( $thumb ){

            //check for post links and open the anchor
            if( !empty( $event_info->post_link ) && $event_info->post_link !== '' ){
                $return .= '<a href="javascript:void(0)" class="popup-switch">';
            }

            $return .= $thumb; 

            //check for post links and close the anchor
            if( !empty( $event_info->post_link ) && $event_info->post_link !== ''  ){
                $return .= '</a><p class="popup-indicator">click to view more info</p>';

                //get the linked post content
                $linking = get_post( $event_info->post_link, 'OBJECT', 'raw' );
                $linkDesc = $linking->post_content;

                //get the thumbnail
                $linkImg = get_the_post_thumbnail( $event_info->post_link );

                //output the display
                $return .= '
                    <div class="popup-description hidden">
                        <span class="close-modal">X</span>
                        <div class="popup-description-image">' . $linkImg . '</div>
                        <div class="popup-description-content">' . $linkDesc . '</div>
                    </div>';
            }
        }
        
        $return .= '        </div>';
        $return .= '        <div class="reg-prd-detail">';
        $return .= '            <div class="prd-description">' . $event[1]->post_content . '</div>';

        //check for variable price
        if( $event_info->variable_price == 'true'){

            //set a current price value for the button
            $curPrice = 1;

            $return .= '<div class="prd-price wide">Enter Amount</div><br>';
            $return .= '<label class="inline-label">$</label><input type="text" name="variable_price" placeholder="Ex: 10.00" required="required" style="width: 75%;">';

            $return .= '<div class="clear"></div>';
            $return .= '</div>'; // closes detail container

        } else {

            //set default values
            $regPriceClass = null;
            $curPrice = $event_info->reg_price;

            //check for early deadline
            if( !empty( $event_info->early_deadline ) ){

                $deadlineDisplay = date( 'D, M jS Y', strtotime( $event_info->early_deadline ) );

                //check for early price
                if( !empty( $event_info->early_price ) ){

                    $deadline = strtotime( $event_info->early_deadline );
                    $early_price = $event_info->early_price;

                    //check if deadline has passed
                    if( date( time() ) > $deadline ){

                        //check if price should be displayed despite deadline being passed
                        if( $event_info->show_early_price == 'Y' ){
                            
                            $earlyPriceClass = ' pricecrossout';

                        } else {
                            
                            $earlyPriceClass = ' hidden';

                        }   

                        $regPriceClass = null;

                    } else {

                        $earlyPriceClass = null;
                        $regPriceClass = ' hidden';
                        $curPrice = $event_info->early_price;
                    }
                    
                    //display price
                    $return .= '<div class="prd-price early-price' . $earlyPriceClass . '"><span class="price-title">Early Registration</span><span class="price-indicator">Price: </span><span class="price-value">$' . $event_info->early_price . '</span></div>';    
                }
            } else {
                $deadlineDisplay = '&nbsp';
            }

            $return .= '            <div class="prd-price"><span class="price-title" style="color: #999;font-size: 9px;line-height: 17px;">' . $deadlineDisplay . '</span><span class="price-indicator' . $regPriceClass . '">Price: </span><span class="price-value' . $regPriceClass . '">$' . $event_info->reg_price . '</span></div>';
            $return .= '        </div>'; //closes detail container

            //check for options
            if( !empty( $event_info->item_options ) ){

                $return .= '<div class="clear"></div>';
                $return .= '<div class="cos-item-options-container">';

                $return .= '<select class="cos-cart-options" name="cos-cart-options">';
                
                foreach( $event_info->item_options as $options ){
                    $name = $options->name;
                    $price = $options->price;

                    $return .= '<option value="' . $name . '" data-option-price=" ' . $price . '">' . ucfirst( $name ) . '</option>';
                }
                

                $return .= '</select>';
                $return .= '</div>';
            }
        }

        $return .= '        <div class="reg-prd-add">';

        //get the paypal button  wp_cart_button_handler
        $return .=  do_shortcode('[wp_cart_button name="' . $event[1]->post_title . '" price="' . $curPrice . '"]');

        $return .= '            <div class="prd-view-cart">View Cart</div>';
        $return .= '        </div>';
        $return .= '    </div>';

        if( is_int( $i / 3) ){
            $return .= '<div class="clear"></div>';
        }

        //increase counter
        $i++;
    }

    //end container
    $return .= '<div class="clear"></div>';
    $return .= '</div>';
    $return .= '<div class="clear"></div>';

    return $return;
}
