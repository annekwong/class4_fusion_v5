<style type="text/css">
    .form .value, .list-form .value{text-align:left;}

</style>


<?php
$mydata = $p->getDataArray();
$loop = count($mydata);
if ($show_nodata)
{
    ?>
    <center>
        <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>
    </center>
    <?php
}
else
{
    ?>
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
//                        $order_href = $appCommon->show_order($show_field_array[$ii], $appCdr->format_cdr_field($show_field_array[$ii]));
//
//                        echo "<th rel='8'>&nbsp;&nbsp; " . $order_href . "  &nbsp;&nbsp;</th>";
                        
                        $title = trim($appCdr->format_cdr_field($show_field_array[$ii]));

                                echo "<th rel='8'>" . $title . "</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < $loop; $i++)
                {
                    ?>
                    <tr>
                        <?php
                        for ($ii = 0; $ii < $c; $ii++)
                        {
                            $f = $show_field_array[$ii];
//                            
//                            if($f=='time')
//                            {
//                                $f ='end_time';
//                            }
                            if ($f == 'ingress_client_cost' || $f == 'egress_cost' || $f == 'ingress_client_rate' || $f == 'egress_rate')
                            {
                                $field = $appCommon->currency_rate_conversion($mydata[$i][0][$f]);
                            }
                            elseif ($f == 'egress_erro_string')
                            {
                                // echo $mydata[$i][0][$f].'<br />';
                                $field = $appCommon->convert_error($mydata[$i][0][$f]);
                            }
                            elseif ($f == 'egress_dnis_type')
                            {
                                $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                            }
                            elseif ($f == 'ingress_dnis_type')
                            {
                                $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                            }
                            elseif ($f == 'id')
                            {
                                $status = $appCommon->check_sip_capture_exists($mydata[$i][0]['id']);
                                $fields_arr = array();
                                if ($status['ingress'])
                                {
                                    array_push($fields_arr, '<a title="View Ingress" target="_blank"  href="' . $this->webroot . 'cdrreports/cdr_capture/' . $mydata[$i][0]['id'] . '/ingress"><img src="' . $this->webroot . 'images/view.png"/></a>');
                                    array_push($fields_arr, '<a title="Down Ingress" target="_blank"  href="' . $this->webroot . 'cdrreports/down_sippcap/' . $mydata[$i][0]['id'] . '/ingress"><img src="' . $this->webroot . 'images/export.png"/></a>');

                                    if ($status['ingress_rtp'])
                                    {
                                        array_push($fields_arr, '<a title="Down Ingress RTP" target="_blank"  href="' . $this->webroot . 'cdrreports/down_rtpwav/' . $mydata[$i][0]['id'] . '/ingress"><img src="' . $this->webroot . 'images/wav.png"/></a>');
                                    }
                                }
                                if ($status['egress'])
                                {
                                    array_push($fields_arr, '<a title="View Egress" target="_blank"  href="' . $this->webroot . 'cdrreports/cdr_capture/' . $mydata[$i][0]['id'] . '/egress"><img src="' . $this->webroot . 'images/view.png"/></a>');
                                    array_push($fields_arr, '<a title="Down Egress" target="_blank"  href="' . $this->webroot . 'cdrreports/down_sippcap/' . $mydata[$i][0]['id'] . '/egress"><img src="' . $this->webroot . 'images/export.png"/></a>');

                                    if ($status['egress_rpt'])
                                    {
                                        array_push($fields_arr, '<a title="Down Egress RTP" target="_blank"  href="' . $this->webroot . 'cdrreports/down_rtpwav/' . $mydata[$i][0]['id'] . '/egress"><img src="' . $this->webroot . 'images/wav.png"/></a>');
                                    }
                                }
                                $field = implode('', $fields_arr);
                            }
                            elseif ($f == 'time')  // 8.18 time 字段没有 
                            {
                                $time = substr($mydata[$i][0]['release_tod'], 0, -6);
//                                $field = $time;
                                $field = date('Y-m-d H:i:sO',$time);
//                                $field = $appCommon->cutomer_cdr_field($f, $date);
                            }
                            elseif ($f == 'trunk_type')  // 8.18 time 字段没有 
                            {
                                $field = strcmp($mydata[$i][0]['trunk_type'],'1') ? "Exchange" :"Class4";
//                                $field = $appCommon->cutomer_cdr_field($f, $date);
                            }
                            elseif ($f == 'release_cause')  
                            {
                                $release_cause_arr = $appCdr->show_release_cause();
                                $field = isset($release_cause_arr[$mydata[$i][0]['release_cause']])? $release_cause_arr[$mydata[$i][0]['release_cause']] : "other";
//                                $field = $appCommon->cutomer_cdr_field($f, $date);
                            }
                            else
                            {
                                $field = $appCommon->cutomer_cdr_field($f, $mydata[$i][0][$f]);
                            }
                            if (trim($field) == '')
                            {
                                echo "<td  class='in-decimal'  style='text-align:center;'><strong  style='color:red;'>" . __('Unknown', true) . "</strong></td>";
                            }
                            else
                            {
                                echo " <td  class='in-decimal'  style='text-align:center;white-space:nowrap;overflow:hidden; width:auto;'>" . $field. "</td>";
                            }
                        }
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>


    <?php //if ((isset($_GET['page']) && $_GET['page'] > 0)): ?>
    <div class="row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('page'); ?>
        </div> 
    </div>
    <div class="clearfix"></div>
    <?php //endif ?>
<?php  } ?>


