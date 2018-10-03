<?php

class LrnSetting extends AppModel {
    
//    var $name = 'LrnSetting';
    var $useTable = false;
//    var $primaryKey = "id";
//    var $hasMany = array(
//        'Item' => array(
//            'className' => 'LrnItem',
//            'foreignKey' => 'group_id',
//            'order' => 'Item.id ASC',
//        )
//    );
    
    public function exists_name($name, $id)
    {
//        $sql = "SELECT count(*) FROM lrn_groups where name = '{$name}'";
//
//        if (!is_null($id))
//            $sql .= " and id != $id";
//
//        $result = $this->query($sql);
//
//        return intval($result[0][0]['count']) > 0;
        return false;
    }
    
}


?>
