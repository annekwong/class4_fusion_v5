<style>
    .tabsbar.tabsbar-2 ul li.active a i:before {
        /*color: #E5412D;*/
    }

    .btn-primary {
        /*background: #E5412D;*/
        /*border-color: #E5412D;*/
        color: #FFF;
        border: 0px;
    }

    .input-group-btn.open .btn-primary.dropdown-toggle, .btn-primary.disabled, .btn-primary[disabled], .btn-primary:hover, .btn-primary:focus {
        /*background: #DD301B;*/
        color: #FFF;
        /*border-color: #E5412D;*/
        border: 0px;
    }

    .btn-group > .btn:hover {
        z-index: auto;
        border: 0px;
    }

    .btn-group > .btn{
        border: 0px;
    }
    .btn-default:hover {
        /*background: #E5412D;*/
        /*border-color: #E5412D;*/
        color: #ffffff;
        border: 0px;
    }

    .table-primary thead th {

        /*background-color: #E5412D;*/

    }

    h3.glyphicons i:before, h2.glyphicons i:before {
        /*color: #E5412D;*/
    }


    .widget.widget-icon {
        display: block;
        padding: 10px;
        margin-bottom: 0;
    }
    .inner-2x.innerAll {
        padding: 30px !important;
    }
    .widget {
        background: #FFF;
        margin: 0 auto 15px;
        position: relative;
        border: 1px solid #EFEFEF;
    }
    .text-center {
        text-align: center !important;
    }
    .text-regular {
        color: #444 !important;
    }
    .widget.widget-icon i, .widget.widget-icon span {
        color: #FFF;
    }
    .text-xlarge {
        font-size: 50px;
        line-height: 50px;
    }
    .text-xlarge:before {
        font-size: 30pt;
    }
    .display-block {
        display: block !important;
    }
    *, a:focus {
        outline: none !important;
    }


    .widget.widget-icon {
        display: block;
        padding: 10px;
        margin-bottom: 0;
    }
    .inner-2x.innerAll {
        padding: 30px !important;
    }
    .widget {
        background: #FFF;
        margin: 0 auto 15px;
        position: relative;
        border: 1px solid #EFEFEF;
    }
    .text-center {
        text-align: center !important;
    }
    .text-regular {
        color: #444 !important;
    }


    .widget.widget-icon {
        display: block;
        padding: 10px;
        margin-bottom: 0;
    }
    .inner-2x.innerAll {
        padding: 30px !important;
    }
    .widget {
        background: #FFF;
        margin: 0 auto 15px;
        position: relative;
        border: 1px solid #EFEFEF;
    }
    .text-center {
        text-align: center !important;
    }


    .lead{
        font-weight: bolder;
    }



    .widget.widget-icon.inverse {
        background: #e5412d
    }

    .widget.widget-icon.primary {
        background: #5c5c5c
    }

    .widget.widget-icon.success {
        background: #609450;
    }

    .table-primary thead .th_alerts th {
        background-color: #e5412d
    }
    .table-primary thead .th_unpaid_invoices th {
        background-color: #5c5c5c
    }
    .table-primary thead .th_messages th {
        background-color: #609450;
    }
    #tab_alerts h2{
        color:  #e5412d
    }
    #tab_unpaid_invoices h2{
        color: #5c5c5c
    }
    #tab_messages h2{
        color: #609450;
    }

    .msg h2{
        /*color: #E5412D;*/
    }

    .widget.widget-body-white > .widget-body {
        min-height: 200px;
    }

</style>
<link rel="stylesheet" href="<?php echo $this->webroot; ?>common/theme/carrier/picto.css" />
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Accounts') ?></li>
</ul>
<div>
    <hr/>
</div>
<div class="innerLR">
    <div class="innerB">
        <h1 class="margin-none pull-left"><?php __('Messages') ?> &nbsp;<i
                class="fa fa-fw fa-pencil text-muted"></i></h1>

        <div class="btn-group pull-right">
            <a href="<?php echo $this->webroot; ?>clients/carrier_dashboard" class="btn btn-default"><i
                    class="fa fa-fw fa-bar-chart-o"></i> <?php __('Analytics') ?></a>
            <a href="<?php echo $this->webroot; ?>clients/carrier/true" class="btn btn-default"><i
                    class="fa fa-fw fa-user"></i> <?php __('Account') ?></a>
            <a href="<?php echo $this->webroot; ?>clients/messages" class="btn btn-primary"><i class="fa fa-fw fa-dashboard"></i> <?php __('Messages') ?></a>
        </div>
        <div class="clearfix"></div>
    </div>

