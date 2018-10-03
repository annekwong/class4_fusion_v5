<?php echo $form->create('Codedeck')?>
	<table>
		<tr>
			<!--		<td></td>-->
			<td></td>
			<td><?php echo $form->input('name',Array('div'=>false,'label'=>false,'value'=>$this->data[0][0]['name'],
					'maxlength'=>256,'class' => 'validate[required,custom[onlyLetterNumberLineSpace]]'))?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<a title="Save" id="save" href="<?php echo $this->webroot?>codedecks/add_codedeck" onclick="return false">
					<i class="icon-save"></i>
				</a>
				<a title="Exit" id="delete"  href="">
					<i class="icon-remove"></i>
				</a>
			</td>
		</tr>
	</table>
<?php echo $form->end()?>