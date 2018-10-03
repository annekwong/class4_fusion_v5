<?php echo $form->create('DidAssign')?>
<table>
    <tr>
        <td></td>
        <td><?php echo $ingresses[$this->data['DidAssign']['ingress_id']]; ?></td>
        <td><?php echo $xform->input('egress_id',array('options'=>$egresses))?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <i title="save" class="icon-save"></i> 
            </a>
            <a id="delete" title="Exit">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end()?>

<script>
    $(function() {
        
    });
</script>
