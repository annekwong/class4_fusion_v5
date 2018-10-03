<?php echo $form->create('Paymentterm')?>
<?php 
//获取当月天数
#  $numDay = date("t",mktime(0,0,0,date('m'),date('d'),date('Y')));
  $numDay=31;
  $arrayDay=array();
  for($i=1; $i<=$numDay;$i++){
  		if($i==1){
  			$arrayDay[1]='1 st';
  			}
  		if($i==2){
  			$arrayDay[2]='2 nd';
  			}
  		else{
  			$arrayDay[$i]=$i.' th';
  			}
       }
  $arrayWeekDay=Array(0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wendsday',4=>'Thursday',5=>'Friday',6=>'Saturday');
  
  $arrayEvery = array();
  for($k=1;$k<61;$k++){
    $arrayEvery[$k] = $k;
  }
  
  $array_days = array();
  for($m=1;$m<31;$m++){
    $array_days[$m] = $m;
  }
  
  
?>
<table class="list">
	<tr >
		<?php echo $form->input('payment_term_id',Array('div'=>false,'label'=>false,'type'=>'hidden'))?>
		<td><?php echo $form->input('name',Array('div'=>false,'label'=>false,'maxlength'=>256,'style'=>'width:100px'))?></td>
		<td>
		<?php if($this->data['Paymentterm']['type']==4){$this->data['Paymentterm']['days']=$this->data['Paymentterm']['more_days'];}?>
			<?php echo $form->input( 'type',Array('div'=>false,'label'=>false,'style'=>'width:120px','options'=>$appPaymentterms->get_options_type()))?>
			<?php echo $form->input('days',Array('div'=>false,'label'=>false,'style'=>'width:80px','options'=>$arrayDay,'value'=>$this->data['Paymentterm']['days']) )?>
			<?php echo $form->input('days2',Array('div'=>false,'label'=>false,'style'=>'width:80px;display:none','value'=>$this->data['Paymentterm']['days']))?>	
			<?php echo $form->input('days3',Array('div'=>false,'label'=>false,'style'=>'width:80px;display:none','options'=>$arrayWeekDay,'value'=>$this->data['Paymentterm']['days']) )?>
                        <?php echo $form->input('days4',Array('div'=>false,'label'=>false,'style'=>'width:80px;display:none','options'=>$arrayEvery,'value'=>$this->data['Paymentterm']['days']) )?>
		</td>
		<td><?php echo $form->input('grace_days',Array('div'=>false,'label'=>false,'style'=>'width:100px','options'=>$array_days,'value'=>$this->data['Paymentterm']['grace_days']))?></td>
		<td><?php echo $form->input('notify_days',Array('div'=>false,'label'=>false,'style'=>'width:100px','options'=>$array_days,'value'=>$this->data['Paymentterm']['notify_days']))?></td>
		<td></td>
        <td>
		<?php echo $form->input('finance_rate',Array('div'=>false,'label'=>false,'style'=>'width:80px;'))?>
		</td>
		<td  align="center" style="text-align: center;" class="last">
		 	<a id="save" href="#" title="Save">
		    	<i class="icon-edit"></i>
		    </a>
		    <a id="delete" href="#" title="Deleted">
		    	<i class='icon-remove'></i>
		    </a>		    
		 </td>
		</tr>
</table>
<?php echo $form->end()?>