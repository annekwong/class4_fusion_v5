<style>
.table-primary.table-bordered.default tbody td{
    background-color: #e9ffaf;
    border-color: #e9ffaf;
}
.table-primary.table-bordered.default tr:nth-child(odd) td, .table-primary tbody tr:nth-child(odd) th {
     background-color: #ffffff;
}
</style>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie lt-ie9 lt-ie8 lt-ie7 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 7]>
<html class="ie lt-ie9 lt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 8]>
<html class="ie lt-ie9 fluid top-full menuh-top"> <![endif]-->
<!--[if gt IE 8]>
<html class="animations ie gt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if !IE]><!-->
<html class="animations fluid top-full menuh-top"><!-- <![endif]-->
<head>
    <title><?php __('Signup :: Class4') ?></title>

    <!-- Meta -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!--    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />-->
    <meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE"
    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot; ?>common/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->webroot; ?>common/bootstrap/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- Glyphicons Font Icons -->
    <link href="<?php echo $this->webroot; ?>common/theme/fonts/glyphicons/css/glyphicons.css" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo $this->webroot; ?>common/theme/fonts/font-awesome/css/font-awesome.min.css">
    <!--[if IE 7]><link rel="stylesheet" href="<?php echo $this->webroot; ?>common/theme/fonts/font-awesome/css/font-awesome-ie7.min.css"><![endif]-->

    <!-- Uniform Pretty Checkboxes -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/pixelmatrix-uniform/css/uniform.default.css" rel="stylesheet" />

    <!-- PrettyPhoto -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/gallery/prettyphoto/css/prettyPhoto.css" rel="stylesheet" />

    <!-- Validation Engine -->
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>common/validationEngine/validationEngine.jquery.css">

    <!-- JQuery -->
    <script src="<?php echo $this->webroot; ?>js/jquery-1.10.1.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/jquery-migrate-1.2.1.min.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/html5shiv.js"></script>
    <![endif]-->

    <!-- MiniColors ColorPicker Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/color/jquery-miniColors/jquery.miniColors.css" rel="stylesheet" />

    <!-- jGrowl -->
    <link href="<?php echo $this->webroot ?>css/jquery.jgrowl.css"	media="all" rel="stylesheet" type="text/css" />
    <script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>

    <!-- Main Theme Stylesheet :: CSS -->
    <link href="<?php echo $this->webroot; ?>common/theme/css/style-default-menus-dark.css?1374506511" rel="stylesheet" type="text/css" />

    <!-- LESS.js Library -->
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/less.min.js"></script>

    <!--themer-->
    <link href="<?php echo $this->webroot; ?>css/themer.css" rel="stylesheet" />


    <!-- Global -->

    <script>
        //<![CDATA[
        var basePath = '',
            commonPath = '<?php echo $this->webroot; ?>common/';

        // colors
        var primaryColor = '#7faf00',
            dangerColor = '#b55151',
            successColor = '#609450',
            warningColor = '#ab7a4b',
            inverseColor = '#45484d';

        var themerPrimaryColor = primaryColor;
        //]]>

        //themer
        document.cookie="themerSelectedTheme="+<?php echo empty($themerSelectedTheme) ? 0 : $themerSelectedTheme  ?>;

    </script>
    <style>
        .widget {
            margin-bottom: 5px;
        }

        body.login #login .widget .widget-body {
            background-color: white;
        }

        .container{
            width:auto;
        }

        .control-group{
            width:100%;
            clear: both;
        }


        body.login #login label{font-weight: normal;float: left;text-align: right;width:180px;margin-bottom: 20px; height: 30px;line-height:30px;}
        .controls{float: left;padding-left: 10px;}
        .controls input{max-width: 250px;}
        .row-fluid .span6{margin-left:0px;padding-left: 100px;}
        .required{color: red;}
        /*.control-group{padding-bottom: 10px;}*/
        body.login #login .container input{margin-bottom: 20px;padding-top: 4px;padding-bottom: 4px;}
        body.login #login .container textarea{width: 250px; padding-left: 9px; padding-right: 9px;}
        body.login #login a:not(.btn) {
            color: #e5412d;
            text-decoration: none;
        }
    </style>

</head>
<body class="document-body login">

