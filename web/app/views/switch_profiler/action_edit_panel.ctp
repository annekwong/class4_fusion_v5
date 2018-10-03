<?php echo $form->create('SwitchProfile')?>
<?php $class = $isedit ? "input-small no-input-style": "input-small" ;?>
<table>
    <tr>
        <td><?php echo $xform->input('profile_name',array('id'=>'profile_name','maxlength'=>256, 'class'=>'input-small'))?></td>
        <!--<td><?php echo $xform->input('profile_name',array('id'=>'profile_name','maxlength'=>256, 'readonly'=>$isedit, 'class'=>'input-small'))?></td>-->
<!--        <td>--><?php //echo $xform->input('profile_status',array('options'=>array(0 => 'INIT', 1 => 'ACTIVE', 2 => 'DEACTIVE', 3 => 'SHUTDOWN', 4 =>'DESDROY'), 'class'=>'input-small'))?><!--</td>-->
        <td><?php echo $xform->input('sip_ip',array('id'=>'sip_ip','maxlength'=>256, 'readonly'=>$isedit, 'class'=>$class))?></td>
        <td><?php echo $xform->input('sip_port',array('id'=>'sip_port','maxlength'=>256, 'readonly'=>$isedit, 'class'=>$class))?></td>
<!--        <td><?php echo $xform->input('sip_debug',array('options'=>range(0, 9)))?></td>
        <td><?php echo $xform->input('sip_trace', array('type'=>'checkbox'))?></td>-->
        <!--td><?php echo $xform->input('proxy_ip',array('id'=>'proxy_ip','maxlength'=>256, 'class'=>'input-small'))?></td-->
        <!--td><?php echo $xform->input('proxy_port',array('id'=>'proxy_port','maxlength'=>256, 'class'=>'input-small'))?></td-->
        <td><?php echo $xform->input('cps',array('id'=>'cps','maxlength'=>256, 'class'=>'input-small'))?></td>
        <td><?php echo $xform->input('cap',array('id'=>'cap','maxlength'=>256, 'class'=>'input-small'))?></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <i class="icon-save"></i>
            </a>
            <a id="delete" title="Exit">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="14">
            <span class="require_auth"><?php __('Require Authenication')?>:<?php echo $xform->input('auth_register',array('type'=>'checkbox'))?></span>
            <span class="require_auth"><?php __('RPID')?>:<?php echo $xform->input('support_rpid',array('type'=>'checkbox'))?></span>
            <span class="require_auth"><?php __('OLI')?>:<?php echo $xform->input('support_oli',array('type'=>'checkbox'))?></span>
            <span class="require_auth"><?php __('PRIV')?><?php echo $xform->input('support_priv',array('type'=>'checkbox'))?></span>
            <span class="require_auth"><?php __('DIV')?>:<?php echo $xform->input('support_div',array('type'=>'checkbox'))?></span>
            <span class="require_auth"><?php __('PAID')?>:<?php echo $xform->input('support_paid',array('type'=>'checkbox'))?></span>
            <span class="require_auth"><?php __('PCI')?>:<?php echo $xform->input('support_pci',array('type'=>'checkbox'))?></span>
            <span class="require_auth"><?php __('X LRN')?>:<?php echo $xform->input('support_x_lrn',array('type'=>'checkbox'))?></span>
            <span class="require_auth"><?php __('X Header')?>:<?php echo $xform->input('support_x_header',array('type'=>'checkbox'))?></span>
        </td>
    </tr>
</table>
<?php echo $form->end()?>
