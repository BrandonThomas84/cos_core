jQuery.noConflict();

// ADD NEW SEARCH ROW BY AJAX. JUST CLEANER THAN CLONING
function add_new_search_row(){
	
	var cur = parseInt(jQuery(this).attr('rel'),10);
	var tar = cur + 1;
	var pri = jQuery('.query-list').attr('id');
	
	jQuery('#'+cur+'-query-loader').show();
		 
	jQuery.post( 
    	paupressAdminAjax.ajaxurl, 
    	{ action : 'paupress_new_search_row', data : tar, paupress_admin_nonce : paupressAdminAjax.paupress_admin_nonce },
        function( response ) {
            // DISPLAY THE RESPONSE
            jQuery('#'+pri).append(response);
            jQuery('#'+cur+'-query-loader').hide();
            if ( cur > 1 ) {
            	jQuery('#'+cur+'-query-sub').fadeIn('fast');
            }
            jQuery('#'+cur+'-query-add').hide();
            //jQuery('#'+tar+'-query-add').on('click',add_new_search_row);
            //jQuery('#'+tar+'-query-sub').on('click',sub_search_row);
            //jQuery('.query-parent').on('change',select_search_form);
            paupress_bind_events();
        }
    );
	    	    	
};

// REMOVE A SEARCH ROW
function sub_search_row() {
	var cur = jQuery(this).attr('rel');
	var oby = jQuery('#'+cur).find('.i-field').val();
	
	// REMOVE THE ROW AND ANY APPENDED OPTIONS
	if ( oby !== null ) {
		jQuery('[name="order_by"] option[value="'+oby+'"]').each(function() {
			jQuery(this).remove();
		});
		jQuery('[name="order_by"]').trigger('liszt:updated');
	}
	jQuery('#'+cur).remove();
	
	// FIND THE LAST ROW AND BRING BACK THE PLUS BUTTON
	var maximum = null;
	
	jQuery('.query-list li').each(function() {
	  var value = parseFloat(jQuery(this).attr('id'));
	  maximum = (value > maximum) ? value : maximum;
	});
	
	if ( maximum < cur ) {
		jQuery('#'+maximum+'-query-add').show();
	}
	
}

// PULL THE CONTEXTUAL QUERY FIELDS VIA AJAX
function select_search_form() { 
	
	var fid = jQuery(this).val();
	var sid = jQuery(this).attr('id');
	var fname = jQuery('option:selected',this).text();
	var key = jQuery(this).parent().attr('title');
	
	// EMPTY OUT THE MAIN HOLDER'S ELEMENTS AND PLAY THE LOADER
	jQuery('#'+key+'-child').empty().html('<img src="'+paupressSearchAjax.ajaxload+'" />');
	 
	jQuery.post( 
    	paupressSearchAjax.ajaxurl, 
    	{ action : 'paupress_search_form', fid : fid, key : key, paupress_search_nonce : paupressSearchAjax.paupress_search_nonce },
        function( response ) {
            // DISPLAY THE RESPONSE
            jQuery('#'+key+'-child').html(response);
            //jQuery( 'input.paupress-date' ).datepicker({ dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true });
            paupress_bind_events();
            if ( jQuery('[name="order_by"] option[value="'+fid+'"]').length == 0 && jQuery('#'+sid+' option:selected').hasClass('orderable') ) {
            	jQuery('[name="order_by"] optgroup:first').prepend('<option value="'+fid+'">'+fname+'</option>');
            	jQuery('[name="order_by"]').trigger('liszt:updated');
            }
            
            //jQuery('#'+did+'-child .query-auto').selectToAutocomplete();
        }
    );
    	    
}

// REPORT UI TOGGLES
function paupress_report_ui_action(){
	
	// HIDE ALL FORM ELEMENTS, SET ALL TABS TO NEUTRAL
	jQuery('.drawer').hide();
	jQuery('#report-nav .nav-tab').removeClass('nav-tab-active');
	
	// GRAB THE TITLE, TARGET THE DESTINATION AND ENABLE/DISABLE REPORT FORM ELEMENTS AS NECESSARY
	var relTitle = jQuery(this).attr('title');
	if ( relTitle == 'group-edit' ) {
		jQuery('.gredit-user').show().removeAttr('disabled');
		jQuery('.gredit-action').show().removeAttr('disabled');
		
	} else if ( relTitle == 'group-action' ) {
		jQuery('.gredit-user').show().removeAttr('disabled');
		jQuery('.gredit-action').show().removeAttr('disabled');
		jQuery('#action-select').removeAttr('disabled');
		
	} else if ( relTitle == 'filter' && jQuery('.gredit-user:visible') ) {
		jQuery('.gredit-user').hide().attr('disabled', 'disabled');
		jQuery('.gredit-action').hide().attr('disabled', 'disabled');
	} 
	
	jQuery('#'+relTitle).slideToggle('fast');
	jQuery(this).addClass('nav-tab-active');
	
}

