(function($) {

	$(document).ready(function() {

		$('.leadgen-custom-info').submit(function(e) {

			// get the form data
            var data = $(this).serialize();
			
			// process the form
			$.ajax({
				type		: 'POST',
				url			: leadgen_data.ajaxurl,
				data		: data,
				dataType	: 'json',
				encode		: true
			})

			.done(function(data) {
				
				$('.leadgen-customer-info').prepend('<label class="leadgen-label--info">' + data.message + '</label>');

				console.log(data);

			} );

			// stop the form from submitting the normal way and refreshing the page
			e.preventDefault();
			return false;

		} );

	} );

} )(jQuery);