<?php

class DidReport extends DidAppModel
{
    var $useTable = 'did_report';
    var $primaryKey = 'id';

    private function dateRange($first, $last, $step = '+1 day', $output_format = 'Ymd')
    {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    public function getData($start, $end, $gmt, $fields = null, $group = null, $where = '')
    {
        $dates = $this->dateRange(date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end)));
        $sql = "";
        $fields = implode(',', $fields);
        $group = implode(',', $group);

        foreach ($dates as $key => $value) {
            $table = $this->useTable . $value;
            $union = "";

            if (!empty($sql)) {
                $union = " union all ";
            }
            $sql .= <<<EOD
    {$union}  
    SELECT *                       
    FROM {$table}  
    WHERE report_time BETWEEN '{$start} {$gmt}' AND '{$end} {$gmt}' {$where}
EOD;
        }

        $sql = <<<SQL
SELECT 
{$fields},
sum(duration) as duration,
sum(ingress_bill_time) as ingress_bill_time,
sum(ingress_call_cost) as ingress_call_cost,
sum(ingress_total_calls) as ingress_total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as ingress_success_calls,
sum(egress_bill_time) as egress_bill_time,
sum(egress_call_cost) as egress_call_cost,
sum(egress_total_calls) as egress_total_calls,
sum(egress_success_calls) as egress_success_calls,
sum(ingress_busy_calls) as ingress_busy_calls,
sum(egress_busy_calls) as egress_busy_calls,
sum(ingress_cancel_calls) as ingress_cancel_calls,
sum(egress_cancel_calls) as egress_cancel_calls
FROM ({$sql}) as t1
LEFT JOIN resource as vendorResource ON vendorResource.resource_id = ingress_id
LEFT JOIN resource as clientResource ON clientResource.resource_id = egress_id
GROUP by {$group}
SQL;
        return $this->query($sql);
    }
}