// ENABLE THE FIELDS FOR EDITING
function get_user_gredits() {
	if ( jQuery('#master-gredit').is(':checked') ) {
		jQuery('.gredit-user.subgredit').attr('checked', 'checked');
	} else {
		jQuery('.gredit-user.subgredit:checked').removeAttr('checked');
	}
};
function get_action_gredits() {
	if ( jQuery('#action-gredit').is(':checked') ) {
		jQuery('.gredit-action.subgredit').attr('checked', 'checked');
	} else {
		jQuery('.gredit-action.subgredit:checked').removeAttr('checked');
	}
};

// SAVE QUERIES
function save_report() { 
	
	//var title = jQuery('#paupress-query-title').val();
	//var query = jQuery('#paupress-query-transient').val();
	var fid = jQuery(this).closest('.report-saver').attr('id'); 
	
	// DISABLE THE FORM
	jQuery('#'+fid).submit( function() { return false; } );
	
	// CDATA TO AVOID VALIDATION ERRORS
	//<![CDATA[
	var str = jQuery('#'+fid).serialize();
	// ]]>
	
	// EMPTY OUT THE MAIN HOLDER'S ELEMENTS AND PLAY THE LOADER
	//jQuery('#save-query-form').empty().html('<img src="'+paupressReportAjax.ajaxload+'" />');
	
	jQuery.post( 
    	paupressAjax.ajaxurl, 
    	{ action : 'paupress_process_queries', data : str },
        function( response ) {
            jQuery('#saved-query').html(response).fadeIn('slow');
            setTimeout(function() {
            	jQuery('#saved-query').fadeOut('slow');
            }, 2000);
            
            // EMPTY OUT THE MAIN HOLDER'S ELEMENTS AND PLAY THE LOADER
            jQuery('#reports-queries-display').empty().html('<img src="'+paupressReportAjax.ajaxload+'" />');
             
            jQuery.post( 
            	paupressAjax.ajaxurl, 
            	{ action : 'paupress_process_queries', data : 'hi' },
                function( response ) {
                    jQuery("#reports-queries-display").html(response);
                    jQuery('#process-reports-queries').click(process_save_report);
                }
            );
        }
    );
	    
}

// PROCESS THE SAVING OF THE REPORTS
function process_save_report() { 
	
	//var title = jQuery('#paupress-query-title').val();
	//var query = jQuery('#paupress-query-transient').val();
	var fid = jQuery(this).closest('.report-saver').attr('id'); 
	
	// DISABLE THE FORM
	jQuery('#'+fid).submit( function() { return false; } );
	
	// CDATA TO AVOID VALIDATION ERRORS
	//<![CDATA[
	var str = jQuery('#'+fid).serialize();
	// ]]>
	
	// EMPTY OUT THE MAIN HOLDER'S ELEMENTS AND PLAY THE LOADER
	jQuery('#reports-queries-display').empty().html('<img src="'+paupressReportAjax.ajaxload+'" />');
	 
	jQuery.post( 
    	paupressAjax.ajaxurl, 
    	{ action : 'paupress_process_queries', data : str },
        function( response ) {
            jQuery("#reports-queries-display").html(response);
            //jQuery('#process-reports-queries').click(process_save_report);
        }
    );
	    
}


// PROCESS THE SAVING OF THE REPORTS
function process_user_imports() { 
	
	//var title = jQuery('#paupress-query-title').val();
	//var query = jQuery('#paupress-query-transient').val();
	var fid = jQuery(this).closest('.report-saver').attr('id'); 
	
	// DISABLE THE FORM
	jQuery('#'+fid).submit( function() { return false; } );
	
	// CDATA TO AVOID VALIDATION ERRORS
	//<![CDATA[
	var str = jQuery('#'+fid).serialize();
	// ]]>
	
	// EMPTY OUT THE MAIN HOLDER'S ELEMENTS AND PLAY THE LOADER
	jQuery('#reports-user-imports-display').empty().html('<img src="'+paupressReportAjax.ajaxload+'" />');
	 
	jQuery.post( 
    	paupressAjax.ajaxurl, 
    	{ action : 'paupress_process_saved_imports', data : str },
        function( response ) {
            jQuery('#reports-user-imports-display').html(response);
            //jQuery('#process-reports-queries').click(process_save_report);
        }
    );
	    
}


