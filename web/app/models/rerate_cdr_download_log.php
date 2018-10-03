<?php

class RerateCdrDownloadLog extends AppModel {
    var $name = 'RerateCdrDownloadLog';
    var $useTable = "rerate_cdr_download_log";
    var $primaryKey = "id";


    function getStatus()
    {
        return array(
            0 => __('Waiting',true),
            1 => __('Formatting CDR',true),
            2 => __('Compress Result',true),
            3 => __('Finished',true),
        );
    }

}

