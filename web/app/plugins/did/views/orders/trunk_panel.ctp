<?php echo $form->create('Resource')?>
<table>
    <tr>
        <td><?php echo $xform->input('name',array('maxlength'=>256, 'value' => isset($this->data['Resource']['alias']) ? $this->data['Resource']['alias'] : ''))?></td>
        <?php 
            if (isset($this->data['ResourceIp']['ip'])):
                $ip_info = explode('/', $this->data['ResourceIp']['ip']);
            else:
                $ip_info = array('', '');
            endif;
        ?>
        <td><?php echo $xform->input('ip',array('maxlength'=>256, 'style'=>'width:80px;', 'value' => isset($ip_info[0]) ? $ip_info[0] : ''))?></td>
        <td><?php echo $xform->input('mask',array('maxlength'=>256, 'style'=>'width:40px;', 'value' => isset($ip_info[1]) ? $ip_info[1] : ''))?></td>
        <td><?php echo $xform->input('prefix',array('maxlength'=>256, 'type'=>'text', 'style'=>'width:80px;', 'value' => isset($this->data['ResourceDirection']['digits']) ? $this->data['ResourceDirection']['digits'] : ''))?></td>
    
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
