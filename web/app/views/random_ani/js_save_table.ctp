<?php echo $form->create('RandomAniTable') ?>

<table>
    <tr>
         <td></td>
        <td><?php echo $xform->input('name', array('maxlength' => 256)) ?></td>
        <td></td>
        <td>
            <a title="Save" href="javascript:void(0)" id="save" >
                <i class="icon-save"></i>
            </a>
            <a title="Exit" href="javascript:void(0)" style="margin-left: 20px;" id="delete" >
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>
