<?php

//支付通道
class Paymentplatform extends AppModel{
	var $name = 'Paymentplatform';
	var $useTable = "payment_platform";
	var $primaryKey = "payment_platform_id";
	
	

	

	
	/**
	 * 验证
	 * @return true 有错误信息
	 *     false 没有错误信息
	 */
	function validate_platform($data,$post_arr){
  $error_flag=false;//错误信息标志
	
	  $payment_platform_id=$this->getkeyByPOST('payment_platform_id',$post_arr);
	   $name=$data['Paymentplatform']['name'];
			$ip=$data['Paymentplatform']['ip'];
		 $account=$data['Paymentplatform']['account'];
		 	$password=$data['Paymentplatform']['password'];
		
		 //验证落地网关
		 if(empty($name)){
    	   $this->create_json_array('#PaymentplatformName',101,__('pleaseinputpaymentflatformname',true));
    	   $error_flag=true;//有错误信息
    }
    
			 if(empty($ip)){
    	   $this->create_json_array('#PaymentplatformIp',101,__('pleaseinputip',true));
    	   $error_flag=true;//有错误信息
    }
				 if(empty($account)){
    	   $this->create_json_array('#PaymentplatformAccount',101,__('pleaseinputaccountname',true));
    	   $error_flag=true;//有错误信息
    }
				 if(empty($password)){
    	   $this->create_json_array('#PaymentplatformPassword',101,__('pleaseinputpass',true));
    	   $error_flag=true;//有错误信息
    }
		  $c=$this->check_name($payment_platform_id,$name);
    if($c!=0){
      $this->create_json_array('#PaymentplatformName',301,__('paymentplatformnameexist',true));
      $error_flag=true;
    }
    return $error_flag;
	}
	
/**
 * 验证支付通道名字不能重复
 * @param unknown_type $res_id
 * @param unknown_type $a
 */	
function check_name($payment_platform_id,$name){
		$name="'".$name."'";
	empty($payment_platform_id)?$sql="select count(*) from payment_platform where name=$name ":
	$sql="select count(*) from payment_platform where name=$name  and payment_platform_id<>$payment_platform_id";
	$c= $this->query($sql);
	if(empty($c)){
	return 0;
	}else{
	return $c[0][0]['count'];}
}	




/**
 * 添加支付通道 or 更新支付通道
 * @param unknown_type $data
 * @param unknown_type $post_arr
 * @return 
 */
function saveOrUpdate($data,$post_arr){
	  $msgs=$this->validate_platform($data,$post_arr);
	  if(!empty($msgs)){
	  	return false;//add fail
	  }else{
	  	 $res_block_id=$this->saveOrUpdate_platform($data,$post_arr);//添加或者更新
	  	 return true;//add succ
	  }
}


	function saveOrUpdate_platform($data,$post_arr){

	  $payment_platform_id=$this->getkeyByPOST('payment_platform_id',$post_arr);


		if(!empty($payment_platform_id)){
			//更新
		 $data['Paymentplatform']['payment_platform_id']=$payment_platform_id;
		 $this->save ( $data ['Paymentplatform']);
	}else{
		//添加
		$this->save ( $data ['Paymentplatform'] );
		$payment_platform_id = $this->getlastinsertId ();
	}
return $payment_platform_id;
	
	}

	


/**
 * 普通查询
 * @paranknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function downlist($currPage=1,$pageSize=13){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	
		$reseller_id = $_SESSION['sst_reseller_id']; 
	 	$totalrecords = $this->query("select count(id) as c from error_info where  status>10 and  reseller_id=$reseller_id");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1; 
		$pageSize = $page->getPageSize();
		$sql = "select e.id,e.type,r_name,u_name,uploadtime,onloadtime,status
		    from  error_info as e
		   left join (select name as r_name,reseller_id  from reseller  ) r   on  r.reseller_id=e.reseller_id
		    left join (select name as u_name,user_id  from users  ) u   on  u.user_id=e.user_id
		    where  status>10  and e.reseller_id=$reseller_id
	order by uploadtime  desc  	limit '$pageSize' offset '$currPage'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	
	
	
	


/**
 * 普通查询
 * @paranknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function findAll($currPage=1,$pageSize=13){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	

	 
		
		$reseller_id = $_SESSION['sst_reseller_id']; 
	 	$totalrecords = $this->query("select count(payment_platform_id) as c from payment_platform");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select *
		    from  payment_platform 
	order by payment_platform_id  desc  	limit '$pageSize' offset '$currPage'";
		
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
	
	$condition="'%".$key."%'";
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(res_block_id) as c
	 	from resource_block  as b  
	 	 where digit::varchar   like $condition 
	 	or (select count(*)>0 from resource   where resource_id=b.engress_res_id 	 	and alias like $condition )
	 	
	 	or(select  count(*)>0 from resource where resource_id =b.ingress_res_id and alias like $condition)
	 	");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
   $sql="select b.res_block_id,e.egress_name,i.ingress_name,b.digit
		    from  resource_block   as b
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=b.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=b.ingress_res_id
	 	where digit::varchar   like $condition 
	 	or (select count(*)>0 from resource   where resource_id=b.engress_res_id 	 	and alias like $condition )
	 	or(select  count(*)>0 from resource where resource_id =b.ingress_res_id and alias like $condition)

	order by res_block_id  	limit '$pageSize' offset '$currPage'";
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