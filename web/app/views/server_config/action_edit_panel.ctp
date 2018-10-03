<?php echo $form->create('ServerConfig')?>
<table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
    <tr>
        <td><?php echo $xform->input('name',array('id'=>'name','maxlength'=>256))?></td>
<!--        <td></td>-->
        <td><?php echo $xform->input('lan_ip',array('id'=>'lan_ip','maxlength'=>256))?></td>
        <td><?php echo $xform->input('lan_port',array('id'=>'lan_port','maxlength'=>256))?></td>
        <!--        <td>--><?php //echo $xform->input('active_call_ip',array('id'=>'active_call_ip','maxlength'=>256))?><!--</td>-->
        <!--        <td>--><?php //echo $xform->input('active_call_port',array('id'=>'active_call_port','maxlength'=>256))?><!--</td>-->
        <!--td><?php echo $xform->input('paid_replace_ip',array('type'=>'checkbox'))?></td-->
        <!--        <td><?php echo $xform->input('sip_capture_ip',array('id'=>'sip_capture_ip','maxlength'=>256))?></td>
        <td><?php echo $xform->input('sip_capture_port',array('id'=>'sip_capture_port','maxlength'=>256))?></td>
        <td><?php echo $xform->input('sip_capture_path',array('id'=>'sip_capture_path','maxlength'=>256))?></td>-->
        <td></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <i class="icon-save"></i>
            </a>
            <a id="delete" title="Exit">
                <i class='icon-remove'></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end()?>




