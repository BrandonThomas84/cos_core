jQuery(document).ready(function($){
	var convention = new Date(2015, 1, 27);
	$("#countdown").countdown({ 
    	until: convention,
    	format: 'OWDHMS',
    	padZeros: true,
	});
});
