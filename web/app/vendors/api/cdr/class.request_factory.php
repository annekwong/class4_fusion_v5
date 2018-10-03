<?php

class RequestFactory
{
    public function run($type, $data, $ajax = true)
    {
        $object = null;
        $result = false;

        switch ($type) {
            case '1':
                require_once 'class.cdr.php';
                $object = new CallDetailReport();
                break;
            case '2':
                require_once 'class.async_cdr.php';
                $object = new CallDetailReportAsync();
                break;
            case '3':
                require_once 'class.scheduled_search.php';
                $object = new ScheduledSearch();
                break;
            case '4':
                require_once 'class.global_reports.php';
                $object = new GlobalReports();
                break;
            case '5':
                require_once 'class.report.php';
                $object = new Report();
                break;
        }

        if ($object) {
            $result = $object->process($data);
        }

        if ($ajax) {
            header('Content-length: ' . strlen($result));
        }

        return $result;
    }
}