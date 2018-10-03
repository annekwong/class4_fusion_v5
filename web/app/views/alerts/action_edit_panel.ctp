
<?php echo $form->create('Action')?>
<table>
    <tr>
        <td><?php echo $xform->input('name',array('maxlength'=>256, 'class'=>'input-small'))?></td>
        <td><?php echo $xform->input('block_ani',array('class'=>'input-small')); ?></td>
        <td><?php echo $xform->input('loop_detection', array('class'=>'input-small'));?></td>
        <td><?php echo $xform->input('trouble_tickets_template', array('options'=>$templates, 'class'=>'input-small'));?></td>
        <td><?php echo $xform->input('email_notification',Array('options'=>array('None','System\'s NOC','Partner\'s NOC', 'Both NOC'), 'class'=>'input-small'));?></td>
        <td><?php echo $xform->input('disable_route_target',Array('options'=>array('None', 'Entire Trunk', 'Entire Host'), 'class'=>'input-small'));?></td>
        <td><?php echo $xform->input('disable_code_trunk', array('class'=>'input-small'));?></td>
        <td><?php echo $xform->input('disable_duration', array('class'=>'input-small'));?></td>
        <td><?php echo $xform->input('change_prioprity',Array('options'=>array('None', 'Trunk', "Host"), 'class'=>'input-small'));?></td>
        <td><?php echo $xform->input('change_to_priority', Array('options'=>range(0, 10), 'class'=>'input-small'));?></td>
        <td></td>
        <td></td>
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





