<?php

class RateUploadShell extends Shell
{

    var $uses = array('RateUploadTask');

    function main()
    {
        $log_id = (int) $this->args[0];
        $log_info = $this->RateUploadTask->findById($log_id);

        if (!$log_info){
            return false;
        }

        if (!$log_info['RateUploadTask']['reduplicate_rate_action']){

            $log_update = array(
                'id' => $log_id,
                'status' => -2,
            );
            $this->RateUploadTask->save($log_update);

            $end_date = $log_info['RateUploadTask']['all_rate_end_date'];
            $rate_table_id = $log_info['RateUploadTask']['rate_table_id'];
            $sql = <<<SQL
update rate set end_date = '$end_date' where rate_table_id = $rate_table_id and end_date is null)
SQL;
            echo $sql;
            $this->RateUploadTask->query($sql);
        }

        $log_update = array(
            'id' => $log_id,
            'status' => 0,
        );
        $this->RateUploadTask->save($log_update);

    }


}

