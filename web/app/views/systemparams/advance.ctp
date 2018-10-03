
<style type="text/css" >
    .width5{
        width:5px;
        background-color: #EFEFEF;
    }
    body #myform tr td input {
        margin-bottom: 10px;
    }
    body #myform tr td input[type="text"],input[type="password"]{width: 220px;}
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
    fieldset{border:1px solid #ebebeb;padding:10px;margin-bottom:15px}
    .width-220{
        width:220px !important;
    }
    .icon-check-minus{
        color: red;
        font-size: 16px;
    }
    .icon-check-sign{
        color: green;
        font-size: 16px;
    }
    .icon-refresh{
        font-size: 16px;
        margin: 0 5px;
        color: green;
        cursor: pointer;
        display: inline-block;
    }
    .connected{color:green;}
    .unconnected{color:red;}
    .check-db-connection{cursor:pointer;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/view">
            <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/advance">
            <?php echo __('Advance System Setting') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Advance System Setting') ?></h4>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("advance_setting/tab",array('active' => 'advance')); ?>
        </div>
        <div class="widget-body">

            <div style="margin:0 auto;">

                <form method="post" id="myform">

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('Database') ?></h4></div>
                        <div class="widget-body" >
                            <table class="table table-bordered" style="margin-top:17px;">
                                <col width="20%">
                                <col width="20%">
                                <col width="60%">
                                <tbody>
                                <tr data-id="db1">
                                    <td class="align_right padding-r10" rowspan="6">
                                        <?php __('Default Database') ?>:
                                    </td>
                                    <td class="padding-r10 align_right">
                                        <a href = "javascript:void(0)" title="Check Connection" class = "check-db-connection">
                                            <i class="icon-refresh" aria-hidden="true"></i>
                                        </a>
                                     </td>
                                    <td class="padding-r10 ">
                                        <span class="connect-db-status connected">Connected</span>
                                    </td>
                                </tr>
                                <tr class="db1">
                                     <td class="align_right padding-r10">
                                       <?php __('Database Host') ?>
                                     </td>
                                     <td>
                                       <input id="database_2" type="text" class="validate[custom[ip]]" name="db[hostaddr]" value="<?php echo $data['db']['hostaddr']; ?>" />
                                   </td>
                                </tr>
                                <tr class="db1">
                                    <td class="align_right padding-r10">
                                        <?php __('Database Port') ?>
                                    </td>
                                    <td>
                                        <input id="database_3"  class="validate[custom[onlyNumber]]" type="text" name="db[port]" value="<?php echo $data['db']['port']; ?>" />
                                    </td>
                                </tr>
                                <tr class="db1">
                                    <td class="align_right padding-r10">
                                        <?php __('Database Name') ?>
                                    </td>
                                    <td>
                                        <input id="database_4" type="text" name="db[dbname]" value="<?php echo $data['db']['dbname']; ?>" />
                                    </td>
                                </tr>
                                <tr class="db1">
                                    <td class="align_right padding-r10">
                                        <?php __('Database User') ?>
                                    </td>
                                    <td>
                                        <input id="database_5" type="text" name="db[user]" value="<?php echo $data['db']['user']; ?>" />
                                    </td>
                                </tr>
                                <tr class="db1">
                                    <td class="align_right padding-r10">
                                        <?php __('Database Password') ?>
                                    </td>
                                    <td>
                                        <input id="database_6" type="password" name="db[password]" value="<?php echo $data['db']['password']; ?>" />
                                    </td>
                                </tr>

                                <!--              第二个数据库                      -->
                                <tr data-id="db2">
                                    <td class="align_right padding-r10" rowspan="6">
                                        <?php __('Second Database') ?>:
                                    </td>
                                    <td class="padding-r10 align_right">
                                        <a href = "javascript:void(0)" title="Check Connection" class = "check-db-connection">
                                            <i class="icon-refresh" aria-hidden="true"></i>
                                        </a>
                                     </td>
                                    <td class="padding-r10 ">
                                        <span class="connect-db-status connected">Connected</span>
                                    </td>
                                </tr>
                                <tr class="db2">
                                    <td class="align_right padding-r10">
                                        <?php __('Database Host') ?>
                                    </td>
                                    <td>
                                        <input id="database_2" type="text" class="validate[custom[ip]]" name="web_db2[host]" value="<?php echo $data['web_db2']['host']; ?>" />
                                    </td>
                                </tr>
                                <tr class="db2">
                                    <td class="align_right padding-r10">
                                        <?php __('Database Port') ?>
                                    </td>
                                    <td>
                                        <input id="database_3" type="text" class="validate[custom[onlyNumber]]" name="web_db2[port]" value="<?php echo $data['web_db2']['port']; ?>" />
                                    </td>
                                </tr>
                                <tr class="db2">
                                    <td class="align_right padding-r10">
                                        <?php __('Database Name') ?>
                                    </td>
                                    <td>
                                        <input id="database_4" type="text" name="web_db2[dbname]" value="<?php echo $data['web_db2']['dbname']; ?>" />
                                    </td>
                                </tr>
                                <tr class="db2">
                                    <td class="align_right padding-r10">
                                        <?php __('Database User') ?>
                                    </td>
                                    <td>
                                        <input id="database_5" type="text" name="web_db2[user]" value="<?php echo $data['web_db2']['user']; ?>" />
                                    </td>
                                </tr>
                                <tr class="db2">
                                    <td class="align_right padding-r10">
                                        <?php __('Database Password') ?>
                                    </td>
                                    <td>
                                        <input id="database_6" type="password" name="web_db2[password]" value="<?php echo $data['web_db2']['password']; ?>" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Web') ?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <col width="40%">
                        <col width="60%">
                        <tbody>
                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Enable Debug') ?>
                            </td>
                            <td>
                                <input  type="hidden" name="web_base[debug_level]" value=""  />
                                <input id="web_base_1" type="checkbox" name="web_base[debug_level]"  <?php
                                if ($data['web_base']['debug_level'] == 2)
                                {
                                ?>checked="checked"<?php } ?> />
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Enable Cmd Debug') ?>
                            </td>
                            <td>
                                <input  type="hidden" name="web_base[cmd_debug]" value=""  />
                                <input id="web_base_3" type="checkbox" name="web_base[cmd_debug]" <?php
                                if ($data['web_base']['cmd_debug'])
                                {
                                ?>checked="checked"<?php } ?> />
                            </td>
                        </tr>
                        <!--tr>
                                    <td class="align_right padding-r10">
                                        <?php __('System Token') ?>
                                    </td>
                                    <td>
                                        <input id="web_base_4" type="text" name="web_base[system_token]" value="<?php echo $data['web_base']['system_token']; ?>" />
                                    </td>
                                </tr-->
                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Check Switch License') ?>
                            </td>
                            <td>
                                <input  type="hidden" name="web_base[check_switch_license]" value=""  />
                                <input id="web_base_3" type="checkbox" name="web_base[check_switch_license]" <?php
                                if (isset($data['web_base']['check_switch_license']) && $data['web_base']['check_switch_license'])
                                {
                                ?>checked="checked"<?php } ?> />
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!--                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">-->
            <!--                        <div class="widget-head"><h4 class="heading">--><?php //__('Path Configuration') ?><!--</h4></div>-->
            <!--                        <div class="widget-body">-->
            <!--                            <table class="table table-bordered">-->
            <!--                                <col width="40%">-->
            <!--                                <col width="60%">-->
            <!--                                <tbody>-->
            <!--                                <tr>-->
            <!--                                    <td class="align_right padding-r10">-->
            <!--                                        --><?php //__('DB Export Path') ?>
            <!--                                    </td>-->
            <!--                                    <td>-->
            <!--                                        <input id="web_path_1" type="text" name="web_path[db_export_path]" value="--><?php //echo $data['web_path']['db_export_path']; ?><!--" />-->
            <!--                                    </td>-->
            <!--                                </tr>-->
            <!--                                </tbody>-->
            <!--                            </table>-->
            <!--                        </div>-->
            <!--                    </div>-->

            <!--                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">-->
            <!--                        <div class="widget-head"><h4 class="heading">--><?php //__('US jurisdiction Update') ?><!--</h4></div>-->
            <!--                        <div class="widget-body">-->
            <!--                            <table class="table table-bordered">-->
            <!--                                <col width="40%">-->
            <!--                                <col width="60%">-->
            <!--                                <tbody>-->
            <!--                                <tr>-->
            <!--                                    <td class="align_right padding-r10">-->
            <!--                                        --><?php //__('FTP IP') ?>
            <!--                                    </td>-->
            <!--                                    <td>-->
            <!--                                        <input id="us_jur_1" type="text" name="storage_server[ip]" value="--><?php //echo $data['storage_server']['ip']; ?><!--" />-->
            <!--                                    </td>-->
            <!--                                </tr>-->
            <!---->
            <!--                                <tr>-->
            <!--                                    <td class="align_right padding-r10">-->
            <!--                                        --><?php //__('FTP Username') ?>
            <!--                                    </td>-->
            <!--                                    <td>-->
            <!--                                        <input id="us_jur_2" type="text" name="storage_server[ftp_user]" autocomplete=false value="--><?php //echo $data['storage_server']['ftp_user']; ?><!--" />-->
            <!--                                    </td>-->
            <!--                                </tr>-->
            <!---->
            <!--                                <tr>-->
            <!--                                    <td class="align_right padding-r10">-->
            <!--                                        --><?php //__('FTP Password') ?>
            <!--                                    </td>-->
            <!--                                    <td>-->
            <!--                                        <input id="us_jur_3" type="password" name="storage_server[ftp_password]" />-->
            <!--                                    </td>-->
            <!--                                </tr>-->
            <!---->
            <!--                                </tbody>-->
            <!--                            </table>-->
            <!--                        </div>-->
            <!--                    </div>-->

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Web Feature') ?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <col width="40%">
                        <col width="60%">
                        <tbody>
                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Copyright Link') ?>
                            </td>
                            <td>
                                <input type="hidden" name="web_feature[copyright_link]"  />
                                <input id="web_feature_2" type="checkbox" name="web_feature[copyright_link]" <?php
                                if ($data['web_feature']['copyright_link'])
                                {
                                    ?> checked="checked"<?php } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Enable Origination Module') ?>
                            </td>
                            <td>
                                <input type="hidden" name="web_feature[did]"  />
                                <input id="web_feature_2" type="checkbox" name="web_feature[did]" <?php
                                if ($data['web_feature']['did'])
                                {
                                    ?> checked="checked"<?php } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Paypal') ?>
                            </td>
                            <td>
                                <input type="hidden" name="web_feature[paypal]"  />
                                <input id="web_feature_3" type="checkbox" name="web_feature[paypal]" <?php
                                if ($data['web_feature']['paypal'])
                                {
                                    ?> checked="checked"<?php } ?> />
                            </td>
                        </tr>
                        <!--                                        <tr>-->
                        <!--                                            <td class="align_right padding-r10">-->
                        <!--                                                --><?php //__('Yourpay') ?>
                        <!--                                            </td>-->
                        <!--                                            <td>-->
                        <!--                                                <input type="hidden" name="web_feature[yourpay]"  />-->
                        <!--                                                <input id="web_feature_4" type="checkbox" name="web_feature[yourpay]" --><?php
                        //                                                if ($data['web_feature']['yourpay'])
                        //                                                {
                        //                                                    ?><!-- checked="checked"--><?php //} ?><!-- />-->
                        <!--                                            </td>-->
                        <!--                                        </tr>-->
                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Pay in New Window') ?>
                            </td>
                            <td>
                                <input type="hidden" name="web_feature[pay_in_new_window]"  />
                                <input id="web_feature_5" type="checkbox" name="web_feature[pay_in_new_window]" <?php

                                if ($data['web_feature']['pay_in_new_window'])
                                {
                                    ?> checked="checked"<?php } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Statistics Group All') ?>
                            </td>
                            <td>
                                <input type="hidden" name="web_feature[statistics_group_all]"  />
                                <input id="web_feature_6" type="checkbox" name="web_feature[statistics_group_all]"  <?php
                                if ($data['web_feature']['statistics_group_all'])
                                {
                                    ?> checked="checked"<?php } ?> />
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!--                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">-->
            <!--                        <div class="widget-head"><h4 class="heading">--><?php //__('Redis') ?><!--</h4></div>-->
            <!--                        <div class="widget-body">-->
            <!--                            <table class="table table-bordered">-->
            <!--                                <col width="40%">-->
            <!--                                <col width="60%">-->
            <!--                                <tbody>-->
            <!--                                <tr>-->
            <!--                                    <td class="align_right padding-r10">-->
            <!--                                        --><?php //__('IP') ?>
            <!--                                    </td>-->
            <!--                                    <td>-->
            <!--                                        <input id="redis_1" type="text" name="redis[ip]" value="--><?php //echo $data['redis']['ip']; ?><!--" />-->
            <!--                                    </td>-->
            <!--                                </tr>-->
            <!--                                <tr>-->
            <!--                                    <td class="align_right padding-r10">-->
            <!--                                        --><?php //__('Port') ?>
            <!--                                    </td>-->
            <!--                                    <td>-->
            <!--                                        <input id="redis_2" type="text" name="redis[port]"  value="--><?php //echo $data['redis']['port']; ?><!--"/>-->
            <!--                                    </td>-->
            <!--                                </tr>-->
            <!--                                </tbody>-->
            <!--                            </table>-->
            <!--                        </div>-->
            <!--                    </div>-->

            <!--div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('Script Invoice') ?></h4></div>
                        <div class="widget-body">
                            <table class="table table-bordered">
                                <col width="40%">
                                <col width="60%">

                                <tbody>

                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Generate Invoice From') ?>
                                    </td>
                                    <td>
                                        <select id="script_invoice_1" name="script_invoice[invoice_from_report]" >
                                            <option value="0" <?php
            if ($data['script_invoice']['invoice_from_report'] == 0)
            {
                ?> selected="selected" <?php } ?> ><?php __('Cdr') ?></option>
                                            <option value="1" <?php
            if ($data['script_invoice']['invoice_from_report'] == 1)
            {
                ?> selected="selected" <?php } ?>><?php __('Report') ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Include Origination Billing') ?>
                                    </td>
                                    <td>
                                        <select id="script_invoice_2" name="script_invoice[invoice_did]" >
                                            <option value="0" <?php
            if ($data['script_invoice']['invoice_did'] == 0)
            {
                ?> selected="selected" <?php } ?> ><?php __('No') ?></option>
                                            <option value="1" <?php
            if ($data['script_invoice']['invoice_did'] == 1)
            {
                ?> selected="selected" <?php } ?>><?php __('Yes') ?></option>
                                        </select>
                                    </td>

                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div-->

            <!--                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">-->
            <!--                        <div class="widget-head"><h4 class="heading">--><?php //__('Check Route') ?><!--</h4></div>-->
            <!--                        <div class="widget-body">-->
            <!--                            <table class="table table-bordered">-->
            <!--                                <col width="40%">-->
            <!--                                <col width="60%">-->
            <!---->
            <!--                                <tbody>-->
            <!---->
            <!--                                <tr>-->
            <!--                                    <td class="align_right padding-r10">-->
            <!--                                        --><?php //__('Auto Test IP') ?>
            <!--                                    </td>-->
            <!--                                    <td>-->
            <!--                                        <input id="check_route_1" type="text" name="check_route[check_route_ip]" value="--><?php //echo $data['check_route']['check_route_ip']; ?><!--" />-->
            <!--                                    </td>-->
            <!--                                </tr>-->
            <!--                                </tbody>-->
            <!--                            </table>-->
            <!--                        </div>-->
            <!--                    </div>-->

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('PCAP Archival') ?></h4></div>
                <!--                        --><?php
                //                        if(!empty($pcapData)) {
                //                            echo "<input type='hidden' name='pcap[pcap_id]' value='{$pcapData['id']}' />";
                //                        }
                //                        ?>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <col width="40%">
                        <col width="60%">

                        <tbody>

                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Storage Type') ?>
                            </td>
                            <td>
                                <?php
                                $type = isset($pcapData['storage_type']) ? $pcapData['storage_type'] : 0;
                                ?>
                                <select class="storage_type" name="pcap[storage_type]">
                                    <option value=""></option>
                                    <option value="0" <?php if($type == 0) echo 'selected';?> >Local Storage</option>
                                    <option value="1" <?php if($type == 1) echo 'selected';?> >Remote Storage via SCP</option>
                                    <option value="2" <?php if($type == 2) echo 'selected';?> >FTP</option>
                                    <option value="3" <?php if($type == 3) echo 'selected';?> >Google Drive</option>
                                </select>
                            </td>
                        </tr>

                        <!--             Local Storage               -->
                        <tr class="hidden" data-opt="0">
                            <td class="align_right padding-r10">
                                <?php __('Local Path') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[storage_path]" value="<?php echo $pcapData['storage_path']?>"/>
                            </td>
                        </tr>
                        <!--            END Local Storage               -->

                        <!--             Remote Storage via SCP               -->
                        <tr class="hidden" data-opt="1">
                            <td class="align_right padding-r10">
                                <?php __('Remote Server IP') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[remote_server_ip]" value="<?php echo $pcapData['remote_server_ip']?>"/>
                            </td>
                        </tr>
                        <tr class="hidden" data-opt="1">
                            <td class="align_right padding-r10">
                                <?php __('Remote Server Port') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[remote_server_port]" value="<?php echo $pcapData['remote_server_port']?>"/>
                            </td>
                        </tr>
                        <tr class="hidden" data-opt="1">
                            <td class="align_right padding-r10">
                                <?php __('Storage path') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[storage_path]" value="<?php echo $pcapData['storage_path']?>"/>
                            </td>
                        </tr>
                        <tr class="hidden" data-opt="1">
                            <td class="align_right padding-r10">
                                <?php __('Username') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[username]" value="<?php echo $pcapData['username']?>"/>
                            </td>
                        </tr>
                        <tr class="hidden" data-opt="1">
                            <td class="align_right padding-r10">
                                <?php __('Password') ?>
                            </td>
                            <td>
                                <input type="password" name="pcap[password]" value="<?php echo $pcapData['password']?>"/>
                            </td>
                        </tr>
                        <!--            END Remote Storage via SCP               -->

                        <!--             FTP               -->
                        <tr class="hidden" data-opt="2">
                            <td class="align_right padding-r10">
                                <?php __('FTP IP') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[ftp_ip]" value="<?php echo $pcapData['ftp_ip']?>"/>
                            </td>
                        </tr>
                        <tr class="hidden" data-opt="2">
                            <td class="align_right padding-r10">
                                <?php __('FTP Port') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[ftp_port]" value="<?php echo $pcapData['ftp_port']?>" />
                            </td>
                        </tr>
                        <tr class="hidden" data-opt="2">
                            <td class="align_right padding-r10">
                                <?php __('Storage path') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[storage_path]" value="<?php echo $pcapData['storage_path']?>"/>
                            </td>
                        </tr>
                        <tr class="hidden" data-opt="2">
                            <td class="align_right padding-r10">
                                <?php __('Username') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[username]"  value="<?php echo $pcapData['username']?>"/>
                            </td>
                        </tr>
                        <tr class="hidden" data-opt="2">
                            <td class="align_right padding-r10">
                                <?php __('Password') ?>
                            </td>
                            <td>
                                <input type="password" name="pcap[password]" value="<?php echo $pcapData['password']?>"/>
                            </td>
                        </tr>
                        <!--            END FTP               -->

                        <!--            Google Drive               -->
                        <tr class="hidden" data-opt="3">
                            <td class="align_right padding-r10">
                                <?php __('Google Drive Key') ?>
                            </td>
                            <td>
                                <input type="text" name="pcap[google_drive_key]" value="<?php echo $pcapData['google_drive_key']?>" />
                            </td>
                        </tr>
                        <!--            END Google Drive               -->
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Client Portal') ?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <col width="40%">
                        <col width="60%">

                        <tbody>

                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Enable portal user to change their IP addresses') ?>
                            </td>
                            <td>
                                <select name="portal[change_ip]" >
                                    <option value="0" <?php
                                    if ($data['portal']['change_ip'] == 0)
                                    {
                                        ?> selected="selected" <?php } ?> ><?php __('No') ?></option>
                                    <option value="1" <?php
                                    if ($data['portal']['change_ip'] == 1)
                                    {
                                        ?> selected="selected" <?php } ?>><?php __('Yes') ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Display the Switch\'s SIP IP to user') ?>
                            </td>
                            <td>
                                <select name="portal[show_switch_ip]" >
                                    <option value="0" <?php
                                    if ($data['portal']['show_switch_ip'] == 0)
                                    {
                                        ?> selected="selected" <?php } ?> ><?php __('No') ?></option>
                                    <option value="1" <?php
                                    if ($data['portal']['show_switch_ip'] == 1)
                                    {
                                        ?> selected="selected" <?php } ?>><?php __('Yes') ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r10">
                                <?php __(' Enable user to choose from one or more public products') ?>
                            </td>
                            <td>
                                <select name="portal[build_trunk_from_product]" >
                                    <option value="0" <?php
                                    if ($data['portal']['build_trunk_from_product'] == 0)
                                    {
                                        ?> selected="selected" <?php } ?> ><?php __('No') ?></option>
                                    <option value="1" <?php
                                    if ($data['portal']['build_trunk_from_product'] == 1)
                                    {
                                        ?> selected="selected" <?php } ?>><?php __('Yes') ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Enable portal user to add ingress trunks') ?>
                            </td>
                            <td>
                                <select name="portal[add_ingress]" >
                                    <option value="0" <?php
                                    if ($data['portal']['add_ingress'] == 0)
                                    {
                                        ?> selected="selected" <?php } ?> ><?php __('No') ?></option>
                                    <option value="1" <?php
                                    if ($data['portal']['add_ingress'] == 1)
                                    {
                                        ?> selected="selected" <?php } ?>><?php __('Yes') ?></option>
                                </select>
                            </td>
                        </tr>

                        <!--tr>
                            <td class="align_right padding-r10">
                                <?php __('Enable portal user to add egress trunks') ?>
                            </td>
                            <td>
                                <select name="portal[add_egress]" >
                                    <option value="0" <?php
                                    if ($data['portal']['add_egress'] == 0)
                                    {
                                        ?> selected="selected" <?php } ?> ><?php __('No') ?></option>
                                    <option value="1" <?php
                                    if ($data['portal']['add_egress'] == 1)
                                    {
                                        ?> selected="selected" <?php } ?>><?php __('Yes') ?></option>
                                </select>
                            </td>
                        </tr-->


                        </tbody>
                    </table>
                </div>
            </div>


            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('API Configuration')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <col width="40%">
                        <col width="60%">
                        <tbody>
                        <tr>
                            <td class="align_right padding-r10"><?php __('PCAP URL'); ?></td>
                            <td>
                                <input type="text" class="width-220" id="api_pcap_url" name="api_pcap_url" value="<?php echo $apiUrls['api_pcap_url']; ?>">
                                <a href = "javascript:void(0)" class = "check-connection">
                                    <i class="icon-refresh" aria-hidden="true"></i>
                                </a>
                                <i class="icon-check-minus" area-hidden="true" title="Unconnected">
                                </i>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Invoice URL'); ?></td>
                            <td>
                                <input type="text" class="width-220" id="api_invoice_url" name="api_invoice_url" value="<?php echo $apiUrls['api_invoice_url']; ?>">
                                <a href = "javascript:void(0)" class = "check-connection">
                                    <i class="icon-refresh" aria-hidden="true"></i>
                                </a>
                                <i class="icon-check-minus" area-hidden="true" title="Unconnected">
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Orig Invoice URL'); ?></td>
                            <td>
                                <input type="text" class="width-220" id="api_invoice_orig_url" name="api_invoice_orig_url" value="<?php echo $apiUrls['api_invoice_orig_url']; ?>">
                                <a href = "javascript:void(0)" class = "check-connection">
                                    <i class="icon-refresh" aria-hidden="true"></i>
                                </a>
                                <i class="icon-check-minus" area-hidden="true" title="Unconnected">
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Import URL'); ?></td>
                            <td>
                                <input type="text" class="width-220" id="api_import_url" name="api_import_url" value="<?php echo $apiUrls['api_import_url']; ?>">

                                <a href = "javascript:void(0)" class = "check-connection">
                                    <i class="icon-refresh" aria-hidden="true"></i>
                                </a>
                                <i class="icon-check-minus" area-hidden="true" title="Unconnected">
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('CDR Archival URL'); ?></td>
                            <td>
                                <input type="text" class="width-220" id="cdr_archival_url" name="cdr_archival_url" value="<?php echo $apiUrls['cdr_archival_url']; ?>">

                                <a href = "javascript:void(0)" class = "check-connection">
                                    <i class="icon-refresh" aria-hidden="true"></i>
                                </a>
                                <i class="icon-check-minus" area-hidden="true" title="Unconnected">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bold center"><?php __('Real Time CDR and Reporting'); ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('URL'); ?></td>
                            <td>
                                <input type="text" class="width-220 validate[custom[url]]" id="real_time_and_reporting_url" name="real_time_and_reporting_url" value="<?php echo $apiUrls['real_time_and_reporting_url']; ?>">
                                <a href = "javascript:void(0)" class="check-cdr-connection">
                                    <i class="icon-refresh" aria-hidden="true"></i>
                                </a>
                                <i class="icon-check-minus" area-hidden="true" title="Unconnected">
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Auth port'); ?></td>
                            <td>
                                <input type="text" id="cdr_auth_port" class="width-220 validate[custom[onlyNumber]]" name="cdr_api[auth_port]" value="<?php echo $data['cdr_api']['auth_port'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Sync port'); ?></td>
                            <td>
                                <input type="text" class="width-220 validate[custom[onlyNumber]]" name="cdr_api[sync_port]" value="<?php echo $data['cdr_api']['sync_port'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Async port'); ?></td>
                            <td>
                                <input type="text" class="width-220 validate[custom[onlyNumber]]" name="cdr_api[async_port]" value="<?php echo $data['cdr_api']['async_port'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Aggregation port'); ?></td>
                            <td>
                                <input type="text" class="width-220 validate[custom[onlyNumber]]" name="cdr_api[agg_port]" value="<?php echo $data['cdr_api']['agg_port'] ?>">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Archive Setting') ?></h4></div>
                <!--                    --><?php
                //                    if(!empty($cdrData)) {
                //                        echo "<input type='hidden' name='cdr[cdr_id]' value='{$cdrData['id']}' />";
                //                    }
                //                    ?>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <col width="40%">
                        <col width="60%">
                        <tbody>

                        <!--CDR Archive-->
                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Enable CDR Archive') ?>
                            </td>
                            <td>
                                <?php
                                $type = isset($cdrData['storage_type']) ? $cdrData['storage_type'] : '';
                                ?>
                                <select class="storage_type_1" name="cdr[storage_type]">
                                    <option value="4" <?php if($type == 4) echo 'selected';?> >Store archive in FTP</option>
                                    <option value="5" <?php if($type == 5) echo 'selected';?> > Store archive in Google Cloud Storage</option>
                                </select>
                            </td>
                        </tr>
                        <tr >
                            <td class="align_right padding-r10"><?php __('Archive'); ?></td>
                            <td>
                                <input type="text" class="width-100 numeric" id="storage_days" name="cdr[storage_days]" value="<?php echo isset($cdrData['storage_days']) ? $cdrData['storage_days'] : '';?>"> days of CDR.
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Store'); ?></td>
                            <td>
                                <input type="text" class="width-100 numeric" id="storage_days_local" name="cdr[storage_days_local]" value="<?php echo isset($cdrData['storage_days_local']) ? $cdrData['storage_days_local'] : '';?>"> days of CDR in local.
                            </td>
                        </tr>


                        <tr  class="hidden" data-opt="4">
                            <td class="align_right padding-r10"><?php __('FTP Server'); ?></td>
                            <td>
                                <input type="text" class="width-100 validate[custom[ftp]]" id="ftp_ip" name="cdr[ftp_ip]" value="<?php echo isset($cdrData['ftp_ip']) ? $cdrData['ftp_ip'] : '';?>">
                            </td>
                        </tr>
                        <tr  class="hidden" data-opt="4">
                            <td class="align_right padding-r10"><?php __('FTP Port'); ?></td>
                            <td>
                                <input type="text" class="width-100" id="ftp_port" name="cdr[ftp_port]" value="<?php echo isset($cdrData['ftp_port']) ? $cdrData['ftp_port'] : '';?>">
                            </td>
                        </tr>
                        <tr  class="hidden" data-opt="4">
                            <td class="align_right padding-r10"><?php __('FTP User'); ?></td>
                            <td>
                                <input type="text" class="width-100" id="username" name="cdr[username]" value="<?php echo isset($cdrData['username']) ? $cdrData['username'] : '';?>">
                            </td>
                        </tr>
                        <tr  class="hidden" data-opt="4">
                            <td class="align_right padding-r10"><?php __('FTP Password'); ?></td>
                            <td>
                                <input type="text" class="width-100" id="password" name="cdr[password]" value="<?php echo isset($cdrData['password']) ? $cdrData['password'] : '';?>">
                            </td>
                        </tr>
                        <tr  class="hidden" data-opt="4">
                            <td class="align_right padding-r10"><?php __('FTP Path'); ?></td>
                            <td>
                                <input type="text" class="width-100" id="storage_path" name="cdr[storage_path]" value="<?php echo isset($cdrData['storage_path']) ? $cdrData['storage_path'] : '';?>">
                            </td>
                        </tr>
                        <tr  class="hidden" data-opt="5">
                            <td class="align_right padding-r10"><?php __('Google Project Name'); ?></td>
                            <td>
                                <input type="text" class="width-100" id="google_drive_name" name="cdr[google_drive_name]" value="<?php echo isset($cdrData['google_drive_name']) ? $cdrData['google_drive_name'] : '';?>">
                            </td>
                        </tr>
                        <tr  class="hidden" data-opt="5">
                            <td class="align_right padding-r10"><?php __('Google Token'); ?></td>
                            <td>
                                <input type="text" class="width-100" id="google_drive_key" name="cdr[google_drive_key]" value="<?php echo isset($cdrData['google_drive_key']) ? $cdrData['google_drive_key'] : '';?>">
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Switch Setting') ?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <col width="40%">
                        <col width="60%">
                        <tbody>
                        <tr >
                            <td class="align_right padding-r10"><?php __('LRN Request IP'); ?></td>
                            <td>
                                <select class="storage_type" name="web_switch[lrn_request_ip]">
                                    <option value=""></option>
                                    <?php foreach($public_ip as $ip):?>
                                        <option value="<?php echo $ip; ?>" <?php echo isset($data['web_switch']['lrn_request_ip']) && $data['web_switch']['lrn_request_ip'] == $ip ? 'selected': '';?>><?php echo $ip; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('License IP'); ?></td>
                            <td>
                                <select class="storage_type" name="web_switch[license_ip]">
                                    <option value=""></option>
                                    <?php foreach($public_ip as $ip):?>
                                        <option value="<?php echo $ip; ?>" <?php echo isset($data['web_switch']['license_ip']) && $data['web_switch']['license_ip'] == $ip ? 'selected': '';?>><?php echo $ip; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Live Call Engine IP'); ?></td>
                            <td>
                                  <input id="active_call_ip" class="width220" type="text" name="web_active_call[active_call_ip]" value="<?php echo isset($data['web_active_call']['active_call_ip']) ? $data['web_active_call']['active_call_ip'] : ''; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Live Call Engine Port '); ?></td>
                            <td>
                                  <input id="active_call_port"  class="width220" type="text" name="web_active_call[active_call_port]" value="<?php echo isset($data['web_active_call']['active_call_port']) ? $data['web_active_call']['active_call_port'] : ''; ?>" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="row-fluid">
                <div class="span12 center" >
                    <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit')?>">
                    <input type="reset"  value="<?php __('Revert')?>" class="btn btn-inverse" />
                </div>
            </div>
            </form>

        </div>
    </div>
</div>


<script type="text/javascript">

    function checkDBConnection(id){
        let params_field = ['hostaddr', 'port', 'dbname', 'user', 'password'];
        let params = {};
        let db_field_index = {'db1': 'db', 'db2': 'web_db2'};
        $.each(params_field, function( i, field ) {
           params[field] = $('.'+id).find('input[name="'+db_field_index[id]+'['+field+']"]').val();
           if(id == 'db2'){
               params['hostaddr'] = $('.'+id).find('input[name="web_db2[host]"]').val();
           }
        });
        $.ajax({
            url: "<?php echo $this->webroot; ?>systemparams/checkDBConnection",
            method: 'POST',
            data:  params,
            dataType : "json",
            success: function (response) {
                if(response.status){
                    $('.connect-db-status').removeClass('unconnected').addClass('connected').text(response.msg);
                }else{
                    $('.connect-db-status').removeClass('connected').addClass('unconnected').text(response.msg);
                }
                console.log(response);
            }
        })
    }
    let db_list = ['db1', 'db2'];
    $.each(db_list, function( i, db ) {
        checkDBConnection(db);
    });

    $('.check-db-connection').on('click', function(){
        let db = $(this).closest('tr').attr('data-id');
        checkDBConnection(db);
    })


    function getApiURL(checkId){
        let IDs = checkId || ['api_pcap_url', 'api_invoice_url', 'api_import_url', 'cdr_archival_url', 'real_time_and_reporting_url', 'api_invoice_orig_url'];
        let URLS = {};
        let regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        //let url = '';

        $.each(IDs, function(i, v){
            url = $('#' + v).val().trim();
            if(url){
                URLS[v] = url;
            }
        });

        return URLS;
    }
    function checkAPIConnections(Urls){

        let apiUrls = (typeof Urls !== 'undefined') ? Urls : getApiURL();
        if (apiUrls) {
            $.post('<?php echo $this->webroot; ?>systemparams/checkApiCalls', {apiUrls : apiUrls}, function (response) {
                if(response){
                    $.each(JSON.parse(response), function(id, val){
                        if(val){
                            $('#' + id).closest('td').find('i').last().attr('title', 'Connected').removeClass('icon-check-minus').addClass('icon-check-sign');
                        }else{
                            $('#' + id).closest('td').find('i').last().attr('title', 'Unconnected').removeClass('icon-check-sign').addClass('icon-check-minus');
                        }
                    });
                }
            });
        }

    };
    checkAPIConnections();
    $('.check-connection').on('click', function(){
        let checkId = [$(this).closest('td').find('input').attr('name')];
        let apiUrls = getApiURL( checkId );
        checkAPIConnections(apiUrls);
    })

    function changeStorage(className) {
        let el = $("."+className);
        el.closest('table').find("tr.hidden").css({
            'visibility' : 'hidden',
            'display'    : 'none'
        });
        el.closest('table').find("tr.hidden input").prop('disabled', true);
        if($(el).val() != '') {
            $("tr[data-opt=" + $(el).val() + "]").css({
                'visibility' : 'visible',
                'display'    : 'table-row'
            }).prop('disabled', false);
            $("tr[data-opt=" + $(el).val() + "] input").prop('disabled', false)
        }
    }

    function CheckNum(){
        $('.numeric').keyup(function(e)
        {
            if (/\D/g.test(this.value))
            {
                this.value = this.value.replace(/\D/g, '');
            }
        });
    }

    $(function() {
        $(".storage_type").change(function(){changeStorage("storage_type")}).trigger('change');
        $(".storage_type_1").change(function(){changeStorage("storage_type_1")}).trigger('change');
        changeStorage("storage_type");
        changeStorage("storage_type_1");
        $('#web_base_1').change(function(){
            $(this).val($(this).attr('checked') ? '2' : '0');
        });

        CheckNum();

        $(".check-cdr-connection").click(function () {
            let _this = this;
            let port = $("#cdr_auth_port").val();
            let url = $("#real_time_and_reporting_url").val();

            $.ajax({
                url: "<?php echo $this->webroot; ?>systemparams/checkApiCalls",
                method: 'POST',
                data: {
                    apiUrls: {'realTime': url + ":" + port}
                },
                success: function (response) {
                    let decodedReponse = JSON.parse(response);

                    if (decodedReponse.realTime == true) {
                        $(_this).parent().find('i').last().attr('title', 'Connected').removeClass('icon-check-minus').addClass('icon-check-sign');
                    } else {
                        $(_this).parent().find('i').last().attr('title', 'Unconnected').removeClass('icon-check-sign').addClass('icon-check-minus');
                    }
                }
            })
        });

        $(".check-cdr-connection").each(function (key, item) {
            $(item).click();
        });
    });
</script>
