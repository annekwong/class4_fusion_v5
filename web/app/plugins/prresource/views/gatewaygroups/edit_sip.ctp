<form method="post">
    <table>
        <tr>
<!--            <td><input type="checkbox" /></td>-->
            <td>
                <select name="voip_gateway" id="voip_gateway">
                    <?php foreach ($voipGateways as $item): ?>
                        <option value="<?php echo $item['ServerConfig']['id']; ?>" <?php if($itemData['EgressProfile']['server_name'] == $item['ServerConfig']['name']) echo 'selected';?>><?php echo $item['ServerConfig']['name']; ?></option>
                    <?php endforeach; ?>

                </select>
            </td>
            <td>
                <select name="ingress_id">
                    <option <?php if($itemData['EgressProfile']['ingress_id'] == '') echo 'selected'; ?>>All</option>
                    <?php foreach ($ingressTrunks as $item): ?>
                        <option value="<?php echo $item[0]['resource_id']; ?>" <?php if($itemData['EgressProfile']['ingress_id'] == $item[0]['resource_id']) echo 'selected';?>><?php echo $item[0]['alias']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select name="sip_profile" id="sip_profile" data-id="<?php echo $itemData['EgressProfile']['profile_id'];?>"></select>
            </td>
            <td align="center" style="text-align:center" class="last">
                <a id="save" href="###" title="Save" proid = "<?php echo $item[0]['resource_id']; ?>">
                    <i class="icon-save"></i>
                </a>
                <a id="delete" title="Exit">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
    </table>
</form>

<script>
    function getSips() {

        let value = $("#voip_gateway").val();
        $("#sip_profile option").remove();
        let defaultId = $("#sip_profile").data('id');
        $.post('<?php echo $this->webroot ?>prresource/gatewaygroups/get_sip_by_gateway/<?php echo $resource_id; ?>', {
            'gatewayId': value
        }, function (data) {
            let result = $.parseJSON(data);
            $.each(result, function(item, value) {
                let textSelected = value.SwitchProfile.id == defaultId ? 'selected' : '';
                $("#sip_profile").append("<option value='" + value.SwitchProfile.id + "' " + textSelected + ">" + value.SwitchProfile.sip_ip + "</option>");
            });
        });
    }

    $(function () {
        $("#voip_gateway").change(getSips);

        getSips();
    });
</script>