<!-- Wrapper -->
<div id="login">

    <div class="container">
        <div style="padding: 0 20px 20px;">
            <?php
            $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';

            if (file_exists($logo_path)) {
                $logo = $this->webroot . 'upload/images/logo.png';
            } else {
                $logo = $this->webroot . 'images/logo.png';
            }
            ?>
            <img src="<?php echo $logo ?>" alt="" style="width: 116px; height: 44px;" />

        </div>

        <div class="innerLR">

            <div class="widget widget-tabs widget-body-white">

                <div class="widget-head">
                    <h3 class="heading"><?php __('Sign Up'); ?></h3>
                </div>

                <div class="widget-body">
                    <?php $backsignup = $session->read('backsignup');?>
                    <?php
                        $action = isset($agentId) ? '/signup/index/' . base64_encode($agentId) : '/signup/index';
                    ?>
                    <?php echo $form->create('Signup', array('action' => $action, 'url' => $action, 'id' => 'signup')); ?>
                    <input type="hidden" name="lang" value="eng">

                    <div id="form1" >
                        <div class="widget-head"><h4 class="heading"><?php __('Basic Info'); ?></h4></div>
                        <div class="widget-body" style="overflow:visible;">
                            <?php
                           if(empty($backsignup)):
                               ?>
                               <script>
                                   $(function(){
                                       $('#login1').val('');
                                       $('#password').val('');
                                   })
                               </script>
                           <?php endif; ?>
                            <table class="form  table table-striped  table-bordered  table-white table-primary default">
                                 <tr>
                                    <td class="align_right padding-r20"><?php echo __('Company Information') ?> <span class="required"> *</span></td>
                                    <td>
                                     <?php echo $form->input('company', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'company','value' => array_keys_value($backsignup,'company'))) ?>                            </td>
                                    </td>

                                    <td class="align_right padding-r20"><?php echo __('Address') ?><span class="required"> *</span></td>
                                    <td>
                                        <?php echo $form->input('address', array('maxlength' => 500, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'address' ,'value' => array_keys_value($backsignup,'address'))) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r20"><?php echo __('Username') ?><span class="required"> *</span></td>
                                    <td>
                                    <?php echo $form->input('login', array('maxlength' => 40, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,funcCall[notEqualAdmin]]','id' =>'login1' ,'value' => array_keys_value($backsignup,'login'))) ?>                            </td>
                                    </td>
                                    <td class="align_right padding-r20"><?php echo 'City'; ?><span class="required"> *</span></td>
                                    <td><?php echo $form->input('city', array('maxlength' => 40, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'city' ,'value' => array_keys_value($backsignup,'city'))) ?></td>
                                </tr>
                                <tr>
                                    <td  class="align_right padding-r20"><?php echo __('Password') ?><span class="required"> *</span></td>
                                    <td ><?php echo $form->input('password', array('maxlength' => 50, 'type' => 'password', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[onlyLetterNumber],minSize[6]]','id' =>'password' ,'value' => array_keys_value($backsignup,'password'))) ?></td>
                                    <td  class="align_right padding-r20"><?php echo 'State'; ?><span class="required"> *</span></td>
                                    <td ><?php echo $form->input('state', array('maxlength' => 40, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'state' ,'value' => array_keys_value($backsignup,'state'))) ?></td>
                                </tr>
                                <tr>
                                        <td  class="align_right padding-r20"><?php echo __('Confirm password') ?><span class="required"> *</span></td>
                                        <td><?php echo $form->input('repassword', array('maxlength' => 50, 'type' => 'password', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,equals[password]]','id' =>'repassword' ,'value' => array_keys_value($backsignup,'repassword'))) ?></td>
                                        <td  class="align_right padding-r20"><?php echo 'Zip'; ?><span class="required"> *</span></td>
                                        <td><?php echo $form->input('zip', array('maxlength' => 40, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'zip' ,'value' => array_keys_value($backsignup,'zip'))) ?></td>
                                </tr>
                                <tr >
                                    <td class="align_right padding-r20"><?php echo __('Name of Business Contact') ?><span class="required"> *</span></td>
                                    <td ><?php echo $form->input('contact_name', array('maxlength' => 17, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,maxSize[200]]','id' =>'contact_name' ,'value' => array_keys_value($backsignup,'contact_name'))) ?></td>
                                    <td class="align_right padding-r20"><?php echo 'Country'; ?><span class="required"> *</span></td>
                                    <td ><?php echo $form->input('billing_country', array('maxlength' => 40, 'options' => $country_arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_country' ,'value' => 'US')) ?></td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r20"><?php echo __('Main e-mail', true); ?><span class="required"> *</span></td>
                                    <td><?php echo $form->input('email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[email]]','id' =>'email' ,'value' => array_keys_value($backsignup,'email'))) ?></td>
                                    <td class="align_right padding-r20"><?php echo __('Phone') ?></td>
                                    <td ><?php echo $form->input('phone', array('maxlength' => 17, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[custom[onlyNumber]','id' =>'phone' ,'value' => array_keys_value($backsignup,'phone'))) ?></td>
                                </tr>
                                <tr class="control-group">
                                    <td class="align_right padding-r20"><?php echo __('NOC e-mail', true); ?><span class="required"> *</span></td>
                                    <td ><?php echo $form->input('noc_email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[email]]','id' =>'noc_email' ,'value' => array_keys_value($backsignup,'noc_email'))) ?></td>
                                    <td class="align_right padding-r20"><?php echo __('Tax ID', true); ?></td>
                                    <td ><?php echo $form->input('tax_id', array('maxlength' => 17, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[custom[onlyNumber]]','id' =>'tax_id' ,'value' => array_keys_value($backsignup,'tax_id'))) ?></td>
                                </tr>
                                <tr class="control-group">
                                        <td class="align_right padding-r20"><?php echo __('Rates e-mail', true); ?><span class="required"> *</span></td>
                                        <td ><?php echo $form->input('rate_email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[email]]','id' =>'rate_email' ,'value' => array_keys_value($backsignup,'rate_email'))) ?></td>
                                   <?php if (isset($agentInfo) && isset($agentId)): ?>
                                        <td class="align_right padding-r20"><?php echo __('Referral', true); ?></td>
                                        <td >
                                            <input type="hidden" name="data[Signup][agent_assoc_id]" value="<?php echo $agentId; ?>">
                                            <?php
                                            $agentName = "";
                                            foreach ($agentInfo as $item) {
                                                if ($item['Agent']['agent_id'] == $agentId) {
                                                    $agentName = $item['Agent']['agent_name'];
                                                    break;
                                                }
                                            }
                                            ?>
                                            <b><?php echo $agentName; ?></b>
                                        </td>
                                   <?php else: ?>
                                       <td></td>
                                       <td></td>
                                   <?php endif; ?>
                                </tr>
                            </table>




                            <!--                                <div class="heading-button center" id="form_footer">-->
                            <!--                                    <div class="buttons pull-right" style="margin-top: 20px;float: none;">-->
                            <!--                                        <input id="continue_next" type="button" continue_next value="Continue" class="btn btn-primary">-->
                            <!--                                    </div>-->
                            <!--                                    <div class="clearfix"></div>-->
                            <!--                                </div>-->
                        </div>
                    </div>









                    <div >
                        <div class="widget-head"><h4 class="heading"><?php __('Billing Info'); ?></h4></div>
                        <div class="widget-body">
                           <script>
                               $(function(){
                                   //billing 复制 basic info

                                   $('#use_same').click(
                                       function(){

                                           var address = $('#address').val();
                                           $('#billing_address').val(address);

                                           var city = $('#city').val();
                                           $('#billing_city').val(city);

                                           var state = $('#state').val();
                                           $('#billing_state').val(state);

                                           var zip = $('#zip').val();
                                           $('#billing_zip').val(zip);

                                           var country = $('#billing_country').first().val();
                                           $('select#billing_country').last().val(country);

                                           var contact_name =  $('#contact_name').val();
                                           $('#billing_contact_name').val(contact_name);

                                           var phone =  $('#phone').val();
                                           $('#billing_phone').val(phone);

                                           var email = $('#email').val();
                                           $('#billing_email').val(email);


                                       }
                                   );
                               })
                           </script>

                           <table class="form  table table-striped  table-bordered  table-white table-primary default">
                                <tr >
                                    <td colspan="4" class="padding-r20">
                                     <input type="button" style="margin-bottom: 0px;margin-top: 10px" value="Use the same as Basic Info" id="use_same" class="btn btn-primary">
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="align_right padding-r20"><?php echo __('Address') ?><span class="required"> *</span></td>
                                    <td><?php echo $form->input('billing_address', array('maxlength' => 500, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required, maxSize[1000]]','id' =>'billing_address' ,'value' => array_keys_value($backsignup,'billing_address'))) ?></td>
                                    <td class="align_right padding-r20"><?php echo __('Billing Contact Name') ?><span class="required"> *</span></td>
                                    <td><?php echo $form->input('billing_contact_name', array('maxlength' => 40, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_contact_name' ,'value' => array_keys_value($backsignup,'billing_contact_name'))) ?></td>
                                </tr>
                                <tr>
                                     <td class="align_right padding-r20"><?php echo 'City'; ?><span class="required"> *</span></td>
                                     <td ><?php echo $form->input('billing_city', array('maxlength' => 40, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_city' ,'value' => array_keys_value($backsignup,'billing_city'))) ?></td>
                                     <td class="align_right padding-r20"><?php echo __('Billing Phone') ?></td>
                                     <td ><?php echo $form->input('billing_phone', array('maxlength' => 17, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[custom[onlyNumber]]','id' =>'billing_phone' ,'value' => array_keys_value($backsignup,'billing_phone'))) ?></td>
                                 </tr>
                                 <tr >
                                    <td class="align_right padding-r20"><?php echo 'State'; ?><span class="required"> *</span></td>
                                    <td ><?php echo $form->input('billing_state', array('maxlength' => 40, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_state' ,'value' => array_keys_value($backsignup,'billing_state'))) ?></td>
                                    <td class="align_right padding-r20"><?php echo __('Billing e-mail', true); ?><span class="required"> *</span></td>
                                    <td><?php echo $form->input('billing_email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[email]]','id' =>'billing_email' ,'value' => array_keys_value($backsignup,'billing_email'))) ?></td>
                                 </tr>
                                 <tr class="control-group">
                                    <td class="align_right padding-r20"><?php echo 'Zip'; ?><span class="required"> *</span></td>
                                    <td ><?php echo $form->input('billing_zip', array('maxlength' => 40, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_zip' ,'value' => array_keys_value($backsignup,'billing_zip'))) ?></td>
                                    <td class="align_right padding-r20"><?php echo 'Country'; ?><span class="required"> *</span></td>
                                    <td ><?php echo $form->input('billing_country', array('maxlength' => 40, 'options' => $country_arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_country' ,'value' => 'US')) ?></td>
                                 </tr>
                           </table>
                        </div>
                    </div>





                    <div>
                        <div class="widget-head"><h4 class="heading"><?php __('Trunk IPs'); ?></h4></div>
                        <div class="widget-body">
                            <div class="row-fluid">

                                <!-- Column -->
                                <div class="row-fluid">

                                    <!-- Column -->
                                    <div class="overflow_x" style="margin:20px;">
                                        <div style="margin-TOP:20px;margin-bottom: 20px;">
                                            <bottom id="addHost" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i>Add Host </bottom>
                                        </div>
                                        <table class="footable table table-striped table-bordered  table-white table-primary default" id="host_table">
                                            <thead>
                                            <tr>
                                                <th class="baidiv" width="40%">IP</th>
                                                <th class="baidiv" width="20%">Netmask</th>
                                                <th class="baidiv" width="20%">Port</th>

                                                <th class="last baidiv" width="20%">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="rows" id="rows-ip">



                                            <tr id="mb" style="">
                                                <td class="value baidiv">
                                                    <input type="text" class="nohei validate[required,custom[ip]]"  name="data[Signup][ip][]" id="ip">
                                                </td>
                                                <td class="baidiv"><select name="data[Signup][netmark][]" class="nohei" style="width: 100px;" id="GatewaygroupNeedRegister">
                                                        <option value="32">32</option>
                                                        <option value="31">31</option>
                                                        <option value="30">30</option>
                                                        <option value="29">29</option>
                                                        <option value="28">28</option>
                                                        <option value="27">27</option>
                                                        <option value="26">26</option>
                                                        <option value="25">25</option>
                                                        <option value="24">24</option>
                                                    </select></td>
                                                <td class="value baidiv"><input type="text" style="width: 100px;" class="nohei validate[required,custom[number]]" name="data[Signup][port][]" value="5060" id="port" maxlength="16"></td>


                                                <td style="width: 55px; text-align:center;" class="last baidiv">
                                                    <a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"><i class="icon-remove"></i></a>
                                                </td>
                                            </tr></tbody>
                                        </table>
                                        <script type="text/javascript">
                                            jQuery(document).ready(function(){
                                                var mb=jQuery('#mb').remove();
                                                jQuery('#addHost').click(function(){
                                                    mb.clone(true).appendTo('#host_table tbody');
                                                    return false;
                                                }).trigger('click');


                                            });
                                        </script>

                                    </div>







                                </div>

                            </div>
                            <!--                                <div class="heading-button center" id="form_footer">-->
                            <!--                                    <div class="buttons pull-right" style="margin-top: 20px;float: none;">-->
                            <!--                                        <input id="continue_next" type="button" continue_next value="Continue" class="btn btn-primary">-->
                            <!--                                    </div>-->
                            <!--                                    <div class="clearfix"></div>-->
                            <!--                                </div>-->
                        </div>
                    </div>

                    <?php if (!empty($product_name_arr)) : ?>
                        <div >
                            <div class="widget-head"><h4 class="heading"><?php __('Products'); ?></h4></div>
                            <div class="widget-body">
                                <div class="row-fluid">

                                    <!-- Column -->
                                    <div class="row-fluid">

                                        <!-- Column -->
                                        <div class="overflow_x" style="margin:20px;">
                                            <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded table-condensed" id="product_table">
                                                <thead>
                                                <tr>
                                                    <th width="25%"><?php __('Product Name'); ?></th>
                                                    <!--  <th width="15%"><?php __('Routing Plan'); ?></th>
                                                    <th width="35%"><?php __('Rate Table'); ?></th> -->
                                                    <th width="50%"><?php __('Description'); ?></th>
                                                    <th width="15%"><?php __('Tech Prefix'); ?></th>

                                                    <th width="10%"><?php __('Action'); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody class="rows" id="rows-ip">



                                                <tr id="mb_p">
                                                    <td>
                                                        <select name = "product_id[]" id="product_id" class="input in-text in-input product_id">
                                                            <option value=""></option>
                                                            <?php foreach($product_name_arr as $key => $product):?>
                                                            <option value="<?php echo $key;?>" data-rate-table="<?php echo isset($product_rate_table[$key]) ? base64_encode($product_rate_table[$key]) : '';?>"><?php echo $product;?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </td>

                                                    <!-- <td></td> -->
                                                    <td></td>
                                                    <td></td>


                                                    <td style="width: 55px; text-align:center;" class="last baidiv">
                                                         <!--a title="delete" rel="delete" onclick="removeVal($(this).closest('tr').find('select').val());$(this).closest('tr').remove();"><i class="icon-remove"></i></a-->
                                                         <a title="<?php __('Download Rate'); ?>" class="no-event download_rate" href="<?php echo $this->webroot ?>clients/download_rate/" >
                                                           <i class="icon-download"></i>
                                                         </a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <script type="text/javascript">
                                                <?php if(!empty($product_arr)) :?>
                                                //product_arr
                                                var product_arr = <?php echo json_encode($product_arr)?>;
                                                <?php endif;?>

                                                function selProducts() {
                                                    var arr = new Array();
                                                    $('.product_id').each(function( index ) {
                                                        arr[index] = $( this ).val();
                                                    });
                                                    return arr;
                                                }
                                                function unselectedProducts() {
                                                    var arr = new Array();
                                                    $('.product_id').each(function( index ) {
                                                        arr[index] = $( this ).val();
                                                    });
                                                    return $(Object.keys(product_arr)).not(arr).get();
                                                }

                                                function setVal(el, val) {
                                                    $('.product_id option[value='+el.val()+']').each(function () {
                                                        $(this).removeAttr('disabled');
                                                    });
                                                    $('.product_id option[value='+val+']').each(function () {
                                                        $(this).attr('disabled', 'disabled');
                                                    });
                                                    el.val(val);
                                                    var tr = $(el).parent().parent();
                                                    tr.find('td:eq(2)').html(product_arr[val]['tech_prefix']);
                                                    el.find('option[value='+val+']').removeAttr('disabled');
                                                }

                                                function removeVal(val) {
                                                    $('.product_id option[value='+val+']').each(function () {
                                                        $(this).removeAttr('disabled');
                                                    });
                                                }

                                                jQuery(document).ready(function(){
                                                    <?php if(!empty($product_arr)) :?>

                                                    $('.download_rate').on('click', function(e){
                                                       e.preventDefault();
                                                       var rate_table_id = $(this).closest('tr').find('#product_id option:selected').attr('data-rate-table');
                                                       location.href= $(this).attr('href') + rate_table_id;
                                                    });

                                                    $('.product_id').on('change',function(){
                                                        var product_id = $(this).val();
                                                        var tr = $(this).parent().parent();
                                                        if(product_id){
                                                           addProduct();
                                                           $(this).closest('tr').find('.download_rate').removeClass('no-event');
                                                        }else{
                                                           $(this).closest('tr').find('.download_rate').addClass('no-event');
                                                           $(this).closest('tr').find('td:eq(2)').html('');
                                                        }
                                                        // tr.find('td:eq(1)').html(product_arr[product_id]['routing_plan']);
                                                        // tr.find('td:eq(2)').html(product_arr[product_id]['rate_table']);
                                                        // tr.find('td:eq(3)').html(product_arr[product_id]['tech_prefix']);

                                                        tr.find('td:eq(1)').html(product_arr[product_id]['description']);
                                                        tr.find('td:eq(2)').html(product_arr[product_id]['tech_prefix']);

                                                        var sp = selProducts();
                                                        var cnt = 0;
                                                        for(var i = sp.length - 1; i >= 0; i--) {
                                                            if(sp[i] === product_id) {
                                                                cnt++;
                                                            }
                                                        }

                                                        if (cnt > 1) {
                                                            showMessages_new("[{'field':'','code':'101','msg':'<?php __('Product must be unique!'); ?>'}]");
                                                        }
                                                        var val = $(this).val();
                                                        do {
                                                            var sel_prd = selProducts();
                                                            var unq = $.inArray(product_id,sel_prd);
                                                            tr.parent().find('select option[value='+sel_prd[unq]+']').attr('disabled', 'disabled');
                                                        } while (unq != -1);
                                                        $(this).find('option[value='+val+']').removeAttr('disabled');
                                                        var uns = unselectedProducts();
                                                        for(var i = uns.length - 1; i >= 0; i--) {
                                                            $('.product_id option[value='+uns[i]+']').each(function () {
                                                                $(this).removeAttr('disabled');
                                                            });
                                                        }

                                                    }).trigger('change');
                                                    //setTimeout("jQuery('#mb_p').remove()",50);

                                                    <?php endif;?>


                                                    function addProduct(){
                                                        var mb_p=jQuery('#mb_p');
                                                        if (selProducts().length < Object.keys(product_arr).length + 1) {
                                                            var selected = selProducts();
                                                            var uns = unselectedProducts();
                                                            mb_p.clone(true).appendTo('#product_table tbody');
                                                            var elem = $('#product_table tbody select').last();
                                                            var tr = $('#product_table tbody tr').last();
                                                            tr.find('td:eq(2)').html('').end().find('.download_rate').addClass('no-event');
                                                            for(var i = selected.length - 1; i >= 0; i--) {
                                                                $(elem).find('option[value='+selected[i]+']').attr('disabled', 'disabled');
                                                            }
                                                        } else {
                                                            showMessages_new("[{'field':'','code':'101','msg':'<?php __('You can`t add more fields! They are unique!'); ?>'}]");
                                                        }
                                                        return false;
                                                    };

                                                    <?php if(empty($product_arr)) :?>
                                                    jQuery('#mb_p').find('.icon-remove').click();

                                                    <?php endif;?>

                                                });
                                            </script>

                                        </div>







                                    </div>

                                </div>

                            </div>
                        </div>
                    <?php endif; ?>






                    <!-- <div id="form3" data-collapse-closed="true" data-toggle="collapse-widget" class="widget"> -->
                    <!-- <div class="widget-head"><h4 class="heading"><?php __('Finish'); ?></h4></div> -->
                    <div class="center widget-body">
                        <label for="captcha"><?php __('Verify Code'); ?></label>
                        <div class="input-append">
                            <?php echo $form->input('captcha', array('maxlength' => 4, 'label' => false, 'div' => false, 'class' => 'validate[required]','id' =>'captcha', 'placeholder' => 'Verify Code')) ?>
                            <img style="margin-top:2px;margin-left: 30px;" src="<?php echo $this->webroot ?>homes/validate_code" required/>
                        </div>
                        <div class="heading-button center" id="form_footer">
                            <div class="buttons pull-right" style="margin: 0;float: none;">
                                <input type="submit" value="Submit" class="btn btn-primary">
                                <input type="button" value="Revert" reset class="btn btn-default">
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <!-- </div> -->



                    <?php echo $form->end(); ?>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- // Wrapper END -->

<!-- Themer -->
<!--<div id="themer" class="collapse"></div>-->
<!-- // Themer END -->

<div id="loading"><i class="icon-spinner icon-spin icon-large" style="" id="loading_i"></i></div>

<!-- MiniColors Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/color/jquery-miniColors/jquery.miniColors.js"></script>

<!-- Cookie Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.cookie.js"></script>



<!-- Themer -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/themer.js"></script>


<!-- jQuery Event Move -->
<script
    src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.event.move/js/jquery.event.move.js"></script>

<!-- jQuery Event Swipe -->
<script
    src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.event.swipe/js/jquery.event.swipe.js"></script>


<!-- History.js -->
<!--[if gt IE 8]><!-->
<script src="http://browserstate.github.io/history.js/scripts/bundled/html4+html5/jquery.history.js"></script>
<!--<![endif]-->

<!-- jQuery Ajaxify -->
<!--[if gt IE 8]><!-->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery-ajaxify/ajaxify-html5.js"></script>
<!--<![endif]-->


<!-- Code Beautify -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/js-beautify/beautify.js"></script>
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/js-beautify/beautify-html.js"></script>

<!-- PrettyPhoto -->
<script
    src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>


<!-- Modernizr -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/modernizr.js"></script>

<!-- Bootstrap -->
<script src="<?php echo $this->webroot; ?>common/bootstrap/js/bootstrap.min.js"></script>

<!-- Holder Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/holder/holder.js?1374506526"></script>

<!-- Uniform Forms Plugin -->
<script
    src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

<!-- MegaMenu -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/megamenu.js?1374506526"></script>

<!-- Common Demo Script -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/signup.js"></script>

<!-- Validation Engine -->
<script src="<?php echo $this->webroot; ?>common/validationEngine/jquery.validationEngine.js"></script>
<script src="<?php echo $this->webroot; ?>common/validationEngine/jquery.validationEngine-en.js"></script>
<script src="<?php echo $this->webroot; ?>common/validationEngine/validateExtend.js"></script>

<!--<script src="<?php echo $this->webroot; ?>common/theme/scripts/class4/common.js"></script>-->
<script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>

<!-- Notyfy Notifications Plugin -->
<link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.css"
      rel="stylesheet"/>
<link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/themes/default.css"
      rel="stylesheet"/>
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.js"></script>

<script src="<?php echo $this->webroot; ?>js/bb-functions.js"></script>
<?php
if (empty($m) || $m == '')
{
    $m = $session->read('m');
    $session->del('m');
}
if (!empty($m))
{
    ?>
    <script type="text/javascript">
        showMessages_new("<?php echo $m ?>");
    </script>
<?php } ?>


<script type="text/javascript">
    function notEqualAdmin(field, rules, i, options)
    {
        if (field.val() == "admin") {
// this allows the use of i18 for the error msgs
            return 'This field can not be admin!';
        }
    }
</script>

<script type="text/javascript">
    $('#signup').validationEngine();
    $(
        function(){

            //处理邮箱地址input
            var input_email = $('input[class *= "custom[email]"]');
            if(input_email.length > 0){
                input_email.each(function(){
                    var old = $(this).attr('class');
                    var arr = old.split('custom[email]');
                    var new_class = arr[0] + 'custom[email],custom[email_chars]' + arr[1];
                    $(this).attr('class',new_class);


                });
                input_email.live('input',function() {
                    var text = $(this).val();
//                alert(text);
                    if(text.indexOf(' ') !== -1 || text.indexOf(',') !== -1){
                        var new_text = text.replace(/\s/g,'');
                        new_text = new_text.replace(/,/g,';');
//                alert(new_text);
                        $(this).val(new_text);
                    }

                });
            }
//            $("span.collapse-toggle").unbind('click');
//            $("span.collapse-toggle").css('cursor', 'not-allowed');
//
//
//            //切换自身
//            function coggle_self(myself){
//                //切换自身
//                var my_widget_body = myself.parent().next();
//                my_widget_body.collapse('toggle');
//
//                //自身overflow
//                choose_overflow(my_widget_body);
//                //alert(my_widget_body.hasClass('in'));
//            }
//
////            //关闭其他
//            function open_close(index){
//                $(".collapse").each(function(index1){
//                    //console.log(index,index1,$(this).hasClass('in'));
//                    if(index1 !== index  && $(this).hasClass('in')){
//                        //var my_widget = $(this).parents('.collapse:first').find('.widget-body');
//                        $(this).collapse('toggle');
//                        $(this).css('overflow', 'hidden');
//
//                    }});
//            }
//
////            //处理自身overflow
//            function choose_overflow(my_widget_body){
//
//                var is_open = my_widget_body.hasClass('in');
//
//                if(is_open){
//                    my_widget_body.css('overflow', 'visible');
//                } else {
//                    my_widget_body.css('overflow', 'hidden');
//                }
//            }
//
//            //允许小于自身的点击，禁止大于自身的点击
//            function not_allow(index){
//                $("span.collapse-toggle:lt("+index+")").css('cursor', 'pointer');
//                $("span.collapse-toggle").unbind('click');
//                $("span.collapse-toggle:lt("+index+")").click(function(){
//                    //切换自身
//                    var is_open = $(this).parent().next();
//                    if(!is_open.hasClass('in')){
//
//                        coggle_self($(this));
//                    }else{
//                        $(this).css('cursor', 'not-allowed');
//                    }
//
//                    //关闭其他
//                    var index = $('span.collapse-toggle').index(this);
//                    open_close(index);
////console.log(index);
//                    //禁止大于自身
//                    not_allow(index);
//                });
//                //$("span.collapse-toggle:gt("+index+")").unbind('click');
//                $("span.collapse-toggle:gt("+(index-1)+")").css('cursor', 'not-allowed');
//            }
//
//
//
//            $("[continue_next]").click(
//                function(){
//                    var curr = $(this).parents('.widget:first');
//                    var next = curr.next();
//                    var n_open = next.find('span.collapse-toggle');
//
//                    //var index_con = $("[continue_next]").index(this) + 1;alert(index_con);alert($("#signup").validationEngine('validate',"#form1"));
//
//                    //    return false;
//
//                    //打开下一个
//                    coggle_self(n_open);
//
//                    //关闭其他
//                    var index = $('span.collapse-toggle').index(n_open);
//                    open_close(index);
//
//                    //允许小于自身，禁止大于自身
//                    not_allow(index);
//
//
//                }
//            );


            //处理自身overflow
//            $(".collapse").each(function(){
//                if($(this).hasClass('in')){
//                    $(this).css('overflow', 'visible');
//                } else {
//                    $(this).css('overflow', 'hidden');
//                }
//            });

            $('.collapse-toggle').click(function(){
                var show = $(this).parent().next();
                show.collapse('toggle');
                if(show.hasClass('in')){
                    show.css('overflow', 'visible');
                } else {
                    show.css('overflow', 'hidden');
                }
            });





            $("[reset]").click(
                function(){
                    $('.input').val('');
                    $("span.collapse-toggle:first").click();
                }
            );
//
            $(":submit").unbind('click');
//
            $(":submit").click(
                function(){
                    if(!$('#signup').validationEngine('validate')){

//                        if($("#captcha").val()){
//                            $("span.collapse-toggle:first").click();
//                            setTimeout("$('#signup').validationEngine('validate')",500);
//
//                        }
                        $('.collapse').each(function(){
                            if(!$(this).hasClass('in')){
                                $(this).collapse('toggle');
                                $(this).css('overflow', 'visible');
                            }
                        });


                        //showMessages_new("[{'field':'','code':'101','msg':'<?php __('Please check all the form fields filled in correctly!'); ?>'}]");
                        return false;

                    }


                    var product_arr = new Array();
                    var have_dup = 0;
                    $('.product_id').each(function($i){
                        var _exist=$.inArray($(this).val(),product_arr);
                        if(_exist>=0){
                            have_dup = 1;
                            return false;
                        }
                        product_arr[$i] = $(this).val();

                    });

                    if (have_dup){
                        showMessages_new("[{'field':'','code':'101','msg':'<?php __('Exist Duplicate product!'); ?>'}]");
                        return false;
                    }
                }
            );




        }
    );


</script>

<script>
    $(document).ready(function() {
        $("#themer").remove();
    });
</script>

</body>
</html>