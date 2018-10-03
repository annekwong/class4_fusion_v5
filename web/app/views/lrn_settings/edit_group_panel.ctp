<?php echo $form->create('LrnSetting')?>
<table>
    <tr>
        <td></td>
        <td><?php echo $xform->input('name',array('maxlength'=>256,'class'=>'validate[required,custom[onlyLetterNumberLine]]'))?></td>
        <td><?php echo $xform->input('rule',array('options'=>$strategies))?></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <i  title="save" class="icon-save"></i>
            </a>
            <a id="delete" title="Exit">
                <i class='icon-remove'></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end()?>
