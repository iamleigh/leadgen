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

			var notice			= form.find('.leadgen-notice');
			
			// process the form
			$.ajax({
				type		: 'POST',
				url			: leadgen_data.ajaxurl,
				data		: data,
				dataType	: 'json',
				encode		: true,
				success		: function( data ) {
					
					var $label_class = data.success ? 'success' : 'error';

					notice.find('.leadgen-notice--400').remove();

					if (data.success === false) {
						
						notice.find('.leadgen-notice--success').remove();

						if ( notice.find('.leadgen-notice--error').length === 0 ) {
							notice.append('<label class="leadgen-notice--error">' + data.data.form + '</label>');
						}

						if ( typeof data.data.title !== "undefined" ) {
							
							if ( form.find('#leadgen-customer-name .leadgen-label--error').length === 0 ) {
								field_name.append('<label class="leadgen-label--' + $label_class + '">' + data.data.title + '</label>');
							}

							field_name.find('.leadgen-input').addClass('leadgen-has_' + $label_class);

						} else {

							field_name.find('.leadgen-input').removeClass('leadgen-has_error');
							field_name.find('.leadgen-label--error').remove();

							if ( typeof data.data.title !== "undefined" ) {
							
								if ( form.find('#leadgen-customer-name .leadgen-label--error').length === 0 ) {
									field_name.append('<label class="leadgen-label--' + $label_class + '">' + data.data.title + '</label>');
								}
	
								field_name.find('.leadgen-input').addClass('leadgen-has_' + $label_class);
	
							}

						}

						if ( typeof data.data.leadgen_customer_phone !== "undefined" ) {
							
							if ( form.find('#leadgen-customer-phone .leadgen-label--error').length === 0 ) {
								field_phone.append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_phone + '</label>');
							}

							field_phone.find('.leadgen-input').addClass('leadgen-has_' + $label_class);

						} else {

							field_phone.find('.leadgen-input').removeClass('leadgen-has_error');
							field_phone.find('.leadgen-label--error').remove();
							
							if ( typeof data.data.leadgen_customer_phone !== "undefined" ) {
							
								if ( form.find('#leadgen-customer-phone .leadgen-label--error').length === 0 ) {
									field_phone.append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_phone + '</label>');
								}
	
								field_phone.find('.leadgen-input').addClass('leadgen-has_' + $label_class);
	
							}

						}

						if ( typeof data.data.leadgen_customer_email !== "undefined" ) {
							
							if ( form.find('#leadgen-customer-email .leadgen-label--error').length === 0 ) {
								field_email.append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_email + '</label>');
							}

							field_email.find('.leadgen-input').addClass('leadgen-has_' + $label_class);

						} else {

							field_email.find('.leadgen-input').removeClass('leadgen-has_error');
							field_email.find('.leadgen-label--error').remove();

							if ( typeof data.data.leadgen_customer_email !== "undefined" ) {
							
								if ( form.find('#leadgen-customer-email .leadgen-label--error').length === 0 ) {
									field_email.append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_email + '</label>');
								}
	
								field_email.find('.leadgen-input').addClass('leadgen-has_' + $label_class);
	
							}
							
						}

						if ( typeof data.data.leadgen_customer_budget !== "undefined" ) {
							
							if ( form.find('#leadgen-customer-budget .leadgen-label--error').length === 0 ) {
								field_budget.append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_budget + '</label>');
							}

							field_budget.find('.leadgen-input').addClass('leadgen-has_' + $label_class);

						} else {

							field_budget.find('.leadgen-input').removeClass('leadgen-has_error');
							field_budget.find('.leadgen-label--error').remove();

							if ( typeof data.data.leadgen_customer_budget !== "undefined" ) {
							
								if ( form.find('#leadgen-customer-budget .leadgen-label--error').length === 0 ) {
									field_budget.append('<label class="leadgen-label--' + $label_class + '">' + data.data.leadgen_customer_budget + '</label>');
								}
	
								field_budget.find('.leadgen-input').addClass('leadgen-has_' + $label_class);
	
							}

						}

					} else {
						
						// Reset form
						if (form[0]) {
							form[0].reset();
						}

						form.find('.leadgen-notice--error').remove();

						if ( notice.find('.leadgen-notice--success').length === 0 ) {
							notice.append('<label class="leadgen-notice--success">Your form was submitted successfully.</label>');
						}

						form.find('.leadgen-label--error').remove();
						form.find('.leadgen-input').removeClass('leadgen-has_error');

					}

				},
				error		: function() {

					if ( notice.find('.leadgen-notice--400').length === 0 ) {
						notice.append('<label class="leadgen-notice--400">There was an error on form submission.</label>');
					}

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