String.prototype.trim= function(){  
	return this.replace(/(^\s*)|(\s*$)/g, "");  
};
var  globalUrl='/exchange/';






/**
 * 
 * @param url
 *            请求数据的路径
 * @param columns
 *            显示到报表上的列 如[ {name:'显示的列名,不能为空',type:'该列的数据类型,为空默认为String'},
 *            {name:'显示的列名,不能为空',type:'该列的数据类型,为空默认为String'},
 *            {name:'显示的列名,不能为空',type:'该列的数据类型,为空默认为String'} ]
 * @param objId
 *            显示报表的容器Id
 * @return
 */
/*
 * 调用方法：需要首先在页面上引入下列代码： 最好放在引入script的第一行 <script type="text/javascript"
 * src="http://www.google.com/jsapi"></script>
 * <script>google.load('visualization', '1',{packages: ['annotatedtimeline']});</script>
 * 
 * 其次需要引入jQuery
 * 
 * 控制器里需返回这样的格式： [
 * ｛columnName:'第一行第一列的值',columnName:'第一行第二列的值',columnName:'第一行第三列的值'},
 * ｛columnName:'第二行第一列的值',columnName:'第二行第二列的值',columnName:'第二行第三列的值'},
 * ｛columnName:'第三行第一列的值',columnName:'第三行第二列的值',columnName:'第三行第三列的值'} ]
 * 
 * 注意：控制器返回的数据的列的顺序必须和传入的columns里列的顺序相同！
 */
function Chart(data,columns,objId){
	var table = new google.visualization.DataTable();
	var cols = null;
	if (typeof columns == "string") {
		cols = eval(columns);
	} else {
		cols = columns;
	}

	// 添加报表显示的列
	for (var i = 0;i<cols.length; i++) {
		var col = cols[i];
		table.addColumn(typeof col.type=="undefined"?"string":col.type,col.name);
	}
	
	// 添加报表显示的行
	data = data.trim().substring(0,data.length-1)+"]";
	var rowsArray = eval(data);
	var rows = new Array();
	for (var i = 0;i<rowsArray.length; i++) {
		var oneRow = new Array();
		var row = rowsArray[i];

		var columnIndex = 0;
		for (var attr in row) {
			var value = row[attr];// 取得每一列的值
			if (columnIndex == 0) {
				var vs = new   Date(Date.parse(value.replace(/-/g,   "/")));
				oneRow.push({v:vs});
				columnIndex++;
				continue;
			}

			oneRow.push({v:value});
		}


		rows.push(oneRow);
	}

	table.addRows(rows);

	// 生成报表
	var obj = document.getElementById(objId[0]);
	var oo = new google.visualization.AnnotatedTimeLine(obj);
	var obj1 = document.getElementById(objId[1]);
	var oo1 = new google.visualization.AnnotatedTimeLine(obj1);
	oo.draw(table, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0, 1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});
	oo1.draw(table, {'allowRedraw':false ,'displayAnnotations': false,'displayExactValues': false,'displayZoomButtons': false,'scaleColumns': [0, 1,2,3],'scaleType': 'allmaximized','thickness': 2,'colors':['#1670e0','#fa4727','#4b9353','#e6b640'],'min':0});
	
	var arr = new Array();
	arr.push(oo);
	arr.push(oo1);
	return arr;
}

/**
 * 
 * @param type
 *            图表类型 折线图->lc 柱状图->bvg
 * @param data
 *            数据
 * @param xDisplay
 *            x坐标显示的值
 * @param yDisplay
 *            y坐标显示的值
 * @param displayImgId
 *            显示报表图片的img标签id
 * @param width
 *            报表显示宽度
 * @param height
 *            报表显示高度
 * @return null
 */
function imageChart(type,data,xDisplay,yDisplay,displayImgId,width,height){
	var x = "0:|";
	var y = "1:||";

	for (var i = 0;i<xDisplay.length;i++) {
		x += xDisplay[i] + "|";
	}

	for (var i = 0;i<yDisplay.length;i++) {
		y += yDisplay[i] + "|";
	}
	y = y.substring(0,y.length - 1);

	var wh = "200x125";
	if (width&&height) {
		wh = width+"x"+height;
	}

	var finalUrl = "http://chart.apis.google.com/chart?chs="+wh+"&chd=s:"+data
	+"&cht="+type+"&chxt=x,y&chxl="+x+''+y;

	document.getElementById(displayImgId).src = finalUrl;
}


/**
 * 生成饼图
 * 
 * @param data
 *            数据
 * @param pieDisplay
 *            显示的名字
 * @param displayImgId
 *            显示图片的容器 id
 * @param width
 *            宽度
 * @param height
 *            高度
 * @return
 */
