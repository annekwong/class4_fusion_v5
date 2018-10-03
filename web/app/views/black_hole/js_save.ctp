	<table>
		<tr>
			<td></td>
			<td>
			     <input style="width:120px;" class="input in-text validate[required,custom[ipv4]]" name="ip" class="ip" type="text" >
			</td>
			<td></td>
			<td>
			     <select name="auto_block" class='select in-select'>
                    <option value=false><?php __('False') ?></option>
                    <option value=true ><?php __('True') ?></option>
                </select>
			</td>

			<td></td>
			<td></td>
			<td>
				<a title="Save" id="save" onclick="return false">
					<i class="icon-save"></i>
				</a>
				<a title="Exit" id="delete"  href="">
					<i class="icon-remove"></i>
				</a>
			</td>
		</tr>
	</table>
