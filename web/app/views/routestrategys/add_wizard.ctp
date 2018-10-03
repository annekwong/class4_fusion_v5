<style type="text/css">
    .in-text, .in-password, .in-textarea, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select {
        width: 250px;
    }

    select, textarea, input[type="text"] {
        margin-bottom: 0;
    }

    th .btn-primary, th .btn-primary:hover {
        background: #7FAF00;
    }
    .ms-container ul.ms-list{
        width: 280px;
    }
    .ms-container{
        background: transparent url('<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/img/switch.png') no-repeat 290px 80px;
    }
</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>routestrategys/add_wizard">
        <?php __('Routing') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>routestrategys/add_wizard">
        <?php echo __('Wizard') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>routestrategys/add_wizard">
        <?php echo $this->pageTitle ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="link_back btn btn-inverse btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>routestrategys/wizard">
        <i></i> <?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-tabs-double widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active">
                    <a class="glyphicons paperclip step" id="step1" data-value="1" data-toggle="tab" href="#tab1">
                        <i></i>
                        <span class="strong"><?php __('Step 1') ?></span>
                        <span><?php __('Define Carrier'); ?></span>
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js projector step" id="step2" data-value="2" data-toggle="tab"
                       href="#tab2">
                        <i></i>
                        <span class="strong"><?php __('Step 2') ?></span>
                        <span><?php __('Define Trunk') ?></span>
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js tag step" id="step3" hit="" data-value="3" data-toggle="tab"
                       href="#tab3">
                        <i></i>
                        <span class="strong"><?php __('Step 3') ?></span>
                        <span><?php __('Define Route ') ?></span>
                    </a>
                </li>

                <li>
                    <a class="glyphicons no-js tint step" id="step4" data-value="4" data-toggle="tab" href="#tab4">
                        <i></i>
                        <span class="strong"><?php __('Step 4') ?></span>
                        <span><?php __('Define Rate') ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <form method="post" id="wizard_form">
                <input type="hidden" name="id" value="<?php echo isset($wizard_info['id']) ? $wizard_info['id'] : ''?>"/>
                <div class="tab-content">


                    <!--step 1-->
                    <div id="tab1" class="tab-pane active" style="height:100%">
                        <table class="table  tableTools table-bordered  table-white">
                            <colgroup>
                                <col width="20%"/>
                                <col width="30%"/>
                                <col width="20%"/>
                                <col width="30%"/>
                            </colgroup>
                            <tr>
                                <td class="align_right padding-r20">
                                    <?php __('Choose Carrier') ?>
                                </td>
                                <td>
                                    <select name="data[Client][choose_client_type]" id="client_type" class="width220">
                                        <option value="0"><?php __('New Carrier') ?></option>
                                        <option value="1" <?php if(!empty($wizard_info)) echo 'selected="selected"'?>><?php __('Existing Carrier') ?></option>
                                    </select>
                                </td>
                                <td class="align_right padding-r20">
                                    <?php __('Carrier Name') ?>
                                </td>
                                <td>
                                    <input id="input_client_name" type="text" name="data[Client][input_client_name]"
                                           class="width220 validate[required,custom[onlyLetterNumberLineSpace]]"/>
                                    <select id="select_client_id" name="data[Client][select_client_id]" style="display:none;"
                                            class="width220 validate[required]" value="<?php echo $wizard_info['client_id']?>">
                                        <?php foreach ($clients as $client): ?>
                                            <option
                                                value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>

                            <tr class="create_carrier">
                                <td class="align_right padding-r20"><?php echo __('mode') ?></td>
                                <td>
                                    <?php
                                    $st = array('1' => __('Prepaid', true), '2' => __('postpaid', true));
                                    echo $form->input('Client.mode', array('id' => 'mode','options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-select width220'))
                                    ?>
                                </td>
                                <td class="align_right padding-r20">
                                    <span class="padding-r20"
                                          id="credit_type_flg"><?php echo __('allowedcredit') ?></span>
                                </td>
                                <td>

                                        <span id="unlimited_panel">
                                        <?php __('Unlimited') ?>
                                        <?php echo $form->input('Client.unlimited_credit', array('id' => 'unlimited_credit','class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox')) ?>
                                        </span>
                                    <?php echo $form->input('Client.allowed_credit', array('id' => 'allowed_credit','label' => false, 'value' => '0.000', 'div' => false, 'type' => 'text', 'class' => 'validate[min[0],custom[number]]', 'maxlength' => '30', 'style' => 'width: 100px; display: inline-block;')) ?>
                                    <span class='money'
                                          style="display:inline-block"><?php echo $default_currency; ?></span>
                                </td>
                            </tr>

                            <tr class="create_carrier">
                                <td class="align_right padding-r20"><?php echo __('Company Name') ?> </td>
                                <td>
                                    <?php echo $form->input('Client.company', array('maxlength' => 256, 'label' => false, 'div' => false, 'class' => 'input width220 validate[maxSize[200],custom[onlyLetterNumberLineSpace]]', 'value' => empty($post[0][0]['company_name']) ? '' : $post[0][0]['company_name'])) ?>
                                </td>
                                <td class="align_right padding-r20"><?php echo __('Main e-mail', true); ?> </td>
                                <td> <?php echo $form->input('Client.email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'width220 validate[custom[email]]', 'value' => empty($post[0][0]['corporate_contact_email']) ? '' : $post[0][0]['corporate_contact_email'])) ?></td>

                            </tr>
                            <tr class="create_carrier">
                                <td class="align_right padding-r20"><?php echo __('NOC e-mail', true); ?> </td>

                                <td> <?php echo $form->input('Client.noc_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'width220 validate[custom[email]]')) ?></td>
                                <td class="align_right padding-r20"><?php echo __('Billing e-mail', true); ?> </td>
                                <td> <?php echo $form->input('Client.billing_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'width220 validate[custom[email]]', 'value' => empty($post[0][0]['billing_contact_email']) ? '' : $post[0][0]['billing_contact_email'])) ?></td>

                            </tr>
                            <tr class="create_carrier">
                                <td class="align_right padding-r20"><?php echo __('Rates e-mail', true); ?> </td>
                                <td> <?php echo $form->input('Client.rate_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'width220 validate[custom[email]]')) ?></td>
                                <td class="align_right padding-r20"><?php echo __('Rate Delivery e-mail', true); ?> </td>
                                <td> <?php echo $form->input('Client.rate_delivery_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'width220 validate[custom[email]]')) ?></td>

                            </tr>


                        </table>
                        <div class="center separator">
                            <a value="next" data-toggle="tab" step="#step2" href=""
                               class="next input in-submit btn btn-primary"><?php __('Next') ?></a>
                        </div>
                    </div>


                    <!--step 2-->
                    <div id="tab2" class="tab-pane">
                        <table class="form table tableTools table-bordered  table-white">
                            <colgroup>
                                <col width="20%"/>
                                <col width="30%"/>
                                <col width="20%"/>
                                <col width="30%"/>
                            </colgroup>
                            <tr>
                                <td class="align_right padding-r20">
                                    <?php __('Choose Trunk') ?>
                                </td>
                                <td>
                                    <select name="data[Trunk][choose_trunk_type]" id="trunk_type" class="width220">
                                        <option value="0"><?php __('New Trunk') ?></option>
                                        <option value="1" <?php if(!empty($wizard_info)) echo 'selected="selected"'?>><?php __('Existing Trunk') ?></option>
                                    </select>
                                    <a href="javascript:void(0)" title="<?php __('add_routing_wizard_no_exist_trunk'); ?>">
                                        <i class="icon-question-sign"></i>
                                    </a>
                                </td>
                                <td class="align_right padding-r20">
                                    <?php __('Trunk Name') ?>
                                </td>
                                <td>
                                    <input id="input_trunk_name" type="text" name="data[Trunk][input_trunk_name]"
                                           class="width220 validate[required,custom[onlyLetterNumberLineSpace]]"/>
                                    <select id="select_trunk_id" name="data[Trunk][select_trunk_id]" style="display:none;"
                                            class="width220 validate[required]">
                                        <?php if(isset($wizard_info['ingress_list']) && !empty($wizard_info['ingress_list'])): foreach ($wizard_info['ingress_list'] as $item): ?>
                                            <option
                                                value="<?php echo $item[0]['resource_id'] ?>"><?php echo $item[0]['alias'] ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </td>
                            </tr>


                        </table>
                        <h4 class="center" style="margin: 20px 0 10px 0"><?php __('Host List') ?></h4>
                        <table class="form table  tableTools table-bordered  table-white">
                            <tbody id="ip_body">
                            <tr>
                                <td><?php __('IP'); ?></td>
                                <td><?php __('Port'); ?></td>
                                <td>
                                    <a title="<?php __('Add IP'); ?>" href="javascript:void(0)" id="add_ip_port">
                                        <i class="icon-plus"></i>
                                    </a>
                                        <a title="<?php __('Copy IP'); ?>" class="copy_ip_btn" href="#MyModalCopyIngressIP" data-toggle="modal">
                                        <i class="icon-copy"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php if(isset($wizard_info['ips']) && !empty($wizard_info['ips'])): foreach($wizard_info['ips'] as $item):?>
                                <tr class="clone">
                                    <td>
                                        <input type="text" name="ips[]" class="ip validate[required,custom[ipv4]]" value="<?php echo $item[0]['ip']?>"/>
                                    </td>
                                    <td>
                                        <input type="text" name="ports[]" class="port validate[required,custom[onlyNumber]]" value="<?php echo $item[0]['port']?>"
                                               maxlength="5"/>
                                    </td>
                                    <td>
                                        <a title="delete" href="javascript:void(0)" class="delete_ip_port">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; else:  ?>

                                <tr class="clone">
                                    <td>
                                        <input type="text" name="ips[]" class="ip validate[required,custom[ipv4]]"/>
                                    </td>
                                    <td>
                                        <input type="text" name="ports[]" class="port validate[required,custom[onlyNumber]]"
                                               maxlength="5"/>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="delete_ip_port">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endif;?>
                            </tbody>

                        </table>
                        <div class="center separator">
                            <a step="#step2" href="" data-toggle="tab" value="prev"
                               class="prev btn btn-primary"><?php __('Previous') ?></a>
                            <a value="next" data-toggle="tab" step="#step3" href=""
                               class="next input in-submit btn btn-primary"><?php __('Next') ?></a>
                        </div>
                    </div>


                    <!--step 3-->
                    <div id="tab3" class="tab-pane">
                        <table class="form table  tableTools table-bordered  table-white">
                            <col width="30%">
                            <col width="70%">
                            <tr>
                                <td class="align_right padding-r20">
                                    <?php __('Prefix') ?>
                                </td>
                                <td>
                                    <input type="text" name="data[Route][prefix]" id="Routeprefix" class="width220" value="<?php echo isset($wizard_info['tech_prefix']) ? $wizard_info['tech_prefix'] : ''?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r20"><?php __('Vendor') ?></td>
                                <td>

                                    <?php
                                    $selected = array();
                                    if(isset($wizard_info['vendors'])){
                                        foreach($wizard_info['vendors'] as $item){
                                            $selected[] = $item[0]['resource_id'];
                                        }
                                    }

                                    echo $form->select('Route.vendors', $egresses,$selected, array('id' => 'vendors', 'class' => 'multiselect validate[required]', 'type' => 'select', 'multiple' => true), false);
                                    //                                    echo $xform->input('Route.vendors', array('type' => 'select', 'multiple' => 'multiple', 'options' => $egresses, 'selected' => $wizard_info['vendors'], 'class' => "multiselect validate[required]", 'id' => 'vendors'))
                                    ?>
                                </td>
                            </tr>

                        </table>
                        <div class="center separator">
                            <a step="#step3" href="" data-toggle="tab" value="prev"
                               class="prev btn btn-primary"><?php __('Previous') ?></a>
                            <a value="next" data-toggle="tab" step="#step4" href=""
                               class="next input in-submit btn btn-primary"><?php __('Next') ?></a>
                        </div>
                    </div>


                    <!--step 4-->
                    <div id="tab4" class="tab-pane">
                        <table class="form table  tableTools table-bordered  table-white">
                            <col width="40%">
                            <col width="60%">
                            <tbody>
                            <tr>
                                <td class="align_right padding-r20">
                                    <?php __('Choose Rate Table'); ?>:
                                </td>
                                <td>
                                    <select name="choose_rate_table" class="width220">
                                        <option value="0"><?php __('New Rate Table') ?></option>
                                        <option value="1" <?php if(isset($wizard_info['rate_generation_template_id']) && !$wizard_info['rate_generation_template_id']): ?>selected<?php endif; ?> ><?php __('Existing Rate Table') ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="exist_rate_table_tr">
                                <td class="align_right padding-r20">
                                    <?php __('Rate Table'); ?>:
                                </td>
                                <td>
                                    <select name="exist_rate_table">
                                        <?php foreach ($rate_tables as $rate_table): ?>
                                            <option value="<?php echo $rate_table[0]['rate_table_id']; ?>" <?php if(isset($wizard_info['virtual_rate_table_id']) && $wizard_info['virtual_rate_table_id'] == $rate_table[0]['rate_table_id']) echo 'selected="selected"'?>><?php echo $rate_table[0]['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="new_rate_table_tr">
                                <td class="align_right padding-r20">
                                    <?php __('Code Deck'); ?>:
                                </td>
                                <td>
                                    <select name="code_deck">
                                        <option></option>
                                        <?php foreach ($code_decks as $code_deck): ?>
                                            <option value="<?php echo $code_deck[0]['code_deck_id']; ?>" <?php if(isset($wizard_info['code_deck_id']) && $wizard_info['code_deck_id'] == $code_deck[0]['code_deck_id']) echo 'selected="selected"'?>><?php echo $code_deck[0]['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="new_rate_table_tr">
                                <td class="align_right padding-r20">
                                    <?php __('Currency'); ?>:
                                </td>
                                <td>
                                    <select name="currency" value="<?php echo $wizard_info['currency_id']?>">
                                        <?php
                                        $tmp = isset($wizard_info['currency_id']) ? $wizard_info['currency_id'] : $default_currency;

                                        foreach ($currencies as $currency):
                                            ?>
                                            <option value="<?php echo $currency[0]['currency_id']; ?>" <?php if(!strcmp($tmp,$currency[0]['currency_id'])){ ?>selected="selected"<?php } ?>><?php echo $currency[0]['code']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="new_rate_table_tr">
                                <td class="align_right padding-r20">
                                    <?php __('Type'); ?>:
                                </td>
                                <td>
                                    <select name="type">
                                        <option value="0" <?php if(isset($wizard_info['rate_type']) && $wizard_info['rate_type'] == 0) echo 'selected="selected"'?>><?php __('DNIS')?></option>
                                        <option value="1" <?php if(isset($wizard_info['rate_type']) && $wizard_info['rate_type'] == 1) echo 'selected="selected"'?>><?php __('LRN')?></option>
                                        <option value="2" <?php if(isset($wizard_info['rate_type']) && $wizard_info['rate_type'] == 2) echo 'selected="selected"'?>><?php __('LRN BLOCK')?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="new_rate_table_tr">
                                <td class="align_right padding-r20">
                                    <?php __('Rate Type'); ?>:
                                </td>
                                <td>
                                    <?php
                                    $tmp = !empty($wizard_info) ? $wizard_info['jur_type'] : $default_us_ij_rule;
                                    echo $form->input('rate_type',array('type'=>'select','options'=> $rate_type_arr,'div'=>false,
                                        'label'=>false,'selected'=>$tmp,'name'=>'rate_type'));
                                    ?>
                                </td>
                            </tr>
                            <?php if(!empty($wizard_info) && $wizard_info['rate_generation_template_id'] != 0): ?>

                                <tr >
                                    <td class="align_right padding-r20">
                                        <?php __('Update Rate'); ?>:
                                    </td>
                                    <td>
                                        <input type="checkbox" id="is_update_rate" name="is_update_rate" value="1"/>
                                    </td>
                                </tr>
                            <?php endif;?>
                            <tr class="update_rate new_rate_table_tr">
                                <td class="align_right padding-r20"><?php __('Choose Rate Generation Template'); ?> </td>
                                <td>
                                    <select id="select_template_id" name="data[Rate][select_template_id]"
                                            class="width220 validate[required]">
                                        <?php foreach ($rate_generation_templates as $rate_template): ?>
                                            <option
                                                value="<?php echo $rate_template[0]['id'] ?>"><?php echo $rate_template[0]['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <!--                                    <a href="--><?php //echo $this->webroot?><!--rate_generation/add_rate_template" target="_blank" id="add_rate_template" title="Create New"><i class="icon-plus"></i></a>-->
                                    <a href="#myModal_addRateGeneration" data-toggle="modal" title="<?php __('create new'); ?>">
                                        <i class="icon-plus"></i>
                                    </a>
                                    <a id="refresh_rate_template" href="javascript:void(0)" title="Refresh"><i class="icon-refresh"></i></a>
                                </td>
                            </tr>
                            <?php if(!empty($wizard_info)): ?>
                                <tr class="update_rate new_rate_table_tr">
                                    <td class="align_right padding-r20"><?php __('Effective Date If New'); ?> </td>
                                    <td>
                                        <input class=" input in-text width220 validate[required] wdate" type="text"   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="new_effective_date">
                                    </td>
                                </tr>
                                <tr class="update_rate new_rate_table_tr">
                                    <td class="align_right padding-r20"><?php __('Effective Date If Increase'); ?> </td>
                                    <td>
                                        <input class=" input in-text width220 validate[required] wdate" type="text"   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="increase_effective_date">
                                    </td>
                                </tr>
                                <tr class="update_rate new_rate_table_tr">
                                    <td class="align_right padding-r20"><?php __('Effective Date If Decrease'); ?> </td>
                                    <td>
                                        <input class=" input in-text width220 validate[required] wdate" type="text"   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="decrease_effective_date">
                                    </td>
                                </tr>
                            <?php else:?>
                                <tr class="new_rate_table_tr">
                                    <td class="align_right padding-r20"><?php __('Effective Date'); ?> </td>
                                    <td>
                                        <input class=" input in-text width220 validate[required] wdate" type="text"   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="effective_date">
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if(!empty($wizard_info)): ?>
                                <tr class="update_rate new_rate_table_tr">
                                    <td class="align_right padding-r20"><?php __('End Date Method'); ?> </td>
                                    <td>
                                        <select name="end_date_method">
                                            <option value="1">Duplicated Codes Only</option>
                                            <option value="2">Code with Rate Changed Only</option>
                                            <option value="3">All Codes</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endif;?>
                            <tr class="update_rate new_rate_table_tr">
                                <td class="align_right padding-r20"><?php __('End Date'); ?> </td>
                                <td>
                                    <input class=" input in-text width220 wdate" type="text"   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="end_date">
                                </td>
                            </tr>
                            <tr class="update_rate new_rate_table_tr">
                                <td class="align_right padding-r20"><?php __('Send Email'); ?> </td>
                                <td>
                                    <input type="checkbox" id="is_send_mail" name="is_send_email" value="1"/>
                                </td>
                            </tr>
                            <tr class="update_rate new_rate_table_tr" id="email_template">
                                <td class="align_right padding-r20"><?php __('Email Template'); ?> </td>
                                <td>
                                    <select  name="email_template_id" >
                                        <?php foreach ($rate_email_template as $rate_email_template_id => $rate_email_template_name): ?>
                                            <option value="<?php echo $rate_email_template_id ?>"><?php echo $rate_email_template_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>


                            </tbody>
                        </table>



                        <div class="center separator">
                            <a step="#step4" href="" data-toggle="tab" value="prev"
                               class="prev btn btn-primary"><?php __('Previous'); ?></a>
                            <!--                            <a value="next" id="submit"  href=""  class="input in-submit btn btn-primary">-->
                            <?php //__('Submit') ?><!--</a>-->
                            <input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary"/>
                        </div>
                    </div>


            </form>

        </div>

    </div>
</div>
<input type="hidden" name="AlertRules[id]" value="1"/>
<input type="hidden" id="step_" value="1"/>
<div id="myModal_addRateGeneration" class="modal hide" style="width:auto">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Add Rate Generation Template'); ?></h3>
    </div>
    <div class="separator"></div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>

<div id="MyModalCopyIngressIP" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Copy From Ingress IP'); ?></h3>
    </div>
    <div class="modal-body">
        <table class="form table table-condensed">
            <tr>
                <td class="align_right"><?php __('Ingress Trunk'); ?></td>
                <td><?php echo $form->input('ingress_trunk',array('type' => 'select','options' => $ingresses,'class'=>'ingress_trunk',
                        'div' => false,'label' => false)); ?></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>
<script type="text/javascript">
    $(function(){

        $("#MyModalCopyIngressIP").find('.sub').click(function(){
            var $this = $(this);
            var selected_trunk = $("#MyModalCopyIngressIP").find('.ingress_trunk').val();
            $.ajax({
                'url': '<?php echo $this->webroot; ?>routestrategys/get_ingress_ips/'+selected_trunk,
                'type': 'GET',
                'dataType': 'json',
                'success': function(data) {
                    if(data == ''){
                        return;
                    }
                    if ($('.clone:first').find('.ip').val() == '' && $('.clone:first').find('.port').val() == '') {
                        $('.clone:first').find('.ip').val(data[0][0]['ip']);
                        $('.clone:first').find('.port').val(data[0][0]['port']);
                    } else {
                        $.each(data, function(index, item) {
                            $('.clone:first').clone(true).appendTo('#ip_body');
                            $('.clone:last').find('.ip').val(item[0]['ip']);
                            $('.clone:last').find('.port').val(item[0]['port']);
                        });    
                    }
                    
                    $this.next().click();
                }
            });
        });


        $("#myModal_addRateGeneration").on('shown',function(){
            $(this).find('.modal-body').load("<?php echo $this->webroot; ?>rate_generation/add_rate_template?is_ajax=1");
        });

        $("#myModal_addRateGeneration").find('.sub').click(function(){
            var $this = $(this);
            var is_validate = $("#myform").validationEngine('validate');
            if ( !is_validate ){
                return false;
            }

            $.ajax({
                url: "<?php echo $this->webroot ?>rate_generation/add_rate_template?is_ajax=1",
                type: 'post',
                dataType: 'text',
                data: $('#myform').serialize(),
                success: function(data) {
                    if (data != 0)
                    {
                        $this.next().click();
                        refresh_rate_generation_template(data);
                        jGrowl_to_notyfy('<?php __('Create success'); ?>', {theme: 'jmsg-success'});
                    }
                    else
                        jGrowl_to_notyfy('<?php __('Create failed'); ?>', {theme: 'jmsg-error'});
                }
            });
        });
    });
</script>

<script type="text/javascript">
    function credit_unlimited_change(obj)
    {
        var checked = $(obj).is(":checked");
        if(checked){
            $("#allowed_credit").hide();
            $(".money").hide();
        }else{
            $("#allowed_credit").show();
            $(".money").show();
        }
    }

    function refresh_ingress_trunk() {
        var client_id = $('#select_client_id').val();
        $.ajax({
            'url': '<?php echo $this->webroot; ?>routestrategys/get_ingress/'+client_id,
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $('#select_trunk_id').empty();
                $("#MyModalCopyIngressIP").find('.ingress_trunk').empty();
                $.each(data, function(index, item) {
                    $('#select_trunk_id').append('<option value="' + item[0]['resource_id'] + '">' + item[0]['alias'] + '</option>');
                    $("#MyModalCopyIngressIP").find('.ingress_trunk').append('<option value="' + item[0]['resource_id'] + '">' + item[0]['alias'] + '</option>');
                });

                if ( $("#select_trunk_id").find('option').size() == 0 ){
                    $('#trunk_type').val(0);
                    $('#trunk_type').find('option').eq(1).attr('disabled',true).css({ "background": "#A7A7A7" });
                    $(".copy_ip_btn").hide();
                }else{
                    $('#trunk_type').find('option').eq(1).attr('disabled',false).css({ "background": "" });
                    $(".copy_ip_btn").show();
                }
                $('#trunk_type').change();
            }
        });
    }

    function refresh_trunk_ips() {
        var trunk_id = $('#select_trunk_id').val();
        if(trunk_id == null || trunk_id == '') {

            return;
        }

        $.ajax({
            'url': '<?php echo $this->webroot; ?>routestrategys/get_ingress_ips/'+trunk_id,
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $('.clone:not(:first)').remove();
                if(data == ''){
                    $('.clone').find('.ip').val('');
                    $('.clone').find('.port').val('');
                    return;
                }

                $.each(data, function(index, item) {
                    $('.clone:first').clone(true).appendTo('#ip_body');
                    $('.clone:last').find('.ip').val(item[0]['ip']);
                    $('.clone:last').find('.port').val(item[0]['port']);
                });
                $('.clone:first').remove();
            }
        });
    }

    function refresh_rate_generation_template( default_selected ){
        $.ajax({
            'url': '<?php echo $this->webroot; ?>routestrategys/get_rate_templates/',
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $('#select_template_id').empty();
                $.each(data, function(index, item) {
                    if ( item[0]['id'] == default_selected ){
                        $('#select_template_id').append('<option selected value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                    }else{
                        $('#select_template_id').append('<option value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                    }

                });
            }
        });
    }

    function choose_rate_table(){
        var choose_type = $("select[name=choose_rate_table]").val();
        console.log(choose_type);
        if ( choose_type == '0' ){
            console.log('show old');
            $('.new_rate_table_tr').show();
            $('.exist_rate_table_tr').hide();
        }else{
            console.log('show new');
            $('.new_rate_table_tr').hide();
            $('.exist_rate_table_tr').show();
        }
    }

    $(function () {
        choose_rate_table();
        $('.widget-head li a:gt(1)').prop('disabled',true);
        $('.widget-head li a:gt(1)').css('cursor','not-allowed');
        $('#wizard_form').submit(function(){
            $(this).validationEngine('validate');
        });

        $("select[name=choose_rate_table]").change(function(){
            choose_rate_table();
        });


        $('.widget-head li a').on('click',function(){



            var a_active = $('.widget-head li.active a').data('value');

            if($(this).index('.widget-head li a') < a_active - 1){
                return true;
            }
            var flag = false;
            $("#tab"+a_active+ " [class *= 'validate']").each(function(){
                if ($(this).validationEngine('validate'))
                {

                    flag = true;
                    return false;
                }
            });
            if(flag) return false;

            var now = $(this).data('value');
            $('.widget-head li a:eq('+now+')').prop('disabled',false);
            $('.widget-head li a:eq('+now+')').css('cursor','pointer');
        });

        $('.next').click(function () {

            var a_active = $('.widget-head li.active a').data('value');


            var next = a_active + 1;
            $('#step' + next).click();
        });

        $('.prev').click(function () {
            var prev = $('.widget-head li.active a').data('value') - 1;
            $('#step' + prev).click();
        });

        <?php if(empty($wizard_info)): ?>
        //client
        $('#client_type').change(function () {
            var val = $(this).val();
            if (val == 0) {
                $('#input_client_name').show();
                $('#select_client_id').hide();
                $('.create_carrier').show();
                $('#trunk_type').val('0');
                $('#trunk_type').change();
                $(".copy_ip_btn").hide();
            } else {
                $('#input_client_name').hide();
                $('#select_client_id').show();
                $('.create_carrier').hide();
                $('#select_client_id').change();
                $(".copy_ip_btn").show();
            }
        }).trigger('change');

        $('#select_client_id').change(function(){
            refresh_ingress_trunk();
        });



        $("#mode").change(function(){
            var mode_type = $(this).val();
            if(mode_type == 2){
                $("#credit_type_flg").html('<?php __('allowedcredit') ?>:');
                $("#unlimited_panel").show();
            }else{
                $("#credit_type_flg").html('<?php __('Test Credit') ?>:');
                $("#unlimited_panel").hide();
            }
        }).trigger('change');

        $("#unlimited_credit").click(function(){
            credit_unlimited_change($(this));
        });


        //trunk
        $('#trunk_type').change(function () {
            var val = $(this).val();
            if (val == 0) {
                $('#input_trunk_name').show();
                $('#select_trunk_id').hide();
                $('.clone:not(:first)').remove();
                $('.clone').find('.ip').val('');
                $('.clone').find('.port').val('');
            } else {
                $('#input_trunk_name').hide();
                $('#select_trunk_id').show();
                $('#select_trunk_id').change();
            }
        });
        <?php endif;?>

        $('#select_trunk_id').change(function(){
            refresh_trunk_ips();
        });

        $('#add_ip_port').click(function(){
            $('.clone:first').clone(true).appendTo('#ip_body');
        });
        $('.delete_ip_port').live('click', function() {
            if ($('#ip_body').find('tr').size() > 2) {
                $(this).parent().parent().remove();
            }
        });

        //route
        $('#vendors').multiSelect({
            selectableHeader: "<div class='custom-header'>Optional Selection</div>",
            selectionHeader: "<div class='custom-header'>Selected Selection</div>"
        });

        $('#step4').on('click',function(){
            var left = $('.ms-selection').offset().left;
            $.sleep(500);
            $(".vendorsformError").css('left',left+'px');
        });

        //rate
        $('#refresh_rate_template').click(function(){
            refresh_rate_generation_template(0);
        });

        $('#email_template').hide();
        $('#is_send_mail').click(function(){
            if($(this).is(":checked")){

                $('#email_template').show();
            } else {
                $('#email_template').hide();
            }

        });

        <?php if(!empty($wizard_info)): ?>
        //edit
        $('#input_client_name').hide();
        $('#select_client_id').show().prop('disabled',true);
        $('.create_carrier').hide();
        $('#client_type').prop('disabled',true);
        $('#select_client_id').find('option[value="<?php echo $wizard_info['client_id']?>"]').prop('selected',true);

        $('#input_trunk_name').hide();
        $('#trunk_type').prop('disabled',true);
        $('#select_trunk_id').show().prop('disabled',true);
        $('#select_trunk_id').find('option[value="<?php echo $wizard_info['resource_id']?>"]').prop('selected',true);

        $('.update_rate').hide();
        $('#is_update_rate').click(function(){
            if($(this).is(":checked")){

                $('.update_rate').show();
                if($('#is_send_mail').is(":checked")){

                    $('#email_template').show();
                } else {
                    $('#email_template').hide();
                }
            } else {
                $('.update_rate').hide();
            }

        });

        <?php endif;?>



    })

</script>