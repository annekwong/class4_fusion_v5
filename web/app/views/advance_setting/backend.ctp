<style type="text/css" >
    .width5{
        width:5px;
        background-color: #EFEFEF;
    }
    body #myform tr td input {
        margin-bottom: 0px;
    }
    .red{
        background-color: rgb(229,65,45);
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
    }
    #inner{
        height: 28px;
        padding-top: 8px;
        padding-left: 10px;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Advance System Setting') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Advance System Setting') ?></h4>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a class="glyphicons no-js imac" href="<?php echo $this->webroot; ?>advance_setting/web">
                        <i></i>
                        <?php __('Web') ?>
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons no-js cogwheel" href="<?php echo $this->webroot; ?>advance_setting/backend">
                        <i></i>
                        <?php __('Backend') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">

            <div style="margin:0 auto;">

                <form method="post" id="myform">
                    <div class="row-fluid" >
                        <div class="span6">
                            <table class="list table dynamicTable tableTools table-bordered  table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('General') ?>:</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Backup Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="general[backup_ip]" value="<?php echo $data['general']['backup_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Report Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="general[report_ip]" value="<?php echo $data['general']['report_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Active Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="general[active_ip]" value="<?php echo $data['general']['active_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Switch Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="general[switch_ip]" value="<?php echo $data['general']['switch_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Billing Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="general[billing_ip]" value="<?php echo $data['general']['billing_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Public Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="general[public_ip]"  value="<?php echo $data['general']['public_ip']; ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Media Gateway Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="general[media_gateway_ip]" value="<?php echo $data['general']['media_gateway_ip']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Sip Endpoint') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Switch Name') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="sip_endpoint[switch_name]" value="<?php echo $data['sip_endpoint']['switch_name']; ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Auto Profile') ?>:
                                        </td>
                                        <td>
                                            <input  type="hidden" name="sip_endpoint[auto_profile]" value=""  />
                                            <input type="checkbox" name="sip_endpoint[auto_profile]" <?php
                                            if ($data['sip_endpoint']['auto_profile'])
                                            {
                                                ?> checked="checked" <?php } ?> />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Switch Core') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Max Sessions') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_core[max_sessions]" value="<?php echo $data['switch_core']['max_sessions']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Log Level') ?>:
                                        </td>
                                        <td>
                                            <select name="switch_core[log_level]">
                                                <?php
                                                foreach ($log_level_arr as $key => $value)
                                                {
                                                    ?>
                                                    <option value="<?php echo $key; ?>"<?php
                                                    if ($data['switch_core']['log_level'] == $key)
                                                    {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $value; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Task Counts') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_core[task_counts]" value="<?php echo $data['switch_core']['task_counts']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Cli Listen Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_core[cli_listen_ip]" value="<?php echo $data['switch_core']['cli_listen_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Cli Listen Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_core[cli_listen_port]" value="<?php echo $data['switch_core']['cli_listen_port']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Switch Media') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Sport') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_media[sport]" value="<?php echo $data['switch_media']['sport']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Eport') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_media[eport]" value="<?php echo $data['switch_media']['eport']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Switch Api') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Api Bind Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_api[api_bind_port]" value="<?php echo $data['switch_api']['api_bind_port']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Api Listen Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_api[api_listen_port]" value="<?php echo $data['switch_api']['api_listen_port']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Api Backup Cdr') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="switch_api[api_backup_cdr]" value="false" />
                                            <input  type="checkbox" name="switch_api[api_backup_cdr]" <?php
                                            if ($data['switch_api']['api_backup_cdr'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Api Backup Dir') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_api[api_backup_dir]" value="<?php echo $data['switch_api']['api_backup_dir']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Billing Server Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_api[billing_server_ip]" value="<?php echo $data['switch_api']['billing_server_ip']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Billing Server Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_api[billing_server_port]" value="<?php echo $data['switch_api']['billing_server_port']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Billing Method') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="switch_api[billing_method]"  />
                                            <input  type="checkbox" name="switch_api[billing_method]" <?php
                                            if ($data['switch_api']['billing_method'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>


                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Switch Lnp') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Lnp Bind Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_lnp[lnp_bind_ip]" value="<?php echo $data['switch_lnp']['lnp_bind_ip']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Lnp Bind Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_lnp[lnp_bind_port]" value="<?php echo $data['switch_lnp']['lnp_bind_port']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Lnp Heartbeat Enable') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="no" name="switch_lnp[lnp_heartbeat_enable]"  />
                                            <input  type="checkbox" name="switch_lnp[lnp_heartbeat_enable]" <?php
                                            if ($data['switch_lnp']['lnp_heartbeat_enable'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Lnp Heartbeat Timeout') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_lnp[lnp_heartbeat_timeout]" value="<?php echo $data['switch_lnp']['lnp_heartbeat_timeout']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Lnp Heartbeat Interval') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_lnp[lnp_heartbeat_interval]" value="<?php echo $data['switch_lnp']['lnp_heartbeat_interval']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Lnp Cache Enable') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="no" name="switch_lnp[lnp_cache_enable]"  />
                                            <input  type="checkbox" name="switch_lnp[lnp_cache_enable]" <?php
                                            if ($data['switch_lnp']['lnp_cache_enable'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Lnp Cache Expire') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_lnp[lnp_cache_expire]" value="<?php echo $data['switch_lnp']['lnp_cache_expire']; ?>"  />
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <!--switch_route-->
                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Switch Route') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Media Server Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[media_server_ip]" value="<?php echo $data['switch_route']['media_server_ip']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Media Server Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[media_server_port]" value="<?php echo $data['switch_route']['media_server_port']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Media Remote Server Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[media_remote_server_ip]" value="<?php echo $data['switch_route']['media_remote_server_ip']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Media Remote Server Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[media_remote_server_port]" value="<?php echo $data['switch_route']['media_remote_server_port']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Media Server Enable') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="switch_route[media_server_enable]"  />
                                            <input  type="checkbox" name="switch_route[media_server_enable]" <?php
                                            if ($data['switch_route']['media_server_enable'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('User Listen Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[user_listen_ip]" value="<?php echo $data['switch_route']['user_listen_ip']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('User Listen Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[user_listen_port]" value="<?php echo $data['switch_route']['user_listen_port']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Origination Code From') ?>:        
                                        </td>
                                        <td>
                                            <select name="switch_route[origination_code_from]">
                                                <option value="0" <?php
                                                if ($data['switch_route']['origination_code_from'] == 0)
                                                {
                                                    echo "selected='selected'";
                                                }
                                                ?>><?php __('Code table') ?></option>
                                                <option value="1" <?php
                                                if ($data['switch_route']['origination_code_from'] == 1)
                                                {
                                                    echo "selected='selected'";
                                                }
                                                ?>><?php __('Rate table') ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Termination Code From') ?>:      
                                        </td>
                                        <td>
                                            <select name="switch_route[termination_code_from]">
                                                <option value="0" <?php
                                                if ($data['switch_route']['termination_code_from'] == 0)
                                                {
                                                    echo "selected='selected'";
                                                }
                                                ?>><?php __('Code table') ?></option>
                                                <option value="1" <?php
                                                if ($data['switch_route']['termination_code_from'] == 1)
                                                {
                                                    echo "selected='selected'";
                                                }
                                                ?>><?php __('Rate table') ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Ring Timeout') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[ring_timeout]" value="<?php echo $data['switch_route']['ring_timeout']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Origination Pdd Timeout') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[origination_pdd_timeout]" value="<?php echo $data['switch_route']['origination_pdd_timeout']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Termination Pdd Timeout') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[termination_pdd_timeout]" value="<?php echo $data['switch_route']['termination_pdd_timeout']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Canada Npas Csv') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[canada_npas_csv]" value="<?php echo $data['switch_route']['canada_npas_csv']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Canada Territories Csv') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[canada_territories_csv]" value="<?php echo $data['switch_route']['canada_territories_csv']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Usa Npas Csv') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[usa_npas_csv]" value="<?php echo $data['switch_route']['usa_npas_csv']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Us Territories Csv') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[us_territories_csv]" value="<?php echo $data['switch_route']['us_territories_csv']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Npa Number Directory') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[npa_number_directory]" value="<?php echo $data['switch_route']['npa_number_directory']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Lcr Analysis Results') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[lcr_analysis_results]" value="<?php echo $data['switch_route']['lcr_analysis_results']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Ani List Filename') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_route[ani_list_filename]" value="<?php echo $data['switch_route']['ani_list_filename']; ?>"  />
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            

                        </div>
                        <div class="span6">
                            <!--switch-sdp-->
<!--                            <table class="list table dynamicTable tableTools table-bordered  table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2">switch-sdp:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Any:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_any]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_any]" <?php
                                            if ($data['switch-sdp']['sdp_any'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Audio:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_audio]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_audio]" <?php
                                            if ($data['switch-sdp']['sdp_audio'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Video:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_video]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_video]" <?php
                                            if ($data['switch-sdp']['sdp_video'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Application:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_application]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_application]" <?php
                                            if ($data['switch-sdp']['sdp_application'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Data:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_data]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_data]" <?php
                                            if ($data['switch-sdp']['sdp_data'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Control:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_control]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_control]" <?php
                                            if ($data['switch-sdp']['sdp_control'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Message:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_message]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_message]" <?php
                                            if ($data['switch-sdp']['sdp_message'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Image:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_image]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_image]" <?php
                                            if ($data['switch-sdp']['sdp_image'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Red:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_red]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_red]" <?php
                                            if ($data['switch-sdp']['sdp_red'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Tcp:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_tcp]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_tcp]" <?php
                                            if ($data['switch-sdp']['sdp_tcp'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Udp:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_udp]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_udp]" <?php
                                            if ($data['switch-sdp']['sdp_udp'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Rtp:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_rtp]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_rtp]" <?php
                                            if ($data['switch-sdp']['sdp_rtp'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Srtp:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_srtp]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_srtp]" <?php
                                            if ($data['switch-sdp']['sdp_srtp'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Udptl:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_udptl]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_udptl]" <?php
                                            if ($data['switch-sdp']['sdp_udptl'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Tls:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_tls]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_tls]" <?php
                                            if ($data['switch-sdp']['sdp_tls'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Bandwidth Ct:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_bandwidth_ct]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_bandwidth_ct]" <?php
                                            if ($data['switch-sdp']['sdp_bandwidth_ct'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Andwidth As:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_bandwidth_as]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_bandwidth_as]" <?php
                                            if ($data['switch-sdp']['sdp_bandwidth_as'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Bandwidth Specification Value:') ?>
                                        </td>
                                        <td>
                                            <input type="text" name="switch-sdp[bandwidth_specification_value]" value="<?php echo $data['switch-sdp']['bandwidth_specification_value']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Ip4:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_ip4]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_ip4]" <?php
                                            if ($data['switch-sdp']['sdp_ip4'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Ip6:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_ip6]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_ip6]" <?php
                                            if ($data['switch-sdp']['sdp_ip6'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Multi Cast:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_multi_cast]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_multi_cast]" <?php
                                            if ($data['switch-sdp']['sdp_multi_cast'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Number Of Groups:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_number_of_groups]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_number_of_groups]" <?php
                                            if ($data['switch-sdp']['sdp_number_of_groups'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Key Clear:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_key_clear]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_key_clear]" <?php
                                            if ($data['switch-sdp']['sdp_key_clear'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Key Base64:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_key_base64]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_key_base64]" <?php
                                            if ($data['switch-sdp']['sdp_key_base64'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Key Uri:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_key_uri]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_key_uri]" <?php
                                            if ($data['switch-sdp']['sdp_key_uri'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Key Prompt:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_key_prompt]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_key_prompt]" <?php
                                            if ($data['switch-sdp']['sdp_key_prompt'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Key:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_key]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_key]" <?php
                                            if ($data['switch-sdp']['sdp_key'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Sdp Support Multi Media Port:') ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="switch-sdp[sdp_support_multi_media_port]"  />
                                            <input  type="checkbox" name="switch-sdp[sdp_support_multi_media_port]" <?php
                                            if ($data['switch-sdp']['sdp_support_multi_media_port'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>-->
                            
                            <!--billing_cdr-->
                            <table class="list table dynamicTable tableTools table-bordered  table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Billing Cdr') ?>:</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Set Balance') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="billing_cdr[set_balance]"  />
                                            <input  type="checkbox" name="billing_cdr[set_balance]" <?php
                                            if ($data['billing_cdr']['set_balance'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Write Db Cdr') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="billing_cdr[write_db_cdr]"  />
                                            <input  type="checkbox" name="billing_cdr[write_db_cdr]" <?php
                                            if ($data['billing_cdr']['write_db_cdr'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Write Hd Cdr') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="billing_cdr[write_hd_cdr]"  />
                                            <input  type="checkbox" name="billing_cdr[write_hd_cdr]" <?php
                                            if ($data['billing_cdr']['write_hd_cdr'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Send Report') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="billing_cdr[send_report]"  />
                                            <input  type="checkbox" name="billing_cdr[send_report]" <?php
                                            if ($data['billing_cdr']['send_report'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Auto Recover') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="billing_cdr[auto_recover]"  />
                                            <input  type="checkbox" name="billing_cdr[auto_recover]" <?php
                                            if ($data['billing_cdr']['auto_recover'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Hd Cdr Dir') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_cdr[hd_cdr_dir]" value="<?php echo $data['billing_cdr']['hd_cdr_dir']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Active Bind Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_cdr[active_bind_ip]" value="<?php echo $data['billing_cdr']['active_bind_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Active Bind Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_cdr[active_bind_port]" value="<?php echo $data['billing_cdr']['active_bind_port']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Active Server Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_cdr[active_server_ip]" value="<?php echo $data['billing_cdr']['active_server_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Active Server Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_cdr[active_server_port]" value="<?php echo $data['billing_cdr']['active_server_port']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Report Server Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_cdr[report_server_ip]"  value="<?php echo $data['billing_cdr']['report_server_ip']; ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Report Server Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_cdr[report_server_port]" value="<?php echo $data['billing_cdr']['report_server_port']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Report Bind Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_cdr[report_bind_ip]"  value="<?php echo $data['billing_cdr']['report_bind_ip']; ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Report Bind Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_cdr[report_bind_port]" value="<?php echo $data['billing_cdr']['report_bind_port']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--billing_log-->
                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Billing Log') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Log Level') ?>:
                                        </td>
                                        <td>
                                            <select name="billing_log[log_level]">
                                                <?php
                                                foreach ($billing_log_level_arr as $key => $value)
                                                {
                                                    ?>
                                                    <option value="<?php echo $key; ?>"<?php
                                                    if ($data['billing_log']['log_level'] == $key)
                                                    {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $value; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Log Directory') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_log[log_directory]" value="<?php echo $data['billing_log']['log_directory']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!--billing_monit-->
                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Billing Monit') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('send_code_info') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="billing_monit[send_code_info]"  />
                                            <input  type="checkbox" name="billing_monit[send_code_info]" <?php
                                            if ($data['billing_monit']['send_code_info'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('import_host_info') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="billing_monit[import_host_info]"  />
                                            <input  type="checkbox" name="billing_monit[import_host_info]" <?php
                                            if ($data['billing_monit']['import_host_info'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Send Report') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="billing_monit[import_route_info]"  />
                                            <input  type="checkbox" name="billing_monit[import_route_info]" <?php
                                            if ($data['billing_monit']['import_route_info'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('get_rtp_info') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="billing_monit[get_rtp_info]"  />
                                            <input  type="checkbox" name="billing_monit[get_rtp_info]" <?php
                                            if ($data['billing_monit']['get_rtp_info'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <!--billing_api-->
                            <table class="list table dynamicTable tableTools table-bordered  table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Billing Api') ?>:</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Api Listen Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_api[api_listen_ip]" value="<?php echo $data['billing_api']['api_listen_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Api Listen Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_api[api_listen_port]" value="<?php echo $data['billing_api']['api_listen_port']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('User Listen Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_api[user_listen_ip]" value="<?php echo $data['billing_api']['user_listen_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('User Listen Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_api[user_listen_port]" value="<?php echo $data['billing_api']['user_listen_port']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Api Bind Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_api[api_bind_ip]" value="<?php echo $data['billing_api']['api_bind_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Api Bind Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_api[api_bind_port]"  value="<?php echo $data['billing_api']['api_bind_port']; ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Remote Addr') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="billing_api[remote_addr]" value="<?php echo $data['billing_api']['remote_addr']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <!--active_call-->
                            <table class="list table dynamicTable tableTools table-bordered  table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Active Call') ?>:</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Active Search Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="active_call[active_search_ip]" value="<?php echo $data['active_call']['active_search_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Active Search Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="active_call[active_search_port]" value="<?php echo $data['active_call']['active_search_port']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Active Listen Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="active_call[active_listen_ip]" value="<?php echo $data['active_call']['active_listen_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Active Listen Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="active_call[active_listen_port]" value="<?php echo $data['active_call']['active_listen_port']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <!--ha_server-->
                            <table class="list table dynamicTable tableTools table-bordered  table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Ha Server') ?>:</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Ha Listen Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="ha_server[ha_listen_ip]" value="<?php echo $data['ha_server']['ha_listen_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Ha Listen Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="ha_server[ha_listen_port]" value="<?php echo $data['ha_server']['ha_listen_port']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <!--report_cdr-->
                            <table class="list table dynamicTable tableTools table-bordered  table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Report Cdr') ?>:</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Report Listen Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="report_cdr[report_listen_ip]" value="<?php echo $data['report_cdr']['report_listen_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Report Listen Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="report_cdr[report_listen_port]" value="<?php echo $data['report_cdr']['report_listen_port']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('User Listen Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="report_cdr[user_listen_ip]" value="<?php echo $data['report_cdr']['user_listen_ip']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('User Listen Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="report_cdr[user_listen_port]" value="<?php echo $data['report_cdr']['user_listen_port']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--switch_license-->
                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Switch License') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('License Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_license[license_ip]" value="<?php echo $data['switch_license']['license_ip']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('License Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_license[license_port]" value="<?php echo $data['switch_license']['license_port']; ?>"  />
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <!--switch_ha-->
                            <table class="list table dynamicTable tableTools table-bordered   table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Switch Ha') ?>:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('Ha Bind Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_ha[ha_bind_ip]" value="<?php echo $data['switch_ha']['ha_bind_ip']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Ha Bind Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_ha[ha_bind_port]" value="<?php echo $data['switch_ha']['ha_bind_port']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Ha Server Ip') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_ha[ha_server_ip]" value="<?php echo $data['switch_ha']['ha_server_ip']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Ha Server Port') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_ha[ha_server_port]" value="<?php echo $data['switch_ha']['ha_server_port']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Ha Recover Key') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="switch_ha[ha_recover_key]" value="<?php echo $data['switch_ha']['ha_recover_key']; ?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Ha Auto Recover') ?>:
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="switch_ha[ha_auto_recover]"  />
                                            <input  type="checkbox" name="switch_ha[ha_auto_recover]" <?php
                                            if ($data['switch_ha']['ha_auto_recover'])
                                            {
                                                ?> checked="checked"<?php } ?> />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--rate_update-->
                            <table class="list table dynamicTable tableTools table-bordered  table-primary table-white">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php __('Rate Update') ?>:</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php __('History Rate List') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="rate_update[history_rate_list]" value="<?php echo $data['rate_update']['history_rate_list']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php __('Generate Rate List') ?>:
                                        </td>
                                        <td>
                                            <input  type="text" name="rate_update[generate_rate_list]" value="<?php echo $data['rate_update']['generate_rate_list']; ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 center" >
                            <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit')?>">
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(function() {



        });

    </script>