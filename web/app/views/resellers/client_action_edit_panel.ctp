<?php echo $form->create('ResellerClient')?>
<table>
    <tr>
        <td><?php echo $xform->input('login_id',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('password',array('maxlength'=>256, 'type'=>'password'))?></td>
        <?php if ($login_type == 1): ?>
        <td><?php echo $xform->input('reseller_id',array('options' => $resellers))?></td>
        <?php endif; ?>
        <td></td>
        <td><?php echo $xform->input('email',array('maxlength'=>256))?></td>
        <td></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <img title="save" src="<?php echo $this->webroot?>images/menuIcon_004.gif"> 
            </a>
            <a id="delete" title="Exit">
                <i class='icon-remove'></i>
            </a>
        </td>
    </tr>
    
</table>
<?php echo $form->end()?>