function pieChart(data,pieDisplay,displayImgId,width,height){
	var chxl = "";
	for (var i = 0;i<pieDisplay.length;i++) {
		chxl+=pieDisplay[i]+"|";
	}

	chxl = chxl.substring(0,chxl.length - 1);

	var wh = "200x80";
	if (width&&height) {
		wh = width+"x"+height;
	}

	var finalUrl = "http://chart.apis.google.com/chart?cht=p3&chd=s:"+data+"&chs="+wh+"&chl="+chxl;
	document.getElementById(displayImgId).src = finalUrl;
}


// 报表所需的列
function getcolumns(){
	return [
        	{name:'Date',type:'datetime'},
        	{name:'Calls',type:'number'},
        	{name:'CPS',type:'number'},
        	{name:'ASR',type:'number'},
        	{name:'ACD',type:'number'},
        	{name:'PDD',type:'number'}
       					];
}

// 加载报表
function loadChartLeft() {
	var arr = new Array();
	var lOrR = document.getElementById("leftTimeBar");
	var lOrR1 = document.getElementById("rightTimeBar");
	arr.push(lOrR);
	arr.push(lOrR1);
	
	var arr1 = new Array();
	var dfCh = document.getElementById("defaultchecked");// 默认选中24个小时
	var dfCh1 = document.getElementById("defaultchecked1");// 默认选中24个小时
	arr1.push(dfCh);
	arr1.push(dfCh1);
	
	var tmp = globalChart(['coordinateID','longerID'],1,arr1,arr);
	c1 = tmp[0];
	c2 = tmp[1];
	
	hideAndShow(c1,[0,1]);
	hideAndShow(c2,[2,3]);
}


// 隐藏和显示指定的列
function hideAndShow(who,which) {
	for (var i = 0;i<5;i++) {
		who.hideDataColumns(i);
	}
	for (var i = 0;i<which.length;i++) {
		who.showDataColumns(which[i]);
	}
}

// 时间段是否有下划线
function underlineornot(LOrR,obj){
	for (var k = 0;k<LOrR.length;k++) {
		var spans = LOrR[k].getElementsByTagName("span");
		for (var i = 0;i<spans.length;i++) {
				spans[i].style.textDecoration = "underline";
		} 
		obj[k].style.textDecoration = "none";
	}
}

/*
 * 获得span在容器中的索引
 */
function getspanindex(bar,obj){
	var spans = bar.getElementsByTagName("span");
	for (var i = 0;i<spans.length;i++) {
		if (spans[i].innerHTML.trim() == obj.innerHTML.trim()) {
			return spans[i];
		}
	}
	return null;
}
/*
 * 点击时间段查询 (24 hours、3 hours etc...)
 */
function loadset(id,type,obj,LOrR){
	var bar = new Array();
	bar.push(document.getElementById("leftControlBar"));// 左边的报表容器
	bar.push(document.getElementById("rightControlBar"));// 右边的报表容器
	
	var w;
	// 判断点击的是左侧的报表还是右侧的报表
	if (LOrR.id == "leftTimeBar") {
		var another = getspanindex(document.getElementById("rightTimeBar"),obj);
		w = globalChart(['coordinateID','longerID'],type,[obj,another],[LOrR,document.getElementById("rightTimeBar")]);
		c1 = w[0];
		c2 = w[1];
	} else {
		var another = getspanindex(document.getElementById("leftTimeBar"),obj);
		w = globalChart(['coordinateID','longerID'],type,[obj,another],[LOrR,document.getElementById("leftTimeBar")]);
		c1 = w[0];
		c2 = w[1];
	}

	// 记录选中的复选框
	for (var j = 0;j<bar.length;j++) {
		var ccount = new Array();
		var jk = 0;
		var checkboxes = bar[j].getElementsByTagName("input");
		for (var i = 0;i<checkboxes.length; i++) {
			if (checkboxes[i].type == "checkbox") {
				if (checkboxes[i].checked == true) {
					ccount.push(jk);
				}
				jk++;
			}
		}

		hideAndShow(w[j],ccount);
	}
}


/*
 * 5个复选框架只允许选择两个
 */
