<style>
    .row {
        margin-left: 0px;
    }

    .search {
        margin: 10px 0px;
        float: right;
    }

    .search span, .search input, .search button {
        vertical-align: middle;
    }

    .search input {
        margin: 0px;
    }

</style>
<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>Origination</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>Trunks</li>
</ul>

<div>
    <hr/>
</div>

<div class="innerLR">
    <!--NEW HTML-->
    <div class="row">
<!--        <div class="search">-->
<!--            <a href="javascript:void(0)" class="btn btn-primary btn-icon glyphicons circle_plus" id="add">-->
<!--                <i></i>-->
<!--                Create New-->
<!--            </a>-->
<!--        </div>-->
        <table id="didTable" class="footable list table table-striped tableTools table-bordered  table-white table-primary default floatThead-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>IP</th>
                <th>Rate Per Max Channel</th>
                <th>Rate Per Actual Channel</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $item) { ?>
                <tr data-id="<?php echo base64_encode($item['Resource']['resource_id']); ?>">
                    <td><?php echo $item['Resource']['alias']; ?></td>
                    <td>
                        <?php
                        $temp = array();

                        foreach ($item['ResourceIp'] as $resourceIp) {
                            array_push($temp, $resourceIp['ResourceIp']['ip'] . ':' . $resourceIp['ResourceIp']['port']);
                        }
                        echo implode(', ', $temp);
                        ?>
                    </td>
                    <td><?php echo $item['Resource']['price_per_max_channel']; ?></td>
                    <td><?php echo $item['Resource']['price_per_actual_channel']; ?></td>
                    <td><?php echo $appGatewaygroup->getTextStatus($item['Resource']['status']); ?></td>
                    <td>
                        <?php
//                        echo $item['Gatewaygroup']['active'];
                        if ($item['Resource']['active'] == true) { ?>
                            <a href="<?php echo $this->webroot;?>did_client/changeTrunkStatus/<?php echo base64_encode($item['Resource']['resource_id']); ?>/0" title="Deactivate Trunk" class="deactivate">
                                <i class="icon-check"></i>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo $this->webroot;?>did_client/changeTrunkStatus/<?php echo base64_encode($item['Resource']['resource_id']); ?>/1" title="Activate Trunk" class="activate">
                                <i class="icon-check-empty"></i>
                            </a>
                        <?php } ?>

                        <a title="<?php __('Edit') ?>" class="edit_item" href="###">
                            <i class="icon-edit"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    jQuery('#add').click(function() {
        $('.msg').hide();
        $('table.list').show();
        jQuery('table.list tbody').trAdd({
            ajax: "<?php echo $this->webroot ?>did_client/trunk_edit_panel",
            action: "<?php echo $this->webroot ?>did_client/trunk_edit_panel",
            'insertNumber': 'first',
            removeCallback: function() {
                if (jQuery('table.list tr').size() == 1) {
                    jQuery('table.list').hide();
                }
            },
            onsubmit: function() {
//                var did_price = $("#DidBillingPlanDidPrice").val();
//                var min_price = $("#DidBillingPlanMinPrice").val();
//                var name = $("#DidBillingPlanName").val();
//                if (!name)
//                {
//                    jGrowl_to_notyfy("The field name can not be NULL!", {theme: 'jmsg-error'});
//                    return false;
//                }
//                if (did_price)
//                {
//                    if (!checknumber(did_price, 'DID Price'))
//                    {
//                        return false;
//                    }
//                }
//                if (!checknumber(min_price, 'MIN Price'))
//                {
//                    return false;
//                }

                return true;
            }
        });
        jQuery(this).parent().parent().show();
    });

    jQuery('a.edit_item').click(function() {
        jQuery(this).parent().parent().trAdd({
            action: '<?php echo $this->webroot ?>did_client/trunk_edit_panel/' + jQuery(this).parent().parent().data('id'),
            ajax: '<?php echo $this->webroot ?>did_client/trunk_edit_panel/' + jQuery(this).parent().parent().data('id'),
            saveType: 'edit',
            onsubmit: function() {


                return true;
            }
        });
    });
</script>

