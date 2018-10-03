/**
 * 
 * 下载异常话单
 * 
 * 
 * type
 * '0：找不到账户
1：找不到费率模板
2：找不到费率';
 */
function  export_mismatch_cdr(){
	var start_date = $("#query-start_date-wDt").val();
	var start_time = $("#query-start_time-wDt").val();
	var stop_date = $("#query-stop_date-wDt").val();
	var stop_time = $("#query-stop_time-wDt").val();
	$('account_start_date').attr('value',$("#query-start_date-wDt").val());
	$('account_start_time').attr('value',$("#query-start_time-wDtt").val());
	$('account_stop_date').attr('value',$("#query-stop_date-wDt").val());
	$('account_stop_time').attr('value',$("#query-stop_time-wDt").val());
	return true;
} 
/**
 * 创建client的transclation
 * @param root
 * @param resourceId
 * @param count
 * @return
 */
function createClientTable(root,resourceId,count){
	
	$.ajax({
		url:root+'clients/ajax_tran/'+resourceId+'.json',
		type:'GET',
		dataType:'json',
		success:function(array){
		var htmlStr = "<table><tr height=23><th>ID</th><th>交易日期</th><th>金额</th><th>交易类型</th><th>余额</th><th>原因</th><th>备注</th></tr>";
		for(var i=0;i<array.length;i++){
			htmlStr = htmlStr+"<tr height=25>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+(i+1);
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].create_time;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].amount;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].tran_type;	
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].balance;	
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].cause;	
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].description;	
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"</tr>";
		}
		htmlStr = htmlStr+"</table>";
		$('#ipTable'+count).html(htmlStr);
	},
	 error:function(){
		var htmlStr = "Sorry!System has error!Please restart the system,and login to view!";
		$('#ipTable'+count).html(htmlStr);
	}
	});
}
/**
 * 创建电路使用报表的host
 * @param root
 * @param resourceId
 * @param count
 * @return
 */