function onlychoosetwo(obj){
	var hideIds = new Array();// 需要隐藏的ID

	var who;// 操作哪个报表

	// 所有checkbox的id
	var allids = [
	              {txt:"first",ids:["call1","cps1","asr1","acd1","pdd1"]},
	              {txt:"second",ids:["call2","cps2","asr2","acd2","pdd2"]}
	              ];

	var checkCount = 0;// 选中的个数

	var allboxsids = null;

	if (obj.id.lastIndexOf("1") != -1) {// 点击的是左侧的报表的选择框
		allboxsids = allids[0].ids;
	}  else {// 点击的是右侧的报表的选择框
		allboxsids = allids[1].ids;
	}

	// 计算选中的个数
	for (var i = 0;i<allboxsids.length ;i++) {
		var chx = document.getElementById(allboxsids[i]);
		if (chx.checked == true) {
			checkCount ++;
		}
	}

	// 判断选中的个数 个数为1 则启用所有的复选框
	// 否则禁用未选择的复选框
	if (checkCount <=  1) {
		for (var i = 0;i<allboxsids.length ;i++) {
			var chx = document.getElementById(allboxsids[i]);
			chx.disabled = false;
		}
	} else {
		for (var i = 0;i<allboxsids.length ;i++) {
			var chx = document.getElementById(allboxsids[i]);
			if (chx.checked != true) {
				chx.disabled = true;
				hideIds.push(i);
			}

			if (chx.id.lastIndexOf("1") != -1) {
				who = c1;
			} else who = c2;
		}
	}

	// 报表中 显示选中的列 隐藏其他列
	if (hideIds.length >= 1) {
		for (var i = 0;i<5;i++) {
			who.showDataColumns(i);
		}
		for (var i = 0;i<hideIds.length ;i++) {
			who.hideDataColumns(hideIds[i]);
		}
	}
}

/*
 * 删除tbody所有行
 */
function removeAllRow(id){
	var tab = document.getElementById(id);// Table
	for(var i=tab.rows.length-1;i>=0;i--){// 清除表格所有行
		tab.deleteRow(i);
	 }		
}



/**
 * 将数据填入  指定的表格
 * @param data
 * @return
 */
function historycal(data){

// data = data.substring(0,data.length - 1)+"]";
	datas = eval("(" +data+")");
	$('#acd_15').html(datas[0]['acd']);
	$('#asr_15').html(datas[0]['asr']);
	$('#ca_15').html(datas[0]['ca']);
	$('#pdd_15').html(datas[0]['pdd']);
	//$('#profit_15').html(datas[0]['profit']);
	
	
	$('#acd_1h').html(datas[1]['acd']);
	$('#asr_1h').html(datas[1]['asr']);
	$('#ca_1h').html(datas[1]['ca']);
	$('#pdd_1h').html(datas[1]['pdd']);
//	$('#profit_1h').html(datas[1]['profit']);
	$('#acd_24h').html(datas[2]['acd']);
	$('#asr_24h').html(datas[2]['asr']);
	$('#ca_24h').html(datas[2]['ca']);
	$('#pdd_24h').html(datas[2]['pdd']);
	//$('#profit_24h').html(datas[2]['profit']);


	
}

/*
 * 查询product 和 resource history
 */
function pro_res_history(url,ahref){
	jQuery.get(url,function(data){
			data = data.substring(0,data.length - 1)+"]";
			var dataArr = eval(data);

			removeAllRow("tbodyOfShowTable");// 删除tbody的所有行
		var tab = document.getElementById("tbodyOfShowTable");// 表格
			for (var kk = 0;kk<dataArr.length;kk++) {
	 			var datas = dataArr[kk];
				var tr = document.createElement("tr");
	 			var tdd = document.createElement("td");
	 			tr.style.height = "28px";
	 			tdd.className = "bcess_td";
	 			
	 			if (ahref) {
	 				tdd.innerHTML = "<a href="+ahref+datas[0].pr_name+">"+datas[0].pr_name+"</a>";
	 			}else{
	 				tdd.innerHTML = datas[0].pr_name;
	 			}
	 			
	 			tr.appendChild(tdd);
	 			
	 			for (var i = 0;i<datas.length; i++) {
	 				var td = document.createElement("td");
	 				td.className = "bcess_td";

	 				var obj = datas[i];

	 				var j = 0;
	 				for (var attr in obj) {
	 	 				
	 	 				if (j == 0) {// 第一个属性略过 因为是prefix name 不需要显示
	 	 	 				j++;
	 	 	 					continue;
	 	 	 			}
	 					var div = document.createElement("div");
	 					if (j == 4) {
	 						div.className = "monitor_global_style_2"+(j-1);	
	 					} else {
	 						div.className = "monitor_global_style_2"+j;	
	 					}

	 					div.innerHTML = obj[attr];
	 					
	 					td.appendChild(div);
	 					j++;
	 				}
	 				tr.appendChild(td);
	 			}
	 			tab.appendChild(tr);
	 		}
		});
}


/*
 * 隐藏或显示不活动的数据行
 */
function hideInactivePro(obj,tid){
 	var tab = document.getElementById(tid);
 	var what = "table-row";
	if (obj.checked == true) {// 隐藏不活动的行
		what = "none";
	} else {// 显示不活动的行
		what = "table-row";
	}
	
	for (var i = 0;i<tab.rows.length; i++) {
		var cell = tab.rows[i].cells[tab.rows[i].cells.length-1];// 最后一列
		if (parseInt(cell.innerHTML.trim()) == 0) {
			tab.rows[i].style.display = what;
		}
	}
	
	return what;
}

