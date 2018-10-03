
 <!--Tools>>SIP Capture页面用到的-->
<link rel="stylesheet" href="<?php echo $this->webroot?>css/ui.tabs.css" type="text/css">
  <script src="<?php echo $this->webroot?>js/jquery-tab.js" type="text/javascript"></script> 
  <script src="<?php echo $this->webroot?>js/jquery-tab-ui.core.js" type="text/javascript"></script> 
  <script src="<?php echo $this->webroot?>js/jquery-tab.tabs.js" type="text/javascript"></script>
<!--//Tools>>SIP Capture页面用到的--> 
<div id="title">
  <h1>
    <?php __('Tools')?>
    &gt;&gt;<?php echo __('Ladder Diagram',true);?></h1>
</div>
<div class="container">

  <div id="rotate">
    <ul>
      <li><a href="#fragment-1"><span><?php echo __('Ladder Diagram',true);?></span></a></li>
      <li><a href="#fragment-2"><span><?php echo __('Call Info',true);?></span></a></li>
      <li><a href="#fragment-3"><span><?php echo __('Media',true);?></span></a></li>
    </ul>
    
    <!--ladder content-->
    <div class="ladder_content">
      <div id="fragment-1">
        <div class="ladder_left">
            <table id="tableLeftHeaderTable" cellspacing="0" cellpadding="0">
              <tr class="arrowRow">
                <td>&nbsp;</td>
              </tr>
              <tr class="textRow">
                <td><?php __('UDP Frame 1 25/Jul/11 17:24:10.3774')?></td>
              </tr>
              
            </table>
          </div>
        
        <div class="ladder_middle">
          <div class="ladder_top">
          <table cellspacing="0" cellpadding="0">
            <tbody>
              <tr>
                <td class="halfTd">&nbsp;</td>
                <td class="node" title="173.205.112.237:: Port(s): 5060; <br>">173.205.112.237</td>
                <td class="node" title="207.223.64.148:: Port(s): 5060, 48932; <br>">207.223.64.148</td>
                <td class="node" title="66.234.135.60:: Port(s): 48932; <br>">66.234.135.60</td>
                <td class="node" title="2.137.1; "><?php __('CHCHILSGX')?></td>
                <td class="node" title="2.137.100; "><?php __('PN-ALIAS')?></td>
                <td>245.175.254</td>
                <td class="node" title="207.223.64.133:: Port(s): 17250; <br>">207.223.64.133</td>
                <td>2.137.7</td>
                <td>253.192.92</td>
                
                <td style="border: none;">&nbsp;</td>
                <td style="border: none;">&nbsp;</td>
                <td style="border: none;">&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>
          <div class="ladder_right">
          	
            <table id="mainTable" cellspacing="0" cellpadding="0">
              <tr class="textRow">
                <td class="halfTd">&nbsp;</td>
                <td><div class="textTitle" id="title0"><a href="#title0" onclick="showInfo('title0');"><?php __('F1 INVITE (sdp)')?></a></div>
                  &nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="halfTd" style="border-right: 0;">&nbsp;</td>
                <td style="border: none;">&nbsp;</td>
                <td style="border: none;">&nbsp;</td>
                <td style="border: none;">&nbsp;</td>
              </tr>
              <tr class="arrowRow">
                <td class="halfTd">&nbsp;</td>
                <td colspan="1" class="arrow" id="arrow0"><div style="width: 150px; height: 12px">
                    <div class="arrow-cap" style="background: blue; height: 12px; width: 4px;"></div>
                    <div class="arrow-line" style="width: 137px; height: 5px; border-width: 0 0 2px 0; border-style: none none solid none; border-color: blue;"></div>
                    <div class="arrow-point" style="border-color: #eee blue; border-width: 6px 0px 6px 9px;"></div>
                  </div></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="halfTd" style="border-right: 0;">&nbsp;</td>
              </tr>
             
            </table>
          	
          </div>
          <div class="click_scroll">
                <div class="vleft"><strong> << </strong></div>
                <div class="vright"><strong> >> </strong></div>
            </div>
        </div>
        
        <!--点击后显示详细-->
        <div class="ladder_bottom" id="ladder_bottom">
          <ul id="com_tags">
            <li style="display:none;"><a onclick="selectTag('tagContent0',this)" href="javascript:void(0)"><?php __('hex')?></a> </li>
            <li class="selectTag" id="dafault_select"><a onclick="selectTag('tagContent1',this)" href="javascript:void(0)"><?php __('packet properties')?></a> </li>
            <li><a onclick="selectTag('tagContent2',this)" href="javascript:void(0)"><?php __('text')?></a> </li>
            <li><a onclick="close_bottom();" href="javascript:void(0)" style="color:#f00; font-weight:bold;"><?php __('Close')?></a> </li>
          </ul>
          <div id="tagContent">
            <div class="tagContent" id="tagContent0"> 
                <div id="title0_hex" class="tabBoxContent hide" ><?php __('hex1')?></div>
                <div id="title2_hex" class="tabBoxContent hide" ><?php __('hex2')?></div>
                <div id="title3_hex" class="tabBoxContent hide" ><?php __('hex3')?></div>
            </div>
            
            <div class="tagContent selectTag" id="tagContent1">
                <div id="title0_info" class="tabBoxContent hide" ><?php __('content1')?></div>
                <div id="title2_info" class="tabBoxContent hide"><?php __('content2')?></div>
                <div id="title3_info" class="tabBoxContent hide"><?php __('content3')?></div>
            </div>
            <div class="tagContent" id="tagContent2">
                <div id="title0_text" class="tabBoxContent hide" ><?php __('text1')?></div>
                <div id="title2_text" class="tabBoxContent hide" ><?php __('text2')?></div>
                <div id="title3_text" class="tabBoxContent hide" ><?php __('text3')?></div>
            </div>
          </div>
        </div>
        <!--//点击后显示详细--> 
      </div>
      <div id="fragment-2"><?php echo __('Call Info',true);?></div>
      <div id="fragment-3"><?php echo __('Media',true);?></div>
    </div>
    <!--//ladder content--> 
  </div>
  
  
  <script type="text/javascript">
  //顶部tab切换
	$(function() {
		$('#rotate > ul').tabs({ fx: { opacity: 'toggle' } }).tabs('rotate', 0);//0不自动，设有值会自动切换2000
		
	});
