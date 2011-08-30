	// Criteria
	var keyword = '';
	var school = '';
	var type = '';
	var sort = '';
	var date = '';
	
	
$(document).ready(function(){

	function update(){
		
		$('#most-popular').html('');
		
		$(work).each(function(i, item){
		
			if(school == '' || school == item[7] || school == 0)
			{
				$('#most-popular').append('\
					<li>\
						<a href="?p=work&id=' + item[0] + '">\
							<img src="uploads/' + item[4] + '"/>\
							<p>' + item[3] + '</p>\
						</a>\
					</li>');
			}
			else
			{
				//alert(item[7]);
			}
			//alert(item[3]);
			
		});
		
	}
	
	update();	
		
		/*
		$('#schools li').click(function(){
			
			if($(this).attr('value') != 'reset')
			{
				$(this).parent().children().css('display','none');
				$('#schools .reset').css('display','block');
				
				school = $(this).attr('value');
				update();
			}
			else
			{
				$(this).parent().children().css('display','block');
				$('#schools .reset').css('display','none');
				
				school = '';
				update();
			}
		
			alert(school);
		
		});*/
		
		
		$('#schools li').click(function(){
			
			if(school == '')
			{
				$('#schools li').css('display','none');
			
				school = $(this).attr('value');
				update();
				
				$('#schools li:last-child').css('display','block');
			}
			else
			{
			window.location.replace("index.php?p=explore");
			}
			
			
	
		})
		

});