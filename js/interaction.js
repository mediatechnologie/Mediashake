$(function(){
	
	// Showcase items
	
	$('#list').imagesLoaded(function(){
	
		$('#list').masonry({
			itemSelector: 'li',
			columnWidth: 220,
			isAnimated: true,
			gutterWidth: 15,
			isFitWidth: true,
			animationOptions: {
				duration: 350,
				easing: 'linear',
				queue: false
			}
		});
		
	});
	
/*	$('#list li .thumbnail-shadow').html('<img src="images/enlarge.png"/>');
	
	// Show preview button on hover
	$('#list li').hover(function(){
		var id = $(this).attr('id');
		$('#' + id + ' .thumbnail-shadow').attr('class', 'thumbnail-shadow-active');
	}, function(){
		$('#list li .thumbnail-shadow-active').attr('class', 'thumbnail-shadow');
	});
	
	// Open showcase lightbox
	$('#list li .thumbnail-shadow img').click(function(){
		var id = $(this).parent().parent().attr('id');
		console.log(id);
		alert(id);
	});

*/

	// Upload
	$('#upload #type input').click(function(){
		var type = $(this).attr('value');
		$('#upload #category').attr('value', type);
		$('#upload #upload_options').children().hide();
		$('#upload #upload_options #option_' + type).show();
	});
	
	$('#upload form').submit(function(){
		var category = $('#category').val()
		var title = $('#title').attr('value');
		var description = $('#description').val();
		switch(category)
		{
			case 'image':
				break;
			case 'video':
				var data = $('#video_url').attr('value');
				break;
			case 'website':
				var data = $('#website_url').attr('value');
				break;
			case 'document':
				break;
		}
		
		if(category == 'null' || title == '' || description == '' || data == '')
		{
			alert('You haven\'t filled in everything!');
			return false;
		}
	});
	
	
	// Love
	$('.meta .like').click(function(){
		
		var loves = Number($(this).text());
		var element = $(this);
		var id = $(this).parent().parent().attr('id').split('_');
		id = id[1];
		
		$.ajax({
			url: 'jsi.php',
			type: 'POST',
			data: {action: 'love', id: id},
			async: false,
			cache: false,
			success: function(data){
				if(data == 1)
				{
					var new_loves = loves+1;
					$(element).html(new_loves);
				}
				else
				{
				}
			}
		});
	});
});