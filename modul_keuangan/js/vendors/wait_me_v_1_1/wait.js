(function($){

	let appended = '<div id="overlay">'+
            		'<div class="content-loader">'+
                		'<div class="lds-dual-ring"></div><br>'+
                		'<span class="text">Sedang Memuat Halaman. Harap Tunggu...</span>'+
            		'</div>'+
        		'</div>';

	$("body").prepend(appended);

	$.fn.wait = function(action){
		if(action == 'show'){
			$('#overlay').fadeIn(200);
		}

		if(action == 'close'){
			$('#overlay').fadeOut('200')
		}
	}

	$(document).ready(function(){
		$("#overlay").fadeOut();
	})

}(jQuery))