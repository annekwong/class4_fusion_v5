<style type="text/css">

.list td.in-decimal {
text-align:center;
}
</style>
<div id="title">
            <h1>

      <?php echo __('clientbalance');  ?>
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
    </div>

<div id="container">

<ul id="stats-extra"  style="font-weight: bolder;font-size: 1.1em;color: #6694E3">
    <li id="stats-period">
    <span rel="helptip" class="helptip" id="ht-100012"><?php echo __('reporttime')?></span>
    <span class="tooltip" id="ht-100012-tooltip">Period for which statistics exists in database</span>:
    <span><?php  echo $start;?></span> &mdash; <span><?php echo $end?></span></li>
      <li id="stats-time"><?php echo __('querytook')?>: 2.2691 <?php echo __('second')?></li></ul>



       <?php   if(!empty($post)){?>
      <ul id="stats-extra"  style="font-weight: bolder;font-size: 1.1em;color: #6694E3">
    <li id="stats-period">
    <span rel="helptip" class="helptip" id="ht-100012"></span>
    <span class="tooltip" id="ht-100012-tooltip"><?php __('Period for which statistics exists in database')?></span>
    <span><?php echo __('Reseller')?></span> &mdash; <span><?php echo __('count')?></span></li>
      <li id="stats-time"></li></ul>

<table class="list nowrap with-fields">
    <thead>

        <tr> <td width="6%" rel="2" class="cset-1">&nbsp;<?php echo __('Reseller')?>&nbsp;</td>
            <td width="6%" rel="2" class="cset-1">&nbsp;<?php echo __('totalamount')?>&nbsp;</td>
            <td width="6%" rel="3" class="cset-1">&nbsp;<?php __('totalcostcdr')?>&nbsp;</td>
                    <td width="6%" rel="4" class="cset-2">
                   &nbsp;<?php __('profit')?>&nbsp;</td>
            <td width="6%" rel="5" class="cset-2">
          &nbsp;<span rel="helptip" class="helptip" id="ht-100004"><?php __('duration')?></span>
          <span class="tooltip" id="ht-100004-tooltip"><?php __('duration')?></span>&nbsp;</td>
            <td width="6%" rel="6" class="cset-2">
          &nbsp;<span rel="helptip" class="helptip" id="ht-100005"><?php __('bill_time')?></span>
          <span class="tooltip" id="ht-100005-tooltip"><?php __('Calls with Q.931 disconnect cause = 16 or 31')?></span>&nbsp;
       </td>
            <td width="6%" rel="7" class="cset-2">
           &nbsp;<span rel="helptip" class="helptip" id="ht-100006"><?php __('cdrcount')?></span><span class="tooltip" id="ht-100006-tooltip"><?php __('Calls with Q.931 disconnect cause = 17')?></span>&nbsp;</td>
            <td  width="6%" rel="8" class="cset-2">&nbsp;<span rel="helptip" class="helptip" id="ht-100007"><?php __('package_amount')?></span><span class="tooltip" id="ht-100007-tooltip"><?php __('Calls with Q.931 disconnect cause = 34')?></span>&nbsp;</td>

            <td width="6%"   colspan="2" rel="10" class="cset-3">&nbsp;<span rel="helptip" class="helptip" id="ht-100009"><?php __('rateamount')?></span><span class="tooltip" id="ht-100009-tooltip"><?php __('Value of respective parameter calculated as number of non-zero calls')?></span>&nbsp;</td>

        </tr>
    </thead>

                        <tbody class="orig-calls">
                <?php

                $size=count($post);
                for($i=0;$i<$size;$i++){?>
                <tr class="row-2">
                <td class="in-decimal"><strong><?php  echo  $post[$i][0]['name']; ?> </strong></td>
 <td class="in-decimal"><strong><?php  echo  number_format($post[$i][0]['r_income'], 3); ?> <?php echo __('RMB',true);?></strong></td>
<td class="in-decimal"><?php  echo  number_format($post[$i][0]['r_total_cost'], 3); ?><?php echo __('RMB',true);?></td>
 <td class="in-decimal"><?php  echo  number_format($post[$i][0]['r_profit'], 3); ?><?php echo __('RMB',true);?></td>
        <td class="in-decimal"><?php  echo  number_format($post[$i][0]['r_call_duration'], 0); ?></td>
        <td class="in-decimal"><?php  echo  number_format($post[$i][0]['r_bill_time'], 0); ?></td>
         <td class="in-decimal"><?php  echo  number_format($post[$i][0]['r_cdr_count'], 0); ?></td>

        <td class="in-decimal"><?php  echo  number_format($post[$i][0]['reseller_package'], 3); ?></td>
        <td class="in-decimal"><?php  echo number_format(($post[$i][0]['r_income']-$post[$i][0]['reseller_package']),3) ?></td>


    </tr>
        <?php }?>

            </tbody></table>


 <?php }?>





  <?php if(!empty($post2)){?>
       <ul id="stats-extra"  style="font-weight: bolder;font-size: 1.1em;color: #6694E3">
    <li id="stats-period">
    <span rel="helptip" class="helptip" id="ht-100012"></span>
    <span class="tooltip" id="ht-100012-tooltip"><?php __('Period for which statistics exists in database')?></span>
    <span><?php echo __('pifa')?></span> &mdash; <span><?php echo __('count')?></span></li>
      <li id="stats-time"></li></ul>

<table class="list nowrap with-fields">
    <thead>

        <tr> <td width="6%" rel="2" class="cset-1">&nbsp;<?php echo __('pifa')?>&nbsp;</td>
            <td width="6%" rel="2" class="cset-1">&nbsp;&nbsp;<?php echo __('egressfees')?></td>
            <td width="6%" rel="3" class="cset-1">&nbsp;<?php echo __('totalamount')?>&nbsp;</td>
                    <td width="6%" rel="4" class="cset-2">
                   &nbsp;<?php __('profit')?>&nbsp;</td>
            <td width="6%" rel="5" class="cset-2">
          &nbsp;<span rel="helptip" class="helptip" id="ht-100004"><?php __('duration')?></span>
          <span class="tooltip" id="ht-100004-tooltip"><?php __('duration')?></span>&nbsp;</td>
            <td width="6%" rel="6" class="cset-2">
          &nbsp;<span rel="helptip" class="helptip" id="ht-100005"><?php __('bill_time')?></span>
          <span class="tooltip" id="ht-100005-tooltip"><?php __('Calls with Q.931 disconnect cause = 16 or 31')?></span>&nbsp;
       </td>
            <td width="6%" rel="7" class="cset-2">
           &nbsp;<span rel="helptip" class="helptip" id="ht-100006"><?php __('cdrcount')?></span><span class="tooltip" id="ht-100006-tooltip"><?php __('Calls with Q.931 disconnect cause = 17')?></span>&nbsp;</td>

        </tr>
    </thead>

                        <tbody class="orig-calls">
                <?php

                $size=count($post2);
                for($i=0;$i<$size;$i++){?>
                <tr class="row-2">
                <td class="in-decimal"><strong><?php  echo  $post2[$i][0]['name']; ?> </strong></td>
 <td class="in-decimal"><strong><?php  echo  number_format($post2[$i][0]['r_income'], 3); ?> <?php echo __('RMB',true);?></strong></td>
       <td class="in-decimal"><?php  echo  number_format($post2[$i][0]['r_total_cost'], 3); ?></td>
<td class="in-decimal"><?php  echo number_format( $post2[$i][0]['r_income']-$post2[$i][0]['r_total_cost'],3); ?><?php echo __('RMB',true);?></td>
  <td class="in-decimal"><?php  echo  number_format($post2[$i][0]['r_call_duration'], 0); ?></td>

        <td class="in-decimal"><?php  echo  number_format($post2[$i][0]['r_bill_time'], 0); ?></td>
         <td class="in-decimal"><?php  echo  number_format($post2[$i][0]['r_cdr_count'], 0); ?></td>

    </tr>
        <?php }?>

            </tbody></table>
 <?php }?>


