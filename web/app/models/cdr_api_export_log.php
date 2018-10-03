<?php

class CdrApiExportLog extends AppModel
{
    var $useTable = 'cdr_api_export_log';
    var $status = array(
        0 => 'Processing',
        1 => 'Completed',
        2 => 'Writing to file'
    );
}