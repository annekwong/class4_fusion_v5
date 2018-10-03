<?php echo $form->create('Digit') ?>
<table>
    <tr>
        <td></td>
        <td><?php echo $xform->input('translation_name', array('maxlength' => 256)) ?></td>
        <td></td>
        <td></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="#" onclick="check_()" title="Save">
                <i class="icon-save"></i>
            </a>
            <a id="delete" title="Exit">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>




