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
	
	$('#list li .thumbnail-shadow').html('<img src="images/enlarge.png"/>');
	
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

});