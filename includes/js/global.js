$(document).ready(function() {  
	$("input.rating_input").jStepper({minValue:0, maxValue:5});
	$("input.rating_input").numeric();
	
	$('input.inputbox').livequery(function() {
		$(this).focus(function() {
			$(this).parents('li').find('span').fadeIn();
		});
		$(this).blur(function() {
			$(this).parents('li').find('span').fadeOut();
		});
	});
	
	
	$("#Form_Search").constrain({ allow: { regex: "[a-zA-Z]"} });
	
	$(".beer_attributes li span").hide();
	$(".beer_attributes li").hover( function () {  
		$("span.property_name",this).fadeIn("fast");
		$("span.property_desc",this).fadeIn("fast");
	},  
	function () {  
		$("span.property_name",this).fadeOut("fast");
		$("span.property_desc",this).fadeOut("fast");
	}  
	); 
	$("ul.beer_attributes li:even").addClass("alt");  

	$("#recommend_submit").hide();
	$("#to_recommend").hide();
	$('#recommend').click(function() {
		$(this).hide();
		$('#recommend_submit').show();
		$("#to_recommend").slideDown();
	});


	$('#Form_Password').keyup(function(){
		var pass = passwordStrength($('#Form_Password').val(),$('#Form_Username').val());
		$("#Form_Password").removeClass();
		if(pass=="Bad" || pass=="Too Short") {
			$("#Form_Password").addClass("inputbox bad");
			$("#result").html("<b>Strength</b>: " + passwordStrength($('#Form_Password').val(),$('#Form_Username').val()));
		} else if(pass=="Good" || pass=="Strong") {
			$("#Form_Password").addClass("inputbox good");
			$("#result").html("<b>Strength</b>: " + passwordStrength($('#Form_Password').val(),$('#Form_Username').val()));
		} else if(pass=="Decent") {
			$("#Form_Password").addClass("inputbox decent");
			$("#result").html("<b>Strength</b>: " + passwordStrength($('#Form_Password').val(),$('#Form_Username').val()));
		} else if(pass="Cannot be empty.") {
			$("#Form_Password").removeClass();
			$("#Form_Password").addClass("inputbox");
			$("#result").html("Can only contain numbers/letters.");
		}
	});
});
