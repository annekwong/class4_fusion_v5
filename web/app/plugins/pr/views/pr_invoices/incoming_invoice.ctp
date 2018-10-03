<?php $d = $p->getDataArray(); ?>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/view">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/incoming_invoice">
        <?php echo __('Invoices') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Invoices', true); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="<?php echo $this->webroot; ?>pr/pr_invoices/add_incoming" class="link_btn btn btn-primary btn-icon glyphicons circle_plus">
        <i></i><?php echo __('Create New',true);?>
    </a>
    <?php if(count($d)): ?>
        <form action="" target="_blank" method="get" id="export_panel" style="display:inline;">
            <input type="hidden" name="is_export" value="1" />
            <a class="list-export btn btn-primary btn-icon glyphicons file_export" id="export_excel_btn">
                <i></i><?php __('Export'); ?>
            </a>
        </form>
    <?php endif; ?>
</div>
<div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li>
                    <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/view/0"><i></i>
                        <?php echo __('Auto Client Invoice') ?></a>
                </li>
                <li>
                    <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/view/0"><i></i>
                        <?php echo __('Manual Client Invoice') ?></a>
                </li>
                <li><a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/vendor_invoice">
                        <i></i><?php echo __('Vendor Invoice') ?></a>
                </li>
                <li class="active"><a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/incoming_invoice"><i></i><?php echo __('Old Vendor Invoice') ?></a></li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Carrier')?>:</label>
                        <select name="client">
                            <option value=""><?php __('All')?></option>
                            <?php foreach($clients as $client): ?>
                                <option <?php if(isset($_GET['client']) && $_GET['client'] == $client[0]['client_id']) echo 'selected="selected"'; ?> value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- // Filter END -->

                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>


            <?php if (count($d) == 0) {?>
                <h2 class="msg center"><br /><?php echo __('no_data_found')?></h2>
            <?php } else {?>
                <div class="clearfix"></div>
                <form action="<?php echo $this->webroot ?>pr/pr_invoices/incoming_invoice_mass_edit" method="post" id="myform">
                    <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">

                        <thead>
                        <tr>
                            <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
                                <th rowspan="2">
                                    <input type="checkbox" id="selectAll"  value="1" name="selector" class="input in-checkbox"></th>
                            <?php }?>
                            <th><?php echo $appCommon->show_order('invoice_number',__('Invoice No',true))?></th>
                            <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
                                <th rowspan="2">Link upload</th>
                            <?php }?>
                            <th rowspan="2"><?php echo $appCommon->show_order('client',__('Carriers',true))?></th>
                            <th rowspan="2"><?php echo $appCommon->show_order('disputed',__('Invoice Period',true))?></th>
                            <th rowspan="2"><?php echo $appCommon->show_order('total_amount',__('Amt Gross',true))?></th>
                            <th rowspan="2">&nbsp;<?php echo __('Amt Paid')?></th>
                            <th rowspan="2"><?php echo $appCommon->show_order('due_date',__('Due Date',true))?></th>
                            <th rowspan="2">Payment</th>

                            <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
                                <th rowspan="2" class="last"><?php echo __('action',true);?></th>
                            <?php }?>
                        </tr>
                        <tr>
                            <th><?php echo $appCommon->show_order('invoice_time',__('Invoice Date',true))?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $mydata = $p->getDataArray();
                        $loop = count($mydata);
                        for ($i=0;$i<$loop;$i++) {
                            $state = $mydata[$i][0]['state'];
                            ?>
                            <tr class="<?php echo ($state == -1) ? 'row-2 row-3' : 'row-1'; ?>">
                                <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
                                    <th align="center"><input type="checkbox"
                                                              value="<?php echo $mydata[$i][0]['invoice_id']?>"
                                                              id="ids-<?php echo $mydata[$i][0]['invoice_id']?>" name="ids[]" class="input in-checkbox to_be_selected"></th>
                                <?php }?>
                                <th rel="tooltip" id="ci_<?php echo $i?>">
                                    <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
                                        <a  href="<?php echo $this->webroot?>invoices/edit/<?php echo  $mydata[$i][0]['invoice_id']?>" class="link_width"><b><?php echo $mydata[$i][0]['invoice_number']?></b></a>
                                    <?php }else{echo $mydata[$i][0]['invoice_number'];}?>
                                    <br>
                                    <small title=""><?php echo $mydata[$i][0]['invoice_time']?></small></th>
                                <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
                                    <!--            <th><a title="<?php echo __('download')?>"
     href="<?php echo $this->webroot.'pr/pr_invoices/createpdf_invoice/'.$mydata[$i][0]['invoice_number']?>/2" > <img width="16" height="16" src="<?php echo $this->webroot?>images/download.png"> </a></th>-->
                                    <th>
                                        <?php if ($mydata[$i][0]['pdf_path'] && $mydata[$i][0]['pdf_path'] != 'NULL'): ?>
                                            <a href="<?php echo $this->webroot; ?>upload/incoming_invoice/<?php echo $mydata[$i][0]['pdf_path']  ?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/download.png"> </a>
                                        <?php endif; ?>
                                    </th>
                                <?php }?>
                                <th style="text-align:left;"><?php if (empty($mydata[$i][0]['res'])) {?>
                                     <?php echo $mydata[$i][0]['client']?></th>
                            <?php } else {
                                echo $mydata[$i][0]['res'];
                            }
                            ?>

                                <th align="center"><small> <?php echo $mydata[$i][0]['invoice_start']?><br>
                                        <?php echo $mydata[$i][0]['invoice_end']?> </small></th>
                                <th align="right"><strong><?php echo number_format($mydata[$i][0]['total_amount'], 2);?></strong> <br>
                                </th>
                                <th align="center"><?php if ($mydata[$i][0]['paid'] == false) {?>
                                        <i class="icon-check-empty"></i>
                                    <?php } else {?>
                                        <i class="icon-check"></i>
                                    <?php }?></th>
                                <th align="center"><span class="warn">
              <?php
              if(strpos($mydata[$i][0]['due_inteval'], 'days') && $mydata[$i][0]['due_inteval']<0){
                  echo abs($mydata[$i][0]['due_inteval']);
                  ?>
                  days ago
              <?php }?>
              </span> <br>
                                    <small><?php echo $mydata[$i][0]['due_date']?></small></th>

                                <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>

                                    <th><a  href="<?php echo $this->webroot?>pr/pr_invoices/payment_to_invoice/<?php echo $mydata[$i][0]['invoice_id']?>/2/<?php echo $mydata[$i][0]['client_id']?>/incoming_invoice">
                                            <i class="icon-money"></i>
                                        </a>
                                    </th>


                                    <th class="last">

                                    <a href="<?php echo $this->webroot?>pr/pr_invoices/credit_note/<?php echo $mydata[$i][0]['invoice_number'];?>" title="<?php __('Credit Note')?>">
                                        <i class="icon-plus-sign"></i>
                                    </a>

                                    <a href="<?php echo $this->webroot?>pr/pr_invoices/debit/<?php echo $mydata[$i][0]['invoice_number'];?>" title="<?php __('Debit')?>">
                                        <i class="icon-plus-sign"></i>
                                    </a>

                                    <a href="<?php echo $this->webroot; ?>pr/pr_invoices/recon/<?php echo $mydata[$i][0]['invoice_id'];?>" title="<?php echo __('Reconcile',true);?>">
                                        <i class="icon-legal"></i>
                                    </a>

                                    <a title="<?php __('Payment List')?>" href="#myModal_PaymentList1" data-toggle="modal" class="payment_list" invoice_id="<?php echo $mydata[$i][0]['invoice_id'];?>">
                                        <i class="icon-money"></i>
                                    </a>
                                    <a title="<?php __('Apply Payment')?>" href="#myModal_apply_payment" data-toggle="modal" class="apply_payment" invoice_id="<?php echo $mydata[$i][0]['invoice_id'];?>">
                                        <i class="icon-dollar"></i>
                                    </a>

                                    <a href="<?php echo $this->webroot; ?>pr/pr_invoices/delete_incoming/<?php echo $mydata[$i][0]['invoice_id'];?>" title="<?php echo __('Delete',true);?>">
                                        <i class="icon-remove"></i>
                                    </a>
                                    </th><?php }?>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>
                    <div style="margin: 10px 0px;">&nbsp;
                        <?php __('Action')?>:
                        <select style="width: 150px;" class="input in-select select" name="action" id="action">
                            <option value="1"><?php __('Delete Selected')?></option>
                        </select>
                        <input type="submit" class="input btn-primary btn margin-bottom10" value="<?php __('Submit')?>">
                    </div>
                </form>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php } ?>


            <div id="myModal_PaymentList1" class="modal hide" style="width:auto">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">&times;</button>
                    <h3><?php __('Payment List'); ?></h3>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
                </div>

            </div>

            <div id="myModal_apply_payment" class="modal hide" style="width:auto">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">&times;</button>
                    <h3><?php __('Apply Payment'); ?></h3>
                </div>
                <form id="payment_form" action="" method="post">
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" id="apply_payment_submit" class="btn btn-primary" value="Submit">
                        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="dd"> </div>



