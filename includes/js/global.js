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

	$(".favorites li span").hide();
	$(".favorites li").hover( function () {  
		$("span.remove_favorite",this).fadeIn("fast");
	},  
	function () {  
		$("span.remove_favorite",this).fadeOut("fast");
	}  
	); 
	$("ul.favorites li:even").addClass("alt");  

	$(function(){
		$(".remove_favorite").click(function(){
			var id = $(this).attr('id');
			$.ajax({
				type: "POST",
				data: "id="+id,
				url: "http://spaceheater.dhcp.rose-hulman.edu/Beer/remove_fav.php",
				success: function(msg)
				{
					$("li.fav_"+id).fadeOut();
				}
			});
		});
	});
	
	$(function(){
		$(".favorite.yes").click(function(){
			var id = $(this).attr('id').replace('f_','');
			$.ajax({
				type: "POST",
				data: "id="+id,
				url: "http://spaceheater.dhcp.rose-hulman.edu/Beer/remove_fav.php",
				success: function(msg)
				{;
					$("#f_"+id).removeClass().addClass("favorite no");
					location.reload();
				}
			});
		});
	});
	
	$(function(){
		$(".favorite.no").click(function(){
			var id = $(this).attr('id').replace('f_','');
			$.ajax({
				type: "POST",
				data: "id="+id,
				url: "http://spaceheater.dhcp.rose-hulman.edu/Beer/add_fav.php",
				success: function(msg)
				{
					$("#f_"+id).removeClass().addClass("favorite yes");
					location.reload();
				}
			});
		});
	});

	$("#recommend_submit").hide();
	$("#to_recommend").hide();
	$('#recommend').click(function() {
		$(this).hide();
		$('#recommend_submit').show();
		$("#to_recommend").slideDown();
	});

	$("#Form_To").autocomplete("http://spaceheater.dhcp.rose-hulman.edu/Beer/includes/auto.php", {
		width: 200,
		selectFirst: true
	});
	
	$("#Form_Search").autocomplete("http://spaceheater.dhcp.rose-hulman.edu/Beer/includes/search_auto.php", {
		width: 414,
		selectFirst: true
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