//尾部tab切换
function selectTag(showContent,selfObj){
	// 操作标签
	var tag = document.getElementById("com_tags").getElementsByTagName("li");
	var taglength = tag.length;
	for(i=0; i<taglength; i++){
		tag[i].className = "";
	}
	selfObj.parentNode.className = "selectTag";
	// 操作内容
	for(i=0; j=document.getElementById("tagContent"+i); i++){
		j.style.display = "none";
	}
	document.getElementById(showContent).style.display = "block";
}
</script>
  <script type="text/javascript">
 /*$(document).ready(function() {
	$('#mainTable .textTitle').each(function(){
		$(this).click(function(){
			$("#ladder_bottom").css("display","");
			
			$("#"+$(this).attr('id')+"_hex").css("display","block");
			$("#"+$(this).attr('id')+"_hex").siblings().css("display","none");
			
			$("#"+$(this).attr('id')+"_info").css("display","block");
			$("#"+$(this).attr('id')+"_info").siblings().css("display","none");
			
			$("#"+$(this).attr('id')+"_text").css("display","block");
			$("#"+$(this).attr('id')+"_text").siblings().css("display","none");
		});
		
	});
 });
*/
function showInfo(showContent){
	var $href = $(this).attr('href');
	if($href==0){
		$("#"+showContent+"_hex").css("display","none");
		$("#"+showContent+"_hex").siblings().css('display','none');
		
		$("#"+showContent+"_info").css("display","none");
		$("#"+showContent+"_info").siblings().css('display','none');
		
		$("#"+showContent+"_text").css("display","none");
		$("#"+showContent+"_text").siblings().css('display','none');
	}else{
		$("#ladder_bottom").css("display","");
		
		$("#"+showContent+"_hex").css("display","block");
		$("#"+showContent+"_hex").siblings().css('display','none');
		
		$("#"+showContent+"_info").css("display","block");
		$("#"+showContent+"_info").siblings().css('display','none');
		
		$("#"+showContent+"_text").css("display","block");
		$("#"+showContent+"_text").siblings().css('display','none');
	}
}

function close_bottom(){
	$("#ladder_bottom").css("display","none");
}
  </script>

<script language="javascript">
$(function(){

	var i = 5;  //定义每个面板显示8个菜单
	var len = $(".ladder_top table td").length;  //获得LI元素的个数
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
			}
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
</script>
</div>

<style type="text/css">
/*新样式*/

/*ladder*/
#rotate {
}
.ladder_content {
	clear:both;
	margin-bottom:10px;
	height:auto;
	overflow:auto;
}

