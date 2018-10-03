<form method="post">
    <table>
        <tr>
<!--            <td><input type="checkbox" /></td>-->
            <td>
                <select name="voip_gateway" id="voip_gateway">
                    <?php foreach ($voipGateways as $item): ?>
                        <option value="<?php echo $item['ServerConfig']['id']; ?>" ><?php echo $item['ServerConfig']['name']; ?></option>
                    <?php endforeach; ?>

                </select>
            </td>
            <td>
                <select name="ingress_id">
                    <option>All</option>
                    <?php foreach ($ingressTrunks as $item): ?>
                        <option value="<?php echo $item[0]['resource_id']; ?>" ><?php echo $item[0]['alias']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select name="sip_profile" id="sip_profile"></select>
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

        var value = $("#voip_gateway").val();
        $("#sip_profile option").remove();
        $.post('<?php echo $this->webroot ?>prresource/gatewaygroups/get_sip_by_gateway/<?php echo $resource_id; ?>', {
            'gatewayId': value
        }, function (data) {
            let result = $.parseJSON(data);
            $.each(result, function(item, value) {
                $("#sip_profile").append("<option value='" + value.SwitchProfile.id + "'>" + value.SwitchProfile.sip_ip + "</option>");
            });
        });
    }

    $(function () {
        $("#voip_gateway").change(getSips);

        getSips();
    });
</script>
