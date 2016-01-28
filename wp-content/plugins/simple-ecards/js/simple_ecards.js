jQuery(document).ready(function($) {
	$('#simple_ecards_card_select').on('change', function() {
		var selectedID = $('#simple_ecards_card_select').val();
		var newImg = $('#card_' + selectedID).clone();
		$('#simple_ecards_card_wrapper img').replaceWith(newImg);
	});

	// Yay a custom validator.
	$('#simple_ecards_send_form input').on('blur', function() {
		// First we make sure all required fields are filled out
		if($(this).hasClass('required'))
		{
			if($(this).val() == '')
			{
				$(this).SEAddErrorMessage({message: 'Please fill out this field.'});
				
			}
			else
			{
				$(this).SERemoveErrorMessage();
			}
		}
				
		// Next we check emails
		if($(this).hasClass('email'))
		{
			if(!$(this).SECheckEmail())
			{
				$(this).SEAddErrorMessage({message: 'Please enter a valid email address.'});	
			}
			else
			{
				$(this).SERemoveErrorMessage();
			}
		}
	});

	$('#simple_ecards_submit').on('click', function(e) {
		e.preventDefault();
		if($('#simple_ecards_send_form .se_error').length != 0 || $('#simple_ecards_send_form .required').val() == '')
		{
			alert('There are some issues with the info you entered. Please double-check it and try again.');
		}
		
		else
		{
			// Make the nutton no longer clickable
			$('#simple_ecards_submit').attr('disabled', 'disabled');
			$('#simple_ecards_submit').val('Working');
			

			var SECard = $('#simple_ecards_card_select').val();
			var SESendTo = $('#simple_ecards_send_to').val();
			var SESendToName = $('#simple_ecards_send_to_name').val();
			var SEFrom = $('#simple_ecards_from').val();
			var SEFromName = $('#simple_ecards_from_name').val();
			var SESubject = $('#simple_ecards_subject').val();
			var SEMessage = $('#simple_ecards_message').val();
			var recaptcha_challenge_field = $('#recaptcha_challenge_field').val();
			var recaptcha_response_field = $('#recaptcha_response_field').val();
			

			var data = {
				'action' : 'simple_ecards_send',
				'card' : SECard,
				'send_to' : SESendTo,
				'send_to_name' : SESendToName,
				'send_from' : SEFrom,
				'send_from_name' : SEFromName,
				'subject' : SESubject,
				'message' : SEMessage,
				'recaptcha_challenge_field' : recaptcha_challenge_field,
				'recaptcha_response_field' : recaptcha_response_field
			};

			$.get(ajax_data['ajax_url'], data, function(response)
			{
				$('#simple_ecards_send_form').replaceWith(response);
			});
		}
	});
});

jQuery.fn.SEAddErrorMessage = function()
{
	var e = jQuery(this[0]);
	var args = arguments[0] || {};
	var msg = args.message;
	// Visual feedback
	e.parent().find('.se_error').remove();
	e.parent().append("<span class='se_error'>" + msg + "</span>");
	
	
}

jQuery.fn.SERemoveErrorMessage = function()
{
	var e = jQuery(this[0]);
	e.parent().find('.se_error').remove();

}

jQuery.fn.SECheckEmail = function()
{
	var e = jQuery(this[0]);
	var val = e.val();
	var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return regex.test(val);
}
