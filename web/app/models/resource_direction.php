<?php
class ResourceDirection extends AppModel {
	var $name = 'ResourceDirection';
	var $useTable = 'resource_direction';
	var $primaryKey = 'direction_id';
	var $order = "direction_id DESC";
	
	 var $default_schema = array(
//	    'direction_id' => array ('name'  => '', 'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => 11,  ),
	    'resource_id' => array ( 'name' => 'Trunk Alias', 'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
	    'time_profile_id' => array ('name' => 'Time Profile' ,  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
				'direction' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
	    'action' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
	    'digits' => array (  'type' => 'string',  'null' => '' ,  'default' => '' ,  'length' => 10, ),
	    'dnis' => array (  'type' => 'text',  'null' => 1,  'default' => '' ,  'length' => '' , ),
	    'type' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
	    'number_length' => array ('type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
	    'number_type' => array ( 'type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
    );
	
    
    
    
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
	
    static $time_profiles = null;
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
	
	
	function format_direction_for_download($value,$data){
		switch($value){
			case  1 : return 'I';
			case  2 : return 'E';
			default : return 'U';			
		}
	}
}
?>