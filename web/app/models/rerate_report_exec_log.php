<?php

class RerateReportExecLog extends AppModel {
    var $name = 'RerateReportExecLog';
    var $useTable = "rerate_report_exec_log";
    var $primaryKey = "id";


    function getStatus()
    {
        return array(
            0 => __('Waiting',true),
            1 => __('In Process',true),
            2 => __('Finished',true),
            -1 => __('Error',true)
        );
    }

    function getExecType()
    {
        return array(
            0 => __('',true),
            1 => __('Re-Report',true),
            2 => __('Re-Balance',true),
        );
    }

}

