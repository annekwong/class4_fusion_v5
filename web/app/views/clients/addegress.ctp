<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Carrier') ?> [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add Egress Trunk') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Add Egress Trunk') ?></h4>
    <div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?<?php echo $$hel->getParams('getUrl') ?>"><i></i> <?php __('Back')?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <?php echo $form->create('Client', array('id' => 'myform', 'action' => 'addegress/' . $this->params['pass'][0])); ?> <?php echo $form->input('ingress', array('label' => false, 'value' => 'false', 'div' => false, 'type' => 'hidden')); ?> <?php echo $form->input('egress', array('label' => false, 'value' => 'true', 'div' => false, 'type' => 'hidden')); ?>
            <input type="hidden" name="is_finished" id="is_finished" value="0" />
            <table class="table dynamicTable tableTools table-bordered  table-white form">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tr>
                    <td class="align_right">
                        <?php echo __('Egress Name', true); ?>
                        <p class="muted"></p>
                    </td>
                    <td>
                        <?php echo $form->input('alias', array('id' => 'alias', 'label' => false, 'class'=>'validate[required] width220','div' => false, 'type' => 'text', 'maxlength' => '256')); ?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right">
                        <?php __('Rate Table') ?>
                        <p class="muted"></p>
                    </td>
                    <td>
                        <?php echo $form->input('rate_table_id', array('options' => $rate,'empty' => '  ', 'label' => false,
                            'class' => 'select', 'div' => false, 'type' => 'select')); ?>
                        <a>
                            <i id="addratetable" style="cursor:pointer;" class="icon-plus" onclick="showDiv('pop-div', '800', '300', '<?php echo $this->webroot ?>clients/addratetable','<?php __('create_%s',false,array(__('rate_table',true))); ?>');" ></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="align_right">
                        <?php __('Authorized'); ?>
                        <p class="muted"></p>
                    </td>
                    <td>
                        <select name='reg_type' id='host_authorize' onchange='check_host();'>
                            <option value="0"><?php __('Authorized by IP Only') ?></option>
                            <?php if (array_keys_value($hosts[0], '0.reg_type') == 1): ?>
                                <option selected value="1"><?php __('Authorized by SIP Registration') ?> </option>
                            <?php else: ?>
                                <option value="1"><?php __('Authorized by SIP Registration') ?> </option>
                            <?php endif; ?>

                            <?php if (array_keys_value($hosts[0], '0.reg_type') == 2): ?>
                                <option selected value="2"><?php __('Register to gateway') ?> </option>
                            <?php else: ?>
                                <option value="2"><?php __('Register to gateway') ?> </option>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <!--
            <div class="row-fluid">
                <div class="span3">
                    <strong><?php echo __('Egress Name', true); ?></strong>
                    <p class="muted"></p>
                </div>
                <div class="span9">
                    <?php echo $form->input('alias', array('id' => 'alias', 'label' => false, 'class'=>'validate[required]','div' => false, 'type' => 'text', 'maxlength' => '256')); ?>
                    <div class="separator"></div>
                </div>
                <div class="span3">
                    <strong><?php __('Rate Table') ?>:</strong>
                    <p class="muted"></p>
                </div>
                <div class="span9">
                    <?php echo $form->input('rate_table_id', array('options' => $rate,'empty' => '  ', 'label' => false,
                        'class' => 'select', 'div' => false, 'type' => 'select')); ?>
                    <a>
                        <i id="addratetable" style="cursor:pointer;" class="icon-plus" onclick="showDiv('pop-div', '700', '300', '<?php echo $this->webroot ?>clients/addratetable');" ></i>
                    </a>
                    <div class="separator"></div>
                </div>

            </div>
            -->
            <?php echo $this->element("gatewaygroups/host_edit", ['ips'=> $ips]) ?>
            <?php if ($$hel->_get('viewtype') == 'wizard') { ?>
                <div id="form_footer">
                    <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceEgressForm').attr('action','?nextType=egress&<?php echo $$hel->getParams('getUrl') ?>')" value="<?php echo __('Next Egress') ?>" style="width:80px" />
                    <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceEgressForm').attr('action','?nextType=ingress&<?php echo $$hel->getParams('getUrl') ?>')" value="<?php echo __('Next Ingress') ?>" style="width:80px"/>
                    <input type="button"  value="<?php echo __('End') ?>" class="input in-submit" onclick="location='<?php echo $this->webroot ?>clients/index?filter_id=<?php echo $$hel->_get('query.id_clients') ?>'"/>
                </div>
            <?php } else { ?>
                <div id="form_footer" class="buttons center">
                    <input type="submit" class="btn btn-primary" id="submit_form" style="width:auto;" value="<?php echo __('Add Egress Trunk') ?>" class="input in-submit"/>
                    <input type="button" class="btn btn-primary" id="ingress" style="width:140px;" value="<?php echo __('Add Ingress Trunk', true); ?>" />
                    <!--    <input type="reset"  value="<?php echo __('reset') ?>"  class="input in-submit"/>-->
                    <input type="button" class="btn btn-primary" id="back" value="<?php echo __('Finish') ?>" />
                </div>
                <div class="clearfix"></div>
            <?php } ?>
            <?php echo $form->end(); ?>


        </div>

    </div>
