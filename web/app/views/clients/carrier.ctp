
<?php
$enableActionColumn = $systemParams['enable_client_delete_trunk'] == 'true' || $systemParams['enable_client_disable_trunk'] == 'true';
?>

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

    label {
        display: inline-block;
        width: 30%;
        text-align: right;
        margin-bottom: 10px;
        /*padding: 0;*/
        padding-right: 10px;
    }

    input[type=text], textarea {
        width: 60%;
        border: 0px;
        margin-top: 6px;
        color: #7c7c7c;
        font-weight: normal;
        font-family: 'Roboto', sans-serif;
        background-color: #f9f9f9;
    }



    textarea[readonly]{
        color: #7c7c7c;
        font-weight: normal;
        font-family: 'Roboto', sans-serif;
        background-color: #f9f9f9;
    }

    /*.widget-body{*/
    /*background-color: #f9f9f9;*/
    /*}*/

    .balance_item{
        height: 34px;
        margin-left: 0px;
        margin-top: 0px;
        padding-top: 10px;
    }

    .balance_item > span:nth-child(1){

        padding-left: 10px;
        padding-right: 10px;
        width: 30%;
        display: inline-block;
        text-align: right;

    }

    .balance_item span:nth-child(2) {
        padding-left: 10px;
        padding-right: 10px;
        display: inline-block;
        width: 40%;
        text-align: left;
    }

    .account_info .text-center{
        min-height: 380px;
    }

    .account_info .padding-none{
        height: 500px;
    }

    .heading-button input{
        /*background-color: #E5412D;*/
        cursor: pointer;
    }

    .widget.widget-body-white > .widget-body {
        min-height: 200px;
    }

    .msg h2{
        /*color: #E5412D;*/
    }

    .widget .widget-head .table_heading{
        font-size:17.5px;
        /*color:#E5412D;*/
        padding-bottom: 20px;
    }
</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if($_SESSION['login_type'] == 3):?>
        <li><?php __('Client Portal') ?></li>
    <?php else:?>
        <li><?php __('Management') ?></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Accounts') ?></li>
