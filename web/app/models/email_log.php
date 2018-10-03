<?php

class EmailLog extends AppModel {
    var $name = 'EmailLog';
    var $useTable = "email_log"; 
    var $primaryKey = "id";

    public function get_carriers()
    {
        $clients = $this->query("select client_id, name from client order by name asc");
        return $clients;
    }

    public function get_email_log_type()
    {
        return array(
            1 => 'Low Balance',
            2 => 'Daily Summary',
            3 => 'Auto Delivery',
            4 => 'Zero Balance',
            5 => 'CDR',
            //6 => 'Exchange Alert Route',
            7 => 'Invoice',
            8 => 'Daily Balance',
         //   9 => 'Daily Payment',
            10 => 'Trunk Host Change',
            11 => 'Trunk Prefix/Product Change',
            21 => 'Daily Payment',
            22 => 'Rule Alert',
            31 => 'Welcome Letter',
            32 => 'Denovo support',
            33 => 'Fraud Detection',
            34 => 'Invalid Number Detection',
            35 => 'Rate Download',
            36 => __("No Download Rate in Deadline",true),
          //  37 => __("Vendor Invoice Dispute",true),
            38 => __("Rate Generation Update Rate",true),
            39 => 'Registration letter',
            40 => __("Payment Sent",true),
            41 => __("Payment Received",true),
            42 => __("Send Rate",true),
            43 => __("Order New Number", true)
        );
    }
}


?>
