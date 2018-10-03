function ajax_check_upload(root){
	jQuery.jGrowl.defaults.position = 'top-center';
	 jQuery.jGrowl("数据已经开始验证,请等待己秒钟",{theme:'jmsg-success'}); 
	var id=$('#id_code_decks').val();
	
	$.ajax({
		url:root+'codedecks/ajax_check_upload/'+id+'.json',
		type:'GET',
		dataType:'json',
		success:function(array){
	},
	 error:function(){
		
		var htmlStr = "Sorry!System has error!Please restart the system,and login to view!";
		
		
	}
	});
}
var ip_host=1;
//新增host
function createHost(){
	ip_host++;
	var tab = document.getElementById("timeBody");
	var row = document.createElement("tr");
	for (var i=0;i<10;i++) {
		var cell = document.createElement("td");
		 var   input =document.createElement("input");
			//cell.appendChild(input);
		if (i == 9) {
			cell.className="backGround leftAlign topBorder";
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
				if (confirm("确定要这样操作吗？")) {
					tab.removeChild(row);//del

				}
			};
			cell.appendChild(lable);
		}
		if(i==8){
			cell.className="backGround centerAlign rightBorder topBorder";
			var copycps = document.getElementById("username0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
			
			$(newcps).attr('id','reg_username'+ip_host);
			cell.appendChild(newcps);
		}
		if(i==7){
			cell.className="backGround centerAlign rightBorder topBorder";
			var copycps = document.getElementById("pass0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
			$(newcps).attr('id','reg_pass'+ip_host);
			cell.appendChild(newcps);
		}
		//是否zhuce 
		if(i==6){			
			cell.className="backGround centerAlign rightBorder topBorder";
			  hidden=	document.createElement("input");
			  hidden.type="hidden";
			  hidden.name="need_register[]";
				cell.appendChild(hidden);

				
				var copychx = document.getElementById("_need_register0");
				newchx = copychx.cloneNode(true);
				
				newchx.onclick = function(){
					this.previousSibling.value=this.checked?true:false;
					if(this.checked==true){
					//	showMessages("[{'field':'#ip00','code':'201','msg':'网关被注册后ip地址里可以添加域名'}]");
						//showMessages("[{'field':'#username00','code':'201','msg':'请输入用户名'}]");
						//showMessages("[{'field':'#pass00','code':'201','msg':'请输入密码'}]");
						  $('#reg_ip'+ip_host).attr('class','invalid');
						  $('#reg_ip'+ip_host).attr('title','网关被注册后ip地址里可以输入域名');

						  $('#reg_username'+ip_host).attr('class','invalid');
						  $('#reg_username'+ip_host).attr('title','请输入用户名');
						  $('#reg_pass'+ip_host).attr('class','invalid');
						  $('#reg_pass'+ip_host).attr('title','请输入密码');
						
						
					}else{
					//	showMessages("[{'field':'#ip00','code':'201','msg':'网关没有被注册之前," +
							//	"ip地址里不可以添加域名,用户名和密码也无效'}]");
						
						  $('#reg_ip'+ip_host).attr('class','invalid');
						  $('#reg_ip'+ip_host).attr('title','请输入正确的ip地址,例如192.168.1.1.125');

						  $('#reg_username'+ip_host).attr('class','');
						  
						  $('#reg_pass'+ip_host).attr('class','');
						  
					}
			};
			cell.appendChild(newchx);
		}
		
		
		if(i==5){
			cell.className="backGround centerAlign rightBorder topBorder";
			var copycps = document.getElementById("time_profile_id0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
			cell.appendChild(newcps);
		}
		
		if(i==4){
			cell.className="backGround centerAlign rightBorder topBorder";
			var copycps = document.getElementById("cps0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
			cell.appendChild(newcps);
		}
		
		if(i==3){
			cell.className="backGround centerAlign rightBorder topBorder";
			var copycapa = document.getElementById("capa0");
			newcap= copycapa.cloneNode(true);
			newcap.value = "";
			cell.appendChild(newcap);
		}
		//监控
		if (i == 2) {	
			cell.className="backGround centerAlign rightBorder topBorder";
			var copycapa = document.getElementById("port0");
			newcap= copycapa.cloneNode(true);
			//newcap.value = "";
			cell.appendChild(newcap);
		}
		if (i == 1) {
			var copycapa = document.getElementById("netmask0");
			newcap= copycapa.cloneNode(true);
			cell.className="backGround centerAlign rightBorder topBorder";
			newcap.value = "";
			newcap.selectedIndex = 0;
			cell.appendChild(newcap);
} 
	
		if (i == 0) {
			var copycapa = document.getElementById("ip0");
			newcap= copycapa.cloneNode(true);
			newcap.value = "";
			newcap.className="";
			$(newcap).attr('id','reg_ip'+ip_host);
			cell.className="backGround leftAlign1 rightBorder topBorder";
			cell.appendChild(newcap);
		

} 
		

		row.appendChild(cell);
	}
	
	tab.appendChild(row);
}



//******************************************新增action***************************************************
function createAction(){
	var tab = document.getElementById("actiontimeBody");
	var row = document.createElement("tr");

	
	for (var i=0;i<5;i++) {
		var cell = document.createElement("td");
		 var   input =document.createElement("input");
			//cell.appendChild(input);
		if (i == 4) {
			cell.className="backGround leftAlign  topBorder";
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
				if (confirm("Are you sure？")) {
					tab.removeChild(row);//del

				}
			};
			cell.appendChild(lable);
		}

		if(i==3){
			cell.className="backGround centerAlign rightBorder topBorder";
			var copycps = document.getElementById("digit0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
			cell.appendChild(newcps);
		}
		
		if(i==2){
			cell.className="backGround centerAlign rightBorder topBorder";
			var copycapa = document.getElementById("action0");
			newcap= copycapa.cloneNode(true);
			newcap.value = "";
			newcap.selectedIndex = 0;
			cell.appendChild(newcap);
		}
		//监控
		if (i == 1) {	
			cell.className="backGround centerAlign rightBorder topBorder";
			var copycapa = document.getElementById("match0");
			newcap= copycapa.cloneNode(true);
			newcap.value = "";
			cell.appendChild(newcap);
		}
		if (i == 0) {
			var copycapa = document.getElementById("direct0");
			newcap= copycapa.cloneNode(true);
			newcap.value = "";
			newcap.selectedIndex = 0;
			cell.className="backGround centerAlign rightBorder topBorder";
			cell.appendChild(newcap);
} 
	
		row.appendChild(cell);
	}
	
	tab.appendChild(row);
}






//******************************************新增product**************************************************
function createProduct(){
	var tab = document.getElementById("producttimeBody");
	var row = document.createElement("tr");

	
	for (var i=0;i<3;i++) {
		var cell = document.createElement("td");
		 var   input =document.createElement("input");
			//cell.appendChild(input);
		if (i == 2) {
			cell.className="backGround leftAlign topBorder";
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
				if (confirm("Are you sure？")) {
					tab.removeChild(row);//del

				}
			};
			cell.appendChild(lable);
		}

		if(i==1){
			cell.className="backGround leftAlign rightBorder topBorder";
			var copycps = document.getElementById("prefix0");
			newcps = copycps.cloneNode(true);
			newcps.value = "";
		//	newcps.className="in-text.hover,.in-password.hover,.in-textarea.hover";
			cell.appendChild(newcps);
		}
		
		if(i==0){
			cell.className="backGround leftAlign1 rightBorder topBorder";
			var copycapa = document.getElementById("proId0");
			newcap= copycapa.cloneNode(true);
			newcap.value = "";
			newcap.selectedIndex = 0;
			//newcap.className="in-text.hover,.in-password.hover,.in-textarea.hover";
			cell.appendChild(newcap);
		}


	
		row.appendChild(cell);
	}
	
	tab.appendChild(row);
}




/**
* 
* @param ingress1
* @param egress1
* @return
*/
function initGress(ingress1,egress1){
	//选中ingress(对接网关)
	if(ingress1){
		document.getElementById('_ingress').value='true';
		document.getElementById('_egress').value='false';
		document.getElementById('_pay_monthly_span').style.display='none';
		document.getElementById('_tdm_span').style.display='none';
		document.getElementById('rate_div').style.display='none';
		document.getElementById('_direct_span').style.display='none';
		document.getElementById('_mapping_div').style.display='';
		document.getElementById('_product_div').style.display='';
		document.getElementById('_strate_div').style.display='none';
	}
	if(egress1&& egress1!=''){
		//落地网关
		document.getElementById('_ingress').value='false';
		document.getElementById('_egress').value='true';
		document.getElementById('_pay_monthly_span').style.display='';
		document.getElementById('_tdm_span').style.display='';
		document.getElementById('rate_div').style.display='';
		document.getElementById('_direct_span').style.display='';
		document.getElementById('_mapping_div').style.display='none';
		document.getElementById('_product_div').style.display='none';
		document.getElementById('_strate_div').style.display='';
	}
	
}


function check_gress(){
	var ingress1=document.getElementById('ingress1').checked;
	var egress1=document.getElementById('egress1').checked;
	//选中ingress(对接网关)
	if(ingress1){
		document.getElementById('_ingress').value='true';
		document.getElementById('_egress').value='false';
		document.getElementById('_pay_monthly_span').style.display='none';
		document.getElementById('_tdm_span').style.display='none';
		document.getElementById('rate_div').style.display='none';
		document.getElementById('_direct_span').style.display='none';
		document.getElementById('_mapping_div').style.display='';
		document.getElementById('_product_div').style.display='';
		document.getElementById('_strate_div').style.display='none';
	}
	if(egress1){
		//落地网关
		document.getElementById('_ingress').value='false';
		document.getElementById('_egress').value='true';
		document.getElementById('_pay_monthly_span').style.display='';
		document.getElementById('_tdm_span').style.display='';
		document.getElementById('rate_div').style.display='';
		document.getElementById('pass_through_div').style.display='';
		document.getElementById('_direct_span').style.display='';
		document.getElementById('_mapping_div').style.display='none';
		document.getElementById('_product_div').style.display='none';
		document.getElementById('_strate_div').style.display='';
	}

}


function check_tdm(){

	var ingress1=document.getElementById('tdm1').checked;
	
	//选中ingress
	if(ingress1){
		document.getElementById('_tdm').value='true';
	}else{
		document.getElementById('_tdm').value='false';
	}


}

function check_month(){

	var ingress1=document.getElementById('pay_monthly1').checked;
	
	//选中ingress
	if(ingress1){
		document.getElementById('_pay_monthly').value='true';
	}else{
		document.getElementById('_pay_monthly').value='false';
	}


}



/**
 * 
 * 
 * 核查网关是否注册
 * @return
 */
function check_register(){

	var ingress1=document.getElementById('_need_register0').checked;
	
	//选中ingress
	if(ingress1){
		document.getElementById('need_register0').value='true';
	
		
		  $('#ip0').attr('class','invalid');
		  $('#ip0').attr('title','网关被注册后ip地址里可以输入域名');

		  $('#username0').attr('class','invalid');
		  $('#username0').attr('title','请输入用户名');
		  $('#pass0').attr('class','invalid');
		  $('#pass0').attr('title','请输入密码');
		  

	}else{
		
		document.getElementById('need_register0').value='false';
		  $('#ip0').attr('class','invalid');
		  $('#ip0').attr('title','请输入正确的ip地址,例如192.168.1.1.125');
		  $('#username0').attr('class','');
		  $('#pass0').attr('class','');

	}


}

function check_active(){

	var ingress1=document.getElementById('active1').checked;
	
	//选中ingress
	if(ingress1){
		document.getElementById('_active').value='true';
	}else{
		document.getElementById('_active').value='false';
	}


}


function check_direct(){
	var ingress1=document.getElementById('direct1').checked;
	if(ingress1){
		document.getElementById('_direct').value='true';
	}else{
		document.getElementById('_direct').value='false';
	}
}
function check_LNP(){
	var ingress1=document.getElementById('LNP1').checked;
	if(ingress1){
		document.getElementById('_LNP').value='true';
	}else{
		document.getElementById('_LNP').value='false';
	}
}


function check_t38(){
	var ingress1=document.getElementById('t381').checked;
	if(ingress1){
		document.getElementById('_t38').value='true';
	}else{
		document.getElementById('_t38').value='false';
	}
}



function check_lrn(){
	var ingress1=document.getElementById('lrn_block1').checked;
	if(ingress1){
		document.getElementById('_lrn_block').value='true';
	}else{
		document.getElementById('_lrn_block').value='false';
	}
}




//通过jGrowl显示错误信息
function alert_info(msgs,temp){
	//错误信息格式定义
	
	//登录验证格式
/*	var msgs =[
	           {"moduleName":"_auth","facadeName":"_authCheck","code":101,"args":[],"msg":"Access denied!"}
	           ];*/
	
//添加网关组错误信息格式	
/*var msgs1= [
{"moduleName":"voip_hosts","facadeName":"add","code":101,"args":[],"msg":"Please fill \"Name\" field."},
{"moduleName":"voip_hosts","facadeName":"add","code":102,"args":[],"msg":"Please fill \"IP\" field correctly."}
];*/



//if we got just one message to show
if (!(msgs instanceof Array)) {
  msgs = [msgs];
}
	   // init defaults
  $.jGrowl.defaults.position = 'top-center';
  $.jGrowl.defaults.closeTemplate = 'x';
  $.jGrowl.defaults.closerTemplate = '<div>[ '+L['hide-all']+' ]</div>';
  // init variables
  var params = {
      sticky: false,  //不需要用户手动关闭
      theme: 'default'
  };
  
  //循环遍历错误信息
  for(var i = 0; i < msgs.length; i++) {
      if (!msgs[i]['msg']) {
          continue;
      }
      params['sticky'] = true;
      switch(msgs[i]['code'].toString().substring(0, 1)) {
          case '1':
              params['theme'] = 'jmsg-alert';
              break;
          case '2':
              params['sticky'] = false;
              params['theme'] = 'jmsg-success';
              break;
          case '3':
          case '4':
              params['theme'] = 'jmsg-error';
              break;
          default:
              params['theme'] = 'jmsg-default';
              break;
      }
     //jquery的弹出信息显示 
      $.jGrowl(msgs[i]['msg'], params);
  }

    
  
}







/**
* init the times
*/
var times = 0 ;


/**
* change entity to decrease ingress resource
* 
* @param select
* @return
*/
function entSelChange(select){
	
	var selectValue = $(select).val();
	
	if(selectValue>=0){
		
		$.ajax({
			
			url:'./entRes.html?times='+times,
			type:'get',
			data:'entId='+selectValue,
			dataType:'xml',
			error:function(x){alert(x.status);},
			success:function(data){
				
				$('#BlocklistIngressResId').empty();
				
				$('<option value=0>全部</option>').appendTo('#ingressResource');
				
				$(data).find("option").each(function(j){

					$('<option value='+$(this).children('value').text()+'>'+$(this).children('lable').text()+'</option>').appendTo('#ingressResource')
					
				}) 
				}

		}
		);
		
		times++;
	}
}







/**
* 动态查找指定客户的对接网关
* @param root
* @param resourceId
* @param count
* @return
*/
function ajax_ingress(root,clientid){
	$.ajax({
		url:root+'blocklists/ajax_ingress/'+clientid+'.json',
		type:'GET',
		dataType:'json',
		success:function(array){
		
		$('#BlocklistIngressResId').empty();
		
		$('<option value=0>All</option>').appendTo('#BlocklistIngressResId');
		var option; // option
		for ( var i = 0; i < array.length; i++) {
			option = document.createElement("option");
			option.value = array[i][0].resource_id;
			option.innerHTML =array[i][0].alias;
			document.getElementById('BlocklistIngressResId').appendChild(option);
		}

	},
	 error:function(){
		
		var htmlStr = "Sorry!System has error!Please restart the system,and login to view!";
		
		
	}
	});
	
}

function deleteTd(obj){
	if (confirm("Are you sure?")) {
		obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);
	}
}








function ajax_egress(root,clientid){
	$.ajax({
		url:root+'blocklists/ajax_egress/'+clientid,
		type:'GET',
		
		success:function(array){
		$('#BlocklistEngressResId').empty();
		
		$(array).appendTo('#BlocklistEngressResId');


	},
	 error:function(){
		
		var htmlStr = "Sorry!System has error!Please restart the system,and login to view!";
		
		
	}
	});
	
}


/************************************************************************************************************************
 * 
 * 全选角色，反选角色
 * 
 * 
 * **********************************************************************************************************************
 */
//处理默认模块
function repaintModules() 
{

	 $('input[type=checkbox]').each(function(){

			if($(this).attr("checked")==true){
//如果checkbox被选中
			     $(this).next().attr("value",'true');//设置旁边的隐藏域的值为true
				}else{
					$(this).next().attr("value",'false');
					}


		 });
	
	//如果2个可读可写的checkbox都被取消，默认模块下拉框中的模块不可以选
  $('#RoleDefaultSysfuncId option').each(function () {
      if ($('input[rel=acl-'+$(this).attr('value')+']:checked').length) {
          $(this).attr('disabled', '');
      } else {
          $(this).attr('disabled', 'disabled');
      }
  });
}


//全选和取消可读的模块
function repaint_read_Modules() 
{
	 $("[name='readable_check']").attr("checked",'true');//全选可读的模块
	 $('input[name=readable_check]').each(function(){

			if($(this).attr("checked")==true){
//如果checkbox被选中
			     $(this).next().attr("value",'true');//设置旁边的隐藏域的值为true
				}else{
					$(this).next().attr("value",'false');
					}


		 });
	
	//如果2个可读可写的checkbox都被取消，默认模块下拉框中的模块不可以选
  $('#RoleDefaultSysfuncId option').each(function () {
      if ($('input[rel=acl-'+$(this).attr('value')+']:checked').length) {
          $(this).attr('disabled', '');
      } else {
          $(this).attr('disabled', 'disabled');
      }
  });
}





//取消可读的模块
function repaint_read_Modules_cancel() 
{

	 $("[name='readable_check']").removeAttr("checked");//取消全选
	 $('input[name=readable_check]').each(function(){

			if($(this).attr("checked")==true){
//如果checkbox被选中
			     $(this).next().attr("value",'true');//设置旁边的隐藏域的值为true
				}else{
					$(this).next().attr("value",'false');
					}


		 });
	
	//如果2个可读可写的checkbox都被取消，默认模块下拉框中的模块不可以选
  $('#RoleDefaultSysfuncId option').each(function () {
      if ($('input[rel=acl-'+$(this).attr('value')+']:checked').length) {
          $(this).attr('disabled', '');
      } else {
          $(this).attr('disabled', 'disabled');
      }
  });
}


//全选可写的模块
function repaint_write_Modules() 
{
	 $("[name='writable_check']").attr("checked",'true');//全选可写的模块
	 $('input[name=writable_check]').each(function(){

			if($(this).attr("checked")==true){
//如果checkbox被选中
			     $(this).next().attr("value",'true');//设置旁边的隐藏域的值为true
				}else{
					$(this).next().attr("value",'false');
					}


		 });
	
	//如果2个可读可写的checkbox都被取消，默认模块下拉框中的模块不可以选
  $('#RoleDefaultSysfuncId option').each(function () {
      if ($('input[rel=acl-'+$(this).attr('value')+']:checked').length) {
          $(this).attr('disabled', '');
      } else {
          $(this).attr('disabled', 'disabled');
      }
  });
}






//取消可写的模块
function repaint_write_Modules_cancel() 
{

	 $("[name='writable_check']").removeAttr("checked");//取消全选
	 $('input[name=writable_check]').each(function(){

			if($(this).attr("checked")==true){
//如果checkbox被选中
			     $(this).next().attr("value",'true');//设置旁边的隐藏域的值为true
				}else{
					$(this).next().attr("value",'false');
					}


		 });
	
	//如果2个可读可写的checkbox都被取消，默认模块下拉框中的模块不可以选
  $('#RoleDefaultSysfuncId option').each(function () {
      if ($('input[rel=acl-'+$(this).attr('value')+']:checked').length) {
          $(this).attr('disabled', '');
      } else {
          $(this).attr('disabled', 'disabled');
      }
  });
}



//全选可执行的模块
function repaint_exe_Modules() 
{
	 $("[name='executable_check']").attr("checked",'true');//全选可执行的模块
	 $('input[name=executable_check]').each(function(){

			if($(this).attr("checked")==true){
//如果checkbox被选中
			     $(this).next().attr("value",'true');//设置旁边的隐藏域的值为true
				}else{
					$(this).next().attr("value",'false');
					}


		 });
	
	//如果2个可读可写的checkbox都被取消，默认模块下拉框中的模块不可以选
  $('#RoleDefaultSysfuncId option').each(function () {
      if ($('input[rel=acl-'+$(this).attr('value')+']:checked').length) {
          $(this).attr('disabled', '');
      } else {
          $(this).attr('disabled', 'disabled');
      }
  });
}
//取消可执行的模块
function repaint_exe_Modules_cancel() 
{

	 $("[name='executable_check']").removeAttr("checked");//取消全选
	 $('input[name=executable_check]').each(function(){

			if($(this).attr("checked")==true){
//如果checkbox被选中
			     $(this).next().attr("value",'true');//设置旁边的隐藏域的值为true
				}else{
					$(this).next().attr("value",'false');
					}


		 });
	//如果2个可读可写的checkbox都被取消，默认模块下拉框中的模块不可以选
  $('#RoleDefaultSysfuncId option').each(function () {
      if ($('input[rel=acl-'+$(this).attr('value')+']:checked').length) {
          $(this).attr('disabled', '');
      } else {
          $(this).attr('disabled', 'disabled');
      }
  });
}
////页面初始化注册事件
function check_gateway(){
	var GatewaygroupClientId = $('#GatewaygroupClientId').val();
	var timezone = $('#timezone').val();
}
