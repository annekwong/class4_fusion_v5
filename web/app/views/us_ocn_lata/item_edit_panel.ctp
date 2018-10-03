<?php echo $form->create('UsOcnLata')?>
<table>
    <tr>
        <td><input type="checkbox" /></td>
        <!-- <td><?php echo $xform->input('ocn',array('maxlength'=>256))?></td> -->
        <!-- <td><?php echo $xform->input('lata',array('maxlength'=>256))?></td> -->
        <td><?php echo $xform->input('npa',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('nxx',array('maxlength'=>256, 'type'=>'text'))?></td>
        <td><?php echo $xform->input('a_block',array('maxlength'=>1))?></td>
        <td>
            <input style="max-width:120px;" type="text"  name="data[UsOcnLata][effective_time]" style="font-weight:bold;text-align:right;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', lang: 'en'})" />
        </td>
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
