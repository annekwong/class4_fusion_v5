<?php echo $form->create('Resource') ?>

<table>
    <tr>
        <td><?php echo $name; ?></td>
        <td><?php echo $xform->input('counter_time', array('maxlength' => 256,'class'=>'validate[custom[onlyNumber]]')) ?></td>
        <td><?php echo $xform->input('number', array('maxlength' => 256,'class'=>'validate[custom[onlyNumber]]')) ?></td>
        <td><?php echo $xform->input('block_time', array('maxlength' => 256,'class'=>'validate[custom[onlyNumber]]')) ?></td>
        <td>
            <a title="Save" href="#%20" id="save" >
                <i class="icon-save"></i>
            </a>
            <a title="Delete" href="#%20" style="margin-left: 20px;" id="delete" >
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>
