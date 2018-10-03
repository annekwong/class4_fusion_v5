<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>traffic_alert/index"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>traffic_alert/index">
        <?php echo __('Traffic Alert Rule', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Traffic Alert Rule', true); ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>traffic_alert/add_rule">
        <i></i>
        <?php __('Create New') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="clearfix"></div>

        <div class="widget-body">
            <?php
            if (count($data) == 0)
            {
                ?>
                <br />
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <?php
            }
            else
            {
                ?>
                <div class="clearfix"></div>
                <fieldset>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <th><?php __('Carriers') ?></th>
                            <th><?php __('Destination') ?></th>
                            <th><?php echo $appCommon->show_order('less_hour', __('Previous Hour Attempt Less Than', true)) ?></th>
                            <th><?php echo $appCommon->show_order('greater_hour', __('Current Hour Attempt Greater Than', true)) ?></th>
                            <th><?php echo $appCommon->show_order('email', __('Email To', true)) ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($data as $item)
                        {
                            ?>
                            <tr>
                                <td>
                                    <a class="carrier_detail" rule_id ="<?php echo $item['TrafficAlert']['id']; ?>" href="javascript:void(0)"><?php __('Detail') ?></a>
                                </td>
                                <td>
                                    <a class="des_detail" title="<?php echo str_replace(",", "<br />", $item['TrafficAlert']['code_name']); ?>" rule_id ="<?php echo $item['TrafficAlert']['id']; ?>" href="javascript:void(0);">
                                        <?php echo substr($item['TrafficAlert']['code_name'], 0, 10); ?>...
                                    </a>
                                </td>
                                <td><?php echo $item['TrafficAlert']['less_hour']; ?></td>
                                <td><?php echo $item['TrafficAlert']['greater_hour']; ?></td>
                                <td><?php echo $item['TrafficAlert']['email']; ?></td>
                                <td>
                                    <?php if ($item['TrafficAlert']['active']): ?>
                                        <a title="<?php __('Inactive') ?>" onclick="return myconfirm('<?php __('sure to inactive'); ?>',this);"
                                           href="<?php echo $this->webroot; ?>traffic_alert/disable/<?php echo base64_encode($item['TrafficAlert']['id']) ?>" >
                                            <i class="icon-check"></i>
                                        </a>
                                    <?php else: ?>
                                        <a title="<?php __('Active') ?>"  onclick="return myconfirm('<?php __('sure to active'); ?>',this);"
                                           href="<?php echo $this->webroot; ?>traffic_alert/enable/<?php echo base64_encode($item['TrafficAlert']['id']) ?>" >
                                            <i class="icon-unchecked"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo $this->webroot; ?>traffic_alert/edit_rule/<?php echo base64_encode($item['TrafficAlert']['id']); ?>" title="<?php __('Edit')?>">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a onclick="return myconfirm('Are you sure to delete the rule?', this);" href="<?php echo $this->webroot; ?>traffic_alert/delete/<?php echo base64_encode($item['TrafficAlert']['id']); ?>" title="<?php __('Delete')?>">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </fieldset>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function() {

        $(".carrier_detail").click(function() {
            var id = $(this).attr('rule_id');
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            $dd.load('<?php echo $this->webroot; ?>traffic_alert/ajax_get_carrier_name/' + id,
                function(responseText, textStatus, XMLHttpRequest) {
                    $dd.dialog({
                        'width': '300px'
                    });
                });
        });


    })

</script>


