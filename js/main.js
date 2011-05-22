$(document).ready(function(){
	
	// Init
	$('#register-form').hide();
	
	// Login form
	var login_visible = false;
	$('#navigation .login').click(function(){
		
		if(login_visible)
		{
			$('#login').css('visibility', 'hidden');
			login_visible = false
		}
		else
		{
			$('#login').css('visibility', 'visible');
			login_visible = true;
		}
		
	});
	
	// Register form
	$('#join-banner input:last-child').click(function(){
		$('#join-banner #register-form').slideDown(500);
		//$('#join-banner input:last-child').fadeOut(1000);
		alert('test');
	})
	
});