
<style type="text/css">
.group-title.bottom {
-moz-border-radius:0 0 6px 6px;
border-top:1px solid #809DBA;
margin:15px auto 10px;
}
.list td.in-decimal {
text-align:center;
}

.value input, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select {
-moz-box-sizing:border-box;
width:100px;;
}

.list {
font-size:1em;
margin:0 auto 20px;
width: 100%;
}

#container .form {
margin:0 auto;
width:750px;
}
</style>
<div id="title">
            <h1>
<?php echo __('Ip Report',true);?>
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        

    <ul id="title-menu">



  </ul>

    

        

    </div>

<div id="container">
<ul class="tabs">

      <li ><a href="<?php echo $this->webroot?>monitorsreports/globalstats"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"><?php echo __('globlestatus')?></a></li>
      
    <li  ><a href="<?php echo $this->webroot?>monitorsreports/productstats"> <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('routestatus')?></a>  </li>
    
  
<?php $gress=$_SESSION['gress'];

if($gress=='ingress'){?>
 
     <li    class="active"><a href="<?php echo $this->webroot?>monitorsreports/ingress/ingress"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('ingress')?></a>        </li>
      <li  ><a href="<?php echo $this->webroot?>monitorsreports/egress/egress"><img width="16" height="16" src="<?php echo $this->webroot?>images/bNotes.gif"><?php echo __('egress')?></a>        </li>
 
<?php }else{?>

    <li   ><a href="<?php echo $this->webroot?>monitorsreports/ingress/ingress"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('ingress')?></a>        </li>
      <li   class="active"><a href="<?php echo $this->webroot?>monitorsreports/egress/egress"><img width="16" height="16" src="<?php echo $this->webroot?>images/bNotes.gif"><?php echo __('egress')?></a>        </li>


<?php }?>

       </ul>
       
       
       
        <div style="display: none" id="charts_holder">
        
        
         <?php //****总价格报表1************?>
 <div       class="group-title bottom"  >
 
 <a onclick="$('#charts_holder').toggle();return false;" href="#"><?php echo __('viewcharts')?></a>
 
 
 </div>  
  
<div id="chart_9be11_div" class="amChart">
<div id="chart_9be11_div_inner" class="amChartInner">

<script type="text/javascript" src="<?php echo $this->webroot?>amcolumn/swfobject.js"></script>
	<div id="flashcontent">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
		so.addVariable("path", "<?php echo  $this->webroot?>amstock/");

		//so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} EUR</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>Total Cost, EUR</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph></graphs></settings>"));
		//so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='Total Cost'><value xid='0'>88888.0</value><value xid='1'>755665.28</value></graph></graphs></chart>"));


		
		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_cost.xml"));
	//	so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_cost.xml"));
		//so.addVariable("preloader_color", "#ffffff");

		so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amstock/amstock_settings.xml"));
		so.write("flashcontent");//将flash放入div
		

		// ]]>
	</script>
<!-- end of amcolumn script -->

</div>
</div>

  </div>
  
  
  <?php //****************************分割线?>
 <div       class="group-title bottom"  >
 
 <a onclick="$('#charts_holder').toggle();return false;" href="#"><?php echo __('viewcharts')?></a>
 </div>  
  
  

 
  
