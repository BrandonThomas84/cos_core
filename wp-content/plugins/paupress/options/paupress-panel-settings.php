<?php

add_filter( 'paupress_options_tabs', 'paupanels_settings_trans', 19, 1 );
function paupanels_settings_trans( $navigation ) {
	if ( array_key_exists( 'paupress_panel_settings', $navigation ) ) {
		unset( $navigation['paupress_panel_settings'] );
	}
	return $navigation;
}

function paupress_panel_settings_trans() {

	
	return apply_filters( 'paupress_panel_settings', array(
	
		array( 'meta' => array( 
								'source' => 'pauforms', 
								'meta_key' => 'paupanels_embed_title', 
								'name' => __( 'Embed Options', 'paupress' ), 
								'help' => '', 
								'description' => __( 'If you would rather have your forms processed on the page, this is for you', 'paupress' ), 
								'options' => array( 
													'field_type' => 'title',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'pauforms', 
								'meta_key' => 'paupanels_embed', 
								'name' => __( 'Embed panels on page', 'paupress' ), 
								'help' => '', 
								'options' => array( 
													'field_type' => 'checkbox',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'pauforms', 
								'meta_key' => 'paupanels_embed_height', 
								'name' => __( 'Default minimum height(px) for the forms', 'paupress' ), 
								'help' => '', 
								'options' => array( 
													'field_type' => 'text', 
													'class' => 'input-short', 
													'req' => false, 
													'public' => false, 
													'choices' => '200', 
								) 
		) ),
		
	));
	
}