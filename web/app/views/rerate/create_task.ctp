<style type="text/css">
    .ms-container ul.ms-list{
        width: 280px;
    }
    .ms-container{
        background: transparent url('<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/img/switch.png') no-repeat 290px 80px;
    }
    input.pointer_input{
        cursor: pointer;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rerate/create_task">
        <?php __('Tool') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rerate/create_task">
        <?php echo __('Re-Rate'); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Re-Rate'); ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('re_rate/tabs',array('active' => 'task')); ?>
        </div>
        <div class="widget-body">
            <form method="post" id="task_form">
                <div id="templates">
                    <div class="widget" >
                        <div class="widget-head"><h4 class="heading"><?php __('Re-rate Scope') ?></h4></div>
                        <div class="widget-body">
                            <table class="form table dynamicTable tableTools table-bordered  table-white">
                                <colgroup>
                                    <col width="30%">
                                    <col width="70%">
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td class="align_right"><?php __('time'); ?>:</td>
                                    <td>
                                        <?php __('From'); ?>
                                        <input class="input width140 validate[required] pointer_input" id="createTaskStart"  type="text" readonly name="data[from_time]"
                                               onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en',maxDate:'#F{$dp.$D(\'createTaskEnd\')}'})">
                                        <?php __('To'); ?>
                                        <input class="input width140 validate[required] pointer_input" id="createTaskEnd" style="cursor: pointer;" type="text" readonly name="data[to_time]"
                                               onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en',minDate:'#F{$dp.$D(\'createTaskStart\')}',maxDate:'<?php echo date('Y-m-d H:59:59',strtotime("-3 hours")); ?>'})">
                                        <?php __('GMT'); ?>
                                        <?php echo $form->input('timezone',array('type' => 'select','selected' => '+00','class' => 'width120',
                                            'options' => $appCommon->get_timezone_arr(true),'label' => false,'div' => false)); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right"><?php __('Update US Jurisdiction') ?>: </td>
                                    <td>
                                        <select name="data[update_us_jurisdiction]">
                                            <option value="0"><?php __('No'); ?></option>
                                            <option value="1"><?php __('Yes'); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right"><?php __('Re-rate Trunk Type') ?>: </td>
                                    <td>
                                        <select class="re_rate_trunk_type" name="re_rate_trunk_type">
                                            <option value="0"><?php __('Ingress'); ?></option>
                                            <option value="1"><?php __('Egress'); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right"><?php __('Status') ?>: </td>
                                    <td>
                                        <select class="re_rate_trunk_status" >
                                            <option value="1"><?php __('Active only'); ?></option>
                                            <option value=""><?php __('All'); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="ingress_multiselect trunk_dropdown">
                                    <td class="right"><?php __('Ingress'); ?>:</td>
                                    <td>
                                        <select multiple id="pre-selected-options" class="multiselect" name="ingress_trunks[]">
                                            <?php foreach($ingress_trunks as $client_name=>$ingress_info): ?>
                                                <optgroup label="<?php echo $client_name; ?>">
                                                    <?php foreach($ingress_info as $ingress_trunk_id => $ingress_trunk): ?>
                                                        <option value="<?php echo $ingress_trunk_id; ?>" <?php echo isset($ingress_trunks_active[$client_name][$ingress_trunk_id]) ? 'class="active"' : ''; ?> ><?php echo $ingress_trunk; ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="egress_multiselect trunk_dropdown">
                                    <td class="right">
                                        <?php __('Egress'); ?>:
                                    </td>
                                    <td>
                                        <select multiple id="pre-selected-options1" class="multiselect" name="egress_trunks[]">
                                            <?php foreach($egress_trunks as $client_name=>$egress_info): ?>
                                                <optgroup label="<?php echo $client_name; ?>">
                                                    <?php foreach($egress_info as $egress_trunk_id => $egress_trunk): ?>
                                                        <option value="<?php echo $egress_trunk_id; ?>" <?php echo isset($egress_trunks_active[$client_name][$egress_trunk_id]) ? 'class="active"' : ''; ?>><?php echo $egress_trunk; ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="ingress_multiselect_active trunk_dropdown">
                                    <td class="right"><?php __('Ingress'); ?>:</td>
                                    <td>
                                        <select multiple id="pre-selected-options2" class="multiselect" name="ingress_trunks[]">
                                            <?php foreach($ingress_trunks_active as $client_name=>$ingress_info): ?>
                                                <optgroup label="<?php echo $client_name; ?>">
                                                    <?php foreach($ingress_info as $ingress_trunk_id => $ingress_trunk): ?>
                                                        <option value="<?php echo $ingress_trunk_id; ?>" <?php echo isset($ingress_trunks_active[$client_name][$ingress_trunk_id]) ? 'class="active"' : ''; ?> ><?php echo $ingress_trunk; ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="egress_multiselect_active trunk_dropdown">
                                    <td class="right">
                                        <?php __('Egress'); ?>:
                                    </td>
                                    <td>
                                        <select multiple id="pre-selected-options3" class="multiselect" name="egress_trunks[]">
                                            <?php foreach($egress_trunks_active as $client_name=>$egress_info): ?>
                                                <optgroup label="<?php echo $client_name; ?>">
                                                    <?php foreach($egress_info as $egress_trunk_id => $egress_trunk): ?>
                                                        <option value="<?php echo $egress_trunk_id; ?>" <?php echo isset($egress_trunks_active[$client_name][$egress_trunk_id]) ? 'class="active"' : ''; ?>><?php echo $egress_trunk; ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="widget">
                        <div class="widget-head"><h4 class="heading"><?php __('Re-rate Method') ?></h4></div>
                        <div class="widget-body">
                            <table class="form table table-primary tableTools table-bordered  table-white">
                                <colgroup>
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="40%">
                                    <col width="20%">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th><?php __('Trunk'); ?></th>
                                    <th><?php __('Rate Table'); ?></th>
                                    <th><?php __('Set Effective Date to'); ?></th>
                                    <th><?php __('Action'); ?></th>
                                </tr>
                                </thead>
                                <tbody id="trunk_table">
                                <tr class="trunk_table_tr">
                                    <td><input type="hidden" class="tr_trunk_id" name="trunk_data[trunk_id][]" /></td>
                                    <td>
                                        <select name="trunk_data[rate_table][]" >
                                            <?php foreach ($rate_tables as $rate_table_id =>$rate_table): ?>
                                                <option value="<?php echo $rate_table_id ?>"><?php echo $rate_table ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="effective_date_type"  >
                                            <option value="1"><?php __('A specified Date'); ?></option>
                                            <option value="9"><?php __('Same as CDR Time'); ?></option>
                                        </select>
                                        <input class="input width120 validate[required] pointer_input" type="text" readonly
                                               name="trunk_data[rate_effective_date][]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})">
                                    </td>
                                    <td>
                                        <a title="Remove" class="remove" href="javascript:void(0)" style="margin-left: 20px;">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="center separator">
                    <input class="input in-submit btn btn-primary" value="<?php echo __('submit') ?>" type="submit">
                    <input type="reset" value="Revert"  class="btn btn-inverse" />
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">


    function trunk_type_show( reload )
    {
        if ( reload == false ){
            $('.ms-selected').click();
            $('#trunk_table').html('');
        }
        var trunk_type = $(".re_rate_trunk_type").val();
        var active = $(".re_rate_trunk_status") ? "_active" : '';
        $(".trunk_dropdown").hide();
        if ( trunk_type == '1' ){
            $(".egress_multiselect" + active).show();
        }else{
            $(".ingress_multiselect" + active).show();
        }

    }
    $(function(){

        $('.re_rate_trunk_status').on('change', function(){
            var trunk_type = $(".re_rate_trunk_type").val() == 1 ? 'egress' : 'ingress';
            $(".trunk_dropdown").hide();
            if($(this).val()){
               $("." + trunk_type +  "_multiselect_active").show();
            }else{
               $("." + trunk_type +  "_multiselect").show();
            }
        })

        $('#pre-selected-options').multiSelect();
        $('#pre-selected-options1').multiSelect();
        $('#pre-selected-options2').multiSelect();
        $('#pre-selected-options3').multiSelect();

        $("input[type=reset]").click(function(){
            setTimeout(function(){
                $('#pre-selected-options').multiSelect('refresh');
                $('#pre-selected-options1').multiSelect('refresh');
                $('#pre-selected-options2').multiSelect('refresh');
                $('#pre-selected-options3').multiSelect('refresh');
            });
        });

        var trunk_table_tr = jQuery('.trunk_table_tr').eq(0).remove();
        jQuery('a[id=add_margin]').click(function() {
            trunk_table_tr.clone(true).prependTo('#trunk_table');
        });

        trunk_type_show( true );
        $(".re_rate_trunk_type").change(function(){
            trunk_type_show( false );
        });

        $(".ms-elem-selectable").click(function(){
            var trunk_id = $(this).attr('id').substring(0,$(this).attr('id').indexOf('-selectable'));
            var trunk_name = $(this).find('span').html();
//            console.log("selected is " + trunk_id);
            var this_trunk_tr = trunk_table_tr.clone(true);
            this_trunk_tr.attr('id',trunk_id+'-table_tr').find('td').eq(0).append(trunk_name).find('.tr_trunk_id').val(trunk_id);
            this_trunk_tr.find('.remove').attr('onclick','$("#'+trunk_id+'-selection").click()');
            this_trunk_tr.prependTo('#trunk_table');
        });
        $('.ms-elem-selection').click(function(){
            var trunk_id = $(this).attr('id').substring(0,$(this).attr('id').indexOf('-selection'));
//            console.log("remove selected is " + trunk_id);
            $("#"+trunk_id+"-table_tr").remove();
        });
        $(".update_ingress_rate").change(function(){
            var $this = $(this);
            if($this.val() == 1) {
                $this.parent().prev().attr('rowspan',3);
                $(".ingress_rate_option").show();
            }
            else {
                $this.parent().prev().attr('rowspan',1);
                $(".ingress_rate_option").hide();
            }
        });

        $(".update_egress_rate").change(function(){
            var $this = $(this);
            if($this.val() == 1) {
                $this.parent().prev().attr('rowspan',3);
                $(".egress_rate_option").show();
            }
            else {
                $this.parent().prev().attr('rowspan',1);
                $(".egress_rate_option").hide();
            }
        });

        $(".effective_date_type").live('change',function(){
            var $this = $(this);
            if($this.val() == 1)
                $this.parent().find('.pointer_input').show();
            else
                $this.parent().find('.pointer_input').hide();
        });

        $("#task_form").submit(function(){
            if ( $('#trunk_table').find('tr').size() == 0 ){
                jGrowl_to_notyfy('<?php __('Please select at least one trunk'); ?>',{'theme':'jmsg-error'});
                return false;
            }
            return true;
        });
    })
</script>