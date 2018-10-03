<?php
class Userauthip extends AppModel{
	var $name = 'Userauthip';
	var $useTable = "user_auth_ip";
	var $primaryKey = "id";
	
	


	function  save_auth_ip0($id){
		pr($_POST['accounts']);
		$account=Array();
		if(!empty($id)){
			$this->saveHost($account, $id);
			return;
		}
	}

	
	/**
	 * 添加和更新网关组   
	 * 
	 */
	function saveOrUpdateHost($data,$post_arr,$account=Array()){
		  $res_id=$this->saveOrUpdate_resource($data,$post_arr);//添加或者更新
		   
		  if (!empty($res_id)){
		  	 $this->saveHost($account,$res_id);  #保存host 
		    isset($post_arr['resource'])? $this->saveResouce($post_arr['resource'],$res_id):'';
	      return $res_id;
		  	}
	}
	/**
	 * 添加网关组
	 */	
	function saveOrUpdate_resource($data,$post_arr){
		$client_id=$data['Users']['user_id'];
		//find client  rateble
		$list=$this->query("select user_id from  users  where  user_id=$client_id");
		$rate_table_id=$list[0][0]['user_id'];
		
	  $res_id=$this->getkeyByPOST('user_id',$post_arr);
		if(!empty($res_id)){
			 $data['Users']['user_id']=$res_id;
			 $this->save ( $data ['Gatewaygroup']);
		}else{
				$this->save ( $data ['Users'] );
				$res_id=$this->getLastInsertID();
		}
		return $res_id;
	}
	/**
	 * 从界面上的$_POST变量中获取key
	 * @param  $post_arr
	 * @param $key 
	 */
	function getkeyByPOST($key, $post_arr) {
		isset ( $post_arr [$key] ) ? ($id = $post_arr [$key]) : $id = '';
		return $id;
	}
    function saveHost($account,$id){
    	$this->loadModel('Users');
		$count=count($account['ip']);
		$this->Users->bindModel(Array('hasMany'=>Array('Userauthip')));
		$this->Users->deleteAll(Array("user_id='$id'"));
		for($i=0;$i<$count;$i++){
			$data=Array();
			$data['user_id']=$id;
			
			if(is_ip($account['ip'][$i])){
				$data['ip']=$account['ip'][$i];
				if(array_keys_value($account,'need_register.'.$i)){
					$data['ip']=$account['ip'][$i].'/'.array_keys_value($account,'need_register.'.$i);
				}
			}
			$this->Userauthip->save($data);
			$this->Userauthip->id=false;
		}
	}
#保存resource prefix
	function saveResouce($mydata=null,$res_id=null){
	
	  if(!empty($mydata)){
	  	for ($i=0;$i<count($mydata['id']);$i++){
	  	  if(!empty($mydata['id'][$i])){
	  	  		$mydata ['tech_prefix'] [$i] = trim ( $mydata ['tech_prefix'] [$i] );
	  	     $sql="update resource_prefix set tech_prefix='".$mydata['tech_prefix'][$i]."',route_strategy_id=".$mydata['route_strategy_id'][$i].",rate_table_id=".$mydata['rate_table_id'][$i]." where id=".$mydata['id'][$i] ;

	  	     $this->query($sql);
	  	  } else{
					
					$mydata ['tech_prefix'] [$i] = trim ( $mydata ['tech_prefix'] [$i] );
					$sql = "insert into resource_prefix(resource_id,tech_prefix,route_strategy_id,rate_table_id) values(" . $res_id . ",'" . $mydata ['tech_prefix'] [$i] . "'," . $mydata ['route_strategy_id'] [$i] . "," . $mydata ['rate_table_id'] [$i] . ") "; 
	  	  	
	  	  	
	  	    $this->query($sql);
	  	          }
	    	} 
	      }
	}
}
?>