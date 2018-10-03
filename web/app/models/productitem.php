<?php
class Productitem extends AppModel {
	var $name = 'Productitem';
	var $useTable = 'product_items';
	var $primaryKey = 'item_id';
	var $alias='StaticRoute';

	CONST PRODUCT_ITEM_ROUTE_TYPE_ORDER=1;
	CONST PRODUCT_ITEM_ROUTE_TYPE_INTERSTATE=2;
	CONST PRODUCT_ITEM_ROUTE_TYPE_INTRASTATE=3;

	var $validate = array(
//						'digits' => array(
//				'blank' => array(
//					'required' => true,
//					'rule' => 'notEmpty',
//					'message' => 'Digits cannot be NULL!',						
//					'last' => true
//					),
//				'numeric' => array(
//					'required' => true,
//					'rule' => array('numeric'),
//					'message' => 'Digits must contain numeric only.'
//				)		
//			),

		'strategy' => array(
			'blank' => array(
				'required' => true,
				'rule' => 'notEmpty',
				'message' => 'Strategy cannot be NULL!',
				'last' => true
			),
			'numeric' => array(
				'required' => true,
				'rule' => array('numeric'),
				'message' => 'Strategy must contain numeric only.'
			)
		),

		'resource_id_1' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'Resource Id 1 must contain numeric only.'
			)
		),
		'resource_id_2' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'Resource Id 2 must contain numeric only.'
			)
		),
		'resource_id_3' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'Resource Id 3 must contain numeric only.'
			)
		),
		'resource_id_4' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'Resource Id 4 must contain numeric only.'
			)
		),
		'resource_id_5' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'Resource Id 5 must contain numeric only.'
			)
		),
		'resource_id_6' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'Resource Id 6 must contain numeric only.'
			)
		),
		'resource_id_7' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'Resource Id 7 must contain numeric only.'
			)
		),
		'resource_id_8' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'Resource Id 8 must contain numeric only.'
			)
		),

		'percentage_1' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'percentage  1 must contain numeric only.'
			)
		),


		'percentage_2' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'percentage  2 must contain numeric only.'
			)
		),
		'percentage_3' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'percentage  3 must contain numeric only.'
			)
		),
		'percentage_4' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'percentage  4 must contain numeric only.'
			)
		),
		'percentage_5' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'percentage  5 must contain numeric only.'
			)
		),
		'percentage_6' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'percentage  6 must contain numeric only.'
			)
		),
		'percentage_7' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'percentage  7 must contain numeric only.'
			)
		),
		'percentage_8' => array(
			'numeric' => array(
				'allowEmpty'=>true,
				'rule' => array('numeric'),
				'message' => 'percentage  8 must contain numeric only.'
			)
		),



	);





	var $default_schema = Array(
		'digits' => array (  'type' => 'string',  'null' => 1,  'default' => '',  'length' => 100),
		'strategy' => array (  'type' => 'integer',  'null' => '',  'default' => '',  'length' => ''),
		'time_profile_id' => array ( 'name' => "Profile", 'type' => 'integer',  'null' => '',  'default' => '',  'length' => ''),

	);

