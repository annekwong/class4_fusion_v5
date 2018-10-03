<table class="list footable table tableTools table-bordered  table-white">
    <thead></thead>
    <tr>
        <td class="right"><?php __('SIP Profile Name'); ?></td>
        <td>
            <select name="data[switch_profile]" id="switch_profile_select">
                <?php foreach ($switch_profile_arr as $switch_profile): ?>
                    <option value="<?php echo $switch_profile['SwitchProfile']['id']; ?>" ip="<?php echo $switch_profile['SwitchProfile']['sip_ip']; ?>">
                        <?php echo $switch_profile['SwitchProfile']['profile_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="right"><?php __('SIP IP'); ?></td>
        <td class="sip_ip">
        </td>
    </tr>
    <tr>
        <td class="right"><?php __('Ingress Trunk Name'); ?></td>
        <td>
            <?php echo $xform->input('ingress',array('type' => 'select','multiple' => 'multiple','options' => $ingress_arr,)); ?>
        </td>
    </tr>
</table>
<script type="text/javascript">
    $(function(){
        $("#switch_profile_select").change(function () {
            var ip = $(this).find('option:checked').attr('ip');
            $(this).closest('tr').next().find('.sip_ip').html(ip);
        }).trigger('change');
    });
</script>