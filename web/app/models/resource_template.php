<?php
class ResourceTemplate extends AppModel{
    var $name = 'ResourceTemplate';
    var $useTable = 'resource_template';
    var $primaryKey = 'resource_template_id';
    var $no_resource_fields = array(
        'name','create_by','update_on','create_on','resource_template_id'
    );

//    var $hasMany = array(
//        'ResourceDirectionTemplate' => array(
//            'className' => 'ResourceDirectionTemplate',
//            'foreignKey' => 'resource_template_id',
//        )
//    );

    function findNousecodecs($template_id = '')
    {
        $codecs_str_result = $this->find('first',array(
            'fields' => array('codecs_str'),
            'conditions' => array(
                'resource_template_id' => $template_id
            )
        ));
        if(!isset($codecs_str_result['ResourceTemplate']['codecs_str']) || empty($codecs_str_result['ResourceTemplate']['codecs_str']))
            $r = $this->query("select id,name  from  codecs ORDER by name ASC ");
        else
        {
            $codecs_arr = explode(',',$codecs_str_result['ResourceTemplate']['codecs_str']);
            foreach($codecs_arr as $key =>$codecs)
            {
                $codecs_arr[$key] = intval($codecs);
            }
            $sql_codecs_str = implode(",",$codecs_arr);
            $r = $this->query("select id,name  from  codecs  where  id not in($sql_codecs_str) ORDER by name ASC ");
        }
        $size = count($r);
        if ($size == 0)
        {
            return NULL;
        }
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r[$i][0]['id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /**
     *
     * @param unknown_type $template_id
     * @return 通过$template_id查询codes
     */
    function findUsecodecs($template_id = '')
    {
        $codecs_str_result = $this->find('first',array(
            'fields' => array('codecs_str'),
            'conditions' => array(
                'resource_template_id' => $template_id
            )
        ));
        if(!isset($codecs_str_result['ResourceTemplate']['codecs_str']) || empty($codecs_str_result['ResourceTemplate']['codecs_str']))
            return array();
        $codecs_arr = explode(',',$codecs_str_result['ResourceTemplate']['codecs_str']);
        $result = array();
        foreach($codecs_arr as $key =>$codecs)
        {
            $codecs_id = intval($codecs);
            $r = $this->query("select id,name  from  codecs  where  id = $codecs_id ORDER by NAME ASC ");
            array_push($result,$r[0]);
        }

        $size = count($result);
        if ($size == 0)
        {
            return NULL;
        }
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $result[$i][0]['id'];
            $l[$key] = $result[$i][0]['name'];
        }
        return $l;
    }

    public function get_used_resource($template_id)
    {
        $sql = "SELECT resource_id,alias FROM resource WHERE resource_template_id = $template_id ORDER by alias ASC ";
        $data = $this->query($sql);
        return $data;
    }

    public function get_resource_template($type = 0)
    {
        $result = $this->query("SELECT resource_template_id,name FROM resource_template WHERE trunk_type = $type ORDER BY NAME ASC ");
        $size = count($result);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $result[$i][0]['resource_template_id'];
            $l[$key] = $result[$i][0]['name'];
        }
        return $l;
    }


    public function get_resource_fields($template_id)
    {
        $data = $this->findByResourceTemplateId($template_id);
        foreach ($data['ResourceTemplate'] as $key => $item)
        {
            if(in_array($key,$this->no_resource_fields))
                unset($data['ResourceTemplate'][$key]);
        }
        return $data['ResourceTemplate'];
    }
}
