(function($){

	$.fn.ezPopup = function(action, option){

		var settings = $.extend({
			closable: true
        }, option);

        var context = this;

		$(this).on('click', '.close', function(){
			$(context).fadeOut(300);
		})

		switch(action){
			case 'show':
				$(this).fadeIn(150);
				break;

			case 'close':
				$(this).fadeOut(15);
				break;
		}
	}

}(jQuery))