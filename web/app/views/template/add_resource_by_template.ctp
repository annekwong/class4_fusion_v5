<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo $this->pageTitle; ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="<?php echo $this->webroot.$back_url ?>" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left"><i></i><?php echo __('goback', true); ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form action="" method="post">
                <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                    <colgroup><col width="37%"><col width="63%">

                    </colgroup>
                    <tr>
                        <td class="align_right padding-r10"><?php echo $set_template_name; ?></td>
                        <td>
                            <?php echo $form->input('resource_template_id', array('label' => false, 'div' => false, 'type' => 'select',
                                'options' => $templates,'class' =>'validate[required]')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php echo $set_name ?></td>
                        <td>
                            <?php echo $form->input('alias', array('class' => 'width220 validate[required,custom[onlyLetterNumberLineSpace]]', 'id' => 'alias', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '100')); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="align_right padding-r10"><?php echo __('client') ?></td>
                        <td>
                            <?php
                            if(!empty($carrier_id)){
                                echo $form->input('client_id', array('label' => false,
                                    'class' => 'width220', 'div' => false, 'type' => 'text', 'value' => $clients[$carrier_id], 'readonly' => 'readonly'));
                                echo $form->input('client_id', array('label' => false, 'div' => false, 'type' => 'hidden', 'value' => $carrier_id));
                            } else {
                                echo $form->input('client_id', array('options' => $clients, 'empty' => '', 'label' => false,
                                    'class' => 'select validate[required]', 'div' => false, 'type' => 'select'));
                            }

                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('calllimit') ?></td>
                        <td>
                            <?php echo $form->input('capacity', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'totalCall', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '8')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('cps') ?></td>
                        <td>
                            <?php echo $form->input('cps_limit', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'totalCPS', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '8')); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="align_right padding-r10"><?php __('active') ?></td>
                        <td>
                            <?php
                            echo $form->input('active', array('options' => array('true' => __('True',true), 'false' => __('False',true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="align_right padding-r10"><?php __('Authorized'); ?></td>
                        <td>
                            <select name='reg_type' id='host_authorize' onchange='check_host();'>
                                <option value="0"><?php __('Authorized by IP Only')?></option>
                                <option value="1"><?php __('Authorized by SIP Registration')?> </option>
                                <option value="2"><?php __('Register to gateway') ?> </option>
                            </select>
                        </td>
                    </tr>
                </table>

                <?php echo $this->element("gatewaygroups/host_edit") ?>
                <?php if(!isset($is_egress)): ?>
                    <?php echo $this->element("gatewaygroups/resource_prefix") ?>
                <?php endif; ?>
                <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']):?>
                    <div id="form_footer" class="center separator">
                        <input type="submit" id="submit_form" value="<?php echo __('submit') ?>" class="input in-submit btn btn-primary"/>
                        <input type="reset"  value="<?php echo __('Revert') ?>"  class="input in-submit btn btn-default"/>
                    </div>
                <?php endif; ?>
            </form>


        </div>



    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(
        function() {
            $("#client_id").change(function() {
                var client_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->webroot; ?>clients/ajax_get_limit",
                    dataType: 'json',
                    data: "id=" + client_id,
                    success: function(msg) {
                        var call_limit = msg.call_limit;
                        var cps_limit = msg.cps_limit;
                        if(cps_limit){
                            $("#totalCPS").attr({ placeholder: 'MAX:'+cps_limit, class: "width220 validate[max[" + cps_limit + "],custom[onlyNumberSp]]" });
                        } else {
                            $("#totalCPS").attr({placeholder:'',class:'width220 validate[custom[onlyNumberSp]]'});
                        }
                        if(call_limit) {
                            $("#totalCall").attr({ placeholder: 'MAX:'+call_limit, class: "width220 validate[max[" + call_limit + "],custom[onlyNumberSp]]" });
                        } else {
                            $("#totalCall").attr({placeholder:'',class:'width220 validate[custom[onlyNumberSp]]'});
                        }
                    }
                });
            });

        });


</script>
