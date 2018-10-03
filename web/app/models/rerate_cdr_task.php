<?php
class RerateCdrTask extends AppModel{
    var $name = 'RerateCdrTask';
    var $useTable = 'rerate_cdr_task';
    var $primaryKey = 'id';

    public function getStatus()
    {
        return array(
            0=>__("Waiting",true),
            1=>__("Download CDR",true),
            2=>__("Load Rate",true),
            3=>__("Re-rate CDR",true),
            4=>__("Re-rate Finished",true),
            5=>__('Error',true),
            6=>__('Re-Report Waiting',true),
            7=>__('Re-Report Process',true),
            8=>__('Re-Report Finished',true),
            9=>__('Re-Balance Waiting',true),
            10=>__('Re-Balance Process',true),
            11=>__('Re-Balance Finished',true),
        );
    }

}
