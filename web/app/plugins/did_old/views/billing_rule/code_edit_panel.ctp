<?php echo $form->create('DidSpecialCode')?>
<table>
    <tr>
        <td><?php echo $xform->input('code',array('type'=>'text', 'maxlength'=>256))?></td>
        <td><?php echo $xform->input('pricing',array('maxlength'=>256, 'style'=>'width:80px;'))?></td>
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