<?php if(!empty($post3)){?>
   <ul id="stats-extra"  style="font-weight: bolder;font-size: 1.1em;color: #6694E3">
    <li id="stats-period">
    <span rel="helptip" class="helptip" id="ht-100012"></span>
    <span class="tooltip" id="ht-100012-tooltip"><?php __('Period for which statistics exists in database')?></span>
    <span><?php echo __('account')?></span> &mdash; <span><?php echo __('count')?></span></li>
      <li id="stats-time"></li></ul>

<table class="list nowrap with-fields">
    <thead>

        <tr> <td width="6%" rel="2" class="cset-1">&nbsp;<?php echo __('card_number')?>&nbsp;</td>
            <td width="6%" rel="2" class="cset-1">&nbsp;<?php echo __('setup_fee')?>&nbsp;</td>

            <td width="6%" rel="5" class="cset-2">
          &nbsp;<span rel="helptip" class="helptip" id="ht-100004"><?php __('duration')?></span>
          <span class="tooltip" id="ht-100004-tooltip"><?php __('duration')?></span>&nbsp;</td>
            <td width="6%" rel="6" class="cset-2">
          &nbsp;<span rel="helptip" class="helptip" id="ht-100005"><?php __('bill_time')?></span>
          <span class="tooltip" id="ht-100005-tooltip"><?php __('Calls with Q.931 disconnect cause = 16 or 31')?></span>&nbsp;
       </td>
            <td width="6%" rel="7" class="cset-2">
           &nbsp;<span rel="helptip" class="helptip" id="ht-100006"><?php __('cdrcount')?></span><span class="tooltip" id="ht-100006-tooltip"><?php __('Calls with Q.931 disconnect cause = 17')?></span>&nbsp;</td>
            <td  width="6%" rel="8" class="cset-2">&nbsp;<span rel="helptip" class="helptip" id="ht-100007"><?php __('package_amount')?></span><span class="tooltip" id="ht-100007-tooltip"><?php __('Calls with Q.931 disconnect cause = 34')?></span>&nbsp;</td>

            <td width="6%"   colspan="2" rel="10" class="cset-3">&nbsp;<span rel="helptip" class="helptip" id="ht-100009"><?php __('rateamount')?></span><span class="tooltip" id="ht-100009-tooltip"><?php __('Value of respective parameter calculated as number of non-zero calls')?></span>&nbsp;</td>

        </tr>
    </thead>

                        <tbody class="orig-calls">
                <?php

                $size=count($post3);
                for($i=0;$i<$size;$i++){?>
                <tr class="row-2">
                <td class="in-decimal"><strong><?php  echo  $post3[$i][0]['card_number']; ?> </strong></td>

<td class="in-decimal"><?php  echo  number_format($post3[$i][0]['r_total_cost'], 3); ?><?php echo __('RMB',true);?></td>

        <td class="in-decimal"><?php echo number_format($post3[$i][0]['r_call_duration'],0)?></td>
        <td class="in-decimal"><?php echo number_format($post3[$i][0]['r_bill_time'],0)?></td>
         <td class="in-decimal"><?php  echo  number_format($post3[$i][0]['r_cdr_count'],0); ?></td>

        <td class="in-decimal"><?php  echo  number_format($post3[$i][0]['reseller_package'], 3); ?></td>
        <td class="in-decimal"><?php  echo number_format(($post3[$i][0]['r_total_cost']-$post3[$i][0]['reseller_package']),3) ?></td>


    </tr>
        <?php }?>

            </tbody></table>


 <?php }?>




 <div class="group-title bottom">
 <img width="16" height="16" src="<?php echo $this->webroot?>images/charts.png">
 <a onclick="$('#charts_holder').toggle();return false;" href="#"><?php echo __('viewcharts')?> »</a>
 </div>





 <?php //***********************报表查询参数*********************?>

 <?php //***********************报表查询参数*********************?>
