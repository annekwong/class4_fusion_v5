<style type="text/css">
    .form .value, .list-form .value{text-align:left;}

    .waiting-div {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 9999;
        height: 100%;
        width: 100%;
        background: rgba(0,0,0, 0.5);
        vertical-align: middle;
        text-align: center;
    }

    .waiting-div div{
        top: 50%;
        position: relative;
        font-size: 24px;
        color: #fff;
    }
</style>

<?php if ($show_nodata): ?>

    <?php $mydata = $p->getDataArray();
    $loop = count($mydata);
    if ($loop == 0 && !(isset($_GET['page']) && $_GET['page'] > 0) && $show_nodata)
    {
        ?>
        <center>
            <div class="msg center">
                <h2><?php echo __('no_data_found', true); ?>
                </h2>
            </div>
        </center>
    <?php }
    else
    { ?>
        <?php if ($loop >= 100 || (isset($_GET['page']) && $_GET['page'] > 0)): ?>

    <?php endif; ?>
        <div class="clearfix"></div>
        <div class="wrapper small">
            <!--            <div class="scroll_div overflow_x" style="margin-top: 30px; max-height:450px;">-->
            <!--                <table class="scroll_table list nowrap with-fields footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">-->

            <table class="table large template table-bordered table-striped table-primary cdr_table" style="table-layout: auto; min-width: 0px;">
                <thead>
                <tr>
                    <?php
                    if(isset($show_field_array[9]) && $show_field_array[9] == 'call_duration') {
                        unset($show_field_array[9]);
                        $show_field_array = array_values($show_field_array);
                    }
                    $c = count($show_field_array);
                    $unneedArray = array('route_id', 'origination_profile_port');
                    for ($ii = 0; $ii < $c; $ii++) {

                        if (!in_array($show_field_array[$ii], $unneedArray)) {
                            $order_href = $appCommon->show_order($show_field_array[$ii], $appCdr->format_cdr_field($show_field_array[$ii]));

                            /*
                              if($show_field_array[$ii]=='ingress_client_cost'||$show_field_array[$ii]=='egress_cost'||$show_field_array[$ii]=='egress_rate'||$show_field_array[$ii]=='ingress_client_rate'){
                              $currency_code=$appCommon->show_sys_curr();
                              }else{
                              $currency_code='';
                              } *
                             */
                            if ($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet') {
                                if ($show_field_array[$ii] == 'id') {
                                    continue;
                                }
                            }
                            echo "<th rel='8'>&nbsp;&nbsp; " . $order_href . "  &nbsp;&nbsp;</th>";
                        }
                    }
                    ?>

<!--                    --><?php //if($_SESSION['login_type'] == 3): ?>
<!--                        <th>Action</th>-->
<!--                    --><?php //endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $show_release_cause_arr = $appCdr->show_release_cause();
                for ($i = 0; $i < $loop; $i++)
                {
                    if(isset($mydata[$i][0]['pcap_field']) && isset($mydata[$i][0]['start_time_of_date']) && isset($mydata[$i][0]['release_tod'])){
                        $tmpField = explode("||", $mydata[$i][0]['pcap_field']);
                        $tmpField[3] = base64_encode($tmpField[3]);
                        $tmpField[4] = urlencode($mydata[$i][0]['start_time_of_date']);
                        $tmpField[5] = urlencode($mydata[$i][0]['release_tod']);
                        $mydata[$i][0]['pcap_field'] = implode("||", $tmpField);
                    }
                    ?>
                    <tr data-value="<?php echo isset($mydata[$i][0]['pcap_field']) ? $mydata[$i][0]['pcap_field'] : ''; ?>" data-simulate="<?php echo isset($mydata[$i][0]['simulate_field']) ? $mydata[$i][0]['simulate_field'] : ''; ?>" data-egress="<?php echo isset($mydata[$i][0]['egress_info_field']) ? $mydata[$i][0]['egress_info_field'] : ''; ?>">
                        <?php
                        for ($ii = 0; $ii < $c; $ii++)
                        {
                            $f = $show_field_array[$ii];
                            if(!in_array($f, $unneedArray)) {
                                if ($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'){
                                    if($f == 'id'){
                                        continue;
                                    }
                                }

                                if($f == 'commission'){
                                    $field = $appCommon->currency_rate_conversion($mydata[$i][0][$f]);
                                }else if($f == 'ingress_client_cost'){
                                    $field = $appCommon->currency_rate_conversion($mydata[$i][0][$f]);
                                }else if ($f == 'ingress_client_cost' || $f == 'egress_cost' || $f == 'ingress_client_rate' || $f == 'egress_rate')
                                {
                                    $field = $appCommon->currency_rate_conversion($mydata[$i][0][$f]);
                                }elseif ($f == 'egress_erro_string'){
                                    // echo $mydata[$i][0][$f].'<br />';
                                    $field = $appCommon->convert_error($mydata[$i][0][$f]);
                                }elseif ($f == 'egress_dnis_type'){
                                    $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                                }elseif ($f == 'ingress_dnis_type'){
                                    $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                                }elseif ($f == 'is_final_call'){
                                    $field = $appCommon->convertBool($mydata[$i][0][$f]);
                                }elseif ($f == 'id'){
                                    $status = $appCommon->check_sip_capture_exists($mydata[$i][0]['id']);
                                    $fields_arr = array();
                                    if ($status['ingress']){
                                        array_push($fields_arr, '<a title="View Ingress" target="_blank"  href="' . $this->webroot . 'cdrreports_db/cdr_capture/' . $mydata[$i][0]['id'] . '/ingress"><img src="' . $this->webroot . 'images/view.png"/></a>');
                                        array_push($fields_arr, '<a title="Down Ingress" target="_blank"  href="' . $this->webroot . 'cdrreports_db/down_sippcap/' . $mydata[$i][0]['id'] . '/ingress"><img src="' . $this->webroot . 'images/export.png"/></a>');

                                        if ($status['ingress_rtp']){
                                            array_push($fields_arr, '<a title="Down Ingress RTP" target="_blank"  href="' . $this->webroot . 'cdrreports_db/down_rtpwav/' . $mydata[$i][0]['id'] . '/ingress"><img src="' . $this->webroot . 'images/wav.png"/></a>');
                                        }
                                    }
                                    if ($status['egress']){
                                        array_push($fields_arr, '<a title="View Egress" target="_blank"  href="' . $this->webroot . 'cdrreports_db/cdr_capture/' . $mydata[$i][0]['id'] . '/egress"><img src="' . $this->webroot . 'images/view.png"/></a>');
                                        array_push($fields_arr, '<a title="Down Egress" target="_blank"  href="' . $this->webroot . 'cdrreports_db/down_sippcap/' . $mydata[$i][0]['id'] . '/egress"><img src="' . $this->webroot . 'images/export.png"/></a>');

                                        if ($status['egress_rpt']){
                                            array_push($fields_arr, '<a title="Down Egress RTP" target="_blank"  href="' . $this->webroot . 'cdrreports_db/down_rtpwav/' . $mydata[$i][0]['id'] . '/egress"><img src="' . $this->webroot . 'images/wav.png"/></a>');
                                        }
                                    }
                                    $field = implode('', $fields_arr);
                                }elseif ($f == 'egress_id as egress_name'){
                                    $field = $appCommon->cutomer_cdr_field('egress_id', $mydata[$i][0]['egress_name']);
                                }elseif ($f == 'ingress_id as ingress_name'){
                                    $field = $appCommon->cutomer_cdr_field('ingress_id', $mydata[$i][0]['ingress_name']);
                                }elseif (in_array($f,array('start_time_of_date','release_tod','answer_time_of_date')) && $this->params['action'] == 'sip_packet'){
//                                    $field = $appCommon->cutomer_cdr_field($f, $mydata[$i][0][$f]);
                                    $field = $mydata[$i][0][$f];
                                }else{
                                    if($f == 'release_cause'){
                                        $field = $appCommon->cutomer_cdr_field_db($f, $mydata[$i][0][$f]);
                                        $field = $show_release_cause_arr[$field];
                                    } else {
                                        $field = $appCommon->cutomer_cdr_field_db($f, $mydata[$i][0][$f]);
                                    }

                                }
                                if (trim($field) == ''){
                                    echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'>" . __('Unknown', true) . "</td>";
                                }
                                else{
                                    Configure::load('myconf');
                                    $wav_url = Configure::read('cloud_shark.cloud_api');

                                    if ($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'){
                                        if($f == 'origination_call_id'){
                                            echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>";
                                            echo $field."&nbsp;";
                                            echo " <a target='_bank' href='".$this->webroot."cdrreports_db/get_sip?switch_ip=".urlencode($mydata[$i][0]['origination_destination_host_name'])."&switch_port=".base64_encode($mydata[$i][0]['origination_profile_port'])."&call_id=".base64_encode($field)."&start_time=".urlencode($mydata[$i][0]['start_time_of_date'])."&end_time=".urlencode($mydata[$i][0]['release_tod'])."&duration=".urlencode($mydata[$i][0]['orig_call_duration'] + 60)."' >SIP</a>&nbsp;";
                                            /**
                                            echo "<a href='javascript:void(0);' onclick=OpenWin('".base64_encode($wav_url."".urlencode($mydata[$i][0]['origination_destination_host_name'])."/".$field."/callee")."') >Callee</a>&nbsp;";
                                            echo "<a href='javascript:void(0);'  onclick=OpenWin('".base64_encode($wav_url."".urlencode($mydata[$i][0]['origination_destination_host_name'])."/".$field."/caller")."') >Caller</a>&nbsp;";
                                            echo "<a href='javascript:void(0);'  onclick=OpenWin('".base64_encode($wav_url."".urlencode($mydata[$i][0]['origination_destination_host_name'])."/".$field."/ringtone")."') >Ringtone</a>&nbsp;";

                                             **/




                                            echo "</td>";
                                        }else if($f == 'termination_call_id'){
                                            echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>";
                                            echo $field."&nbsp;";
                                            echo " <a target='_bank' href='".$this->webroot."cdrreports_db/get_sip?switch_ip=".urlencode($mydata[$i][0]['origination_destination_host_name'])."&switch_port=".base64_encode($mydata[$i][0]['origination_profile_port'])."&call_id=".base64_encode($field)."&start_time=".urlencode($mydata[$i][0]['start_time_of_date'])."&end_time=".urlencode($mydata[$i][0]['release_tod'])."&duration=".urlencode($mydata[$i][0]['orig_call_duration'])."' >SIP</a>&nbsp;";
                                            // echo "<a target='_bank' href='".$this->webroot."cdrreports_db/get_sip/".base64_encode($mydata[$i][0]['id'])."/1/egress?duration=".urlencode($mydata[$i][0]['orig_call_duration'])."&switch_ip=".urlencode($mydata[$i][0]['origination_destination_host_name'])."&termination_call_id=".base64_encode($field)."&time=".urlencode($mydata[$i][0]['time'])."' >SIP</a>&nbsp;";
                                            /**
                                            echo "<a href='javascript:void(0);'  onclick=OpenWin('".base64_encode($wav_url.urlencode($mydata[$i][0]['origination_destination_host_name'])."/".$field."/callee")."') >Callee</a> &nbsp;";
                                            echo "<a href='javascript:void(0);' onclick=OpenWin('".base64_encode($wav_url."".urlencode($mydata[$i][0]['origination_destination_host_name'])."/".$field."/caller")."') >Caller</a> &nbsp;";
                                            echo "<a href='javascript:void(0);'  onclick=OpenWin('".base64_encode($wav_url."".urlencode($mydata[$i][0]['origination_destination_host_name'])."/".$field."/ringtone")."') >Ringtone</a> &nbsp;";
                                             **/
                                            echo "</td>";
                                        }else{
                                            echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>" . $field . "</td>";
                                        }
                                    }else{
                                        echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>" . $field . "</td>";
                                    }
                                }
                             }

                        }
                      ?>
<!--                    --><?php //if($_SESSION['login_type'] == 3): ?>
<!--                        <td>-->
<!--                            <a class="cloudshark view_pcap" href="javascript:void(0);" title="Show PCAP" >-->
<!--                                <i class="icon-bullseye"></i>-->
<!--                            </a>-->
<!--                        </td>-->
<!--                    --><?php //endif; ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="separator"></div>
        </div>


        <?php if ($loop >= 100 || (isset($_GET['page']) && $_GET['page'] > 0)): ?>
        <div class="separator"></div>
        <div class="row-fluid">
            <div class="pagination pagination-large pagination-right margin-none">
                <?php echo $this->element('page'); ?>
            </div>
        </div>

    <?php endif ?>
    <?php } ?>

<?php endif; ?>


<?php

Configure::load('myconf');
$lis_url = Configure::read('cloud_shark.listen');


?>

<script>


    function OpenWin(url){
        //alert(url);
        window.open ("<?php echo $lis_url;?>?answer="+url, "_blank", "height=200, width=800, toolbar= no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=100,left=300")
    }




</script>

<script type="text/javascript">
    $(function(){
        var $cdrTable = $("table.cdr_table");
        $cdrTable.floatThead({
            //the pageTop is a global function i have here, it takes care of making the table float under my floated nav
            top: function(){
                return $(".navbar").height();
            },
            scrollContainer: function($table){
                return $table.closest('.wrapper');
            },
            position: 'absolute'
        });
    });
</script>
<a href="#MyModalGetSIP" class="hide" id="GetSIP" data-toggle="modal"></a>
<div id="MyModalGetSIP" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Get PCAP'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>
</div>


<a href="#MyModalGetSimulate" class="hide" id="GetSimulate" data-toggle="modal"></a>
<div id="MyModalGetSimulate" class="modal hide" style="width: 1000px; margin-left: -500px;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Show Actual Failover'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>
</div>


<a href="#MyModalGetEgress" class="hide" id="GetEgress" data-toggle="modal"></a>
<div id="MyModalGetEgress" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Show Detail Trunk Choices'); ?></h3>
    </div>
    <div class="modal-body">
        <table class="list  table table-striped table-bordered  table-white table-primary">
            <thead>
            <tr>
                <th><?php __('Egress Trunk'); ?></th>
                <th><?php __('Rate'); ?></th>
                <th><?php __('Status'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="egress_trunk_td"></td>
                <td class="egress_rate_td"></td>
                <td class="status_td"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>
</div>

<div class="waiting-div">
    <div>Wait please, we generate url...</div>
</div>

<?php if ($_SESSION['login_type'] == 1): ?>
<style type="text/css">
    .smart_menu_box{display:none; width:200px; position:absolute; z-index:201105;}
    .smart_menu_body{padding:1px; border:1px solid #B8CBCB; background-color:#fff; -moz-box-shadow:2px 2px 5px #666; -webkit-box-shadow:2px 2px 5px #666; box-shadow:2px 2px 5px #666;}
    .smart_menu_ul{margin:0; padding:0; list-style-type:none;}
    .smart_menu_li{position:relative;}
    .smart_menu_a{display:block; height:25px; line-height:24px; padding:0 5px 0 25px; color:#000; font-size:12px; text-decoration:none; overflow:hidden;}
    .smart_menu_a:hover, .smart_menu_a_hover{background-color:#348CCC; color:#fff; text-decoration:none;}
    .smart_menu_li_separate{line-height:0; margin:3px; border-bottom:1px solid #B8CBCB; font-size:0;}
    .smart_menu_triangle{width:0; height:0; border:5px dashed transparent; border-left:5px solid #666; overflow:hidden; position:absolute; top:7px; right:5px;}
    .smart_menu_a:hover .smart_menu_triangle, .smart_menu_a_hover .smart_menu_triangle{border-left-color:#fff;}
    .smart_menu_li_hover .smart_menu_box{top:-1px; left:130px;}
    /*.smart_menu_a:hover, .smart_menu_a_hover{background-color:#7faf00;}*/
</style>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery-smartMenu.js"></script>
<script type="text/javascript">
    var account_type = '<?php echo $_SESSION['login_type'];?>';
    function showPACPData(obj,type){
        var data = obj.data('value');
        $("#MyModalGetSIP").find('.modal-body').html('');
        get_sip_from_cdr_list(data, type);
    }
    if(account_type == 3){
        var imageMenuData = [
            [{
                text: "<?php __('Show Ingress PCAP'); ?>",
                func: function() {
                    showPACPData($(this),2);
                }
            }]
        ];
    }else{
        var imageMenuData = [
            [{
                text: "<?php __('Show Egress PCAP'); ?>",
                func: function() {
                    showPACPData($(this),1);
                }
            }, {
                text: "<?php __('Show Ingress PCAP'); ?>",
                func: function() {
                    showPACPData($(this),2);
                }
            }, {
                text: "<?php __('Show Detail Trunk Choices'); ?>",
                func: function() {
                    var egress_data = $(this).data('egress');
                    $("#MyModalGetEgress").find('.modal-body').find("tbody td").html('');
                    var egressArr = egress_data.toString().split('||');
                    $("#MyModalGetEgress").find(".egress_trunk_td").html(egressArr[0]);
                    $("#MyModalGetEgress").find(".egress_rate_td").html(egressArr[1]);
                    $("#MyModalGetEgress").find(".status_td").html(egressArr[2]);
                    $("#GetEgress").click();

                }
            }, {
                text: "<?php __('Show Actual Failover'); ?>",
                func: function() {
                    var simulate_data = $(this).data('simulate');
                    $("#MyModalGetSimulate").find('.modal-body').html('');
                    $("#GetSimulate").click();
                    $("#MyModalGetSimulate").find('.modal-body').load('<?php echo $this->webroot; ?>cdrreports_db/get_simulate_from_cdr',{'search':simulate_data});
                }
            }]
        ];
    }
    $("table.cdr_table tbody tr").smartMenu(imageMenuData, {
        'textLimit':50
    });

    function get_sip_from_cdr_list(data, type){
          $.ajax({
             type: "POST",
             url: "<?php echo $this->webroot; ?>cdrreports_db/get_sip_from_cdr_list",
             data: {'type': type,'search':data},
             dataType:'JSON',
             success: function(result) {
                 if (typeof result.status !='undefined' && !result.status)
                 {
                     jGrowl_to_notyfy(result.data, {theme: 'jmsg-error'});
                 } else {
                     $("#MyModalGetSIP").find('.modal-body').html(result.data);

                 }
             }
          });
    }

    function explode( delimiter, string ) {	// Split a string by string
        //
        // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   improved by: kenneth
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

        var emptyArray = { 0: '' };

        if ( arguments.length != 2
            || typeof arguments[0] == 'undefined'
            || typeof arguments[1] == 'undefined' )
        {
            return null;
        }

        if ( delimiter === ''
            || delimiter === false
            || delimiter === null )
        {
            return false;
        }

        if ( typeof delimiter == 'function'
            || typeof delimiter == 'object'
            || typeof string == 'function'
            || typeof string == 'object' )
        {
            return emptyArray;
        }

        if ( delimiter === true ) {
            delimiter = '1';
        }

        return string.toString().split ( delimiter.toString() );
    }

    var refreshIntervalId = -1;
    var outData = [];

    function refreshPcap() {
        $.ajax({
            'url': '<?php echo $this->webroot ?>cdrreports_db/refreshResult',
            'type': 'post',
            'data': outData,
            'dataType': 'json',
            'success': function (data) {
                $("p.process_msg").html(data.data.msg);
                if (data.self_status == 1) {
                    clearInterval(refreshIntervalId);
                    $(".waiting-div").hide();
                    window.open("http://<?php echo $cloudSharkUrl; ?>/view?url=" + data.download_url, "_blank");
                }
            }
        });
    }

    $(".view_pcap").click(function () {

        $(".waiting-div").show();

        let value = $(this).parent().parent().data('value');
        let arrayValue = explode('||', value);

        let ajaxData = {
            'call_id': arrayValue[0],
            'switch_ip': arrayValue[2],
            'switch_port': arrayValue[3],
            'start_time': arrayValue[4],
            'end_time': arrayValue[5],
            'duration': arrayValue[6]
        };
        $.ajax({
            'url' : '<?php echo $this->webroot ?>cdrreports_db/ajax_get_sip',
            'type' : 'POST',
            'data' : ajaxData,
            'dataType' : 'json',
            'success' : function(data) {
                outData = data;
                if (data.self_status == 1){
                    $(".waiting-div").hide();
                    window.open("http://<?php echo $cloudSharkUrl; ?>/view?url=" + data.download_url, "_blank");
                } else if (data.self_status == 2) {
                    refreshIntervalId = setInterval(refreshPcap, 2000);
                }
            }
        });

    });
</script>
<?php endif; ?>
