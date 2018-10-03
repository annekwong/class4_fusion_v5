<script type="text/javascript">
	//&lt;![CDATA[
		var currentTime = '1281772609';
		var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
	 //]]&gt; 
	</script> 
	<link rel="icon" type="image/x-icon" href="<?php echo $this->webroot?>favicon.ico"><link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->webroot?>favicon.ico">	
        <h1><?php echo __('editservice')?></h1>
                <ul id="title-menu">
    		<li>
    			<a href="<?php echo $this->webroot?>services/services_list">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/rerating_queue.png" alt="">
    				&nbsp;<?php echo __('goback')?>    			</a>
    		</li>
  		</ul>
</div>
<div class="container">   


		<form  method="post">
			<input type="hidden" value="<?php echo $service[0][0]['service_id']?>" id="service_id" name="service_id"/>
		<fieldset style="display: none;"></fieldset>
<table class="cols"><col width="50%"><col width="50%"><tbody><tr>
<td class="first"><!-- COLUMN 1 -->

<fieldset><legend><?php echo __('basicinfo')?></legend>
<table class="form">
<tbody><tr>
    <td class="label label2"><?php echo __('service_name')?>:</td>
    <td class="value value2">
    <input style="width:300px;float:left" type="text" id="service_name" value="<?php echo $service[0][0]['service_name']?>" name="service_name" class="input in-text">    </td>
</tr>

<tr>
    <td class="label label2"><?php echo __('servicetype')?>:</td>
    <td class="value value2">
    
 			<select id="types"style="width:300px;float:left" name="type" class="input in-select">
 					<option value="1"><?php echo __('showincoming')?></option>
 					<option value="2"><?php echo __('recored')?></option>
 					<option value="3"><?php echo __('colorring')?></option>
 					<option value="4"><?php echo __('flymsg')?></option>
 					<option value="5"><?php echo __('web400')?></option>
 					<option value="6"><?php echo __('familynum')?></option>
 					<option value="7"><?php echo __('groupnumbymonth')?></option>
 					<option value="8"><?php echo __('fsbymonth')?></option>
			</select>    </td>
<script type="text/javascript">document.getElementById('type').value="<?php echo $service[0][0]['type']?>";</script>   
</tr>

<!--  <tr>
    <td class="label label2"><?php echo __('Reseller')?>:</td>
    <td class="value value2">
    
 			<select style="width:300px;float:left" id="reseller_id" name="reseller_id" class="input in-select">
			</select>    </td>

</tr>-->



<tr>
    <td class="label label2"><?php echo __('status')?>:</td>
    <td class="value value2">
    
 		<select style="width:300px;float:left" id="status" name="status" class="input in-select">
 			<option value="1"><?php echo __('active')?></option>
 			<option value="0"><?php echo __('noactive')?></option>
</select> <script type="text/javascript">document.getElementById('status').value="<?php echo $service[0][0]['status']?>";</script>   </td>

</tr>


</tbody></table>

</fieldset>



<!-- / COLUMN 1 --></td><td><!-- COLUMN 2 -->
<fieldset><legend><?php echo __('billinginfo')?></legend>
<table class="form">
<tbody><tr>
    <td class="label label2"><?php echo __('billinterval')?>:</td>
    <td>
    <input style="width:150px;float:left;" value="<?php echo $service[0][0]['billing_time']?>" name="billing_time" id="billing_time"/>
     		<select style="width:150px;float:left;" id="billing_type" name="billing_type" class="input in-select">
					<option value="3"><?php echo __('months')?></option>
					<option value="2"><?php echo __('weekly')?></option>
					<option value="1"><?php echo __('days')?></option>
</select>   <span style="float:left;padding-top:5px;">1<?php echo __('ci',true)?></span>
    <script type="text/javascript">document.getElementById('billing_type').value="<?php echo $service[0][0]['billing_type']?>";</script>
    </td>

</tr>

<tr>
    <td class="label label2"><?php echo __('cc')?>:</td>
    <td class="value value2">
    <input style="width:300px;float:left" name="cost" id="cost" value="<?php echo $service[0][0]['cost']?>"/>
    </td>

</tr>


<tr>
    <td class="label label2"><?php echo __('effective_amount')?>:</td>
    <td class="value value2">
    <input style="width:300px;float:left" value="<?php echo $service[0][0]['effective_amount']?>" name="effective_amount" id="effective_amount"/>
    </td>

</tr>
</tbody></table>
</fieldset>








<!-- / COLUMN 2 --></td></tr></tbody></table>

<div id="footer">
            <input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit">

    <input type="reset" value="<?php echo __('reset')?>" class="input in-button">
    </div>
		</form>


</div>

<div id="tooltip" style="display: none; top: 475px; left: 464px; right: auto;" class=""><h3 style="display: none;"></h3><div class="body"><span class="" id="ht-100011-tooltip"><?php __('Specify last day, that the clients was')?> <b><?php __('invoiced for')?></b>.</span></div><div class="url" style="display: none;"></div></div><div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>