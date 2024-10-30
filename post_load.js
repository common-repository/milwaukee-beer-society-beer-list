(function ($) {
	$(document).ready(function(){
		if($( window ).width() < 550){
			$('div.bones').hide();
		}
	});	
	$(window).resize(function(){
		if($( window ).width() < 550){
			$('div.bones').hide();
		}else{
			$('div.bones').show();
		}
	});
	$('#bl_area .bl_item').click(function(e){
		var bid = $(this).data('bid');
		var isSmartphone = ( navigator.userAgent.match(/(iPad|iPhone|iPod|Android)/gi) ? true : false );
		//var urlprefix = isSmartphone ? "untappd:///?beer=" : "https://untappd.com/beer/";
        if(isSmartphone){
            var now = new Date().valueOf();
            setTimeout(function () {
                if (new Date().valueOf() - now > 100) return;	
                window.location = "https://untappd.com/beer/" + bid;
            }, 25);
            window.location = "untappd:///?beer=" + bid;
        }else{

            window.location = "https://untappd.com/beer/" + bid;

        };

		//window.location.href = "https://untappd.com/beer/" + bid;
	});
}(jQuery));