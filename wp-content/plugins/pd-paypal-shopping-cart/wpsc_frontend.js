/* 
* @Author: Brandon Thomas
* @Date:   2014-08-14 11:50:45
* @Last Modified by:   Brandon Thomas
* @Last Modified time: 2014-08-14 11:55:44
*/

jQuery(document).ready(function($){
	
	//force registration to submit to a new page
	$(".wp_cart_checkout_button").parent("form").attr("target","_blank");

    function setRegistrationOption( e ){

		var optionName = $( e ).find('option:selected').val();
		var optionPrice = $( e ).find('option:selected').data("optionPrice");

		var buttonName = $( e ).parent().siblings(".reg-prd-add").find( "form.wp-cart-button-form input[name='product']");
		var buttonTmpName = $( e ).parent().siblings(".reg-prd-add").find( "form.wp-cart-button-form input[name='product_tmp']");
		var staticValue = $( e ).parent().siblings(".reg-prd-add").find( "form.wp-cart-button-form > input[name='static-value']");
		var buttonPrice = $( e ).parent().siblings(".reg-prd-add").find( "form.wp-cart-button-form input[name='price']");

		

		$( buttonName ).val( staticValue.val() + ' - ' + optionName );
		$( buttonTmpName ).val( staticValue.val() + ' - ' + optionName );
		$( buttonPrice ).val( $.trim( optionPrice ) );

		$( e ).parent().siblings(".reg-prd-detail").find(".price-value").empty();
		$( e ).parent().siblings(".reg-prd-detail").find(".price-value").text( '$' + optionPrice );
	}

	$("select.cos-cart-options").change( function(){
		setRegistrationOption( this );
	});
	

	$("select.cos-cart-options").each(function(){

		var buttonName = $( this ).parent().siblings(".reg-prd-add").find( "form.wp-cart-button-form > input[name='product']").val();
		var staticValue = $('<input>').attr({ type: 'hidden', name: 'static-value', value: buttonName }).appendTo( $( this ).parent().siblings(".reg-prd-add").find( "form.wp-cart-button-form") );

		setRegistrationOption( this );
	});

	//disables admin meta boxes on selection of variable price
	$("#pdesigns_variable_price").change(function(){

		if( this.checked ){
			$("#pdesigns_registered_reg_price").prop("disabled", true);
			$("#pdesigns_registered_event_show_early_price").prop("disabled", true);
			$("#pdesigns_registered_early_price").prop("disabled", true);
			$("#pdesigns_registered_event_early_deadline").prop("disabled", true);
		} else {
			$("#pdesigns_registered_reg_price").prop("disabled", false);
			$("#pdesigns_registered_event_show_early_price").prop("disabled", false);
			$("#pdesigns_registered_early_price").prop("disabled", false);
			$("#pdesigns_registered_event_early_deadline").prop("disabled", false);
		}
	});

	//add a required field to the form
	$("input[name='variable_price']").each(function(){
		var form = $(this).parent().siblings(".reg-prd-add").find("form");
		$( form ).find("input[type='submit']").addClass("hidden");
	});

	$("input[name='variable_price']").keyup(function(){
		var form = $(this).parent().siblings(".reg-prd-add").find("form");
		$( form ).find("input[name='price']").val( $(this).val() );
		$( form ).find("input[type='submit']").removeClass("hidden");
	});

	//popup display for products
	$(".popup-switch").click(function(){

		var pswitch = $(this);
		var pDescription = $(this).siblings(".popup-description");

		if( $(pDescription).hasClass("hidden") ){

			$("#darken-screen").removeClass("hidden");
			$("#darken-screen").animate({
				"opacity" : .95
			}, 400, function(){
				$(pDescription).removeClass("hidden");
				$(pDescription).animate("opacity", 1)	
			});			
		};

	});
	$(".popup-description > .close-modal").click(function(){

		$(this).parent().animate({
			"opactiy" : 0
		}, 400, function(){
			$(this).addClass("hidden");
			$("#darken-screen").animate({
				"opacity" : 0 
			}, 400, function(){
				$(this).addClass("hidden");
			})
		})
	});
});