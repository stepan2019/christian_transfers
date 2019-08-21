(function($){

	"use strict";
	
	$(document).ready(function () {
		transfers_search.init();
	});
	
	var transfers_search = {
	
		init: function () {
	
			// SEARCH
			$('input[name=trip]#oneway').click(function() {
				$('.f-row:nth-child(2)').hide(500);
				$('#pickup2').prop("disabled", true);
				$('#dropoff2').prop("disabled", true);
				$('#return-date').prop("disabled", true);
				$('#ret').prop("disabled", true);
			});
			$('input[name=trip]#return').click(function() {
				$('.f-row:nth-child(2)').show(500);
				$('#pickup2').prop("disabled", false);
				$('#dropoff2').prop("disabled", false);
				$('#return-date').prop("disabled", false);
				$('#ret').prop("disabled", false);
			});

			// DATE & TIME PICKER
			$('.departure-date').datetimepicker({
				dateFormat: window.datepickerDateFormat + '',
				altFormat: window.datepickerAltFormat,
				timeFormat: 'hh:mm',
				altFieldTimeOnly: 'false',
				numberOfMonths: 1,
				altField: "#dep"		
			});
			if (typeof(window.datepickerDepartureDateValue) != 'undefined' && window.datepickerDepartureDateValue.length > 0) {
				$('.departure-date').datetimepicker("setDate", window.datepickerDepartureDateValue);
			}
			
			$('.return-date').datetimepicker({
				dateFormat: window.datepickerDateFormat + '',
				altFormat: window.datepickerAltFormat,
				timeFormat: 'hh:mm',
				altFieldTimeOnly: 'false',
				numberOfMonths: 1,
				altField: "#ret"		
			});
			if (typeof(window.datepickerReturnDateValue) != 'undefined' && window.datepickerReturnDateValue.length > 0) {
				$('.return-date').datetimepicker("setDate", window.datepickerReturnDateValue);
			}
			
			$('.select-avail-slot').on('click', function(e) {
				
				if ($(this).hasClass('selected')) {
				
					$(this).removeClass('selected');
					$(this).removeClass('color');
					$(this).addClass('grey');
					
					if ($(this).hasClass('select-avail-dep-slot')) {
						window.bookingRequest.departureAvailabilityId = 0;
						window.bookingRequest.departureIsPrivate = false;
						window.bookingRequest.departureSlotMinutesNumber = -1;
					} else {
						window.bookingRequest.returnAvailabilityId = 0;
						window.bookingRequest.returnIsPrivate = false
						window.bookingRequest.returnSlotMinutesNumber = -1;
					}
				} else {
					var availId = $(this).attr('id').replace('select-avail-slot-', '');
					
					var slotMinutesNumber = $.grep($(this).attr('class').split(" "), function(v, i){
					   return v.indexOf('select-avail-slot-time-') === 0;
					}).join();
					
					slotMinutesNumber = slotMinutesNumber.replace('select-avail-slot-time-', '');

					if ($(this).hasClass('select-avail-dep-slot')) {

						if($("#returnHeading").length > 0) {
							var $root = $('html, body');
							$root.animate({
								scrollTop: $("#returnHeading").offset().top + ($("#returnHeading").height() / 2)
							}, 500, function () {});
						} else {
							var $root = $('html, body');
							$root.animate({
								scrollTop: $("#book-transfers").offset().top + ($("#book-transfers").height() / 2)
							}, 500, function () {});
						}						
					
						$('.select-avail-dep-slot').removeClass('selected');
						$('.select-avail-dep-slot').removeClass('color');
						$('.select-avail-dep-slot').addClass('grey');
						
						$(this).addClass('selected');
						$(this).addClass('color');
						$(this).removeClass('grey');
						
						window.bookingRequest.departureSlotMinutesNumber = slotMinutesNumber;
						window.bookingRequest.departureAvailabilityId = availId;
						if ($(this).hasClass('select-avail-slot-private')) {
							window.bookingRequest.departureIsPrivate = true;
						} else {
							window.bookingRequest.departureIsPrivate = false;
						}
					} else {
					
						var $root = $('html, body');
						$root.animate({
							scrollTop: $("#book-transfers").offset().top + ($("#book-transfers").height() / 2)
						}, 500, function () {});

					
						$('.select-avail-ret-slot').removeClass('selected');
						$('.select-avail-ret-slot').removeClass('color');
						$('.select-avail-ret-slot').addClass('grey');
						
						$(this).addClass('selected');
						$(this).addClass('color');
						$(this).removeClass('grey');
						
						window.bookingRequest.returnSlotMinutesNumber = slotMinutesNumber;
						window.bookingRequest.returnAvailabilityId = availId;
						if ($(this).hasClass('select-avail-slot-private')) {
							window.bookingRequest.returnIsPrivate = true;
						} else {
							window.bookingRequest.returnIsPrivate = false;
						}
					}
					
					if (window.bookingRequest.departureAvailabilityId > 0) {
						$('.proceed-to-booking').show();
					} else {
						$('.proceed-to-booking').hide();
					}
				}
				
				e.preventDefault();
			});
			
			$('.proceed-to-booking').on('click', function(e) {

				if (window.bookingRequest.departureAvailabilityId) {
				
					var redirectUri = '';
				
					redirectUri = window.bookingFormPageUrl + '?' + 
					'depavid=' + window.bookingRequest.departureAvailabilityId + 
					'&depslot=' + window.bookingRequest.departureSlotMinutesNumber + 
					'&dep=' + window.bookingRequest.departureDateAlt + 
					'&depp=' + (window.bookingRequest.departureIsPrivate ? '1' : '0');
					
					if (window.bookingRequest.returnAvailabilityId > 0) {
						redirectUri += 
							'&retavid=' + window.bookingRequest.returnAvailabilityId + 
							'&retslot=' + window.bookingRequest.returnSlotMinutesNumber + 
							'&ret=' + window.bookingRequest.returnDateAlt + 
							'&retp=' + (window.bookingRequest.returnIsPrivate ? '1' : '0');
					
					};
					
					redirectUri += '&ppl=' + window.bookingRequest.people;
					
					window.location.href = redirectUri;
				}
				
				e.preventDefault();
			});
		}
	}

})(jQuery);