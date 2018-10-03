<?php

function get_item($start_time, $client_id, $type) 
{
    $sql = "select a::date, sum(d::numeric) 
as e from mutual_trans('2012-03-01 00:00:00',CURRENT_TIMESTAMP, 1248,4) 
as t(a text,b text,c text,d text) GROUP BY a::date";
}



?>
