<?php

class FraudDetectionLogDetail extends AppModel {
    
    var $name = 'FraudDetectionLogDetail';
    var $useTable = "fraud_detection_log_detail";
    var $primaryKey = "id";

    var $blockTypeArr = array();
    var $statusArr = array();

    public function get_block_type_arr()
    {
        $this->blockTypeArr = array(
            0 => __('1 hour minute',true),
            1 => __('1 hour revenue',true),
            2 => __('24 hours minute',true),
            3 => __('24 hours revenue',true),
        );
        return $this->blockTypeArr;
    }







}