<div style="width: 100%;; margin: 0px ">
 
    
       <fieldset><legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> <?php echo __('Historical',true);?></legend>

   <table class="list nowrap with-fields">
    <thead>
                <tr>
           <td   width="10%" rowspan="2" rel="0"  style="vertical-align: bottom;"><?php echo __('host',true);?></td>

               <td class="cset-1" colspan="4">15 <?php echo __('Min',true);?></td>
            <td colspan="4" class="cset-2">1 <?php echo __('hour',true);?></td>
            <td colspan="4" class="cset-3">
            <span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour',true);?></span>
            <span id="ht-100002-tooltip" class="tooltip"><?php __('Average successful rate (percent of successful calls)')?></span>
            
            </td>
    
        </tr>
        <tr>
            <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('acd',true);?>&nbsp;</td>
            <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('asr',true);?>&nbsp;</td>   
              <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('ca',true);?>&nbsp;</td>
            <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('pdd',true);?>&nbsp;</td> 
               
            <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('acd',true);?>&nbsp;</td>
            <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('asr',true);?>&nbsp;</td>   
              <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('ca',true);?>&nbsp;</td>
            <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('pdd',true);?>&nbsp;</td>    
            
            <td width="6%" class="cset-3" rel="10">&nbsp;<span id="ht-100009" class="helptip" rel="helptip"><?php echo __('acd',true);?></span><span id="ht-100009-tooltip" class="tooltip"><?php __('Value of respective parameter calculated as number of non-zero calls')?></span>&nbsp;</td>
           
               <td width="6%" class="cset-3 last" rel="10" >&nbsp;<span id="ht-100009" class="helptip" rel="helptip"><?php echo __('asr',true);?></span><span id="ht-100009-tooltip" class="tooltip"><?php __('Value of respective parameter calculated as number of non-zero calls')?></span>&nbsp;</td>
            <td width="6%" class="cset-3" rel="10" >&nbsp;<span id="ht-100009" class="helptip" rel="helptip"><?php echo __('ca',true);?></span><span id="ht-100009-tooltip" class="tooltip"><?php __('Value of respective parameter calculated as number of non-zero calls')?></span>&nbsp;</td>
           
               <td width="6%" class="cset-3 last" rel="10" >&nbsp;<span id="ht-100009" class="helptip" rel="helptip"><?php echo __('pdd',true);?></span><span id="ht-100009-tooltip" class="tooltip"><?php __('Value of respective parameter calculated as number of non-zero calls')?></span>&nbsp;</td>
           
        </tr>
    </thead>
    
                        <tbody class="orig-calls"   id='tbodyOfShowTable'>

                <tr class="row-2">
 <td class="in-decimal"><strong   >
        <span id="ht-100019" class="helptip" rel="helptip" style='color:#4B9100'>
     
  <?php  echo $ip;?>
  
  	</span>
            <span id="ht-100019-tooltip" class="tooltip"><?php echo __('ip',true);?></span>
            
 
				</strong></td>
 
   <td class="in-decimal"><?php echo $mydata[0][0]['acd_15min']?></td>
   <td class="in-decimal"><?php echo $mydata[0][0]['asr_15min']?></td>  
   <td class="in-decimal"><?php echo $mydata[0][0]['ca_15min']?></td>
  <td class="in-decimal"><?php echo $mydata[0][0]['pdd_15min']?></td>
  
        <td class="in-decimal"><?php echo $mydata[0][0]['acd_1h']?></td>
        <td class="in-decimal"><?php echo $mydata[0][0]['asr_1h']?></td>
        <td class="in-decimal"><?php echo $mydata[0][0]['ca_1h']?></td>
        <td  class="in-decimal"><?php echo $mydata[0][0]['pdd_1h']?></td>
        
        <td class="in-decimal"><?php echo $mydata[0][0]['acd_24h']?></td>
        <td  class="in-decimal"><?php echo $mydata[0][0]['asr_24h']?></td>
         <td  class="in-decimal"><?php echo $mydata[0][0]['ca_24h']?></td>
        <td  class="in-decimal last"><?php echo $mydata[0][0]['pdd_24h']?></td>
    </tr>
        
        
            </tbody>
          
                
 </table>

    </fieldset>
    
    
    </div>
      

</div>
<div>

</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
	<script   type="text/javascript" >
	//生成System Cap
	var  globalUrl='<?php echo $this->webroot?>';
	api_getsyslimit();
	//加载 history 表格的信息

		historycal(globalUrl+"monitors/history");//加载历史信息
 	
	
	</script>