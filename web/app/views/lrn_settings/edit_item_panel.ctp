<?php echo $form->create('LrnItem') ?>
<table>
    <tr>
        <td><?php echo $xform->input('ip', array('type' => 'text')) ?></td>
        <td><?php echo $xform->input('port') ?></td>
        <td><?php echo $xform->input('timeout') ?></td>
        <td><?php echo $xform->input('retry') ?></td>
        <td><?php echo $xform->input('option', array('type' => 'checkbox', 'class' => 'option_master')) ?></td>
        <td><?php echo $xform->input('option_interval', array('class' => 'option_items')) ?></td>
        <td><?php echo $xform->input('dynamic_timeout', array('type' => 'checkbox','class' => 'option_items')) ?></td>
        <td><?php echo $xform->input('filter_timeout', array('type' => 'checkbox', 'class' => 'option_items')) ?></td>
        <td>-</td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <i class="icon-save"></i>
            </a>
            <a id="delete" title="Delete">
               <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>
