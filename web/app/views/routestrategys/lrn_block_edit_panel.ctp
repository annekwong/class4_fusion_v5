<form class="lrn_block_from">
<table>
    <tr>
        <td></td>
        <td><?php echo $xform->input('code',array('maxlength'=>256,'type' => 'text','class' => 'validate[custom[code]]'))?></td>
        <td></td>
        <td></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="javascript:void(0)" title="<?php __('Save'); ?>">
                <i class="icon-save"></i>
            </a>
            <a id="delete" href="javascript:void(0)" title="<?php __('Cancel'); ?>">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
</form>
