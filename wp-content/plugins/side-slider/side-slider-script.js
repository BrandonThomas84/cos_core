jQuery(document).ready(function($){
	
	//apply height to the slider container if text is vertical
	var sliderHeight = $(".side-tab").outerWidth();
	$(".side-content.vert-text").css("height", sliderHeight );

	//slider variables
	var container = $("#side-follow");
	var tab = $(container).children(".side-tab");
	var body = $(container).children(".side-content");
	var fullWidth = $(window).width();
	var bodyWidth = 300;
	var tabWidth = 40;
	var containerWidth = bodyWidth + tabWidth + 3;

	function showSlider(){
		$(container).animate({"width" : containerWidth+"px"});
		$(body).animate({"width" : bodyWidth+"px"}, function(){
			$(body).children(".side-content-inner").css({"display" :"block"});
			$(body).children(".side-content-inner").animate({"opacity" : 1 });
		});
	}

	function hideSlider(){
		$(body).children(".side-content-inner").css({"display" :"none"});
		$(body).animate({"width" : "0px"});
		$(container).animate({"width" : tabWidth+"px"});
	}

	//controls hover functionality for desktop application
	$(container).mouseenter(function(){
		showSlider();
	});

	//controls hover off functionality for desktop application
	$(body).mouseleave(function(){		
		hideSlider();	

	});

	//provides click functionality for mobile applications
	$(container).click(function(){
		showSlider();
	});

	//close box on click anywhere on the document
	$('html').click(function() {
		hideSlider();
	});

	//prevent the slider from closing when clicked on 
	$(container).click(function(event){
	    event.stopPropagation();
	});

});
