<?php
class RateSendLogDetail extends AppModel{

    var $name = 'RateSendLogDetail';
    var $useTable = 'rate_send_log_detail';
    var $primaryKey = 'id';

    public function send_statuses(){
        return [
            1 => 'Sent',
            2 => 'Unable to Deliver',
            3 => 'Downloaded',
            4 => 'Expired',
        ];
    }

    public function get_status($type){
        $statuses = $this->send_statuses();
        return isset($statuses[$type]) ? $statuses[$type] : '';
    }

    //rate_table send log
    public function get_all_product_rate_table_log($resource_id, $rate_table_id){

        return $sql = <<<EOD
        SELECT "RateSendLog"."create_time" AS "RateSendLog__create_time","RateSendLog"."rate_table_id" AS "RateSendLog__rate_table_id",
        "RateSendLogDetail"."send_to" AS "RateSendLogDetail__send_to", "RateSendLog"."effective_date" AS "RateSendLog__effective_date",
        "RateSendLogDetail"."id" AS "RateSendLogDetail__id", "RateSendLog"."id" AS "RateSendLog__id",
        "RateSendLogDetail"."status" AS "RateSendLogDetail__status","RateSendLogDetail"."resource_id" AS "RateSendLogDetail__resource_id",
         "RateSendLog"."file" AS "RateSendLog__file"
        FROM "rate_send_log_detail" AS "RateSendLogDetail"
        INNER JOIN
        rate_send_log AS "RateSendLog"
        ON ("RateSendLog"."id" = "RateSendLogDetail"."log_id")
        WHERE "resource_id" = $resource_id AND "rate_table_id" = $rate_table_id

EOD;
    }

}
