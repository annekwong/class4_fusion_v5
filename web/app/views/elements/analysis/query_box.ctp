<div id="container"> 
  <!-- DYNAREA -->
  
  <?php

 		if($report_type==0){
	   	 echo  " <div class='msg'>Please select parameters for report</div>";
	}
 		if($report_type==1){
	   	 echo  $this->element("analysis/lcr");
	}
	 		if($report_type==2){
	   	 echo  $this->element("analysis/comparison");
	}
	?>
    <style type="text/css">
.form .label, .list-form .label{width:120px;}
.form .value, .list-form .value img {
	_float:left;	
}
.form .value, .list-form .value {
	width:auto;
	text-align:left;
}
.in-text, .value select, .value .in-text, .value .in-select {
	width:120px;
}
</style>
  <fieldset class="query-box">
  <div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php echo __('query',true);?>
  </div>
    <?php echo $this->element('search_report/search_js')?>
    <form   method="get" onsubmit="if ($('#query-output').val() == 'web') loading()">
      <input type="hidden" name="query[process]" value="1" id="query-process" class="input in-hidden">
      <table class="form" width="100%">

        <tbody>
          <tr>
            <td rowspan="4" colspan="2" class="value"><?php echo $this->element("analysis/checkbox_rate_table")?></td>
            <?php echo $this->element('search_report/search_code_or_code_name');?>
            <td rowspan="4" class="buttons">
			<?php  if ($_SESSION['role_menu']['Tools']['analysis']['model_r']&&$_SESSION['role_menu']['Tools']['analysis']['model_x']) {?>
            <input value="<?php echo __('query',true);?>" type="submit" class="input in-submit">
            <?php }?></td>
          </tr>
          <tr> <?php echo $this->element('search_report/search_country')?>
            <td class="label"><?php echo __('Report Type',true);?>:</td>
            <td class="value"><?php 
				 		$type=array('1'=>'LCR Table','2'=>'Rate Comparison');
				 		echo $form->input('report_type',
				 		array('options'=>$type,'name'=>'report_type','id'=>'CdrReportType','label'=>false ,'div'=>false,'type'=>'select'));
				 ?></td>
           
          </tr>
          <tr>
            <td class="label"><?php echo __('Get margins for',true);?>:</td>
            <td class="value"><?php 		echo $form->input('ingress_alias',		
	array('onchange'=>"change_prefix_ingress(this,'prefix')" ,'options'=>$ingress,'name'=>'ingress_alias',
'id'=>'CdrIngressAlias','label'=>false ,'div'=>false,'type'=>'select'));		 ?></td>
            <td class="label"><?php echo __('Actual on (Date)',true);?>:</td>
            <td class="value"><input type="text" name="effective_date" 
    value='<?php if(!empty($_GET['effective_date'])){  echo $_GET['effective_date']; }?>'
      class="input in-text wdate"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"  /></td>
          </tr>
          <tr>
            <td class="label"><?php echo __('Currency',true);?>:</td>
            <td class="value"><?php echo $form->input('currency',	array('options'=>$currency,'empty'=>'','name'=>'currency','id'=>'CdrCurrency','label'=>false ,'div'=>false,'type'=>'select'));?></td>
            <td></td><td></td>
          </tr>
        </tbody>
      </table>
    </form>
  </fieldset>
  <?php echo $this->element('search_report/search_js_show')?> 
  <script type="text/javascript">
//<![CDATA[


function change_prefix_ingress(ingress,prefix){
	var host = document.getElementById(prefix);
	host.options.length = 0;
	if (ingress.value.length >= 1) {
	jQuery.getJSON("<?php echo $this->webroot?>analysis/get_prefix_by_resource?r_id="+ingress.value,{},function(data){			
			var datas = data;//eval(data);
			var loop = datas.length;	
			for (var i = 0;i<loop;i++) {
				
				var d = datas[i];						
				var option = document.createElement("option");
				option.innerHTML = d.prefix;	
				option.value = d.prefix;				
				host.appendChild(option);
			}
		});
	}
}

function checkall(t) 
{
    $(t).parent().parent().find(':checkbox').attr('checked', $(t).attr('checked'));
}



//]]>
</script> <!-- DYNAREA --> 
</div>
