<style>
    input[type="text"],textarea{margin-bottom: 0;}
    textarea{max-width: 800px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/dashboard">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/dashboard">
        <?php echo __('Dashboard') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/auto_delivery">
        <?php echo __('Auto Delivery') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Auto Delivery'); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard" class="glyphicons dashboard">
                        <i></i><?php __('Dashboard')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/report" class="glyphicons stats">
                        <i></i><?php __('Report')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/search_charts"  class="glyphicons charts">
                        <i></i><?php __('Charts')?>
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $this->webroot ?>homes/auto_delivery"  class="glyphicons stroller">
                        <i></i><?php __('Auto Delivery')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/qos_report"  class="glyphicons notes">
                        <i></i><?php __('Qos Report')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/ingress"  class="glyphicons eye_open">
                        <i></i><?php __('Ingress Clients Qos')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/egress"  class="glyphicons eye_open">
                        <i></i><?php __('Egress Clients Qos')?>
                    </a>
                </li>
<!--                <li>-->
<!--                    <a href="--><?php //echo $this->webroot ?><!--homes/alert"  class="glyphicons alarm">-->
<!--                        <i></i>--><?php //__('Alert')?>
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </div>
        <div class="widget-body">

            <form method="post">
                <table class="table dynamicTable tableTools table-bordered  table-striped table-white">
                    <col width="25%">
                    <col width="50%">
                    <col width="25%">
                    <tr>
                        <td class="right"><?php __('Group By')?></td>
                        <td>
                            <select name="group_by">
                                <option value="0" <?php echo $data[0][0]['auto_delivery_group_by'] == 0 ? 'selected' : '' ?>><?php __('Country')?></option>
                                <option value="1" <?php echo $data[0][0]['auto_delivery_group_by'] == 1 ? 'selected' : '' ?>><?php __('Code Name')?></option>
                                <option value="2" <?php echo $data[0][0]['auto_delivery_group_by'] == 2 ? 'selected' : '' ?>><?php __('Code')?></option>
                                <option value="3" <?php echo $data[0][0]['auto_delivery_group_by'] == 3 ? 'selected' : '' ?>><?php __('Trunk')?></option>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Time Zone')?></td>
                        <td>
                            <select name="timezone" id="timezone">
                                <option value="-12:00">GMT -12:00</option>
                                <option value="-11:00">GMT -11:00</option>
                                <option value="-10:00">GMT -10:00</option>
                                <option value="-09:00">GMT -09:00</option>
                                <option value="-08:00">GMT -08:00</option>
                                <option value="-07:00">GMT -07:00</option>
                                <option value="-06:00">GMT -06:00</option>
                                <option value="-05:00">GMT -05:00</option>
                                <option value="-04:00">GMT -04:00</option>
                                <option value="-03:00">GMT -03:00</option>
                                <option value="-02:00">GMT -02:00</option>
                                <option value="-01:00">GMT -01:00</option>
                                <option value="+00:00">GMT +00:00</option>
                                <option value="+01:00">GMT +01:00</option>
                                <option value="+02:00">GMT +02:00</option>
                                <option value="+03:00">GMT +03:00</option>
                                <option value="+04:00">GMT +04:00</option>
                                <option value="+05:00">GMT +05:00</option>
                                <option value="+06:00">GMT +06:00</option>
                                <option value="+07:00">GMT +07:00</option>
                                <option value="+08:00">GMT +08:00</option>
                                <option value="+09:00">GMT +09:00</option>
                                <option value="+10:00">GMT +10:00</option>
                                <option value="+11:00">GMT +11:00</option>
                                <option value="+12:00">GMT +12:00</option>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Email Address')?></td>
                        <td>
                            <input type="text" name="email_address" style="width:400px;" value="<?php echo $data[0][0]['auto_delivery_address']; ?>" />
                        </td>
                        <td><?php __('Separated by')?> ;</td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Email Subject')?></td>
                        <td>
                            <input type="text" name="auto_delivery_subject" style="width:400px;" value="<?php echo $data[0][0]['auto_delivery_subject']; ?>"  />
                        </td>
                        <td><?php __('{date}, {timezone}')?></td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Email Content')?></td>
                        <td>
                            <textarea style="width:600px;height:200px;" name="auto_delivery_content"><?php echo $data[0][0]['auto_delivery_content']; ?></textarea>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="buttons-group center"><input type="submit" value="<?php __('Submit')?>" class="btn btn-primary" />&nbsp;&nbsp;<input type="button" id="testing" value="<?php __('Test')?>" class="btn btn-default" /></td>
                    </tr>
                </table>
            </form>


        </div>
    </div>
</div>

<script>
    $(function() {
        //$('#timezone option[value=<?php echo $data[0][0]['auto_delivery_timezone']; ?>]').attr('selected', true);
        var timezone = "<?php  echo $data[0][0]['auto_delivery_timezone'] ? $data[0][0]['auto_delivery_timezone'] : "";  ?>";
        $("#timezone").val(timezone);
        $('#testing').click(function() {
            $.ajax({
                'url' : '<?php echo $this->webroot ?>homes/auto_delivery_test',
                'type' : 'POST',
                'dataType' : 'text',
                'success' : function(data) {
                    jGrowl_to_notyfy('The Auto Delivery is sent successfully!',{theme:'jmsg-success'});
                }
            });
        });
    });
</script>