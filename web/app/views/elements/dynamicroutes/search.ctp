<fieldset class="title-block" id="advsearch">
<form>
<table >
<tbody>
<tr>
    <td style="display:none; "><label> <?php echo __('route_name')?>:</label>
    	<input   type="text"   name="name"   />
	</td>
    <td>
    	<label><?php echo __('routingrule')?>:</label>
    	<?php echo $xform->search('filter_routing_rule',Array('options'=>$OptionsRouteingRule,'empty'=>'select'))?>
    </td>
    <td class="buttons"><input type="submit" value="<?php echo __('Search',true);?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>