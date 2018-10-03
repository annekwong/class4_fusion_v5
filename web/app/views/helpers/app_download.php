<?php
class AppDownloadHelper extends AppHelper {
	var $helpers = array('form');
	
	function display_field_select($schema,$selected = null){		
		return $this->form->select(false,$this->format_schema_for_options($schema),$selected,array('name' => 'fields[]','type' => 'select','style'=>"width:200px"),true);
	}
	
	function format_schema_for_options($schema){
		$options = array();
		foreach($schema as $field_name => $value){
         	$options[$field_name] =  isset($value['name']) ?  Inflector::humanize($value['name']) :  Inflector::humanize($field_name);
		}		
		return $options;
	}
	
	
	
	function show_download_title($str){
		if($str=='digit_translation'){
		
		return 'Routing ';
		}else{
		
		return $str;
		}
	
	
	}
}