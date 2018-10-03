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
            <?php echo $this->element("advance_setting/tab",array('active' => 'advance')); ?>
        </div>
        <div class="widget-body">

            <div style="margin:0 auto;">

                <form method="post" id="myform">

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('Database') ?></h4></div>
                        <div class="widget-body">
                            <table class="table table-bordered">
                                <col width="40%">
                                <col width="60%">
                                <tbody>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Database Host') ?>
                                    </td>
                                    <td>
                                        <input id="database_2" type="text" name="db[hostaddr]" value="<?php echo $data['db']['hostaddr']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Database Port') ?>
                                    </td>
                                    <td>
                                        <input id="database_3" type="text" name="db[port]" value="<?php echo $data['db']['port']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Database Name') ?>
                                    </td>
                                    <td>
                                        <input id="database_4" type="text" name="db[dbname]" value="<?php echo $data['db']['dbname']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Database User') ?>
                                    </td>
                                    <td>
                                        <input id="database_5" type="text" name="db[user]" value="<?php echo $data['db']['user']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Database Password') ?>
                                    </td>
                                    <td>
                                        <input id="database_6" type="password" name="db[password]" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('Web Base') ?></h4></div>
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
                                        <select id="web_base_1" name="web_base[debug_level]" >
                                            <option value="0" <?php
                                            if ($data['web_base']['debug_level'] == 0)
                                            {
                                                ?> selected="selected" <?php } ?> ><?php __('disable')?></option>
                                            <option value="2" <?php
                                            if ($data['web_base']['debug_level'] == 2)
                                            {
                                                ?> selected="selected" <?php } ?>><?php __('enable')?></option>
                                        </select>
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
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('System Token') ?>
                                    </td>
                                    <td>
                                        <input id="web_base_4" type="text" name="web_base[system_token]" value="<?php echo $data['web_base']['system_token']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Check Switch License') ?>
                                    </td>
                                    <td>
                                        <input  type="hidden" name="web_base[check_switch_license]" value=""  />
                                        <input id="web_base_3" type="checkbox" name="web_base[check_switch_license]" <?php
                                        if ($data['web_base']['check_switch_license'])
                                        {
                                        ?>checked="checked"<?php } ?> />
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('Path Configuration') ?></h4></div>
                        <div class="widget-body">
                            <table class="table table-bordered">
                                <col width="40%">
                                <col width="60%">
                                <tbody>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('DB Export Path') ?>
                                    </td>
                                    <td>
                                        <input id="web_path_1" type="text" name="web_path[db_export_path]" value="<?php echo $data['web_path']['db_export_path']; ?>" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('US jurisdiction Update') ?></h4></div>
                        <div class="widget-body">
                            <table class="table table-bordered">
                                <col width="40%">
                                <col width="60%">
                                <tbody>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('FTP IP') ?>
                                    </td>
                                    <td>
                                        <input id="us_jur_1" type="text" name="storage_server[ip]" value="<?php echo $data['storage_server']['ip']; ?>" />
                                    </td>
                                </tr>

                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('FTP Username') ?>
                                    </td>
                                    <td>
                                        <input id="us_jur_2" type="text" name="storage_server[ftp_user]" autocomplete=false value="<?php echo $data['storage_server']['ftp_user']; ?>" />
                                    </td>
                                </tr>

                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('FTP Password') ?>
                                    </td>
                                    <td>
                                        <input id="us_jur_3" type="password" name="storage_server[ftp_password]" />
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('Web Feature') ?></h4></div>
                        <div class="widget-body">
                            <table class="table table-bordered">
                                <col width="40%">
                                <col width="60%">
                                <tbody>
                                <!--                                        <tr>-->
                                <!--                                            <td class="align_right padding-r10">-->
                                <!--                                                --><?php //__('Copyright Link') ?>
                                <!--                                            </td>-->
                                <!--                                            <td>-->
                                <!--                                                <input type="hidden" name="web_feature[copyright_link]"  />-->
                                <!--                                                <input id="web_feature_2" type="checkbox" name="web_feature[copyright_link]" --><?php
                                //                                                if ($data['web_feature']['copyright_link'])
                                //                                                {
                                //                                                    ?><!-- checked="checked"--><?php //} ?><!-- />-->
                                <!--                                            </td>-->
                                <!--                                        </tr>-->
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

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('Redis') ?></h4></div>
                        <div class="widget-body">
                            <table class="table table-bordered">
                                <col width="40%">
                                <col width="60%">
                                <tbody>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('IP') ?>
                                    </td>
                                    <td>
                                        <input id="redis_1" type="text" name="redis[ip]" value="<?php echo $data['redis']['ip']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Port') ?>
                                    </td>
                                    <td>
                                        <input id="redis_2" type="text" name="redis[port]"  value="<?php echo $data['redis']['port']; ?>"/>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
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
                    </div>

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('Check Route') ?></h4></div>
                        <div class="widget-body">
                            <table class="table table-bordered">
                                <col width="40%">
                                <col width="60%">

                                <tbody>

                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('Auto Test IP') ?>
                                    </td>
                                    <td>
                                        <input id="check_route_1" type="text" name="check_route[check_route_ip]" value="<?php echo $data['check_route']['check_route_ip']; ?>" />
                                    </td>
                                </tr>
                                <!--                                        <tr>-->
                                <!--                                            <td class="align_right padding-r10">-->
                                <!--                                                --><?php //__('Ani') ?>
                                <!--                                            </td>-->
                                <!--                                            <td>-->
                                <!--                                                <input id="check_route_2" type="text" name="check_route[ani]" value="--><?php //echo $data['check_route']['ani']; ?><!--" /> -->
                                <!--                                            </td>-->
                                <!---->
                                <!--                                        </tr>-->
                                <!---->
                                <!--                                        <tr>-->
                                <!--                                            <td class="align_right padding-r10">-->
                                <!--                                                --><?php //__('Sipp') ?>
                                <!--                                            </td>-->
                                <!--                                            <td>-->
                                <!--                                                <input id="check_route_3" type="text" name="sipp[sipp_exe]" value="--><?php //echo $data['sipp']['sipp_exe']; ?><!--" /> -->
                                <!--                                            </td>-->
                                <!---->
                                <!--                                        </tr>-->

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('PCAP Storage') ?></h4></div>
                        <div class="widget-body">
                            <table class="table table-bordered">
                                <col width="40%">
                                <col width="60%">

                                <tbody>

                                <tr>
                                    <td class="align_right padding-r10">
                                        <?php __('PCAP File Path') ?>
                                    </td>
                                    <td>
                                        <input id="voipmoniter_1" type="text" name="voip_moniter[pcap_path]" value="<?php echo $data['voip_moniter']['pcap_path']; ?>" />
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