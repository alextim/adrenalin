(function( $ ) {
		$('#trip-registration-end-date').datepicker({
			minDate: new Date()
		});
		
		$('#trip-start-date').datepicker({
			minDate: new Date()
		});

/*
	    function dateTimePicker() {

        if ($.fn.datepicker) {
            $('#trip-start-date').datepicker({
                //language: 'en',
                minDate: new Date(),
                onSelect: function(dateStr) {
                    newMinDate = null;
                    newMaxDate = new Date();
                    if ('' !== dateStr) {
                        new_date_min = new Date(dateStr);

                        newMinDate = new Date(new_date_min.setDate(new Date(new_date_min.getDate())));
                    }
                    $('#trip-end-date').datepicker({
                        minDate: newMinDate,
                    });
                }
            });

            $('#trip-end-date').datepicker({
                //language: 'en',
                minDate: new Date()
            });

            $('.trip-datepicker').datepicker({
                //language: 'en',
                minDate: new Date()
            });

            $('.trip-timepicker').datepicker({
                // language: 'en',
                timepicker: true,
                onlyTimepicker: true,

            });
        }
    }
    dateTimePicker();
*/
    $(document).on('click', '#publish', function() {

        var start_date = $('#trip-start-date').val();

        var error = '';
		if ('' == start_date) {
			error += 'Start date can\'t be empty!' + "\n";
		}

        if ('' == error) {
            $(document).off('click', '#publish');
        } else {
            alert(error);
            return false;
        }
    });
	
	$(document).on('click', '#trip-show-dates', function() {
		if ($(this).is(':checked')) {
			$('.trip-date-range-row').css({ 'display': 'table-row' });
		} else {
			$('.trip-date-range-row').css({ 'display': 'none' });
		}
	});	
	
	$(document).on('click', '#trip-enable-sale', function() {
		if ($(this).is(':checked')) {
			$('.trip-sale-price-row').css({ 'display': 'table-row' });
		} else {
			$('.trip-sale-price-row').css({ 'display': 'none' });
		}
	});	
	
	$(document).on('click', '#trip-show-price-list', function() {
		if ($(this).is(':checked')) {
			$('.trip-price-list-row').css({ 'display': 'table-row' });
		} else {
			$('.trip-price-list-row').css({ 'display': 'none' });
		}
	});
	
	$(document).on('click', '#trip-registration-enabled', function() {
		if ($(this).is(':checked')) {
			$('.trip-registration-row').css({ 'display': 'table-row' });
		} else {
			$('.trip-registration-row').css({ 'display': 'none' });
		}
	});
})( jQuery );


