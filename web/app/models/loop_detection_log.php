<?php

class LoopDetectionLog extends AppModel {
    
    var $name = 'LoopDetectionLog';
    var $useTable = "loop_detection_log"; 
    var $primaryKey = "id";
    
    var $hasMany = array(
        'LoopDetectionLogDetail' => array(
            'className' => 'LoopDetectionLogDetail',
            'foreignKey' => 'loop_detection_log_id',
        ),
    );
    
}