<fieldset class="query-box"><legend><?php __('search')?></legend>
<?php echo $form->create ('Cdr', array ('url' => '/settlementreports/settlement_report/' ,'onsubmit'=>""));?>
<!--<form   action="<?php echo $this->webroot?>summaryreports/location_cdr_report/"   method="post">
--><input   type="hidden"   value="searchkey"    name="searchkey"/>
<table style="width: 960px;" class="form">
<col style="width: 80px;">
<col style="width: 235px;">

<tbody>

<tr class="period-block">
    <td class="label"><?php __('time')?>:</td>
    <td colspan="5" class="value">



    <table class="in-date"><tbody>
    <tr>

    <td>

    <table class="in-date">
<tbody>


<tr>
<td style="padding-right: 15px;">
    <select class="input in-select" name="smartPeriod" onchange="setPeriod(this.value)" id="query-smartPeriod"  style="width: 230px;">
    <option value="custom"><?php echo __('custom')?></option>
    <option value="curDay" selected="selected"><?php echo __('today')?></option>
    <option value="prevDay"><?php echo __('yesterday')?></option>
    <option value="curWeek"><?php echo __('currentweek')?></option>
    <option value="prevWeek"><?php echo __('previousweek')?></option>
    <option value="curMonth"><?php echo __('currentmonth')?></option>
    <option value="prevMonth"><?php echo __('previousmonth')?></option>
    <option value="curYear"><?php echo __('currentyear')?></option>
    <option value="prevYear"><?php echo __('previousyear')?></option></select></td>
    <td><input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
         value="" name="start_date"  style="margin-left: 64px; width: 206px;"></td>
    <td></td>
