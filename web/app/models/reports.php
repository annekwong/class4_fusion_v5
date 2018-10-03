<?php

class Reports extends AppModel {

    var $name = 'Reports';
    var $useTable = FALSE;


    public function get_summary_report_header($show_arr,$group_count = 0, $type = 1)
    {
        $array1 = array(
            __('ABR',true),
            __('ASR',true),
            __('ACD',true),
            __('ALOC',true),
            __('PDD',true),
            __('NER',true),

        );
        $array2 = array(
            __('NPR Count',true),
            __('NRF Count',true),
            __('NRF',true),
            __('Revenue',true),
            __('Profit',true),
            __('Margin',true),
            __('PP Min',true),
            __('PP K Calls',true),
        );
        $array3 = array(
            __('SD Count',true),
            __('SDP',true),
            __('Limited',true),
            __('Actual Time',true),
            __('Billable Time',true),
            __('Total Cost',true),
            __('Inter Cost',true),
            __('Intra Cost',true),
        );
        $array4 = array(
            __('Total Calls',true),
            __('Not Zero Calls',true),
            __('Success Calls',true),
            __('Busy Calls',true),
        );
        $head_arr = array_merge($array1,$array3);
        if ($type == 1){
            $head_arr = array_merge($array1,$array2,$array3);
        }

        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
            array_push($head_arr,__('Actual Rate',true));
        else
            array_push($head_arr,__('Avg Rate',true));
        $array = array_merge($head_arr,$array4);

        $return_arr = array();
        foreach ($show_arr as $show_key)
        {
            $tmp_key = $show_key - $group_count;
            if (array_key_exists($tmp_key,$array))
                array_push($return_arr,$array[$tmp_key]);
        }
        return $return_arr;
    }


    public function get_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name){
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups) && !$is_user) {
            $filter_client = $this->get_user_limit_filter();
        }
        else {
            $filter_client = '';
        }
        if ($_SESSION['login_type'] == 2) {
            $agent_id = (int) $_SESSION['sst_agent_info']['Agent']['agent_id'];
            $filter_client = " and (exists(SELECT 1 FROM agent_clients WHERE agent_id = {$agent_id} AND client_id={$table_name}.ingress_client_id))";
        }

        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,$table_name.'2%');

        if ($type == 1){
            $org_sql ='';
            if(!empty($fields)){
                $filter_fields_arr = array('duration','ingress_bill_time','ingress_call_cost','lnp_cost','ingress_total_calls','
not_zero_calls','ingress_success_calls','ingress_busy_calls','
lrn_calls','pdd','ingress_cancel_calls','ingress_call_cost_intra','ingress_call_cost_inter','q850_cause_count','release_cause');
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }
            foreach($date_arr as $value){
                $table_date = $table_name.$value;

                $union = "";

                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields} duration,ingress_bill_time,ingress_call_cost,egress_call_cost,lnp_cost,ingress_total_calls,
not_zero_calls,ingress_success_calls,ingress_busy_calls,npr_count,
lrn_calls,pdd,ingress_cancel_calls,ingress_call_cost_intra,ingress_call_cost_inter,q850_cause_count,release_cause
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}'
{$filter_client}
{$wheres}

";
                $org_sql = str_replace($table_name.".", $table_date.".", $org_sql);

            }

            $sql = "SELECT
{$out_fields}
sum(duration) as duration,
sum(ingress_bill_time) as bill_time,
sum(ingress_call_cost) as call_cost,
sum(egress_call_cost) as egress_cost,
sum(lnp_cost) as lnp_cost,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls,
sum(ingress_busy_calls) as busy_calls,
sum(lrn_calls) as lrn_calls,
sum(pdd) as pdd,
sum(ingress_cancel_calls) as cancel_calls,
sum(ingress_call_cost_intra) as inter_cost,
sum(ingress_call_cost_inter) as intra_cost,
sum(q850_cause_count) as q850_cause_count,
sum(npr_count) as npr_value,
sum(case when duration <= 12 then ingress_total_calls else 0 end) as sd_count,
sum(CASE WHEN release_cause = 13 THEN ingress_total_calls ELSE 0 END) AS nrf_count,
sum(CASE WHEN release_cause = 6 THEN ingress_total_calls ELSE 0 END) AS call_limit,
sum(CASE WHEN release_cause = 7 THEN ingress_total_calls ELSE NULL END) AS cps_limit
from  ( {$org_sql} ) as t1
{$groups} {$orders}";
        } else if($type == 2) {
            $org_sql ='';
            if(!empty($fields)){
                $filter_fields_arr = array('duration','egress_bill_time','egress_call_cost','egress_total_calls','not_zero_calls','egress_success_calls','egress_busy_calls','
pdd','egress_cancel_calls','egress_call_cost_inter','egress_call_cost_intra','q850_cause_count','release_cause');
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }
            foreach($date_arr as $value){
                $table_date = $table_name.$value;

                $union = "";
                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields} duration,egress_bill_time,egress_call_cost,egress_total_calls,not_zero_calls,egress_success_calls,egress_busy_calls,
pdd,egress_cancel_calls,egress_call_cost_inter,egress_call_cost_intra,q850_cause_count,release_cause
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}'
{$filter_client}
{$wheres}

";
                $org_sql = str_replace($table_name.".", $table_date.".", $org_sql);

            }

            $sql = "SELECT
{$out_fields}
sum(duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_call_cost) as call_cost,
sum(egress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(egress_success_calls) as success_calls,
sum(egress_busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(egress_cancel_calls) as cancel_calls,
sum(egress_call_cost_inter) as inter_cost,
sum(egress_call_cost_intra) as intra_cost,
sum(q850_cause_count) as q850_cause_count,
sum(case when not_zero_calls <= 12 then egress_total_calls else 0 end) as sd_count,
sum(CASE WHEN release_cause = 6 THEN egress_total_calls ELSE 0 END) AS call_limit,
sum(CASE WHEN release_cause = 7 THEN egress_total_calls ELSE NULL END) AS cps_limit
from (
{$org_sql}
) as t1  {$groups} {$orders}";
        }
        return $sql;
    }
}

?>
