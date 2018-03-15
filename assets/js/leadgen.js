;// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
// noinspection JSUnusedLocalSymbols
(function ($, window, document, undefined) {

	$(document).ready(function() {

		$('#leadgen_new_customer').submit(function(e) {

			// get the form data
			var form = $(this),
				data = $(this).serialize();

			var $target_message	= form.find('.leadgen-response-message');
			
			// process the form
			$.ajax({
				type		: 'POST',
				url			: leadgen_data.ajaxurl,
				data		: data,
				dataType	: 'json',
				encode		: true,
				success		: function( data ) {
					
					$target_message.html('');
					
					var $label_class = data.success ? 'success' : 'error';

					if ( typeof data.data.title !== "undefined" ) {
						
						if ( form.find('#leadgen-customer-name .leadgen-label--error').length === 0 ) {
							form.find('#leadgen-customer-name').append('<label class="leadgen-label--' + $label_class + '">' + data.data.title + '</label>');
						}

					} else {
						form.find('#leadgen-customer-name .leadgen-label--error').remove();
					}

					if ( typeof data.data.leadgen_customer_phone !== "undefined" ) {
						
						if ( form.find('#leadgen-customer-phone .leadgen-label--error').length === 0 ) {
							form.find('#leadgen-customer-phone').append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_phone + '</label>');
						}

					} else {
						form.find('#leadgen-customer-phone .leadgen-label--error').remove();
					}

					if ( typeof data.data.leadgen_customer_email !== "undefined" ) {
						
						if ( form.find('#leadgen-customer-email .leadgen-label--error').length === 0 ) {
							form.find('#leadgen-customer-email').append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_email + '</label>');
						}

					} else {
						form.find('#leadgen-customer-email .leadgen-label--error').remove();
					}

					if ( typeof data.data.leadgen_customer_budget !== "undefined" ) {
						
						if ( form.find('#leadgen-customer-budget .leadgen-label--error').length === 0 ) {
							form.find('#leadgen-customer-budget').append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_budget + '</label>');
						}

					} else {
						form.find('#leadgen-customer-budget .leadgen-label--error').remove();
					}

					if (data.success === true) {
						
						// Reset form
						if ($this[0]) {
							$this[0].reset();
						}

						if (typeof data.data.url !== "undefined") {
							window.location.href = data.data.url;
						}
					}

					console.log(data);

				}
			});

			/* .done(function(data) {
				
				form.prepend('<label class="leadgen-label--info">' + data.message + '</label>');

				console.log(data);

			} ); */

			// stop the form from submitting the normal way and refreshing the page
			e.preventDefault();
			return false;

		} );

	} );

} )(jQuery);