// LISTENER DURING LONG OPERATIONS THAT POLLS THE PHP SCRIPT AND RETURNS THE PROGRESS
function setListener(){
	jQuery.ajax({
		type : 'post',
		dataType : 'html',
		url : paupressPollAjax.ajaxurl,
		data : {
		action: 'paupress_poll_status',
		paupress_poll_nonce : paupressPollAjax.paupress_poll_nonce
		},
		success: function(data, status, xhr) {
			// do something, such as write out the data somewhere.
			jQuery('#progress').html(data);
			if ( jQuery('#working').length ) {
				setTimeout('setListener()', 2000);
				jQuery('#working');
			} else if ( jQuery('#done').length ) {
				jQuery('#done');
				jQuery('#loader').empty();
			}
		}, error: function(){
			setTimeout('setListener()', 5000);
		}
	});
 }


// AJAX FORM SUBMISSION FOR THE MAIN SEARCH
function paupress_report_submit() { 
	
	// GET THE ID OF CURRENT FORM
	// FOR PAGINATION, TAKE THE EXTRA ARGUMENTS FOR LOCATION AND RETURN
	if ( jQuery(this).hasClass('outside') ) {
		var fid = jQuery(this).attr('title');
		var relval = jQuery(this).attr('rel');
		var revval = jQuery(this).attr('rev');
		jQuery('#'+revval).val(relval);
		
	} else {
		var fid = jQuery(this).closest('.report-form').attr('id');
		
	}
	
	// MAKE TINY MCE SAVE ANY CONTENT WE'VE ENTERED
	if ( jQuery('.tmce-active').length ) {
		tinyMCE.triggerSave();
	}
	
	// FIRST, CLEAR OUT THE OLD FORMS
	if ( jQuery('.gredit_checks').length ) {
		jQuery('.gredit_checks').each( function () {
			jQuery('.gredit_checks').remove();
		});
	}
	if ( jQuery('.gredit_data').length ) {
		jQuery('.gredit_data').each( function () {
			jQuery('.gredit_data').remove();
		});
	}
	
	// LOOP THROUGH THE USER DATA FORM IF APPROPRIATE TO DO SO
	if ( jQuery(this).hasClass('grexappeal') ) {

			jQuery.each( jQuery('#report-data-array input').serializeArray(), function ( i, obj ) {
				jQuery('<input class="gredit_data" type="hidden">').prop( obj ).appendTo('#'+fid);
			} );

			jQuery.each( jQuery('#report-data-form input[type="checkbox"]').serializeArray(), function ( i, obj ) {
				jQuery('<input class="gredit_checks" type="hidden">').prop( obj ).appendTo('#'+fid);
			} );
	}
	
	if ( jQuery(this).hasClass('import-process') ) {
	
			jQuery.each( jQuery('#report-data-form select').serializeArray(), function ( i, obj ) {
				jQuery('<input class="gredit_data" type="hidden">').prop( obj ).appendTo('#'+fid);
			} );
			
			jQuery.each( jQuery('#report-data-form input[type="hidden"]').serializeArray(), function ( i, obj ) {
				jQuery('<input class="gredit_data" type="hidden">').prop( obj ).appendTo('#'+fid);
			} );

	}
		
    // DISABLE THE FORM
    jQuery('#'+fid).submit( function() { return false; } );
    
    // CDATA TO AVOID VALIDATION ERRORS
    //<![CDATA[
     var str = jQuery('#'+fid).serialize();
    // ]]>
    
    // EMPTY OUT THE MAIN HOLDER'S ELEMENTS AND PLAY THE LOADER
	jQuery('#report-display').empty().html('<div id="loader"><img src="'+paupressReportAjax.ajaxload+'" /></div><div id="progress"></div>');
	//jQuery('#report-display').innerHTML = '';
	//jQuery('#report-display').html('<div id="loader"><img src="'+paupressReportAjax.ajaxload+'" /></div><div id="progress"></div>');
    if ( jQuery(this).hasClass('import-process') ) {
    	
    	jQuery('#progress').html('in progress');
    	var marker = jQuery('#marker').val();
    	setTimeout('setListener()', 1000);
    	
    	// SUBMIT THE FORM DATA
    	jQuery.post( 
    		paupressReportAjax.ajaxurl, 
    		{ 
    			action : 'paupress_report_form', 
    			data : str, 
    			paupress_report_nonce : paupressReportAjax.paupress_report_nonce 
    		}
    	);
    
    } else {
    
        // SUBMIT THE FORM DATA
        jQuery.ajax({ 
    		type: 'POST', 
	    	url: paupressReportAjax.ajaxurl, 
	    	data: { 
	    		action : 'paupress_report_form', 
	    		data : str, 
	    		paupress_report_nonce : paupressReportAjax.paupress_report_nonce 
	    	},
	        success: function( response ) {
	            // DISPLAY THE RESPONSE
	            //jQuery('#report-display').innerHTML = '';
	            jQuery('#report-display').html(response);
	            /*jQuery('#report-display').find('script').each(function(i) {
	            	eval(jQuery(this).text());
	            });*/
	            jQuery('#report-display .content-view').click(inline_toggle_view);
	            jQuery('#report-display .toggle-view a').tipTip({defaultPosition: "top", edgeOffset: -15});
	            jQuery("#save-current-query").click(save_report);
	            jQuery('.report-go-go').click(paupress_report_submit);
	            // INITIALIZE THE GOOGLE MAP
	            if( jQuery('#gmap').is(':visible') ){//.length != 0
	                initialize();
	            }
	            
	            // INITIALIZE THE GOOGLE MAP
	            jQuery('.gmap-view').click(function(){
	            	//jQuery(this).removeClass('gmap');
	            	jQuery(this).addClass('gmap_resize');
	            	initialize();
	            });
	            
	            
	            // RESIZE GOOGLE MAP ONLOAD
	            jQuery('.gmap_resize').click(function(){
	            	resizeMap();
	            });
	        }, 
	        timeout: 60000
	    });
	}    
}

