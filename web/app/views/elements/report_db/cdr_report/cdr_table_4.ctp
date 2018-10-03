<style type="text/css">
    .form .value, .list-form .value{text-align:left;}
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
        <div class="scroll_div" style="overflow: auto; margin-top: 30px;">
            <table  class="scroll_table list nowrap with-fields footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <?php
                        $c = count($show_field_array);
                        //$currency_code='';
                        for ($ii = 0; $ii < $c; $ii++)
                        {
                            $order_href = $appCommon->show_order($show_field_array[$ii], $appCdr->format_cdr_field($show_field_array[$ii]));

                            /*
                              if($show_field_array[$ii]=='ingress_client_cost'||$show_field_array[$ii]=='egress_cost'||$show_field_array[$ii]=='egress_rate'||$show_field_array[$ii]=='ingress_client_rate'){
                              $currency_code=$appCommon->show_sys_curr();
                              }else{
                              $currency_code='';
                              } *
                             */
                            echo "<th rel='8'>&nbsp;&nbsp; " . $order_href . "  &nbsp;&nbsp;</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < $loop; $i++)
                    {
                        ?>
                        <tr style="color: #4B9100">
                            <?php
                            for ($ii = 0; $ii < $c; $ii++)
                            {
                                $f = $show_field_array[$ii];
                                
                                if($f == 'origination_call_id'){
                             
                                    $field = "<a href='javascript:void(0);' onclick=\"get_all('{$mydata[$i][0][$f]}','{$mydata[$i][0]['time']}',this)\"  >{$mydata[$i][0][$f]}</a>";
                                    //$field = $appCommon->currency_rate_conversion($mydata[$i][0][$f]);
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
                                }else{
                                    //echo 'A'.'<br />';
                                    $field = $appCommon->cutomer_cdr_field_db($f, $mydata[$i][0][$f]);
                                }
                                if (trim($field) == ''){
                                    echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>" . __('Unknown', true) . "</strong></td>";
                                }
                                else{
                                    echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>" . $field . "</td>";
                                }
                            }
                            ?>
                        </tr>
        <?php } ?>
                </tbody>
            </table>
        </div>


        <?php if ($loop >= 100 || (isset($_GET['page']) && $_GET['page'] > 0)): ?>
            <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('page'); ?>
                </div> 
            </div>

        <?php endif ?>
    <?php } ?>

<?php endif; ?>

        <script>
        
        
        function get_all(call_id,time,obj){
	if($(obj).parents('tr').eq(0).next().find('table').length==0){
                        $.ajax({
                            'url':"<?php echo $this->webroot;?>cdrreports_db/get_result",
                            'type':'post',
                            'dataType':'html',
                            'data':{'call_id':call_id,'time':time},
                            'beforeSend':function(){
                                $(obj).hide();
                                $(obj).parents('td').eq(0).append("<img src='<?php echo $this->webroot;?>images/check_waiting.gif' />")
                            },
                            'success':function(data){
                                $(obj).parents('tr').eq(0).after("<tr><td colspan='6'>"+data+"</td></tr>");
                                $(obj).parents('td').eq(0).find('img').remove();
                                $(obj).show();
                            }
                        });
	}else{
                        $(obj).parents('tr').eq(0).next().remove();
	}
            }
        
        
        </script>