</tr>






</tbody></table>

    </td>
    <td><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
    	readonly="readonly"
         style="width: 60px;" value="00:00:00" name="start_time" class="input in-text"></td>
    <td>&mdash;</td>
    <td><table class="in-date">
<tbody><tr>
    <td><input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')"
    readonly="readonly"
     onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
    <td></td>
</tr>
</tbody></table>

    </td>
    <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
    readonly="readonly"
     onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text"></td>
    </tr></tbody></table>

</td>
    <td class="buttons"><input type="submit" value="Query" class="input in-submit"></td>
</tr>





<!--<tr>
    <td class="label">帐户卡号 :</td>
    <td id="client_cell" class="value">

     <?php echo $form->input('account_name',array('label'=>false,'div'=>false,'type'=>'text'))?>

    </td>

    <td class="label">代理商名:</td>
    <td class="value">
      <?php echo $form->input('org_source_number',array('label'=>false,'div'=>false,'type'=>'text'))?>

    </td>

    <td class="label"> 批发名 :</td>
    <td class="value">
      <?php echo $form->input('dest_source_number',array('label'=>false,'div'=>false,'type'=>'text'))?>

    </td>
</tr>


--><tr>
    <td class="label"> <?php echo __('Reseller')?>:</td>
    <td class="value">
<select id="CdrResellerId" name="data[Cdr][reseller_id]">
<?php
							for ($i=0;$i<count($r_reseller);$i++){
						?>
								<option value="<?php echo $r_reseller[$i][0]['reseller_id']?>">
									<?php
										$space = "";
										for ($j=0;$j<$r_reseller[$i][0]['spaces'];$j++) {
											 	$space .= "&nbsp;&nbsp;";
										}
										if ($i==0){
											echo "{$r_reseller[$i][0]['name']}";
										} else {
											echo "&nbsp;&nbsp;".$space."↳".$r_reseller[$i][0]['name'];
										}
									?>
								</option>
							<?php
								}
							?>
</select>
    </td>

    <td class="label"><span rel="helptip" class="helptip" id="ht-100013"><?php echo __('pifa')?></span><span class="tooltip" id="ht-100013-tooltip"><b><?php __('Use pre-assigned')?></b> &mdash; <?php __('means usage of code decks assigned to each pulled client or rate table')?>.<br><br><?php __('If you will')?> <b><?php __('specify')?></b> <?php __('a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names')?>.</span>:</td>
    <td class="value">

        		<?php
   		echo $form->input('client_id',
 		array('options'=>$client,'empty'=>__('select',true),'label'=>false ,
 	'title'=>__('pleaseaddegress',true),
 		'div'=>false,'type'=>'select'));?>
    </td>

        <td class="label">帐户:</td>
    <td class="value">
 		<?php echo $form->input('account_id',
 		array('options'=>$account,'empty'=>__('select',true),'label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
    </tr>



<tr>
    <td class="label"><?php echo __('conndis')?> :</td>
    <td id="client_cell" class="value">

    		<?php
$arr_cdr=array('1'=>__('conn',true),'2'=>__('calldis',true));
    		echo $form->input('call_type',
 		array('options'=>$arr_cdr,'empty'=>__('select',true),'label'=>false ,'div'=>false,'type'=>'select'));?>


    </td><!--

    <td class="label">代理商名:</td>
    <td class="value">
      <?php echo $form->input('org_source_number',array('label'=>false,'div'=>false,'type'=>'text'))?>

    </td>

    <td class="label"> 批发名 :</td>
    <td class="value">
      <?php echo $form->input('dest_source_number',array('label'=>false,'div'=>false,'type'=>'text'))?>

    </td>
--></tr>

</tbody></table>
<?php echo $form->end();?>
</fieldset>





 <?php //*******************************flash报表*****start********************************?>
 <div style="display: none;" id="charts_holder">


         <?php //****总价格报表1************?>

<div id="chart_9be11_div" class="amChart">
<div id="chart_9be11_div_inner" class="amChartInner">

<script type="text/javascript" src="<?php echo $this->webroot?>amcolumn/swfobject.js"></script>
	<div id="flashcontent">
		<strong><?php __('You need to upgrade your Flash Player')?></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} EUR</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>Total Cost, EUR</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='Total Cost'><value xid='0'>88888.0</value><value xid='1'>755665.28</value></graph></graphs></chart>"));



		so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_cost.xml"));
		so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_cost.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent");
		// ]]>
	</script>
<!-- end of amcolumn script -->




</div>
</div>




 <?php //****每分钟收费报表2***********?>

<div id="chart_d8ee4_div" class="amChart">
<div id="chart_d8ee4_div_inner" class="amChartInner">
<!-- saved from url=(0013)about:internet -->
<!-- amcolumn script-->

	<div id="flashcontent1">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");


		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} min</balloon_text><grow_time>0</grow_time><type>3d column</type></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>Time (Total/Billed) origination</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='Billed Time'><value xid='0'>79330.22</value><value xid='1'>272519.48</value></graph><graph gid='1' title='Total Time'><value xid='0'>79330.22</value><value xid='1'>272686.4</value></graph></graphs></chart>"));

		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_time.xml"));
		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_time.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent1");
		// ]]>
	</script>
