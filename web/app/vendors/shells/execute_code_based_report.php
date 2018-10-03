<?php

class ExecuteCodeBasedReportShell extends Shell
{

    var $uses = array('CodeBasedReportLog', 'CodeBasedReportLogStatus');

    function main()
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        Configure::load('myconf');

        $statusQuery = $this->CodeBasedReportLogStatus->find('first', array(
            'conditions' => array("status_value = 'Init'")
        ));
        $initStatusId = $statusQuery['CodeBasedReportLogStatus']['id'];

        $statusQuery = $this->CodeBasedReportLogStatus->find('first', array(
            'conditions' => array("status_value = 'Pending'")
        ));
        $pendingStatusId = $statusQuery['CodeBasedReportLogStatus']['id'];

        $data = $this->CodeBasedReportLog->find('first', array(
                'fields' => array(
                    'CodeBasedReportLog.id',
                    'CodeBasedReportLog.file_name',
                    'CodeBasedReportLog.user_id',
                    'CodeBasedReportLog.search_start_date',
                    'CodeBasedReportLog.search_end_date',
                    'CodeBasedReportLog.ingress_trunk_id',
                    'CodeBasedReportLog.egress_trunk_id'
                ),
                'conditions' => array(
                    'CodeBasedReportLog.status_id' => $initStatusId
                ))
        );

        $exportFolder = Configure::read('database_export_path') . '/cbr_report/';
        if (!is_dir($exportFolder)) {
            mkdir($exportPath, 0777, true);
        }

        if (!empty($data)) {
            foreach ($data as $d) {

                $orig_code = 0;
                $inbound_bill_time = 0;
                $inbound_call_cost = 0;
                $outbound_bill_time = 0;
                $outbound_call_cost = 0;
                $duration = 0;
                $total_calls = 0;
                $not_zero_calls = 0;
                $pdd = 0;
                $data = "Orig Code, Inbound Bill Time, Inbound Call Cost, Outbound Bill Time, Outbound Call Cost, Duration, Total Calls, Not Zero Calls, PDD \n";

                $begin = new DateTime( date('Y-m-d', strtotime($d['search_start_date'])) );
                $end = new DateTime( date('Y-m-d', strtotime($d['search_end_date'])) );

                $end->modify('+1 day');

                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);

                $whereConditions = " WHERE is_final_call = 1 ";
                $egressTrunkId = (int) $d['egress_trunk_id'];
                if ($egressTrunkId) {
                    $whereConditions .= " AND egress_trunk_id = '{$egressTrunkId}' ";
                }
                $ingressTrunkId = (int) $d['ingress_trunk_id'];
                if ($ingressTrunkId) {
                    $whereConditions .= " AND ingress_trunk_id = '{$ingressTrunkId}' ";
                }

                $csvResult = [];
                foreach ( $period as $dt ) {
                    $tableName = 'client_cdr' . $dt->format('Ymd');
                    $sqlQuery = "select orig_code,
                        sum(ingress_client_bill_time) as inbound_bill_time,
                        sum(ingress_client_cost) as inbound_call_cost, 
                        sum(egress_bill_time) as outbound_bill_time, 
                        sum(egress_cost) as outbound_call_cost, 
                        sum(call_duration) as duration,
                        count(*) as total_calls, 
                        sum(case when call_duration > 0 then 1 else 0 end ) as not_zero_calls ,
                        sum(pdd) as pdd from {$tableName} {$whereConditions} 
                    group by orig_code";

                    $result = $this->CodeBasedReportLog->query($sqlQuery);
                    foreach ($result as $r) {
                        $origCode = (double) $r[0]['orig_code'];
                        if (!isset($csvResult[$origCode])) {
                            $csvResult[$origCode]['inbound_bill_time'] = 0;
                            $csvResult[$origCode]['inbound_call_cost'] = 0;
                            $csvResult[$origCode]['outbound_bill_time'] = 0;
                            $csvResult[$origCode]['outbound_call_cost'] = 0;
                            $csvResult[$origCode]['duration'] = 0;
                            $csvResult[$origCode]['total_calls'] = 0;
                            $csvResult[$origCode]['not_zero_calls'] = 0;
                            $csvResult[$origCode]['pdd'] = 0;
                        }

                        $csvResult[$origCode]['inbound_bill_time'] += (double) $r[0]['inbound_bill_time'];
                        $csvResult[$origCode]['inbound_call_cost'] += (double) $r[0]['inbound_call_cost'];
                        $csvResult[$origCode]['outbound_bill_time'] += (double) $r[0]['outbound_bill_time'];
                        $csvResult[$origCode]['outbound_call_cost'] += (double) $r[0]['outbound_call_cost'];
                        $csvResult[$origCode]['duration'] += (double) $r[0]['duration'];
                        $csvResult[$origCode]['total_calls'] += (double) $r[0]['total_calls'];
                        $csvResult[$origCode]['not_zero_calls'] += (double) $r[0]['not_zero_calls'];
                        $csvResult[$origCode]['pdd'] += (double) $r[0]['pdd'];
                    }
                }
                foreach ($csvResult as $origCode => $reportData) {
                    $data .= "$origCode, " . $reportData['inbound_bill_time'] . ", " . $reportData['inbound_call_cost'] . ", " . $reportData['outbound_bill_time'] . ", " . $reportData['outbound_call_cost'] . ", " . $reportData['duration'] . ", " . $reportData['total_calls'] . ", " . $reportData['not_zero_calls'] . ", " . $reportData['pdd'] . " \n";
                }

                $filename = $d['file_name'];
                file_put_contents($exportFolder . DIRECTORY_SEPARATOR . $filename, $data);

                $recordId = $d['id'];
                $this->CodeBasedReportLog->query("UPDATE code_based_report_log SET status_id = '{$pendingStatusId}' WHERE id = '{$recordId}'");
            }
        }
    }

}
