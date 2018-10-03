<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rerate/create_task">
        <?php echo __('Tool', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rerate/re_rate_log">
        <?php __('Re-rate'); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Re-rate'); ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('re_rate/tabs',array('active' => 'task_log')); ?>
        </div>
        <div class="widget-body">
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <div id="d" class="overflow_x separator" onscroll="$('#main').floatThead('reflow');">
                    <table id="main" class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <th rowspan="2"><?php __('Job ID'); ?></th>
                            <th rowspan="2"><?php echo $appCommon->show_order('create_time', __('Created Time', true)) ?></th>
                            <th rowspan="2"><?php __('Selected Time'); ?></th>
                            <th rowspan="2"><?php __('Process Time'); ?></th>
                            <th rowspan="2"><?php __('Status'); ?></th>
                            <th rowspan="2"><?php __('Process'); ?></th>
                            <th colspan="2"><?php __('Trunk'); ?></th>
                            <!--                        <th>--><?php //__('Replace LRN'); ?><!--</th>-->
                            <th rowspan="2"><?php __('Replace US JD'); ?></th>
                            <th colspan="2"><?php __('Replace Rate'); ?></th>
                            <th colspan="2"><?php __('Effective Date'); ?></th>
                            <th rowspan="2"><?php __('Action'); ?></th>
                        </tr>
                        <tr>
                            <th><?php __('Ingress'); ?></th>
                            <th><?php __('Egress'); ?></th>
                            <th><?php __('Ingress'); ?></th>
                            <th><?php __('Egress'); ?></th>
                            <th><?php __('Ingress'); ?></th>
                            <th><?php __('Egress'); ?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($this->data as $item): ?>
                            <?php
                            $ingress_trunks_show = array();
                            $egress_trunks_show = array();
                            $is_replace_ingress_rate = __('No',true);
                            $is_replace_egress_rate = __('No',true);
                            $ingress_effective_date = __('Day By Day',true);
                            $egress_effective_date = __('Day By Day',true);
                            $ingress_trunk_str = $item['RerateCdrTask']['ingress_trunk'];
                            $tmp_data = explode(';',$ingress_trunk_str);
                            foreach ($tmp_data as $tmp_item){
                                $tmp_item_arr = explode(',',$tmp_item);
                                if (count($tmp_item_arr) > 1) {
                                    if ($tmp_item_arr[1])
                                        $is_replace_ingress_rate = __('Yes', true);
                                    if ($tmp_item_arr[3])
                                        $ingress_effective_date = date('Y-m-d', $tmp_item_arr[3]);
                                    $single_info = explode(',', $tmp_item);
                                    if (isset($ingress_trunks[$single_info[0]]))
                                        $ingress_trunks_show[] = $ingress_trunks[$single_info[0]];
                                }
                            }
                            $ingress_trunks_content = implode(", ",$ingress_trunks_show);

                            $egress_trunk_str = $item['RerateCdrTask']['egress_trunk'];
//                            die(var_dump($egress_trunk_str));
                            $tmp_data = explode(';',$egress_trunk_str);
                            foreach ($tmp_data as $tmp_item){
                                $tmp_item_arr = explode(',',$tmp_item);
                                if (count($tmp_item_arr) > 1) {
                                    if ($tmp_item_arr[1])
                                        $is_replace_egress_rate = __('Yes', true);
                                    if ($tmp_item_arr[3])
                                        $egress_effective_date = date('Y-m-d', $tmp_item_arr[3]);
                                    $single_info = explode(',', $tmp_item);
                                    if (isset($egress_trunks[$single_info[0]]))
                                        $egress_trunks_show[] = $egress_trunks[$single_info[0]];
                                }
                            }
                            $egress_trunks_content = implode(", ",$egress_trunks_show);

                            ?>
                            <tr>
                                <td>#<?php echo $item['RerateCdrTask']['id']; ?></td>
                                <td><?php echo date('Y-m-d H:i:sO',$item['RerateCdrTask']['create_time']); ?></td>
                                <td>
                                    <small>
                                        <?php echo date('Y-m-d H:i:sO',($item['RerateCdrTask']['from_time'] + $item['RerateCdrTask']['timezone']*60)); ?>
                                        <br/>
                                        <?php echo date('Y-m-d H:i:sO',($item['RerateCdrTask']['to_time'] + $item['RerateCdrTask']['timezone']*60)); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if($item['RerateCdrTask']['start_time']): ?>
                                        <small>
                                            <?php echo date('Y-m-d H:i:sO',($item['RerateCdrTask']['start_time'])); ?>
                                            <br/>
                                            <?php if($item['RerateCdrTask']['end_time']): ?>
                                                <?php echo date('Y-m-d H:i:sO',($item['RerateCdrTask']['end_time'])); ?>
                                            <?php endif; ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $status[intval($item['RerateCdrTask']['status'])]; ?></td>
                                <td><?php echo $appCommon->sub_string($item['RerateCdrTask']['progress']); ?></td>
                                <td>
                                    <a href="javascript:void(0)" class="label" data-toggle="popover" 
                                        data-title="<?php echo __('Show Ingress', true) ?>"
                                       data-content="<?php echo $ingress_trunks_content; ?>" data-placement="left">
                                        <?php __('Show Ingress'); ?>
                                    </a>
                                </td>
                                <td><a href="javascript:void(0)" class="label" data-toggle="popover" data-title="<?php __('Show Egress'); ?>"
                                       data-content="<?php echo $egress_trunks_content; ?>" data-placement="left">
                                        <?php __('Show Egress'); ?>
                                    </a></td>
                                <td><?php $item['RerateCdrTask']['update_us_jurisdiction'] ? __('Yes') : __('No'); ?></td>
                                <td><?php echo $is_replace_ingress_rate; ?></td>
                                <td><?php echo $is_replace_egress_rate; ?></td>
                                <td><?php echo $ingress_effective_date; ?></td>
                                <td><?php echo $egress_effective_date; ?></td>
                                <td>
                                    <?php if ($item['RerateCdrTask']['status'] == 4 or $item['RerateCdrTask']['status'] > 5): ?>
                                        <a title="<?php __('Regenerate Report'); ?>" onclick="return myconfirm('<?php __('Are you sure to re-report'); ?>?',this);"
                                           href="<?php echo $this->webroot; ?>rerate/re_report/<?php echo base64_encode($item['RerateCdrTask']['id']); ?>">
                                            <i class="icon-reply"></i>
                                        </a>
                                        <a title="<?php __('Download New CDR'); ?>" href="<?php echo $this->webroot; ?>rerate/download_cdr/<?php echo base64_encode($item['RerateCdrTask']['id']); ?>">
                                            <i class="icon-file"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($item['RerateCdrTask']['status'] >= 8): ?>
                                        <a title="<?php __('Re-Balance'); ?>" onclick="return myconfirm('<?php __('Are you sure to re-balance'); ?>?',this);"
                                           href="<?php echo $this->webroot; ?>rerate/re_balance/<?php echo base64_encode($item['RerateCdrTask']['id']); ?>">
                                            <i class="icon-usd"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator"></div>
                </div>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
            $('.label').popover({
            }).on('shown', function(e){
                var popover =  $(this).data('popover'),
                $tip = popover.tip();
                var close = $('<a class="close" href="#">&times;</a>') 
                    .click(function(){
                        popover.hide();
                    });
                $('.popover-title', $tip).append(close);
            });
    });
</script>