<!-- end of amcolumn script -->

</div>
</div>



                     <?php //****asr   报表3***********?>
<div id="chart_a4ecd_div" class="amChart">
<div id="chart_a4ecd_div_inner" class="amChartInner">

	<div id="flashcontent3">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} %</balloon_text><grow_time>0</grow_time><type>3d column</type></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>ASR, %</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='ASR Cur'><value xid='0'>20.38</value><value xid='1'>43.85</value></graph><graph gid='1' title='ASR Std'><value xid='0'>66.4</value><value xid='1'>85</value></graph></graphs></chart>"));
		//	so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_asr.xml"));
		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_asr.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent3");
		// ]]>
	</script>
</div>
</div>




                                <?php //****acd报表4***********?>

<div id="chart_8671f_div" class="amChart">
<div id="chart_8671f_div_inner" class="amChartInner">

	<div id="flashcontent4">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");


		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} min</balloon_text><grow_time>0</grow_time><type>3d column</type></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>ACD, min</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='ACD Cur'><value xid='0'>3.19</value><value xid='1'>1.56</value></graph><graph gid='1' title='ACD Std'><value xid='0'>1.38</value><value xid='1'>0.91</value></graph></graphs></chart>"));


		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_acd.xml"));

		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_acd.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent4");
		// ]]>
	</script>


</div>
</div>




            <div style="width: 100%;">
       <div style="width: 33%; float: left;">

  <?php //****打进的call报表5***********?>

<div id="chart_4c649_div" class="amChart">
<div id="chart_4c649_div_inner" class="amChartInner">
	<div id="flashcontent5">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/ampie.swf", "amcolumn", "100%", "400", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><background><alpha>100</alpha><border_alpha>20</border_alpha></background><balloon><show>{title}: {value} ({percents}%)</show><text_color>000000</text_color><text_size>15</text_size><max_width>400</max_width><corner_radius>6</corner_radius></balloon><legend><align>center</align></legend><pie><inner_radius>40</inner_radius><height>20</height><angle>20</angle><outline_alpha>50</outline_alpha><alpha>80</alpha><hover_brightness>30</hover_brightness><gradient_ratio>-50,0,0,-50</gradient_ratio></pie><animation><start_radius>0%</start_radius><pull_out_time>1.5</pull_out_time><pull_out_effect>strong</pull_out_effect><pull_out_radius>25%</pull_out_radius></animation><data_labels><show>{title}: {percents}%</show></data_labels><labels><label lid='0'><text>Calls Count orig</text><x>20</x><y>20</y><text_size>20</text_size></label></labels></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<pie><slice title='Calls Success'>57437</slice><slice title='Calls Busy'>2936</slice><slice title='Calls No Channel'>35606</slice><slice title='Calls Error'>26130</slice></pie>"));


	//	so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_origcall.xml"));

		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_origcall.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent5");
		// ]]>
	</script>
