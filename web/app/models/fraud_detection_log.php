<?php

class FraudDetectionLog extends AppModel {
    
    var $name = 'FraudDetectionLog';
    var $useTable = "fraud_detection_log";
    var $primaryKey = "id";

    var $createByArr = array();
    var $statusArr = array();

    public function get_create_by_arr()
    {
        $this->createByArr = array(
            0 => __('Auto',true),
            1 => __('Manual',true),
        );
        return $this->createByArr;
    }

    public function get_status_arr()
    {
        $this->statusArr = array(
            0 => __('Normal',true),
            1 => __('Over Limit',true),
        );
        return $this->statusArr;
    }



}