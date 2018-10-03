<?php
App::import('Helper','Order.AppOrder');
class AppOrderPlacesHelper extends AppOrderHelper{
	function get_regions_from_codes($codes){
		return array_unique(array_map(create_function('$d','return array_keys_value($d,"0.name");'),$codes));		
	}
	
	function filter_codes_from_codes_by_region($codes,$region,$country=null){
		return array_filter($codes,create_function('$d','return array_keys_value($d,"0.name")==="'.$region.'";'));	
	}
	
}