function create_host_reportTable(root,resourceId,count){
	$.ajax({
		url:root+'gatewaygroups/ajax_host_report/'+resourceId+'.json',
		type:'GET',
		dataType:'json',
		success:function(array){
		var htmlStr = "<table><tr height=23><th>Host</th><th>ip</th><th>port</th><th>Cps</th><th>Userd</th></tr>";
		for(var i=0;i<array.length;i++){
			htmlStr = htmlStr+"<tr height=25>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+(i+1);
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].ip;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].port;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].fqdn;	
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].use_cnt;	
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"</tr>";
		}
		htmlStr = htmlStr+"</table>";
		$('#ipTable'+count).html(htmlStr);
	},
	 error:function(){
			var htmlStr = "Sorry!System has error!Please restart the system,and login to view!";
			$('#ipTable'+count).html(htmlStr);
		}
	});
}
//******************************************新增赠送话费规则**************************************************
function creategift_amount(){
	var tab = document.getElementById("gift_amounttimeBody");
	var row = document.createElement("tr");

	
	for (var i=0;i<5;i++) {
		var cell = document.createElement("td");
		 var   input =document.createElement("input");
			//cell.appendChild(input);
		if (i == 4) {
			cell.className="leftAlign rightBorder";
			if (cell.getElementsByTagName("input")[0] != null) {
				cell.removeChild(cell.getElementsByTagName("input")[0]);
			}
			var lable = document.createElement("lable");//创建lable
			var a1 = document.createElement("a");
			a1.className=" resource_add_Edit_style_24";
			a1.href="javascript:void(0)";
			var delimg = document.createTextNode("删除");
			//delimg.src="/exchange/images/del.gif";
		//	delimg.title = "";
			a1.appendChild(delimg);
			lable.appendChild(a1);
			a1.onclick = function(){
				if (confirm("确定要删除吗?")) {
					tab.removeChild(row);//del

				}
			};
			cell.appendChild(lable);
		}
		if(i==3){
			cell.className="leftAlign rightBorder";
			var copycps = document.getElementById("bonus_credit0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
		//	newcps.className="in-text.hover,.in-password.hover,.in-textarea.hover";
			cell.appendChild(newcps);
		}
		if(i==2){
			cell.className="leftAlign rightBorder";
			var copycps = document.getElementById("gift_amount0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
		//	newcps.className="in-text.hover,.in-password.hover,.in-textarea.hover";
			cell.appendChild(newcps);
		}
		if(i==1){
			cell.className="leftAlign rightBorder";
			var copycps = document.getElementById("basic_amount0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
		//	newcps.className="in-text.hover,.in-password.hover,.in-textarea.hover";
			cell.appendChild(newcps);
		}
		if(i==0){
			cell.className="leftAlign rightBorder";
			var copycps = document.getElementById("refill_amount0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
		//	newcps.className="in-text.hover,.in-password.hover,.in-textarea.hover";
			cell.appendChild(newcps);
		}
		row.appendChild(cell);
	}
	tab.appendChild(row);
}
//************************************添加积分转话费用规则********************************//
function creategift_point(){
	var tab = document.getElementById("gift_pointtimeBody");
	var row = document.createElement("tr");
	for (var i=0;i<3;i++) {
		var cell = document.createElement("td");
		 var   input =document.createElement("input");
			//cell.appendChild(input);
		if (i == 2) {
			cell.className="leftAlign rightBorder";
			if (cell.getElementsByTagName("input")[0] != null) {
				cell.removeChild(cell.getElementsByTagName("input")[0]);
			}
			var lable = document.createElement("lable");//创建lable
			var a1 = document.createElement("a");
			a1.className=" resource_add_Edit_style_24";
			a1.href="javascript:void(0)";
			var delimg = document.createTextNode("删除");
			//delimg.src="/exchange/images/del.gif";
		//	delimg.title = "";
			a1.appendChild(delimg);
			lable.appendChild(a1);
			a1.onclick = function(){
				if (confirm("确定要删除吗?")) {
					tab.removeChild(row);//del
				}
			};
			cell.appendChild(lable);
		}
		if(i==1){
			cell.className="leftAlign rightBorder";
			var copycps = document.getElementById("bonus_credit_point0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
		//	newcps.className="in-text.hover,.in-password.hover,.in-textarea.hover";
			cell.appendChild(newcps);
		}
//		if(i==1){
//			cell.className="leftAlign rightBorder";
//			var copycps = document.getElementById("gift_amount0");
//			newcps = copycps.cloneNode(true);
//			newcps.value = "";
//		//	newcps.className="in-text.hover,.in-password.hover,.in-textarea.hover";
//			cell.appendChild(newcps);
//		}
		if(i==0){
			cell.className="leftAlign rightBorder";
			var copycps = document.getElementById("bonus_credit_point0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
		//	newcps.className="in-text.hover,.in-password.hover,.in-textarea.hover";
			cell.appendChild(newcps);
		}
		row.appendChild(cell);
	}
	tab.appendChild(row);
}
//单激复选框给隐藏域赋值
function addHideValue(e){
	if($(e).attr("checked")==true){
		$(e).next().attr("value",'true');
	}else{
		$(e).next().attr("value",'false');
	}
}
//普通删除res_ip
function deleteHostTr(e){
	 var obj=$("[name='ip[]']");
	 if(obj.length>0){
			if(confirm('确定删除吗？')){
				$(e).parent().parent().remove();//删除当前行
			 }
		}else{
			//showMessages("[{'field':'#ip0','code':'101','msg':'至少要添加一个host'}]");
		}
}
//普通删除赠送话费规则
function deletegift_amount(e){
	 var obj=$("[name='refill_amount[]']");
	 if(obj.length>1){
			if(confirm('确定删除吗？')){
				$(e).parent().parent().remove();//删除当前行
			 }
		}else{
			showMessages("[{'field':'#refill_amount0','code':'101','msg':'至少要添加一个赠送话费规则'}]");
		}
}
var direction_id_arr='';
function del_direction(e,direction_id){
	if(confirm('确定删除吗？')){
		$(e).parent().parent().remove();//删除当前行
		direction_id_arr=direction_id_arr+','+direction_id;//记录删除的resource_ip的id
		document.getElementById('deldirectionid').value=direction_id_arr;
	 }
}
var resource_product_arr='';
function delProduct(e,ref_id){
	if(confirm('确定删除吗？')){
		$(e).parent().parent().remove();//删除当前行
		resource_product_arr=resource_product_arr+','+ref_id;//记录删除的resource_ip的id
		document.getElementById('delProduct').value=resource_product_arr;
	 }
}
//已有记录的删除res_ip
var resource_ip_id_arr='';
function delHost(e,resource_ip_id){
	 var obj=$("[name='ip[]']");
	 if(obj.length>1){
			if(confirm('确定删除吗？')){
				$(e).parent().parent().remove();//删除当前行
				resource_ip_id_arr=resource_ip_id_arr+','+resource_ip_id;//记录删除的resource_ip的id
				document.getElementById('delHost').value=resource_ip_id_arr;
			 }
		}else{
			showMessages("[{'field':'#ip0','code':'101','msg':'至少要添加一个host'}]");
		}
}




var res_dyn_id_arr='';

//删除动态路由和落地网关的关联
function delEgress(e,res_dyn_id){
	if(confirm('确定删除吗？')){
		 var obj=$("[name='engress_res_id[]']");
		if(obj.length>1){
			$(e).parent().parent().remove();//删除当前行
			res_dyn_id_arr=res_dyn_id_arr+','+res_dyn_id;//记录删除的落地网关
			document.getElementById('delEgress').value=res_dyn_id_arr;
			
		}else{
			
			showMessages("[{'field':'#DynamicrouteEngressResId','code':'101','msg':'至少要添加一个落地网关'}]");


		}
		
	
		 }
	
	
	
}




//******************************************新增落地网关**************************************************
function addEgress(){

if(($("#egress_table tr").length-1)>8){
	
	jQuery.jGrowl.defaults.position = 'top-center';
	jQuery.jGrowl("Egress can only add 8",{theme:'jmsg-alert'}); 
	return ;
}
	
	var tab = document.getElementById("egresstimeBody");
	var row = document.createElement("tr");

	
	for (var i=0;i<2;i++) {
		var cell = document.createElement("td");
		 var   input =document.createElement("input");
			//cell.appendChild(input);
		if (i == 1) {
		
			if (cell.getElementsByTagName("input")[0] != null) {
				cell.removeChild(cell.getElementsByTagName("input")[0]);
			}
			var lable = document.createElement("lable");//创建lable
			var a1 = document.createElement("a");
			a1.href="javascript:void(0)";
			var delimg = document.createTextNode("Delete");
			//delimg.src="/exchange/images/del.gif";
		//	delimg.title = "";
			a1.appendChild(delimg);
			lable.appendChild(a1);
			a1.onclick = function(){
				//if (confirm("是否要删除？")) {
					tab.removeChild(row);//del

				//}
			};
			cell.appendChild(lable);
		}

		if(i==0){
	
			var copycapa = document.getElementById("DynamicrouteEngressResId");
			newcap= copycapa.cloneNode(true);
			
			
			//newcap.className="input in-select";
			cell.appendChild(newcap);
		}


	
		row.appendChild(cell);
	}
	
	tab.appendChild(row);
}








/**
 * 创建动态路由表格
 * @param root
 * @param resourceId
 * @param count
 * @return
 */
function createDynTable(root,dynamic_route_id,count){
	
	
	$.ajax({
		url:root+'dynamicroutes/ajax_egress/'+dynamic_route_id+'.json',
		type:'GET',
		dataType:'json',
		success:function(array){
		var htmlStr = "<table><tr height=23><th>Host</th><th>IP FQDN/Netmask</th><th>Port</th><th>Call Limit</th><th>CPS Limit</th></tr>";
		for(var i=0;i<array.length;i++){
			htmlStr = htmlStr+"<tr height=25>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+(i+1);
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].host;
			//htmlStr = htmlStr+"</td>";
			//htmlStr = htmlStr+"<td>";
			if(array[i][0].netmask!='')
			{
				htmlStr = htmlStr+'/'+array[i][0].netmask;
			}
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].port;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			if(array[i].capacity!=0){
				htmlStr = htmlStr+array[i][0].capacity;	
			}
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			if(array[i].cps!=0){
				htmlStr = htmlStr+array[i][0].cps_limit;	
			}
			htmlStr = htmlStr+"</td>";
	//		htmlStr = htmlStr+"<td>";
			 //var params='"'+array[i].host+'","'+array[i].port+'"';
			//htmlStr = htmlStr+"		<a href='javascript:displayMessage("+params+");' style='color:#005c9c;text-decoration:none;' >Start Capture</a>";
		//	htmlStr = htmlStr+"</td>";
	
			htmlStr = htmlStr+"</tr>";
		}
		
		htmlStr = htmlStr+"</table>";
		
		$('#ipInfo'+count).html(htmlStr);

	},
	 error:function(){
		
		var htmlStr = "Sorry!System has error!Please restart the system,and login to view!";
		
		$('#ipTable'+count).html(htmlStr);
	}
	});
}





/**
 * 循环创建
 * @param root
 * @param resourceId
 * @param count
 * @return
 */
function createTable(root,resourceId,count){

	$.ajax({
		url:root+'gatewaygroups/ajax_ip/'+resourceId+'.json',
		type:'GET',
		dataType:'json',
		success:function(array){
		var htmlStr = "<table class='table table-condensed'><tr height=23><th>Host</th><th>IP</th><th>Prefix</th><th>Port</th><th>CPS</th>";
		for(var i=0;i<array.length;i++){

			htmlStr = htmlStr+"<tr height=25>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+(i+1);
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			if(array[i][0].ip){
				htmlStr = htmlStr+array[i][0].ip;
			}else{
				htmlStr = htmlStr+array[i][0].fqdn;
			}
			htmlStr = htmlStr+"</td>";

			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].prefix;
			htmlStr = htmlStr+"</td>";

			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].port;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			if(array[i].capacity!=0){
				htmlStr = htmlStr+array[i][0].capacity;	
			}
			htmlStr = htmlStr+"</td>";

	//		htmlStr = htmlStr+"<td>";
			 //var params='"'+array[i].host+'","'+array[i].port+'"';
			//htmlStr = htmlStr+"		<a href='javascript:displayMessage("+params+");' style='color:#005c9c;text-decoration:none;' >Start Capture</a>";
		//	htmlStr = htmlStr+"</td>";
//暂时屏蔽
			
//			if (array[i][0].need_register == true) {
//			htmlStr = htmlStr+"<td>";
//			htmlStr = htmlStr+(array[i][0].registered==true?"yes":"no");
//			if (array[i][0].registered==false){
//				htmlStr += "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"document.getElementById('ip_id').value='"+array[i][0].resource_ip_id+"';cover('edit_ip')\">Edit</a>";
//			}
//			htmlStr = htmlStr+"</td>";
//	
//			
//                        }
		}
		htmlStr = htmlStr+"</tr>";
		htmlStr = htmlStr+"</table>";
		
		$('#ipTable'+count).html(htmlStr);

	},
	 error:function(){
		
		var htmlStr = "Sorry!System has error!Please restart the system,and login to view!";
		
		$('#ipTable'+count).html(htmlStr);
	}
	});
	
}

function updateIp(){
	var user_name = $('#ip_username').val();
	if (!user_name){jQuery.jGrowl('请输入新用户名',{theme:'jmsg-alert'});return;}
	
	var user_pass = $('#ip_pass').val();
	if (!user_pass){jQuery.jGrowl('请输入新密码',{theme:'jmsg-alert'});return;}
	
	var id = $('#ip_id').val();
	jQuery.post('/exchange/gatewaygroups/edit_ip',{user_name:user_name,pass:user_pass,id:id},function(data){
		location.reload();
	});
}



function pull(root,com,count,callback){
	var index = com.src ;
	if (callback)callback();
	if(index.indexOf('+.gif')>=0){
		pushAll(root);
		com.src = root+"images/-.gif";
		com.alt = "extend";
		$('#ipInfo'+count).slideDown("normal");
		$('#resInfo'+count).attr("style","background-color:#f9f9f9");
	}
	else{
		com.src = root+"images/+.gif";
		com.alt = "deploy";
		$('#ipInfo'+count).slideUp('normal');
		$('#resInfo'+count).removeAttr("style");
	}
}
function pushAll(root){
	var object = $("img[alt='extend']").each(function(i){
		jQuery(this).attr("src",root+"images/+.gif");
		var count = jQuery(this).attr('id').substring(5,6);
		$('#ipInfo'+count).slideUp('normal');
		$('#resInfo'+count).removeAttr('style');
	});
}
function history_fun(ids){
	var explored = $("#explorValue").val();
	if(explored!=0){
		$("#errorMessage"+explored).hide();
	}
	if(ids!=explored){
		$("#errorMessage"+ids).removeAttr("style");
		$("#explorValue").val(ids);
	}
	else{
		$("#explorValue").val(0);
	}
}
function createSeriesTable(id,count,msg){
	
	jQuery.getJSON('/exchange/cardpools/get_batchs?id='+id,function(array){
		var htmlStr = "<table><tr><th>ID</th><th>"+msg.st+"</th><th>"+msg.et+"</th><th>"+msg.gt+"</th><th>"+msg.oc+"</th><th>"+msg.ocn+"</th><th>&nbsp;</th></tr>";
		if (array.length == 0){
			htmlStr += "<tr><td colspan='5' style='text-align:center;color:green'>"+msg.nodata+"</td></tr>";
		}
		for(var i=0;i<array.length;i++){

			htmlStr = htmlStr+"<tr>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].series_batch_id;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].start_num;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].end_num;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+array[i][0].generated_date;
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+"<span style='color:red'>"+array[i][0].of_cards+"</span>";
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+"<a href='/exchange/cardpools/cards_list/"+array[i][0].card_series_id+"?bybatch=t&batch_id="+array[i][0].series_batch_id+"'>"+array[i][0].of_cards_now+"</a>";
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+"<td>";
			htmlStr = htmlStr+"<a style='float:left;margin-left:41%;' href='/exchange/cardpools/download/"+array[i][0].series_batch_id+"/batch'><img src='/exchange/images/ico_download.gif' alt=''></a>";
			htmlStr = htmlStr+"</td>";
			htmlStr = htmlStr+'</tr>';
		}
		
		htmlStr = htmlStr+'</table>';
		
		$('#ipTable'+count).html(htmlStr);
	});
	
}