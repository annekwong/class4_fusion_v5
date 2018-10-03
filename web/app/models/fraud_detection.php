<?php

class FraudDetection extends AppModel {
    
    var $name = 'FraudDetection';
    var $useTable = "fraud_detection";
    var $primaryKey = "id";

    var $emailToArr = array();

    public function get_email_to_arr()
    {
        $this->emailToArr = array(
            0 => __('Your Own NOC',true),
            1 => __('Partner’s NOC',true),
            2 => __('Both',true)
        );
        return $this->emailToArr;
    }


    public function get_mail_template()
    {
        $sql = "SELECT fraud_detection_from,fraud_detection_subject,fraud_detection_content FROM mail_tmplate limit 1";
        return $this->query($sql);
    }
}