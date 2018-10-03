<?php echo $form->create('Rate')?>

<table>
	<tr>
		<td></td>
		<td><?php echo $xform->input('name',array('maxlength'=>256, 'style' => 'width: 95%'))?></td>
		<td><?php echo $xform->input('code_deck_id',Array('options'=>$$hel->_get_select_options($CodedeckList,'Codedeck','code_deck_id','name'),'empty'=>''))?></td>
		<td><?php echo $xform->input('currency_id',Array('options'=>$$hel->_get_select_options($CurrencyList,'Currency','currency_id','code')))?></td>
		<td></td>
		<td><?php echo $xform->input('rate_type',Array('options'=>array(0=>'DNIS', 1=>'LRN', 2=>'LRN BLOCK') ));?></td>
<!--		<td><?php echo $xform->input('jurisdiction_country_id',Array('options'=>$$hel->_get_select_options($JurisdictioncountryList,'Jurisdictioncountry','id','name'),'empty'=>''))?></td>-->
<td><?php echo $xform->input('jur_type',Array('options'=>array(0=>'A-Z', 1=>'US Non-JD', 2=>'US JD', 3=>'OCN-LATA-JD', 4 => 'OCN-LATA-NON-JD') ));?></td>

		<td><?php
		$define_by = isset($this->data['Rate']['define_by']) ? $this->data['Rate']['define_by'] : 0;
		if($define_by){
		    echo $xform->input('define_by',Array('options'=> $define_by_arr, 'value' => $define_by));
		}else{
		    echo $define_by_arr[$define_by];
		}
		?>
		</td>
<!--		<td>--><?php //echo $xform->input('lnp_dipping_rate',array('maxlength'=>256))?><!--</td>-->

                <td></td> <td></td>
                <td>
			<a title="Save" href="#%20" id="save" >
                            <i class="icon-save"></i>
			</a>
			<a title="Exit" href="#%20" style="margin-left: 20px;" id="delete" >
				<i class="icon-remove"></i>
			</a>
                        <a title="Indeterminate Rate Setting" href="#%20" onclick="showDiv('pop-div','500','200','/Class4/clients/addratetable/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ""; ?>');" id="edit_indeter">
                             <i class="icon-wrench"></i>
                        </a>
		</td>
	</tr>
</table>
<?php echo $form->end()?>