</ul>
<div>
    <hr/>
    <div class="innerLR">
        <div class="innerB">
            <h1 class="margin-none pull-left "><?php __('Accounts') ?> &nbsp;<i
                        class="fa fa-fw fa-pencil text-muted"></i></h1>

            <div class="btn-group pull-right">

                <?php if ($_SESSION['login_type'] == 3): ?>
                <a href="<?php echo $this->webroot; ?>did_client/dashboard" class="btn btn-default">
                    <?php else: ?>
                    <a href="<?php echo $this->webroot; ?>clients/carrier_dashboard" class="btn btn-default">
                        <?php endif; ?>
                        <i class="fa fa-fw fa-bar-chart-o"></i> <?php __('Analytics') ?></a>
                    <a href="<?php echo $this->webroot; ?>clients/carrier/true" class="btn btn-primary"><i
                                class="fa fa-fw fa-user"></i> <?php __('Account') ?></a>
                    <a href="<?php echo $this->webroot; ?>clients/messages" class="btn btn-default"><i class="fa fa-fw fa-dashboard"></i> <?php __('Messages') ?></a>
            </div>
            <div class="clearfix"></div>
        </div>

        <!--    account-->
        <div class="row-fluid">

            <div class="span4 account_info">
                <!-- Widget -->
                <div class="widget widget-body-white widget-heading-simple  padding-none">
                    <div class="widget-body padding-none" style="background-color: #f9f9f9">
                        <form action="<?php echo $this->webroot; ?>clients/save_user" method="post" id="user_form" role="form">
                            <h4 class="heading innerAll " style="background-color: #ffffff"><i
                                        class="fa fa-fw fa-user "></i><?php echo __('User Account') ?></h4>


                            <div class="innerAll half text-center">
                                <div>
                                    <label for="company"
                                           class="col-sm-2 control-label"><?php echo __('Company Name: ') ?> </label>
                                    <?php echo $form->input('company', array('id' => 'company', 'class' => 'validate[maxSize[200],custom[onlyLetterNumberLineSpace]]', 'label' => false, 'div' => false, 'value' => $post['Client']['company'], 'maxLength' => 256)) ?>
                                </div>
                                <div>
                                    <label for="email" class="control-label"><?php echo __('Main e-mail: ') ?> </label>
                                    <?php echo $form->input('email', array('id' => 'email', 'label' => false, 'div' => false, 'class' => 'validate[custom[email]]', 'value' => $post['Client']['email'])) ?>
                                </div>

                                <?php if ($_SESSION['login_type'] != 3): ?>
                                    <div>
                                        <label for="phone" class="control-label"><?php echo __('Phone: ') ?> </label>
                                        <?php echo $form->input('phone', array('id' => 'phone', 'label' => false, 'div' => false, 'class' => '', 'value' => $post['Client']['phone'])) ?>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <label for="address"
                                           class="col-sm-2 control-label" style="margin-top: 10px;vertical-align: top"><?php echo __('Address: ') ?> </label>
                                    <?php echo $form->input('address', array('id' => 'address', 'label' => false, 'div' => false, 'type' => 'textarea', 'rows' => 3, 'maxlength' => '300', 'value' => $post['Client']['address'])) ?>
                                </div>

                            </div>

                            <!--                        <div class="heading-button center">-->
                            <!--                            <div class="buttons">-->
                            <!--                                <input id="user_edit" type="submit" class="btn btn-primary" value="--><?php //__('Edit') ?><!--">-->
                            <!--                            </div>-->
                            <!--                            <div class="clearfix"></div>-->
                            <!--                        </div>-->
                        </form>
                    </div>

                </div>
                <!-- //Widget -->


            </div>
            <div class="span4 account_info">
                <!-- Widget -->
                <div class="widget widget-body-white widget-heading-simple  padding-none">
                    <div class="widget-body padding-none" style="background-color: #f9f9f9">
                        <h4 class="heading innerAll " style="background-color: #ffffff"><i
                                    class="fa fa-usd "></i> <?php __('Account Balance') ?></h4>


                        <div class="innerAll half text-center">
                            <div class="balance_item">
                                <span><?php echo __('Status: ') ?></span>
                                <?php if ($data['status']): ?>
                                    <span style="color:Green"><?php __('Active'); ?></span>
                                <?php else: ?>
                                    <span style="color:red"><?php __('Inactive'); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="balance_item">
                                    <span>
                                        <?php echo __('Type: ') ?>
                                    </span>
                                <span>
                                        <?php echo $data['mode'] == '1' ? 'Prepaid' : 'Postpaid' ?>
                                    </span>
                            </div>
                            <div class="balance_item">
                                    <span>
                                        <?php __('Available Credit: ') ?>
                                    </span>
                                <span>
                                        <?php echo $data['unlimited_credit'] ? "Unlimited" : abs($data['allowed_credit']); ?>
                                    </span>
                            </div>
                            <?php if ($_SESSION['login_type'] != 3): ?>
                                <div class="balance_item">
                                    <span>
                                        <?php __('Ingress Trunk: ') ?>
                                    </span>
                                    <span>
                                        <?php echo $data['ingress_count'] ?>
                                    </span>
                                </div>
                                <div class="balance_item">
                                    <span>
                                        <?php __('Egress Trunk: ') ?>
                                    </span>
                                    <span>
                                        <?php echo $data['egress_count'] ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="balance_item">
                                    <span>
                                        <?php __('Actual Balance: ') ?>
                                    </span>
                                <span>
                                        <a href="<?php echo $this->webroot ?>finances/get_actual_ingress_egress_detail/<?php echo $_SESSION['sst_client_id'] ?>" target="_blank">
                                            <?php
                                            echo $client_balance < 0 ? '(' . str_replace('-', '', number_format($client_balance, 2)) . ')' : number_format($client_balance, 2);
                                            ?>
                                        </a>
                                    </span>
                            </div>
                            <!-- <div class="balance_item">
                                    <span>
                                        <?php // __('Mutual Balance: ') ?>
                                    </span>
                                    <span>
                                        <a href="<?php //echo $this->webroot ?>finances/get_mutual_ingress_egress_detail/<?php //echo base64_encode($_SESSION['sst_client_id']); ?>"
                                           target="_blank">
                                            <?php
                            //$mutal_total = $data['mutual_balance'];
                            //echo $mutal_total < 0 ? '(' . str_replace('-', '', number_format($mutal_total, 3)) . ')' : number_format($mutal_total, 3);
                            ?>
                                        </a>
                                    </span>
                            </div> -->

                        </div>

                        <div class="heading-button center">
                            <div class="buttons">
                                <?php
                                if (Configure::read('payline.enable_paypal') && !empty($_SESSION['carrier_panel']['Client']['is_panel_onlinepayment'])): ?>
                                    <input id="pay_now" type="button" class="btn btn-primary" value="<?php __('Pay Now') ?>">
                                <?php endif; ?>
                                <input id="transaction" type="button" data-client="<?php echo $_SESSION['sst_client_id'];?>" class="btn btn-primary" value="<?php __('Transaction') ?>">
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- //Widget -->


            </div>
            <div class="span4 account_info">
                <!-- Widget -->
                <div class="widget widget-body-white widget-heading-simple  padding-none">
                    <div class="widget-body padding-none" style="background-color: #f9f9f9;">
                        <h4 class="heading innerAll "  style="background-color: #ffffff"><i
                                    class="fa fa-comment "></i> <?php __('Email Contacts') ?></h4>

                        <form action="<?php echo $this->webroot; ?>clients/save_user" method="post" id="user_form" role="form">
                            <div class="innerAll half text-center">
                                <div>
                                    <label for="noc_email"
                                           class="col-sm-2 control-label"><?php echo __('NOC e-mail: ', true); ?></label>
                                    <?php echo $form->input('noc_email', array('label' => false, 'div' => false, 'class' => 'validate[custom[email]]', 'value' => $post['Client']['noc_email'])) ?>
                                </div>
                                <div>
                                    <label for="billing_email"
                                           class="control-label"><?php echo __('Billing e-mail: ', true); ?></label>
                                    <?php echo $form->input('billing_email', array('label' => false, 'div' => false, 'class' => 'validate[custom[email]]', 'value' => $post['Client']['billing_email'])) ?>
                                </div>
                                <?php if ($post['Client']['client_type'] != 1): ?>
                                    <div>
                                        <label for="rate_email"
                                               class="control-label"><?php echo __('Rates e-mail: ', true); ?></label>
                                        <?php echo $form->input('rate_email', array('label' => false, 'div' => false, 'class' => 'validate[custom[email]]', 'value' => $post['Client']['rate_email'])) ?>
                                    </div>
                                <?php endif; ?>
                                <!-- <div>
                                <label for="rate_email"
                                       class="control-label"><?php //echo __('Rate Delivery e-mail: ', true); ?></label>
                                <?php //echo $form->input('rate_delivery_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'validate[custom[email]]', 'value' => $post['Client']['rate_delivery_email'])) ?>
                            </div> -->

                            </div>

                            <!--                            <div class="heading-button center">-->
                            <!--                                <div class="buttons">-->
                            <!--                                    <input id="contacts_edit" type="submit" class="btn btn-primary" value="--><?php //__('Edit') ?><!--">-->
                            <!--                                </div>-->
                            <!--                                <div class="clearfix"></div>-->
                            <!--                            </div>-->
                        </form>
                    </div>
                </div>
                <!-- //Widget -->


            </div>


            <!-- //End Widget -->
        </div>
        <script>
            //account部分 js
            $(function(){
                $('.text-center input').attr("readonly",true);
                $('.text-center input').css('cursor','default');
                $('.text-center textarea').attr("readonly",true);
                $('.text-center textarea').css({'cursor':'default','resize':'none'});

                $('#user_edit').click(function(){
                    if($(this).val() == 'Edit'){


                        $('.account_info:first input').attr("readonly",false);
                        $('.account_info:first input').css({'cursor':'auto', 'border':'1px solid #E5412D'});
                        $('.account_info:first textarea').attr("readonly",false);
                        $('.account_info:first textarea').css({'cursor':'auto', 'border':'1px solid #E5412D', 'resize':'both'});

                        $(this).val('Submit');
                        $(this).css('cursor','pointer');
                        return false;
                    }

                })

                $('#contacts_edit').click(function(){
                    if($(this).val() == 'Edit'){

                        $('.account_info:last input').attr("readonly",false);
                        $('.account_info:last input').css({'cursor':'auto', 'border':'1px solid #E5412D'});
                        $('.account_info:last textarea').attr("readonly",false);
                        $('.account_info:last textarea').css({'cursor':'auto', 'border':'1px solid #E5412D'});
                        $(this).val('Submit');
                        $(this).css('cursor','pointer');
                        return false;
                    }

                })

                <?php if($data_window){ ?>

                $('#pay_now').click(function(){
                    window.open('<?php echo $this->webroot?>clients/client_pay','_blank' ,true);
                })

                <?php } else { ?>

                $('#pay_now').click(function(){
                    window.open('<?php echo $this->webroot?>clients/client_pay','_self' ,true);
                })

                <?php } ?>

                $('#transaction').click(function(){
                    var client_id = $(this).attr('data-client');
                    if(client_id){
                        window.open('<?php echo $this->webroot?>finances/get_actual_ingress_egress_detail/' + client_id,'_self' ,true);
                    }
                })
            })
        </script>




        <!--    ingress-->
        <?php $d = $p->getDataArray();?>
        <?php if (count($d) !== 0) :?>
            <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>
            <div class="widget widget-tabs widget-body-white">
                <div class="widget-head" style="padding-bottom: 10px; padding-top: 10px;">
                    <h4 class="heading innerAll table_heading " style=""><i class="fa fa-table "></i> <?php __('Ingress Trunks')?></h4>
                </div>
                <div class="widget-body">
                    <!--            --><?php //$w = $session->read('writable');?>
                    <div class="overflow_x">
                        <table id="mytable" class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <thead>
                            <tr>
                                <!--                            --><?php //if($w):?>
                                <!--                                <th class="footable-first-column expand" data-class="expand"  ><input type="checkbox" onclick="checkAll(this,'mytable');" value=""/></th>-->
                                <!--                            --><?php //endif ?>
                                <th>	<?php echo $appCommon->show_order('alias', __('Ingress Name',true))?> </th>
                                <th>
                                    <?php echo __('Product Name')?>&nbsp;
                                </th>
                                <th>
                                    <?php echo __('Prefix')?>
                                </th>
                                <th>
                                    <?php echo __('host_ip')?>&nbsp;
                                </th>
                                <th>
                                    <?php echo __('Call limit')?>&nbsp;
                                </th>
                                <th>
                                    <?php echo __('CPS Limit')?>&nbsp;
                                </th>


                                <!--                            <th>	--><?php //echo $appCommon->show_order('capacity', __('Call limit',true))?><!-- </th>-->
                                <!--                            <th>	--><?php //echo $appCommon->show_order('cps_limit', __('CPS Limit',true))?><!-- </th>-->
                                <?php if ($enableActionColumn): ?>
                                    <th  data-hide="phone,tablet"  style="display: table-cell;" class="footable-last-column" style="width:10%"><?php echo __('action')?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 	$modal_arr=array();for ($i=0;$i<count($d);$i++) : $modal_arr[$i] = 0;?>

                                <?php if(isset($product_prefix[$i])):?>
                                    <?php foreach($product_prefix[$i] as $key => $item):?>
                                        <tr style="<?php if($d[$i][0]['active'] == 0) echo 'background:#ccc;';?>">
                                            <!--                                --><?php //if($w):?>
                                            <!--                                    <td  class="footable-first-column expand" data-class="expand"   style="text-align:center"><input type="checkbox" value="--><?php //echo $d[$i][0]['resource_id']?><!--"/></td>-->
                                            <!--                                --><?php //endif?>
                                            <td><?php echo $d[$i][0]['alias']?></td>
                                            <td>
                                                <?php echo $item['product']; ?>
                                            </td>
                                            <td>
                                                <?php echo $item['prefix']; ?>
                                            </td>
                                            <td>
                                                <a data-toggle="modal" href="#myModal_ip<?php echo $i; ?>" title="IP List">
                                                    <i class="icon-list"></i>
                                                </a>
                                            </td>


                                            <td  align="center"><?php  if(empty($d[$i][0]['capacity'])) {echo "Unlimited";}else{echo  $d[$i][0]['capacity']; }?></td>
                                            <td ><?php  if(empty($d[$i][0]['cps_limit'])) {echo "Unlimited";}else{echo  $d[$i][0]['cps_limit']; }?></td>
                                            <?php if ($enableActionColumn): ?>
                                                <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                                                    <div  class="action_icons">
                                                        <!--                                            <a title="Product List" href="--><?php //echo $this->webroot; ?><!--clients/product_list/--><?php //echo base64_encode($d[$i][0]['resource_id'])?><!--">-->
                                                        <!--                                                <i class="icon-list"></i>-->
                                                        <!--                                            </a>-->
                                                        <?php if ($systemParams['enable_client_disable_trunk'] == 'true'): ?>
                                                            <?php if($d[$i][0]['active']==1): ?>
                                                                <a onclick="return myconfirm('Are you sure you would like to inactive the selected <?php echo $d[$i][0]['alias']?>!', this);"
                                                                   href="<?php echo $this->webroot?>clients/dis_able_resource/<?php echo base64_encode($d[$i][0]['resource_id'])?>/carrier?<?php echo $this->params['getUrl']?>" title="<?php echo __('Inactive')?>">
                                                                    <i class="icon-check"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <a  onclick="return myconfirm('Are you sure you would like to active the selected <?php echo $d[$i][0]['alias']?>!', this);"
                                                                    href="<?php echo $this->webroot?>clients/active_resource/<?php echo base64_encode($d[$i][0]['resource_id'])?>/carrier?<?php echo $this->params['getUrl']?>" title="<?php echo __('Active')?>">
                                                                    <i class="icon-check-empty"></i>
                                                                </a>
                                                            <?php endif ?>
                                                        <?php endif; ?>
                                                        <?php if ($systemParams['enable_client_delete_trunk'] == 'true'): ?>
                                                            <a  onclick="return myconfirm('Are you sure to delete ,egress trunk <?php echo $d[$i][0]['alias']?>?', this);" href="<?php echo $this->webroot?>clients/del_resource/<?php echo base64_encode($d[$i][0]['resource_id'])?>/carrier?<?php echo $this->params['getUrl']?>" title="<?php echo __('del')?>">
                                                                <i class="icon-remove"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            <?php endif; ?>
                                        </tr>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>


        <!--    //product list-->
        <div id="myModal_product" class="modal hide">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">&times;</button>
                <h3><span id="title"></span> <?php __('Product')?></h3>
            </div>
            <div class="modal-body">

            </div>
        </div>
        <script>
            $(function(){
                $('.a_resource_id').click(function(){
                    var value = $(this).attr('value');
                    $('#myModal_product').on('shown',function(){
                        var model = $(this);

                        $.ajax({
                            url: '<?php echo $this->webroot?>clients/ajax_product_list',
                            'type': 'POST',
                            'data': {resource_id:value},
                            success: function(data){
                                //res = data;
                                $('#myModal_product').find('.modal-body').html(data);
                            }
                        })
                    })
                })

            })
        </script>

        <!--    //ip list-->
        <?php 	for ($i=0;$i<count($d);$i++) :?>

            <div id="myModal_ip<?php echo $i; ?>" class="modal hide">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">&times;</button>
                    <h3><?php echo __('Ingress',true)."[".$d[$i][0]['alias']."] IP"; ?></h3>
                </div>
                <div class="modal-body">
                    <?php if(isset($resource_ip_arr[$i]['resource_ip'][0][0]['need_register']) && $resource_ip_arr[$i]['resource_ip'][0][0]['need_register'] == 1): ?>
                        <?php if($change_ip): ?>
                            <div class="buttons pull-right newpadding">
                                <a table_type="1" tbody_id = "tbody<?php echo $i; ?>" class="link_btn btn btn-primary btn-icon glyphicons circle_plus ip_add_btn" href="javascript:void(0)">
                                    <i></i>
                                    <?php __('Create new'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <table class="table table-bordered table-primary" resource_id = "<?php echo $d[$i][0]['resource_id']; ?>">
                            <thead>
                            <tr>
                                <th><?php __('Username'); ?></th>
                                <th><?php __('Password'); ?></th>
                                <?php if($change_ip): ?>
                                    <th><?php __('Action'); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody id="tbody<?php echo $i; ?>">
                            <?php foreach ($resource_ip_arr[$i]['resource_ip'] as $key =>$resource_ip_item): ?>
                                <tr>
                                    <td><?php echo $resource_ip_item[0]['username']; ?></td>
                                    <td>******</td>
                                    <?php if($change_ip): ?>
                                        <td>
                                            <a class="sip_edit_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" title="save" href="javascript:void(0)"><i class="icon-edit"></i></a>
                                            <a class="delete_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" href="javascript:void(0)" title="Delete">
                                                <i class="icon-remove"></i>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <?php if($change_ip): ?>
                            <div class="buttons pull-right newpadding">
                                <a  table_type="2" tbody_id = "tbody<?php echo $i; ?>" class="link_btn btn btn-primary btn-icon glyphicons circle_plus ip_add_btn" href="javascript:void(0)">
                                    <i></i>
                                    <?php __('Create new'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <table class="table table-bordered table-primary" resource_id = "<?php echo $d[$i][0]['resource_id']; ?>">
                            <thead>
                            <tr>
                                <th><?php __('IP'); ?></th>
                                <th><?php __('Port'); ?></th>
                                <th><?php __('CPS'); ?></th>
                                <?php if($change_ip): ?>
                                    <th><?php __('Action'); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody id="tbody<?php echo $i; ?>">
                            <?php foreach ($resource_ip_arr[$i]['resource_ip'] as $key =>$resource_ip_item): ?>
                                <tr>
                                    <td><?php echo $resource_ip_item[0]['ip']; ?></td>
                                    <td><?php echo $resource_ip_item[0]['port']; ?></td>
                                    <td><?php echo $resource_ip_item[0]['cps']; ?></td>
                                    <?php if($change_ip): ?>
                                        <td>
                                            <a class="ip_edit_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" title="save" href="javascript:void(0)"><i class="icon-edit"></i></a>
                                            <a class="delete_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" href="javascript:void(0)" title="Delete">
                                                <i class="icon-remove"></i>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

        <?php endfor; ?>
        <table class="hide">
            <tr class="sip_tr">
                <td><input type="text"  name="username" class="username validate[required]" /></td>
                <td><input type="password" class="pass_word " /></td>
                <td>
                    <a class="sip_save_btn" re_ip_id="" title="save" href="javascript:void(0)"><i class="icon-save"></i></a>
                    <a onclick="$(this).closest('tr').remove();" href="javascript:void(0)" title="Cancel">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
            <tr class="ip_tr">
                <td><input type="text" name="ip"  class="width120 ip validate[required,custom[ipv4]]" /></td>
                <td><input type="text" name="port" class="width50 port validate[required,custom[integer]]" /></td>
                <td></td>
                <td>
                    <a class="ip_save_btn" re_ip_id="" title="save" href="javascript:void(0)"><i class="icon-save"></i></a>
                    <a onclick="$(this).closest('tr').remove();" href="javascript:void(0)" title="Cancel">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>

            <tr class="sip_tr_edit">
                <td><input type="text" name="username" class="username" /></td>
                <td><input type="password" class="pass_word" /></td>
                <td>
                    <a class="sip_save_btn" re_ip_id="" title="save" href="javascript:void(0)"><i class="icon-save"></i></a>
                    <a class="save_cancel" href="javascript:void(0)" title="Cancel">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
            <tr class="ip_tr_edit">
                <td><input type="text" name="ip"  class="width120 ip validate[required,custom[ipv4]]" /></td>
                <td><input type="text" name="port" class="width50 port validate[required,custom[integer]]" /></td>
                <td></td>
                <td>
                    <a class="ip_save_btn" re_ip_id="" title="save" href="javascript:void(0)"><i class="icon-save"></i></a>
                    <a class="save_cancel" href="javascript:void(0)" title="Cancel">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            $(function(){
                var sip_tr = $(".sip_tr").eq(0).remove();
                var ip_tr = $(".ip_tr").eq(0).remove();
                var sip_tr_edit = $(".sip_tr_edit").eq(0).remove();
                var ip_tr_edit = $(".ip_tr_edit").eq(0).remove();
                $(".ip_add_btn").click(function(){
                    var $tbody_id = $(this).attr('tbody_id');
                    var $table_type = $(this).attr('table_type');
                    if($table_type == 1)
                        sip_tr.clone(true).prependTo("#"+$tbody_id);
                    else
                        ip_tr.clone(true).prependTo("#"+$tbody_id);
                });

                $(".sip_save_btn").live('click',function(){
                    var user_name = $(this).closest('tr').children().eq(0).children().eq(0).val();
                    var password = $(this).closest('tr').children().eq(1).children().eq(0).val();
                    var clear_tr = $(this).closest('tr').next();
                    var resource_id = $(this).closest('table').attr('resource_id');
                    var re_ip_id = $(this).attr('re_ip_id');
                    $(this).closest('tr').find('.username').validationEngine('validate');
                    if(!re_ip_id)
                    {
                        $(this).closest('tr').find('.pass_word').addClass('validate[required,minSize[6]]');
                        $(this).closest('tr').find('.pass_word').validationEngine('validate');
                    }
                    else
                    {
                        if(password)
                        {
                            $(this).closest('tr').find('.pass_word').addClass('validate[minSize[6]]');
                            $(this).closest('tr').find('.pass_word').validationEngine('validate');
                        }
                    }
                    if(!user_name)
                        return false;
                    if(!re_ip_id && ! password)
                        return false;
                    if(password.length < 6 && password.length > 0)
                        return false;
                    var $this = $(this);
                    $.ajax({
                        'url': '<?php echo $this->webroot ?>clients/ajax_save_resource_ip',
                        'type': 'POST',
                        'dataType': 'json',
                        'data': {'user_name': user_name, 'password': password,'type':'sip',resource_id:resource_id,re_ip_id:re_ip_id},
                        'success': function(data) {
                            if (!data.flg) {
                                var msg = data.msg;
                                jGrowl_to_notyfy(msg,{theme:'jmsg-error'});
                            } else {
                                var clone_result = clear_tr.clone(true);
                                clone_result.children().eq(0).html(user_name);
                                clone_result.find('a').eq(0).attr('re_ip_id',data.re_ip_id);
                                clone_result.find('a').eq(1).attr('re_ip_id',data.re_ip_id);
                                $this.closest('tr').before(clone_result);
                                clone_result.show();
                                $this.closest('tr').remove();
                                jGrowl_to_notyfy('<?php __('succeed'); ?>',{theme:'jmsg-success'});
                            }

                        }
                    });
                });

                $(".delete_btn").live('click',function(){
                    var re_ip_id = $(this).attr('re_ip_id');
                    var $this = $(this);
                    var $this_div = $this.parent().parent().parent().parent().parent().parent();
                    $this_div.hide();
                    bootbox.confirm('<?php __('sure to delete'); ?>', function(result) {
                        if(result) {
                            $.ajax({
                                'url': '<?php echo $this->webroot ?>clients/ajax_delete_resource_ip',
                                'type': 'POST',
                                'dataType': 'json',
                                'data': {'re_ip_id': re_ip_id},
                                'success': function(data) {
                                    if(data.flg)
                                    {
                                        jGrowl_to_notyfy('<?php __('succeed'); ?>',{theme:'jmsg-success'});
                                        $this.closest('tr').remove();
                                    }
                                    else
                                        jGrowl_to_notyfy('<?php __('failed'); ?>',{theme:'jmsg-error'});
                                    $this_div.show();
                                }
                            });
                        }
                        else
                        {
                            $this_div.show();
                        }
                    });

                });

                $(".sip_edit_btn").live('click',function(){
                    var re_ip_id = $(this).attr('re_ip_id');
                    var $this = $(this);
                    var hide_tr = $this.closest('tr');
                    var user_name = hide_tr.children().eq(0).html();
                    var closest_tbody = $this.closest('tbody');
                    sip_tr_edit.children().eq(0).find('input').val(user_name);
                    sip_tr_edit.find('a').eq(0).attr('re_ip_id',re_ip_id);
                    hide_tr.before(sip_tr_edit.clone(true));
                    hide_tr.hide();
                });

                $(".save_cancel").live('click',function(){
                    $(this).closest('tr').next().show();
                    $(this).closest('tr').remove();
                });




                $(".ip_save_btn").live('click',function(){
                    var ip = $(this).closest('tr').children().eq(0).children().eq(0).val();
                    var port = $(this).closest('tr').children().eq(1).children().eq(0).val();
                    var clear_tr = $(this).closest('tr').next();
                    var resource_id = $(this).closest('table').attr('resource_id');
                    var re_ip_id = $(this).attr('re_ip_id');
                    var flg1 = $(this).closest('tr').find('.ip').validationEngine('validate');
                    var flg2 = $(this).closest('tr').find('.port').validationEngine('validate');
                    if (flg1 || flg2)
                        return false;
                    var $this = $(this);
                    $.ajax({
                        'url': '<?php echo $this->webroot ?>clients/ajax_save_resource_ip',
                        'type': 'POST',
                        'dataType': 'json',
                        'data': {'ip': ip, 'port': port,'type':'ip',resource_id:resource_id,re_ip_id:re_ip_id},
                        'success': function(data) {
                            if (!data.flg) {
                                var msg = data.msg;
                                jGrowl_to_notyfy(msg,{theme:'jmsg-error'});
                            } else {
                                var clone_result = clear_tr.clone(true);
                                clone_result.children().eq(0).html(ip);
                                clone_result.children().eq(1).html(port);
                                clone_result.find('a').eq(0).attr('re_ip_id',data.re_ip_id);
                                clone_result.find('a').eq(1).attr('re_ip_id',data.re_ip_id);
                                $this.closest('tr').before(clone_result);
                                clone_result.show();
                                $this.closest('tr').remove();
                                jGrowl_to_notyfy('<?php __('succeed'); ?>',{theme:'jmsg-success'});
                            }

                        }
                    });
                });


                $(".ip_edit_btn").live('click',function(){
                    var re_ip_id = $(this).attr('re_ip_id');
                    var $this = $(this);
                    var hide_tr = $this.closest('tr');
                    var ip = hide_tr.children().eq(0).html();
                    var port = hide_tr.children().eq(1).html();
                    var closest_tbody = $this.closest('tbody');
                    ip_tr_edit.children().eq(0).find('input').val(ip);
                    ip_tr_edit.children().eq(1).find('input').val(port);
                    ip_tr_edit.find('a').eq(0).attr('re_ip_id',re_ip_id);
                    hide_tr.before(ip_tr_edit.clone(true));
                    hide_tr.hide();
                });

//            $('.close').click(function(){
//                window.location.reload();
//            });

            })
        </script>


        <!--    egress-->
        <?php $d_egress = $p_egress->getDataArray();?>
        <?php if (count($d_egress) !== 0) :?>
            <p class="separator text-center"><i class="fa fa-ellipsis-h fa-3x"></i></p>
            <div class="widget widget-tabs widget-body-white">
                <div class="widget-head" style="padding-bottom: 10px; padding-top: 10px;">
                    <h4 class="heading innerAll table_heading" style=""><i class="fa fa-table "></i> <?php __('Egress Trunks')?></h4>
                </div>
                <div class="widget-body">
                    <div class="overflow_x">
                        <table id="mytable" class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <thead>
                            <tr>
                                <!--                        --><?php //if($w){?>
                                <!--                            <th class="footable-first-column expand" data-class="expand"  ><input type="checkbox" onclick="checkAll(this,'mytable');" value=""/></th>-->
                                <!--                        --><?php //}?>
                                <th>	<?php echo $appCommon->show_order('egress_alias', __('Egress Name',true))?> </th>
                                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('rateTable')?></th>
                                <th>
                                    <?php echo __('host_ip')?>&nbsp;
                                </th>
                                <th>
                                    <?php __('Call limit')?>&nbsp;
                                </th>
                                <th>
                                    <?php __('CPS Limit')?>&nbsp;
                                </th>
                                <!--                        <th>	--><?php //echo $appCommon->show_order('ID', __('Egress ID',true))?><!-- </th>-->

                                <!--                        <th>	--><?php //echo $appCommon->show_order('capacity', __('Call limit',true))?><!-- </th>-->
                                <!--                        <th>	--><?php //echo $appCommon->show_order('cps_limit', __('CPS Limit',true))?><!-- </th>-->

                                <th  data-hide="phone,tablet"  style="display: table-cell;" class="footable-last-column" style="width:10%"><?php echo __('action')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 	for ($i=0;$i<count($d_egress);$i++) {?>
                                <?php $d_egress[$i][0]['rate_table_id'] = "";
                                $d_egress[$i][0]['rate_table_name'] = "";
                                if(isset($egress_rate_table[$d_egress[$i][0]['resource_id']])){
                                    $d_egress[$i][0]['rate_table_id'] = $egress_rate_table[$d_egress[$i][0]['resource_id']]['rate_table_id'];
                                    $d_egress[$i][0]['rate_table_name'] = $egress_rate_table[$d_egress[$i][0]['resource_id']]['rate_table_name'];
                                } ?>
                                <tr style="<?php if($d_egress[$i][0]['active'] == 0) echo 'background:#ccc;';?>">
                                    <!--                            --><?php //if($w){?>
                                    <!--                                <td  class="footable-first-column expand" data-class="expand"   style="text-align:center"><input type="checkbox" value="--><?php //echo $d[$i][0]['resource_id']?><!--"/></td>-->
                                    <!--                            --><?php //}?>
                                    <td><?php echo $d_egress[$i][0]['alias']?></td>

                                    <td data-hide="phone,tablet"  style="display: table-cell;">
                                        <?php if($d_egress[$i][0]['rate_table_id']): ?>
                                            <a target="_blank" title="Download" href="<?php echo $this->webroot?>clients/download_rate/<?php echo base64_encode($d_egress[$i][0]['rate_table_id']);?>" class="link_width">
                                                <?php echo $d_egress[$i][0]['rate_table_name']?>
                                            </a>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <a data-toggle="modal" href="#myModal_egress_ip<?php echo $i; ?>" title="IP List">
                                            <i class="icon-list"></i>
                                        </a>
                                    </td>
                                    <!--                            <td>--><?php //echo $d_egress[$i][0]['resource_id']?><!--</td>-->


                                    <td  align="center"><?php  if(empty($d_egress[$i][0]['capacity'])) {echo "Unlimited";}else{echo  $d_egress[$i][0]['capacity']; }?></td>
                                    <td ><?php  if(empty($d_egress[$i][0]['cps_limit'])) {echo "Unlimited";}else{echo  $d_egress[$i][0]['cps_limit']; }?></td>


                                    <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                                        <div  class="action_icons">
                                            <?php if($d_egress[$i][0]['active']==1){?>
                                                <a onclick="return myconfirm('Are you sure you would like to inactive the selected <?php echo $d_egress[$i][0]['alias']?>!', this);"
                                                   href="<?php echo $this->webroot?>clients/dis_able_resource/<?php echo base64_encode($d_egress[$i][0]['resource_id'])?>/carrier?<?php echo $this->params['getUrl']?>" title="<?php echo __('Inactive')?>">
                                                    <i class="icon-check"></i>
                                                </a>
                                            <?php }else{?>
                                            <a  onclick="return myconfirm('Are you sure you would like to active the selected <?php echo $d_egress[$i][0]['alias']?>!', this);"
                                                href="<?php echo $this->webroot?>clients/active_resource/<?php echo base64_encode($d_egress[$i][0]['resource_id'])?>/carrier?<?php echo $this->params['getUrl']?>" title="<?php echo __('Active')?>">
                                                <i class="icon-check-empty"></i><?php }?>
                                            </a>
                                            <a  onclick="return myconfirm('Are you sure to delete ,egress trunk <?php echo $d_egress[$i][0]['alias']?>?', this);" href="<?php echo $this->webroot?>clients/del_resource/<?php echo base64_encode($d_egress[$i][0]['resource_id'])?>/carrier?<?php echo $this->params['getUrl']?>" title="<?php echo __('del')?>">
                                                <i class="icon-remove"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!--    //egress_ip_list-->
        <?php 	for ($i=0;$i<count($d_egress);$i++) :?>
            <div id="myModal_egress_ip<?php echo $i; ?>" class="modal hide">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">&times;</button>
                    <h3><?php echo __('Egress',true)."[".$d_egress[$i][0]['alias']."] IP"; ?></h3>
                </div>
                <div class="modal-body">
                    <?php if(isset($egress_ip_arr[$i]['resource_ip'][0][0]['need_register']) && $egress_ip_arr[$i]['resource_ip'][0][0]['need_register'] == 1): ?>
                        <?php if($change_ip): ?>
                            <div class="buttons pull-right newpadding">
                                <a table_type="1" tbody_id = "tbody<?php echo $i; ?>" class="link_btn btn btn-primary btn-icon glyphicons circle_plus ip_add_btn" href="javascript:void(0)">
                                    <i></i>
                                    <?php __('Create new'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <table class="table table-bordered table-primary" resource_id = "<?php echo $egress_ip_arr[$i][0]['resource_id']; ?>">
                            <thead>
                            <tr>
                                <th><?php __('Username'); ?></th>
                                <th><?php __('Password'); ?></th>
                                <?php if($change_ip): ?>
                                    <th><?php __('Action'); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody id="tbody<?php echo $i; ?>">
                            <?php foreach ($egress_ip_arr[$i]['resource_ip'] as $key =>$resource_ip_item): ?>
                                <tr>
                                    <td><?php echo $resource_ip_item[0]['username']; ?></td>
                                    <td>******</td>
                                    <?php if($change_ip): ?>
                                        <td>
                                            <a class="sip_edit_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" title="save" href="javascript:void(0)"><i class="icon-edit"></i></a>
                                            <a class="delete_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" href="javascript:void(0)" title="Delete">
                                                <i class="icon-remove"></i>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <?php if($change_ip): ?>
                            <div class="buttons pull-right newpadding">
                                <a  table_type="2" tbody_id = "tbody<?php echo $i; ?>" class="link_btn btn btn-primary btn-icon glyphicons circle_plus ip_add_btn" href="javascript:void(0)">
                                    <i></i>
                                    <?php __('Create new'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <table class="table table-bordered table-primary" resource_id = "<?php echo $d_egress[$i][0]['resource_id']; ?>">
                            <thead>
                            <tr>
                                <th><?php __('IP'); ?></th>
                                <th><?php __('Port'); ?></th>
                                <th><?php __('CPS'); ?></th>
                                <?php if($change_ip): ?>
                                    <th><?php __('Action'); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody id="tbody<?php echo $i; ?>">
                            <?php foreach ($egress_ip_arr[$i]['resource_ip'] as $key =>$resource_ip_item): ?>
                                <tr>
                                    <td><?php echo $resource_ip_item[0]['ip']; ?></td>
                                    <td><?php echo $resource_ip_item[0]['port']; ?></td>
                                    <td><?php echo $resource_ip_item[0]['cps']; ?></td>
                                    <?php if($change_ip): ?>
                                        <td>
                                            <a class="ip_edit_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" title="save" href="javascript:void(0)"><i class="icon-edit"></i></a>
                                            <a class="delete_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" href="javascript:void(0)" title="Delete">
                                                <i class="icon-remove"></i>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        <?php endfor; ?>

        <!-- //End Col -->
    </div>
