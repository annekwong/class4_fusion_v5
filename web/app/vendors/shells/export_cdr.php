<?php

class ExportCdrShell extends Shell
{
    var $uses = array('CdrRead', 'Export');

    private function process($currentValue, $totalCount, $totalHour, $logId)
    {
        $completed = $currentValue / $totalCount * 100;
        $hoursCompleted = round($totalHour * $completed / 100);
        $this->CdrRead->query("UPDATE cdr_export_log SET finished_hours = {$hoursCompleted} WHERE id = {$logId}");
    }

    public function main()
    {
        $logId = $this->args[0];
        $data = $this->CdrRead->query("SELECT * FROM cdr_export_log WHERE id={$logId}");
        $data = $data[0][0];
        $sql = $data['where_sql'];
        $start = $data['cdr_start_time'];
        $end = $data['cdr_end_time'];
        $hours = round((strtotime($end) - strtotime($start))/3600, 1);
        $fileName = $data['file_name'];
        $filePath = realpath(ROOT . '/../db_nfs_path/') . DS . $fileName;

        $sql = "\COPY ( $sql ) TO '$filePath' CSV HEADER";
        $this->CdrRead->_get_psql_cmd($sql);

//        $tmpRes = $this->CdrRead->query($sql);
//        $rows = count($tmpRes);
//        $handle = fopen($filePath, 'w');
//        $header = array_keys($tmpRes[0][0]);
//        fputcsv($handle, $header);
//
//        foreach ($tmpRes as $key => $item) {
//            $this->process($key, $rows, $hours, $logId);
//
//            foreach ($item[0] as $subKey => $subItem) {
//                if(strpos($subItem, ' ') !== false) {
//                    $item[0][$subKey] = '"' . $item[0][$subKey] . '"';
//                }
//            }
//
//            fputcsv($handle, $item[0]);
//        }
//
//        fclose($handle);
//        $currentDate = date('Y-m-d H:i:s');
//        $this->CdrRead->query("UPDATE cdr_export_log SET finished_hours = {$hours}, file_rows = {$rows}, status = 4, stop_time = '{$currentDate}' WHERE id = {$logId}");
    }
}