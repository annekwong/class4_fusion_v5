<?php

class RateUploadTask extends AppModel
{

    var $name = 'RateUploadTask';
    var $useTable = 'rate_upload_task';
    var $primaryKey = 'id';
    var $statusArr = array();
    var $methodArr = array();

    public function get_status_arr()
    {
        $this->statusArr = array(
            __("initial",true),
            __("download_rate",true),
            __("process_rate",true),
            __("commit_rate_to_db",true),
            __("finished",true),
            __("error",true),
            -1 => __("waiting",true),
            -2 => __("End-Date All Records",true),
        );
        return $this->statusArr;
    }

    public function get_method_arr()
    {
        $this->methodArr = array(
            __("ignore",true),
            __("delete",true),
            __("update",true),
        );
        return $this->methodArr;
    }

}
