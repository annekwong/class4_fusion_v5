<?php
class Giftamount extends AppModel{
	
	//充值赠送话费规则
	var $name = 'Giftamount';
	var $useTable = "sales_strategy_charges";
	var $primaryKey = "sales_strategy_charges_id";
	
	
	/**
	 * 更新一条记录
	 * @param unknown_type $data
	 */
function update_g($data){
	  $msgs=$this->validate_one($data);//验证
	  if(!empty($msgs)){
	  	return false;//add fail
	  }else{
	  	 $this->save($data);//更新
	  	 return true;//add succ
	  }

}	
	
	/*8
	 * 验证单个记录
	 */
function validate_one($data){
	  $error_flag=false;//错误信息标志
$refill_amount=$data['Giftamount']['refill_amount'];
$gift_amount=$data['Giftamount']['gift_amount'];
$bonus_credit=$data['Giftamount']['bonus_credit'];
if(empty($refill_amount)){
 $this->create_json_array('#GiftamountRefillAmount',101,__('pleaserefillamount',true));
    	   $error_flag=true;//有错误信息
}

if(empty($gift_amount)){
 $this->create_json_array('#GiftamountGiftAmount',101,__('pleasegiftamount',true));
    	   $error_flag=true;//有错误信息
}

if(empty($bonus_credit)){
 $this->create_json_array('#GiftamountBonusCredit',101,__('pleasebonus_credit',true));
    	   $error_flag=true;//有错误信息
}



    return $error_flag;
}	

	/**
	 * 验证
	 * @return true 有错误信息
	 *     false 没有错误信息
	 */
	function validate_salestra($data,$post_arr){

	 $error_flag=false;//错误信息标志
	  $sales_strategy_id=$this->getkeyByPOST('sales_strategy_id',$post_arr);
	  $name=$data['Salestrateg']['name'];
	  $extended_days=$data['Salestrateg']['extended_days'];

		
		 //验证落地网关
		 if(empty($name)){
    	   $this->create_json_array('#SalestrategName',101,__('pleaseinputroutename',true));
    	   $error_flag=true;//有错误信息
    }
    
	  $c=$this->check_name($sales_strategy_id,$name);
    if($c!=0){
      $this->create_json_array('#SalestrategName',301,__('routenameexist',true));
      $error_flag=true;
    }
	    if(!empty($extended_days)){
	    		if (!preg_match('/^[0-9]+$/',$extended_days)) {
     	 $this->create_json_array('#SalestrategExtendedDays',101,__('extended_daysvalid',true));
    	   $error_flag=true;//有错误信息

     }
    }
   


    return $error_flag;
	}
	
/**
 * 验证客户名字不能重复
 * @param unknown_type $res_id
 * @param unknown_type $a
 */	
function check_name($sales_strategy_id,$name){
	
	
		$name="'".$name."'";
	empty($sales_strategy_id)?$sql="select count(*) from sales_strategy where name=$name ":
	$sql="select count(*) from sales_strategy where name=$name  and sales_strategy_id<>$sales_strategy_id";
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
	  //$msgs=$this->validate_salestra($data,$post_arr);//验证
	   $msgs='';//验证
	  if(!empty($msgs)){
	  	return false;//add fail
	  }else{
	  	 $this->save_charge($data,$post_arr);//添加或者更新
	  	 return true;//add succ
	  }
}


/**
 * 添加充值送话费规则
 * @param unknown_type $post_arr
 */
function save_charge($data,$post_arr){
	    $sales_strategy_id=$this->getkeyByPOST('sales_strategy_id',$post_arr);
	    $refill_amount_arr = $post_arr['refill_amount'];
	    $gift_amount_arr = $post_arr['gift_amount'];
	    $bonus_credit_arr =$post_arr['bonus_credit'];
	    $basic_amount_arr =$post_arr['basic_amount'];
	
			$all_size=intval(count($refill_amount_arr));//总记录
				for($i = 0; $i < $all_size; $i ++) {
					$this->query("insert into sales_strategy_charges (sales_strategy_id,basic_amount,refill_amount,gift_amount,bonus_credit)
					  values($sales_strategy_id,$basic_amount_arr[$i],$refill_amount_arr[$i],$gift_amount_arr[$i],$bonus_credit_arr[$i])");	
					}
									
				
					
}
	




/**
 * 普通查询
 * @paranknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function findAll($currPage=1,$pageSize=10){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(sales_strategy_id) as c from sales_strategy");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$sql = "select sales_strategy.sales_strategy_id,sales_strategy.name,a.charges_cnt,c.card_cnt,rate.rate_table_name,regular_customer,new_customer,
		max_balance,extended_days
		    from  sales_strategy 
		   left join (select count(*)as  charges_cnt,sales_strategy_id from sales_strategy_charges    group by  sales_strategy_id) a   
		   on  a.sales_strategy_id=sales_strategy.sales_strategy_id
		   
		      left join (select count(*)as  card_cnt,sales_strategy_id from card_series    group by  sales_strategy_id) c   
		   on  c.sales_strategy_id=sales_strategy.sales_strategy_id
		   
		   left join (select name  as rate_table_name, rate_table_id  from rate_table   ) rate   
		   on  rate.rate_table_id=sales_strategy.new_rate_table_id
		
		    
	order by sales_strategy.sales_strategy_id  	limit '$pageSize' offset '$offset'";
		
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
function likequery($key,$currPage=1,$pageSize=10){
	
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
		$offset=$currPage*$pageSize;
   $sql="select b.res_block_id,e.egress_name,i.ingress_name,b.digit
		    from  resource_block   as b
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=b.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=b.ingress_res_id
	 	where digit::varchar   like $condition 
	 	or (select count(*)>0 from resource   where resource_id=b.engress_res_id 	 	and alias like $condition )
	 	or(select  count(*)>0 from resource where resource_id =b.ingress_res_id and alias like $condition)

	order by res_block_id  	limit '$pageSize' offset '$offset'";
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
}	
	
	
	
	/**
 * 高级搜索
 * @param unknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function Advancedquery($data,$currPage=1,$pageSize=10){
		
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
		$offset=$currPage*$pageSize;
		$sql = "select resource_block.res_block_id,e.egress_name,i.ingress_name,digit
		    from  resource_block
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=resource_block.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=resource_block.ingress_res_id
		
		    $where
	order by res_block_id  	limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	



	
	/**
	 * 查找充值话费规则
	 * @param unknown_type $role_id
	 */
	function findAll_charges($sales_strategy_id){

return 
$this->query("select refill_amount, gift_amount  ,bonus_credit  from sales_strategy_charges where sales_strategy_id=$sales_strategy_id
    order by sales_strategy_charges_id");


}	
		public function findgift_amount($currPage=1,$pageSize=10,$id){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(sales_strategy_charges_id) as c from sales_strategy_charges  where sales_strategy_id=$id");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$sql = "select *  from  sales_strategy_charges  where sales_strategy_id=$id
order by sales_strategy_charges_id  	limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	
	


}