// TOGGLE DATE BY ABSOLUTE RANGE OR RELATIVE
function date_toggle() {
	var targ = jQuery(this).val();
	jQuery(this).closest('.panel').children('.date_field').find('input, select').attr('disabled', 'disabled');
	jQuery(this).closest('.panel').find('.date_field').hide();
	//jQuery('.date_field').hide().find('input, select').attr('disabled', 'disabled');
	jQuery('#'+targ).show().find('input, select').removeAttr('disabled');
}
function s_date_toggle() {
	var targ = jQuery(this).val();
	if ( 'date' != targ ) {
		jQuery('.s_date_field').hide().attr('disabled', 'disabled');
	} else {
		jQuery('.s_date_field').show().removeAttr('disabled');
	}
}


// WHEN THE DOM IS READY...
jQuery(document).ready(function () {
	
	// TOGGLE THE DATES
	jQuery('.query-list').on('change', '.date-toggle', date_toggle);
	jQuery('.query-list').on('change', '.timeframe-select', s_date_toggle);
	
	// LOAD THE ADDITIONAL QUERY FIELDS
	jQuery('.query-list').on('change', '.query-parent', select_search_form);
	
	// MANAGE THE SEARCH ROWS	
	jQuery('#paupress').on('click', '.query-add', add_new_search_row);
	jQuery('#paupress').on('click', '.query-sub', sub_search_row);
	
	// SELECT ALL USERS OR ACTIONS TO EDIT OR ACT UPON
	jQuery('#report-nav').on('click', '.nav-tab', paupress_report_ui_action);
	jQuery('#paupress').on('change', '#master-gredit', get_user_gredits);
	jQuery('#paupress').on('change', '#action-gredit', get_action_gredits);
	
	// MAIN AJAX SUBMISSION FORM
	jQuery('#paupress').on('click', '.report-go', paupress_report_submit);
	
	// PROCESS SAVED QUERIES
	jQuery('#paupress').on('click', '#save-current-query', save_report);
	jQuery('#paupress').on('click', '#process-reports-queries', process_save_report);
	
	// PROCESS SAVED QUERIES
	jQuery('#paupress').on('click', '#process-reports-user-imports', process_user_imports);
	
	// INTERFACE TOGGLES
	jQuery('#paupress').on('change', '#user-match-select', function(){
		if ( jQuery(this).val() != '' ) {
			var fvalu = jQuery(this).val();
			//console.log(fvalu);
			var fname = jQuery('option:selected',this).text();
			jQuery('#data-handler-options').fadeIn('slow');
			jQuery('#import-members-submit').addClass(fvalu).attr('disabled', 'disabled');
			jQuery('#import-members-msg-field').text(fname);
			jQuery('#import-members-msg').fadeIn('slow');
		}
	});
	jQuery('#import-preview-table').on('change', '.import-preview-select', function(){
		var svalu = jQuery(this).val();
		if ( jQuery('#import-members-submit').hasClass(svalu) ) {
			jQuery('#import-members-submit').removeClass(svalu).removeAttr('disabled');
			jQuery('#import-members-msg').fadeOut('fast');
		}
	});
	jQuery('#report-nav').on('click', '#filter-tab', function(){
		if ( jQuery('.query-parent-holder .chzn-container').css('width') == '0px' ) {
			jQuery('.query-parent-holder').each(function(){
				jQuery('.chzn-container',this).css('width','180px');
				jQuery('.chzn-drop',this).css('width','178px');
				jQuery('.chzn-drop .chzn-search').css('width','168px');
				jQuery('.chzn-drop .chzn-search input').css('width','168px');
			});
		}
	});
	
	/*
	jQuery('#paupress').on('click', '#filter', function(){
		if ( jQuery('#paupress-query-title').attr('value').length != 0 ) {
			jQuery('#save-current-query').removeClass('button').addClass('button-primary');
		}
	});
	*/

});
