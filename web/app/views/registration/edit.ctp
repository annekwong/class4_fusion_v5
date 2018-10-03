<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Registration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('User Description') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('User Description')?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="javascript:void(0)" onclick="history.back(1);"><i></i> <?php __('Back')?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="widget">
                <div class="widget-head"><h4 class="heading"><?php __('User Description') ?></h4></div>
                <div class="widget-body">
                    <?php echo $form->create('Registration', array('action' => 'index', 'url' => '/registration/edit', 'id' => 'Registration')); ?>
                    <input type="hidden" name="edit_id" value="<?php echo $backsignup['id'];?>"/>
                    <input type="hidden" name="return_url" value="<?php echo $return_url;?>"/>
                    <table
                        class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Company Information') ?> </td>
                            <td>
                                <?php echo $form->input('company', array('maxlength' => 256, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,maxSize[200],custom[onlyLetterNumberLineSpace]]', 'id' => 'company', 'value' => array_keys_value($backsignup, 'company'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('Username') ?></td>
                            <td>
                                <?php
                                echo $form->input('login', array('maxlength' => 256, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[onlyLetterNumberLine],custom[HeadLetterNumberLine],funcCall[notEqualAdmin]]', 'id' => 'login1', 'value' => array_keys_value($backsignup, 'login')))
                                ?>
                            </td>
                        </tr>
                        <tr>

                            <td class="align_right padding-r20"><span class="show_password"><?php echo __('Password') ?></span></td>
                            <td style="height: 40px;">
                                <?php echo $form->input('password', array('maxlength' => 64, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[onlyLetterNumber],minSize[6]] show_password', 'id' => 'password', 'value' => array_keys_value($backsignup, 'password'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('Name of Bussiness Contact') ?></td>
                            <td>
                                <?php echo $form->input('contact_name', array('maxlength' => 17, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,maxSize[200],custom[onlyLetterNumberLineSpace]]','id' =>'contact_name' ,'value' => array_keys_value($backsignup,'contact_name'))) ?>
                            </td>
                            <!--                            <td class="align_right padding-r20">--><?php //echo __('Phone') ?><!--</td>-->
                            <!--                            <td>-->
                            <!--                                --><?php //echo $form->input('phone', array('maxlength' => 17, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[custom[onlyNumberHyphen]]', 'id' => 'phone', 'value' => array_keys_value($backsignup, 'phone'))) ?>
                            <!--                            </td>-->

                        </tr>


                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Main e-mail', true); ?></td>
                            <td>
                                <?php echo $form->input('email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[email]]', 'id' => 'email', 'value' => array_keys_value($backsignup, 'email'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('NOC e-mail', true); ?></td>
                            <td>
                                <?php echo $form->input('noc_email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[email]]', 'id' => 'noc_email', 'value' => array_keys_value($backsignup, 'noc_email'))) ?>
                            </td>
                        </tr>

                        <tr>
                            <!--                            <td class="align_right padding-r20">--><?php //echo __('Billing e-mail', true); ?><!--</td>-->
                            <!--                            <td>-->
                            <!--                                --><?php //echo $form->input('billing_email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[email]]', 'id' => 'billing_email', 'value' => array_keys_value($backsignup, 'billing_email'))) ?>
                            <!--                            </td>-->

                            <td class="align_right padding-r20"><?php echo __('Rates e-mail', true); ?></td>
                            <td>
                                <?php echo $form->input('rate_email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[email]]', 'id' => 'rate_email', 'value' => array_keys_value($backsignup, 'rate_email'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('Rate Delivery Email', true); ?></td>
                            <td>
                                <?php echo $form->input('rate_delivery_email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[custom[email]]', 'id' => 'rate_delivery_email', 'value' => array_keys_value($backsignup, 'rate_delivery_email'))) ?>
                            </td>
                        </tr>

                        <!--                        <tr>-->
                        <!--                            -->
                        <!---->
                        <!--                            <td class="align_right padding-r20">--><?php //echo __('Address', true); ?><!--</td>-->
                        <!--                            <td>-->
                        <!--                                --><?php //echo $form->input('address', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'address' ,'value' => array_keys_value($backsignup,'address'))) ?>
                        <!--                            </td>-->
                        <!--                            <td class="align_right padding-r20">--><?php //echo __('Tax ID', true); ?><!--</td>-->
                        <!--                            <td>-->
                        <!--                                --><?php //echo $form->input('tax_id', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[custom[onlyNumber]]', 'id' => 'tax_id', 'value' => array_keys_value($backsignup, 'tax_id'))) ?>
                        <!--                            </td>-->

                        <!--                        </tr>-->



                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Address') ?></td>
                            <td>
                                <?php echo $form->input('address', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'address' ,'value' => array_keys_value($backsignup,'address'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('City', true); ?></td>
                            <td>
                                <?php echo $form->input('city', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'city' ,'value' => array_keys_value($backsignup,'city'))) ?>
                            </td>
                            <!--                            <td class="align_right padding-r20">--><?php //echo __('Account Details', true); ?><!--</td>-->
                            <!--                            <td>-->
                            <!--                                --><?php //echo $form->input('details', array('rows' => '5', 'label' => false, 'div' => false, 'class' => 'input in-text in-input', 'id' => 'details', 'value' => array_keys_value($backsignup, 'details'))) ?>
                            <!--                            </td>-->

                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('State', true); ?></td>
                            <td>
                                <?php echo $form->input('state', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'state' ,'value' => array_keys_value($backsignup,'state'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('Zip', true); ?></td>
                            <td>
                                <?php echo $form->input('zip', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'zip' ,'value' => array_keys_value($backsignup,'zip'))) ?>
                            </td>


                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Country', true); ?></td>
                            <td>
                                <?php echo $form->input('country', array('options' => $country_arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'country' ,'value' => array_keys_value($backsignup,'country'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('Phone') ?></td>
                            <td>
                                <?php echo $form->input('phone', array('maxlength' => 17, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[custom[onlyNumberHyphen]]', 'id' => 'phone', 'value' => array_keys_value($backsignup, 'phone'))) ?>
                            </td>


                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Tax ID', true); ?></td>
                            <td>
                                <?php echo $form->input('tax_id', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[custom[onlyNumber]]', 'id' => 'tax_id', 'value' => array_keys_value($backsignup, 'tax_id'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php //echo __('Address', true); ?></td>
                            <td>
                                <!--                                --><?php //echo $form->input('address', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'address' ,'value' => array_keys_value($backsignup,'address'))) ?>
                            </td>


                        </tr>





                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Billing Address') ?></td>
                            <td>
                                <?php echo $form->input('billing_address', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_address' ,'value' => array_keys_value($backsignup,'billing_address'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('Billing City', true); ?></td>
                            <td>
                                <?php echo $form->input('billing_city', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_city' ,'value' => array_keys_value($backsignup,'billing_city'))) ?>
                            </td>
                            <!--                            <td class="align_right padding-r20">--><?php //echo __('Account Details', true); ?><!--</td>-->
                            <!--                            <td>-->
                            <!--                                --><?php //echo $form->input('details', array('rows' => '5', 'label' => false, 'div' => false, 'class' => 'input in-text in-input', 'id' => 'details', 'value' => array_keys_value($backsignup, 'details'))) ?>
                            <!--                            </td>-->

                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Billing State', true); ?></td>
                            <td>
                                <?php echo $form->input('billing_state', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_state' ,'value' => array_keys_value($backsignup,'billing_state'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('Billing Zip', true); ?></td>
                            <td>
                                <?php echo $form->input('billing_zip', array('maxlength' => 100, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_zip' ,'value' => array_keys_value($backsignup,'billing_zip'))) ?>
                            </td>


                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Billing Country', true); ?></td>
                            <td>
                                <?php echo $form->input('billing_country', array('options' => $country_arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_country' ,'value' => array_keys_value($backsignup,'billing_country'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('Billing Contact Name') ?></td>
                            <td>
                                <?php echo $form->input('billing_contact_name', array('maxlength' => 17, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required]','id' =>'billing_contact_name' ,'value' => array_keys_value($backsignup,'billing_contact_name'))) ?>
                            </td>


                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Billing e-mail', true); ?></td>
                            <td>
                                <?php echo $form->input('billing_email', array('maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[required,custom[email]]', 'id' => 'billing_email', 'value' => array_keys_value($backsignup, 'billing_email'))) ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('Billing Phone') ?></td>
                            <td>
                                <?php echo $form->input('billing_phone', array('maxlength' => 17, 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input','id' =>'billing_phone' ,'value' => array_keys_value($backsignup,'billing_phone'))) ?>
                            </td>


                        </tr>

                    </table>

                    <?php if ($_SESSION['role_menu']['Management']['registration']['model_w']): ?>
                        <div style="margin-TOP:20px;margin-bottom: 20px;">
                            <bottom id="addHost" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i>Add Host
                            </bottom>
                        </div>
                    <?php endif; ?>
                    <table
                        class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded table-condensed"
                        id="host_table">
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
                                <input type="text" class="nohei validate[required,custom[ip]]" name="data[Registration][ip][]"
                                       id="ip">
                            </td>
                            <td class="baidiv"><select name="data[Registration][netmark][]" class="nohei"
                                                       style="width: 100px;"
                                                       id="GatewaygroupNeedRegister">
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
                            <td class="value baidiv"><input type="text"
                                                            class="nohei validate[required,custom[number]]"
                                                            name="data[Registration][port][]" id="port" maxlength="16" value="5060"></td>


                            <td style="width: 55px; text-align:center;" class="last baidiv">
                                <a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"><i
                                        class="icon-remove"></i></a>
                            </td>
                        </tr>
                        <?php if(!empty($ips)):
                            foreach($ips as $val):
                                ?>
                                <tr id="mb" style="">
                                    <td class="value baidiv">
                                        <input type="text" class="nohei validate[required,custom[ip]]" name="data[Registration][ip][]"
                                               id="ip" value="<?php echo array_keys_value($val[0], 'ip')?>">
                                    </td>
                                    <td class="baidiv"><select name="data[Registration][netmark][]" class="nohei" value="<?php echo array_keys_value($val[0], 'netmark')?>"
                                                               style="width: 100px;"
                                                               id="GatewaygroupNeedRegister">
                                            <option value="32" <?php if($val[0]['netmark']==32){echo 'selected="selected"';}?>>32</option>
                                            <option value="31" <?php if($val[0]['netmark']==31){echo 'selected="selected"';}?>>31</option>
                                            <option value="30" <?php if($val[0]['netmark']==30){echo 'selected="selected"';}?>>30</option>
                                            <option value="29" <?php if($val[0]['netmark']==29){echo 'selected="selected"';}?>>29</option>
                                            <option value="28" <?php if($val[0]['netmark']==28){echo 'selected="selected"';}?>>28</option>
                                            <option value="27" <?php if($val[0]['netmark']==27){echo 'selected="selected"';}?>>27</option>
                                            <option value="26" <?php if($val[0]['netmark']==26){echo 'selected="selected"';}?>>26</option>
                                            <option value="25" <?php if($val[0]['netmark']==25){echo 'selected="selected"';}?>>25</option>
                                            <option value="24" <?php if($val[0]['netmark']==24){echo 'selected="selected"';}?>>24</option>
                                        </select></td>
                                    <td class="value baidiv"><input type="text"
                                                                    class="nohei validate[required,custom[number]]"
                                                                    name="data[Registration][port][]" id="port" maxlength="16" value="<?php echo array_keys_value($val[0], 'port')?>"></td>


                                    <td style="width: 55px; text-align:center;" class="last baidiv">
                                        <?php if ($_SESSION['role_menu']['Management']['registration']['model_w']): ?>
                                            <a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"><i
                                                    class="icon-remove"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach;endif; ?>
                        </tbody>
                    </table>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            var mb = jQuery('#mb').remove();
                            jQuery('#addHost').click(function () {
                                mb.clone(true).appendTo('#host_table tbody');
                                return false;
                            });


                        });
                    </script>



                    <?php if ($_SESSION['role_menu']['Management']['registration']['model_w']): ?>
                        <div style="margin-TOP:20px;margin-bottom: 20px;">
                            <bottom id="addRoute" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i><?php echo __('Add Resource Prefix', true); ?>
                            </bottom>
                        </div>
                    <?php endif; ?>
                    <table
                        class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded table-condensed"
                        id="route_table">
                        <thead>
                        <tr>
                            <th width="25%">Product Name</th>
                            <th width="15%">Routing Plan</th>
                            <th width="35%">Rate Table</th>
                            <th width="15%">Tech Prefix</th>

                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody class="rows" id="rows-ip">
                        <tr id="mb_p">
                            <td>
                                <?php echo $form->input('product_id', array('name' => 'product_id[]','options' => $product_name_arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input product_id','id' =>'product_id')) ?>

                            </td>

                            <td></td>
                            <td></td>
                            <td></td>


                            <td style="width: 55px; text-align:center;" class="last baidiv">
                                <a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"><i class="icon-remove"></i></a>
                            </td>
                        </tr>
                        <?php if(!empty($backsignup['product_id'])):
                            foreach($backsignup['product_id'] as $val):
                                ?>
                                <tr id="mb_p">
                                    <td>
                                        <?php echo $form->input('product_id', array('name' => 'product_id[]','options' => $product_name_arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input product_id','id' =>'product_id' ,'value' => $val)) ?>

                                    </td>

                                    <td></td>
                                    <td></td>
                                    <td></td>


                                    <td style="width: 55px; text-align:center;" class="last baidiv">
                                        <?php if ($_SESSION['role_menu']['Management']['registration']['model_w']): ?>
                                            <a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"><i class="icon-remove"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach;endif; ?>
                        </tbody>
                    </table>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            //product_arr
                            <?php if(!empty($product_arr)): ?>
                            var product_arr = <?php echo json_encode($product_arr)?>;
   console.log(product_arr.length);
                            $('.product_id').live('change',function(){
                                var product_id = $(this).val();
                                var tr = $(this).parent().parent();
                                tr.find('td:eq(1)').html(product_arr[product_id]['routing_plan']);
                                tr.find('td:eq(2)').html(product_arr[product_id]['rate_table']);
                                tr.find('td:eq(3)').html(product_arr[product_id]['tech_prefix']);
                            }).trigger('change');
                            <?php endif ?>


                            var mb_p = jQuery('#mb_p').remove();
                            jQuery('#addRoute').click(function () {
                                if(Object.keys(product_arr).length == $('tr#mb_p').length){
                                    jGrowl_to_notyfy('Product rows cannot be greater then products count!', {theme: 'jmsg-error'});
                                    return false;
                                }
                                mb_p.clone(true).appendTo('#route_table tbody');
                                return false;
                            });


                        });
                    </script>





                    <?php if ($_SESSION['role_menu']['Management']['registration']['model_w']): ?>
                        <div id="form_footer" class="bottom-buttons separator center">
                            <div style="margin: 0;float:none;" class="buttons pull-right">
                                <input type="submit" class="btn btn-primary" value="<?php __('Save') ?>">
                                <input type="reset"  class="btn btn-default" value="<?php __('Revert') ?>">
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php echo $form->end(); ?>
                </div>


            </div>
        </div>
    </div>
</div>

<script>
    function notEqualAdmin(field, rules, i, options)
    {
        if (field.val() == "admin") {
// this allows the use of i18 for the error msgs
            return 'This field can not be admin!';
        }
    }
    $(function(){
        $('#Registration').validationEngine();

        //$(":submit").unbind('click');

        $("#Registration").submit(
            function(){
                if(!$('#Registration').validationEngine('validate')){

//
                    return false;

                }
                //$('#Registration').submit();
            }
        );
    })

</script>