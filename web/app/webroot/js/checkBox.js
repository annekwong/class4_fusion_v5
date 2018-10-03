
/**
 * the function execute when the checkbox is clicked
 * 
 */
function checkBox(type,checkbox){

	var checked = $(checkbox).attr('checked');
	
	if(checked){
		
		$("input[name='deleteSelects']").each(function(){
		
			$(this).attr('checked',true);
		}
		);
		
	}
	else{
		
		$("input[name='deleteSelects']").each(function(){
			
			$(this).attr('checked',false);
		});
	}
	

}




/**
 * delete submit
 * 
 * @return
 */
function delSubmit(){
	
	var index = 0 ;
	
	$("input[name='deleteSelects']").each(function(){
		
		if($(this).attr('checked')){
			
			index = 1 ;
			
			return ;
		}
	});
	
	if(index==0){
		
		alert("Please select the objects you want to delete!");
		
		return false ;
	}
	else{
		

		document.forms[1].submit();
	}
}











/**
* the function execute when the checkbox is clicked
* 
*/
function subCheckBox(type,checkbox){
	
	var checked = $(checkbox).attr('checked');
	
	if(checked){
		
		$("input[name='subdelSelects']").each(function(){
		
			$(this).attr('checked',true);
		}
		);
		
	}
	else{
		
		$("input[name='subdelSelects']").each(function(){
			
			$(this).attr('checked',false);
		});
	}
	

}





/**
* delete submit
* 
* @return
*/
function subDelSubmit(){
	
	var index = 0 ;
	
	$("input[name='subdelSelects']").each(function(){
		
		if($(this).attr('checked')){
			
			index = 1 ;
			
			return ;
		}
	});
	
	if(index==0){
		
		alert("Please select the objects you want to delete!");
		
		return false ;
	}
	else{
		
		document.forms[1].ways.value="item";
		
		document.forms[1].submit();
	}
}


function goPage(page) {
	
	var url = window.location.href;
	var pattern = /^[0-9]+/;
	if(url.indexOf("?")==-1){
	
		
		
		
		var urls = url.split("/");
		
		
		
		if(pattern.test(urls[urls.length-1])){
			
			url = url.substring(0,url.indexOf(urls[urls.length-1]));
			
		}
		else{
			
			url = url.substring(0,url.indexOf(".html"))+"/";
			
		}
		
		var pageNum = document.getElementById(page).value;
		if(!pattern.test(pageNum)){
			pageNum = 1;		
		}
		
		
		window.location = url + pageNum + ".html";
		
		//window.location.href = 'http://127.0.0.1:8080/routerIII/index.html';
		
	}
	else{
		//alert("-1--");
		var pageNum = document.getElementById(page).value;
		if(!pattern.test(pageNum)){
			pageNum = 1;		
		}
		
		//alert(url+ document.getElementById(page).value + ".html");
		window.location = url+"&page=" + pageNum ;
		//alert(window.location);
		
	}
	//alert("---");
}

function goPagef(page) {
	
	var url = window.location.href;
	var pattern = /^[0-9]+/;
	if(url.indexOf("?")==-1){
	
		//alert("---");
		
		
		var urls = url.split("/");
		
		//alert(urls[urls.length-1]);
		
		if(pattern.test(urls[urls.length-1])){
			
			url = url.substring(0,url.indexOf(urls[urls.length-1]));
			
		}
		else{
			
			url = url.substring(0,url.indexOf(".html"))+"/0/";
			
		}
		
		var pageNum = document.getElementById(page).value;
		if(!pattern.test(pageNum)){
			pageNum = 1;		
		}
		
		//alert(url+ document.getElementById(page).value + ".html");
		window.location = url + pageNum + ".html";
		
		//window.location.href = 'http://127.0.0.1:8080/routerIII/index.html';
		
	}
	else{
		//alert("-1--");
		var pageNum = document.getElementById(page).value;
		if(!pattern.test(pageNum)){
			pageNum = 1;		
		}
		
		//alert(url+ document.getElementById(page).value + ".html");
		window.location = url+"&page=" + pageNum ;
		//alert(window.location);
		
	}
	//alert("---");
}

function goPages(page) {
	
	var url = window.location.href;
	var pattern = /^[0-9]+/;
	if(url.indexOf("?")==-1){
		
		
		
		var urls = url.split("/");
		
		//alert(urls[urls.length-1]);
		
		if(pattern.test(urls[urls.length-2])){
			
			url = url.substring(0,url.indexOf(urls[urls.length-1]));
			
		}
		else{
			
			url = url.substring(0,url.indexOf(".html"))+"/";
			
		}
		
		var pageNum = document.getElementById(page).value;
		if(!pattern.test(pageNum)){
			pageNum = 1;		
		}
		
		//alert(url+ document.getElementById(page).value + ".html");
		window.location = url + pageNum + ".html";
		
		//window.location.href = 'http://127.0.0.1:8080/routerIII/index.html';
	}
	else{
	
		var pageNum = document.getElementById(page).value;
		if(!pattern.test(pageNum)){
			pageNum = 1;		
		}
		
		//alert(url+ document.getElementById(page).value + ".html");
		window.location = url + pageNum ;
				
	}
}

