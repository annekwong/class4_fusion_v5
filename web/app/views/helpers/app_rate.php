<?php 

App::import("Model","Rate");
class AppRateHelper extends AppHelper {	
	function show_jurisdiction_country($jurisdiction_countries,$jurisdiction_country_id){
		if(isset($jurisdiction_countries[$jurisdiction_country_id])){
			return $jurisdiction_countries[$jurisdiction_country_id] ;
		}else{
			return NULL;
		}
	}
	function  show_route_plan(){}
	function format_jurisdiction_countries_for_options($jurisdiction_countries){
		return $jurisdiction_countries;
	}
	
	
	
	
	function get_time_zone(){
		return  array(
		  '-12:00'=>'-12:00',
		'-11:00'=>'-11:00',
		'-10:00'=>'-10:00',
			'-09:30'=>'-09:30',
		'-09:00'=>'-09:00',
		'-08:00'=>'-08:00',
		'-07:00'=>'-07:00',
		'-06:00'=>'-06:00',
		'-05:00'=>'-05:00',
		'-04:30'=>'-04:30',
		'-04:00'=>'-04:00',
			'-03:30'=>'-03:30',
		'-03:00'=>'-03:00',
		'-02:00'=>'-02:00',
		'-01:00'=>'-01:00',
		'-01:00'=>'-01:00',
			'00:00'=>'00:00',
			'0'=>'00:00',
		
			'01:00'=>'01:00',
			'02:00'=>'02:00',
			'03:00'=>'03:00',
			'03:30'=>'03:30',
			'04:00'=>'04:00',
				'04:30'=>'04:30',
			'05:00'=>'05:00',
			'05:30'=>'05:30',
			'06:00'=>'06:00',
			'06:30'=>'06:30',
			'07:00'=>'07:00',
			'08:00'=>'08:00',
			'09:00'=>'09:00',
			'09:30'=>'09:30',
			'10:00'=>'10:00',
			'10:30'=>'10:30',
			'11:00'=>'11::00',
			'11:30'=>'11::30',
			'12:00'=>'12::00'
		
		
		);
		
		
		
		
	}
	
	function is_show_jur_rate($rate_table_id){
		$model =new Rate();
		return $model->is_show_jur_rate($rate_table_id);
	}
}
?>