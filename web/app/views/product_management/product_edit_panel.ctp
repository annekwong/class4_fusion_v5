<?php echo $form->create('ProductRouteRateTable')?>
<table>
    <tr>
        <td><?php echo $xform->input('product_name',array('maxlength'=>256,'class'=>'validate[required,custom[onlyLetterNumberLineSpace]]'))?></td>
        <td><?php echo $xform->input('route_strategy_id',array('type'=>'select','options' => $route_plan))?></td>
        <td><?php echo $xform->input('rate_table_id',array('type'=>'select','options' => $rate_table))?></td>
        <td><?php echo $xform->input('tech_prefix',array('type'=> 'text','class'=>'validate[required,custom[integer]]'))?></td>
        <td></td>
        <td></td>
        <td class="last">
            <a id="save" href="javascript:void(0)" title="Save">
                <i class="icon-save"></i>
            </a>
            <a id="delete" title="Cancel">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end()?>
