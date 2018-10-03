<?php

class LoopDetectionDetail extends AppModel {
    
    var $name = 'LoopDetectionDetail';
    var $useTable = "loop_detection_detail";
    var $primaryKey = 'id';


    public function findIngressByRule($rule_id = ''){

        $data = $this->find('all',array(
            'conditions' => array(
                'loop_detection_id' => intval($rule_id)
            ),
        ));
        $return_arr = array();
        foreach ($data as $item){
            $return_arr[] = $item['LoopDetectionDetail']['resource_id'];
        }
        return $return_arr;
    }

}