function deleteSelect(url){		
	//alert("=="+document.forms["selectPageForm"].deleteSelects);
	var ids = document.forms["selectPageForm"].deleteSelects;
	//alert("=="+ids);
	var i=0;
	for(;i<ids.length;i++){
		if(ids[i].checked){
			break;
		}
	}
//alert(i);
//alert(ids.length);
	if(i == ids.length){
		alert("Please choose items to delete!");
		return false;
	}else if((ids.length >0) || (i==0 && document.forms["selectPageForm"].deleteSelects.checked)){//
		//alert("--"+i);
		document.forms["selectPageForm"].action=url;
		document.forms["selectPageForm"].submit();
		return true;
	}else {
		alert("Please choose items to delete!");
	}

	return false;
}





function setCookieValue(cookieValue,index){
	
	
	  var v = $("#totalCounts").val(); // alert(v);
	     
	     if(v!=undefined&&($("#prePage1").val()==0||index==0)){
	    	 if(v>5000){
	    		if(window.confirm("There are more than 3000 items,it will display 3000 items in the each page ,should you continue it?")){
	    			setcookie(cookieValue,index);
	    		}else{
	    			var preSize = $("#changeSize").val();
	    			$("#prePage"+index).val(preSize);
	    		return ;	
	    		}
	    	 }else{
	    		 setcookie(cookieValue,index);
	    	 }
	    	 
	     }else{
	    	 setcookie(cookieValue,index);
	     }

}


function setcookie(cookieValue,index){
	
//alert("index="+cookieValue);
	var userID = $("#cookieUserID").val();

	var value = $("#prePage"+index).val();//alert(value);
	
	if(value==undefined){
	value = index ;
	
	}

	
	if(cookieValue==1){

		$.cookie("route_class4_product_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
		if(index==2){
			document.forms['selectPageForm'].action='./product.html?cmd=per1';
		
		}
		document.forms['selectPageForm'].submit();
	}
	else if(cookieValue==2){
		$.cookie("route_class4_entity_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
		if(index==2){
			document.forms['selectPageForm'].action='./entity.html?cmd=per1';
		
		}
		document.forms['selectPageForm'].submit();
	}
	else if(cookieValue==3){
		$.cookie("route_class4_resource_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
		if(index==2){
			document.forms['selectPageForm'].action='./resource.html?cmd=per1';
		
		}
		document.forms['selectPageForm'].submit();
	}
	else if(cookieValue==4){
		$.cookie("route_class4_user_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
		if(index==2){
			document.forms['selectPageForm'].action='./user.html?cmd=per1';
		
		}
		document.forms['selectPageForm'].submit();
	}
	else if(cookieValue==5){
		
		//alert("aa");
		$.cookie("route_class4_digit_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
//		alert("url5="+document.forms['selectPageForm'].action);
		document.forms['selectPageForm'].submit();
	}
	else if(cookieValue==6){
		$.cookie("route_class4_tran_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
	//	alert("url6="+document.forms['selectPageForm'].action);
		document.forms['selectPageForm'].submit();
	}
	else if(cookieValue==7){
		$.cookie("route_class4_sip_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
		document.forms['selectPageForm'].submit();
	}
	else if(cookieValue==8){
		$.cookie("route_class4_item_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
		document.forms['selectPageForm'].submit();
	}
	else if(cookieValue==9){
		$.cookie("route_class4_cdr_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
		document.forms['selectPageForm'].submit();
	}
	else if(cookieValue==10){
		$.cookie("route_class4_resStat_"+userID,value,{ expires: 7, path: '/' });
		location.reload(false);
	}
	else if(cookieValue==11){
		$.cookie("route_class4_proStat_"+userID,value,{ expires: 7, path: '/' });
		location.reload(false);
	}
	else if(cookieValue==12){
		$.cookie("route_class4_preStat_"+userID,value,{ expires: 7, path: '/' });
		location.reload(false);
	}
	else if(cookieValue==15){
		$.cookie("route_class4_black_"+userID,value,{ expires: 7, path: '/' });
		$("#changeSize").val("change");
		if(index==2){
			document.forms['selectPageForm'].action='./blocklist.html?cmd=per1';
		
		}
		document.forms['selectPageForm'].submit();
	}
}






function updatePageSize(){
	var preSize = $("#changeSize").val();
    var patrn=/[30|50|100]{1}/g;
    if(patrn.test(preSize)){
        $(".paginated_"+preSize).html("<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>");
    }
    if(preSize==3000){
        preSize = 0;
        }
    
     
    $("#prePage1 ").val(preSize); 
    $("#prePage2 ").val(preSize); 
}


