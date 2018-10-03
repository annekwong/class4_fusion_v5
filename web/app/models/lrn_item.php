<?php

class LrnItem extends AppModel {
    
//    var $name = 'LrnItem';
    var $useTable = false;
//    var $primaryKey = "id";
    
    var $belongsTo = array(
        'LrnSetting' => array(
            'className' => 'LrnSetting',
            'foreignKey' => 'group_id',
        )
    );
    
}


?>
