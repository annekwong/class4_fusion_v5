<?php echo $form->create('RandomAniGeneration') ?>

<table>
    <tr>
         <td></td>
        <td><?php echo $xform->input('ani_number', array('maxlength' => 256)) ?></td>
        <td>
            <a title="Save" href="#%20" id="save" >
                <i class="icon-save"></i>
            </a>
            <a title="Exit" href="#%20" style="margin-left: 20px;" id="delete" >
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>
