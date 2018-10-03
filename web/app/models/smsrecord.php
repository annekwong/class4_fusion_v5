<?php
class Smsrecord extends AppModel{
	
	//短信记录
	var $name = 'Smsrecord';
	var $useTable = "sms_record";
	var $primaryKey = "msg_record_id";
	
	

	/**
	 * 验证
	 * @return true 有错误信息
	 *     false 没有错误信息
	 */
	function validate_block($data,$post_arr){
  $error_flag=false;//错误信息标志
	
	  $res_block_id=$this->getkeyByPOST('res_block_id',$post_arr);
	   $engress_res_id=$data['Blocklist']['engress_res_id'];
			$digit=$data['Blocklist']['digit'];
		 $client_id=$data['Blocklist']['client_id'];
		 	$ingress_res_id=$data['Blocklist']['ingress_res_id'];//容许欠费
		
		 //验证落地网关
		 if(empty($engress_res_id)){
    	   $this->create_json_array('#BlocklistEngressResId',101,__('egressnamenull',true));
    	   $error_flag=true;//有错误信息
    }
    
			 if(empty($digit)){
    	   $this->create_json_array('#BlocklistDigit',101,__('pleaseinputprefix',true));
    	   $error_flag=true;//有错误信息
    }


    return $error_flag;
	}
	
/**
 * 验证客户名字不能重复
 * @param unknown_type $res_id
 * @param unknown_type $a
 */	
function check_name($client_id,$name){
	
	
		$name="'".$name."'";
	empty($client_id)?$sql="select count(*) from client where name=$name ":
	$sql="select count(*) from client where name=$name  and client_id<>$client_id";
	$c= $this->query($sql);
	if(empty($c)){
	return 0;
	}else{
	return $c[0][0]['count'];}
}	




/**
 * 添加Client or 更新Client
 * @param unknown_type $data
 * @param unknown_type $post_arr
 * @return 
 */
function saveOrUpdate($data,$post_arr){
	  $msgs=$this->validate_block($data,$post_arr);
	  if(!empty($msgs)){
	  	return false;//add fail
	  }else{
	  	 $res_block_id=$this->saveOrUpdate_block($data,$post_arr);//添加或者更新
	  	 return true;//add succ
	  }
}


/**
 * 添加Client or 更新Client
 * @param unknown_type $data
 * @param unknown_type $post_arr
 */
	function saveOrUpdate_block($data,$post_arr){

	  $res_block_id=$this->getkeyByPOST('res_block_id',$post_arr);
	  pr($data);
	  //
	  if(empty($data['Blocklist']['ingress_res_id'])){
	  $data['Blocklist']['ingress_res_id']=NULL;
	  }
		if(!empty($res_block_id)){
			//更新
		 $data['Blocklist']['res_block_id']=$res_block_id;
		 $this->save ( $data ['Blocklist']);
	}else{
		//添加
		$this->save ( $data ['Blocklist'] );
		$res_block_id = $this->getlastinsertId ();
	}
return $res_block_id;
	
	}

	

	


/**
 * 普通查询
 * @paranknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function findAll($currPage=1,$pageSize=13,$search=null,$adv_search=null){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 $login_type=$_SESSION['login_type'];
	 //damin
	 if($login_type==1){
	 $sql1="select count(msg_record_id) as c from sms_record as e where 1=1 $adv_search";
	  $sql2=" ";
	  $sql3 = "select e.msg_record_id,e.receive_code,e.cost,e.send_content,e.type,e.sms_content,r.card_number,status,time
		    from  sms_record as e
		   left join (select card_number ,card_id  from card  ) r   on  r.card_id=e.card_id where 1=1 $adv_search";
	  
	  if (!empty($search)){
	  	$sql1 .= " and card_id in (select card_id from card where card_number like '%$search%')";
	  	$sql3 .= " and r.card_number like '%$search%'";
	  }
	 }
	 //reseller
	 if($login_type==2){
	 		$reseller_id = $_SESSION['sst_reseller_id'];
	 	 $sql1="select count(msg_record_id) as c from sms_record as e where  e.reseller_id=$reseller_id $adv_search";
	   $sql2="  and  e.reseller_id=$reseller_id";
	   
	    $sql3 = "select e.msg_record_id,e.receive_code,e.cost,e.send_content,e.type,e.sms_content,r.card_number,status,time
		    from  sms_record as e
		   left join (select card_number ,card_id  from card  ) r   on  r.card_id=e.card_id where 1=1 $adv_search";
	    
	 if (!empty($search)){
	  	$sql1 .= " and card_id in (select card_id from card where card_number like '%$search%')";
	  	$sql3 .= " and r.card_number like '%$search%'";
	  }
	 }
	 //client
	 if($login_type==3){
	 	 	$client_id = $_SESSION['sst_client_id'];
	  $sql1="select count(msg_record_id) as c from sms_record as e where  e.client_id=$client_id $adv_search";
	   $sql2="  and  e.client_id=$client_id";
	    $sql3 = "select e.msg_record_id,e.receive_code,e.cost,e.send_content,e.type,e.sms_content,r.card_number,status,time
		    from  sms_record as e
		   left join (select card_number ,card_id  from card  ) r   on  r.card_id=e.card_id where 1=1 $adv_search";
	    
	 if (!empty($search)){
	  	$sql1 .= " and card_id in (select card_id from card where card_number like '%$search%')";
	  	$sql3 .= " and r.card_number like '%$search%'";
	  }
	 }
	 
		 if($login_type==4){
	 	 	$card_id = $_SESSION['sst_card_id'];
	 	 	if (empty($card_id)){$card_id = $_SESSION['card_id'];}
	  $sql1="select count(msg_record_id) as c from sms_record as e  where  e.card_id=$card_id $adv_search";
	   $sql2="  and  e.card_id=$card_id";
	    $sql3 = "select e.msg_record_id,e.receive_code,e.cost,e.send_content,e.type,e.sms_content,r.card_number,status,time
		    from  sms_record as e
		   left join (select card_number ,card_id  from card  ) r   on  r.card_id=e.card_id where 1=1 $adv_search";
	    
		 if (!empty($search)){
	  	$sql1 .= " and card_id in (select card_id from card where card_number like '%$search%')";
	  	$sql3 .= " and r.card_number like '%$search%'";
	  }
	 }
	 
	 //user
if($login_type==5 ||$login_type==6){
		$user_id = $_SESSION['sst_user_id'];    
$sql1="select count(msg_record_id) as c from sms_record as e where  user_id=$user_id $adv_search";
 $sql2="  and e.user_id=$user_id";
 
  $sql3 = "select e.msg_record_id,e.receive_code,e.send_content,e.cost,e.type,e.sms_content,r.card_number,status,time
		    from  sms_record as e
		   left join (select card_number ,card_id  from card  ) r   on  r.card_id=e.card_id where 1=1 $adv_search";
  
if (!empty($search)){
	  	$sql1 .= " and card_id in (select card_id from card where card_number like '%$search%')";
	  	$sql3 .= " and r.card_number like '%$search%'";
	  }

}

	if($login_type==7){
		$user_id = $_SESSION['sst_card_sip_id'];    
$sql1="select count(msg_record_id) as c from sms_record as e where  card_sip_id=$user_id $adv_search";
 $sql2="  and e.card_sip_id=$user_id";
 
  $sql3 = "select e.msg_record_id,e.receive_code,e.send_content,e.type,e.cost,e.sms_content,r.sip_code,status,time
		    from  sms_record as e
		   left join (select sip_code ,card_sip_id  from card_sip  ) r   on  r.card_sip_id=e.card_sip_id where 1=1 $adv_search";

  
	if (!empty($search)){
	  	$sql1 .= " and card_id in (select card_id from card where card_number like '%$search%')";
	  	$sql3 .= " and r.card_number like '%$search%'";
	  }
}
	 
	
	 	$totalrecords = $this->query($sql1);
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "
$sql3
		   $sql2
	order by e.msg_record_id   desc  	limit '$pageSize' offset '$currPage'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	
	
	
	
	
	
	/**
	 * 模糊查询
	 * @param unknown_type $condition
	 * @param unknown_type $currPage
	 * @param unknown_type $pageSize
	 */
function likequery($key,$currPage=1,$pageSize=13){
		 $login_type=$_SESSION['login_type'];
	 //damin
	 if($login_type==1){
	 $sql1="select count(msg_record_id) as c from sms_record ";
	  $sql2=" ";
	 }
	 //reseller
	 if($login_type==2){
	 		$reseller_id = $_SESSION['sst_reseller_id'];
	 	 $sql1="select count(msg_record_id) as c from sms_record where  reseller_id=$reseller_id ";
	   $sql2="  where  e.reseller_id=$reseller_id";
	 }
	 //client
	 if($login_type==3){
	 	 	$client_id = $_SESSION['sst_client_id'];
	  $sql1="select count(msg_record_id) as c from sms_record where  client_id=$client_id ";
	   $sql2="  where  e.client_id=$client_id";
	 }
	 
	 //user
if($login_type==5 ||$login_type==6){
		$user_id = $_SESSION['sst_user_id'];    
$sql1="select count(msg_record_id) as c from sms_record where  user_id=$user_id ";
 $sql2="  where e.user_id=$user_id";

}
	 
	
	
	$condition="'%".$key."%'";
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(msg_record_id) as c
	 	from sms_record  as b  
	 	 where code   like $condition
	 	or (select count(*)>0 from client   where client_id=b.client_id 	 	and name like $condition )
	 	
	 	or(select  count(*)>0 from reseller where reseller_id =b.reseller_id and name like $condition)
	 	");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select e.msg_record_id,e.code,e.message,e.cost,e.type,r_name,u_name,c_name,e.time
		    from  sms_record as e
		   left join (select name as r_name,reseller_id  from reseller  ) r   on  r.reseller_id=e.reseller_id
		    left join (select name as u_name,user_id  from users  ) u   on  u.user_id=e.user_id
		      left join (select name as c_name,client_id  from client  ) c   on  c.client_id=e.client_id
		  	 	 where e.code   like $condition 
	 	or (select count(*)>0 from client   where client_id=e.client_id 	 	and name like $condition )
	 	
	 	or(select  count(*)>0 from reseller where reseller_id =e.reseller_id and name like $condition)
	order by e.msg_record_id   desc  	limit '$pageSize' offset '$currPage'";
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
}	
	
	
	
	/**
 * 高级搜索
 * @param unknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function Advancedquery($data,$currPage=1,$pageSize=13){
		
		//解析搜索条件
		$condition="where   ";
		$i=0;
		$len=intval(count($data['Blocklist']));
		
		
		foreach ($data['Blocklist'] as $key=>$value){

			//判断是否存在搜索条件
			if($value==''){
		  	continue;
			}
		$tmp= "resource_block.".$key."='".$value."'  and   "; 
		$condition=$condition.$tmp;
		$i++;
		}

		  
$where=substr($condition,0,strrpos($condition, 'a'));
		//pr($where);
		
		
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(res_block_id) as c from resource_block  $where");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select resource_block.res_block_id,e.egress_name,i.ingress_name,digit
		    from  resource_block
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=resource_block.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=resource_block.ingress_res_id
		
		    $where
	order by res_block_id  	limit '$pageSize' offset '$currPage'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	





}