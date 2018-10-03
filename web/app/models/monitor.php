<?php

class Monitor extends AppModel {
    var $name = "Monitor";
    var $useTable = 'prefix_info';
    
    public function movefile($name) {
         copy(Configure::read('database_export_path') . "/{$name}.csv",APP.'webroot'.DS.'stock'.DS."{$name}.csv");    
    }
    
    public function create_carrier_stats_call_csv($name, $gress,$resource_id, $ip_id='') {
        $flag = '';
        
        if(!empty($ip_id)) {
            $flag = "and host_info.ip_id= '$ip_id'";
        }
        $db_path = Configure::read('database_actual_export_path');
        $sql = <<<EOT
COPY(
select to_char(to_timestamp(time::bigint), 'YYYY-MM-DD HH24:MI:SS') as date, 
sum(ca::integer) as ca
from host_info 
join 
resource on resource.resource_id::text=host_info.res_id 
join 
resource_ip on ip_id = resource_ip.resource_ip_id::text
where $gress=true and time::bigint between extract(epoch from now())::bigint - (3600*24)
and extract(epoch from now())::bigint and resource.resource_id = '$resource_id' $flag
group by resource.resource_id ,resource_ip.ip,ip_id,date 
order by date desc
) TO '".$db_path."/{$name}.csv' CSV    
EOT;
        $this->query($sql);
        $this->movefile($name);
    }
    
