<?php
if (empty($this->data))
{
    ?>
    <?php echo $this->element('listEmpty') ?>
    <div class="overflow_x">
        <table class="list table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
            <thead>
            <tr>
                <th rowspan="2"><input id="selectAll" value="" type="checkbox" onclick="checkAll(this,'mytable .expand');" /></th>
                <th rowspan="2"><?php echo $appCommon->show_order('ResourceBlock.time_profile_id', __('Time Profile', true)) ?> </th>
                <th rowspan="2">Type</th>
                <th rowspan="2"><?php echo $appCommon->show_order('egress_client_id',__('Egress Carriers',true))?> </th>
                <th rowspan="2"><?php echo $appCommon->show_order('engress_res_id',__('Egress Trunk',true))?> </th>
                <th rowspan="2"><?php echo $appCommon->show_order('ingress_client_id', __('Ingress Carrier', true)) ?> </th>
                <th rowspan="2"><?php echo $appCommon->show_order('ingress_res_id', __('Ingress Trunk', true)) ?> </th>
                <th colspan="4"><?php __('ANI'); ?> </th>
                <th colspan="3"><?php __('DNIS'); ?> </th>
                <th rowspan="2"><?php __('Blocked By') ?> </th>
                <th rowspan="2"><?php echo __('Update By', true); ?></th>
                <th rowspan="2"><?php echo __('Create Time', true); ?></th>
                <?php
                if ($_SESSION['role_menu']['Routing']['blocklists']['model_w'])
                {
                    ?><th rowspan="2"><?php echo __('Action') ?></th><?php } ?>
            </tr>
            <tr>
                <th><?php __('Empty'); ?></th>
                <th><?php __('Prefix'); ?></th>
                <th><?php __('Min'); ?></th>
                <th><?php __('Max'); ?></th>
                <th><?php __('Prefix'); ?></th>
                <th><?php __('Min'); ?></th>
                <th><?php __('Max'); ?></th>
            </tr>
            </thead>
        </table>
        <div class="separator"></div>
    </div>
<?php
}
else
{
    ?>
    <div class="clearfix"></div>
    <div class="overflow_x">
        <table id="mytable" class="list table table-striped dynamicTable tableTools table-bordered  table-white table-primary no_float">
            <thead>
            <tr>
                <th rowspan="2"><input id="selectAll" value="" type="checkbox" onclick="checkAll(this,'mytable .expand');" /></th>
                <th rowspan="2"><?php echo $appCommon->show_order('ResourceBlock.time_profile_id', __('Time Profile', true)) ?> </th>
                <th rowspan="2"><?php __('Type'); ?></th>
                <th rowspan="2"><?php echo $appCommon->show_order('egress_client_id',__('Egress Carriers',true))?> </th>
                <th rowspan="2"><?php echo $appCommon->show_order('engress_res_id',__('Egress Trunk (Group)',true))?> </th>
                <th rowspan="2"><?php echo $appCommon->show_order('ingress_client_id', __('Ingress Carrier', true)) ?> </th>
                <th rowspan="2"><?php echo $appCommon->show_order('ingress_res_id', __('Ingress Trunk (Group)', true)) ?> </th>

                <th colspan="4"><?php __('ANI'); ?> </th>
                <th colspan="3"><?php __('DNIS'); ?> </th>
                <th rowspan="2"><?php __('Blocked By') ?> </th>
                <th rowspan="2"><?php echo __('Update By', true); ?></th>
                <th rowspan="2"><?php echo __('Create Time', true); ?></th>
                <?php
                if ($_SESSION['role_menu']['Routing']['blocklists']['model_w'])
                {
                    ?><th rowspan="2"><?php echo __('Action') ?></th><?php } ?>
            </tr>
            <tr>
                <th><?php __('Empty'); ?></th>
                <th><?php __('Prefix'); ?></th>
                <th><?php __('Min'); ?></th>
                <th><?php __('Max'); ?></th>
                <th><?php __('Prefix'); ?></th>
                <th><?php __('Min'); ?></th>
                <th><?php __('Max'); ?></th>
            </tr>
            </thead>
            <tbody id="list_id">
            <?php
            foreach ($this->data as $list)
            {
                ?>
                <tr >
                    <td>
                        <input selectDelete id="<?php echo $list['ResourceBlock']['res_block_id'] ?>" value="<?php echo $list['ResourceBlock']['res_block_id'] ?>" type="checkbox"/>
                    </td>
                    <td><?php echo array_keys_value($list, 'TimeProfile.name') ?></td>
                    <td>
                        <?php
                        if($list['ResourceBlock']['type'] == 2) {
                            echo "Block By Trunk Group";
                        } else {
                            echo "Block By Trunk";
                        }
                        ?>
                    </td>

                    <!--		<td style="width:10%"><?php echo $list['ResourceBlock']['res_block_id'] ?></td>-->
                    <td><?php echo array_keys_value($list, 'EgressClient.name') ?></td>
                    <td>
                        <?php
                        if(!(empty($list['ResourceBlock']['egress_group_id']))) {
                            echo $list['ResourceBlock']['egress_group_name'];
                        } else {
                            echo array_keys_value($list, 'Egress.alias');
                        }
                        ?>
                    </td>
                    <td><?php echo array_keys_value($list, 'IngressClient.name') ?></td>
                    <td>
                        <?php
                        if(!(empty($list['ResourceBlock']['ingress_group_id']))) {
                            echo $list['ResourceBlock']['ingress_group_name'];
                        } else {
                            echo array_keys_value($list, 'Ingress.alias');
                        }
                        ?>
                    </td>
                    <td>
                        <input type="checkbox" disabled="disabled" <?php if ($list['ResourceBlock']['ani_empty']): ?>checked="checked"<?php endif; ?> />
                    </td>
                    <td>
                        <?php echo array_keys_value($list, 'ResourceBlock.ani_prefix'); ?>
                    </td>
                    <td><?php echo array_keys_value($list, 'ResourceBlock.ani_length'); ?></td>
                    <td><?php echo array_keys_value($list, 'ResourceBlock.ani_max_length'); ?></td>
                    <td>
                        <?php
                        if ($_SESSION['role_menu']['Routing']['blocklists']['model_w'])
                        {
                            ?>
                            <?php echo array_keys_value($list, 'ResourceBlock.digit') ?>
                            <?php
                        }
                        else
                        {
                            echo array_keys_value($list, 'ResourceBlock.digit');
                        }
                        ?>

                    </td>
                    <td>
                        <?php echo array_keys_value($list, 'ResourceBlock.dnis_length'); ?>
                    </td>
                    <td>
                        <?php echo array_keys_value($list, 'ResourceBlock.dnis_max_length'); ?>
                    </td>
                    <td>
                        <?php if(isset($block_action_type[array_keys_value($list, 'ResourceBlock.action_type')]))
                        {
                            if (array_keys_value($list, 'ResourceBlock.action_type') == 0) {
                                echo $block_action_type[array_keys_value($list, 'ResourceBlock.action_type')] .
                                    ' [' . array_keys_value($list, 'ResourceBlock.create_by') . ']';
                            } else {
                                echo $block_action_type[array_keys_value($list, 'ResourceBlock.action_type')];
                            }
                        }else{
                            __('automatic');
                        }
                        ?>
                    </td>
                    <!--                    <td data-hide="phone,tablet"  style="display: table-cell;">-->
                    <!--                        --><?php //echo array_keys_value($list, 'ResourceBlock.block_on'); ?>
                    <!--                    </td>-->
                    <td>
                        <?php echo array_keys_value($list, 'ResourceBlock.update_by'); ?>
                    </td>
                    <td>
                        <?php echo array_keys_value($list, 'ResourceBlock.create_time'); ?>
                    </td>
                    <?php
                    if ($_SESSION['role_menu']['Routing']['blocklists']['model_w'])
                    {
                        ?><td>
                        <a title="<?php echo __('edit')?>" class="edit" href="javascript:void(0)" res_block_id="<?php echo $list['ResourceBlock']['res_block_id'] ?>">
                            <i class="icon-edit"></i>
                        </a>
                        <a title="<?php echo __('del') ?>" class="delete" onclick="myconfirm('<?php __('sure to delete'); ?>',this);return false;" href="<?php echo $this->webroot ?>blocklists/del/<?php echo $list['ResourceBlock']['res_block_id'] ?>">
                            <i class="icon-remove"></i>
                        </a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="separator"></div>
    </div>
    <div class="row-fluid separator">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element("xpage") ?>
        </div>
    </div>
    <div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
    function checkint(input, name)
    {
        var re = /^[0-9]+$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
        if (!re.test(input))
        {
            //jGrowl_to_notyfy(name + " must be an integer.", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    jQuery('.edit').click(
        function () {
            var res_block_id = jQuery(this).attr('res_block_id');
            global_option = {};
            global_option.id = res_block_id;
            jQuery(this).parent().parent().trAdd(
                {
                    ajax: "<?php echo $this->webroot ?>blocklists/js_save?id=" + res_block_id,
                    action: "<?php echo $this->webroot ?>blocklists/edit/" + res_block_id,
                    saveType: 'edit',
                    callback:function(options){$('.method_select').trigger('change');return blocklist.trAddCallback(options);},
                    onsubmit:function(options){
                        var ani_length = $("#ani_length").val();
                        var dnis_length = $("#dnis_length").val();
                        var ani_max_length = $("#ani_max_length").val();
                        var dnis_max_length = $("#dnis_max_length").val();
                        if(ani_length){
                            checkint(ani_length,'<?php __('ANI Min Length'); ?>');
                        }
                        if(ani_max_length){
                            checkint(ani_max_length,'<?php __('ANI Max Length'); ?>');
                        }
                        if(dnis_length){
                            checkint(dnis_length,'<?php __('DNIS Min Length'); ?>');
                        }
                        if(dnis_max_length){
                            checkint(dnis_max_length,'<?php __('DNIS Max Length'); ?>');
                        }
                        if(ani_length && ani_max_length){
                            if(parseInt(ani_length) > parseInt(ani_max_length)){
                                jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), __('ANI Max Length',true),__('ANI Min Length',true)); ?>', {theme: 'jmsg-error'});
                                return false;
                            }
                        }
                        if(dnis_length && dnis_max_length) {
                            if (parseInt(dnis_length) > parseInt(dnis_max_length)) {
                                jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), __('DNIS Max Length',true),__('DNIS Min Length',true)); ?>', {theme: 'jmsg-error'});
                                return false;
                            }
                        }
                        return blocklist.trAddOnsubmit(options);
                    }
                }
            );


            return false;
        }
    );
</script>

<script type="text/javascript">
    $(function () {
        var data_count = "<?php echo count($this->data); ?>";
        if(!data_count)
        {
            $("#add").click();
        }
    });
</script>

<script type="text/javascript">
    $(function () {

//        $(".delete").click(function () {
//            var url = $(this).attr('url');
//
//            bootbox.confirm('Are you sure to delete the item ?', function (result) {
//                if (result) {
//                    window.location.href = url;
//                }
//            });
//        });
        $('.prefix_chk').live('change', function () {
            var $this = $(this);
            $this.siblings().val('').attr('disabled', $this.attr('checked'));
        });

        var $method_select = $('.method_select');


        $method_select.live('change', function () {
            var $this = $(this);
            var val = $this.val();
            if (val == '') {
                $this.next().hide();
            } else {
                $this.next().show();
            }
        });

        $('#selectAll').on('click', function() {
            if($(this).attr('checked') == 'checked'){
                $('tr>td:first-child>.border_no').attr('checked','checked');
            }else{
                $('tr>td:first-child>.border_no').removeAttr('checked');
            }
        })
    });
</script>