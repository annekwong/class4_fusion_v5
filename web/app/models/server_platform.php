<?php

class ServerPlatform extends AppModel
{

    var $name = 'ServerPlatform';
    var $useTable = 'server_platform';
    var $primaryKey = 'server_id';
    var $validate = Array(
        'ip' => Array(
            'notEmpty' => Array(
                'rule' => 'notEmpty',
                'message' => 'cannot be NULL!'
            ),
            'ip' => Array(
                'rule' => 'ip',
                'message' => 'IPs must be a valid format.  The following IPs are not valid!'
            )
        )
    );
    var $order = "server_id DESC";

    const SERVER_TYPE_CLASS4 = 0;
    const SERVER_TYPE_SIP_PROXY = 1;

    function update_all_server($data)
    {
        try
        {
            $this->begin();
            if ($this->deleteAll('1=1') === false)
            {
                throw new Exception("sql error");
            }
            if ($this->saveAll($data) === false)
            {
                throw new Exception("sql error");
            }
            $this->commit();
            return true;
        } catch (Exception $e)
        {
            $this->rollback();
            return false;
        }
    }

    function find_all_class4()
    {
        //return $this->find('all',array('conditions' => 'server_type = '.self::SERVER_TYPE_CLASS4));
        return $this->find('all');
    }

}

?>
