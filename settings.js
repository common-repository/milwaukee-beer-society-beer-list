jQuery(function($){
	var settings_url = wp_data[0];
	var loadSettings = function(){
	    
	}

	$('div.bl_settings_form').load(settings_url,loadSettings);
});





