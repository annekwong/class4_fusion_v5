<?php echo $form->create('Curr')?>
<table>
	<tr>
<!--		<td></td>-->
		<td><?php echo $form->input('code',Array('id'=>'code','div'=>false,'label'=>false,'maxlength'=>256))?></td>
		<td><?php echo $form->input('rate',Array('id'=>'rate','div'=>false,'label'=>false))?></td>
		<td></td>
		<td></td>
		<td></td>
                <td><?php echo $form->input('active',Array('div'=>false,'value'=> '','label'=>false, 'hiddenField' => false))?></td>
		<td>
			<a title="Save" id="save" href="" onclick="return false">
                            <i class="icon-save"></i>
			</a>
			<a title="Exit" id="delete"  href="">
                            <i class="icon-remove"></i>
			</a>
		</td>
	</tr>
</table>
<?php echo $form->end()?>