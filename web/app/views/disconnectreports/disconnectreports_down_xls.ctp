<?php 
$size=count($client_org);
if($size==0){?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
<?php if(!empty($show_subgroups)){
$pre_field='';
$curr_field='';
$next_field='';
$field_name=$group_by_field_arr[0];
$curr_field=$client_org[0][0][$field_name];
$pre_field=$curr_field;

//分组字段头
$groupby_head='';
$total_disconnect=0;
$c=count($group_by_field_arr);
for ($ii=0;$ii<$c;$ii++){
$field_name=$group_by_field_arr[$ii];
$curr_field=$client_org[$ii][0][$field_name];
if(empty($curr_field)||$curr_field==''){
$groupby_head=$groupby_head.  __($field_name,true).": <span>".__('Unknown',true)."</span><br>";
}else{
$groupby_head=$groupby_head.  __($field_name,true).": <span>".$curr_field."</span><br>";
}

}
$head_div=" <div class='group-title'>".$groupby_head."</div>";

$table_head="<table class='list'  ><thead><tr>
            <td width='10%' rel='0' >&nbsp; sip Code &nbsp;</td>
            <td width='10%' rel='1' >Count</td>
            <td width='10%' rel='2' class='cset-1'>&nbsp;%  &nbsp;</td>
            <td width='30%' rel='1' ></td>
            <td width='40%' rel='1' >Cause</td>
        </tr></thead><tbody class='orig-calls'>" ;
        $for_tr='';//循环输出的tr
        $sub_tr='';//子统计的tr
        $table_footer="</tbody></table>";


//输出头
echo $head_div,$table_head;


$size=count($client_org);


//计算断开码百分比
$total_disconnect_array=array();
for($k=0;$k<$size;$k++){
$disconnect=(empty($client_org[$k][0]['disconnect']))?0:($client_org[$k][0]['disconnect']);
if($k>0){
//取最后1个分组条件
$field_name=$group_by_field_arr[$c-1];
$curr_field=$client_org[$k][0][$field_name];//上一个分组值
$pre_field=$client_org[$k-1][0][$field_name];//当前分组值
}else{
$total_disconnect=0;
$field_name=$group_by_field_arr[$c-1];
$curr_field=$client_org[0][0][$field_name];//上一个分组值
$pre_field=$curr_field;//当前分组值
}

if($curr_field==$pre_field){
$total_disconnect=$total_disconnect+$client_org[$k][0]['disconnect'];
}else{
$total_disconnect_array[$k-1]=$total_disconnect;
$total_disconnect=$client_org[$k][0]['disconnect'];
}

}

//循环输出表格
for ($i=0;$i<$size;$i++){

$groupby_head='';
//生成分组字段td
for ($ii=0;$ii<$c;$ii++){
$field_name=$group_by_field_arr[$ii];
$curr_field=$client_org[$i][0][$field_name];
$curr_field=(empty($curr_field)||$curr_field=='')?(__('Unknown',true)):$curr_field;
$groupby_head=$groupby_head.  __($field_name,true).": <span>".$curr_field."</span><br>";
}

$disconnect=$client_org[$i][0]['disconnect'];
$disconnect=(empty($disconnect)||$disconnect=='0')?0:$client_org[$i][0]['disconnect'];

//找不到就继续向后找
if(!isset($total_disconnect_array[$i])){
for($t=$i;$t<$size;$t++){
if(!empty($total_disconnect_array[$t])){
$total_disconnect=	$total_disconnect_array[$t];
break;
}

}
}else{

$total_disconnect=	$total_disconnect_array[$i];
}
$per_dis=($total_disconnect=='0')?0:($disconnect/$total_disconnect*100);
//循环的表格
$for_tr="	<tr class=' row-2'   style='color: #4B9100'>
    <td class='in-decimal'>".$client_org[$i][0]['release_cause_from_protocol_stack'] ." </td> 
    <td class='in-decimal'>" .number_format($disconnect, 0)." </td>
    <td class='in-decimal'>".number_format($per_dis, 2)."%</td>
    <td class='in-decimal'>
        <div class='bar'><div style='font-size:0.9em;text-align:left;".number_format($per_dis, 2)."%;'> ".number_format($per_dis, 2)." %&nbsp;</div></div></td>
    <td class='in-decimal'>".$client_org[$i][0]['release_cause'] ." </td> 
</tr>";
if($i>0){
//取最后1个分组条件
$field_name=$group_by_field_arr[$c-1];
$curr_field=$client_org[$i][0][$field_name];//上一个分组值
$pre_field=$client_org[$i-1][0][$field_name];//当前分组值
}else{

$field_name=$group_by_field_arr[$c-1];
$curr_field=$client_org[0][0][$field_name];//上一个分组值
$pre_field=$curr_field;//当前分组值
}

if($curr_field!=$pre_field){
//只要当前和前一个不相等  就生成子统计tr
echo  $table_footer;
$head_div=" <div class='group-title'>".$groupby_head."</div>";
echo $head_div,$table_head;
echo $for_tr;

}else{
//如果当前和上一个相等就统计一次
echo $for_tr;
}
if($i==$size-1){
echo  $table_footer;
}
}
}?>
<?php if(!empty($show_comm)){?>
<table class="list nowrap with-fields"  style="width: 100%">
    <thead>
        <tr>
            <?php
            //输出分组的字段
            $c=0;
            if(!empty($group_by_field_arr)){
            $c=count($group_by_field_arr);

            for ($i=0;$i<$c;$i++){
            if($group_by_field_arr[$i]=='term_country'){
            continue;
            }
            echo " <td rel='8'  width='6%'>&nbsp;&nbsp;&nbsp;&nbsp; ".$appCommon->show_order($group_by_field_arr[$i],__($group_by_field_arr[$i],true))."  &nbsp;&nbsp;</td>";
            }

            }?>
            <td width="10%" rel="0" >Return Code</td>
            <td width="10%" rel="2" class="cset-1">Reason;</td>
            <td width="10%" rel="0" >Counts</td>
            <td width="10%" rel="2" class="cset-1">Counts(%)</td>
            <?php if($rate_type=='org'){?>
            <td width="30%"  class='last' rel="2" class="cset-1">Description</td>
            <?php }?>
        </tr>
    </thead>
    <tbody >
        <?php  
        $size=count($client_org);
        $total_disconnect=0;
        if($size>0){
        for ($i=0;$i<$size;$i++){
        $total_disconnect=$total_disconnect+$client_org[$i][0]['disconnect'];
        }
        }

        for ($i=0;$i<$size;$i++){
        $group_link_arr=array();
        if(empty($client_org[$i][0]['release_cause_from_protocol_stack'])){
        continue;
        }
        $total_per=0;
        if($total_disconnect=='0'){echo $total_per;}else{$total_per=$client_org[$i][0]['disconnect']/$total_disconnect;} 
        ?>
        <tr class=" row-<?php echo $i%2+1?>"   style="color: #4B9100">
            <?php
            //输出分组的字段
            if(!empty($group_by_field_arr)){
            $c=count($group_by_field_arr);

            for ($ii=0;$ii<$c;$ii++){
            $f=$group_by_field_arr[$ii];

            $field=$client_org[$i][0][$f];
            $group_link_arr[$f]=$field;
            if(trim($field)==''){
            echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</td>";
            }else{
            echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
            }


            }?>
            <td class="in-decimal"><?php  echo  $client_org[$i][0]['release_cause_from_protocol_stack']; ?> </td>
            <td class="in-decimal"><?php  echo  strtoupper($client_org[$i][0]['cause']);?></td>
            <td class="in-decimal">
                <?php 
                if($rate_type=='org'){
              echo  $client_org[$i][0]['release_cause'];
                }
                else
                {
               echo  $client_org[$i][0]['release_cause'];
                }
                ?>

            </td>
            <td class="in-decimal"><?php echo number_format($client_org[$i][0]['my_count']/$client_org[$i][0]['all_count']*100,3) ?>%</td>
            <?php if($rate_type=='org'){?>
            <td class="in-decimal"><?php  echo  $client_org[$i][0]['release_cause']; ?> </td>
            <?php }?>
        </tr>
        <?php }?>
    </tbody>
</table>
<?php }}?>