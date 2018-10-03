<?php echo $form->create('DidBillingPlan')?>
<table>
    <tr>
        <td><?php echo $xform->input('name',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('did_price',array('maxlength'=>256, 'style'=>'width:80px;'))?></td>
        <td><?php echo $xform->input('channel_price',array('maxlength'=>256, 'style'=>'width:40px;'))?></td>
        <td><?php echo $xform->input('min_price',array('maxlength'=>256, 'type'=>'text', 'style'=>'width:80px;'))?></td>
        <td><?php echo $xform->input('billed_channels',array('maxlength'=>256, 'style'=>'width:40px;'))?></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <i class="icon-save"></i>
            </a>
            <a id="delete" title="Exit">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end()?>
