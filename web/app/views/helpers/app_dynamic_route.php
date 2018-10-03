<?php class AppDynamicRouteHelper extends AppHelper{
	
	
	
	

	
	function show_egress_id_str($list){
		$tmp_arr=array();
		for($i=1;$i<9;$i++){
			$key="resource_id_$i";
			if(!empty($list[$key])){
				$tmp_arr[]=$list[$key];
			}
		}
		return implode(',',$tmp_arr);
	}
	
	function format_client_options($clients){
		$options = array();
		foreach($clients as $client){
			$options[$client['Client']['client_id']] = $client['Client']['name']; 
		}
		return $options;
	}
	
	function format_client_and_resource_options ($clients){
		$options = array();
		//$options[0] = array("id"=>0, "name"=>"");
		foreach($clients as $client){
			$egress = array();
			$ingress = array();
			foreach($client['Resource'] as $resource){
				if($resource['egress']){
					$egress[$resource['resource_id']] = $resource['alias'];
				}
			if($resource['ingress']){
					$ingress[$resource['resource_id']] = $resource['alias'];
				}
			}
			$options[$client['Client']['client_id']] = array(
							'id' => $client['Client']['client_id'] , 
							'name' => $client['Client']['name'],
							'egress' => $egress,
							'ingress' => $ingress
			); 
		}
		return $options;
	}
	
	function find_egress($res_egress_id,$egresses){
		foreach($egresses as $egress){
			if($egress['Resource']['resource_id'] == $res_egress_id){
				return $egress;
			}
		}
		return null;
	}
	
	function find_client($client_id,$clients){
		foreach($clients as $client){
			if($client['Client']['client_id'] == $client_id){
				return $client;
			}
		}
		return null;
	}
}?>