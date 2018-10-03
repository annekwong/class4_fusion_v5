<tr>
    <td class="align_right padding-r10"><?php __('Enforce CID Block') ?></td>
    <td>
        <?php
        isset($post['Gatewaygroup']['enfource_cid']) && $post['Gatewaygroup']['enfource_cid'] ? $au = 'true' : $au = 'false';
        echo $form->input('enfource_cid', array('options' => array('true' => __('True', true), 'false' => __('False', true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $au));
        ?>
    </td>
</tr>
<tr class='cid_blocking'>
    <td class="align_right padding-r10"><?php __('CID Block Min ASR') ?></td>
    <td>
       <?php echo $form->input('cid_min_asr', array('class' => 'width220 validate[min[1],max[60],custom[onlyNumberSp]]', 'id' => 'asr_value', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => isset($post ['Gatewaygroup']['cid_min_asr']) ? $post['Gatewaygroup']['cid_min_asr'] : '')); ?>
    </td>
</tr>
<tr class='cid_blocking'>
    <td class="align_right padding-r10"><?php __('CID Block Min ACD') ?></td>
    <td>
       <?php echo $form->input('cid_min_acd', array('class' => 'width220 validate[min[1],max[60],custom[onlyNumberSp]]', 'id' => 'cid_min_acd', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => isset($post ['Gatewaygroup']['cid_min_acd']) ? $post['Gatewaygroup']['cid_min_acd'] : '')); ?>
    </td>
</tr>
<tr class='cid_blocking'>
    <td class="align_right padding-r10"><?php __('CID Block Max SDP') ?></td>
    <td>
       <?php echo $form->input('cid_max_sdp', array('class' => 'width220 validate[min[1],max[60],custom[onlyNumberSp]]', 'id' => 'cid_max_sdp', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => isset($post ['Gatewaygroup']['cid_max_sdp']) ? $post['Gatewaygroup']['cid_max_sdp'] : '')); ?>
    </td>
</tr>
<script type="text/javascript">
    $('#GatewaygroupEnfourceCid').on('change', function(){
        if($(this).val() == 'true'){
            $('.cid_blocking').show();
        }else{
            $('.cid_blocking').hide();
        }
    }).trigger('change');
</script>