</div>

<!-----------Add Rate Table----------->
<div id="pop-div" class="pop-div" style="display:none;">
    <div class="pop-thead">
        <span></span>
        <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
    </div>
    <div class="pop-content" id="pop-content"></div>
</div>
<div id="pop-clarity" class="pop-clarity" style="display:none;"></div>


</div>



<!--<script type="text/javascript" src="--><?php //echo $this->webroot ?><!--js/gateway.js"></script>-->
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.base64.min.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.livequery.js"></script>
<script type="text/javascript">
    function test3(id) {
        $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
            $.each(data, function(idx, item) {
                var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                if (item['id'] == id) {
                    option.attr('selected', 'selected');
                }
                var $ratetable = $('#ClientRateTableId');
                $ratetable.append(option);
            });
        })
    }
    jQuery(document).ready(
        function(){
            $("#addHost").click();
            jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});
            jQuery('#alias').xkeyvalidate({type:'strNum'});
            jQuery("form[id^=ClientAddegress]").submit(function(){
                var pa="/[^0-9A-Za-z-\_\s]+/";
                var re =true;
                if(jQuery('#alias').val()==''){
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('The field Egress Name cannot be NULL.');
                    return false;

                }else if(/[^0-9A-Za-z-\_\s]/.test(jQuery("#alias").val())){
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('Egress Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 256 characters in length!');
                    return false;

                }

                if(jQuery('#totalCall').val()!=''){
                    if(/\D/.test(jQuery('#totalCall'.val()))){
                        jQuery(this).addClass('invalid');
                        jQuery(this).jGrowlError('Call limit, must be whole number! ');
                        return false;
                    }
                }

                if(jQuery('#totalCPS').val()!=''){
                    if(/\D/.test(jQuery('#totalCPS').val())){
                        jQuery(this).addClass('invalid');
                        jQuery(this).jGrowlError('CPS Limit, must be whole number!');
                        return false;
                    }

                }
                if(jQuery('#ip:visible').val()!=''||!jQuery('#ip:visible').val()){

                    if(!/^([\w-]+\.)+((com)|(net)|(org)|(gov\.cn)|(info)|(cc)|(com\.cn)|(net\.cn)|(org\.cn)|(name)|(biz)|(tv)|(cn)|(mobi)|(name)|(sh)|(ac)|(io)|(tw)|(com\.tw)|(hk)|(com\.hk)|(ws)|(travel)|(us)|(tm)|(la)|(me\.uk)|(org\.uk)|(ltd\.uk)|(plc\.uk)|(in)|(eu)|(it)|(jp))$/.test(ip))
                    {

                    }
                    if(!/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])){3}$/.test(jQuery('#ip:visible').val())||

                        !/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(jQuery('#ip:visible').val())

                    ){

                        jQuery(this).addClass('invalid');
                        jQuery(this).jGrowlError('IPs/FQDN must be a valid format ');
                        return false;

                    }
                    if(jQuery('#port:visible').val()!=''||!jQuery('#port:visible').val()){
                        if(/\D/.test(jQuery('#port:visible').val())){
                            jQuery(this).addClass('invalid');
                            //	jQuery(this).jGrowlError('Port,must be whole number!');
                            //		re = false;
                        }


                    }

                }


                return re;

            });

        }

    );

    jQuery(function($){
        $('#ingress').click(function() {
            window.location.href = "<?php echo $this->webroot ?>clients/addingress/<?php echo $client_id ?>/<?php echo isset($registration_id) ? $registration_id : '' ?>";
        });
        $('#addratetable').live('click', function() {
            $(this).prev().addClass('clicked');
            //window.open('<?php echo $this->webroot ?>clients/addratetable', 'addratetable',       'height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
        });
    });

    function test2(id) {
        $('#ClientRateTableId').livequery(function() {
            var $ratetable = $(this);
            $ratetable.empty();
            $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
                $.each(data, function(idx, item) {
                    var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                    if($ratetable.hasClass('clicked')) {
                        if(item['id'] == id) {
                            option.attr('selected','selected');
                        }
                    }
                    $ratetable.append(option);
                });
                $ratetable.removeClass('clicked');
            })
        });
    }
    function test3(id) {
        var $ratetable = $("#ClientRateTableId");
        $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
            $.each(data, function(idx, item) {
                var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                if(item['id'] == id) {
                    option.attr('selected','selected');
                }
                $ratetable.append(option);
            });
        })
    }
</script>
<script type="text/javascript">
    $(function() {
        $('#back').click(function() {
            $('#is_finished').val('1');
            $('#myform').submit();
        });
    });
</script>