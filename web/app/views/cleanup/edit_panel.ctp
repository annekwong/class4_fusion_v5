<?php echo $form->create('Cleanup')?>
<table>
    <tr>
        <td><?php echo $this->data['Cleanup']['name']; ?></td>
        <td><?php echo $xform->input('backup_frequency',array('style'=>'max-width:120px','options'=>array_combine(range(1, 3), array('Daily','Weekly','Monthly'))));?></td>
        <!--<td><?php echo $xform->input('data_size',array('style'=>'max-width:120px','options'=>array_combine(range(1, 30), range(1, 30))));?></td>-->
        <td><?php echo $xform->input('data_cleansing_frequency',array('style'=>'max-width:120px','options'=>array_combine(range(1, 30), range(1, 30))));?></td>
        <!--<td><?php echo $xform->input('data_removal',array('style'=>'max-width:120px','options'=>array_combine(range(1, 30), range(1, 30))));?></td>
        <td><?php echo $xform->input('ftp_server',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('ftp_user',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('ftp_password',array('maxlength'=>256))?></td>-->
        <td></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <i class="icon-edit"></i>
            </a>
            <a id="delete" title="Exit">
                <i class='icon-remove'></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end()?>