#fragment-1, #fragment-2, #fragment-3 {
	background: none repeat scroll 0 0 #EEEEEE;
	overflow: hidden;
	padding: 10px 0px;
}
.ladder_top {
	border-left: 1px solid black;
	border-right: 1px solid black;
	border-top: 1px solid black;
	height: 2em;
	/*
	margin-left:219px;
	_left:219px;
	*/
	left:219px;
	overflow: hidden !important;
	position: relative;
	width:750px;
	_width:745px;
}
.ladder_top table {
	border-spacing: 0;
	left: -75px;
	position: absolute;
	table-layout: fixed;
	width: 775px;
}
.ladder_top .halfTd {
	width:75px;
}
.ladder_top td {
	display:table-cell;
	width:150px;
	border-width:0;
	border-right:5px solid #eee;
	text-align:center;
	padding:0;
	margin:0;
	vertical-align:top;
}
.ladder_top .node, .tip-title {
	color:#09f;
	cursor:default;
	text-decoration:underline;
	height:25px;
	line-height:25px;
}
.ladder_middle {
	height:auto;
	overflow:hidden;
}
.ladder_left {
	position:absolute;
	height:500px;
	width:219px;
	margin-top:25px;
	padding:0;
	overflow:auto;
	border-bottom:1px solid black;
	border-top:1px solid black;
	border-left:1px solid black;
}
#tableLeftHeaderTable {
	position:absolute;
	table-layout:fixed;
	border-spacing:0;
	width:100%;
	margin:0;
}
#tableLeftHeaderTable td {
	text-align:center;
}
.ladder_right {
	height:500px;
	/*
	margin-left:219px;
	_left:219px;
	*/
	left:219px;
	border:1px solid black;
	overflow:scroll;
	overflow-x:hidden;
	position:relative;
	width:750px;
	_width:745px;
}
.ladder_right table {
	position:relative;
	table-layout:fixed;
	border-spacing:0;
	width:775px;
}
.ladder_right td {
	width:150px;
	border-width:0;
	border-right:5px solid #f93;
	vertical-align:bottom;
	padding:0;
	margin:0;
	vertical-align:bottom;
}
.ladder_right td div.textTitle {
	position:absolute;
	background:#eee;
	white-space:nowrap;
	padding:0 5px;
}
.ladder_right .halfTd {
	width:75px;
}
.textRow {
	height:2.5em;
}
.arrowRow {
	height:12px;
	font-size:0;
}
.front {
	position:relative;
	z-index:100;
}
.arrow {
	cursor:pointer;
}
.arrow-point {
	float:left;
	width:0;
	height:0;
	padding:0;
	margin:0;
	line-height:0;
	font-size:0;
	border-style:solid;
	border-width:0;
}
.arrow-line {
	float:left;
	border-style:none none solid none;
	margin:0;
	padding:0;
	font-size:0;
}
.arrow-cap {
	float:left;
	font-size:0;
	padding:0;
	margin:0;
}
/*底下信息*/
.ladder_bottom {
	background:#eee;
	padding:10px;
	clear:both;
}

.tabHolder {
	position:relative;
	width:100%;
	height:21px;
	overflow:visible;
	margin:0;
	padding:0;
	overflow:visible;
}
.tabHolder .tab {
	position:relative;
	float:left;
	margin:0 10px;
	text-align:center;
	padding:0 4px;
	min-width:100px;
	height:20px;
	border-color:black;
	border-style:solid;
	border-width:1px 1px 0 1px;
	cursor:pointer;
	background:#ddd;
	bottom:0;
	-moz-border-radius-topleft:4px;
	-webkit-border-top-left-radius:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-right-radius:4px;
}
.tabHolder .tab:hover {
	background:#eee;
}
.tabHolder .current {
	position:relative;
	bottom:-1px;
	background:#eee;
}
#bottomTabHolder .current {
	background:#E9E9E9;
}
#com_tags {
	PADDING: 0px;
	MARGIN-left:10px;
	WIDTH: auto;
	HEIGHT: 25px;
}
#com_tags li {
	float:left;
	margin:0 10px;
	text-align:center;
	padding:0 4px;
	min-width:100px;
	height:25px;
	line-height:25px;
	border:1px solid #AECBD4;
	border-bottom:none;
	cursor:pointer;
	background:url("..images/list-head-bg.png") repeat-x scroll center bottom #FFFFFF;
	-moz-border-radius-topleft:4px;
	-webkit-border-top-left-radius:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-right-radius:4px;
	
}
#com_tags li a {
	PADDING:0px 10px;
	COLOR: #999;
	HEIGHT: 25px;
	LINE-HEIGHT: 25px;
	TEXT-DECORATION: none;
	display:block;
	_display:inline-block;
}
#com_tags li.emptyTag {
	WIDTH: 4px;
}
#com_tags li.selectTag {
	HEIGHT: 25px;
	line-height:25px;
	margin-bottom:-2px;
	background:#fff;
	display:block;
}
#com_tags li.selectTag a {
	COLOR: #000;
	HEIGHT: 25px;
	LINE-HEIGHT: 25px;
}
#tagContent {
	BORDER:1px solid #aecbd4;
	PADDING: 1px;
	padding: 10px;
	BACKGROUND: #fff;
}
.tagContent {
	DISPLAY: none;
	COLOR: #474747;
}
#tagContent DIV.selectTag {
	DISPLAY: block;
}
.tabBoxContent {
	display:none;
}
.hide {
	width:auto;
	height:auto;
	display:none;
}
/*点击左右滚动*/
.click_scroll {
	clear:both;
	height:20px;
	line-height:20px;
	margin-top:-25px;
	/*
	margin-left:219px;
	_left:219px;
	*/
	left:239px;
	_left:233px;
    position:absolute;
	width:710px;
	_width:700px;
}
.vleft {
	padding:0px 2px;
	float:left;
	background:#ccc;
	display:block;
	font-size:16px;
}
.vright {
	padding:0px 2px;
	float:right;
	background:#ccc;
	display:block;
	font-size:16px;
}
#div1 {
	width:130px;
	height:15px;
	border:1px #CCCCCC solid;
	border-radius:4px;
	position:absolute;
	display:none;
	background:#CCCCCC;
	padding:2px;
	font-size:12px;
	color:#f00;
}
/*//点击左右滚动*/
/*//ladder*/
/*//新样式*/
</style>