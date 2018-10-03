<?php

class LoopDetection extends AppModel {
    
    var $name = 'LoopDetection';
    var $useTable = "loop_detection"; 
    var $primaryKey = "id";

    var $hasMany = Array(
        'LoopDetectionDetail'
    );

    public function delete_rule($id){
        $id = intval($id);
        $count = $this->find('count',array(
            'conditions' => array(
                'id' => $id
            ),
        ));
        if (!$count){
            return false;
        }
        $this->begin();

//        将resource表中的相关数据清空
        $sql = "select * from loop_detection_detail where loop_detection_id = $id";
        $detail_data = $this->query($sql);

        $resource_where_arr = array();
        foreach ($detail_data as $detail_item){
            $resource_where_arr[] = "resource_id = " . $detail_item[0]['resource_id'];
        }

        if ($resource_where_arr){

            $resource_update_sql = "update resource set number = null,counter_time = null,block_time = null where " . implode(' or ', $resource_where_arr);
            if ($this->query($resource_update_sql) === false){
                $this->rollback();
                return false;
            }
        }

        $delete_detail_sql = "delete from loop_detection_detail  where loop_detection_id = $id";
        if ($this->query($delete_detail_sql) === false){
            $this->rollback();
            return false;
        }

        if($this->del($id) === false){
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;


    }
}