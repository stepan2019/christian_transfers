(function($){

	"use strict";
	  
	$(document).ready(function () {
		window.transfers.init();
	});
	
	$(window).load(function () {
		window.transfers.load();
	});
	
	// ANIMATIONS
	new WOW().init();
	
	window.transfers = {
	
		init: function () {
			
			// MOBILE MENU
			$('.main-nav').slicknav({
				prependTo:'.header .wrap',
				label:''
			});
			
			// CUSTOM FORM ELEMENTS
			$('input[type=radio], input[type=checkbox],input[type=number], select').uniform();
			
			// SEARCH RESULTS 
			$('.information').hide();
			$('.trigger').click(function () {
				$(this).parent().parent().nextAll('.information').slideToggle(500);
			});
			$('.close').click(function () {
			   $('.information').hide(500);
			});	
			
			// FAQS
			$('.faqs dd').hide();
			$('.faqs dt').click(function () {
				$(this).next('.faqs dd').slideToggle(500);
				$(this).toggleClass('expanded');
			});
			
			// TABS
			$('.tab-content').hide();
			$('.tab-content:first-of-type').show();
			$('.tabs li:first-of-type').addClass('active');

			$('.tabs a').on('click', function (e) {
				e.preventDefault();
				$(this).closest('li').addClass('active').siblings().removeClass('active');
				$($(this).attr('href')).show().siblings('.tab-content').hide();
			});
			
			// SERVICES
			$('.services-list .single').hide().first().show();
			$('.categories li:first').addClass('active');

			$('.categories a').on('click', function (e) {
				$('div.success').hide();				
				$('div.error').hide();
				$(this).closest('li').addClass('active').siblings().removeClass('active');
				$($(this).attr('href')).show().siblings('.single').hide();
				e.preventDefault();
			});

			var hash = $.trim( window.location.hash );
			if (hash) $('.categories a[href$="'+hash+'"]').trigger('click');

			var hash = $.trim( window.location.hash );
			if (hash) $('.tabs a[href$="'+hash+'"]').trigger('click');
			
			// SMOOTH ANCHOR SCROLLING
			var $root = $('html, body');
			$('a.anchor').click(function(e) {
				var href = $.attr(this, 'href');
				if (typeof ($(href)) != 'undefined' && $(href).length > 0) {
					var anchor = '';
					
					if(href.indexOf("#") != -1) {
						anchor = href.substring(href.lastIndexOf("#"));
					}
						
					if (anchor.length > 0) {
						$root.animate({
							scrollTop: $(anchor).offset().top
						}, 500, function () {
							window.location.hash = anchor;
						});
						e.preventDefault();
					}
				}
			});
		
			window.transfers.resizeFluidItems();
		},
		load: function () {
			// UNIFY HEIGHT
			var maxHeight = 0;
				
			$('.heightfix').each(function(){
				if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
			});
			$('.heightfix').height(maxHeight);	

			// PRELOADER
			$('.preloader').fadeOut();
		},
		numberFormatI18N : function (number) {

			var formattedNumber = '';
		
			$.ajax({
				url: TransfersAjax.ajaxurl,
				data: {
					'action':'number_format_i18n_request',
					'number' : number,
					'nonce' : TransfersAjax.nonce
				},
				async: false,
				success:function(data) {
				
					formattedNumber = data;
		
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			}); 
			
			return formattedNumber;
		},
		resizeFluidItems: function() {
			window.transfers.resizeFluidItem(".location-list .one-fourth");
		},
		resizeFluidItem : function (filters) {
		
			var filterArray = filters.split(',');
			
			var arrayLength = filterArray.length;
			for (var i = 0; i < arrayLength; i++) {
				var filter = filterArray[i];
				var maxHeight = 0;            
				$(filter + " .description div").each(function(){
					if ($(this).height() > maxHeight) { 
						maxHeight = $(this).height(); 
					}
				});
				$(filter + ":not(.fluid-item) .description div").height(maxHeight);   
			}
		}
	}

})(jQuery);