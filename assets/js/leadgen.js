(function ($) {

	$(document).ready(function() {

		$('form').submit(function(e) {

			// get the form data
			var data = {
				'name'		: $('input[name=title]').val(),
				'phone'		: $('input[name=leadgen_customer_phone]').val(),
				'email'		: $('input[name=leadgen_customer_email]').val(),
				'budget'	: $('input[name=leadgen_customer_budget]').val()
			};
			
			// process the form
			$.ajax({
				type		: 'POST',
				url			: '',
				data		: data,
				dataType	: 'json',
				encode		: true
			})

			.done(function(data) {

				console.log(data);

			} );

			// stop the form from submitting the normal way and refreshing the page
			e.preventDefault();

		} );

	} );

} )(jQuery);