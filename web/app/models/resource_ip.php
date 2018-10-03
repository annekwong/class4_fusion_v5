<?php
class ResourceIp extends AppModel{
	var $name = 'ResourceIp';
	var $useTable = 'resource_ip';
	var $primaryKey = 'resource_ip_id';
	
	

	static $resource_ids = null;
			function format_resource_id_for_download($value,$data){
		
		if(!self::$resource_ids){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$resource_ids = $model->find("all"); 
		}
		foreach(self::$resource_ids as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}	
	
	var $default_schema =     array(
	'resource_id' => array ('name'=>'Trunk Alias',  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
//    'resource_ip_id' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => 11,  'key' => primary, ),
    'ip' => array (  'type' => 'text',  'null' => 1,  'default' => '' ,  'length' => '' , ),
    'port' => array (  'type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
//    'fqdn' => array (  'type' => 'string',  'null' => 1,  'default' => '' ,  'length' => 100, ),
//    'username' => array (  'type' => 'string',  'null' => 1,  'default' => '' ,  'length' => 40, ),
//    'password' => array (  'type' => 'string',  'null' => 1,  'default' => '' ,  'length' => 16, ),
//    'sip_rpid' => array (  'type' => 'string',  'null' => 1,  'default' => '' ,  'length' => 64, ),
//    'registered' => array (  'type' => 'boolean',  'null' => 1,  'default' => '' ,  'length' => '' , ),
//    'need_register' => array (  'type' => 'boolean',  'null' => 1,  'default' => '' ,  'length' => '' , ),
    );
	
}
