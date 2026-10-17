"use strict";

// Class definition
var KBscript = function() {

	var generals = function(){

		$(".parking_radio").hide();
		$('input[name="parking_radio"]').click(function() { 
		    if($(this).val() == 'Yes') {
		      $(".parking_radio").show();
		    }else
		    {
		    	$(".parking_radio").hide();
		    }
		});

		$(".pool_radio").hide();
		$('input[name="pool_radio"]').click(function() { 
		    if($(this).val() == 'Yes') {
		      $(".pool_radio").show();
		    }else
		    {
		    	$(".pool_radio").hide();
		    }
		});

		$(".music_radio").hide();
		$('input[name="music_radio"]').click(function() { 
		    if($(this).val() == 'Yes') {
		      $(".music_radio").show();
		    }else
		    {
		    	$(".music_radio").hide();
		    }
		});

	};
	return {
        // Init demos
        init: function() {
        	generals();
        }
    };
}();

// Class initialization on page load
jQuery(document).ready(function() {
    KBscript.init();
});