    function create_carrier_csv($resource_id, $ctype, $type){
        $name = uniqid() . '.csv';
        $real_path = '/tmp/exports/' . $name;
        $sql = "COPY(select to_timestamp(a::integer)::timestamp without time zone as time, b as val from qos_chart({$ctype},{$resource_id},{$type},3) 
            as t(a text,b text) ORDER BY a DESC) to '{$real_path}' csv";
        $this->query($sql);
        $web_path = APP . 'webroot' . DS .  'upload' . DS  . 'stock' . DS  . $name;
        copy($real_path, $web_path);
        return $name;
    }
    
    function create_ip_csv($ip_id, $ctype, $type){
        $name = uniqid() . '.csv';
        $real_path = Configure::read('database_export_path') . DS . $name;
        $real_db_path= Configure::read('database_actual_export_path') . DS . $name;
        $sql = "COPY(select to_timestamp(a::integer)::timestamp without time zone as time, b as val from qos_chart({$ctype},{$ip_id},{$type},3) 
            as t(a text,b text) ORDER BY a DESC) to '{$real_db_path}' csv";
        $this->query($sql);
        $web_path = APP . 'webroot' . DS .  'upload' . DS  . 'stock' . DS  . $name;
        copy($real_path, $web_path);
        return $name;
    }
    
    
    
    function create_prefix_csv($type, $product_id){
        $name = uniqid() . '.csv';
        $real_path = Configure::read('database_export_path') . DS . $name;
        $real_db_path= Configure::read('database_actual_export_path') . DS . $name;
        $sql = "COPY(select to_timestamp(a::integer)::timestamp without time zone as time, b as val from qos_chart(2,{$product_id},{$type},3) 
            as t(a text,b text) ORDER BY a DESC) to '{$real_db_path}' csv";
        $this->query($sql);
        $web_path = APP . 'webroot' . DS .  'upload' . DS  . 'stock' . DS  . $name;
        copy($real_path, $web_path);
        return $name;
    }
    
    
    
    public function point_prefix_history($pro_id,$prefix_id) {
        $sql = "select A.id, A.name,A.id,asr24h,acd24h,pdd24h,ca24h,asr1h,acd1h,pdd1h,ca1h,asr15m,acd15m,pdd15m,ca15m from
(select 
prefix_id as id,digits as name, 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr24h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd24h, 
(sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd24h, 
sum(ca::integer) as ca24h
from prefix_info left join product_items on product_items.item_id::text=prefix_info.prefix_id 
where pro_id='$pro_id' and time::bigint between extract(epoch from now())::bigint-(3600*24)  
and  extract(epoch from now())::bigint and prefix_info.pro_id !='dynamic' and prefix_info.prefix_id != 'dynamic' group by prefix_id ) A 
LEFT JOIN
(select 
prefix_id as id,digits as name, 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr1h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd1h, 
(sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd1h, 
sum(ca::integer) as ca1h 
from prefix_info left join product_items on product_items.item_id::text=prefix_info.prefix_id 
where pro_id='$pro_id' 
and time::bigint between extract(epoch from now())::bigint-(3600)  and  extract(epoch from now())::bigint and prefix_info.pro_id !='dynamic' and prefix_info.prefix_id != 'dynamic' group by prefix_id ,digits) B
ON A.id = B.id
LEFT JOIN
(select 
prefix_id as id,digits as name, 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr15m, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd15m, 
(sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd15m, 
sum(ca::integer) as ca15m 
from prefix_info left join product_items on product_items.item_id::text=prefix_info.prefix_id 
where pro_id='$pro_id' 
and time::bigint between extract(epoch from now())::bigint-(60*15)  and  extract(epoch from now())::bigint and prefix_info.pro_id !='dynamic' and prefix_info.prefix_id != 'dynamic' group by prefix_id ,digits) C 
ON A.id = C.id  where A.id = '$prefix_id'
                ;";
	 $result = $this->query($sql);
         $arr = array();
         foreach($result as $res) {
             array_push($arr, $res[0]);
         }
         return $arr;
    }
    
    public function prefix_history_info($product_id, $pageSize, $offset) {
        $sql = "select * from qos_report(7,{$product_id}) as t(qos_name text,asr1 text,acd1 text,ca1
text,pdd1 text,asr2 text,acd2 text,ca2 text,pdd2 text,asr3 text,acd3
text,ca3 text,pdd3 text) limit $pageSize offset $offset";
        $result = $this->query($sql);
        return $result;
    }
    
    public function count_prefix_history_info($product_id) {
        $sql = "select count(*) as c from qos_report(7,{$product_id}) as t(qos_name text,asr1 text,acd1 text,ca1
text,pdd1 text,asr2 text,acd2 text,ca2 text,pdd2 text,asr3 text,acd3
text,ca3 text,pdd3 text)";
        $result = $this->query($sql);
        return $result[0][0]['c'];
    }
    
    public function carrier_info($type,$gress, $pageSize, $offset) {
        $sql = "select (SELECT alias FROM resource WHERE resource_id::text = qos_name AND {$gress}=true) as name,* 
from qos_report({$type},1) as t(qos_name text,asr1 text,acd1 text,ca1
text,pdd1 text,asr2 text,acd2 text,ca2 text,pdd2 text,asr3 text,acd3
text,ca3 text,pdd3 text) limit $pageSize offset $offset";
        $result = $this->query($sql);
        return $result;
    }
    
    public function count_carrier_info($type,$gress) {
        $sql = "select count(*) as c 
from qos_report({$type},1) as t(qos_name text,asr1 text,acd1 text,ca1
text,pdd1 text,asr2 text,acd2 text,ca2 text,pdd2 text,asr3 text,acd3
text,ca3 text,pdd3 text)";
        $result = $this->query($sql);
        return $result[0][0]['c'];
    }
    
    public function prefix_history($pageSize, $offset) {
        $sql = "select case p_type when '2' then 
(SELECT name FROM product WHERE product_id::text = qos_name)
when '1' then
(SELECT name FROM dynamic_route WHERE dynamic_route_id::text = qos_name) end 
 as
name

,* from qos_report(2,1) as t(qos_name text,p_type text,asr1 text,acd1 text,ca1
text,pdd1 text,asr2 text,acd2 text,ca2 text,pdd2 text,asr3 text,acd3
text,ca3 text,pdd3 text) limit $pageSize offset $offset
                ;";
	 $result = $this->query($sql);
         $arr = array();
         foreach($result as $res) {
             array_push($arr, $res[0]);
         }
         return $arr;
    }   
    
   
    
    public function count_prefix_history() {
        $sql = "select count(*) as c from qos_report(2,1) as t(qos_name text,p_type text,asr1 text,acd1 text,ca1
text,pdd1 text,asr2 text,acd2 text,ca2 text,pdd2 text,asr3 text,acd3
text,ca3 text,pdd3 text)";
        $result = $this->query($sql);
        return $result[0][0]['c'];
    }
    
    public function count_filterip($resource_id,$type) {
        $sql = <<<EOT
    select 
count(*) as c from qos_report({$type},{$resource_id}) as t(qos_name text,asr1 text,acd1 text,ca1
text,pdd1 text,asr2 text,acd2 text,ca2 text,pdd2 text,asr3 text,acd3
text,ca3 text,pdd3 text)
    
EOT;
        $result = $this->query($sql);
        return $result[0][0]['c'];
    }
    
    public function filterip($resource_id,$pageSize,$offset,$type) {
        $sql = <<<EOT
    select 
(select ip from resource_ip where resource_ip_id::text = qos_name) as ip,
* from qos_report({$type},{$resource_id}) as t(qos_name text,asr1 text,acd1 text,ca1
text,pdd1 text,asr2 text,acd2 text,ca2 text,pdd2 text,asr3 text,acd3
text,ca3 text,pdd3 text)  limit $pageSize offset $offset
    
EOT;
         $result = $this->query($sql);
         $arr = array();
         foreach($result as $res) {
             array_push($arr, $res[0]);
         }
         return $arr;
    }
    
    
    public function filteripone($ingress, $resource_id, $ip_id) {
        $sql =<<<EOT
        select A.id, A.ip, asr24h, acd24h, pdd24h, ca24h,asr1h, acd1h, pdd1h, ca1h,asr15m, acd15m, pdd15m, ca15m  from
(select ip_id as id,resource_ip.ip as ip , 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr24h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd24h, (sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd24h, 
sum(ca::integer) as ca24h 
from host_info 
join 
resource on resource.resource_id::text=host_info.res_id 
join 
resource_ip on ip_id = resource_ip.resource_ip_id::text
where $ingress=true and time::bigint between extract(epoch from now())::bigint - (3600*24)
and extract(epoch from now())::bigint and resource.resource_id = '$resource_id' and host_info.ip_id= '$ip_id'
group by resource.resource_id ,resource_ip.ip,ip_id ) A
left join
(select ip_id as id,resource_ip.ip as ip , 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr1h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd1h, (sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd1h, 
sum(ca::integer) as ca1h
from host_info 
join 
resource on resource.resource_id::text=host_info.res_id 
join 
resource_ip on ip_id = resource_ip.resource_ip_id::text
where $ingress=true and time::bigint between extract(epoch from now())::bigint - (3600)
and
extract(epoch from now())::bigint and resource.resource_id = '$resource_id' and host_info.ip_id= '$ip_id'
group by resource.resource_id ,resource_ip.ip,ip_id )B on A.id = B.id
left join
(select ip_id as id,resource_ip.ip as ip , 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr15m, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd15m, (sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd15m, 
sum(ca::integer) as ca15m 
from host_info 
join 
resource on resource.resource_id::text=host_info.res_id 
join 
resource_ip on ip_id = resource_ip.resource_ip_id::text
where $ingress=true and time::bigint between extract(epoch from now())::bigint - (60*15)
and
extract(epoch from now())::bigint and resource.resource_id = '$resource_id' and host_info.ip_id= '$ip_id'
group by resource.resource_id ,resource_ip.ip,ip_id )  C on A.id = C.id 
EOT;
         $result = $this->query($sql);
         $arr = array();
         foreach($result as $res) {
             array_push($arr, $res[0]);
         }
         return $arr;
    }
    
    public function get_history() {
        $sql = "select * from qos_report(1,1) as t(qos_name text,asr1 text,acd1 text,ca1
text,pdd1 text,asr2 text,acd2 text,ca2 text,pdd2 text,asr3 text,acd3
text,ca3 text,pdd3 text)";
        return $this->query($sql);
    }
    
}


?>
