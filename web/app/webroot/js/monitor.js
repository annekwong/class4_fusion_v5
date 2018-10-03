var index = 0 ;
var indexChart = 0 ;
var indexTable = 0 ;
var chartState = 1 ;
var initTime = 0 ;
var cd = 0 ;
function changehideProS(page,bool){
	var inactive=document.getElementById("hideInactive").checked;	
	$.ajax({	
		url:"productStats.html?cmd=hide&key="+inactive+"&page="+page+"&index="+indexTable+"&date="+new Date(),
		type:'GET',
		dataType:'json',
		
		success:function(text){
		indexTable++;
		var data = eval(text);
		var array = data.productStat ;
		
//		alert(array);
		
		/*
		 * delete the div content
		 */
		
		var htmlStr = '' ;
		
		htmlStr = htmlStr+'<table id="show_table">';
		htmlStr = htmlStr+"<tr height='30'><th>&nbsp;</th><th class='bcess_td'  bgcolor='#f9fbf1' >Currently</th><th class='bcess_td' bgcolor='#f1fbf8'>15 Min</th><th class='bcess_td' bgcolor='#f2f1fb'>1 Hour</th><th bgcolor='#f8e9ee'>24 Hour</th></tr>";
		htmlStr = htmlStr+"<tr height='28'><td width='200px' class='bcess_td'><span> <a href='./productStatssort.html'>Product Name</a></span></td><td class='bcess_td' bgcolor='#f9fbf1' ><div style='width:50%;float:left;text-align:center;'>Calls</div><div style='width:50%;float:left;text-align:center;'>CPS</div></td>";
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">ACD</div><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td></tr>';
		
		for(var i=0;i<array.length;i++){
			htmlStr = htmlStr+'<tr>';
			htmlStr = htmlStr+'<td height="28" class="bcess_td"><span><a href="./productStats/'+array[i].resourceID+'/'+array[i].id+'.html" style="	text-decoration: none;	color: #005c9c;">'+array[i].resourceID+'</a></span></td>';
			htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f9fbf1" ><div style="width:50%;float:left;text-align:center;">'+array[i].bean.call+'</div><div style="width:50%;float:left;text-align:center;">'+array[i].bean.cps+'</div></td>';
			
			var moniBeans = array[i].beans ;
			
			for(var j=0;j<moniBeans.length;j++){
				if(j==0){
					htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else if(j==1){
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else {
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}			}
			htmlStr = htmlStr+'</tr>';
		}
		
		htmlStr = htmlStr+"</table>";
		
//		alert(htmlStr);
		
		$('#proStatsDiv').html(htmlStr);
		
		
		var pageInfo = data.proPageParam;
		
		var pageArray = pageInfo.pages;
		
//		alert(pageInfo.pages);
//		alert(pageInfo.pageString);
//		alert(pageInfo.totalPage);
		var pageLimit = pageInfo.limit ;
		
		var pageStr = "<span style='font-size:10pt;color:#aaaaaa;' >Per page:</span>" ;	
		
		if(pageLimit==25){
			pageStr = pageStr + "<a class='currents'  >25</a><a href='javascript:setCookieValue(11,50);'  >50</a><a href='javascript:setCookieValue(11,100);'  >100</a><a href='javascript:setCookieValue(11,0);'  >All</a>" ;
		}
		else if(pageLimit==50){
			pageStr = pageStr + "<a href='javascript:setCookieValue(11,25);'  >25</a><a class='currents'  >50</a><a href='javascript:setCookieValue(11,100);'  >100</a><a href='javascript:setCookieValue(11,0);'  >All</a>";
		}
		else if(pageLimit==100){
			pageStr = pageStr + "<a href='javascript:setCookieValue(11,25);'  >25</a><a href='javascript:setCookieValue(11,50);'  >50</a><a class='currents'  >100</a><a href='javascript:setCookieValue(11,0);'  >All</a>";
		}
		else {
			pageStr = pageStr + "<a href='javascript:setCookieValue(11,25);'  >25</a><a href='javascript:setCookieValue(11,50);'  >50</a><a href='javascript:setCookieValue(11,100);'  >100</a><a class='currents'  >All</a>";
		}
		
		    pageStr = pageStr +'<span style="margin-right:280px;">&nbsp;&nbsp;</span>';
		
		    pageStr = pageStr +'<a href="javascript:;" onclick="javascript:changeProS(1,false)">&lt;</a>' ;
		
		for(var j=0;j<pageArray.length;j++){
			
			if(pageArray[j]==pageInfo.pageString){
				
				pageStr = pageStr + '<a id="a'+pageArray[j]+'" class="currents">'+pageArray[j]+'</a>';
			}
			else if(pageArray[j]=='dot'){
				
				pageStr = pageStr + '...';
			}
			else{
				
				pageStr = pageStr + '<a id="a'+pageArray[j]+'" href="javascript:changeProS('+pageArray[j]+',false);" >'+pageArray[j]+'</a>';
			}
		}
		
		pageStr = pageStr + '<a href="javascript:;" onclick="javascript:changeProS('+pageInfo.totalPage+',false)">&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		
		$('.quotes').html(pageStr);
		
		
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
	
	
}


//Hide Inactive Items

function changeResShide(page,gress){

var inactive=document.getElementById("hideInactive").checked;
//	alert(inactive);
	
	

	
	$.ajax({	
		url:"resourceStats.html?cmd=hide&key="+inactive+"&grs="+gress+"&page="+page+"&index="+indexTable+"&date="+new Date(),
		type:'GET',
		dataType:'json',
		
		success:function(text){
		indexTable++;
		var data = eval(text);
		var array = data.resourceStat ;
		
		/*
		 * delete the div content
		 */
		
		var htmlStr = '' ;
		
		htmlStr = htmlStr+'<table id="show_table">';
		htmlStr = htmlStr+"<tr height='30'><th>&nbsp;</th><th class='bcess_td'  bgcolor='#f9fbf1' >Currently</th><th class='bcess_td' bgcolor='#f1fbf8'>15 Min</th><th class='bcess_td' bgcolor='#f2f1fb'>1 Hour</th><th bgcolor='#f8e9ee'>24 Hour</th></tr>";
		htmlStr = htmlStr+"<tr height='28'><td width='200px' class='bcess_td'><span> <a href='./resourceStatssort.html'>Resource ID</a></span></td><td class='bcess_td' bgcolor='#f9fbf1' ><div style='width:50%;float:left;text-align:center;'>Calls</div><div style='width:50%;float:left;text-align:center;'>CPS</div></td>";
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">ACD</div><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td></tr>';
				
		for(var i=0;i<array.length;i++){
			htmlStr = htmlStr+'<tr>';
			htmlStr = htmlStr+'<td class="bcess_td" height="28"><span><a href="./resourceStats/'+array[i].id+'/'+array[i].resourceID+'.html" style="	text-decoration: none;	color: #005c9c;" title='+array[i].resourceName+'>'+array[i].resourceID+'</a></span></td>';
			htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f9fbf1" ><div style="width:50%;float:left;text-align:center;">'+array[i].bean.call+'</div><div style="width:50%;float:left;text-align:center;">'+array[i].bean.cps+'</div></td>';
			
			var moniBeans = array[i].beans ;
			
			for(var j=0;j<moniBeans.length;j++){
				if(j==0){
					htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else if(j==1){
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else {
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}			}
			htmlStr = htmlStr+'</tr>';
		}
		
		htmlStr = htmlStr+"</table>";
		
		
		
		$('#context_right_table_div').html(htmlStr);
		
		
		
		
		var pageInfo = data.resPageParam;
		
		var pageArray = pageInfo.pages;
		
//		alert(pageInfo.pages);
//		alert(pageInfo.pageString);
//		alert(pageInfo.totalPage);
		var pageLimit = pageInfo.limit ;
		
		var pageStr = "<span style='font-size:10pt;color:#aaaaaa;' >Per page:</span>" ;	
		
		if(pageLimit==25){
			pageStr = pageStr + "<a class='currents'  >25</a><a href='javascript:setCookieValue(10,50);'  >50</a><a href='javascript:setCookieValue(10,100);'  >100</a><a href='javascript:setCookieValue(10,0);'  >All</a>" ;
		}
		else if(pageLimit==50){
			pageStr = pageStr + "<a href='javascript:setCookieValue(10,25);'  >25</a><a class='currents'  >50</a><a href='javascript:setCookieValue(10,100);'  >100</a><a href='javascript:setCookieValue(10,0);'  >All</a>";
		}
		else if(pageLimit==100){
			pageStr = pageStr + "<a href='javascript:setCookieValue(10,25);'  >25</a><a href='javascript:setCookieValue(10,50);'  >50</a><a class='currents'  >100</a><a href='javascript:setCookieValue(10,0);'  >All</a>";
		}
		else {
			pageStr = pageStr + "<a href='javascript:setCookieValue(10,25);'  >25</a><a href='javascript:setCookieValue(10,50);'  >50</a><a href='javascript:setCookieValue(10,100);'  >100</a><a class='currents'  >All</a>";
		}
		
		    pageStr = pageStr +'<span style="margin-right:280px;">&nbsp;&nbsp;</span>';
		
		    pageStr = pageStr + '<a href="javascript:;" onclick=javascript:changeResS(1,"'+gress+'",false)>&lt;</a>' ;
		
		for(var j=0;j<pageArray.length;j++){
			
			if(pageArray[j]==pageInfo.pageString){
				
				pageStr = pageStr + '<a id="a'+pageArray[j]+'" class="currents">'+pageArray[j]+'</a>';
			}
			else if(pageArray[j]=='dot'){
				
				pageStr = pageStr + '...';
			}
			else{
				
				pageStr = pageStr + '<a id="a'+pageArray[j]+'" href=javascript:changeResS('+pageArray[j]+',"'+gress+'",false); >'+pageArray[j]+'</a>';
			}
		}
		
		pageStr = pageStr + '<a href="javascript:;" onclick=javascript:changeResS('+pageInfo.totalPage+',"'+gress+'",false)>&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		
		$('.quotes').html(pageStr);
		


		
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
	
	
	
}



function resourceShow(text,gress){
	
	indexTable++;
	
	var data = eval(text);
	var array = data.resourceStat ;
	
	/*
	 * delete the div content
	 */
	
	var htmlStr = '' ;
	
	htmlStr = htmlStr+'<table id="show_table">';
	htmlStr = htmlStr+"<tr height='30'><th>&nbsp;</th><th class='bcess_td'  bgcolor='#f9fbf1' >Currently</th><th class='bcess_td' bgcolor='#f1fbf8'>15 Min</th><th class='bcess_td' bgcolor='#f2f1fb'>1 Hour</th><th class='bcess_td' bgcolor='#f8e9ee'>24 Hour</th></tr>";
	htmlStr = htmlStr+"<tr height='28'><td width='200px' class='bcess_td'><span> <a href='javascript:' onclick=changeResT('"+gress+"')>Resource ID</a></span></td><td class='bcess_td' bgcolor='#f9fbf1' ><div style='width:50%;float:left;text-align:center;'>Calls</div><div style='width:50%;float:left;text-align:center;'>CPS</div></td>";
	htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
	htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">ACD</div><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
	htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td></tr>';
	
	for(var i=0;i<array.length;i++){
		htmlStr = htmlStr+'<tr>';
		htmlStr = htmlStr+'<td class="bcess_td" height="28"><span><a href="./resourceStats/'+array[i].id+'/'+array[i].resourceID+'.html" style="	text-decoration: none;	color: #005c9c;"  title='+array[i].resourceName+'>'+array[i].resourceID+'</a></span></td>';
		htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f9fbf1" ><div style="width:50%;float:left;text-align:center;">'+array[i].bean.call+'</div><div style="width:50%;float:left;text-align:center;">'+array[i].bean.cps+'</div></td>';
		
		var moniBeans = array[i].beans ;
		
		for(var j=0;j<moniBeans.length;j++){
			if(j==0){
				htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
			}
			else if(j==1){
				htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
			}
			else {
				htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
			}
		}
		htmlStr = htmlStr+'</tr>';
	}
	
	htmlStr = htmlStr+"</table>";
	
	$('#context_right_table_div').html(htmlStr);		
	
	var pageInfo = data.resPageParam;
	
	var pageArray = pageInfo.pages;
	
	var pageLimit = pageInfo.limit ;
	
	var pageStr = "<span style='font-size:10pt;color:#aaaaaa;' >Per page:</span>" ;	
	
	if(pageLimit==25){
		pageStr = pageStr + "<a class='currents'  >25</a><a href='javascript:setCookieValue(10,50);'  >50</a><a href='javascript:setCookieValue(10,100);'  >100</a><a href='javascript:setCookieValue(10,0);'  >All</a>" ;
	}
	else if(pageLimit==50){
		pageStr = pageStr + "<a href='javascript:setCookieValue(10,25);'  >25</a><a class='currents'  >50</a><a href='javascript:setCookieValue(10,100);'  >100</a><a href='javascript:setCookieValue(10,0);'  >All</a>";
	}
	else if(pageLimit==100){
		pageStr = pageStr + "<a href='javascript:setCookieValue(10,25);'  >25</a><a href='javascript:setCookieValue(10,50);'  >50</a><a class='currents'  >100</a><a href='javascript:setCookieValue(10,0);'  >All</a>";
	}
	else {
		pageStr = pageStr + "<a href='javascript:setCookieValue(10,25);'  >25</a><a href='javascript:setCookieValue(10,50);'  >50</a><a href='javascript:setCookieValue(10,100);'  >100</a><a class='currents'  >All</a>";
	}
	    pageStr = pageStr +'<span style="margin-right:280px;">&nbsp;&nbsp;</span>';
		
		pageStr = pageStr + '<a href="javascript:;" onclick=javascript:changeRes(1,"'+gress+'")>&lt;</a>' ;
	
	for(var j=0;j<pageArray.length;j++){
		
		if(pageArray[j]==pageInfo.pageString){
			
			pageStr = pageStr + '<a id="a'+pageArray[j]+'" class="currents">'+pageArray[j]+'</a>';
		}
		else if(pageArray[j]=='dot'){
			
			pageStr = pageStr + '...';
		}
		else{
			
			pageStr = pageStr + '<a id="a'+pageArray[j]+'" href=javascript:changeRes('+pageArray[j]+',"'+gress+'"); >'+pageArray[j]+'</a>';
		}
	}
	
	pageStr = pageStr + '<a href="javascript:;" onclick=javascript:changeRes('+pageInfo.totalPage+',"'+gress+'")>&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	
	$('.quotes').html(pageStr);
	


	
}

function changeResT(gress){
	$.ajax({	
		url:"resourceStats.html?cmd=ajax&grs="+gress+"&sort=t&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
	resourceShow(text,gress);
	},
		error:function(){
		indexTable++;
		
		location.reload();
		
	}
	});
}

function changeResH(gress,component){
	
	$.ajax({	
		url:"resourceStats.html?cmd=hide&grs="+gress+"&key="+$(component).attr("checked")+"&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
	resourceShow(text,gress);
	},
		error:function(){
		indexTable++;
		
		location.reload();
		
	}
	});
}


function changeRes(page,gress){
	$.ajax({	
		url:"resourceStats.html?cmd=ajax&grs="+gress+"&page="+page+"&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
	resourceShow(text,gress);
	},
		error:function(){
		indexTable++;
		
		location.reload();
		
	}
	});

}

function changeResS(page,gress,bool){
	var word=" ";

	if(bool){
		
		word = $('#diskey').val();
		
		$("#hidekey").val(word);
	}
	else{
		
		word = $("#hidekey").val();
	}
	$.ajax({	
		url:"resourceStats.html?cmd=search&key="+word+"&grs="+gress+"&page="+page+"&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
		resourceShow(text,gress);
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
}

function changeAttr(gress){
	
	if(gress=='ingress'){
		
		$('#ingressAttr').parent("li").attr("class","selected");
		
		
		$('#egressAttr').parent('li').removeAttr("class");
	}
	else{
		
		$('#egressAttr').parent("li").attr("class","selected");

		
		$('#ingressAttr').parent('li').removeAttr("class");
	}
}


function displayAdvance(com){

	var borwser = navigator.appName;
	
	if(borwser=='Netscape'){
	
		if(index==0){
			var left = com.offsetLeft - 80 ;
			var top = com.offsetTop + 153 ;
			var coms = document.getElementById("advanceDiv");
			
			document.getElementById("keyTds").height = document.getElementById("advanceDiv").offsetHeight-10;
			
			coms.style.left = left + 'px';
			coms.style.top = top +'px' ;
			coms.style.visibility = "visible" ;

			var comShape = document.getElementById("shapeDiv");
			
			comShape.style.left = left + 'px';
			comShape.style.top = top +'px' ;
			comShape.style.visibility = "visible" ;
			
			setTimeout(function(){
				
				document.body.onclick=function (e){
					
					if(index==1){
						
						var leftX = e.pageX;
						var leftY = e.pageY;
						
//						alert(left);
//						alert(leftX);
//						alert(top);
//						alert(leftY);
						
						if(!(left<leftX&&leftX<left+493&&top<leftY&&leftY<top+86)){
							
							coms.style.visibility = "hidden" ;
							
							comShape.style.visibility = "hidden" ;
							
							document.body.onclick=function(e){};
							
							index = 0 ;
						}
					}
					
				};
				
			},500);
			
//			document.addEventListener('onclick',function(){alert('hello');},false);
			
			index = 1;
		}
		else{
			var coms = document.getElementById("advanceDiv");

			coms.style.visibility = "hidden" ;
			
			var comShape = document.getElementById("shapeDiv");	

			comShape.style.visibility = "hidden" ;
			
			document.body.onclick=function(e){};
			
			index = 0 ;
		}
		
	}
	else if(borwser=='Microsoft Internet Explorer'){
		if(index==0){

		
		document.getElementById("keyTd").width = "428px" ;
		document.getElementById("keyTds").height = document.getElementById("advanceDiv").offsetHeight-8;
		
		var left = com.offsetLeft - 80 ;
		var top = com.offsetTop + 253 ;
		var coms = document.getElementById("advanceDiv");

		coms.style.left = left + 'px';
		coms.style.top = top +'px' ;
		coms.style.visibility = "visible" ;

		var comShape = document.getElementById("shapeDiv");

		comShape.style.left = left + 'px';
		comShape.style.top = top +'px' ;
		comShape.style.visibility = "visible" ;

//		setTimeout(function(){
			
//			document.body.onclick=function (e){
//				alert('4');
//				if(index==1){
//					alert('5');
//					alert(e);
//					var leftX = e.clientX;
//					var leftY = e.clientY;
//					alert('6');
//					alert(left);
//					alert(leftX);
//					alert(top);
//					alert(leftY);
					
//					if(!(left<leftX&&leftX<left+493&&top<leftY&&leftY<top+86)){
//						
//						coms.style.visibility = "hidden" ;
//						
//						comShape.style.visibility = "hidden" ;
//						
//						document.body.onclick=function(e){};
//						
//						index = 0 ;
//					}
//				}
//				
//			};
//			
//		},500);
//		alert('1');
//		setTimeout(function(){
//			
//			document.attachEvent('onclick',listener());
//				alert('2');
//				if(index==1){
//
//					var leftX = e.clientX;
//					var leftY = e.clientY;
//
//					
//					if(!(left<leftX&&leftX<left+493&&top<leftY&&leftY<top+86)){
//						
//						coms.style.visibility = "hidden" ;
//						
//						comShape.style.visibility = "hidden" ;
//						
//						document.attachEvent('onclick',function(e){ });
//						
//						index = 0 ;
//					}
//				}
				
				
			
			
//		},1000);
		
		index = 1;
	}
	else{
		var coms = document.getElementById("advanceDiv");

		coms.style.visibility = "hidden" ;
		
		var comShape = document.getElementById("shapeDiv");	

		comShape.style.visibility = "hidden" ;
		
		document.detachEvent('onclick',function(e){});
		
		index = 0 ;
	}
}
}


function productShow(text){
	indexTable++;
	var data = eval(text);
	var array = data.productStat ;

	var htmlStr = '' ;
	
	htmlStr = htmlStr+'<table id="show_table">';
	htmlStr = htmlStr+"<tr height='30'><th>&nbsp;</th><th class='bcess_td'  bgcolor='#f9fbf1' >Currently</th><th class='bcess_td' bgcolor='#f1fbf8'>15 Min</th><th class='bcess_td' bgcolor='#f2f1fb'>1 Hour</th><th class='bcess_td'  bgcolor='#f8e9ee'>24 Hour</th></tr>";
	htmlStr = htmlStr+"<tr height='28'><td width='200px' class='bcess_td'><span><A href='javascript:;' onclick='changeProT()'>Product Name</A></span></td><td class='bcess_td' bgcolor='#f9fbf1' ><div style='width:50%;float:left;text-align:center;'>Calls</div><div style='width:50%;float:left;text-align:center;'>CPS</div></td>";
	htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
	htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">ACD</div><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
	htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td></tr>';
	
	for(var i=0;i<array.length;i++){
		htmlStr = htmlStr+'<tr>';
		htmlStr = htmlStr+'<td height="28" class="bcess_td"><span><a href="./productStats/'+array[i].resourceID+'/'+array[i].id+'.html" style="	text-decoration: none;	color: #005c9c;">'+array[i].resourceID+'</a></span></td>';
		htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f9fbf1"><div style="width:50%;float:left;text-align:center;">'+array[i].bean.call+'</div><div style="width:50%;float:left;text-align:center;">'+array[i].bean.cps+'</div></td>';
		
		var moniBeans = array[i].beans ;
		
		for(var j=0;j<moniBeans.length;j++){
			if(j==0){
				htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
			}
			else if(j==1){
				htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
			}
			else {
				htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
			}			}
		htmlStr = htmlStr+'</tr>';
	}
	
	htmlStr = htmlStr+"</table>";

	$('#proStatsDiv').html(htmlStr);

	var pageInfo = data.proPageParam;		
	var pageArray = pageInfo.pages;
	var pageLimit = pageInfo.limit ;		
	var pageStr = "<span style='font-size:10pt;color:#aaaaaa;' >Per page:</span>" ;	
	
	if(pageLimit==25){
		pageStr = pageStr + "<a class='currents'  >25</a><a href='javascript:setCookieValue(11,50);'  >50</a><a href='javascript:setCookieValue(11,100);'  >100</a><a href='javascript:setCookieValue(11,0);'  >All</a>" ;
	}
	else if(pageLimit==50){
		pageStr = pageStr + "<a href='javascript:setCookieValue(11,25);'  >25</a><a class='currents'  >50</a><a href='javascript:setCookieValue(11,100);'  >100</a><a href='javascript:setCookieValue(11,0);'  >All</a>";
	}
	else if(pageLimit==100){
		pageStr = pageStr + "<a href='javascript:setCookieValue(11,25);'  >25</a><a href='javascript:setCookieValue(11,50);'  >50</a><a class='currents'  >100</a><a href='javascript:setCookieValue(11,0);'  >All</a>";
	}
	else {
		pageStr = pageStr + "<a href='javascript:setCookieValue(11,25);'  >25</a><a href='javascript:setCookieValue(11,50);'  >50</a><a href='javascript:setCookieValue(11,100);'  >100</a><a class='currents'  >All</a>";
	}
	
	    pageStr = pageStr +'<span style="margin-right:280px;">&nbsp;&nbsp;</span>';
	
	    pageStr = pageStr + '<a href="javascript:;" onclick="javascript:changePro(1)">&lt;</a>' ;
	
	for(var j=0;j<pageArray.length;j++){
		
		if(pageArray[j]==pageInfo.pageString){
			
			pageStr = pageStr + '<a id="a'+pageArray[j]+'" class="currents">'+pageArray[j]+'</a>';
		}
		else if(pageArray[j]=='dot'){
			
			pageStr = pageStr + '...';
		}
		else{
			
			pageStr = pageStr + '<a id="a'+pageArray[j]+'" href="javascript:changePro('+pageArray[j]+');" >'+pageArray[j]+'</a>';
		}
	}
	
	pageStr = pageStr + '<a href="javascript:;" onclick="javascript:changePro('+pageInfo.totalPage+')">&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	
	$('.quotes').html(pageStr);

}

function changeProT(){
	$.ajax({
		url:"productStats.html?cmd=ajax"+"&sort=t&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
		productShow(text);
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
}

function changeProH(component){

	$.ajax({
		url:"productStats.html?cmd=hide"+"&key="+$(component).attr("checked")+"&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
		productShow(text);
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
}


function changePro(page){

	$.ajax({	
		url:"productStats.html?cmd=ajax"+"&page="+page+"&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
		productShow(text);
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
	
	
}



function init(com){
	
	$(com).val("");
}



function changeProS(page,bool){

	var word = '';
	
	if(bool){
		
		word = $('#diskey').val();
		$("#hidekey").val(word);
	}
	else{
		
		word = $("#hidekey").val();
	}
	
	$.ajax({	
		url:"productStats.html?cmd=search&key="+word+"&page="+page+"&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
		productShow(text);
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
	
	
}

function changePre(page,id){

	
	
//	$('#uploadDiv')
//	.ajaxStart(function(){
//		$(this).css({"height":document.body.offsetHeight,"position":"absolute"});
//		$(this).show();
//	})
//	.ajaxComplete(function(){
//		$(this).hide();
//	});
	
 var name = $("#proIds").val();
	
	$.ajax({	
		url:"prosin.html?ajax=t&proId="+id+"&page="+page+"&proName="+name+"&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
		indexTable++;
		var data = eval(text);
		var array = data.prefixStat ;
		
//		alert(array);
		
		/*
		 * delete the div content
		 */
		
		var htmlStr = '' ;
		
		htmlStr = htmlStr+'<table id="show_table">';
		htmlStr = htmlStr+"<tr height='30'><th>&nbsp;</th><th class='bcess_td'  bgcolor='#f9fbf1' >Currently</th><th class='bcess_td' bgcolor='#f1fbf8'>15 Min</th><th class='bcess_td' bgcolor='#f2f1fb'>1 Hour</th><th bgcolor='#f8e9ee'>24 Hour</th></tr>";
		htmlStr = htmlStr+"<tr height='28'><td width='200px' class='bcess_td'><span>Prefix Route Name</span></td><td class='bcess_td' bgcolor='#f9fbf1' ><div style='width:50%;float:left;text-align:center;'>Calls</div><div style='width:50%;float:left;text-align:center;'>CPS</div></td>";
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">ACD</div><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td></tr>';
		
		for(var i=0;i<array.length;i++){
			htmlStr = htmlStr+'<tr>';
			htmlStr = htmlStr+'<td height="28" class="bcess_td"><span>'+array[i].resourceID+'</span></td>';
			htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f9fbf1"><div style="width:50%;float:left;text-align:center;">'+array[i].bean.call+'</div><div style="width:50%;float:left;text-align:center;">'+array[i].bean.cps+'</div></td>';
			
			var moniBeans = array[i].beans ;
			
			for(var j=0;j<moniBeans.length;j++){
				if(j==0){
					htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else if(j==1){
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else {
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}			}
			htmlStr = htmlStr+'</tr>';
		}
		
		htmlStr = htmlStr+"</table>";
		
//		alert(htmlStr);
		
		$('#proStatsDiv').html(htmlStr);
		
		
		var pageInfo = data.prePageParam;
		
		var pageArray = pageInfo.pages;
		
//		alert(pageInfo.pages);
//		alert(pageInfo.pageString);
//		alert(pageInfo.totalPage);
		var pageLimit = pageInfo.limit ;
		
		var pageStr = "<span style='font-size:10pt;color:#aaaaaa;' >Per page:</span>" ;	
		
		if(pageLimit==25){
			pageStr = pageStr + "<a class='currents'  >25</a><a href='javascript:setCookieValue(12,50);'  >50</a><a href='javascript:setCookieValue(12,100);'  >100</a><a href='javascript:setCookieValue(12,0);'  >All</a>" ;
		}
		else if(pageLimit==50){
			pageStr = pageStr + "<a href='javascript:setCookieValue(12,25);'  >25</a><a class='currents'  >50</a><a href='javascript:setCookieValue(12,100);'  >100</a><a href='javascript:setCookieValue(12,0);'  >All</a>";
		}
		else if(pageLimit==100){
			pageStr = pageStr + "<a href='javascript:setCookieValue(12,25);'  >25</a><a href='javascript:setCookieValue(12,50);'  >50</a><a class='currents'  >100</a><a href='javascript:setCookieValue(12,0);'  >All</a>";
		}
		else {
			pageStr = pageStr + "<a href='javascript:setCookieValue(12,25);'  >25</a><a href='javascript:setCookieValue(12,50);'  >50</a><a href='javascript:setCookieValue(12,100);'  >100</a><a class='currents'  >All</a>";
		}
		
		    pageStr = pageStr +'<span style="margin-right:280px;">&nbsp;&nbsp;</span>';
		    
		    pageStr = pageStr + '<a href="javascript:;" onclick=javascript:changePre(1,'+id+')>&lt;</a>' ;
		
		
		
		for(var j=0;j<pageArray.length;j++){
			
			if(pageArray[j]==pageInfo.pageString){
				
				pageStr = pageStr + '<a id="a'+pageArray[j]+'" class="currents">'+pageArray[j]+'</a>';
			}
			else if(pageArray[j]=='dot'){
				
				pageStr = pageStr + '...';
			}
			else{
				
				pageStr = pageStr + '<a id="a'+pageArray[j]+'" href=javascript:changePre('+pageArray[j]+','+id+'); >'+pageArray[j]+'</a>';
			}
		}
		
		pageStr = pageStr + '<a href="javascript:;" onclick=javascript:changePre('+pageInfo.totalPage+','+id+')>&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		
		
		
		$('.quotes').html(pageStr);
		

//		alert(array.length);
//		alert(array[0]);
//		
//		alert(array[0].beans);
//		alert(array[0].beans[0]);
//		alert(array[0].beans[0].ca);
		
//		alert($('.currents').html());
//		$(".currents").attr("onclick","javascript:");
//		alert('d');
//		$(".currents").attr("href","javascript:changePre("+$(".currents").html()+","+name+","+id+");");
//		$(".currents").removeAttr("class");
		
		
//		$("#a"+page).removeAttr("onclick");
//		$("#a"+page).removeAttr("href");
//		$("#a"+page).attr("class","currents");
		
		
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
	
	
}



function changePreS(page,id,bool){

	
	
//	$('#uploadDiv')
//	.ajaxStart(function(){
//		$(this).css({"height":document.body.offsetHeight,"position":"absolute"});
//		$(this).show();
//	})
//	.ajaxComplete(function(){
//		$(this).hide();
//	});
	
	var word = '';
	
	if(bool){
		
		word = $('#diskey').val();
		$("#hidekey").val(word);
	}
	else{
		
		word = $("#hidekey").val();
	}
	
	
 var name = $("#proIds").val();
	
	$.ajax({	
		url:"prosin.html?ajax=s&proId="+id+"&page="+page+"&proName="+name+"&key="+word+"&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
		indexTable++;
		var data = eval(text);
		var array = data.prefixStat ;
		
//		alert(array);
		
		/*
		 * delete the div content
		 */
		
		var htmlStr = '' ;
		
		htmlStr = htmlStr+'<table id="show_table">';
		htmlStr = htmlStr+"<tr height='30'><th>&nbsp;</th><th class='bcess_td'  bgcolor='#f9fbf1' >Currently</th><th class='bcess_td' bgcolor='#f1fbf8'>15 Min</th><th class='bcess_td' bgcolor='#f2f1fb'>1 Hour</th><th bgcolor='#f8e9ee'>24 Hour</th></tr>";
		htmlStr = htmlStr+"<tr height='28'><td width='200px' class='bcess_td'><span>Prefix Route Name</span></td><td class='bcess_td' bgcolor='#f9fbf1' ><div style='width:50%;float:left;text-align:center;'>Calls</div><div style='width:50%;float:left;text-align:center;'>CPS</div></td>";
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">ACD</div><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td></tr>';
		
		for(var i=0;i<array.length;i++){
			htmlStr = htmlStr+'<tr>';
			htmlStr = htmlStr+'<td height="28" class="bcess_td"><span>'+array[i].resourceID+'</span></td>';
			htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f9fbf1"><div style="width:50%;float:left;text-align:center;">'+array[i].bean.call+'</div><div style="width:50%;float:left;text-align:center;">'+array[i].bean.cps+'</div></td>';
			
			var moniBeans = array[i].beans ;
			
			for(var j=0;j<moniBeans.length;j++){
				if(j==0){
					htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else if(j==1){
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else {
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}			}
			htmlStr = htmlStr+'</tr>';
		}
		
		htmlStr = htmlStr+"</table>";
		
//		alert(htmlStr);
		
		$('#proStatsDiv').html(htmlStr);
		
		
		var pageInfo = data.prePageParam;
		
		var pageArray = pageInfo.pages;
		
//		alert(pageInfo.pages);
//		alert(pageInfo.pageString);
//		alert(pageInfo.totalPage);
		var pageLimit = pageInfo.limit ;
		
		var pageStr = "<span style='font-size:10pt;color:#aaaaaa;' >Per page:</span>" ;	
		
		if(pageLimit==25){
			pageStr = pageStr + "<a class='currents'  >25</a><a href='javascript:setCookieValue(12,50);'  >50</a><a href='javascript:setCookieValue(12,100);'  >100</a><a href='javascript:setCookieValue(12,0);'  >All</a>" ;
		}
		else if(pageLimit==50){
			pageStr = pageStr + "<a href='javascript:setCookieValue(12,25);'  >25</a><a class='currents'  >50</a><a href='javascript:setCookieValue(12,100);'  >100</a><a href='javascript:setCookieValue(12,0);'  >All</a>";
		}
		else if(pageLimit==100){
			pageStr = pageStr + "<a href='javascript:setCookieValue(12,25);'  >25</a><a href='javascript:setCookieValue(12,50);'  >50</a><a class='currents'  >100</a><a href='javascript:setCookieValue(12,0);'  >All</a>";
		}
		else {
			pageStr = pageStr + "<a href='javascript:setCookieValue(12,25);'  >25</a><a href='javascript:setCookieValue(12,50);'  >50</a><a href='javascript:setCookieValue(12,100);'  >100</a><a class='currents'  >All</a>";
		}
		
		    pageStr = pageStr +'<span style="margin-right:280px;">&nbsp;&nbsp;</span>';
		    
		    pageStr = pageStr +'<a href="javascript:;" onclick=javascript:changePreS(1,'+id+',false)>&lt;</a>' ;
		
		
		
		for(var j=0;j<pageArray.length;j++){
			
			if(pageArray[j]==pageInfo.pageString){
				
				pageStr = pageStr + '<a id="a'+pageArray[j]+'" class="currents">'+pageArray[j]+'</a>';
			}
			else if(pageArray[j]=='dot'){
				
				pageStr = pageStr + '...';
			}
			else{
				
				pageStr = pageStr + '<a id="a'+pageArray[j]+'" href=javascript:changePreS('+pageArray[j]+','+id+',false); >'+pageArray[j]+'</a>';
			}
		}
		
		pageStr = pageStr + '<a href="javascript:;" onclick=javascript:changePreS('+pageInfo.totalPage+','+id+',false)>&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		
		
		
		$('.quotes').html(pageStr);
		

//		alert(array.length);
//		alert(array[0]);
//		
//		alert(array[0].beans);
//		alert(array[0].beans[0]);
//		alert(array[0].beans[0].ca);
		
//		alert($('.currents').html());
//		$(".currents").attr("onclick","javascript:");
//		alert('d');
//		$(".currents").attr("href","javascript:changePre("+$(".cur'rents").html()+","+name+","+id+");");
//		$(".currents").removeAttr("class");
		
		
//		$("#a"+page).removeAttr("onclick");
//		$("#a"+page).removeAttr("href");
//		$("#a"+page).attr("class","currents");
		
		
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
	
	
}



function changeIp(id){
	

//	$('#uploadDiv')
//	.ajaxStart(function(){
//		$(this).css({"height":document.body.offsetHeight,"position":"absolute"});
//		$(this).show();
//	})
//	.ajaxComplete(function(){
//		$(this).hide();
//	});
	
	$.ajax({	
		url:"ressin.html?ajax=t&resId="+id+"&index="+indexTable,
		type:'GET',
		dataType:'json',
		
		success:function(text){
		indexTable++;
		var data = eval(text);
		var array = data.resourceStat ;
		
//		alert(array);
		
		/*
		 * delete the div content
		 */
		
		var htmlStr = '' ;
		
		htmlStr = htmlStr+'<table id="show_table">';
		htmlStr = htmlStr+"<tr height='30'><th>&nbsp;</th><th class='bcess_td'  bgcolor='#f9fbf1' >Currently</th><th class='bcess_td' bgcolor='#f1fbf8'>15 Min</th><th class='bcess_td' bgcolor='#f2f1fb'>1 Hour</th><th bgcolor='#f8e9ee'>24 Hour</th></tr>";
		htmlStr = htmlStr+"<tr height='28'><td width='200px' class='bcess_td'><span>Resource ID</span></td><td class='bcess_td' bgcolor='#f9fbf1' ><div style='width:50%;float:left;text-align:center;'>Calls</div><div style='width:50%;float:left;text-align:center;'>CPS</div></td>";
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">ACD</div><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td>';
		htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">ACD</div ><div style="width:25%;float:left;text-align:center;">ASR</div ><div style="width:25%;float:left;text-align:center;">CA</div ><div style="width:25%;float:left;text-align:center;">PDD</div ></td></tr>';
		
		for(var i=0;i<array.length;i++){
			htmlStr = htmlStr+'<tr>';
			htmlStr = htmlStr+'<td height="28" class="bcess_td"><span><a href="./resourceStats/'+array[i].id+'/'+array[i].resourceID+'.html" style="	text-decoration: none;	color: #005c9c;">'+array[i].resourceID+'</a></span></td>';
			htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f9fbf1" ><div style="width:50%;float:left;text-align:center;">'+array[i].bean.call+'</div><div style="width:50%;float:left;text-align:center;">'+array[i].bean.cps+'</div></td>';
			
			var moniBeans = array[i].beans ;
			
			for(var j=0;j<moniBeans.length;j++){
				if(j==0){
					htmlStr = htmlStr+'<td class="bcess_td"  bgcolor="#f1fbf8"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else if(j==1){
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f2f1fb"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}
				else {
					htmlStr = htmlStr+'<td class="bcess_td" bgcolor="#f8e9ee"><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].acd+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].asr+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].ca+'</div><div style="width:25%;float:left;text-align:center;">'+moniBeans[j].pdd+'</div></td>' ;
				}			}
			htmlStr = htmlStr+'</tr>';
		}
		
		htmlStr = htmlStr+"</table>";
		
//		alert(htmlStr);
		
		$('#proStatsDiv').html(htmlStr);
		
	},
		error:function(){
		indexTable++;
//		alert('Error');
		location.reload();
	}
	});
}

function changeTime(bool){
	
	var value = $("input[name='reTime']:checked").val();

	window.clearInterval(time);
	
	

	changeGlobal(bool);

	if(value==0){
		time = window.setInterval("changeGlobal('z')",180000);
	}
	else if(value==1){
		time = window.setInterval("changeGlobal('z')",300000);
	}
	else if(value==2){
		time = window.setInterval("changeGlobal('z')",1500000);
	}
}


function changeGlobal(bool){
	
//	$('#uploadDiv')
//	.ajaxStart(function(){
//		$(this).css({"height":document.body.offsetHeight,"position":"absolute"});
//		$(this).show();
//	})
//	.ajaxComplete(function(){
//		$(this).hide();
//	});
	
//	ajaxControls(1,7,0,0,indexTable);
//	extendChart(1,7,0,0) ;
	
	$.ajax({
		url:"globals.html?ajax=t&clear="+bool+"&index="+indexTable,
		type:"GET",
		dataType:'json',
		
		success:function(text){
		indexTable++;
		
		var data = eval(text);
		
		var pointerValue = data.pointValue ;
		var totalInfos = data.totalInfo ;
		
		if(pointerValue==null){
			return ;
		}
		
		var peak = pointerValue.peaks ;
		
		var bean = pointerValue.bean ;
		
		var htmlStr1 = '';
		
		htmlStr1 = htmlStr1+'<table width="100%" cellpadding=0 cellspacing=0 style="border:1px solid #888888" ><tr height="30px" bgcolor="#eeeeee"><th class="bcess_td" width="150px">&nbsp;</th><th class="bcess_td" width="150px">Currently</th><th class="bcess_td" width="150px">24 hr Peak</th><th class="bcess_td" width="150px">7 day Peak</th><th class="bcess_td" width="150px">Recent Peak</th></tr>' ;
		for(var i=0;i<peak.length;i++){
			if(i%2==1){
				htmlStr1 = htmlStr1+'<tr  bgcolor="#eeeeee" height="28px"><td class="bcess_td" align="center">'+peak[i].title+'</td><td class="bcess_td" align="center">'+peak[i].current+'</td><td class="bcess_td" align="center">'+peak[i].daypeak+'</td><Td class="bcess_td" align="center">'+peak[i].weekpeak+'</Td><td class="bcess_td" align="center">'+peak[i].recentpeak+'</td></tr>';	
			}
			else{
				htmlStr1 = htmlStr1+'<tr height="28px"><td class="bcess_td" align="center">'+peak[i].title+'</td><td class="bcess_td" align="center">'+peak[i].current+'</td><td class="bcess_td" align="center">'+peak[i].daypeak+'</td><Td class="bcess_td" align="center">'+peak[i].weekpeak+'</Td><td class="bcess_td" align="center">'+peak[i].recentpeak+'</td></tr>';	
			}
		}
		htmlStr1 = htmlStr1+"</table>";
		
		$(".monitor_global_style_7").html(htmlStr1);
		
		var htmlStr2 = '';
		
		htmlStr2 = htmlStr2+'<table width="100%" cellpadding=0 cellspacing=0 style="border:1px solid #888888"><tr height="30px" bgcolor="#eeeeee"><th width="100px" class="bcess_td">&nbsp;</th><th width="150px" class="bcess_td">Currently</th><th width="150px" class="bcess_td">System Cap</th></tr>';
		htmlStr2 = htmlStr2+'<Tr height="28px"><td class="bcess_td" align="center">Total Calls</td><td class="bcess_td" align="center">'+bean.totalCurrent+'</td><td class="bcess_td" align="center">'+bean.totalMax+'</td></tr>';
		htmlStr2 = htmlStr2+'<Tr height="28px" bgcolor="#eeeeee"><td class="bcess_td" align="center">Total CPS</td><td class="bcess_td" align="center">'+bean.cpsCurrent+'</td><td class="bcess_td" align="center">'+bean.cpsMax+'</td></tr>';
		htmlStr2 = htmlStr2+'</table>';
		
		$('#monitor_global_style_8').html(htmlStr2);
		
		var htmlStr3 = '' ;
		
		htmlStr3 = htmlStr3+'<table id="show_table" cellpadding=0 cellspacing=0  width="98%">';
		htmlStr3 = htmlStr3+'<tr height="30">';
		htmlStr3 = htmlStr3+'<th class="bcess_td">&nbsp;</th>';
		htmlStr3 = htmlStr3+'<th class="bcess_td">15 Min</th>';
		htmlStr3 = htmlStr3+'<th class="bcess_td">1 Hour</th>';
		htmlStr3 = htmlStr3+'<th class="bcess_td">24 Hour</th>';
		htmlStr3 = htmlStr3+'</tr>';
		htmlStr3 = htmlStr3+'<tr height="28">';
		htmlStr3 = htmlStr3+'<td width="200px" class="bcess_td" style="background-color:#eeeeee">&nbsp;</td>';
		htmlStr3 = htmlStr3+'<td class="bcess_td" style="background-color:#eeeeee"><div class=" monitor_global_style_12">ACD</div ><div class=" monitor_global_style_13">ASR</div ><div class=" monitor_global_style_14">CA</div > <div class=" monitor_global_style_20">PDD</div></td>';
		htmlStr3 = htmlStr3+'<td class="bcess_td" style="background-color:#eeeeee"><div class=" monitor_global_style_15">ACD</div><div class=" monitor_global_style_13">ASR</div ><div class=" monitor_global_style_14">CA</div > <div class=" monitor_global_style_20">PDD</div></td>';
		htmlStr3 = htmlStr3+'<td class="bcess_td" style="background-color:#eeeeee"><div class=" monitor_global_style_12">ACD</div ><div class=" monitor_global_style_13">ASR</div ><div class=" monitor_global_style_20">CA</div> <div class=" monitor_global_style_20">PDD</div></td>';
		htmlStr3 = htmlStr3+'</tr>';
		
		for(var j=0;j<totalInfos.length;j++){
			
				htmlStr3 = htmlStr3+'<tr height="28" >';
				htmlStr3 = htmlStr3+'<td class="bcess_td"><span>'+totalInfos[j].resourceID+'</span></td>';
				
				var info = totalInfos[j].beans ;
				
				for(var k=0;k<info.length;k++){
					htmlStr3 = htmlStr3+'<td class="bcess_td"><div class=" monitor_global_style_21">'+info[k].acd+'</div><div class=" monitor_global_style_22">'+info[k].asr+'</div><div class=" monitor_global_style_23">'+info[k].ca+'</div><div class=" monitor_global_style_23">'+info[k].pdd+'</div></td>';
				}
				htmlStr3 = htmlStr3+'</tr>';	
			
		}
		htmlStr3 = htmlStr3+'</table>';
		
		$('.monitor_global_style_11').html(htmlStr3);
	},
		error:function(){
		indexTable++;
		location.reload();
	}
	});
	
}



function refresh(){
	window.location.href="globals.html";
}


function clear(){
	
	$.ajax({
	url:"globals.html?ajax=t",
	type:"GET",
	success:function(text){
		
	},
	error:function(){
		location.reload();	
	}
	});
	
}



function refreshChart(){
	
	var index = $("input[name='chartTime']:checked").val();
	
	
	var url = $("#pic1").attr("src");
	
	if(url.indexOf("&chartTime=")>0){
		url = url.substring(0,url.indexOf("&chartTime="));	
	}
	
	$('#pic1').attr("src",url+"&chartTime="+index+"&chartIndex="+indexChart);
	
	
	
	url = $("#pic2").attr("src");
	
	if(url.indexOf("&chartTime=")>0){
		url = url.substring(0,url.indexOf("&chartTime="));	
	}
	
	$('#pic2').attr("src",url+"&chartTime="+index+"&chartIndex="+indexChart);
	
	indexChart++;
}

function refreshTable(obj,pid){
	
	var page = $(".currents").html();

	
	if(obj==1){

		if($("#hidekey").val()==''){
			changePro(page);
		}
		else {
			changeProS(page,false);
		}
	}
	else if(obj==2){
		if($("#hidekey").val()==''){
			changePre(page,pid);
		}
		else {
			changePreS(page,pid,false);
		}
	}
	else if(obj==3){
		if($("#hidekey").val()==''){
			changeRes(page,pid);
		}
		else {
			changeResS(page,pid,false);
		}
	}
	else if(obj==4){
		changeIp(pid);
	}
}


function refreshCharts(index){
	
    // show a spinning wheel while downloading the update
    spinning_wheel = true; 
 
    // number of seconds to wait before a download times out
    timeout = 30; 
 
    // number of times to try downloading before displaying an error
    retry = 2; 
    
    // the update mode (see the update function)
    mode = "reset";
    
    
	
	if(document.getElementById("chartCall").style.display=="block"){
	
		
		// the update url to download the xml update from
	    url = "./generatexml/y/1/"+nameParam+"/"+index+"/"+idParam+".html";
	    
	 // the update url to download the xml update from
	    urls = "./genxml/y/3/"+nameParam+"/"+index+"/"+idParam+".html";
	 

	 
	    document.my_chart.Update_URL( url, spinning_wheel, timeout, retry, mode ); 
	    
	    document.my_charts.Update_URL(urls,spinning_wheel,timeout,retry,mode);
		
	}
	else{
	
		
		// the update url to download the xml update from
	    url = "./genlongxml/y/1/"+nameParam+"/"+index+"/"+idParam+".html";
	    
	 // the update url to download the xml update from
	    urls = "./genxmlprefix/y/3/"+nameParam+"/"+index+"/"+idParam+".html";
	    

	    document.my_chartl.Update_URL( url, spinning_wheel, timeout, retry, mode ); 
        
        document.my_chartls.Update_URL(urls,spinning_wheel,timeout,retry,mode);
		
	}
	
}


function arrangeChange(){
	
	var coms = $("#coordinateID");
	

	
	var com = document.getElementById("coordinateID");
	
	var comdiv = document.getElementById("context_left_div") ;
	
	var com1 = document.getElementById("logo_table_div");
	
	var com2 = document.getElementById("nav_div");
	
	alert(com1.offsetHeight);
	
	alert(com2.offsetHeight);
	
	alert(comdiv);
	alert(comdiv.offsetWidth||comdiv.clientWidth);
	
	alert(com.offsetLeft);
	alert(com.offsetTop);
	
}

function changeArrange(indexes){
	

	
	if(indexes==1&&document.getElementById("chartCall").style.display=='none'){

		$('input[name=chartTime]').get(0).checked = true;
		
	    $("#longerID").hide();
	    $("#chartCallL").hide();
	    $("#chartASRL").hide();
	    $("#chartASR").show();
	    $("#chartCall").show();
	    
	}
	else if(document.getElementById("chartCallL").style.display=='none') {

		$('input[name=chartTime]').get(0).checked = true;
	    
	    $("#chartASR").hide();
	    $("#chartCall").hide();
	    $("#longerID").show();
	    $("#chartCallL").show();
	    $("#chartASRL").show();
	    
	}

}






function launchPro(){
	
//	alert(conIndex);
	
	if(conIndex==0){

//		alert(conIndex);
		
		conIndex=1;
		
		if(google!=null){
			google.setOnLoadCallback(drawChartp);
		}
	}
}


function drawChartp(){
	
	if(conIndex==0){
		conIndex = 1;
		
		var id = $("#proIds").val();
		
//	    makeChart(2,1,id,0);
		chartAjax(1,1,id,0);
	}

}


function drawChartr(){
	
	if(conIndex==0){
		conIndex = 1;
		
		var id = $("#resIds").val();
		
		var direct = $("#resDirects").val();
		
//		makeChart(2,2,id,direct);
		chartAjax(1,2,id,direct);
	}
}


function drawCharti(){
	
	if(conIndex==0){
		conIndex = 1;
		
		var id = $("#ipIds").val();
		
		var direct = $("#ipDirects").val();
		
//		makeChart(2,3,id,direct);
		chartAjax(1,3,id,direct);
	}
}



function drawCharts(){

	
	
	if(conIndex==0){

    	conIndex = 1 ;

//    	ajaxControl(2,7,0,0);
//    	chartAjax(1,7,0,0);
   	finalChart(1,7,0,0);
	}
}

function drawChart(rr) {

    dataCall = new google.visualization.DataTable();
    dataCall.addColumn('datetime', 'Date');
    dataCall.addColumn('number', 'CALL');
    dataCall.addColumn('number', 'CPS');
    dataCall.addColumn('number', 'ASR');
    dataCall.addColumn('number', 'ACD');
    dataCall.addColumn('number', 'PDD');

    dataCall.addRows(rr);

    chartCurrent = rr[0][0];



    var volumnStarts = rr[rr.length-1][0].toUTCString().split(',');


    $('#volumeStart').html(volumnStarts[1].substring(1,12));
    $('#qualStart').html(volumnStarts[1].substring(1,12));

    var volumnEnds = rr[0][0].toUTCString().split(',');

    $('#volumeEnd').html(volumnEnds[1].substring(1,12));
    $('#qualEnd').html(volumnEnds[1].substring(1,12));
    
    chartCall = new google.visualization.AnnotatedTimeLine(document.getElementById('coordinateID'));
    chartCall.draw(dataCall, {'allowRedraw':true ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0, 1,2,3,4],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640','#888888'],'min':0});

    
    
    chartCall.setVisibleChartRange(new Date(chartCurrent.getTime()-86400000), chartCurrent);
//    chartCall.hideDataColumns(2);
//    chartCall.hideDataColumns(3);
    
    if(!document.getElementById("call1").checked){
    	chartCall.hideDataColumns(0);
    }
    if(!document.getElementById("cps1").checked){
    	chartCall.hideDataColumns(1);
    }
    if(!document.getElementById("asr1").checked){
    	chartCall.hideDataColumns(2);
    }
    if(!document.getElementById("acd1").checked){
    	chartCall.hideDataColumns(3);
    }
    if(!document.getElementById("pdd1").checked){
    	chartCall.hideDataColumns(4);
    }

    
    $("#chcall").attr("checked",true);
    $("#chcps").attr("checked",true);
    
    google.visualization.events.addListener(chartCall,'rangechange',function(){
    	var callChange = chartCall.getVisibleChartRange();
//    	if(callChange.end.getTime()-callChange.start.getTime()>172800000){
//    		if(chartState==1){
////    			starttime = callChange.start;
////    			endtime = callChange.end;
//    			chartAjaxs(2,7,0,0);
//    		}
//    	}
//    	else{
//    	
//    		if(chartState==2){
////    			starttime = callChange.start;
////    			endtime = callChange.end;
//    			chartAjaxs(1,7,0,0);
//    		}
//    	}
    	chartASR.setVisibleChartRange(callChange.start,callChange.end);
    	$(".zoomnones").attr('class','zoomlines');
        $(".zoomnone").attr('class','zoomline');
    });

    if(arrayCall[0][0].getTime()-arrayCall[1][0].getTime()>800000){
    	arrayCall[0][0] = new Date(chartCurrent.getTime()-28800000);
    }

    
  }

function drawChartD(rr) {

    dataASR = new google.visualization.DataTable();
    dataASR.addColumn('datetime', 'Date');
    dataASR.addColumn('number', 'CALL');
    dataASR.addColumn('number', 'CPS');
    dataASR.addColumn('number', 'ASR');
    dataASR.addColumn('number', 'ACD');
    dataASR.addColumn('number', 'PDD');
    

    dataASR.addRows(rr);

    $("#waitTime").hide();
//    click(3,document.getElementById('initCALL'));
//    clicks(3,document.getElementById('initASR'));
    
    chartASR = new google.visualization.AnnotatedTimeLine(document.getElementById('longerID'));
    chartASR.draw(dataASR, {'allowRedraw':true ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1,2,3,4],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640','#888888'],'min':0});

    var chartCurrents = rr[0][0];
    
    chartASR.setVisibleChartRange(new Date(rr[0][0].getTime()-86400000), rr[0][0]);
//    chartASR.hideDataColumns(0);
//    chartASR.hideDataColumns(1);
    
    if(!document.getElementById("call2").checked){
    	chartASR.hideDataColumns(0);
    }
    if(!document.getElementById("cps2").checked){
    	chartASR.hideDataColumns(1);
    }
    if(!document.getElementById("asr2").checked){
    	chartASR.hideDataColumns(2);
    }
    if(!document.getElementById("acd2").checked){
    	chartASR.hideDataColumns(3);
    }
    if(!document.getElementById("pdd2").checked){
    	chartASR.hideDataColumns(4);
    }

    $("#chasr").attr("checked",true);
    $("#chacd").attr("checked",true);
    
    google.visualization.events.addListener(chartASR,'rangechange',function(){
    	var callChange = chartASR.getVisibleChartRange();
//    	if(callChange.end.getTime()-callChange.start.getTime()>172800000){
//    		if(chartState==1){
////    			starttime = callChange.start;
////    			endtime = callChange.end;
//    			chartAjaxs(2,7,0,0);
//    		}
//    	}
//    	else{
//    	
//    		if(chartState==2){
////    			starttime = callChange.start;
////    			endtime = callChange.end;
//    			chartAjaxs(1,7,0,0);
//    		}
//    	}
    	chartCall.setVisibleChartRange(callChange.start,callChange.end);
    	$(".zoomnones").attr('class','zoomlines');
        $(".zoomnone").attr('class','zoomline');
    });

    if(arrayASR[0][0].getTime()-arrayASR[1][0].getTime()>800000){
    	arrayASR[0][0] = new Date(chartCurrents.getTime()-28800000);
    }
 
  }


function clearChart(timeTypes){

    $("#longerID").html();

    ajaxControl(timeTypes,7,0,0);
}


   function ajaxControls(timeType,objectType,objectId,direct,index){

//	$("#waitTime").show();
	$.ajax({

    	url:'genjson.html?cmd=p&tmt='+timeType+'&obt='+objectType+'&obd='+objectId+'&rct='+direct+'&index='+index,
		type:'GET',
		dataType:'json',
		success:function(text){
    	var data = eval(text);
    	var datas = data.text ;

    	var i = 0 ;

    	var arrayCallExtend = new Array() ;
    	var arrayASRExtend = new Array() ;
	
    	while(i<datas.length){

    		arrayCallExtend[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];
    		arrayASRExtend[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];

    		if(i==datas.length-1){
        		
    			var data = new google.visualization.DataTable();
    		    data.addColumn('datetime', 'Date');
    		    data.addColumn('number', 'CALL');
    		    data.addColumn('number', 'CPS');
    		    data.addColumn('number', 'ASR');
    		    data.addColumn('number', 'ACD');

    		    data.addRows(arrayCallExtend);
    		    
    		    chartCall.draw(data, {'allowRedraw':true ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});
    		    
    		    data = new google.visualization.DataTable();
    		    data.addColumn('datetime', 'Date');
    		    data.addColumn('number', 'CALL');
    		    data.addColumn('number', 'CPS');
    		    data.addColumn('number', 'ASR');
    		    data.addColumn('number', 'ACD');
    		    
    		    data.addRows(arrayASRExtend);
    		    
    		    chartASR.draw(data, {'allowRedraw':true ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});
            }
    		i++;		
    	}

        }
        });
	}
   
   
   function addArray(value,array){
	   
	   var middle1 = value;
		var middle2 ;
		 var max = array.length+1 ;
		for(var t=0;t<max;t++){
			middle2 = array[t];
			array[t] = middle1;
			middle1 = middle2 ;
		}
	   
   }
   
   
   
   function extendChart(timeType,objectType,objectId,direct){
	   
	   $.ajax({

	    	url:'genjson.html?cmd=q&tmt='+timeType+'&obt='+objectType+'&obd='+objectId+'&rct='+direct+'&indexTable='+indexTable,
			type:'GET',
			dataType:'json',
			success:function(text){
		  	indexTable = indexTable+1 ;
		   
	    	var data = eval(text);
	    	var datas = data.text ;

	    	var i = 0 ;

	    	while(i<datas.length){
	    		
	    		var arrays = new Array(parseInt($("#currentColumn1").val())+1);
	    		var arrayd = new Array(parseInt($("#currentColumn2").val())+1);
	    		
	    		var firstChart = 0;
	    		var secondChart = 0 ;
	    		
	    		arrays[0] = new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute) ;
	    		arrayd[0] = arrays[0] ;
	    		chartCurrent = arrayd[0];
	    		addArray(arrays[0],arrayTime);
	    		
	    		var color = new Array();
	    		var colors = new Array();
	    		var column = new Array();
	    		var columns = new Array();
	    		
	    		
	    		if(document.getElementById("call1").checked){
	    			arrays[firstChart+1] = datas[i].values;color.push("#1670e0");column.push(firstChart);
	    			firstChart++;
	    		}
	    		
	    		if(document.getElementById("call2").checked){
	    			arrayd[secondChart+1] = datas[i].values ;colors.push("#1670e0");columns.push(secondChart);
	    			secondChart++ ;
	    		}
	    		
	    		addArray(datas[i].values,arrayCallMVF);
	    		
	    		
	    		if(document.getElementById("cps1").checked&&$("#isSMV1").val()==1){
	    			arrays[firstChart+1] = datas[i].valuecps;color.push("#fa4727");column.push(firstChart);
	    			firstChart++;
	    		}
	    		
	    		if(document.getElementById("cps2").checked&&$("#isSMV2").val()==1){
	    			arrayd[secondChart+1] = datas[i].valuecps;colors.push("#fa4727");columns.push(secondChart);
	    			secondChart++ ;
	    		}
	    		
	    		addArray(datas[i].valuecps,arrayCPSMVT);

	    		
	    		if(document.getElementById("cps1").checked&&$("#isSMV1").val()==0){
	    			arrays[firstChart+1] = datas[i].value;color.push("#fa4727");column.push(firstChart);
	    			firstChart++;
	    		}
	    		
	    		if(document.getElementById("cps2").checked&&$("#isSMV2").val()==0){
	    			arrayd[secondChart+1] = datas[i].value;colors.push("#fa4727");columns.push(secondChart);
	    			secondChart++ ;
	    		}
	    		
	    		addArray(datas[i].value,arrayCPSMVF);
	    		
	    		if(document.getElementById("asr1").checked&&$("#isSMV1").val()==1){
	    			arrays[firstChart+1] = datas[i].valueasr;color.push("#4b9353");column.push(firstChart);
	    			firstChart++;
	    		}
	    		
	    		if(document.getElementById("asr1").checked&&$("#isSMV2").val()==1){
	    			arrayd[secondChart+1] = datas[i].valueasr;colors.push("#4b9353");columns.push(secondChart);
	    			secondChart++ ;
	    		}
	    		
	    		addArray(datas[i].valueasr,arrayASRMVT);

	    		
	    		if(document.getElementById("asr1").checked&&$("#isSMV1").val()==0){
	    			arrays[firstChart+1] = datas[i].valuet;color.push("#4b9353");column.push(firstChart);
	    			firstChart++;
	    		}
	    		
	    		if(document.getElementById("asr1").checked&&$("#isSMV2").val()==0){
	    			arrayd[secondChart+1] = datas[i].valuet;colors.push("#4b9353");columns.push(secondChart);
	    			secondChart++ ;
	    		}

	    		addArray(datas[i].valuet,arrayASRMVF);
	    		
	    		
	    		if(document.getElementById("acd1").checked&&$("#isSMV1").val()==1){
	    			arrays[firstChart+1] = datas[i].valueacd;colors.push("#e6b640");columns.push(secondChart);
	    			firstChart++;
	    		}
	    		
	    		if(document.getElementById("call1").checked&&$("#isSMV2").val()==1){
	    			arrayd[secondChart+1] = datas[i].valueacd;colors.push("#e6b640");columns.push(secondChart);
	    			secondChart++ ;
	    		}
	    		
	    		addArray(datas[i].valueacd,arrayACDMVT);

	    		
	    		if(document.getElementById("acd1").checked&&$("#isSMV1").val()==0){
	    			arrays[firstChart+1] = datas[i].valuef;colors.push("#e6b640");columns.push(secondChart);
	    			firstChart++;
	    		}
	    		
	    		if(document.getElementById("acd1").checked&&$("#isSMV2").val()==0){
	    			arrayd[secondChart+1] = datas[i].valuef;colors.push("#e6b640");columns.push(secondChart);
	    			secondChart++ ;
	    		}
	    		
	    		addArray(datas[i].valuef,arrayACDMVF);
	    		
	    		dataCall.addRow(arrays);
	    		dataASR.addRow(arrayd);
	    		
	    		i++;
	    	}
	    	
	    	dataCall.setValue(0,0,chartCurrent);
	    	dataASR.setValue(0,0,chartCurrent);
	    	
	    	chartCall.draw(dataCall, {'allowRedraw':true ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727'],'min':0});

	    	chartASR.draw(dataASR, {'allowRedraw':true ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0],'scaleType': 'allmaximized','thickness': 2,'colors':['#4b9353','#e6b640'],'min':0});
	    	
	        }
	        });
   }
   


  function finalChart(timeType,objectType,objectId,direct){
	
	$.ajax({
		url:'genjson.html?cmd=k&tmt='+timeType+'&obt='+objectType+'&obd='+objectId+'&rct='+direct,
		type:'GET',
		dataType:'json',
		success:function(text){
		var data = eval(text);
    	var datas = data.text ;
    	
    	arrayTime = new Array() ;
        arrayCallMVF = new Array() ;
        arrayCPSMVT = new Array() ;
        arrayCPSMVF = new Array() ;
        arrayACDMVT = new Array() ;
        arrayACDMVF = new Array() ;
        arrayASRMVT = new Array() ;
        arrayASRMVF = new Array() ;
        arrayPDDMVT = new Array() ;
        arrayPDDMVF = new Array() ;

    	var i = 0 ;

    	while(i<datas.length){

//    		arrayCall[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];
//    		arrayASR[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];

    		arrayTime[i] = new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute) ;
    		arrayCallMVF[i] = datas[i].value ;
    		arrayCPSMVT[i] = datas[i].valuecps ;
    		arrayCPSMVF[i] = datas[i].values ;
    		arrayACDMVT[i] = datas[i].valueacd ;
    		arrayACDMVF[i] = datas[i].valuef ;
    		arrayASRMVT[i] = datas[i].valueasr ;
    		arrayASRMVF[i] = datas[i].valuet ;
    		arrayPDDMVT[i] = datas[i].valuepdd ;
    		arrayPDDMVF[i] = datas[i].pdd ;
    		  
    		if(i==datas.length-1){
        		
            	loadCharts() ;
            }
    		i++;		
    	}
	    }
	});
	}
  
  
  function loadCharts(){
	  
	    dataCall = new google.visualization.DataTable();
	    dataCall.addColumn('datetime', 'Date');
	    dataCall.addColumn('number', 'Call');
	    dataCall.addColumn('number', 'CPS');

	    dataCall.addRows(arrayTime.length);
	    
	    var volumnStarts = arrayTime[arrayTime.length-1].toUTCString().split(',');

	    chartCurrent = arrayTime[0] ;

	    $('#volumeStart').html(volumnStarts[1].substring(1,12));
	    $('#qualStart').html(volumnStarts[1].substring(1,12));

	    var volumnEnds = arrayTime[0].toUTCString().split(',');

	    $('#volumeEnd').html(volumnEnds[1].substring(1,12));
	    $('#qualEnd').html(volumnEnds[1].substring(1,12));

	    
	    for(var i=0;i<arrayTime.length;i++){
	    	dataCall.setValue(i,0,arrayTime[i]);
	    	dataCall.setValue(i,1,arrayCallMVF[i]);
	    	dataCall.setValue(i,2,arrayCPSMVT[i]);
	    }
	  
	    chartCall = new google.visualization.AnnotatedTimeLine(document.getElementById('coordinateID'));
	    chartCall.draw(dataCall, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727'],'min':0});
	   
	    
	    chartCall.setVisibleChartRange(new Date(arrayTime[0].getTime()-86400000), arrayTime[0]);
	    
	    arrayTime[0] = new Date(chartCurrent.getTime()-28800000);
	    	    
	    google.visualization.events.addListener(chartCall,'rangechange',function(){
	      	var callChange = chartCall.getVisibleChartRange();
	      	
	      	chartStartTime = callChange.start ;
	      	chartEndTime = callChange.end ;
	      	chartChange = 1 ;
	      	
	      	chartASR.setVisibleChartRange(callChange.start,callChange.end);
	      	$(".zoomnones").attr('class','zoomlines');
	          $(".zoomnone").attr('class','zoomline');
	      });
	    
	    dataASR = new google.visualization.DataTable();
	    dataASR.addColumn('datetime', 'Date');
	    dataASR.addColumn('number', 'ASR');
	    dataASR.addColumn('number', 'ACD');
	    
	    dataASR.addRows(arrayTime.length);
	    
	    for(var i=0;i<arrayTime.length;i++){
	    	dataASR.setValue(i,0,arrayTime[i]);
	    	dataASR.setValue(i,1,arrayASRMVT[i]);
	    	dataASR.setValue(i,2,arrayACDMVT[i]);
	    }
	    
	    chartCurrent = arrayTime[0] ;
	    

	    
	    chartASR = new google.visualization.AnnotatedTimeLine(document.getElementById('longerID'));
	    chartASR.draw(dataASR, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1],'scaleType': 'allmaximized','thickness': 2,'colors':['#4b9353','#e6b640'],'min':0});

	    chartASR.setVisibleChartRange(new Date(arrayTime[0].getTime()-86400000), arrayTime[0]);
	    
	    arrayTime[0] = new Date(chartCurrent.getTime()-28800000);

	    chartCurrent = arrayTime[0] ;
	    
	    chartStartTime = new Date(arrayTime[0].getTime()-86400000) ;
	    chartEndTime = arrayTime[0] ;
	    
	    google.visualization.events.addListener(chartASR,'rangechange',function(){
	      	var callChange = chartASR.getVisibleChartRange();
	      	
	      	chartStartTime = callChange.start ;
	      	chartEndTime = callChange.end ;
	      	chartChange = 1 ;
	      	
	      	chartCall.setVisibleChartRange(callChange.start,callChange.end);
	      	$(".zoomnones").attr('class','zoomlines');
	          $(".zoomnone").attr('class','zoomline');
	      });
  }


function loadChart(){
	
	dataCall = new google.visualization.DataTable();
    dataCall.addColumn('datetime', 'Date');
    dataCall.addColumn('number', 'Call');
    dataCall.addColumn('number', 'CPS');

    dataCall.addRows(arrayTime.length);
    
    var volumnStarts = arrayTime[arrayTime.length-1].toUTCString().split(',');

    chartCurrent = arrayTime[0] ;

    $('#volumeStart').html(volumnStarts[1].substring(1,12));
    $('#qualStart').html(volumnStarts[1].substring(1,12));

    var volumnEnds = arrayTime[0].toUTCString().split(',');

    $('#volumeEnd').html(volumnEnds[1].substring(1,12));
    $('#qualEnd').html(volumnEnds[1].substring(1,12));

    
    for(var i=0;i<arrayTime.length;i++){
    	dataCall.setValue(i,0,arrayTime[i]);
    	dataCall.setValue(i,1,arrayCallMVF[i]);
    	dataCall.setValue(i,2,arrayCPSMVT[i]);
    }
	
    chartCall = new google.visualization.AnnotatedTimeLine(document.getElementById('coordinateID'));
    chartCall.draw(dataCall, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727'],'min':0});

    chartCall.setVisibleChartRange(new Date(arrayTime[0].getTime()-86400000), arrayTime[0]);
    
    arrayTime[0] = new Date(chartCurrent.getTime()-28800000);

    
//  chartCall.hideDataColumns(2);
//  chartCall.hideDataColumns(3);
  
//  if(!document.getElementById("call1").checked){
//  	chartCall.hideDataColumns(0);
//  }
//  if(!document.getElementById("cps1").checked){
//  	chartCall.hideDataColumns(1);
//  }
//  if(!document.getElementById("asr1").checked){
//  	chartCall.hideDataColumns(2);
//  }
//  if(!document.getElementById("acd1").checked){
//  	chartCall.hideDataColumns(3);
//  }

  
  $("#chcall").attr("checked",true);
  $("#chcps").attr("checked",true);
  
  google.visualization.events.addListener(chartCall,'rangechange',function(){
  	var callChange = chartCall.getVisibleChartRange();
  	
  	chartStartTime = callChange.start ;
  	chartEndTime = callChange.end ;
  	
  	chartASR.setVisibleChartRange(callChange.start,callChange.end);
  	$(".zoomnones").attr('class','zoomlines');
      $(".zoomnone").attr('class','zoomline');
  });
  
  
  dataASR = new google.visualization.DataTable();
  dataASR.addColumn('datetime', 'Date');
  dataASR.addColumn('number', 'ASR');
  dataASR.addColumn('number', 'ACD');
  
  dataASR.addRows(arrayTime.length);
  
  for(var i=0;i<arrayTime.length;i++){
  	dataASR.setValue(i,0,arrayTime[i]);
  	dataASR.setValue(i,1,arrayASRMVT[i]);
  	dataASR.setValue(i,2,arrayACDMVT[i]);
  }
  
  chartCurrent = arrayTime[0] ;
  

  
  chartASR = new google.visualization.AnnotatedTimeLine(document.getElementById('longerID'));
  chartASR.draw(dataASR, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0],'scaleType': 'allmaximized','thickness': 2,'colors':['#4b9353','#e6b640'],'min':0});

  chartASR.setVisibleChartRange(new Date(arrayTime[0].getTime()-86400000), arrayTime[0]);
  
  arrayTime[0] = new Date(chartCurrent.getTime()-28800000);

  chartCurrent = arrayTime[0] ;

//  chartASR.hideDataColumns(0);
//  chartASR.hideDataColumns(1);
  
//  if(!document.getElementById("call2").checked){
//  	chartASR.hideDataColumns(0);
//  }
//  if(!document.getElementById("cps2").checked){
//  	chartASR.hideDataColumns(1);
//  }
//  if(!document.getElementById("asr2").checked){
//  	chartASR.hideDataColumns(2);
//  }
//  if(!document.getElementById("acd2").checked){
//  	chartASR.hideDataColumns(3);
//  }

  $("#chasr").attr("checked",true);
  $("#chacd").attr("checked",true);
  
  google.visualization.events.addListener(chartASR,'rangechange',function(){
  	var callChange = chartASR.getVisibleChartRange();
  	
  	chartStartTime = callChange.start ;
  	chartEndTime = callChange.end ;
  	
  	chartCall.setVisibleChartRange(callChange.start,callChange.end);
  	$(".zoomnones").attr('class','zoomlines');
      $(".zoomnone").attr('class','zoomline');
  });

}


function swapSMV(viewIndex,dataStyle,chartStyle){
	
	if(document.getElementById("cps"+viewIndex).checked){
		handleSMVForClick("cps_"+viewIndex,"CPS",arrayCPSMVF,arrayCPSMVT,dataStyle,chartStyle,viewIndex);		
	}
	
	if(document.getElementById("asr"+viewIndex).checked){
		handleSMVForClick("asr_"+viewIndex,"ASR",arrayASRMVF,arrayASRMVT,dataStyle,chartStyle,viewIndex);
	}
	
	if(document.getElementById("acd"+viewIndex).checked){
		handleSMVForClick("acd_"+viewIndex,"ACD",arrayACDMVF,arrayACDMVT,dataStyle,chartStyle,viewIndex);
	}

	var visiAttr = 'hidden' ;
	
	if($("#isSMV"+viewIndex).val()=='0'){
		visiAttr = 'visible' ;
		$("#isSMV"+viewIndex).attr('value','1');
	}
	else{
		$("#isSMV"+viewIndex).attr('value','0'); 	
	}
	
	$(".picSMV"+viewIndex).each(function(i){
		if(i==1){
		  $(this).css("visibility",visiAttr);	
		}
	});
	
}

function swapSMVs(viewIndex,dataStyle,chartStyle){
	
	var label = new Array();
	var array = new Array();
	var color = new Array();
	var column = new Array();
	var columnCount = 0 ;
	
	var objectDiv = "coordinateID";
	
	if(viewIndex==2){
		objectDiv = "longerID";
	}
	
	if(document.getElementById("call"+viewIndex).checked){
		label.push("Call");
		array.push(arrayCallMVF);
		color.push("#1670e0");
		column.push(columnCount);
		columnCount++ ;
	}
	
	if(document.getElementById("cps"+viewIndex).checked){		
		label.push("CPS");
		color.push("#fa4727");
		column.push(columnCount);
		columnCount++ ;
		var smvStat = $("#isSMV"+viewIndex).val();		
		if(smvStat==1){
			array.push(arrayCPSMVF);
		}
		else{
			array.push(arrayCPSMVT);
		}
	}
	
	if(document.getElementById("asr"+viewIndex).checked){
    	label.push("ASR");
		color.push("#4b9353");
		column.push(columnCount);
		columnCount++ ;
		var smvStat = $("#isSMV"+viewIndex).val();		
		if(smvStat==1){
			array.push(arrayASRMVF);
		}
		else{
			array.push(arrayASRMVT);
		}
	}
	
	if(document.getElementById("acd"+viewIndex).checked){
    	label.push("ACD");
		color.push("#e6b640");
		column.push(columnCount);
		columnCount++ ;
		var smvStat = $("#isSMV"+viewIndex).val();		
		if(smvStat==1){
			array.push(arrayACDMVF);
		}
		else{
			array.push(arrayACDMVT);
		}
	}
	
	if(document.getElementById("pdd"+viewIndex).checked){
    	label.push("PDD");
		color.push("#888888");
		column.push(columnCount);
		columnCount++ ;
		var smvStat = $("#isSMV"+viewIndex).val();		
		if(smvStat==1){
			array.push(arrayPDDMVF);
		}
		else{
			array.push(arrayPDDMVT);
		}
	}
	 
	rebuild(dataStyle,chartStyle,label,array,objectDiv,color,column) ;

	var visiAttr = 'hidden' ;
	
	if($("#isSMV"+viewIndex).val()=='0'){
		visiAttr = 'visible' ;
		$("#isSMV"+viewIndex).attr('value','1');
	}
	else{
		$("#isSMV"+viewIndex).attr('value','0'); 	
	}
	
	$(".picSMV"+viewIndex).each(function(i){
		if(i==1){
		  $(this).css("visibility",visiAttr);	
		}
	});
	
}


function rebuild(dataStyle,chartStyle,labels,arrays,targetDivID,colors,columns){
	
	dataStyle = new google.visualization.DataTable();
    dataStyle.addColumn('datetime', 'Date');
    
    for(var i=0;i<labels.length;i++){   	
    	dataStyle.addColumn('number', labels[i]);
    }

    dataStyle.addRows(arrayTime.length);
    
    for(var i=0;i<arrayTime.length;i++){
    	dataStyle.setValue(i,0,arrayTime[i]);
    	
    	for(var j=0;j<labels.length;j++){
    		dataStyle.setValue(i,j+1,arrays[j][i]);
    	}
    	
    }
  
//    alert(arrayTime[0]);
    
    chartStyle.draw(dataStyle, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': columns,'scaleType': 'allmaximized','thickness': 2,'colors':colors,'min':0});

    if(chartChange==1){
    	chartStartTime = new Date(chartStartTime.getTime()-28800000) ;
        chartEndTime = new Date(chartEndTime.getTime()-28800000);
    }
    else{
    	chartChange = 1 ;
    }
    
    chartStyle.setVisibleChartRange(chartStartTime, chartEndTime);
    
    if(arrayTime[0].getTime()-arrayTime[1].getTime()>180000){
    	arrayTime[0] = new Date(chartCurrent.getTime()-28800000);
    }
    	    
    google.visualization.events.addListener(chartStyle,'rangechange',function(){
      	var callChange = chartStyle.getVisibleChartRange();
      	
      	chartStartTime = new Date(callChange.start.getTime()-28800000);
      	chartEndTime = new Date(callChange.end.getTime()-28800000) ;
      	
      	if(chartStyle==chartCall){
      		chartASR.setVisibleChartRange(chartStartTime,chartEndTime);         	
      	}
      	else{
      		chartCall.setVisibleChartRange(chartStartTime,chartEndTime);
      	}
      	

      	
	
      	$(".zoomnones").attr('class','zoomlines');
        $(".zoomnone").attr('class','zoomline');
      });
	
    

}



function chartAjax(timeType,objectType,objectId,direct){
	chartState = timeType ;
	$.ajax({
		url:'genjson.html?cmd=s&tmt='+timeType+'&obt='+objectType+'&obd='+objectId+'&rct='+direct,
		type:'GET',
		dataType:'json',
		success:function(text){
		var data = eval(text);
    	var datas = data.text ;
    	
    	arrayCall = new Array();
    	arrayASR = new Array();

    	var i = 0 ;

     	
    	while(i<datas.length){

    		arrayCall[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef,datas[i].pdd];
    		arrayASR[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef,datas[i].pdd];

    		
    		  
    		if(i==datas.length-1){
        		
            	drawChart(arrayCall);
            	drawChartD(arrayASR);
            }
    		i++;		
    	}
	    }
	});
	
}     

function chartAjaxs(timeType,objectType,objectId,direct){
	chartState = timeType ;
	$.ajax({
		url:'genjson.html?cmd=s&tmt='+timeType+'&obt='+objectType+'&obd='+objectId+'&rct='+direct,
		type:'GET',
		dataType:'json',
		success:function(text){
		var data = eval(text);
    	var datas = data.text ;
    	
    	arrayCall = new Array();
    	arrayASR = new Array();

    	var i = 0 ;

     	
    	while(i<datas.length){

    		arrayCall[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];
    		arrayASR[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];

    		
    		  
    		if(i==datas.length-1){
        		
    			var count = 672 ;
    			
    			if(chartState==1){
    				count = 240 ;
    			}
    			
    			
    			dataCall.removeRows(0,count);
    		    dataCall.addRows(arrayCall);
    		    
    		    chartCall.draw(dataCall, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});
    		    
//    		    data = new google.visualization.DataTable();
//    		    data.addColumn('datetime', 'Date');
//    		    data.addColumn('number', 'CALL');
//    		    data.addColumn('number', 'CPS');
//    		    data.addColumn('number', 'ASR');
//    		    data.addColumn('number', 'ACD');
    		    
    		    dataASR.removeRows(0,count);
    		    dataASR.addRows(arrayASR);
    		    
    		    chartASR.draw(dataASR, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});

            }
    		i++;		
    	}
	    }
	});
	
}

function makeChart(timeType,objectType,objectId,direct){
	
	if(initTime>12){
		$("#waitTime").hide();
		$("#coordinateID").css("visibility",'visible');
		$("#longerID").css("visibility",'visible');
		return ;
	}
	else {
		
		initTime++;
		$("#waitTime").show();
		$("#coordinateID").css("visibility",'hidden');
		$("#longerID").css("visibility",'hidden');
	}
	$.ajax({

    	url:'genjson.html?cmd=l&tmt='+timeType+'&obt='+objectType+'&obd='+objectId+'&rct='+direct+'&initTime='+initTime,
		type:'GET',
		dataType:'json',
		success:function(text){
    	var data = eval(text);
    	var datas = data.text ;
    	
    	arrayCall = new Array();
    	arrayASR = new Array();

    	var i = 0 ;

     	
    	while(i<datas.length){

    		arrayCall[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];
    		arrayASR[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];

    		
    		  
    		if(i==datas.length-1){
        		
    			if(initTime==1){
        			var data = new google.visualization.DataTable();
        		    data.addColumn('datetime', 'Date');
        		    data.addColumn('number', 'CALL');
        		    data.addColumn('number', 'CPS');
        		    data.addColumn('number', 'ASR');
        		    data.addColumn('number', 'ACD');

        		    data.addRows(arrayCall);

        		    chartCurrent = arrayCall[0][0];



        		    var volumnStarts = arrayCall[arrayCall.length-1][0].toUTCString().split(',');


        		    $('#volumeStart').html(volumnStarts[1].substring(1,12));
        		    $('#qualStart').html(volumnStarts[1].substring(1,12));

        		    var volumnEnds = arrayCall[0][0].toUTCString().split(',');

        		    $('#volumeEnd').html(volumnEnds[1].substring(1,12));
        		    $('#qualEnd').html(volumnEnds[1].substring(1,12));
        		    
        		    chartCall = new google.visualization.AnnotatedTimeLine(document.getElementById('coordinateID'));
        		    chartCall.draw(data, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0, 1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});

        		    
        		    
//        		    chartCall.setVisibleChartRange(new Date(chartCurrent.getTime()-10800000), chartCurrent);
        		    chartCall.hideDataColumns(2);
        		    chartCall.hideDataColumns(3);
        		    
//        		    if(!document.getElementById("call1").checked){
//        		    	chartCall.hideDataColumns(0);
//        		    }
//        		    if(!document.getElementById("cps1").checked){
//        		    	chartCall.hideDataColumns(1);
//        		    }
//        		    if(!document.getElementById("asr1").checked){
//        		    	chartCall.hideDataColumns(2);
//        		    }
//        		    if(!document.getElementById("acd1").checked){
//        		    	chartCall.hideDataColumns(3);
//        		    }
//
//        		    
        		    $("#chcall").attr("checked",true);
        		    $("#chcps").attr("checked",true);
//        		    
        		    google.visualization.events.addListener(chartCall,'rangechange',function(){
        		    	var callChange = chartCall.getVisibleChartRange();
        		    	chartASR.setVisibleChartRange(callChange.start,callChange.end);
        		    	$(".zoomnones").attr('class','zoomlines');
        		        $(".zoomnone").attr('class','zoomline');
        		    });

//       		    arrayCall[0][0] = new Date(chartCurrent.getTime()-28800000);
        			
        			
        		    data = new google.visualization.DataTable();
        		    data.addColumn('datetime', 'Date');
        		    data.addColumn('number', 'CALL');
        		    data.addColumn('number', 'CPS');
        		    data.addColumn('number', 'ASR');
        		    data.addColumn('number', 'ACD');
        		    

        		    data.addRows(arrayASR);
      
        		    chartASR = new google.visualization.AnnotatedTimeLine(document.getElementById('longerID'));
        		    chartASR.draw(data, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});

//        		    var chartCurrents = arrayASR[0][0];
//        		    
//        		    chartASR.setVisibleChartRange(new Date(chartCurrents.getTime()-10800000), chartCurrents);
        		    chartASR.hideDataColumns(0);
        		    chartASR.hideDataColumns(1);
        		    
//        		    if(!document.getElementById("call2").checked){
//        		    	chartASR.hideDataColumns(0);
//        		    }
//        		    if(!document.getElementById("cps2").checked){
//        		    	chartASR.hideDataColumns(1);
//        		    }
//        		    if(!document.getElementById("asr2").checked){
//        		    	chartASR.hideDataColumns(2);
//        		    }
//        		    if(!document.getElementById("acd2").checked){
//        		    	chartASR.hideDataColumns(3);
//        		    }

        		    $("#chasr").attr("checked",true);
        		    $("#chacd").attr("checked",true);
        		    
        		    google.visualization.events.addListener(chartASR,'rangechange',function(){
        		    	var callChange = chartASR.getVisibleChartRange();
        		    	chartCall.setVisibleChartRange(callChange.start,callChange.end);
        		    	$(".zoomnones").attr('class','zoomlines');
        		        $(".zoomnone").attr('class','zoomline');
        		    });	
    			}
    			else{
    				
    				var data = new google.visualization.DataTable();
        		    data.addColumn('datetime', 'Date');
        		    data.addColumn('number', 'CALL');
        		    data.addColumn('number', 'CPS');
        		    data.addColumn('number', 'ASR');
        		    data.addColumn('number', 'ACD');

        		    data.addRows(arrayCall);
        		    
        		    chartCall.draw(data, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});
        		    
        		    data = new google.visualization.DataTable();
        		    data.addColumn('datetime', 'Date');
        		    data.addColumn('number', 'Call');
        		    data.addColumn('number', 'CPS');
        		    data.addColumn('number', 'ASR');
        		    data.addColumn('number', 'ACD');
        		    
        		    data.addRows(arrayCall);
        		    
        		    chartASR.draw(data, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0,1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});
    			}

//    		    arrayASR[0][0] = new Date(chartCurrents.getTime()-28800000);
    		 
    		    
//            	drawChartD(arrayASR);
            }
    		i++;		
    	}
    	
    	makeChart(timeType,objectType,objectId,direct);
    	
        }
        });
}






function ajaxControl(timeType,objectType,objectId,direct){

	$("#waitTime").show();

	$.ajax({

    	url:'genjson.html?cmd=l&tmt='+timeType+'&obt='+objectType+'&obd='+objectId+'&rct='+direct,
		type:'GET',
		dataType:'json',
		success:function(text){
    	var data = eval(text);
    	var datas = data.text ;
    	
    	arrayCall = new Array();
    	arrayASR = new Array();

    	var i = 0 ;

     	
    	while(i<datas.length){

    		arrayCall[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];
    		arrayASR[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];

    		
    		  
    		if(i==datas.length-1){
        		
            	drawChart(arrayCall);
            	drawChartD(arrayASR);
            }
    		i++;		
    	}
        }
        });


//	$.ajax({
//
//    	url:'genjson.html?cmd=d&tmt='+timeType+'&obt='+objectType+'&obd='+objectId+'&rct='+direct,
//   		type:'GET',
//   		dataType:'json',
// 		success:function(text){
//    	var data = eval(text);
//    	var datas = data.text ;
//    	
//    	arrayCallTwo = new Array();
//    	arrayASRTwo = new Array();
//   	
//
//      	var i = 0 ;
//    	
//    	while(i<datas.length){
//
//    		arrayCallTwo[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];
//    		arrayASRTwo[i] = [new Date(datas[i].year,datas[i].month,datas[i].day,datas[i].hour,datas[i].minute),datas[i].value,datas[i].values,datas[i].valuet,datas[i].valuef];
//
//    		
    		
   // 		if(i==datas.length-1){
   // 			drawChart(arrayCallTwo);
 //           	drawChartD(arrayASRTwo);
//            }
//    		i++;		
//      	}
//          }
//           });
}

function explor(isGolbal){
    if($("#isspand").val()=='0'){
     $("#leftChart").css('width','96%');
     $("#leftChart").css('float','none');
     var bar = $('#leftControlBar').html();
     var timeBar = $('#leftTimeBar').html();
     $('#leftControlBar').html(bar+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+timeBar);
     $('#leftTimeBar').hide();
//        drawChart(arrayCall);        
        $("#rightChart").css('width','96%'); 
        $("#rightChart").css('float','none');
        var bar = $('#rightControlBar').html();
        var timeBar = $('#rightTimeBar').html();
        $('#rightControlBar').html(bar+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+timeBar);
        $('#rightTimeBar').hide();
//
//        drawChartD(arrayASR);	
        
        
        if(isGolbal==1){
        	loadCharts();
        }
        else{
        	drawChart(arrayCall);
        	drawChartD(arrayASR);
        }
        
        $("#isspand").val('1') ;
    }
    
   
    
} 

function unexplor(isGolbal){
    if($("#isspand").val()=='1'){
        var styleCss = $("#leftChart").attr("style")+'float: left;';
        $("#leftChart").css('width','48%');
        $("#leftChart").css('float','left');
        var bar = $('#leftControlBar').html();
        var size = bar.lastIndexOf('&nbsp;');
        bar = bar.substring(0,size); 
        
        $('#leftControlBar').html(bar);
        $('#leftTimeBar').show();

//        drawChart(arrayCall);        
        $("#monitor_global_styles").attr("style","margin-top: 30px;");
        $("#rightChart").css('width','48%');
        $("#rightChart").css('float','left');  
        var bar = $('#rightControlBar').html(); 
        var size = bar.lastIndexOf('&nbsp;');
        bar = bar.substring(0,size);
        $('#rightControlBar').html(bar);
        $('#rightTimeBar').show();

        if(isGolbal==1){
        	loadCharts();
        }
        else{
        	drawChart(arrayCall);
        	drawChartD(arrayASR);
        }
        
        $("#isspand").val('0') ;
    }
    
}

function click(index,com){

    if(com.className=='zoomline'){

        var count ;

        switch(index){

        case 1 :count=3600000;break;
        case 2 :count=10800000;break;
        case 3 :count=86400000;break;
        case 4 :count=259200000;break;
        case 5 :count=604800000;break;
        
        }
        
        


        chartCall.setVisibleChartRange(new Date(arrayTime[0].getTime()-count),arrayTime[0]);


        if(arrayTime[0].getTime()-arrayTime[1].getTime()>180000){
     	   arrayTime[0] = new Date(arrayTime[0].getTime()-28800000);
    	   chartCurrent = arrayTime[0] ;  
        }
        

        chartASR.setVisibleChartRange(new Date(arrayTime[0].getTime()-count),arrayTime[0]);


        if(arrayTime[0].getTime()-arrayTime[1].getTime()>180000){
      	   arrayTime[0] = new Date(arrayTime[0].getTime()-28800000);
     	   chartCurrent = arrayTime[0] ;  
         }

        
        chartStartTime=new Date(arrayTime[0].getTime()-count);       
        chartEndTime=arrayTime[0];
        
        chartChange = 0 ;

        $(".zoomnone").attr('class','zoomline');
        $(".zoomnones").attr('class','zoomlines');
        com.className = 'zoomnone';
    }
}

function clicks(index,com){

    if(com.className=='zoomlines'){

        var count ;

        switch(index){

        case 1 :count=3600000;break;
        case 2 :count=10800000;break;
        case 3 :count=86400000;break;
        case 4 :count=259200000;break;
        case 5 :count=604800000;break;
        
        }

        chartCall.setVisibleChartRange(new Date(arrayTime[0].getTime()-count),arrayTime[0]);


        if(arrayTime[0].getTime()-arrayTime[1].getTime()>180000){
     	   arrayTime[0] = new Date(arrayTime[0].getTime()-28800000);
    	   chartCurrent = arrayTime[0] ;  
        }
        

        chartASR.setVisibleChartRange(new Date(arrayTime[0].getTime()-count),arrayTime[0]);


        if(arrayTime[0].getTime()-arrayTime[1].getTime()>180000){
      	   arrayTime[0] = new Date(arrayTime[0].getTime()-28800000);
     	   chartCurrent = arrayTime[0] ;  
         }
        

        chartStartTime=new Date(arrayTime[0].getTime()-count);       
        chartEndTime=arrayTime[0];
        
        chartChange = 0 ;
        
        $(".zoomnones").attr('class','zoomlines');
        $(".zoomnone").attr('class','zoomline');
        com.className = 'zoomnones';
    }
}

function showOrhide(index,com){

    if(index==0){
        
        if(com.checked){
            chartCall.showDataColumns(0);
            if(!document.getElementById('chcps').checked){
            	var labelzoom = $(".zoomnone").html();

                var count ;

                if(labelzoom=='Last 24 hours'){
                    count = 86400000;
                }else if(labelzoom=='Last hour'){
                	count=3600000;
                }else if(labelzoom=='Last 3 hours'){
                	count=10800000;
                }else if(labelzoom=='Last 3 days'){
                	count=259200000;
                }else if(labelzoom=='Last 7 days'){
                	count=604800000;
                }

                
                chartCall.setVisibleChartRange(new Date(arrayCall[0][0].getTime()-count), arrayCall[0][0]);
            }
        }
        else{
        	chartCall.hideDataColumns(0);
        }
    }
    else if(index==1){
        
        if(com.checked){
            chartCall.showDataColumns(1);
            if(!document.getElementById('chcall').checked){
            	var labelzoom = $(".zoomnone").html();

                var count ;

                if(labelzoom=='Last 24 hours'){
                    count = 86400000;
                }else if(labelzoom=='Last hour'){
                	count=3600000;
                }else if(labelzoom=='Last 3 hours'){
                	count=10800000;
                }else if(labelzoom=='Last 3 days'){
                	count=259200000;
                }else if(labelzoom=='Last 7 days'){
                	count=604800000;
                }
         
                chartCall.setVisibleChartRange(new Date(arrayCall[0][0].getTime()-count), arrayCall[0][0]);
            }
            
        }
        else{
        	chartCall.hideDataColumns(1);
        }
    }
    else if(index==2){
        
        if(com.checked){
        	chartASR.showDataColumns(0);

        	if(!document.getElementById('chacd').checked){
            	var labelzoom = $(".zoomnones").html();

                var count ;

                if(labelzoom=='Last 24 hours'){
                    count = 86400000;
                }else if(labelzoom=='Last hour'){
                	count=3600000;
                }else if(labelzoom=='Last 3 hours'){
                	count=10800000;
                }else if(labelzoom=='Last 3 days'){
                	count=259200000;
                }else if(labelzoom=='Last 7 days'){
                	count=604800000;
                }
            
                chartASR.setVisibleChartRange(new Date(arrayASR[0][0].getTime()-count), arrayASR[0][0]);
            }
        }
        else{
        	chartASR.hideDataColumns(0);
        }
    }
    else{
        
        if(com.checked){
        	chartASR.showDataColumns(1);

        	if(!document.getElementById('chasr').checked){
            	var labelzoom = $(".zoomnones").html();

                var count ;

                if(labelzoom=='Last 24 hours'){
                    count = 86400000;
                }else if(labelzoom=='Last hour'){
                	count=3600000;
                }else if(labelzoom=='Last 3 hours'){
                	count=10800000;
                }else if(labelzoom=='Last 3 days'){
                	count=259200000;
                }else if(labelzoom=='Last 7 days'){
                	count=604800000;
                }
              
                chartASR.setVisibleChartRange(new Date(arrayASR[0][0].getTime()-count), arrayASR[0][0]);
            }
        	
        }
        else{
        	chartASR.hideDataColumns(1);
        }
    }
}




function callAddColumn(title,arrayValue,indexColumn,dataStyle,chartStyle){

	dataStyle.setValue(0,0,arrayTime[0]);
	
	dataStyle.addColumn('number', title);
	for(var i=0;i<arrayValue.length;i++){
		dataStyle.setValue(i,parseInt(indexColumn)+1,arrayValue[i]);
	}	
	
	if(chartStyle==chartCall){
		chartStyle.draw(dataStyle, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727'],'min':0});
	}
	else{
		chartStyle.draw(dataStyle, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0],'scaleType': 'allmaximized','thickness': 2,'colors':['#4b9353','#e6b640'],'min':0});
	}
	
	chartStyle.showDataColumns(parseInt(indexColumn));
}


function handleSMVForClick(viewId,viewStyle,smvF,smvT,dataStyle,chartStyle,viewIndex){
	
	var smvStat = $("#isSMV"+viewIndex).val();
	var columnIndex ;
	if(smvStat==1){		
		chartStyle.hideDataColumns($("#"+viewId+"_m").val());		
		columnIndex = $("#"+viewId).val();
    	if(columnIndex!=1000){
    		chartStyle.showDataColumns(columnIndex);
    	}
    	else{
    		callAddColumn(viewStyle,smvF,$("#currentColumn"+viewIndex).val(),dataStyle,chartStyle);   		
    		$("#"+viewId).val($("#currentColumn"+viewIndex).val());
    		$("#currentColumn"+viewIndex).attr('value',parseInt($("#currentColumn"+viewIndex).val())+1);
    	}
	}
	else{		
		chartStyle.hideDataColumns($("#"+viewId).val());
		columnIndex = $("#"+viewId+"_m").val();
    	if(columnIndex!=1000){
    		chartStyle.showDataColumns(columnIndex);
    	}
    	else{
    		callAddColumn(viewStyle,smvT,$("#currentColumn"+viewIndex).val(),dataStyle,chartStyle);           		
    		$("#"+viewId+"_m").val($("#currentColumn"+viewIndex).val());
    		$("#currentColumn"+viewIndex).attr('value',parseInt($("#currentColumn"+viewIndex).val())+1);
    	}
	}
	
}


function handleSMVForDisplay(viewId,viewStyle,smvT,smvF,dataStyle,chartStyle,viewIndex){
	var smvStat = $("#isSMV"+viewIndex).val();
	var columnIndex ;
	if(smvStat==1){
		columnIndex = $("#"+viewId+"_m").val();
    	if(columnIndex!=1000){
    		chartStyle.showDataColumns(columnIndex);
    	}
    	else{
    		callAddColumn(viewStyle,smvT,$("#currentColumn"+viewIndex).val(),dataStyle,chartStyle);           		
    		$("#"+viewId+"_m").val($("#currentColumn"+viewIndex).val());
    		$("#currentColumn"+viewIndex).attr('value',parseInt($("#currentColumn"+viewIndex).val())+1);
    	}
	}
	else{
		columnIndex = $("#"+viewId).val();
    	if(columnIndex!=1000){
    		chartStyle.showDataColumns(columnIndex);
    	}
    	else{
    		callAddColumn(viewStyle,smvF,$("#currentColumn"+viewIndex).val(),dataStyle,chartStyle);    		
    		$("#"+viewId).val($("#currentColumn"+viewIndex).val());
    		$("#currentColumn"+viewIndex).attr('value',parseInt($("#currentColumn"+viewIndex).val())+1);
    	}
	}
}


function handleSMVForNone(viewId,chartStyle,viewIndex){
	var smvStat = $("#isSMV"+viewIndex).val();
	var columnIndex ;
	if(smvStat==1){
		columnIndex = $("#"+viewId+"_m").val();
    	if(columnIndex!=1000){
    		chartStyle.hideDataColumns(columnIndex);
    	}
	}
	else{
		columnIndex = $("#"+viewId).val();
    	if(columnIndex!=1000){
    		chartStyle.hideDataColumns(columnIndex);
    	}
	}
}

function changeDisplay(viewIndex,dataStyle,chartStyle){
	
	var label = new Array();
	var array = new Array();
	var color = new Array();
	var column = new Array();
	var columnCount = 0 ;
	
	var objectDiv = "coordinateID";
	
	if(viewIndex==2){
		objectDiv = "longerID";
	}
	
	if(document.getElementById("call"+viewIndex).checked){
		label.push("Call");
		array.push(arrayCallMVF);
		color.push("#1670e0");
		column.push(columnCount);
		columnCount++ ;
	}
	
	if(document.getElementById("cps"+viewIndex).checked){		
		label.push("CPS");
		color.push("#fa4727");
		column.push(columnCount);
		columnCount++ ;
		var smvStat = $("#isSMV"+viewIndex).val();		
		if(smvStat==0){
			array.push(arrayCPSMVF);
		}
		else{
			array.push(arrayCPSMVT);
		}
	}
	
	if(document.getElementById("asr"+viewIndex).checked){
    	label.push("ASR");
		color.push("#4b9353");
		column.push(columnCount);
		columnCount++ ;
		var smvStat = $("#isSMV"+viewIndex).val();		
		if(smvStat==0){
			array.push(arrayASRMVF);
		}
		else{
			array.push(arrayASRMVT);
		}
	}
	
	if(document.getElementById("acd"+viewIndex).checked){
    	label.push("ACD");
		color.push("#e6b640");
		column.push(columnCount);
		columnCount++ ;
		var smvStat = $("#isSMV"+viewIndex).val();		
		if(smvStat==0){
			array.push(arrayACDMVF);
		}
		else{
			array.push(arrayACDMVT);
		}
	}
	
	if(document.getElementById("pdd"+viewIndex).checked){
    	label.push("PDD");
		color.push("#888888");
		column.push(columnCount);
		columnCount++ ;
		var smvStat = $("#isSMV"+viewIndex).val();		
		if(smvStat==0){
			array.push(arrayPDDMVF);
		}
		else{
			array.push(arrayPDDMVT);
		}
	}
	 
	if(column.length>0){
		rebuild(dataStyle,chartStyle,label,array,objectDiv,color,column) ;	
	}
	else{
		chartStyle.hideDataColumns(0);
	}
}


function showandhideMV(index,com){
	var target = com.id ;
    if(com.checked){    
        
    	changeDisplay(1,dataCall,chartCall);      
         
        var currentIndex = $("#currentView1").val();

        
        if(currentIndex.length>0){
            currentIndex = currentIndex+','+com.id ;
            $("#currentView1").val(currentIndex);
            if(currentIndex.indexOf('call1')==-1){
                $("#call1").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('cps1')==-1){
                $("#cps1").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('asr1')==-1){
                $("#asr1").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('acd1')==-1){
                $("#acd1").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('pdd1')==-1){
                $("#pdd1").attr("disabled","disabled");
            }
        }
        else{
        	$("#currentView1").val(com.id);
    	
            	var labelzoom = $(".zoomnone").html();

                var count ;
                

                if(labelzoom=='Last 24 hours'){
                    count = 86400000;
                }else if(labelzoom=='Last hour'){
                	count=3600000;
                }else if(labelzoom=='Last 3 hours'){
                	count=10800000;
                }else if(labelzoom=='Last 3 days'){
                	count=259200000;
                }else if(labelzoom=='Last 7 days'){
                	count=604800000;
                }
                
                
                if(count!=null){
                	chartCall.setVisibleChartRange(new Date(chartCurrent.getTime()-count), chartCurrent);
                }
                else {
                  	chartCall.setVisibleChartRange(chartStartTime,chartEndTime);
                }
            
        }
    }
    else{
 	
    	changeDisplay(1,dataCall,chartCall); 
	
    	var currentIndex = $("#currentView1").val();
    	if(currentIndex.indexOf(',')>=0){
        	currentIndex = currentIndex.replace(',','');
        	$("#call1").removeAttr("disabled");
        	$("#cps1").removeAttr("disabled");
        	$("#asr1").removeAttr("disabled");
        	$("#acd1").removeAttr("disabled");
        	$("#pdd1").removeAttr("disabled");
    	}
    	currentIndex = currentIndex.replace(target,'');
    	$("#currentView1").val(currentIndex);
    }
}



function showandhidesMV(index,com){
	var target = com.id ;
    if(com.checked){
        
    	changeDisplay(2,dataASR,chartASR);
            
        var currentIndex = $("#currentView2").val();
        if(currentIndex.length>0){
            currentIndex = currentIndex+','+com.id ;
            $("#currentView2").val(currentIndex);
            if(currentIndex.indexOf('call2')==-1){
                $("#call2").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('cps2')==-1){
                $("#cps2").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('asr2')==-1){
                $("#asr2").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('acd2')==-1){
                $("#acd2").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('pdd2')==-1){
                $("#pdd2").attr("disabled","disabled");
            }
        }
        else{
        	$("#currentView2").val(com.id);

        	var labelzoom = $(".zoomnones").html();

            var count ;

            if(labelzoom=='Last 24 hours'){
                count = 86400000;
            }else if(labelzoom=='Last hour'){
            	count=3600000;
            }else if(labelzoom=='Last 3 hours'){
            	count=10800000;
            }else if(labelzoom=='Last 3 days'){
            	count=259200000;
            }else if(labelzoom=='Last 7 days'){
            	count=604800000;
            }

            chartASR.setVisibleChartRange(new Date(chartCurrent.getTime()-count), chartCurrent);
        }
    }
    else{

    	changeDisplay(2,dataASR,chartASR);
    	
    	var currentIndex = $("#currentView2").val();
    	if(currentIndex.indexOf(',')>=0){
        	currentIndex = currentIndex.replace(',','');
        	$("#call2").removeAttr("disabled");
        	$("#cps2").removeAttr("disabled");
        	$("#asr2").removeAttr("disabled");
        	$("#acd2").removeAttr("disabled");
        	$("#pdd2").removeAttr("disabled");
    	}
    	currentIndex = currentIndex.replace(com.id,'');
    	$("#currentView2").val(currentIndex);
    }
}


function displaySMV(com){
	
	var displaySMV = com.checked ;
	
	if(displaySMV){
		
		var slicp = $("#callMAVText").attr('value');
		var mvStyle = $("#callMAVSelect").val();
		
		$("#callMAVText").attr("disabled","true");		
		$("#callMAVSelect").attr("disabled","true");
		
//		alert(slicp);
//		alert(mvStyle);
		
		$.ajax({
			url:"genjson.html?cmd=i&smvStyle="+mvStyle+"&slicpStyle="+slicp+"&indexStyle="+indexTable,
			type:'GET',
			dataType:'json',
			success:function(text){
			
			indexTable++;
			
			var data = eval(text);
			var array = data.text ;
			
			dataCall.setValue(0,0,arrayCall[0][0]);
			
			dataCall.addColumn('number', 'SMV');
			for(var i=0;i<array.length;i++){
				dataCall.setValue(i,5,array[i]);
			}
			
			cd = cd+1;
			
			chartCall.draw(dataCall, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0, 1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});
			
			chartCall.showDataColumns(4);
			
		},
		    error:function(){

			indexTable++;
			
			dataCall.setValue(0,0,arrayCall[0][0]);
			
			dataCall.addColumn('number', 'SMV');
			for(var i=0;i<100;i++){
				dataCall.setValue(i+1,5,i+1);
			}
			
			cd = cd+1;
			
			chartCall.draw(dataCall, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0, 1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});
			
			chartCall.showDataColumns(4);
					
		}
		});
		
		
	}
	else{
		
		$("#callMAVText").removeAttr("disabled");
		$("#callMAVSelect").removeAttr("disabled");
		
		chartCall.hideDataColumns(4);		
		dataCall.removeColumn(5);
		}
}


function showandhide(index,com){

    if(com.checked){
        chartCall.showDataColumns(index);
        var currentIndex = $("#currentView1").val();
        if(currentIndex.length>0){
            currentIndex = currentIndex+','+com.id ;
            $("#currentView1").val(currentIndex);
            if(currentIndex.indexOf('call1')==-1){
                $("#call1").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('cps1')==-1){
                $("#cps1").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('asr1')==-1){
                $("#asr1").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('acd1')==-1){
                $("#acd1").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('pdd1')==-1){
                $("#pdd1").attr("disabled","disabled");
            }
        }
        else{
        	$("#currentView1").val(com.id);

        	
            	var labelzoom = $(".zoomnone").html();

                

                var count ;


                if(labelzoom=='Last 24 hours'){
                    count = 86400000;
                }else if(labelzoom=='Last hour'){
                	count=3600000;
                }else if(labelzoom=='Last 3 hours'){
                	count=10800000;
                }else if(labelzoom=='Last 3 days'){
                	count=259200000;
                }else if(labelzoom=='Last 7 days'){
                	count=604800000;
                }

                
                chartCall.setVisibleChartRange(new Date(arrayCall[0][0].getTime()-count), arrayCall[0][0]);
            
        }
    }
    else{
    	chartCall.hideDataColumns(index);
    	var currentIndex = $("#currentView1").val();
    	if(currentIndex.indexOf(',')>=0){
        	currentIndex = currentIndex.replace(',','');
        	$("#call1").removeAttr("disabled");
        	$("#cps1").removeAttr("disabled");
        	$("#asr1").removeAttr("disabled");
        	$("#acd1").removeAttr("disabled");
        	$("#pdd1").removeAttr("disabled");
    	}
    	currentIndex = currentIndex.replace(com.id,'');
    	$("#currentView1").val(currentIndex);
    }
}



function showandhides(index,com){

    if(com.checked){
        chartASR.showDataColumns(index);
        var currentIndex = $("#currentView2").val();
        if(currentIndex.length>0){
            currentIndex = currentIndex+','+com.id ;
            $("#currentView2").val(currentIndex);
            if(currentIndex.indexOf('call2')==-1){
                $("#call2").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('cps2')==-1){
                $("#cps2").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('asr2')==-1){
                $("#asr2").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('acd2')==-1){
                $("#acd2").attr("disabled","disabled");
            }
            if(currentIndex.indexOf('pdd2')==-1){
                $("#pdd2").attr("disabled","disabled");
            }
        }
        else{
        	$("#currentView2").val(com.id);

        	var labelzoom = $(".zoomnones").html();

            

            var count ;


            if(labelzoom=='Last 24 hours'){
                count = 86400000;
            }else if(labelzoom=='Last hour'){
            	count=3600000;
            }else if(labelzoom=='Last 3 hours'){
            	count=10800000;
            }else if(labelzoom=='Last 3 days'){
            	count=259200000;
            }else if(labelzoom=='Last 7 days'){
            	count=604800000;
            }

            
            chartASR.setVisibleChartRange(new Date(arrayASR[0][0].getTime()-count), arrayASR[0][0]);
        }
    }
    else{
    	chartASR.hideDataColumns(index);
    	var currentIndex = $("#currentView2").val();
    	if(currentIndex.indexOf(',')>=0){
        	currentIndex = currentIndex.replace(',','');
        	$("#call2").removeAttr("disabled");
        	$("#cps2").removeAttr("disabled");
        	$("#asr2").removeAttr("disabled");
        	$("#acd2").removeAttr("disabled");
        	$("#pdd2").removeAttr("disabled");
    	}
    	currentIndex = currentIndex.replace(com.id,'');
    	$("#currentView2").val(currentIndex);
    }
}

function clickt(index,com){

    if(com.className=='zoomline'){

        var count ;

        switch(index){

        case 1 :count=3600000;break;
        case 2 :count=10800000;break;
        case 3 :count=86400000;break;
        case 4 :count=259200000;break;
        case 5 :count=604800000;break;
        
        }

       

        chartCall.setVisibleChartRange(new Date(chartCurrent.getTime()-count),chartCurrent);
        chartASR.setVisibleChartRange(new Date(chartCurrent.getTime()-count),chartCurrent);
        $(".zoomnone").attr('class','zoomline');
        $(".zoomnones").attr('class','zoomlines');
        com.className = 'zoomnone';
    }
}

function clickts(index,com){

    if(com.className=='zoomlines'){

        var count ;

        switch(index){

        case 1 :count=3600000;break;
        case 2 :count=10800000;break;
        case 3 :count=86400000;break;
        case 4 :count=259200000;break;
        case 5 :count=604800000;break;
        
        }

        

        chartASR.setVisibleChartRange(new Date(chartCurrent.getTime()-count),chartCurrent);
        chartCall.setVisibleChartRange(new Date(chartCurrent.getTime()-count),chartCurrent);
        $(".zoomnones").attr('class','zoomlines');
        $(".zoomnone").attr('class','zoomline');
        com.className = 'zoomnones';
    }
}