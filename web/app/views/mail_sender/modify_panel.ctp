<?php echo $form->create('MailSender') ?>
<table>
    <tr>
        <td><?php echo $xform->input('name', array('maxlength' => 256, 'class' => 'validate[required] input-small')) ?></td>
        <td><?php echo $xform->input('smtp_host', array('maxlength' => 256, 'class' => 'validate[required] input-small')) ?></td>
        <td><?php echo $xform->input('smtp_port', array('maxlength' => 5, 'class' => 'validate[required,custom[onlyNumbersSp], max[65536]] input-small')) ?></td>
        <td><?php echo $xform->input('username', array('maxlength' => 256, 'class' => 'input-small')) ?></td>
        <td><?php echo $xform->input('password', array('maxlength' => 256, 'type' => 'password', 'class' => 'input-small')) ?></td>
        <td><?php echo $xform->input('loginemail', array('options' => array('true' => 'true', 'false' => 'false'), 'class' => 'input-small')) ?></td>
        <td><?php echo $xform->input('secure', array('options' => array(0 => '', 1 => 'TLS', 2 => 'SSL'), 'class' => 'input-small')) ?></td>
        <td><?php echo $xform->input('email', array('maxlength' => 256, 'class' => 'validate[required,custom[email]] input-small')) ?></td>
        <td></td>
        <td></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Save">
                <i class="icon-save"></i>
            </a>
            <a id="delete" title="Exit">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>

