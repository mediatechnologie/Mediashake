$(function(){
	
	$("#addcomment textarea").TextAreaExpander();
	
	$('#addcomment input').click(function(){
		
		var comment = $('#addcomment textarea').val();
		comment.replace("\'","%27")
		comment = encodeURIComponent(comment);
		$.ajax({
				url: 'jsi.php',
				type: 'POST',
				data: {action: 'comment', work: <?php echo $_GET['id']; ?>, comment: comment},
				async: false,
				cache: false,
				success: function(data){
					$('#comments').prepend('\
					<li>\
						<img src="images/users/<?php echo $user_image; ?>.jpg"/>\
						<p class="author"><?php echo $_SESSION['user']['firstname'].' '.$_SESSION['user']['lastname']; ?></p>\
						' + decodeURIComponent(comment) + '\
					</li>\
					');
					$('#addcomment textarea').val('');
				}
			});
		
	});

	$('.rate li').click(function(){
		var rating = $(this).attr('id');
		$.ajax({
			url: 'jsi.php',
			type: 'POST',
			data: {action: 'rate', work: '<?php echo $_GET['id']; ?>', rating: rating},
			async: false,
			cache: false,
			success: function(data){
				alert('You have rated this work ' + rating + ' stars!');
			}
		});
	});
});