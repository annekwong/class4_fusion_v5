<?php

class Resource extends AppModel {

    var $name = 'Resource';
    var $useTable = 'resource';
    var $primaryKey = 'resource_id';

    const RESOURCE_PROTO_SIP = 1;
    const RESOURCE_PROTO_PROTO = 2;
    const RESOURCE_PROTO_ALL = 0;

    var $hasMany = Array(
//        'ResourceIp',
            /*
              'InRealcdr'=>Array(
              'className'=>'Realcdr',
              'foreignKey'=>'ingress_id'
              ),
              'ERealcdr'=>Array(
              'className'=>'Realcdr',
              'foreignKey'=>'egress_id'
              )
             */
    );
    static $rate_tables = null;
    static $route_strategies = null;
    static $clients = null;
    static $codecs = null;

    function findAllEgress() {
        return $this->find('all', array('conditions' => 'egress=true', 'recursive' => -1, 'callbacks' => false));
    }

    /*
      function afterFind($lists)
      {
      $sql="select  resource_ip_id , use_cnt from resource_ip
      left join (select count(* ) as use_cnt,  egress_id,callee_ip_address
      from real_cdr   group by egress_id  ,callee_ip_address )  a
      on  a.egress_id=resource_id::text and a.callee_ip_address::text=resource_ip.ip::text";
      $E_lists=$this->query($sql);
      $e=Array();
      foreach($E_lists as $list)
      {
      $e[$list[0]['resource_ip_id']]=$list[0]['use_cnt'];
      }
      for($i=0;$i<count($lists);$i++)
      {
      if(isset($lists[$i]['ResourceIp'])){
      for($j=0;$j<count($lists[$i]['ResourceIp']);$j++)
      {
      $lists[$i]['ResourceIp'][$j]['use_cnt']=$e[$lists[$i]['ResourceIp'][$j]['resource_ip_id']];
      }
      }
      }
      return $lists;
      }
     * 
     */

    function findEgressWithTrunk() {
        $sql = "select distinct client.client_id as client_id, client.name as name from client
         inner join resource on client.client_id = resource.client_id 
        where resource.egress = true order by client.name";
        
        return $this->query($sql);
    }

    function find_client_ingress($client_id) {
        if (!empty($client_id)) {
            return $this->query("SELECT resource_id,alias FROM resource  WHERE ingress=true  AND   client_id=$client_id ORDER BY resource_id");
        }
        return array();
    }

    function find_client_egress($client_id) {
        if (!empty($client_id)) {
            return $this->query("SELECT resource_id,alias FROM resource  WHERE egress=true  AND   client_id=$client_id ORDER BY resource_id");
        }
        return array();
    }

