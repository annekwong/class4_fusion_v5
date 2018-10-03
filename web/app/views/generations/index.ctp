<style type="text/css">
.in-select{
	width:165px;
}
</style>
<div id="title">
 <h1><?php  __('Generation')?>&gt;&gt;<?php __('Rate table')?></h1> 
</div>
<div id="container">
<fieldset class="query-box">
<legend><?php __('search')?></legend>
<?php echo $form->create ('Cdr', array ('type'=>'get','url' => '/generations/index'));?>
<table style="width:100%">
	<tbody>
		<tr>
			<td style="text-align:right;width:20%"><?php echo __('Start At',true);?>:</td>
			<td style="text-align:left" colspan=3>
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $xform->input('year',Array('options'=>Array()))?>
				&nbsp;&nbsp;<?php echo $xform->input('month',Array('options'=>Array()))?>
				&nbsp;&nbsp;<?php echo $xform->input('month',Array('options'=>Array()))?>
				&nbsp;&nbsp;<?php echo $xform->input('month',Array('options'=>Array()))?>
				&nbsp;&nbsp;<?php echo $xform->input('month',Array('options'=>Array()))?>
			</td>
		</tr>
		<tr>
			<td style="text-align:right" style="width:20%"><?php echo __('Profit',true);?>:</td>
			<td style="text-align:left;width:20%">
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $xform->input('profit')?>$(USD)
			</td>
			<td style="width:10%;text-align:right"></td>
			<td style="text-align:left;"></td>
		</tr>
		<tr>
			<td style="text-align:right" style="width:20%"><?php echo __('Price is taken as',true);?>:</td>
			<td style="text-align:left">
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $xform->input('profit',Array('options'=>Array()))?>%
			</td>
			<td style="text-align:right"></td>
			<td style="text-align:left"></td>
		</tr>
		<tr>
			<td style="text-align:right" style="width:20%"><?php echo __('Reliability trends',true);?>:</td>
			<td style="text-align:left">
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $xform->input('profit',Array('options'=>Array()))?>%
			</td>
			<td style="text-align:right"></td>
			<td style="text-align:left"></td>
		</tr>
		<tr>
			<td style="text-align:right" style="width:20%"><?php echo __('Take by period',true);?>:</td>
			<td style="text-align:left">
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $xform->input('profit',Array('options'=>Array()))?>%
			</td>
			<td style="text-align:right"></td>
			<td style="text-align:left"></td>
		</tr>
		<tr>
			<td style="text-align:right" style="width:20%"><?php echo __('pdd',true);?>(Post dial delay):</td>
			<td style="text-align:left">
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $xform->input('profit',Array('options'=>Array()))?>%
			</td>
			<td style="text-align:right"><?php echo __('Min Billing Time',true);?></td>
			<td style="text-align:left"><?php echo $xform->input('min_billing_time')?>sec</td>
		</tr>
		<tr>
			<td style="text-align:right" style="width:20%"><?php echo __('acd',true);?>(Average call duration):</td>
			<td style="text-align:left">
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $xform->input('profit',Array('options'=>Array()))?>%
			</td>
			<td style="text-align:right"><?php echo __('Billing Step',true);?></td>
			<td style="text-align:left"><?php echo $xform->input('billing_step')?>sec</td>
		</tr>
		<tr>
			<td style="text-align:right" style="width:20%"><?php echo __('asr',true);?>(Answer Seizure Ratio):</td>
			<td style="text-align:left">
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $xform->input('profit',Array('options'=>Array()))?>%
			</td>
			<td style="text-align:right"><?php echo __('Free Seconds',true);?></td>
			<td style="text-align:left"><?php echo $xform->input('billing_step')?>sec</td>
		</tr>
		<tr>
			<td style="text-align:right" style="width:20%"><?php echo __('Vendors',true);?>:</td>
			<td style="text-align:left">
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $xform->input('profit',Array('options'=>Array(),'size'=>10))?>%
			</td>
			<td style="text-align:right"></td>
			<td style="text-align:left"></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td><input type="submit" value="<?php echo __('submit',true);?>"/></td>
		</tr>
	</tbody>
</table>
<?php echo $form->end();?>
</fieldset>
</div>