/////////////////////////// for download /////////////
	static $time_profiles = null;
	static $resource_id_1s = null;
	function format_resource_id_1_for_download($value,$data){

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



	static $resource_id_2s = null;
	function format_resource_id_2_for_download($value,$data){
		if(!self::$resource_id_2s){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$resource_id_2s = $model->find("all");
		}
		foreach(self::$resource_id_2s as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}



	static $resource_id_3s = null;
	function format_resource_id_3_for_download($value,$data){

		if(!self::$resource_id_3s){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$resource_id_3s = $model->find("all");
		}
		foreach(self::$resource_id_3s as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}



	static $resource_id_4s = null;
	function format_resource_id_4_for_download($value,$data){

		if(!self::$resource_id_4s){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$resource_id_4s = $model->find("all");
		}
		foreach(self::$resource_id_4s as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}



	static $resource_id_5s = null;
	function format_resource_id_5_for_download($value,$data){
		if(!self::$resource_id_5s){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$resource_id_5s = $model->find("all");
		}
		foreach(self::$resource_id_5s as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}




	static $resource_id_6s = null;
	function format_resource_id_6_for_download($value,$data){

		if(!self::$resource_id_6s){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$resource_id_6s = $model->find("all");
		}
		foreach(self::$resource_id_6s as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}


	static $resource_id_7s = null;
	function format_resource_id_7_for_download($value,$data){
		if(!self::$resource_id_7s){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$resource_id_7s = $model->find("all");
		}
		foreach(self::$resource_id_7s as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}


	static $resource_id_8s = null;
	function format_resource_id_8_for_download($value,$data){

		if(!self::$resource_id_8s){
			App::import("Model",'Resource');
			$model = new Resource;
			self::$resource_id_8s = $model->find("all");
		}
		foreach(self::$resource_id_8s as $time_profile ){
			if($time_profile['Resource']['resource_id'] == $value){
				return $time_profile['Resource']['alias'];
			}
		}
	}





	function format_strategy_for_download($value,$data){
		switch($value){
//			case  0 : return 'Weight';
//			case  1 : return 'Top-Down';
//			case  2 : return 'Round-Robin';
//			default : return 'Unkown';		

			case  0 : return 'P';
			case  1 : return 'T';
			case  2 : return 'R';
			default : return 'U';
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

	function updateinto($data, $id) {
		if(!$data['time_profile_id'])
			$data["time_profile_id"] = 'NULL';
		$update_by = date('Y-m-d H:i:sO');
		$sql = "update product_items set digits = '{$data['digits']}',
			strategy = {$data['strategy']},time_profile_id = {$data['time_profile_id']},update_by = '$_SESSION[sst_user_name]'
			where item_id = {$id}";
		$this->query($sql);
		return TRUE;
	}

	function updateintoCodeName($data, $code_name) {
		if($data['time_profile_id'] == '0')
			$data["time_profile_id"] = 'NULL';
		$update_by = date('Y-m-d H:i:sO');
		$sql = "update product_items set 
			strategy = {$data['strategy']},time_profile_id = {$data['time_profile_id']},update_by = '$_SESSION[sst_user_name]'
			where code_name = '{$code_name}' and product_id = {$data['product_id']}";
		$this->query($sql);
		return TRUE;
	}

	function saveinto($data) {
		if($data['time_profile_id'] == '0' || !isset($data['time_profile_id']) || empty($data['time_profile_id']))
			$data['time_profile_id'] = 'NULL';
		$result = $this->query("insert into product_items (product_id, digits, strategy, time_profile_id)
		values ({$data['product_id']}, '{$data['digits']}', '{$data['strategy']}',{$data['time_profile_id']}) RETURNING item_id;");

		if(!$result){
			return false;
		}

		$item_id = $result[0][0]['item_id'];
		if ($data['resource_id'][0] != '') {
			$data['resource_id'] = array_unique($data['resource_id']);
			foreach ($data['resource_id'] as $key => $val) {
				if($val != '') {
					$percentage = (isset($data['percentage'][$key])&&$data['percentage'][$key]!='')?$data['percentage'][$key]:"NULL";
					$this->query("insert into product_items_resource (item_id, resource_id, by_percentage) values
                            ({$item_id}, {$val}, {$percentage})");
					/* echo "insert into product_items_resource (item_id, resource_id, by_percentage) values
                     ({$item_id}, {$val}, {$percentage})";*/
				}
			}
		}
		return TRUE;
	}


	function saveintoCodeAndCodeName($data) {
		if($data['time_profile_id'] == '0' || !isset($data['time_profile_id']) || empty($data['time_profile_id']))
			$data['time_profile_id'] = 'NULL';
		$result = $this->query("insert into product_items (product_id, digits,code_name, strategy, time_profile_id)
		values ({$data['product_id']}, '{$data['digits']}', '{$data['code_name']}','{$data['strategy']}',{$data['time_profile_id']}) RETURNING item_id;");
		$item_id = $result[0][0]['item_id'];
		if ($data['resource_id'][0] != '') {
			$data['resource_id'] = array_unique($data['resource_id']);
			foreach ($data['resource_id'] as $key => $val) {
				if($val != '') {
					$percentage = (isset($data['percentage'][$key])&&$data['percentage'][$key]!='')?$data['percentage'][$key]:"NULL";
					$this->query("insert into product_items_resource (item_id, resource_id, by_percentage) values
                            ({$item_id}, {$val}, {$percentage})");
					/* echo "insert into product_items_resource (item_id, resource_id, by_percentage) values
                     ({$item_id}, {$val}, {$percentage})";*/
				}
			}
		}
		return TRUE;
	}

	function saveintoCodeName($data,$code_deck_id) {
        if(!$data['code_name']){
            $this->saveintoCodeAndCodeName($data);
            return true;
        }
		$codes = $this->query("select code,name from code where code_deck_id = '$code_deck_id' and name = '".$data['code_name']."'");
		foreach($codes as $code){
			$data['digits'] = $code[0]['code'];
			$data['code_name'] = $code[0]['name'];
			$this->saveintoCodeAndCodeName($data);
		}
        return true;
	}


	function jsresource($item_id) {
		$sql = "select resource.cps_limit,resource.capacity,
product_items_resource.resource_id,product_items_resource.by_percentage,client_id,

(select rate from rate where rate.rate_table_id = resource.rate_table_id  and rate.code = (select digits from product_items where item_id = product_items_resource.item_id)

and rate.effective_date < now() and (rate.end_date > now() or rate.end_date is null)) as rate 

from product_items_resource
left join resource on
resource.resource_id=product_items_resource.resource_id 

where product_items_resource.item_id = {$item_id} 

order by product_items_resource.id asc";
		$result = $this->query($sql);
		return $result;
	}


	function updatere($data) {
		$product_id = $data['product_id'];
		$digits = $data['digits'];
//		$sql = "update trunk_relation_route set prefix='' WHERE static={$product_id}";
		$this->query("delete from product_items_resource where item_id={$data['item_id']}");
		$data['resource_id'] = array_unique($data['resource_id']);
		if ($data['resource_id'][0] != ''||count($data['resource_id'])>1) {
			foreach ($data['resource_id'] as $key => $val) {
				if($val != '') {
					$percentage = (isset($data['percentage'][$key])&&$data['percentage'][$key]!='')?$data['percentage'][$key]:"NULL";
					$this->query("insert into product_items_resource (item_id, resource_id, by_percentage) values
                                ({$data['item_id']}, {$val}, {$percentage})");
				}
			}
		}
	}


	function updaterecodeName($data,$code_name) {
		$items = $this->query("select item_id from product_items where product_id = {$data['product_id']} and code_name = '".$code_name."'");
		foreach($items as $item){
			$data['item_id'] = $item[0]['item_id'];
			$this->updatere($data);
		}
	}


	function deletere($item_id){
		$this->query("delete from product_items_resource where item_id={$item_id}");
	}

	function deletereByIds($ids){
		$this->query("delete from product_items_resource where item_id in ({$ids})");
	}

	function deletereAll($id){
		$this->query("delete from product_items_resource where item_id in (select item_id from product_items where product_id = {$id})");
	}



	// for upload check duplicate
	function check_duplicate_for_upload($data){
		return $this->find("first",array('conditions' => "product_id = '{$data['Productitem']['product_id']}' and digits='{$data['Productitem']['digits']}'" ));
	}


	function get_foreign_name($id){
		return  $this->query(" select  name from  product  where  product_id=$id;");

	}


}