    function get_acd_asr_etc($ids) {
        $ids = array_filter($ids, create_function('$d', 'return (int)$d>0;'));
        $ids = array_unique($ids);
        if (empty($ids)) {
            return array();
        }
        $ids_conditions = join(',', $ids);
        return $this->query("SELECT  
				(SUM(acd::integer) / SUM(CASE WHEN call_count='0'  THEN '1' ELSE  call_count  end ::integer)) as acd_24h,
			    (SUM(pdd::integer) / SUM(CASE WHEN call_count='0'  THEN '1' ELSE  call_count  end ::integer)) as pdd_24h, 
			    ROUND((SUM(asr::numeric * call_count_asr::numeric) / SUM(CASE WHEN call_count_asr='0'  then '1' ELSE   call_count_asr  END ::integer))) AS asr_24h, 
			    SUM(ca::integer) AS ca_24h,res_id
		    FROM  host_info
		    WHERE res_id <> '' AND res_id::integer  IN ($ids_conditions)  AND TO_TIMESTAMP( SUBSTRING(time::text from 1 for 10 ) ::bigint)  
		    BETWEEN ( CURRENT_TIMESTAMP(0) - INTERVAL '24 hour')  AND ( CURRENT_TIMESTAMP(0) )
		    GROUP  BY  res_id 
		");
    }

    function check_duplicate_for_upload($data) {
        return $this->find("first", array('conditions' => "alias = '" . $data[$this->alias]['alias'] . "'"));
    }

    function format_codec_for_download($value, $data) {
        $datas = $this->query("SELECT * from codecs INNER JOIN resource_codecs_ref ON (resource_codecs_ref.codec_id = codecs.id) WHERE resource_codecs_ref.resource_id = " . $data[$this->alias][$this->primaryKey]);
        $codecs = array();
        foreach ($datas as $data) {
            $codecs[] = $data[0]['name'];
        }
        return join(';', $codecs);
    }

    function format_rate_table_id_for_download($value, $data) {
        if (empty($value)) {
            return null;
        }
        if (!self::$rate_tables) {
            App::import("Model", 'Rate');
            $model = new Rate;
            self::$rate_tables = $model->find("all");
        }
        foreach (self::$rate_tables as $rate_table) {
            if ($rate_table['Rate']['rate_table_id'] == $value) {
                return $rate_table['Rate']['name'];
            }
        }
    }

    function format_route_strategy_id_for_download($value, $data) {
        if (empty($value)) {
            return null;
        }
        if (!self::$route_strategies) {
            App::import("Model", 'RouteStrategy');
            $model = new RouteStrategy;
            self::$route_strategies = $model->find("all");
        }
        foreach (self::$route_strategies as $route_strategy) {
            if ($route_strategy['RouteStrategy']['route_strategy_id'] == $value) {
                return $route_strategy['RouteStrategy']['name'];
            }
        }
    }

    function format_client_id_for_download($value, $data) {
        if (empty($value)) {
            return null;
        }
        if (!self::$clients) {
            App::import("Model", 'Client');
            $model = new Client;
            self::$clients = $model->find("all");
        }
        foreach (self::$clients as $client) {
            if ($client['Client']['client_id'] == $value) {
                return $client['Client']['name'];
            }
        }
    }

    function format_res_strategy_for_download($value, $data) {
        switch ($value) {
            case 1 : return 'T';
            case 2 : return 'R';
            default : return 'U';
        }
    }

    function format_media_type_for_download($value, $data) {
        switch ($value) {
            case 0 : return 'T';
            case 1 : return 'P';
            case 2 : return 'B';
            default : return 'U';
        }
    }

    function format_proto_for_download($value, $data) {
        switch ($value) {
            case 0 : return 'A';
            case 1 : return 'S';
            case 2 : return 'H';
            default : return 'U';
        }
    }

    function format_proto_for_upload($value, $data) {
        switch ($value) {
            case 'ALL' : return 0;
            case 'SIP' : return 1;
            case 'H323' : return 2;
            default : return 999999;
        }
    }

    function format_codec_for_upload($value, $data) {
        if (empty($value)) {
            return array();
        }
        if (!self::$codecs) {
            App::import('Model', 'Codec');
            $codec_model = new Codec;
            self::$codecs = $codec_model->find('all');
        }

        $codecs = array();
        foreach (self::$codecs as $c) {
            $codecs[] = $c['Codec']['name'];
        }
        $import_codecs = split(';', $value);
        $import_codecs = array_unique($import_codecs);
        $import_codec_ids = array();
        $import_codec_not_exists = array();
        foreach ($import_codecs as $import_codec) {
            if (in_array($import_codec, $codecs)) {
                foreach (self::$codecs as $c) {
                    if ($c['Codec']['name'] == $import_codec) {
                        $import_codec_ids[] = $c['Codec']['id'];
                        break;
                    }
                }
            } else {
                $import_codec_not_exists[] = $import_codec;
            }
        }
        if (empty($import_codec_not_exists)) {
            return $import_codec_ids;
        } else {
            throw new Exception('Codec ' . join(',', $import_codec_not_exists) . ' does not exist.');
        }
    }

    function format_rate_table_id_for_upload($value, $data) {
        if (empty($value)) {
            return null;
        }
        if (!self::$rate_tables) {
            App::import("Model", 'Rate');
            $model = new Rate;
            self::$rate_tables = $model->find("all");
        }
        foreach (self::$rate_tables as $rate_table) {
            if ($rate_table['Rate']['name'] == $value) {
                return $rate_table['Rate']['rate_table_id'];
            }
        }
        throw new Exception('Rate Table Name ' . $value . " does not exist.");
    }

    function format_route_strategy_id_for_upload($value, $data) {
        if (empty($value)) {
            return null;
        }
        if (!self::$route_strategies) {
            App::import("Model", 'RouteStrategy');
            $model = new RouteStrategy;
            self::$route_strategies = $model->find("all");
        }
        foreach (self::$route_strategies as $route_strategy) {
            if ($route_strategy['RouteStrategy']['name'] == $value) {
                return $route_strategy['RouteStrategy']['route_strategy_id'];
            }
        }
        throw new Exception('Route Plan Name ' . $value . " does not exist.");
    }

    function format_client_id_for_upload($value, $data) {
        if (empty($value)) {
            return null;
        }
        if (!self::$clients) {
            App::import("Model", 'Client');
            $model = new Client;
            self::$clients = $model->find("all");
        }
        foreach (self::$clients as $client) {
            if ($client['Client']['name'] == $value) {
                return $client['Client']['client_id'];
            }
        }
        throw new Exception('Carrier ' . $value . " does not exist.");
    }

    function format_res_strategy_for_upload($value, $data) {
        switch ($value) {
            case 'Top-Down' : return 1;
            case 'Round-Robin' : return 2;
            default : return 9999999;
        }
    }

    function format_media_type_for_upload($value, $data) {
        switch ($value) {
            case 'Transcoding' : return 0;
            case 'Proxy' : return 1;
            case 'Bypass' : return 2;
            default : return 9999999;
        }
    }

    function after_save_for_upload($data) {
        if (isset($data[$this->alias]['codec']) && is_array(($data[$this->alias]['codec'])) && !empty($data[$this->alias]['codec'])) {
            $codecs = array_unique($data[$this->alias]['codec']);
            $this->query("DELETE FROM resource_codecs_ref WHERE resource_id = {$data[$this->alias][$this->primaryKey]}");
            foreach ($codecs as $codec_id) {
                $this->query("INSERT INTO resource_codecs_ref  (resource_id,codec_id) VALUES ({$data[$this->alias][$this->primaryKey]},{$codec_id})");
            }
        }
    }

    function AfterSave() {
        if (!array_keys_value($this->data, 'Resource.resource_id')) {
            $this->data['Resource']['resource_id'] = $this->getlastinsertId();
        }
        $resource_id = array_keys_value($this->data, 'Resource.resource_id', 'ResourceIp');
        $this->bindModel(Array('hasMany' => Array('ResourceCodecsRef')));
        $this->ResourceCodecsRef->deleteAll(Array('resource_id' => $resource_id));
        foreach ($this->data['Resource']['select2'] as $id) {
            $this->ResourceCodecsRef->save(Array('codec_id' => $id, 'resource_id' => $resource_id));
            $this->ResourceCodecsRef->id = false;
        }
        $account = array_keys_value_empty($this->data, 'Resource.accounts', Array());
        $count = count($account['ip']);
//        $this->ResourceIp->deleteAll(Array("resource_id='$resource_id'"));
        for ($i = 0; $i < $count; $i++) {
            $data = Array();
            $data['resource_id'] = $resource_id;
            $data['port'] = $account['port'][$i];
            if (is_ip($account['ip'][$i])) {
                $data['ip'] = $account['ip'][$i];
                if (array_keys_value($account, 'need_register.' . $i)) {
                    $data['ip'] = $account['ip'][$i] . '/' . array_keys_value($account, 'need_register.' . $i);
                }
            } else {
                $data['fqdn'] = $account['ip'][$i];
                if (array_keys_value($account, 'need_register.' . $i)) {
                    $data['fqdn'] = $account['ip'][$i] . '/' . array_keys_value($account, 'need_register.' . $i);
                }
            }
            $this->ResourceIp->save($data);
            $this->ResourceIp->id = false;
        }
        return true;
    }

    function find_ingress_alias() {

        $r = $this->query("select resource_id,alias from resource where ingress='t' order by alias");
        $size = count($r);
        $l = array(" " => "All");

        for ($i = 0; $i < $size; $i ++) {
            $key = $r [$i] [0] ['resource_id'];
            $l [$key] = $r [$i] [0] ['alias'];
        }
        return $l;
    }

    function find_egress_alias() {
        $r = $this->query("select resource_id,alias from resource where egress='t' order by alias");
        $size = count($r);
        $l = array(" " => "All");

        for ($i = 0; $i < $size; $i ++) {
            $key = $r [$i] [0] ['resource_id'];
            $l [$key] = $r [$i] [0] ['alias'];
        }
        return $l;
    }

    /**
     * Get resource ids by client id
     * @param $clientId
     * @return mixed
     */
    public function getIdsByClientId($clientId)
    {
        $result = $this->find('all', array(
            'fields' => array('resource_id'),
            'conditions' => array(
                'client_id' => $clientId
            )
        ));

        if ($result && !empty($result)) {
            $temp = array();

            foreach ($result as $item) {
                array_push($temp, $item['Resource']['resource_id']);
            }
            $result = $temp;
        }

        return $result;
    }

}
