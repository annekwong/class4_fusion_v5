<?php
class Code extends AppModel{
	var $name = 'Code';
	var $useTable = 'code';
	var $primaryKey = 'code_id';
	var $validate = array(
			'country' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'country cannot be NULL!',						
					'last' => true
					),
				'alphaNumeric' => array(
					'required' => true,
					'rule' => '/^[\w\-\_\s]+$/',
					'message' => 'country must contain numeric characters only.'
				)				
			),
			'name' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'code name cannot be NULL!',						
					'last' => true
					),
				'alphaNumeric' => array(
					'required' => true,
					'rule' => '/^[\w\-\_\s]+$/',
					'message' => 'code name must contain numeric characters only.'
				)				
			),
			'code' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'prefix cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'prefix must contain numeric only.'
				)		
			)
		);
	
	
	var $default_schema = Array(
			'country'=>Array('type'=>'string'),
			'name'=>Array('type'=>'string','name'=>"Code Name"),
			'code'=>Array('type'=>'integer','name'=>"Prefix"),
		);
	
	
//	public function del($id){
//		$qs = $this->query("delete from code where code_id in($id)");
//		return count($qs) == 0;
//	}	
	
	function find_countries(){
		return $this->query("SELECT DISTINCT country FROM code ORDER BY country");		
	}
	
	function find_code_names(){
		return $this->query("SELECT DISTINCT name FROM code ORDER BY name");		
	}
	
	function find_codes(){
		return $this->query("SELECT DISTINCT code FROM code ORDER BY code");		
	}
	
function find_clients(){
		return $this->query("SELECT client_id,name FROM client ORDER BY name");		
	}
	
function find_order_users(){
		return $this->query("SELECT client_id,name FROM order_user where client_id > 0 ORDER BY name");		
	}
	
	function find_by_country_and_regions($country,$regions){
		if(empty($country) || empty($regions)){
			return array();
		}else{
			$regions_str = join(',',array_map(create_function('$d',"return \"'\$d'\";"),$regions));
			return $this->query("SELECT * FROM code WHERE country='$country' AND name IN ($regions_str) ORDER BY name");
		}
	}
	
	function find_by_country_and_region($country,$region){
		if(empty($country) || empty($region)){
			return array();
		}else{
//			if(is_array($ids)){
//				$regions_str = join(',',array_map(create_function('$d','return (int)$d;'),$ids));
//				return $this->query("SELECT * FROM code WHERE country='$country' AND code_id IN ($regions_str) ORDER BY name");	
//			}else{
			return $this->query("SELECT * FROM code WHERE country='$country' and name = '$region' ORDER BY name");
//			}
			
		}
	}
	function find_code_by_country_name($name){
		if(empty($name)){
			return array();
		}else{
			return $this->query("SELECT * FROM code WHERE country='$name' ORDER BY name");
		}
	}
	
	
	//for autoComplete 
		function find_auto_country($country){
		return $this->query("SELECT DISTINCT country::text FROM code  WHERE country ILIKE '$country%' ORDER BY country LIMIT 10");		
	}
	
	function find_auto_code_name($name){
		return $this->query("SELECT DISTINCT name::text as code_name FROM code  WHERE name ILIKE '$name%' ORDER BY code_name  LIMIT 10");		
	}
	
	function find_auto_code($code){
		return $this->query("SELECT DISTINCT code::text FROM code  WHERE code <@ '$code' ORDER BY code  LIMIT 10");		
	}	

	// for upload check duplicate
	function check_duplicate_for_upload($data){
		return $this->find("first",array('conditions' => "code = '{$data['Code']['code']}'" ));	
	}

	function get_foreign_name($id){
  	return  $this->query(" select  name from  code_deck  where  code_deck_id=$id;");

    }	
	function find_code_country($country){
//	 	$sql ="select * from  code_country where country ilike '%$country%'";
//	 	return $this->query($sql);
        return array();
   }
		
}
