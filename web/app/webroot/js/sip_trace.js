
function selectTag(showContent,selfObj){
	
	var tag = document.getElementById("com_tags").getElementsByTagName("li");
	var taglength = tag.length;
	for(i=0; i<taglength; i++){
		tag[i].className = "";
	}
	selfObj.parentNode.className = "selectTag";
	
	for(i=0; j=document.getElementById("tagContent"+i); i++){
		j.style.display = "none";
	}
	document.getElementById(showContent).style.display = "block";
}
jQuery(document).ready(function(){
	$("#ladder_bottom").css("display","none");
});
function showInfo(showContent){
	var $href = $(this).attr('href');
	if($href==0){
		$("#"+"hex_"+showContent).css("display","none");
		$("#"+"hex_"+showContent).siblings().css('display','none');
		
		$("#"+showContent+"_info").css("display","none");
		$("#"+showContent+"_info").siblings().css('display','none');
		
		$("#"+"text_"+showContent).css("display","none");
		$("#"+"text_"+showContent).siblings().css('display','none');
	}else{
		$("#ladder_bottom").css("display","");
		
		$("#"+"hex_"+showContent).css("display","block");
		$("#"+"hex_"+showContent).siblings().css('display','none');
		
		$("#"+showContent+"_info").css("display","block");
		$("#"+showContent+"_info").siblings().css('display','none');
		
		$("#"+"text_"+showContent).css("display","block");
		$("#"+"text_"+showContent).siblings().css('display','none');
	}
}

function close_bottom(){
	$("#ladder_bottom").css("display","none");
}
$(function(){

	var i = 5; 
	var len = $(".ladder_top table td").length;  
	var page = 1;
	var maxpage = Math.ceil(len/i);
	var scrollWidth = $(".ladder_top").width();
	var divbox = "<div id='div1'>This is the last panel!</div>";
	$("body").append(divbox);
	
	$(".vright").click(function(e){
		
		if(!$(".ladder_top table").is(":animated")&&!$(".ladder_right table").is(":animated")){
			if(page == maxpage ){
				$(".ladder_top table").stop();
				$(".ladder_right table").stop();
				$("#div1").css({
					"top": (e.pageY + 20) +"px",
					"left": (e.pageX + (-70)) +"px",
					"opacity": "0.9"
				}).stop(true,false).fadeIn(800).fadeOut(800);
			}else{
				$(".ladder_top table").animate({left : "-=" + scrollWidth +"px"},2000);
				$(".ladder_right table").animate({left : "-=" + scrollWidth +"px"},2000);
				page++;
			}content1
		}
		
	});
	$(".vleft").click(function(){
		
		if(!$(".ladder_top table").is(":animated")&&!$(".ladder_right table").is(":animated")){
			if(page == 1){
				$(".ladder_top table").stop();
				$(".ladder_right table").stop();
			}else{
				$(".ladder_top table").animate({left : "+=" + scrollWidth +"px"},2000);
				$(".ladder_right table").animate({left : "+=" + scrollWidth +"px"},2000);
				page--;
			}
		}

	});
});

