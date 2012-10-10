// JavaScript Document
$(document).ready(function() {
	$("body").append('<div id="data" h="0" tot="0" coli="1"></div>');
	$("#content .block_list").each(function(){
		var h=$(this).height();
		var htotal=parseInt($("#data").attr("h"));
		var coli=parseInt($("#data").attr("coli"));
		var tot=parseInt($("#data").attr("tot"));
		if(htotal==0){
			$("#content").append('<div class="col_list" id="col_'+coli+'"></div>');
		}
		tot+=1;
		htotal+=h;
		$("#col_"+coli).append($(this).html());
		$(this).hide();
		if(htotal>400){
			coli+=1;
			htotal=0;
			tot=0;
		}
		$("#data").attr("coli",coli);
		$("#data").attr("tot",tot);
		$("#data").attr("h",htotal);
	});
	$("#file_button").click(function(){
		if($("#file_form").css("display")=="none"){
			$("#file_form").slideDown(500);
		}else{
			$("#file_form").slideUp(500);	
		}
	});
	$(".nyroModal").click(function(){
		$("#video").hide();				 
	});							 
	$("#data").remove();
	$("form").submit(function() {
		$(this).find(".error").remove();
		var stp=0;
		var numericExpression = /^[0-9 -.]+$/
		var alphaExp = /^[a-z A-Z]+$/;
		var nospace = /^[a-zA-Z0-9]+$/;
		$(".required_input").each(function(){ 
			if($(this).val()=="" || $(this).val()==0){
				$(this).parent().find(".error").remove();
				$(this).parent().append('<span class="error">(*)</b>');
				stp++;
			}else{
				$(this).parent().find(".error").remove();
			}
		});
		$(".letters").each(function(){ 
			if($(this).val().match(alphaExp)==null){
				stp++;
				$(this).parent().find(".error").remove();
				$(this).parent().append('<span class="error">(*)</b>');
			}else{
				$(this).parent().find(".error").remove();
			}
		});
		$(".numbers").each(function(){ 
			if($(this).val().match(numericExpression)==null){
				stp++;
				$(this).parent().find(".error").remove();
				$(this).parent().append('<span class="error">(*)</b>');
			}else{
				$(this).parent().find(".error").remove();
			}
		});
		$(".no_space").each(function(){ 
			if($(this).val().match(nospace)==null){
				stp++;
				$(this).parent().find(".error").remove();
				$(this).parent().append('<span class="error">(*)</b>');
				alert("Username can contain only letters and numbers");
			}else{
				$(this).parent().find(".error").remove();
			}
		});
		$("#email_signup").each(function(){ 
			$.get(base_url+"account/check_email/"+$(this).val(), function(data){
				if(data=="1"){
					$(this).parent().find(".error").remove();
			  		$("#email_signup").parent().append('<span class="error">(*)</b>');
					alert("That email address is already in use.");
			 		return false;
				}else{
					$(this).parent().find(".error").remove();
				}
			});
		});
		if($("#pass").val()!=$("#pass2").val()){
			alert("Passwords do not match");
			stp++;
		}
		if($("#post_email").val()!="" && $("#post_email2").val()!="" && $("#post_email").val()!=undefined && $("#post_email2").val()!=undefined && $("#post_email").val()!=$("#post_email2").val()){
			alert("Email addresses do not match");
			stp++;
		}
		if(stp!=0){
			alert("Please fill in all fields properly");
			return false;
		}else{
			return true;
		}
    });
});