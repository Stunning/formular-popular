$(function(){
	// Save form pointer in variable
	var $form = $('form');
	
	// Add focused class to form element focused
	$('input, textarea', $form).focus(function() {
		$(this).addClass('focused');
	});
	
	$form.on('submit', function(e) {
		// Prevent form submit
		e.preventDefault();
		
		// Send form data to server
		$.post('submit.php', $form.serialize(), function(response) {
			
			// Remove error class on input elements
			$('input, textarea', $form).removeClass('error');
			
			// Remove previous messages
			$('.message', $form).detach();
			
			// Insert response message
			$('<p/>')
			.addClass('message')
			.addClass(response.status)
			.html(response.message)
			.prependTo($form);
			
			// Check if request was a success
			if(response.status !== 'success') {
				// Highlight fields where there were errors
				if(response.errors.length) {
					$.each(response.errors, function(i, error) {	
						$('*[name="'+error.field+'"]', $form).addClass('error');
					});
				}
			} else {
				// Remove form elements
				// on success
				$('.message', $form).siblings().detach();
			}
			
		}, 'json').error(function() {
			// Generic error message
			// if server returns status 500 code
			alert('Något gick fel på servern. Kontakta oss genom att ringa +46(0)8 121 382 55 eller maila hej@stunning.se.');
		});
		
		/* Alternative syntax:
		$.ajax({
			url: 'submit.php',
			type: 'POST',
			dataType: 'json',
			data, $form.serialize(),
			success: function(response){
				
			},
			error: function() {
				
			}
		});
		*/
	});
});