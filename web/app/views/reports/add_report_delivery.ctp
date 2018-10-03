<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Usage Report Delivery') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('ADD Usage Report Delivery') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-default btn-icon glyphicons left_arrow btn-inverse" href="<?php echo $this->webroot; ?>reports/report_delivery">
            <i></i>
            <?php __('Back') ?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <form method="post">
            <table class="form table dynamicTable tableTools table-bordered  table-white">

                <tbody>
                    <tr>
                        <th class="right">
                            :
                        </th>
                        <td colspan="3">
                            <select name="type" id="select_type">
                                <option value="1" selected="selected"><?php __('Daily') ?></option>
                                <option value="2"><?php __('Weekly') ?></option>
                                <option value="3"><?php __('Monthly') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="right">
                            <?php __('Frequency') ?>:
                        </th>
                        <td colspan="3">

                            <span id="monthly" style="display:none;">
                                <select name="month" style="width: 100px;">
                                    <?php for ($i = 1; $i <= 31; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </span>
                            <span id="weekly" style="display:none;">
                                <select name="week" style="width: 100px;">
                                    <option value="M"><?php __('M')?></option>
                                    <option value="T">T</option>
                                    <option value="W"><?php __('W')?></option>
                                    <option value="Th"><?php __('Th')?></option>
                                    <option value="F"><?php __('F')?></option>
                                    <option value="Sa"><?php __('Sa')?></option>
                                    <option value="Su"><?php __('Su')?></option>
                                </select>
                            </span>
                            <span id="daily">
                                <select name="time" style="width: 100px;">
                                    <?php for ($i = 0; $i < 24; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?>:00</option>
                                    <?php } ?>
                                </select>
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th class="right" rowspan="2">
                            <?php __('Carrier')?>:
                            </td>
                        <td colspan="3">
                            <?php __('Select All')?>:
                            <input type="checkbox" name="is_all_carrier" id="all_carrier" />
                        </td> 
                    </tr>

                    <tr>
                        <td colspan="3">
                            <select id="carrier_list" style=" height: 200px;" multiple="multiple" name="carrier[]">
                                <?php foreach ($clients as $client) { ?>
                                    <option value="<?php echo $client[0]['client_id']; ?>"><?php echo $client[0]['name'] ?></option>

                                <?php } ?>
                            </select>
                        </td>
                    </tr>


                    <tr>
                        <th class="right" rowspan="2">
                            <?php __('Ingress Trunk')?>:
                            </td>
                        <td>
                            <?php __('Select All')?>:
                            <input type="checkbox" name="all_ingress" id="all_ingress" checked="checked" />
                        </td>
                        <th class="right" rowspan="2">
                            <?php __('Egress Trunk')?>:
                            </td>
                        <td>
                            <?php __('Select All')?>:
                            <input type="checkbox" name="all_egress" id="all_egress" checked="checked"  />
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <select id="ingress_list" style=" height: 200px;" multiple="multiple" name="ingress[]" disabled="disabled">

                            </select>
                            <input type="hidden" name="ingressid" id="ingress_arr" />
                        </td>
                        <td>
                            <select id="egress_list" style=" height: 200px;" multiple="multiple" name="egress[]" disabled="disabled">

                            </select>
                            <input type="hidden" name="egressid" id="egress_arr" />
                        </td>
                    </tr>

                    <tr>
                        <th class="right"><?php __('Time Bucket')?>:</th>
                        <td colspan="3">
                            <input type="radio" value="1" name="time_bucket" checked="checked" /><?php __('Hourly')?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" value="2" name="time_bucket" /><?php __('Daily')?>
                        </td>
                    </tr>


                    <tr>
                        <th class="right"><?php __('Code Bucket')?>:</th>
                        <td colspan="3">
                            <input type="radio" value="1" name="code_bucket" checked="checked"  /><?php __('By Code')?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" value="2" name="code_bucket" /><?php __('By Code Name')?>
                        </td>
                    </tr>

                    <tr>
                        <th class="right"><?php __('Skip Empty')?>:</th>
                        <td colspan="3">
                            <input type="checkbox" name="skip_empty" checked="checked" />
                        </td>
                    </tr>

                    <tr>
                        <th class="right"><?php __('Email to')?>:</th>
                        <td colspan="3">
                            <input type="text" name="email_to"  />
                        </td>
                    </tr>


                    <tr>
                        <td colspan="4" class="center">
                            <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary">
                            <input class="input in-button btn btn-default" type="reset" style="margin-left: 20px;" value="<?php __('Revert')?>">
                </tbody>
                </td>
                </tr>
            </table>
            </form>
        </div>

        <div class="clearfix"></div>
    </div>
</div>

<script type="text/javascript" >

    $(function() {

    $("#select_type").change(function() {

        var type = $(this).val();
                $("#monthly").hide();
                $("#weekly").hide();
                switch (type) {
        case '1':

                break;
                case '2':
                $("#weekly").show();
                break;
                case '3':
                $("#monthly").show();
                break;
                defalut: break;
        };
    });
    
    
    $("#all_carrier").click(function() {

        var all_carrier = $(this).attr('checked');
        if (all_carrier)
        {
            $("#carrier_list").children().attr('selected','selected');
            $("#carrier_list").change();
            $("#carrier_list").attr('disabled', 'disabled');
                
        } else {
        $("#carrier_list").removeAttr('disabled');
        
        }
    });
    
    
    
    $("#all_ingress").click(function() {

        var all_ingress = $(this).attr('checked');
        if (all_ingress)
        {
            $("#ingress_list").attr('disabled', 'disabled');
        } else {
            $("#ingress_list").removeAttr('disabled');
        }
    });
            
        
    $("#all_egress").click(function() {

            var all_egress = $(this).attr('checked');
            if (all_egress)
           {
               $("#egress_list").attr('disabled', 'disabled');
           } else {
               $("#egress_list").removeAttr('disabled');
           }
    });
    
    
    
    
    $("#carrier_list").change(function() {

            var value = $(this).val();
            $.ajax({
            type: "POST",
            url: "<?php echo $this->webroot ?>reports/ajax_get_trunk",
            data: "value=" + value,
            dataType: "json",
            success: function(mag) {
            $("#ingress_list").html('');
                    var ingress_arr = eval(mag.ingress);
                    $.each(ingress_arr, function(index, item) {
                    var value = ingress_arr[index].resource_id;
                            var name = ingress_arr[index].alias;
                            $("#ingress_arr").val($("#ingress_arr").val() +","+value);
                            $("#ingress_list").html($("#ingress_list").html() + "<option selected='selected' value='" + value + "' >" + name + "</option>");
                    });
                    $("#egress_list").html('');
                    var egress_arr = eval(mag.egress);
                    $.each(egress_arr, function(index, item) {
                    var value = egress_arr[index].resource_id;
                            var name = egress_arr[index].alias;
                            $("#egress_arr").val($("#egress_arr").val() +","+value);
                            $("#egress_list").html($("#egress_list").html() + "<option selected='selected' value='" + value + "' >" + name + "</option>");
                    });
                }
    });
});
});
</script>
