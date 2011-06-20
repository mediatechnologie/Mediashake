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
	
	/* */
		
	// Navigation
		$('#navigation #upload').click(function(){
			
			$('#curtain').css('display', 'block');
			$('#uploadbox').css('display', 'block');
			return false;
		
		});
		
			$('#curtain #uploadbox #type li').click(function(){
				
				var type = $(this).attr('id');
				
				$('#curtain #uploadbox #upload #' + type).css('display', 'block');
				$(this).parent().css('display', 'none');
				
			});
		
		// Login
		$('#navigation #login').toggle(function(){
			
			$(this).css('background-color','#FFF');
			$('#loginbox').css('display', 'block');
			
		}, function(){
			
			$(this).css('background-color','inherit');
			$('#loginbox').css('display', 'none');
			
		});
		
		
		// Account
		$('#navigation #account').toggle(function(){
			
			$(this).css('background-color','#FFF');
			$('#accountbox').css('display', 'block');
			
		}, function(){
			
			$(this).css('background-color','inherit');
			$('#accountbox').css('display', 'none');
			
		});
		
		
			// Login
			$('#login_form').submit(function(){
				
				var username = $('#login_form #username').val();
				var password = $('#login_form #password').val();
				
				$('#login_form .loading-indicator').css('visibility', 'visible');
				
				$.ajax({
					url: 'jsi.php',
					type: 'POST',
					data: {action: 'login', username: username, password: password},
					async: false,
					cache: false,
					success: function(data){
							$('#login_form .loading-indicator').css('visibility', 'hidden');
							
							if(data == 'OK')
								window.location.replace('index.php');
							else if(data == 'WRONG')
								alert('Incorrect username or password!');
							else
								alert('There was an error: ' + data);
					}
				});
				
				return false;
			});
		
		
		// Curtain
		$('#curtain .box .title').click(function(){
			$(this).parent().css('display', 'none');
			$(this).parent().parent().css('display', 'none');
		});
		
		
		// Rating stars
		$('.rate li').hover(function(){

				var id = $(this).attr('id');
				var item = 1;
	
				while(item <= id)
				{
					$('#' + item).addClass('active');
					item = item + 1;
				}
			},function(){
				
				$(this).parent().children().removeClass('active');
				
			});
		});