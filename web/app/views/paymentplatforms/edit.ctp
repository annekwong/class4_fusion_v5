
    <link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">

<div id="title">
        <h1><?php echo __('createpaymentflatform')?></h1>
</div>
<div class="container">


	<?php echo $form->create ('Paymentplatform', array ('action' => 'edit' ));?>

	  <?php echo $form->input('payment_platform_id',array('label'=>false,'div'=>false,'type'=>'hidden','value'=>$post['Paymentplatform']['payment_platform_id']))?>
 <input type="hidden" value="<?php echo $post['Paymentplatform']['payment_platform_id']?>" name="payment_platform_id"/>
<table class="form">
<tbody>

<tr>
    <td class="label label2"><?php echo __('paymentflatformname')?></td>
    <td class="value value2">
           		<?php echo $form->input('name',  
 		array('label'=>false ,'div'=>false,'type'=>'text', 'value'=>$post['Paymentplatform']['name'],'style'=>'float: left; width: 390px;', 'class'=>'input in-text'));?>
    </td>
</tr>


<tr>
    <td class="label label2"><?php echo __('canSupport')?></td>
    <td class="value value2">
         		<?php
  $tmp=array('true'=>__('yes',true),'false'=>__('no',true));
         		echo $form->input('support',
 		array('options'=>$tmp,'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Paymentplatform']['name'],'style'=>'float: left; width: 390px;','class'=>'input in-text'));?>
    </td>
</tr>
<tr>
    <td class="label label2"><?php echo __('ip')?></td>
    <td class="value value2">
           		<?php echo $form->input('ip',  
 		array('label'=>false ,'div'=>false,'type'=>'text','value'=>$post['Paymentplatform']['ip'], 'style'=>'float: left; width: 390px;', 'class'=>'input in-text'));?>
    </td>
</tr>

<tr>
    <td class="label label2"><?php echo __('accountname')?></td>
    <td class="value value2">
           		<?php echo $form->input('account',  
 		array('label'=>false ,'div'=>false,'type'=>'text', 'value'=>$post['Paymentplatform']['account'],'style'=>'float: left; width: 390px;', 'class'=>'input in-text'));?>
    </td>
</tr>

<tr>
    <td class="label label2"><?php echo __('password')?></td>
    <td class="value value2">
           		<?php echo $form->input('password',  
 		array('label'=>false ,'div'=>false,'type'=>'text', 'value'=>$post['Paymentplatform']['password'],'style'=>'float: left; width: 390px;', 'class'=>'input in-text'));?>
    </td>
</tr>

</tbody></table>

<div id="footer">
   <input type="submit" value="<?php echo __('submit')?>"  class="input in-submit">
</div>
</div>