<!--    account-->
    <div style="border: 1px solid #efefef; padding: 15px;">
        <div class="row-fluid">

            <div class="span4 account_info">
                <!-- Widget -->
                <a href="" id="a_alerts" onclick="return false" class="widget widget-icon inverse innerAll inner-2x text-center text-regular">
                    <i class="display-block icon-alarm-clock text-xlarge"></i>
                    <span class="lead"><span id="span_alerts" style="display: inline-block"><?php echo count($alerts_arr)?></span> <?php __('Alerts')?></span>
                </a>
                <!-- //Widget -->


            </div>
            <div class="span4 account_info">
                <!-- Widget -->
                <a href="" id="a_unpaid_invoices"  onclick="return false"  class="widget widget-icon primary innerAll inner-2x text-center text-regular">
                    <i class="display-block icon-reciept-2 text-xlarge"></i>
                    <span class="lead"><span id="span_unpaid_invoices" style="display: inline-block"><?php echo count($unpaid_invoices_arr)?></span> <?php __('Unpaid Invoices')?></span>
                </a>
                <!-- //Widget -->


            </div>
            <div class="span4 account_info">
                <!-- Widget -->
                <a href="" id="a_messages"  onclick="return false"  class="widget widget-icon success innerAll inner-2x text-center text-regular">
                    <i class="display-block icon-envelope-2 text-xlarge"></i>
                    <span class="lead"><span id="span_messages" style="display: inline-block"><?php echo count($messages_arr)?></span> <?php __('Messages')?></span>
                </a>
                <!-- //Widget -->


            </div>


            <!-- //End Widget -->
        </div>
        <div class="separator"></div>
        <div class="overflow_x" id="tab_alerts" style="<?php if($show_tab!=1 || empty($alerts_arr)) echo 'display:none;'?>padding: 15px; border: 1px solid #efefef;">
            <table
                class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default">

                <!-- Table heading -->
                <caption><h2><?php __('Alerts')?></h2></caption>
                <thead>
                <tr class="th_alerts">

                    <th><?php __('Alert Type') ?></th>
                    <th><?php __('Subject') ?></th>
                    <th><?php __('Sent To') ?></th>
                    <th><?php __('Action') ?></th>

                </tr>

                </thead>
                <!-- // Table heading END -->

                <!-- Table body -->
                <tbody>
                <?php foreach($alerts_arr as $item):?>
                    <tr class="tr_alerts">
                        <td><?php echo $alerts_type_arr[$item[0]['type']]?></td>
                        <td><?php echo $item[0]['subject']?></td></td>
                        <td><?php echo $item[0]['email_addresses']?></td>

                        <td>

                            <a data-toggle="modal" class="a_modal_alerts" href="#modal_alerts" title="view" value="<?php echo $item[0]['id']?>">
                                <i class="icon-envelope"></i>
                            </a>
                        </td>

                    </tr>
                <?php endforeach?>


                </tbody>
                <!-- // Table body END -->

            </table>

            <div id="modal_alerts" class="modal hide">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">&times;</button>
                    <h3><span id="title"></span> <?php __('Alert')?></h3>
                </div>
                <div class="modal-body">

                </div>
            </div>
            <script>
                $(function(){

                    $('.a_modal_alerts').click(function(){
                        var alerts_num = $('.a_modal_alerts').index($(this));
                        var value = $(this).attr('value');
                        $('#modal_alerts').attr('value', alerts_num);
                        $('#modal_alerts').on('shown',function(){
                            var model = $(this);

                            $.ajax({
                                url: '<?php echo $this->webroot?>clients/ajax_email_log_view/',
                                'type': 'POST',
                                'data': {e_id:value},
                                success: function(data){
                                    //res = data;
                                    $('#modal_alerts').find('.modal-body').html(data);
                                }
                            })
                        })
                    })

                    $('#modal_alerts').on('hide', function () {

                        var num = $('#modal_alerts').attr('value');
                        $('.tr_alerts:eq('+num+')').remove();

                        $('#modal_alerts').unbind('shown');

                        $('#span_alerts').html( $('.tr_alerts').length );
                        $('.ul_alerts').html( $('.tr_alerts').length );

                        if($('.tr_alerts').length <= 0){
                            $('#tab_alerts').hide();

                            $('#a_alerts').unbind('click');
                            $('#a_alerts').click(function(){
                                $('#tab_alerts').hide();
                                $('#tab_unpaid_invoices').hide();
                                $('#tab_alerts').hide();
                            })
                        }

                    })

                })
            </script>
        </div>
        <div class="overflow_x" id="tab_unpaid_invoices" style="<?php if($show_tab!=2 || empty($unpaid_invoices_arr)) echo 'display:none;'?>padding: 15px; border: 1px solid #efefef;">
            <table
                class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default">

                <!-- Table heading -->
                <caption><h2><?php __('Unpaid Invoices')?></h2></caption>
                <thead>
                <tr class="th_unpaid_invoices">

                    <th><?php __('Invoice Number') ?></th>
                    <th><?php __('Amount') ?></th>
                    <th><?php __('Period') ?></th>
                    <th><?php __('Due Date') ?></th>
                    <th><?php __('Paid') ?></th>
                    <th><?php __('Unpaid') ?></th>

                </tr>

                </thead>
                <!-- // Table heading END -->

                <!-- Table body -->
                <tbody>
                <?php foreach($unpaid_invoices_arr as $v):?>
                <tr>
                    <td><?php echo $v['0']['invoice_number']?></td>
                    <td><?php echo $v['0']['total_amount']?></td>
                    <td><?php echo $v[0]['invoice_start'] . '~' . $v[0]['invoice_start']?></td>
                    <td><?php echo $v['0']['due_date']?></td>
                    <td><?php echo $v['0']['paid']?></td>

                    <td>
                        <?php echo $v['0']['total_amount'] - $v['0']['paid']?>
                    </td>

                </tr>
                <?php endforeach?>

                </tbody>
                <!-- // Table body END -->

            </table>

        </div>
        <div class="overflow_x" id="tab_messages" style="<?php if($show_tab!=3 || empty($messages_arr)) echo 'display:none;'?>padding: 15px; border: 1px solid #efefef;">
            <table
                class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default">

                <!-- Table heading -->
                <caption><h2><?php __('Messages')?></h2></caption>
                <thead>
                <tr class="th_messages">

                    <th><?php __('Messages Type') ?></th>
                    <th><?php __('Subject') ?></th>
                    <th><?php __('Sent To') ?></th>
                    <th><?php __('Sent On') ?></th>
                    <th><?php __('Action') ?></th>

                </tr>

                </thead>
                <!-- // Table heading END -->

                <!-- Table body -->
                <tbody>
                <?php foreach($messages_arr as $item):?>
                <tr class="tr_messages">
                    <td><?php echo $messages_type_arr[$item[0]['type']]?></td>
                    <td><?php echo $item[0]['subject']?></td></td>
                    <td><?php echo $item[0]['email_addresses']?></td>
                    <td><?php echo $item[0]['send_time']?></td>

                    <td>

                        <a data-toggle="modal" class="a_modal_messages" href="#modal_messages" title="View Message" value="<?php echo $item[0]['id']?>">
                            <i class="icon-envelope"></i>
                        </a>
                    </td>

                </tr>
                <?php endforeach?>

                </tbody>
                <!-- // Table body END -->

            </table>

            <div id="modal_messages" class="modal hide" style="width:750px;margin-left: -375px;">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">&times;</button>
                    <h3><span id="title"></span> <?php __('Message')?></h3>
                </div>
                <div class="modal-body">

                </div>
            </div>
            <script>
                $(function(){

                    $('.a_modal_messages').click(function(){
                        var messages_num = $('a_modal_messages').index($(this));
                        var value = $(this).attr('value');
                        $('#modal_messages').attr('value', messages_num);
                        $('#modal_messages').on('shown',function(){
                            var model = $(this);

                            $.ajax({
                                url: '<?php echo $this->webroot?>clients/ajax_email_log_view/',
                                'type': 'POST',
                                'data': {e_id:value},
                                success: function(data){
                                    //res = data;
                                    $('#modal_messages').find('.modal-body').html(data);
                                }
                            })
                        })
                    })

                    $('#modal_messages').on('hide', function () {

                        var num = $('#modal_messages').attr('value');
                        $('.tr_messages:eq('+num+')').remove();

                        $('#span_messages').html( $('.tr_messages').length );
                        $('.ul_messages').html( $('.tr_messages').length );

                        if($('.tr_messages').length <= 0){
                            $('#tab_messages').hide();

                            $('#a_messages').unbind('click');
                            $('#a_messages').click(function(){
                                $('#tab_alerts').hide();
                                $('#tab_unpaid_invoices').hide();
                                $('#tab_messages').hide();
                            })
                        }

                    })

                })
            </script>

        </div>
        <script>
            //account部分 js
            $(function(){
                var tab_alerts = $('#tab_alerts');
                var tab_unpaid_invoices = $('#tab_unpaid_invoices');
                var tab_messages = $('#tab_messages');
                //tab_alerts.show();
                //tab_unpaid_invoices.hide();
                //tab_messages.hide();

                $('#a_alerts').click(function(){
                    <?php if(!empty($alerts_arr)):?>
                    tab_alerts.show();
                    <?php endif?>
                    tab_unpaid_invoices.hide();
                    tab_messages.hide();
                })



                $('#a_unpaid_invoices').click(function(){
                    tab_alerts.hide();
                    <?php if(!empty($unpaid_invoices_arr)):?>
                    tab_unpaid_invoices.show();
                    <?php endif?>
                    tab_messages.hide();
                })



                $('#a_messages').click(function(){
                    tab_alerts.hide();
                    tab_unpaid_invoices.hide();
                    <?php if(!empty($messages_arr)):?>
                    tab_messages.show();
                    <?php endif?>
                })

            })
        </script>
    </div>


    <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head" style="padding-bottom: 10px; padding-top: 10px;">
            <h2 class="heading innerAll table_heading" style="font-size: 21px"><i class="fa fa-table "></i> <?php __('Recent Rate Update')?></h2>
        </div>
        <div class="widget-body">
            <?php if (count($resource_prefix_arr) == 0) :?>
                <div class="msg center">
                    <h2 style="margin-top:80px" class="text-primary"><?php echo __('No Product')?></h2>
                </div>
            <?php else: ?>
            <div class="overflow_x" style="">
                <table
                class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default">

                <!-- Table heading -->
                <thead>
                    <tr>

                        <th><?php __('Product Name') ?></th>
                        <th><?php __('Rate Deck') ?></th>
                        <th><?php __('Rate Sent On') ?></th>
                        <th><?php __('Rate Sent To') ?></th>

                    </tr>

                </thead>
                <!-- // Table heading END -->

                <!-- Table body -->
                <tbody>
                    <?php //$data = $p->getDataArray();?>
                    <?php foreach($data as $item):?>

                    <tr>
                        <td><?php echo $item['product_name']?></td>
                        <td><?php echo $item['rate_deck']?></td>
                        <td><?php echo $item['sent_on']?></td>
                        <td><?php echo $item['sent_to']?></td>
                    </tr>

                    <?php endforeach;?>
                </tbody>
                <!-- // Table body END -->

            </table>
