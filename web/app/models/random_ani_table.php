<?php

class RandomAniTable extends AppModel
{

    var $name = 'RandomAniTable';
    var $useTable = 'random_ani_table';
    var $primaryKey = 'id';

    public function find_all()
    {
        $sql = "SELECT id,name FROM random_ani_table ORDER BY name ASC ";
        $r = $this->query($sql);
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

?>