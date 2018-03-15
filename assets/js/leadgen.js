;// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
// noinspection JSUnusedLocalSymbols
(function ($, window, document, undefined) {

	$(document).ready(function() {

		$('#leadgen_new_customer').submit(function(e) {

			// get the form data
			var form = $(this),
				data = $(this).serialize();

			var form_title		= form.find('.leadgen-title'),
				field_name		= form.find('#leadgen-customer-name'),
				field_phone		= form.find('#leadgen-customer-phone'),
				field_email		= form.find('#leadgen-customer-email'),
				field_budget	= form.find('#leadgen-customer-budget');
			
			// process the form
			$.ajax({
				type		: 'POST',
				url			: leadgen_data.ajaxurl,
				data		: data,
				dataType	: 'json',
				encode		: true,
				success		: function( data ) {
					
					var $label_class = data.success ? 'success' : 'error';

					if ( typeof data.data.title !== "undefined" ) {
						
						if ( form.find('#leadgen-customer-name .leadgen-label--error').length === 0 ) {
							field_name.append('<label class="leadgen-label--' + $label_class + '">' + data.data.title + '</label>');
						}

						field_name.find('.leadgen-input').addClass('leadgen-has_' + $label_class);

					} else {
						
						form.find('#leadgen-customer-name .leadgen-label--error').remove();
						field_name.find('.leadgen-input').removeClass('leadgen-has_error');

					}

					if ( typeof data.data.leadgen_customer_phone !== "undefined" ) {
						
						if ( form.find('#leadgen-customer-phone .leadgen-label--error').length === 0 ) {
							field_phone.append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_phone + '</label>');
						}

						field_phone.find('.leadgen-input').addClass('leadgen-has_' + $label_class);

					} else {
						
						form.find('#leadgen-customer-phone .leadgen-label--error').remove();
						field_phone.find('.leadgen-input').removeClass('leadgen-has_error');

					}

					if ( typeof data.data.leadgen_customer_email !== "undefined" ) {
						
						if ( form.find('#leadgen-customer-email .leadgen-label--error').length === 0 ) {
							field_email.append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_email + '</label>');
						}

						field_email.find('.leadgen-input').addClass('leadgen-has_' + $label_class);

					} else {

						form.find('#leadgen-customer-email .leadgen-label--error').remove();
						field_email.find('.leadgen-input').removeClass('leadgen-has_error');
						
					}

					if ( typeof data.data.leadgen_customer_budget !== "undefined" ) {
						
						if ( form.find('#leadgen-customer-budget .leadgen-label--error').length === 0 ) {
							field_budget.append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_budget + '</label>');
						}

						field_budget.find('.leadgen-input').addClass('leadgen-has_' + $label_class);

					} else {

						form.find('#leadgen-customer-budget .leadgen-label--error').remove();
						field_budget.find('.leadgen-input').removeClass('leadgen-has_error');

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