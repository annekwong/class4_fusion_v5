<?php
class ResourceTranslation extends AppModel {
	var $name = 'ResourceTranslation';
	var $useTable = 'resource_translation_ref';
	var $primaryKey = 'ref_id';
	var $order = "ref_id DESC";
	
	 var $default_schema = array(
//	    'direction_id' => array ('name'  => '', 'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => 11,  ),
	    'resource_id' => array ( 'name' => 'Resource Alias', 'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
	    'time_profile_id' => array ('name' => 'Time Profile' ,  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
  			'translation_id' => array ('name' => 'Translation Name' ,  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),

//		'direction' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
//	    'action' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
//	    'digits' => array (  'type' => 'string',  'null' => '' ,  'default' => '' ,  'length' => 10, ),
//	    'dnis' => array (  'type' => 'text',  'null' => 1,  'default' => '' ,  'length' => '' , ),
//	    'type' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
//	    'number_length' => array (  'type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
//	    'number_type' => array (  'type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
    );
	
    
    
    static $time_profiles = null;
			static $resource_id_1s = null;
			static $translation_ids=null;
			function format_resource_id_for_download($value,$data){
		
		if(!self::$resource_id_1s){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$resource_id_1s = $model->find("all"); 
		}
		foreach(self::$resource_id_1s as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}	
	
	
	
	
	function format_translation_id_for_download($value,$data){
		
		if(!self::$translation_ids){
			App::import("Model",'Digit');
			$model = new Digit;
			self::$translation_ids = $model->find("all"); 
		}
		foreach(self::$translation_ids as $time_profile ){
			if($time_profile['Digit']['translation_id'] == $value){
				return $time_profile['Digit']['translation_name'];
			}
		}
	}	
	
	
	
	
	
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
	
	
	
}
?>