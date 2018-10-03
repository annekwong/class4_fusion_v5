<fieldset class="title-block" id="advsearch"  style="display:none;width:100%;margin:0 0 10px">
    <form name="carriersearch" method="get">
      <input type="hidden"  name="adv_search" value="1"/>
      <table  style="width:auto;">
        <tbody>
          <tr>

		    <td style="width:250px">
							<label><?php echo __('Name')?>:</label>
				    <?php echo $xform->search('filter_name',Array('style'=>'width:67%;display:inline'))?>
				  </td>
		    <td>
				<label><?php echo __('Client type')?>:</label>
				<?php 
				//echo $xform->search('filter_client_type',Array('options'=>Array( Client::CLIENT_CLIENT_TYPE_INGRESS=>'ORIG Carrier',Client::CLIENT_CLIENT_TYPE_EGRESS=>'TERM Carrier'),'empty'=>'All'));
				echo $xform->search('filter_client_type',Array('options'=>Array('all'=>'All', 'ingress'=>'Ingress Trunk Only', 'egress'=>'Egress Trunk Only') ));
				?>
		  	</td>
		    <td class="buttons"><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
		</tr>
		</tbody>
	</table>
</form>
</fieldset>

