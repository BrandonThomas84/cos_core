
jQuery(document).ready(function($){

	var regOption = $("#reg_event_add_option");
	var regOptionPrice = $("#reg_event_add_option_price");
	var regOptionButton = $("#reg_add_option_button");
	var regOptionInput = $("#pdesigns_registered_event_item_options");

	$( regOptionButton ).click(function(){

		//check if option name is empty
		if( $( regOption ).val().length == 0 ){

			//warn the user that they havent entered a name for the option
			alert( "You must enter a name for this option.");

		} else if( $( regOptionPrice ).val().length == 0 ){

			//warn the user that they havent entered a price
			alert( "You must enter a price for this option.");

		} else {

			$("#no-options-display").remove();

			//add the option and price to the input
			$( regOptionInput ).val( $(regOptionInput).val() + $( regOption ).val() + ',' + $( regOptionPrice ).val() + ';' );

			$("#reg_options_display").append("<div class='reg-option-element'><span class='remove-reg-option'>X</span><p class='option-name'>" + $( regOption ).val() + "</p><p class='option-price'>" + $( regOptionPrice ).val() + "</p></div>");

			$( regOption ).val('');
			$( regOptionPrice ).val('');
		}
	});

	$(".remove-reg-option").click(function(){
		
		//remove the display element
		$(this).parent().remove();

		//empty value for the input field
		var newValue = '';

		//create input field value
		$(".reg-option-element").each(function(){
			newValue += $(this).children(".option-name").text()+",";
			newValue += $(this).children(".option-price").text()+";";
		});

		$("#pdesigns_registered_event_item_options").val( newValue );

	});

});
	