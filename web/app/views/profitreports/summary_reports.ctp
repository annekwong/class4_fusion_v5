<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>

<div id="title">
    <h1><?php __('Statistics')?>&gt;&gt;<?php echo __('Profitability Analysis',true);?></h1>
</div>
<div id="container">
    <ul class="tabs">
        <li <?php echo !isset($this->params['pass'][0]) || $this->params['pass'][0]==0 ?'class="active"' : '' ?>>
            <a href="<?php echo $this->webroot ?>profitreports/summary_reports">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png">
                <?php __('Origination')?>             
            </a>
        </li>
        <li <?php echo isset($this->params['pass'][0]) && $this->params['pass'][0]==1 ?'class="active"' : '' ?>>
            <a href="<?php echo $this->webroot ?>profitreports/summary_reports/1">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif">
                <?php __('Termination')?>              
            </a>
        </li>
    </ul>
    <?php 
    $size=count($client_org);
    if($size==0){?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php }else{?>
    <?php echo $this->element('report/real_period')?>



    <table class="list nowrap with-fields" style="width: 100%">
        <thead>
            <tr>

                <?php

                //输出分组的字段

                if(!empty($group_by_field_arr)){

                $c=count($group_by_field_arr);

                for ($i=0;$i<$c;$i++){
                $groupby_field=$appCommon->show_order($group_by_field_arr[$i],__($group_by_field_arr[$i],true));
                echo "<td rel='8'> ".$groupby_field ."&nbsp;&nbsp;</td>";


                }

                }?>
                <td class="cset-1" colspan="2"><?php echo $appCommon->show_order('call_duration',__('Call Duration',true)); ?></td>
                <td class="cset-3" colspan="2"><?php echo $appCommon->show_order('profit',__('profit',true)); ?> </td>
                <td class="cset-6 last" colspan="3" style="text-align: center;"><span><?php __('calls')?></span></td>
                <td class="cset-3"><?php echo $appCommon->show_order('ingress_cost',__('Ingress Cost',true)); ?> </td>
                <td class="cset-3"><?php echo $appCommon->show_order('egress_cost',__('Egress Cost',true)); ?> </td>
            </tr>
            <tr>
                <td class="cset-1" rel="0">&nbsp;</td>

                <?php

                //输出分组的字段

                if(!empty($group_by_field_arr)){

                $c=count($group_by_field_arr);

                for ($i=1;$i<$c;$i++){
                echo '<td class="cset-1" rel="0">&nbsp;</td>';

                }

                }?>
                <td class="cset-1" rel="0">  <?php echo __('min',true); ?>  </td>
                <td class="cset-1" rel="1">%</td>

                <td class="cset-3" rel="6"><?php echo $appCommon->show_sys_curr();?></td>
                <td class="cset-3" rel="7">%</td>

                <td width="15%" class="cset-6" rel="13"> <?php echo $appCommon->show_order('total_calls',__('totalofcalls',true)); ?></td>
                <td width="15%" class="cset-6" rel="14"> <?php echo $appCommon->show_order('notzero_calls',__('notzerocall',true)); ?>   </td>
                <td width="15%" class="cset-6" rel="15"> <?php echo $appCommon->show_order('succ_calls',__('Success',true)); ?> 	</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody class="rows">



            <?php  
            $total_ingress_cost = 0;
            $total_egress_cost = 0;
            $total_duration=0;
            $total_duration_per=0;
            $total_profit=0;
            $total_profit_per=0;
            $total_calls=0;
            $total_notzero_calls=0;
            $total_succ_calls=0;
            $size = count ( $client_org );
            for($i = 0; $i < $size; $i ++) {


            $call_duration=$client_org[$i][0]['call_duration'];
            $duration_per=$client_org[$i][0]['call_duration_percentage'];

            $total_duration=$total_duration+$call_duration;
            $total_duration_per=$total_duration_per+$duration_per;



            $profit=$client_org[$i][0]['profit'];
            $total_profit=$total_profit+$profit;
            
            $ingress_cost = $client_org[$i][0]['ingress_cost'];
            $total_ingress_cost += $ingress_cost;
            
            $egress_cost = $client_org[$i][0]['egress_cost'];
            $total_egress_cost += $egress_cost;
            

            $profit_per=$client_org[$i][0]['profit_percentage'];

            $total_profit_per=$total_profit_per+$profit_per;


            $calls=$client_org[$i][0]['total_calls'];

            $notzero_calls=$client_org[$i][0]['notzero_calls'];
            $succ_calls=$client_org[$i][0]['succ_calls'];

            $total_calls=$total_calls+$calls;
            $total_notzero_calls=$total_notzero_calls+$notzero_calls;
            $total_succ_calls=$total_succ_calls+$succ_calls;

            ?>
            <tr class=" row-2" style="color: #4B9100">
                <?php
                //输出分组的字段
                if(!empty($group_by_field_arr)){
                $c=count($group_by_field_arr);
                for ($ii=0;$ii<$c;$ii++){
                $f=$group_by_field_arr[$ii];
                $field=$client_org[$i][0][$f];
                if(trim($field)==''){
                echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:#992F00;'>".__('Unknown',true)."</strong></td>";
                } else{	echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
                }
                }?>

                <td class="in-decimal right"><?php echo number_format($call_duration,2)?></td>
                <td class="in-decimal pos"><?php echo  number_format($duration_per*100,2);?>%</td>

                <td class="in-decimal pos-b"><?php echo number_format($profit,2)?></td>
<!--                <td class="in-decimal pos-b right"><?php echo  number_format($profit_per,2);?>%</td>-->

                <td class="in-decimal pos-b right"><?php echo  round(($ingress_cost-$egress_cost)/$ingress_cost*100, 2);?>%</td>

                <td class="in-decimal"><?php echo $calls?></td>
                <td class="in-decimal"><?php echo $notzero_calls?></td>
                <td class="in-decimal"><?php echo $succ_calls?></td>
                <td class="in-decimal pos-b right"><?php echo $ingress_cost; ?></td>
                <td class="in-decimal pos-b right"><?php echo $egress_cost; ?></td>
            </tr>


            <?php  }?>

            <tr class="totals row-1 row-2">
                <td colspan="<?php  echo $c;  ?>" class="in-decimal"><?php echo __('Total',true);?>:</td>


                <td class="in-decimal right"><?php echo number_format($total_duration,2)?></td>
                <td class="in-decimal pos"><?php echo  number_format($sum_list[0][0]['call_duration_percentage']*100,2);?>%</td>

                <td class="in-decimal pos-b"><?php echo number_format($total_profit,2)?></td>
<!--                <td class="in-decimal pos-b right"><?php echo  number_format($sum_list[0][0]['profit_percentage'],2);?>%</td>-->
                
                
                <td class="in-decimal pos-b right"><?php echo round(($total_ingress_cost-$total_egress_cost)/$total_ingress_cost*100, 2);?>%</td>


                <td class="in-decimal"><?php echo $total_calls?></td>
                <td class="in-decimal"><?php echo $total_notzero_calls?></td>
                <td class="in-decimal"><?php echo $total_succ_calls?></td>
                <td class="in_decimal"><?php echo $total_ingress_cost; ?></td>
                <td class="in_decimal"><?php echo $total_egress_cost; ?></td>
            </tr>

        </tbody>
    </table>
    <?php }?>
    <?php   //echo  $this->element('profit_report/report_amchart')?>
    <?php   echo  $this->element('profit_report/search')?>
    <?php echo $this->element('search_report/search_js_show');?>


</div>
