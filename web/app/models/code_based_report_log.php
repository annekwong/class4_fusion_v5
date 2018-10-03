<?php
class CodeBasedReportLog extends AppModel{
    var $name = 'CodeBasedReportLog';
    var $useTable = 'code_based_report_log';
    var $primaryKey = 'id';

    public function initExport($userId, $startDate, $endDate, $egressTrunkId, $ingressTrunkId, $reportType)
    {
        if (strtotime($startDate) > strtotime($endDate)) {
            $tmpDate = $endDate;
            $endDate = $startDate;
            $startDate = $tmpDate;
        }

        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $filename = 'cbr_' . date('Y-m-d_H-i-s') . '_' . md5($userId) . '.csv';

        $statusQuery = $this->query("SELECT id FROM code_based_report_log_status WHERE status_value='Init'");
        $statusId = isset($statusQuery[0][0]['id']) ? $statusQuery[0][0]['id'] : 0;
        if (!$statusId) {
            echo "Something went wrong. Please make sure table code_based_report_log_status exists";
            die();
        }
        $emailWhenDone = $reportType == 'email' ? 1 : 0;

        $insertReportQuery = "INSERT INTO code_based_report_log(user_id, status_id, export_start_time, export_end_time, file_name, search_start_date, search_end_date, email_when_done, ingress_trunk_id, egress_trunk_id)
          VALUES ('{$userId}', '{$statusId}', NOW(), NOW(), '{$filename}', '{$startDate}', '{$endDate}', '{$emailWhenDone}', '{$ingressTrunkId}', '{$egressTrunkId}')";
        $this->query($insertReportQuery);
    }
}
?>