<!--<link rel="stylesheet" type="text/css" href="--><?php //echo $this->webroot?><!--easyui/themes/default/easyui.css">-->
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">-->
<!--<script type="text/javascript" src="--><?php //echo $this->webroot?><!--easyui/jquery.easyui.min.js"></script>-->



<script type="text/javascript">
    $(function() {
        $('#export_excel_btn').click(function() {
            $('#export_panel').submit();
        });

        $('#selectAll').change(function() {
            $(".to_be_selected").prop('checked', $(this).prop('checked'));
        });

        $("#myform").submit(function(){
            var checked = $(this).find('tbody').find(':checkbox:checked').size();
            if(checked == 0){
                if($('#action').val() == 1){
                    jGrowl_to_notyfy('<?php __('Please select items to delete!'); ?>', {theme: 'jmsg-error'});
                }
                else{
                    jGrowl_to_notyfy('<?php __('Not selected item'); ?>', {theme: 'jmsg-error'});
                }
                return false;
            }
        });

        var $dd = $('#dd');
        var $payment_list = $('.payment_list');
        var $apply_payment = $('.apply_payment');

        $payment_list.click(function() {
            var invoice_id = $(this).attr('invoice_id');
            $("#myModal_PaymentList1").find('.modal-body').load('<?php echo $this->webroot ?>pr/pr_invoices/get_invoice_payments/' + invoice_id);
//            $dd.dialogui({
//                title: 'Payment List',
//                width: 960,
//                height: 600,
//                closed: false,
//                cache: false,
//                resizable: true,
//                href: '<?php //echo $this->webroot?>//pr/pr_invoices/get_invoice_payments/' + invoice_id,
//                modal: true,
//                buttons:[{
//                    text:'Close',
//                    handler:function(){
//                        $dd.dialogui('close');
//                    }
//                }]
//            });
//
//            $dd.dialogui('refresh', '<?php //echo $this->webroot?>//pr/pr_invoices/get_invoice_payments/' + invoice_id);
        });

        $apply_payment.click(function() {
            var invoice_id = $(this).attr('invoice_id');
            var action;
            if(Math.sign(location.href.search('incoming')) == true){
                action = '/pr/pr_invoices/apply_payment/'+invoice_id+'/incoming';
                $('#payment_form').attr('action',action);
            }
            $("#myModal_apply_payment").find('.modal-body').load('<?php echo $this->webroot ?>pr/pr_invoices/apply_payment/' + invoice_id + '/incoming');
//            $dd.dialogui({
//                title: 'Apply Payment',
//                width: 960,
//                height: 600,
//                closed: false,
//                cache: false,
//                resizable: true,
//                href: '<?php //echo $this->webroot?>//pr/pr_invoices/apply_payment/' + invoice_id + '/incoming',
//                modal: true,
//                buttons:[{
//                    text:'Submit',
//                    handler:function(){
//                        var $payment_form = $('#payment_form');
//                        $payment_form.submit();
//                    }
//                },{
//                    text:'Close',
//                    handler:function(){
//                        $dd.dialogui('close');
//                    }
//                }]
//            });
//
//            $dd.dialogui('refresh', '<?php //echo $this->webroot?>//pr/pr_invoices/apply_payment/' + invoice_id+ '/incoming');
        });




    });
</script>
