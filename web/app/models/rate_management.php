<?php

class RateManagement extends AppModel
{

    var $name = 'RateManagement';
    var $useTable = 'rate_management';
    var $primaryKey = 'id';
    var $hasMany = array(
        'RateManagementDetail' => array(
            'className' => 'RateManagementDetail',
            'foreignKey' => 'rate_management_id',
//            'fields' => array('RateManagementDetail.status', 'RateManagementDetail.rate_table_id', 'RateManagementDetail.upload_time',
//                'RateManagementDetail.file_path', 'RateManagementDetail.log_id', 'RateManagementDetail.email_when_done', 'RateManagementDetail.file_type'),
        ),
    );

}
