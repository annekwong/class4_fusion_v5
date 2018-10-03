<?php echo $form->create('Reseller')?>
<table>
    <tr>
        <td><?php echo $xform->input('name',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('login_id',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('password',array('maxlength'=>256, 'type'=>'password'))?></td>
        <td><?php echo $xform->input('email',array('maxlength'=>256))?></td>
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
