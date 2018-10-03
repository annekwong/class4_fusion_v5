<table>
<tbody>
	<tr>
		<td></td>
	   <td class="value value2">
	    <input type="text" id="code" value="" name="code"  >
	   </td>
	   <td class="value value2">
	    <input type="text" id="name" value="" name="name"  maxLength="16">
	   </td>
	   <td class="value value2">
		<?php echo $form->input('status',array('options'=>$country,'style'=>'width:300px;','id'=>'country','name'=>'country','label'=>false,'div'=>false,'type'=>'select'))?>
	   </td>
	   <td></td>
	   <td></td>
	</tr></tbody>
	</table>

