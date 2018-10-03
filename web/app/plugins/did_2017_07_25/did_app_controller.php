<?php
class DidAppController extends AppController
{
    function save_did_rate($rate_table_id, $min_price)
    {
        $values_arr = array();
        for ($i = 1; $i <= 9; $i++)
            $values_arr[] = "($rate_table_id, '$i', $min_price, 6, 6, $min_price, $min_price)";
//        A-Z
        for ($i = 65; $i <= 90; $i++)
            $values_arr[] = "($rate_table_id, '" . chr($i) . "', $min_price, 6, 6, $min_price, $min_price)";
//      a-z
        for ($i = 97; $i <= 122; $i++)
            $values_arr[] = "($rate_table_id, '" . chr($i) . "', $min_price, 6, 6, $min_price, $min_price)";
        $values_sql = implode(",", $values_arr);
        $insert_sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES $values_sql";
        $this->loadModel('Rate');
        return $this->Rate->query($insert_sql);
    }
}
