<?php

class OrigLog extends DidAppModel
{
    var $name = 'OrigLog';
    var $useTable = 'orig_log';
    var $primaryKey = 'log_id';
    
    public function add_orig_log($module, $action, $detail)
    {
        $data = array(
            'OrigLog' => array(
                'module'    => $module,
                'update_by' => $_SESSION['sst_user_name'],
                'type'  => $action,
                'detail'    => $detail,
            ),
        );
        $flg = $this->save($data);
        if($flg === false)
        {
            return FALSE;
        }
        return TRUE;
    }
    
}

?>