;(function($) {
	
	var _custom_media = false,
		_orig_send_attachment = wp.media.editor.send.attachment;

	$('#banner_media').on('click', function( e ) {

		e.preventDefault();

		var button = $(this);
			_custom_media = true;

		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				
				$('#banner_id').val(attachment.id);
			} else {

				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}
 
		wp.media.editor.open(button);
		return false;
	});

	$('#icon_media').on('click', function( e ) {

		e.preventDefault();

		var button = $(this);
			_custom_media = true;

		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				
				$('#icon_url').val(attachment.url);
			} else {

				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}
 
		wp.media.editor.open(button);
		return false;
	});


	$('.add_media').on('click', function(){

		_custom_media = false;
	});


})( jQuery );