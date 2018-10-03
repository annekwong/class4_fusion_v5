
    <link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
    
    <script type="text/javascript">
    			function checkForm(){
        	var giftscoreBonusCredit = document.getElementById("GiftscoreBonusCredit").value;
        	var giftscoreGiftAmount = document.getElementById("GiftscoreGiftAmount").value;

        	var has_error = false;
        	if (!giftscoreBonusCredit){
        		has_error = true;
        		jGrowl_to_notyfy('<?php echo __('enterscore')?>',{theme:'jmsg-alert'});
            			} else {
                			if (!/^\d+$/.test(giftscoreBonusCredit)){
                    			has_error = true;
                    			jGrowl_to_notyfy('<?php echo __('scoreintergeronly')?>',{theme:'jmsg-alert'});	
                    			}
                			}
			
        	if (giftscoreGiftAmount){
        		//has_error = true;
        		//jGrowl_to_notyfy('<?php echo __('enteramountofscore')?>',{theme:'jmsg-alert'});
            			//} else {
                			if (!/^[0-9]+(\.[0-9]{1,3})?$/.test(giftscoreGiftAmount)){
                    			has_error = true;
                    			jGrowl_to_notyfy('<?php echo __('amountformat')?>',{theme:'jmsg-alert'});	
                    			}
                			}

						if (has_error){
							return false;
						}
        			}
    </script>

<div id="title">
        <h1><?php echo __('addpointchangeamount')?></h1>
</div>
<div class="container">


	<?php echo $form->create ('Giftscore', array ('action' => 'add' ,'onsubmit'=>'return checkForm();'));?>

 	 <?php

   empty($id)?$sale_id=$post['Giftscore']['sales_strategy_id']:$sale_id=$id;
 	 echo $form->input('sales_strategy_id',array('label'=>false,'div'=>false,'type'=>'hidden','value'=>$sale_id))?>
<table class="form">
<tbody>

<tr>
    <td class="label label2"><?php echo __('bonus')?>(<?php __('a')?>):</td>
    <td class="value value2">
           		<?php echo $form->input('bonus_credit',  
 		array('label'=>false ,'div'=>false,'type'=>'text', 'style'=>'float: left; width: 300px;', 'class'=>'input in-text'));?>
    </td>
</tr>
<tr>
    <td class="label label2"><?php echo __('gift_amount')?>(<?php __('money')?>):</td>
    <td class="value value2">
           		<?php echo $form->input('gift_amount',  
 		array('label'=>false ,'div'=>false,'type'=>'text',  'style'=>'float: left; width: 300px;','class'=>'input in-text'));?>
    </td>
</tr>




</tbody></table>

<div id="form_footer">
   <input type="submit" value="<?php echo __('submit')?>"  class="input in-submit">
</div>
</div>

