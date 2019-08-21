(function($){
	
	$(document).ready(function () {
		transfers_hero_unit.init();
	});

	var transfers_hero_unit = {	
	
		load : function () {
			
		},
		init : function() {	

			$(".upload_image_button").on("click", function() {

				$.data(document.body, 'prevElement', $(this).prev());

				window.send_to_editor = function(html) {
					var imgurl = $('img',html).attr('src');
					var inputText = $.data(document.body, 'prevElement');

					if(inputText != undefined && inputText != '')
					{
						inputText.val(imgurl);
					}

					tb_remove();
				};

				tb_show('', 'media-upload.php?type=image&TB_iframe=true');
				return false;
			});
		}
	}
})(jQuery);