/*
 * 将字符串转换为XMLDocument对象
 */
function toXML(strxml){
	  try{
	     xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
	     xmlDoc.async="false";
	     xmlDoc.loadXML(strxml);
	  	}
	  catch(e){
	     try{
	    	 		var oParser=new DOMParser();
		     	xmlDoc=oParser.parseFromString(strxml,"text/xml");
		     	} catch(e){
			     	alert("XMLDocument object can not be created");
			     		}
	  	}
// var oSerializer=new XMLSerializer();//可以输出转换后的xml Only Firefox IE下直接
// xmlDoc.xml即可
// var sXml=oSerializer.serializeToString(xmlDoc,"text/xml");
	  return xmlDoc;
}

/*
 * 替换特殊字符
 */
function convertSpecical(data){
	data = data.replace(/&lt;/g,"<");
	data = data.replace(/&gt;/g,">");
	data = data.replace(/&quot;/g,"\"");
	return data;
}


/*
 * 查询Gobal Stats 中间表格的实时信息 get_sys_limit
 * 
 * 
 * ======>去free的数据
 */

function api_getsyslimit(data){
	data = convertSpecical(data);// 转换特殊字符
	data = "<monitors>"+data.substring(data.indexOf("<"),data.length-1)+"</monitors>";
	var xmlDoc = toXML(data);// 将xml形式的字符串转换为XMLDocument

	var system = xmlDoc.childNodes[0].childNodes[0].childNodes;// 单机版所有<stat>

	var o = new Object();
	for (var i = 0;i<system.length;i++) {
		var c = system[i];
		if (c.tagName){
			var $k =c.getAttribute("name");// Key
			var $v =c.getAttribute("value");// Value
			o[$k] = $v;
		}
	}

	if (typeof o.current_calls == "undefined") {
		return;
	}
	var systab = document.getElementById("syslimit");// table =>id

	var row1 = systab.rows[0];// 第一行
	var row2 = systab.rows[1];// 第二行
	var row3 = systab.rows[2];// 第三行
	var row4 = systab.rows[3];	// 第四行
	// 第一行
	row1.cells[1].innerHTML = o.current_calls;// Currently
	// 24 hr peak
	row1.cells[2].innerHTML = parseInt(o.last_24hr_peak_w_media_calls)+parseInt(o.last_24hr_peak_wo_media_calls);
	// 7 days peak
	row1.cells[3].innerHTML = parseInt(o.last_7d_peak_w_media_calls)+parseInt(o.last_7d_peak_wo_media_calls);
	// Recent peak
	row1.cells[4].innerHTML = parseInt(o.system_peak_w_media_calls)+parseInt(o.system_peak_wo_media_calls);

	// 第二行
	row2.cells[1].innerHTML = o.current_w_media_calls; // Currently
	// 24 hr peak
	row2.cells[2].innerHTML = o.last_24hr_peak_w_media_calls; 
	// 7 days peak
	row2.cells[3].innerHTML = o.last_7d_peak_w_media_calls; 
	// Recent peak
	row2.cells[4].innerHTML = o.system_peak_w_media_calls; 

	// 第三行
	row3.cells[1].innerHTML = o.current_wo_media_calls; // Currently
	// 24 hr peak
	row3.cells[2].innerHTML = o.last_24hr_peak_wo_media_calls; 
	// 7 days peak
	row3.cells[3].innerHTML = o.last_7d_peak_wo_media_calls; 
	// Recent peak
	row3.cells[4].innerHTML = o.system_peak_wo_media_calls; 

	// 第四行
	row4.cells[1].innerHTML = o.current_cps; // Currently
	// 24 hr peak
	row4.cells[2].innerHTML = o.last_24hr_peak_cps; 
	// 7 days peak
	row4.cells[3].innerHTML = o.last_7d_peak_cps; 
	// Recent peak
	row4.cells[4].innerHTML = o.system_peak_cps; 


	
	var currtab = document.getElementById("currentSys");
	var	crow1 = currtab.rows[0];// 第一行
	var	crow2 = currtab.rows[1];// 第二行

	crow1.cells[1].innerHTML = o.current_calls;
	crow1.cells[2].innerHTML = o.system_max_calls;

	crow2.cells[1].innerHTML = o.current_cps;
	crow2.cells[2].innerHTML = o.system_max_cps;
}


/*
 * 刷新数据
 */
function changeTime(){

	var value = jQuery("input[name='reTime']:checked").val();

	window.clearInterval(time);

	refreshPro();

	if(value==0){
		time = window.setInterval("refreshPro()",180000);
	}
	else if(value==1){
		time = window.setInterval("refreshPro()",300000);
	}
	else if(value==2){
		time = window.setInterval("refreshPro()",1500000);
	}
}

// 清除刷新
function clearTime(){
	window.clearInterval(time);
}