</div>
</div>

</div>
   <div style="width: 33%; float: left;">


 <?php //****打出的call统计报表6***********?>

<div id="chart_10224_div" class="amChart">
<div id="chart_10224_div_inner" class="amChartInner">

	<div id="flashcontent6">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/ampie.swf", "amcolumn", "100%", "400", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");
		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><background><alpha>100</alpha><border_alpha>20</border_alpha></background><balloon><show>{title}: {value} ({percents}%)</show><text_color>000000</text_color><text_size>15</text_size><max_width>400</max_width><corner_radius>6</corner_radius></balloon><legend><align>center</align></legend><pie><inner_radius>40</inner_radius><height>20</height><angle>20</angle><outline_alpha>50</outline_alpha><alpha>80</alpha><hover_brightness>30</hover_brightness><gradient_ratio>-50,0,0,-50</gradient_ratio></pie><animation><start_radius>0%</start_radius><pull_out_time>1.5</pull_out_time><pull_out_effect>strong</pull_out_effect><pull_out_radius>25%</pull_out_radius></animation><data_labels><show>{title}: {percents}%</show></data_labels><labels><label lid='0'><text>Calls Count term</text><x>20</x><y>20</y><text_size>20</text_size></label></labels></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<pie><slice title='Calls Success'>298482</slice><slice title='Calls Busy'>8312</slice><slice title='Calls No Channel'>48439</slice><slice title='Calls Error'>44371</slice></pie>"));



		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_termcall.xml"));
		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_termcall.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent6");
		// ]]>
	</script>

</div>
</div>

</div>
                                    </div>
                            <div style="width: 100%;">
                                                <div style="width: 34%; float: left;">

   <?php //****打进打出一起统计报表7***********?>

<div id="chart_666e4_div" class="amChart">
<div id="chart_666e4_div_inner" class="amChartInner">
	<div id="flashcontent7">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amradar.swf", "amradar", "100%", "400", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

	// so.addVariable("chart_settings", encodeURIComponent("<settings>...</settings>"));


		so.addVariable("chart_settings", encodeURIComponent("<settings><height>300</height><background><alpha>100</alpha><border_alpha>20</border_alpha></background><balloon><show>{title}: {value}</show><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><legend><align>center</align></legend><labels><label><text></text><x>20</x><y>20</y><text_size>20</text_size></label></labels></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><axes><axis xid='0'>Calls Total</axis><axis xid='1'>Calls Not Zero</axis><axis xid='2'>Calls Success</axis><axis xid='3'>Calls Busy</axis><axis xid='4'>Calls No Channel</axis><axis xid='5'>Calls Error</axis></axes><graphs><graph color='333333' line_width='2' bullet='round' gid='0' title='origination'><value xid='0'>122109</value><value xid='1'>24885</value><value xid='2'>57437</value><value xid='3'>2936</value><value xid='4'>35606</value><value xid='5'>26130</value></graph><graph color='99CC00' line_width='2' bullet='round' gid='1' title='termination'><value xid='0'>399604</value><value xid='1'>175232</value><value xid='2'>298482</value><value xid='3'>8312</value><value xid='4'>48439</value><value xid='5'>44371</value></graph></graphs></chart>"));


		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_totalcall.xml"));
		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_totalcall.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent7");
		// ]]>
	</script>
</div>
</div>

</div>
                                    </div>
                </div>
  <?php //*******************************flash报表*****end********************************?>







<script type="text/javascript">
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};
var _ss_ids_code_name = {'code_name': 'query-code_name', 'id_code_decks': 'query-id_code_decks'};
</script>





<script type="text/javascript">
//<![CDATA[
tz = $('#query-tz').val();
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    val = $('#query-client_type').val();
    tz = $('#query-tz').val();
    if (val == "1") {
        winOpen('/admin/cc/ss_cc?types=4', 500, 530);
    } else if (val == "0") {
        winOpen('/adminclients/ss_client?types=2&type=0', 500, 530);
    } else if (val == "2") {
        winOpen('/admin/clients/ss_client?type=2&types=8', 500, 530);
    }
}

function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
    }
}
repaintOutput();
//]]>
</script>

</div>
<div>

</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>

