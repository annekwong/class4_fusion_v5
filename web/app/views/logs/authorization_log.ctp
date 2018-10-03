<style>
    table.in-date tr td{border-top: 0;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>logs/authorization_log">
        <?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>logs/authorization_log">
        <?php echo __('Authorization Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Authorization Log') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php
            $data = $p->getDataArray();
            ?>
            <?php
            if (count($data) == 0)
            {
                ?>
                <div class="msg center">
                    <h2><?php echo __('no_data_found') ?></h2>
                </div>
            <?php
            }
            else
            {
                ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('time', __('Time', true)) ?></th>
                            <th><?php __('Direction')?></th>
                            <th><?php __('Auth Type')?></th>
                            <th><?php __('Error Type')?></th>
                            <th><?php __('Request Ip')?></th>
                            <th><?php __('Request Port')?></th>
                            <th><?php __('Username')?></th>
                            <th><?php __('Authname')?></th>
                            <th><?php __('Sip Callid')?></th>
                        </tr>
                    </thead>

                    <tbody>
    <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?php echo date('Y-m-d H:i:sO', $item[0]['time']); ?></td>
                                <td><?php if ($item[0]['direction'])
        {
            echo "Out";
        }
        else
        {
            echo "In";
        } ?></td>
                                <td><?php if ($item[0]['auth_type'])
        {
            echo "Invite";
        }
        else
        {
            echo "Register";
        } ?></td>
                                <td><?php echo isset($error_type[$item[0]['error_type']]) ? $error_type[$item[0]['error_type']] : "other"; ?></td>
                                <td><?php echo $item[0]['request_ip']; ?></td>
                                <td><?php echo $item[0]['request_port']; ?></td>
                                <td><?php echo $item[0]['username']; ?></td>
                                <td><?php echo $item[0]['authname']; ?></td>
                                <td><?php echo $item[0]['sip_callid']; ?></td>
                            </tr>
    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                    </div> 
                </div>
<?php } ?>
            <div class="clearfix"></div>

            <fieldset style="clear:both;overflow:hidden;margin-top:10px;" class="query-box">
                <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
                <form method="get" class="form-inline" id="myform" name="myform">
                    <table  class="form table table-condensed">
                        <input type="hidden" value="FALSE" name="isDown" id="isDown" class="input in-hidden">
                        <tbody>
                            <tr class="period-block">
                                <td colspan="5" style="width:auto;" class="value value2"><table style="width: 98%;" class="in-date">
                                        <tbody>
                                            <col width="7%">
                                            <col width="10%">
                                            <col width="43%">
                                            <col width="20%">
                                            <col width="20%">
                                            <tr>
                                                <td style=""><?php __('Period')?></td>
                                                <td><select id="query-smartPeriod" onchange="setPeriod(this.value)" name="smartPeriod" class="input in-select select" style="width:150px;">
                                                        <option value="custom"><?php __('Custom')?></option>
                                                        <option selected="selected" value="curDay"><?php __('Today')?></option>
                                                        <option value="curWeek"><?php __('Current week')?></option>
                                                        <option value="curMonth"><?php __('Current month')?></option>
                                                    </select>
                                                </td>
                                                <td><input type="text"name="start_date" value="<?php echo $start_date ?>" onkeydown="setPeriod('custom')" readonly="readonly" onchange="setPeriod('custom')" class="in-text input in-input input-small" id="query-start_date-wDt">
                                                    &nbsp;
                                                    <input type="text" class="input in-text in-input input-small" name="start_time" value="<?php echo $start_time ?>" readonly="readonly" onkeydown="setPeriod('custom')" onchange="setPeriod('custom')" id="query-start_time-wDt">
                                                &mdash;
                                                    <input type="text" name="stop_date" value="<?php echo $end_date ?>" onkeydown="setPeriod('custom')" readonly="readonly" onchange="setPeriod('custom')" class="in-text input in-input input-small" id="query-stop_date-wDt">
                                                    &nbsp;
                                                    <input type="text"  class="input in-text in-input input-small" name="stop_time" value="<?php echo $end_time ?>" onkeydown="setPeriod('custom')" readonly="readonly" onchange="setPeriod('custom')" id="query-stop_time-wDt"></td>
                                                <td><?php __('in')?> <select  class="input in-select select" style="width:150px;" name="gmt" id="query-tz">
                                                        <option value="-1200">GMT -12:00</option>
                                                        <option value="-1100">GMT -11:00</option>
                                                        <option value="-1000">GMT -10:00</option>
                                                        <option value="-0900">GMT -09:00</option>
                                                        <option value="-0800">GMT -08:00</option>
                                                        <option value="-0700">GMT -07:00</option>
                                                        <option value="-0600">GMT -06:00</option>
                                                        <option value="-0500">GMT -05:00</option>
                                                        <option value="-0400">GMT -04:00</option>
                                                        <option value="-0300">GMT -03:00</option>
                                                        <option value="-0200">GMT -02:00</option>
                                                        <option value="-0100">GMT -01:00</option>
                                                        <option value="+0000">GMT +00:00</option>
                                                        <option value="+0100">GMT +01:00</option>
                                                        <option value="+0200">GMT +02:00</option>
                                                        <option value="+0300">GMT +03:00</option>
                                                        <option value="+0330">GMT +03:30</option>
                                                        <option value="+0400">GMT +04:00</option>
                                                        <option value="+0500">GMT +05:00</option>
                                                        <option value="+0600">GMT +06:00</option>
                                                        <option value="+0700">GMT +07:00</option>
                                                        <option value="+0800">GMT +08:00</option>
                                                        <option value="+0900">GMT +09:00</option>
                                                        <option value="+1000">GMT +10:00</option>
                                                        <option value="+1100">GMT +11:00</option>
                                                        <option value="+1200">GMT +12:00</option>
                                                    </select></td>
                                                    <td><?php __('Direction')?>
                                                        <select name="direction" style="width:150px;">
                                                        <option value="all"  <?php echo $common->set_get_select('direction', 'all', TRUE); ?>><?php __('All')?></option>
                                                        <option value="0" <?php echo $common->set_get_select('direction', '0'); ?>><?php __('In')?></option>
                                                        <option value="1" <?php echo $common->set_get_select('direction', '1'); ?>><?php __('Out')?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr style="height:10px;"></tr>
                                            <tr>
                                                <td><?php __('Auth Type')?></td>
                                                <td>
                                                    <select name="auth_type" style="width:150px;">
                                                        <option value="all"  <?php echo $common->set_get_select('action', 'all', TRUE); ?>><?php __('All')?></option>
                                                        <option value="0" <?php echo $common->set_get_select('auth_type', '0'); ?>><?php __('Register')?></option>
                                                        <option value="1" <?php echo $common->set_get_select('auth_type', '1'); ?>><?php __('Invite')?></option>
                                                    </select>
                                                </td>
                                                <td><?php __('Error Type')?>
                                                    <select name="error_type"  style="width:150px;">
                                                        <option value="all"  <?php echo $common->set_get_select('error_type', 'all', TRUE); ?>><?php __('All')?></option>
                                                        <option value="0" <?php echo $common->set_get_select('error_type', '0'); ?>><?php __('Normal')?></option>
                                                        <option value="1" <?php echo $common->set_get_select('error_type', '1'); ?>><?php __('Auth Params Incomplete')?></option>
                                                        <option value="2" <?php echo $common->set_get_select('error_type', '2'); ?>><?php __('User Nothingness')?></option>
                                                        <option value="3" <?php echo $common->set_get_select('error_type', '3'); ?>><?php __('Wrong Password')?></option>
                                                        <option value="4" <?php echo $common->set_get_select('error_type', '4'); ?>><?php __('Wrong Username')?></option>
                                                    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span data-placement="top" data-content="Request Port,Username && Authname Complete match;Sip Callid Fuzzy Matching" data-title="Search supported" data-toggle="popover" data-original-title="" >
                                                        <?php __('Search')?>
                                                        </span>
                                                        <input type="text" name="search" value="<?php echo $common->set_get_value('search') ?>" style="width:155px;" />
                                                </td
                                            </tr>
                                        </tbody>
                                    </table></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" class="buttons-group center"><input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary"></td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </fieldset>

        </div>
    </div>
</div>


<script type="text/javascript">
    $(function() {
        alert(2);
        $('#query-tz').val('<?php echo $tz ?>');
        //$('#query-tz option[value="<?php echo $tz ?>"]').attr('selected', 'selected');
    }

</script>

