<style type="text/css">

    input[type="text"] {
        width: 220px;
    }
    .tr_unread{
        font-weight:bold;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/dashboard">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/dashboard">
        <?php echo __('Dashboard') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/alert">
        <?php echo __('Alert') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Alert'); ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<!--
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons eye_open" onclick="read_selected('alert_tbody');" href="javascript:void(0)"><i></i> <?php __('Read Selected')?></a>
    <a class="btn btn-primary btn-icon glyphicons eye_open" href="javascript:void(0)"><i></i><?php __('Read All')?></a>
</div>
-->
<div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard" class="glyphicons dashboard">
                        <i></i><?php __('Dashboard') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/report" class="glyphicons stats">
                        <i></i><?php __('Report') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/search_charts" class="glyphicons charts">
                        <i></i><?php __('Charts') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/auto_delivery" class="glyphicons stroller">
                        <i></i><?php __('Auto Delivery') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/qos_report" class="glyphicons notes">
                        <i></i><?php __('Qos Report') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/ingress"  class="glyphicons eye_open">
                        <i></i><?php __('Ingress Clients Qos')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/egress"  class="glyphicons eye_open">
                        <i></i><?php __('Egress Clients Qos')?>
                    </a>
                </li>
<!--                <li class="active">-->
<!--                    <a href="--><?php //echo $this->webroot ?><!--homes/alert" class="glyphicons alarm">-->
<!--                        <i></i>--><?php //__('Alert') ?>
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <?php echo $form->input('viewed',array('type' => 'select','options' => array(2 => __('All',true), 0 =>__('Unread',true), 1 => __('Read',true)),
                        'selected'=> $appCommon->_get('viewed'),'class' => 'width120','name' => 'viewed')); ?>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <?php
                    $select_type_arr = $type_name;
                    array_unshift($select_type_arr,'All');
                    echo $form->input('type',array('type' => 'select','options' => $select_type_arr,
                        'selected'=> $appCommon->_get('type'),'name' => 'type')); ?>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>
            <div id="report_box">
                <?php if (!count($this->data)): ?>
                    <div class="msg center">
                        <br />
                        <h2>
                            <?php echo __('no data found', true); ?>
                        </h2>
                    </div>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                        <thead>
                        <tr>
                            <th>
                                <?php echo $appCommon->show_order('create_time', __('Time', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('type', __('Type', true)) ?>
                            </th>
                            <th>
                                <?php echo __('Content', true) ?>
                            </th>
                            <th><?php __('Last Read') ?></th>
                            <th><?php __('Read By') ?></th>
                            <th>
                                <?php __('Action')?>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="alert_tbody">
                        <?php foreach($this->data as $item):  ?>
                            <tr class="tr_alerts <?php if(!$item['AdminAlert']['is_view']): ?>tr_unread<?php endif; ?>">
                                <td><?php echo $item['AdminAlert']['create_time'] ?></td>
                                <td><?php echo $type_name[ $item['AdminAlert']['type'] ] ?></td>
                                <td><?php echo $item['AdminAlert']['subject'] ?></td>
                                <td><?php echo $item['AdminAlert']['view_time'] ?></td>
                                <td><?php echo $item['AdminAlert']['view_by'] ?></td>
                                <td>
                                    <a data-toggle="modal" class="a_modal_alerts" href="#modal_alerts" title="Read" value="<?php echo $item['AdminAlert']['id']?>">
                                        <i class="icon-eye-open"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <div class="row-fluid separator">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div>
                    </div>

                <?php endif;?>





                <div class="clearfix"></div>

            </div>

            <div id="modal_alerts" class="modal hide">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">&times;</button>
                    <h3 id="title"></h3>
                </div>
                <div class="modal-body">
                    <p id="body" style="font-size: 16px;"></p>
                </div>
            </div>



        </div>
    </div>
</div>
<script type="text/javascript">



    $(function(){

        $('.a_modal_alerts').click(function(){
            var $this = $(this);
            var alerts_num = $('.a_modal_alerts').index($(this));
            var value = $(this).attr('value');
            $('#modal_alerts').attr('value', alerts_num);
            $('#modal_alerts').on('shown',function(){
                var model = $(this);

                $.getJSON(
                    '<?php echo $this->webroot?>homes/ajax_admin_alert_view/'+value,

                    function(data){
                        //res = data;
                        $('#modal_alerts').find('#body').html(data['body']);
                        $('#modal_alerts').find('#title').html(data['title']);
                        $this.closest('tr').find('td').eq(3).html(data['view_time']);
                        $this.closest('tr').find('td').eq(4).html(data['view_by']);
                    }
                )
            })
        });

        $('#modal_alerts').on('hide', function () {

            var num = $('#modal_alerts').attr('value');
            var is_unread = $('.tr_alerts:eq('+num+')').hasClass('tr_unread');
            if($(".filter-bar").find("select[name='viewed']").find('option:selected').val() == 0){
                $('.tr_alerts:eq('+num+')').remove();
            }else{
                $('.tr_alerts:eq('+num+')').removeClass('tr_unread');
            }

            $('#modal_alerts').unbind('shown');
            if(is_unread)
            //tip
            {
                var val = $('#alert_tip').text();
                val = parseInt(val) - 1;
                $('#alert_tip').text(val);
            }
        });



    })
</script>