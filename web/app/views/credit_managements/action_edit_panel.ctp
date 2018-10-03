<?php echo $form->create('Client')?>
<table>
    <tr>
        <td>
            <?php echo $this->data['Client']['name']  ?>
            <?php echo $xform->input('name',array('type'=>'hidden'))?>
        </td>
        <td>
            <?php if ($this->data['Client']['mode'] == 1){
                echo "--";
            }else{
             echo $xform->input('unlimited_credit',array('type'=>'checkbox'));echo "&nbsp;&nbsp;&nbsp;&nbsp;";__('Unlimited');
            ?>
            <br /><br />
            <input type="text" id="ClientAllowedCredit" value="<?php echo number_format(abs($this->data['Client']['allowed_credit']), 3); ?>"
                   name="data[Client][allowed_credit]" class="width80" <?php if($this->data['Client']['unlimited_credit']): ?>disabled<?php endif; ?> />
        </td>
            <?php } ?>


        <td><?php echo $xform->input('cps_limit',array('maxlength'=>256,'class' => 'width80'))?></td>
        <td><?php echo $xform->input('call_limit',array('maxlength'=>256,'class' => 'width80'))?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="javascript:void(0)" title="Save">
                <i class='icon-save'></i> 
            </a>
            <a id="delete" title="Exit">
                <i class='icon-remove'></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end()?>

<script type="text/javascript">
    $(function(){
        $("#ClientUnlimitedCredit").click(function(){
            var is_checked = $(this).is(":checked");
            if(is_checked){
                $("#ClientAllowedCredit").attr('disabled',true);
            }else{
                $("#ClientAllowedCredit").attr('disabled',false);
            }
        });
    })
</script>
