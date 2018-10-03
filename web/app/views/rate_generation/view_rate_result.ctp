<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tool') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Rate Generation Result') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Generation Result') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons remove" href="javascript:void(0)"><i></i> <?php echo __('Delete Selected') ?></a>
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>rate_generation/rate_template"><i></i><?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active"><a href="<?php echo $this->webroot ?>rate_generation/view_rate_result/<?php echo $this->params['pass'][0]; ?>" class="glyphicons list"><i></i> <?php echo __('List', true); ?></a></li>
                <li><a class="glyphicons download" href="<?php echo $this->webroot?>down/rate_generation_result/<?php echo $this->params['pass'][0]; ?>"><i></i> <?php echo __('Export',true);?></a></li>
            </ul>

        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form action="" method="get">
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" title="<?php __('Fuzzy matching number, country and code name'); ?>" name="code_search" value="<?php echo $appCommon->_get('code_search'); ?>" />
                    </div>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <!--            <form action="--><?php //echo $this->webroot; ?><!--rate_generation/edit_generation_rate_result" method="post">-->
            <input type="hidden" value="<?php echo $this->params['pass'][0] ?>" name="url_params" />
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="rate_table">
                    <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="check-all" />
                        </th>
                        <th><?php echo $appCommon->show_order('code', __('Code', true)) ?></th>
                        <th><?php __('Code Name') ?></th>
                        <th><?php __('Country') ?></th>
                        <?php if($rate_table_type == '1'){ ?>
                            <th><?php __('IJ Rate') ?></th>
                            <th><?php __('Intra Rate') ?></th>
                            <th><?php __('Inter Rate') ?></th>
                            <th><?php __('Local Rate') ?></th>
                        <?php }elseif($rate_table_type == '2'){ ?>
                            <th><?php __('IJ Rate') ?></th>
                        <?php }else{ ?>
                            <th><?php __('Rate') ?></th>
                        <?php } ?>
                        <th><?php __('Min Time') ?></th>
                        <th><?php __('Interval') ?></th>
                        <!--th><?php __('Setup Fee') ?></th>
                        <th><?php __('Grace Time') ?></th>
                        <th><?php __('Seconds') ?></th>
                        <th><?php __('Profile') ?></th-->
                        <th><?php __('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->data as $key => $data_item): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="check-single" data-value="<?php echo $data_item['RateGenerationRate']['generation_rate_id']; ?>" />
                            </td>
                            <td><?php echo $data_item['RateGenerationRate']['code'] ?></td>
                            <td><?php echo $data_item['RateGenerationRate']['code_name'] ?></td>
                            <td><?php echo $data_item['RateGenerationRate']['country'] ?></td>
                            <td><?php echo round($data_item['RateGenerationRate']['rate'],6); ?></td>
                            <?php if($rate_table_type == '1'): ?>
                                <td><?php echo round($data_item['RateGenerationRate']['intra_rate'],6); ?></td>
                                <td><?php echo round($data_item['RateGenerationRate']['inter_rate'],6); ?></td>
                                <td><?php echo round($data_item['RateGenerationRate']['local_rate'],6); ?></td>
                            <?php endif; ?>
                            <td><?php echo $data_item['RateGenerationRate']['min_time']; ?></td>
                            <td><?php echo $data_item['RateGenerationRate']['interval']; ?></td>
                            <!--td><?php echo round($data_item['RateGenerationRate']['setup_fee'],6); ?></td>
                            <td><?php echo $data_item['RateGenerationRate']['grace_time']; ?></td>
                            <td><?php echo $data_item['RateGenerationRate']['seconds']; ?></td>
                            <td><?php echo isset($time_profile[$data_item['RateGenerationRate']['time_profile_id']]) ? $time_profile[$data_item['RateGenerationRate']['time_profile_id']] : ''; ?></td-->
                            <td>
                                <a title="<?php __('edit'); ?>" data-value="<?php echo $data_item['RateGenerationRate']['generation_rate_id']; ?>" href="javascript:void(0)" class="edit_rate">
                                    <i class="icon-edit"></i>
                                </a>
                                <a title="<?php __('View LCR'); ?>" data-value="<?php echo $data_item['RateGenerationRate']['generation_rate_id']; ?>"
                                   href="#myModalViewLCR" data-toggle="modal" class="view_lcr">
                                    <i class="icon-list"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
            <!--                <div class="center separator">-->
            <!--                    <input id="sub" type="submit" value="--><?php //echo __('Submit', true); ?><!--" class="input in-button btn btn-primary"/>-->
            <!--                    <input type="reset" value="--><?php //echo __('Cancel', true); ?><!--" class="input in-button btn btn-default"/>-->
            <!--                </div>-->
            <!--            </form>-->
        </div>
    </div>
</div>
<div id="myModalViewLCR" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('LCR Rate'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-inverse"><?php __('Close'); ?></a>
    </div>

</div>

<script type="text/javascript">
    $(function(){

        $('a.edit_rate').click(function() {
            $(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>rate_generation/rate_result_panel/' + $(this).data('value'),
                ajax: '<?php echo $this->webroot ?>rate_generation/rate_result_panel/' + $(this).data('value'),
                saveType: 'edit',
                onsubmit: function(options)
                {
                    var validate_flg = $("#trAdd").find('input[class*=validate]').validationEngine('validate');
                    if(validate_flg){
                        return false;
                    }
                    return true;
                }
            });
        });

        $("a.view_lcr").click(function(){
            $("#myModalViewLCR").find('.modal-body').load('<?php echo $this->webroot; ?>rate_generation/ajax_get_lcr_rate',{'rate_id':$(this).data('value'), 'lcr_digit':'<?php echo $lcr_digit;?>'});
        });



        $("#rate_table .check-all").click(function () {
            var checked = $(this).is(':checked');
            $('#rate_table .check-single').attr('checked',checked);
        });

        $("a.delete_selected").click(function(){
            if ($("#rate_table .check-single:checked").size() == 0){
                jGrowl_to_notyfy('<?php __('Please select at least one record'); ?>',{'theme':'jmsg-error'});
                return false;
            }
            bootbox.confirm('<?php __('sure to delete'); ?>', function(result) {
                if(result) {
                    var id_arr = new Array();
                    $("#rate_table .check-single:checked").each(function(){
                        id_arr.push($(this).data('value'));
                    });
                    var id_str = id_arr.join(',');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $this->webroot; ?>rate_generation/ajax_delete_result",
                        data: {'id_str':id_str},
                        dataType : 'json',
                        success: function(data){
                            if (data.status == 1){
                                jGrowl_to_notyfy(data.msg,{'theme':'jmsg-success'});
                                $("#rate_table .check-single:checked").closest('tr').remove();
                            }else{
                                jGrowl_to_notyfy(data.msg,{'theme':'jmsg-error'});
                            }
                        }
                    });

                }
            });
        });

    });

    <?php if(!$this->params['hasGet']): ?>
    $.last_running_function = function(){
        <?php if($rate_table_type == '1'): ?>
        $(".ColVis_collection").find('.ColVis_radio').eq(9).find('input[checked="checked"]').parents('button').click();
        $(".ColVis_collection").find('.ColVis_radio').eq(10).find('input[checked="checked"]').parents('button').click();
        $(".ColVis_collection").find('.ColVis_radio').eq(11).find('input[checked="checked"]').parents('button').click();
        $(".ColVis_collection").find('.ColVis_radio').eq(12).find('input[checked="checked"]').parents('button').click();
        <?php else: ?>
        $(".ColVis_collection").find('.ColVis_radio').eq(6).find('input[checked="checked"]').parents('button').click();
        $(".ColVis_collection").find('.ColVis_radio').eq(7).find('input[checked="checked"]').parents('button').click();
        $(".ColVis_collection").find('.ColVis_radio').eq(8).find('input[checked="checked"]').parents('button').click();
        $(".ColVis_collection").find('.ColVis_radio').eq(9).find('input[checked="checked"]').parents('button').click();
        <?php endif; ?>
//        $(".ColVis_collection").find('.ColVis_radio').find('input[checked="checked"]').parents('button').click();
        var button = $('.ColVis_collection button');
    }
    <?php endif; ?>


</script>