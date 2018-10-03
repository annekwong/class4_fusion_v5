

	<div id="title">
    <h1><?php echo __('Edit Service Charge',true);?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>servicecharges/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>	
	
	
		<?php echo $form->create('Servicecharge',array('action'=>'edit'));?>
	<div class="container">
	
<table class="cols"><col width="35%"><col width="38%"><tbody><tr>
<td class="first"><!-- COLUMN 1 -->



<fieldset><legend><?php echo __('Buy Rate info',true);?></legend>
<table class="form">
<tbody><tr>
    <td class="label label2"><?php echo __('name',true);?>:</td>
    <td class="value value2"><?php echo $form->input('name',array('label'=>false,'div'=>false,'type'=>'text'))?></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('Buy Rate',true);?>:</td>
    <td class="value value2"><?php echo $form->input('buy_rate',array('label'=>false,'div'=>false,'type'=>'text'))?></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('Buy Max Rate',true);?>:</td>
    <td class="value value2"><?php echo $form->input('buy_max_rate',array('label'=>false,'div'=>false,'type'=>'text'))?></td>
</tr>


<tr>
    <td class="label label2"><?php echo __('Greater Buy Max Rate Fee',true);?>:</td>
    <td class="value value2"><?php echo $form->input('greater_buy_max_rate_fee',array('label'=>false,'div'=>false,'type'=>'text'))?></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('Less Buy Rate Fee',true);?>:</td>
    <td class="value value2"><?php echo $form->input('less_buy_rate_fee',array('label'=>false,'div'=>false,'type'=>'text'))?></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('Greater Buy Rate Fee',true);?>:</td>
    <td class="value value2"><?php echo $form->input('greater_buy_rate_fee',array('label'=>false,'div'=>false,'type'=>'text'))?></td>
</tr>

</tbody></table>
</fieldset>

<!-- / COLUMN 1 --></td><td><!-- COLUMN 2 -->

<fieldset><legend><?php echo __('Sell Rate info',true);?></legend>
<table class="form">
<tbody><tr>
    <td class="label label2"><?php echo __('Sell Rate',true);?>:</td>
    <td class="value value2">
<?php echo $form->input('sell_rate',array('label'=>false,'div'=>false,'type'=>'text'))?>
    
    </td>
</tr>

<tr>
    <td class="label label2"><?php echo __('Sell Max Rate',true);?>:</td>
    <td class="value value2">
<?php echo $form->input('sell_max_rate',array('label'=>false,'div'=>false,'type'=>'text'))?>
    
    </td>
</tr>

<tr>
    <td class="label label2"><?php echo __('Greater Sell Max Rate Fee',true);?> :</td>
    <td class="value value2">
<?php echo $form->input('greater_sell_max_rate_fee',array('label'=>false,'div'=>false,'type'=>'text'))?>
    
    </td>
</tr>
<tr>
    <td class="label label2"><?php echo __('Less Sell Rate Fee',true);?>:</td>
    <td class="value value2">
<?php echo $form->input('less_sell_rate_fee',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>
</tr>
<tr>
    <td class="label label2"><?php echo __('Greater Sell Rate Fee',true);?>:</td>
    <td class="value value2">
<?php echo $form->input('greater_sell_rate_fee',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>
</tr>

</tbody></table>
</fieldset>





<!-- / COLUMN 2 --></td></tr></tbody></table></div>

			<div id="footer">
		  	<input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
		  	<input type="reset" value="<?php echo __('reset')?>" class="input in-button">
			</div>

</from>
