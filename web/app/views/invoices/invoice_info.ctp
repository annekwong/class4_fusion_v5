<table>
	<tr>
		<td><?php echo $this->data[0][0]['invoice_number']?></td>
		<td><?php echo $this->data[0][0]['client_name']?></td>
		<td>
				<?php echo $this->data[0][0]['invoice_start']?>
				-
				<?php echo $this->data[0][0]['invoice_end']?>
		</td>
		<td>
				<?php if($this->data[0][0]['pay_amount']==$this->data[0][0]['current_balance']){?>
					<?php echo 'Unpaid'?>		
				<?php }else{?>
					<?php echo 'Partially Paid '?>
				<?php }?>
		</td>
		<td>
				<?php if(strtotime(date('Y-m-d h:i:s'))>strtotime($this->data[0][0]['due_date'].' 23:59:59')){?>
					<?php echo 'Yes'?>
				<?php }else{?>
					<?php echo 'No'?>	
				<?php }?>
		</td>
	</tr>
</table>                                                                                                                                                                                                                                                                                           