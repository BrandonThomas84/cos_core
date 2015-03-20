
jQuery(document).ready(function($){

	//maximize the window height
	var pageHeight  = $(window).height() - 300;

	if( $(".main-content-area").innerHeight() < pageHeight ){

		$(".main-content-area").css("min-height", pageHeight );
	}

	//highlight the active menu item
	var uri = new URI();
	var activePage = uri.pathname().replace(/\//g,"");

	$("a[title='"+activePage+"']").addClass("active");

	//move sidebar title above sidebar
	var sidebarTitle = $("#above-sidebar");
	$("#secondary").prepend( sidebarTitle );

	//Testing Javascript
	$(".testing-switch-image").click(function(){
		$(".testing-image").each( function(){
			$(this).toggleClass("hidden");
		});
	});

	$("#realperson-check").realperson({ 
	    length: 6, 
	    includeNumbers: true, 
	    regenerate: 'Click to Change',
	    hashName: 'realperson-check'
	});

 	$(".tigger-contact-form").click(function(){

 		var contactID = $(this).data('userId');
 		var contactName = $(this).data('name');
 		var contactTitle = $(this).data('title');
 		var subTitle = $(this).data('subtitle');
 		var contactType = $(this).data('contactType');
 		var allOption = $(this).data('allOption');

 		//set the message body to empty
 		//$("textarea[name='contact-form-message']").empty();

 		//set the title to the form
 		$("#form-contact-title").empty();
 		$("#form-contact-title").text( contactTitle );

 		//set the subtitle to the form
 		$("#contact-form-subtitle").empty();
 		$("#contact-form-subtitle").text( subTitle ); 		

 		//set form elements
 		$("#contact-form-contact-type").val( contactType );
 		$("#contact-form-userID").val( contactID );
 		$("#contact-form-formtitle").val( contactTitle );
 		$("input[name='contact-form-subtitle']").val( subTitle );	

 		$(document).scrollTop( 0 );

 		$("#darken-screen").removeClass("hidden");
 		$("#contact-form-container").removeClass("hidden");

 		//check for all option
 		if( allOption == 'checked' ){
 			$("input[name='contact-form-include-all']").prop("checked",true);
 			$("#include-all-on-list-container").removeClass("hidden");
 			$("#include-all-on-list-container label").empty();
 			$("#include-all-on-list-container label").text("Include All " + contactType + ' Members' );
 			$("#include-all-on-list-container label").css('textTransform', 'capitalize');
 		}
 		
 	});

 	$(".close-contact-form").click(function(){
 		$("#contact-form-container").animate({"opacity": 0},function(){
			$(this).addClass("hidden");
			$(this).css("opacity",1);
			$("#darken-screen").animate({"opacity": 0},function(){
				$(this).addClass("hidden");
				$(this).css("opacity",1);
				$("#contact-form-title").text("");
			});
 		}); 		
 	});

	$(".close-contact-message").click(function(){
		$(this).parent().animate({"opacity":0}, function(){
			$(this).remove();
			$("#darken-screen").animate("opacity",0, function(){
				$("#darken-screen").addClass("hidden");
			});
		});
	});

	$("#flipbox-back-button").click(function(){
		$(this).addClass("hidden");
		$("#flipbox-front-button").removeClass("hidden");

		$(".flipbox-element-1").addClass("hidden");
		$(".flipbox-element-2").removeClass("hidden");

		$("#flipbox").flip({
			direction:'rl',
			color: 'transparent',
		});
	});

	$("#flipbox-front-button").click(function(){
		$(this).addClass("hidden");
		$("#flipbox-back-button").removeClass("hidden");

		$(".flipbox-element-2").addClass("hidden");
		$(".flipbox-element-1").removeClass("hidden");

		$("#flipbox").flip({
			direction:'lr',
			color: 'transparent',
		});
	});

	

});
	