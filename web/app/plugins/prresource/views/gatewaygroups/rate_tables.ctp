<div class="dialog_form">
    <form id="send_rate_form" action="<?php echo $this->webroot ?>prresource/gatewaygroups/send_rate/" method="post">
        <input type="hidden" name="resource_id" value="<?=$resource_id?>" class="border_no">
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
            <tr>
                <th><input type="checkbox" value="" class="selectAll border_no"></th>
                <th><?php __('Teach Prefix')?></th>
                <th><?php __('Rate Table ID')?></th>
                <th><?php __('Rate Table Name')?></th>
                <th><?php __('Client Name')?></th>
                <th><?php __('Client`s Rate Email')?></th>
                <th><?php __('Rate Email Template')?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($resources as $resource):
            ?>
                <tr>
                    <td><input type="checkbox" name="rate_table_ids[]" value="<?=$resource['RateTable']['rate_table_id']?>" class="border_no"></td>
                    <td><?=$resource['ResourcePrefix']['tech_prefix']?></td>
                    <td><?=$resource['RateTable']['rate_table_id']?></td>
                    <td><?=$resource['RateTable']['name']?></td>
                    <td><?=$resource['Client']['name']?></td>
                    <td><?=$resource['Client']['rate_email']?></td>
                    <td>
                        <?php echo $xform->input('email_template',array('name' => 'email_template[' . $resource['RateTable']['rate_table_id'] . ']','type'=>'select',
                            'class' =>'email_template','options' => $rate_email_template,'default' => 'save_temporary')); ?>
                        <a href="javascript:void(0)" title="<?php __('notify of send rate by template'); ?>"><i class="icon-question-sign"></i></a>
                    </td>
                </tr>
            <?php endforeach;
            ?>
            </tbody>
        </table>
    </form>


</div>