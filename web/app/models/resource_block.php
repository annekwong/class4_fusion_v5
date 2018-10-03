<?php
class ResourceBlock extends AppModel {
	var $name = 'ResourceBlock';
	var $useTable = 'resource_block';
	var $primaryKey = 'res_block_id';
	
	
	
	
	var $default_schema = Array(
//			'ingress_client_id' => array ( 'name' => "Ingress Carriers", 'type' => 'integer',  'null' => '',  'default' => '',  'length' => ''),
//			'egress_client_id' => array ('name' => "Engress Carriers",  'type' => 'integer',  'null' => '',  'default' => '',  'length' => ''),
			'ingress_res_id' => array ( 'name' => "Ingress Trunk", 'type' => 'integer',  'null' => '',  'default' =>'',  'length' => ''),
			'engress_res_id' => array ('name' => "Engress Trunk",  'type' => 'integer',  'null' => '',  'default' => '',  'length' => ''),
			'digit' => array('name'=>'Prefix', 'type' => 'text', 'null' => '', 'default' => '', 'length' => ''),
			'time_profile_id' => array ( 'name' => "Time Profile", 'type' => 'integer',  'null' => '',  'default' => '',  'length' => ''),

		);
		
			static $time_profiles = null;
			static $ingress_res = null;
			static $engress_res = null;
		
	function format_time_profile_id_for_download($value,$data){
		if(empty($value)){
			return null;
		}
		if(!self::$time_profiles){
			App::import("Model",'Timeprofile');
			$model = new Timeprofile;
			self::$time_profiles = $model->find("all"); 
		}
		foreach(self::$time_profiles as $time_profile ){
			if($time_profile['Timeprofile']['time_profile_id'] == $value){
				return $time_profile['Timeprofile']['name'];
			}
		}
	}
	
	
	
	function format_engress_res_id_for_download($value,$data){
		if(empty($value)){
			return null;
		}
		if(!self::$engress_res){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$engress_res = $model->find("all"); 
		}
		foreach(self::$engress_res as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}
        
        function get_resource_block_time($block_log_id, $loop_block_id, $ticket_log_id)
        {
            if ($block_log_id != null)
                $sql = "select blocked_time as time from block_ani where id = $block_log_id";
            elseif ($loop_block_id != null)
                $sql = "select execution_time as time from loop_detection_log where id = $loop_block_id";
            elseif ($ticket_log_id != null)
                $sql = "select blocked_time as time from block_ticket where id = $ticket_log_id";
            else
                return '';
            $result = $this->query($sql);
            if (isset($result[0][0]['time']))
                return ($result[0][0]['time']);
            return '';
        }
	
	function format_ingress_res_id_for_download($value,$data){
		if(empty($value)){
			return null;
		}
		if(!self::$ingress_res){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$ingress_res = $model->find("all"); 
		}
		foreach(self::$ingress_res as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}
	
	
	
	function check_duplicate_for_upload($data){
		return $this->find("first",array('conditions' => "rate_table_id = ".$data['RateTable']['rate_table_id']." AND code = '{$data['RateTable']['code']}'"));	
	}
}