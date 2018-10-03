<?php

class RateUploadTemplate extends AppModel
{

    var $name = 'RateUploadTemplate';
    var $useTable = 'rate_upload_template';
    var $primaryKey = 'id';

    public function get_template()
    {
        $r = $this->query("SELECT name,id FROM rate_upload_template");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }
}
