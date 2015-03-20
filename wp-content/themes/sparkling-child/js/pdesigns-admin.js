
jQuery(document).ready(function($){

	$("select[name^='c_group_']").change(function(){
		console.log("changed");
		$(this).parent("form").submit();
	});

	$("#cos-reset-pass").click(function(){
		confirm('Warning: This will reset the password for the selected user. An email with the new password will be sent to the user and they will be able to login with that.');
	});

});
	