<!--            <div class="separator"></div>-->
<!--            <div class="row-fluid">-->
<!--                <div class="pagination pagination-large pagination-right margin-none">-->
<!--                    --><?php //echo $this->element('page'); ?>
<!--                </div>-->
<!--            </div>-->

            </div>
            <?php endif;?>
        </div>


    </div>

    <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head" style="padding-bottom: 10px; padding-top: 10px;">
            <h2 class="heading innerAll table_heading" style="font-size: 21px"><i class="fa fa-table "></i> <?php __('Recent Invoices')?></h2>
        </div>
        <div class="widget-body">
            <div class="overflow_x" style="">
                <?php $data = $p->getDataArray();?>
                <?php if(empty($data)):?>
                    <div class="msg center">
                        <h2 style="margin-top:80px" class="text-primary"><?php echo __('No Invoice')?></h2>
                    </div>
                <?php else: ?>
                <table
                    class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default">

                    <!-- Table heading -->
                    <thead>
                    <tr>

                        <th><?php __('Invoice Number') ?></th>
                        <th><?php __('Amount') ?></th>
                        <th><?php __('Period') ?></th>
                        <th><?php __('Sent On') ?></th>
<!--                        <th>--><?php //__('Sent To') ?><!--</th>-->
                        <th><?php __('Action') ?></th>

                    </tr>

                    </thead>
                    <!-- // Table heading END -->

                    <!-- Table body -->
                    <tbody>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                        <td><?php echo $v[0]['invoice_number']?></td>
                        <td><?php echo $v[0]['total_amount']?></td>
                        <td><?php echo $v[0]['invoice_start'] . '~' . $v[0]['invoice_start']?></td>
                        <td><?php echo $v[0]['invoice_time']?></td>

                        <td>
                            <?php if ($v[0]['output_type'] == 0): ?>
                                <a target="_blank" title="<?php echo __('download') ?>"
                                   href="<?php echo $this->webroot . 'pr/pr_invoices/createpdf_invoice/' . $v[0]['invoice_number'] ?>/2" >
                                    <i class="icon-file-text"></i>
                                </a>
                            <?php elseif ($v[0]['output_type'] == 1): ?>
                                <a target="_blank" title="<?php echo __('download') ?>"
                                   href="<?php echo $this->webroot . 'pr/pr_invoices/createxls_invoice/' . $v[0]['invoice_number'] ?>/2" >
                                    <i class="icon-file-text"></i>
                                </a>
                            <?php else: ?>
                                <a target="_blank" title="<?php echo __('download') ?>"
                                   href="<?php echo $this->webroot . 'pr/pr_invoices/createhtml_invoice/' . $v[0]['invoice_number'] ?>/2" >
                                    <i class="icon-file-text"></i>
                                </a>
                            <?php endif; ?>
                        </td>

                    </tr>
                    <?php endforeach?>

                    </tbody>
                    <!-- // Table body END -->

                </table>
                <div class="separator"></div>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>




    <!-- //End Col -->
</div>
