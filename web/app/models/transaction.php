<?php
class Transaction extends AppModel{
	var $name = 'Transaction';
	var $useTable = "client_payment";
	var $primaryKey = "client_payment_id";
	var $class4_view_client_transactions ="(SELECT ap.client_payment_id, ap.approved, payment_type::text AS tran_type, ap.payment_time AS \"time\", 
	ap.payment_method::text as payment_method,
ap.client_id::text AS client_id, c.name AS client_name,c.company AS client_company, ap.amount::text AS client_cost,
 ap.result::text AS result, b.balance::text AS current_balance
   FROM client_payment ap
   LEFT JOIN client c ON c.client_id::text = ap.client_id::text
   LEFT JOIN c4_client_balance b ON b.client_id::text = ap.client_id::text
    where c.name is not null

    UNION 
    
     SELECT NULL::integer AS client_payment_id, true AS approved,
  '5'::text AS tran_type, ab.invoice_time  as time,'' as payment_method , ab.client_id::text, c.name AS client_name, c.company AS client_company, ab.total_amount::text AS client_cost, 
  1::text  AS result, b.balance::text AS current_balance 
 FROM invoice ab LEFT JOIN client c ON c.client_id::text = ab.client_id::text
  LEFT JOIN c4_client_balance b ON b.client_id::text = ab.client_id::text where c.name is not null
 
    
   ) as class4_view_client_transactions";
	
//	UNION 
// SELECT NULL::integer AS client_payment_id, true AS approved, '4'::text AS tran_type, ab.\"time\",'' as payment_method
//, ab.ingress_client_id AS client_id, c.name AS client_name, ab.ingress_client_cost::text AS client_cost, ab.ingress_client_bill_result AS result, b.balance::text AS current_balance
//   FROM client_cdr ab
//   LEFT JOIN client c ON c.client_id::text = ab.ingress_client_id::text
//   LEFT JOIN client_balance b ON b.client_id::text = ab.egress_client_id::text
//    where c.name is not null
//    
        
        public function get_one_invoice($client_id, $invoices, $type)
        {
            
            $exclude = '';
            
            if ($invoices != NULL) {
                array_walk($invoices, create_function('&$item, $key', '$item = "\'{$item}\'";'));
                $invoices = implode(',', $invoices);
                $exclude = " and invoice_number not in ($invoices)";
            } 
            
            if ($type == 'received') {
                $sql = "SELECT  * FROM  invoice WHERE client_id = {$client_id} AND paid = FALSE  
                        AND state in (0, 1 , 9) and total_amount > 0 AND type = 0 $exclude ORDER BY invoice_time ASC LIMIT 1";
            } else {
                $sql = "SELECT  * FROM  invoice WHERE client_id = {$client_id} AND paid = FALSE
                        AND state = 0 AND type = 3 and total_amount > 0 $exclude ORDER BY invoice_time ASC LIMIT 1";
            }
            
            $data = $this->query($sql);
            return $data;
        }

    public function get_outstanding_invoices($client_id){
        $sql = <<<SQL
SELECT invoice.invoice_number,invoice.invoice_time,invoice.due_date,invoice.total_amount,invoice.pay_amount,COALESCE(payment, 0) payment
FROM invoice
LEFT join (select invoice_number,
COALESCE(sum(client_payment.amount), 0) as payment
from client_payment
where (payment_type = 3 or payment_type = 4)
group by invoice_number
) as c_p on c_p.invoice_number = invoice.invoice_number 
WHERE invoice.client_id='{$client_id}'
AND paid = FALSE AND state in (0, 1 , 9)
AND total_amount > 0 AND type = 0
AND COALESCE(payment, 0) < invoice.total_amount
SQL;

        return $this->query($sql);

    }
        
	public function findAll($currPage=1,$pageSize=10,$search_res=null,$user_type=null,$search=null,$adv_search=null){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		$sql1="select count(*) as c from transation as a where user_type=$user_type $adv_search";	 
	 if (!empty($search_res)) {
	 		$sql1 .= " and id = $search_res"; 
	 	}
	 	$login_type = $_SESSION['login_type'];
	 		if (!empty($search)) {
	 			if ($user_type == 2){
	 				$sql1 .= " and id in (select reseller_id from reseller where name like '%$search%')";
	 			} else if ($user_type == 3) {
	 				$sql1 .= " and id in (select client_id from client where name like '%$search%')";
	 			} else  if ($user_type == 4){
	 				$sql1 .= " and id in (select card_id from card where card_number like '%$search%')";
	 			}
	 		}
	 	if($login_type==2){
	 		$sql1 .= " and id = {$_SESSION['sst_reseller_id']}";
	 	}
		if($login_type==3){
	 		$sql1 .= " and id = {$_SESSION['sst_client_id']}";
	 	}
	if($login_type==4){
	 		$sql1 .= " and id = {$_SESSION['sst_card_id']}";
	 	}
	 	$totalrecords = $this->query($sql1);
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		//$page = $page->checkRange($page);//检查当前页范围
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select * from  transation a where user_type=$user_type $adv_search";
	if (!empty($search_res)) {
	 		$sql .= " and id = $search_res";
	 	}
	if($login_type==2){
	 		$sql1 .= " and id = {$_SESSION['sst_reseller_id']}";
	 	}
		if($login_type==3){
	 		$sql1 .= " and id = {$_SESSION['sst_client_id']}";
	 	}
	if($login_type==4){
	 		$sql1 .= " and id = {$_SESSION['sst_card_id']}";
	 	}
	if (!empty($search)) {
	 			if ($user_type == 2){
	 				$sql .= " and id in (select reseller_id from reseller where name like '%$search%')";
	 			} else if ($user_type == 3) {
	 				$sql .= " and id in (select client_id from client where name like '%$search%')";
	 			} else  if ($user_type == 4){
	 				$sql .= " and id in (select card_id from card where card_number like '%$search%')";
	 			}
	 		}
	 	$sql .= " order by id   desc  	limit '$pageSize' offset '$currPage'";
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	
	public function refill_findAll($result){
	 empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 10:	$pageSize = $_GET['size'];
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 $login_type=$_SESSION['login_type'];
	 $privilege='';
	 //client
	 if($login_type==3){
		$privilege= " and (id = {$_SESSION['sst_client_id']})";
	 }
	 //充值结果 
		 $result_where=!empty($result)?"     and (result={$result})":'';
//模糊搜索
	 $like_where=!empty($_GET['search'])?" and (name like '%{$_GET['search']}%'  or  id::text like '%{$_GET['search']}%'  or amount::text like '%{$_GET['search']}%')":'';
 	 //起始金额
	 $start_amount_where=!empty($_GET['start_amount'])?"  and (amount::numeric>={$_GET['start_amount']})":'';
//结束金额
	 $end_amount_where=!empty($_GET['end_amount'])?"     and (amount::numeric<={$_GET['end_amount']})":'';
	 //充值方式
	 $tran_type_where='';
	 if(isset($_GET['data'])){
	 	 $tran_type_where=($_GET['data']['Transaction']['tran_type']!='')?" and (tran_type={$_GET['data']['Transaction']['tran_type']})":'';
	 }
	 //路由伙伴
	 $client_where=!empty($_GET ['query'] ['id_clients'])?"  and (id={$_GET ['query'] ['id_clients']})":'';
	 $card_where=!empty($_GET ['query'] ['id_cards'])?"  and (id={$_GET ['query'] ['id_cards']})":'';
	 $reseller_where=!empty($_GET ['query'] ['id_resellers'])?"  and (id={$_GET ['query'] ['id_resellers']})":'';
 	 //按时间搜索
 	 $date_where='';
 	 if(isset($_GET['start_date'])||isset($_GET['end_date'])){
 	  $start =!empty($_GET['start_date'])?$_GET['start_date']:date ( "Y-m-1  00:00:00" );
	  $end = !empty($_GET['end_date'])?$_GET['end_date']:date ( "Y-m-d 23:59:59" );
    $date_where="  and  (create_time  between   '$start'  and  '$end')";
 	 }
	 $totalrecords = $this->query("select count(id) as c from class4_view_refill_record where 1=1 
	  $like_where  $start_amount_where  $end_amount_where  $client_where $card_where  $reseller_where $tran_type_where  $privilege  $result_where");
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		//$page = $page->checkRange($page);//检查当前页范围
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select  * from  class4_view_refill_record where 1=1  
		$like_where  $start_amount_where  $end_amount_where  $date_where  $client_where  $card_where  $reseller_where  $tran_type_where  $privilege  $result_where";
	 $sql .= " order by id     desc  ";
			 $sql .= " 	limit '$pageSize' offset '$currPage'";
		$results = $this->query($sql);
		$page->setDataArray($results);
		return $page;
	}	
	/**
 * 路由伙伴交易查询 
 * @paranknown_type $currPage
 * @param unknown_type $pageSize
 * $like_key--摸索的key
 */
	public function client_tran_findAll($options = array(),$order=null){
		if(empty($order)){
			$order="time  desc";
		}
	  $currPage=$this->_get_page();
	  $pageSize=$this->_get_size();
		require_once 'MyPage.php';
		$page = new MyPage();
	  $login_type=$_SESSION['login_type'];
	  $privilege='';//权限条件
	  if($login_type==3){
	 		$privilege="  and(client_id::integer={$_SESSION['sst_client_id']}) ";
	  	}
		//模糊搜索
	 	$like_where=!empty($_GET['search'])?" and (client_name like '%{$_GET['search']}%' or client_company ilike '%{$_GET['search']}%' or  client_id::text like '%{$_GET['search']}%'  or
	  client_cost::text like '%{$_GET['search']}%')":'';
 	 //起始金额
	 $start_amount_where=!empty($_GET['start_amount'])?"  and (client_cost::numeric>={$_GET['start_amount']})":'';
	//结束金额
	 $end_amount_where=!empty($_GET['end_amount'])?"     and (client_cost::numeric<={$_GET['end_amount']})":'';
	 //充值方式
	  $tran_type_where=!empty($_GET['tran_type'])?" and (tran_type='{$_GET['tran_type']}')":'';
   $client_where=!empty($_GET ['query'] ['id_clients'])?"  and (client_id::integer={$_GET ['query'] ['id_clients']})":'';
   $client_name_where=!empty($_GET['client_name'])?" and (client_name like '%{$_GET['client_name']}%' or client_company ilike '%{$_GET['client_name']}%')":'';
 	 //按时间搜索
 	 $date_where='';
 	  	 extract($this->get_real_period());
 	 			$start_date =date('Y-m-d');
				$stop_date=date('Y-m-d');
				$start_time=date('00:00:00');
				$stop_time=date('23:59:59');
				$tz=$this->get_sys_timezone();
				
 	 if(!empty($_GET['start_date'])||!empty($_GET['end_date'])){
 	 	
 	 				$start_date = $_GET ['start_date']; //开始日期
					$start_time = $_GET ['start_time']; //开始时间
					$stop_date = $_GET ['stop_date']; //结束日期
					$stop_time = $_GET ['stop_time']; //结束时间
					$tz = $_GET ['query'] ['tz'];//时区
					
					$start_day = $start_date;
					$end_day = $stop_date;
 	 }
		 	  	$start = $start_date . '  ' . $start_time."  ".$tz; //开始时间
					$end = $stop_date . '  ' . $stop_time."  ".$tz; //结束时间
					$start=local_time_to_gmt($start);
					$end=local_time_to_gmt($end);
 	  //$start =!empty($_GET['start_date'])?$_GET['start_date'].' 00:00:00':'1970-01-01 00:00:00';
	  //$end = !empty($_GET['end_date'])?$_GET['end_date'].' 23:59:59':date ( "Y-m-d 23:59:59" );
    $date_where="  and  (time  between   '$start'  and  '$end')";
	  $totalrecords = $this->query("select count(*) as c from {$this->class4_view_client_transactions} where 1=1 
	  $like_where  $start_amount_where  $end_amount_where $date_where $client_where $client_name_where   $tran_type_where  $privilege");
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$sql = "select  * from  {$this->class4_view_client_transactions} where 1=1  
		$like_where  $start_amount_where  $end_amount_where  $date_where   $client_where  $client_name_where   $tran_type_where  $privilege  ";
                if(isset($options['is_download']) && $options['is_download']){
			if($this->download_by_sql($sql,array('objectives'=>'client_transaction'))){
				exit(1);
			}
		}
 	 $sql .= "order by  $order  	limit '$pageSize' offset '$offset'";
		$results = $this->query($sql);
		$page->setDataArray($results);
		return $page;
	}	
	public function client_pay_findAll($order=null,$options = array()){
		if(empty($order)){
		$order="client_payment_id  desc";
		}
                 $temp = isset($_SESSION['pagging_row'])? $_SESSION['pagging_row'] : 10;
	 	 empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
                 $_SESSION['pagging_row'] = $pageSize;
		 require_once 'MyPage.php';
		 $page = new MyPage();
	 	 $login_type=$_SESSION['login_type'];
	 	 $privilege='';//权限条件
		 if($login_type==3){
		 		$privilege="  and(client_payment.client_id::integer={$_SESSION['sst_client_id']}) ";
		  }
//模糊搜索
	 $like_where=!empty($_GET['search'])?" and (client.name ilike '%{$_GET['search']}%' or client.company ilike '%{$_GET['search']}%' or  client_payment.client_id::text='{$_GET['search']}'  or
	  amount::text ilike '%{$_GET['search']}%')":'';
 	 //起始金额
	 $start_amount_where=!empty($_GET['start_amount'])?"  and (amount::numeric>={$_GET['start_amount']})":'';
//结束金额
	 $end_amount_where=!empty($_GET['end_amount'])?"     and (amount::numeric<={$_GET['end_amount']})":'';
	 //充值方式
	  $tran_type_where=!empty($_GET['tran_status'])?" and (approved={$_GET['tran_status']})":'';
   $client_where=!empty($_GET ['query'] ['id_clients'])?"  and (client_payment.client_id='{$_GET ['query'] ['id_clients']}')":'';
	 $client_name_where=!empty($_GET['client_name'])?" and (client.name ilike '%{$_GET['client_name']}%' or client.company ilike '%{$_GET['client_name']}%')":'';
	 $reseller_where=!empty($_GET ['query'] ['id_resellers'])?"  and (reseller_id::integer={$_GET ['query'] ['id_resellers']})":'';
 	 //按时间搜索
 	 $date_where='';
 	 //time zone
 	 extract($this->get_real_period());
 	 			$start_date =date('Y-m-d');
				$stop_date=date('Y-m-d');
				$start_time=date('00:00:00');
				$stop_time=date('23:59:59');
				$tz=$this->get_sys_timezone();
				
 	 if(!empty($_GET['start_date'])||!empty($_GET['end_date'])){
 	 	
 	 				$start_date = $_GET ['start_date']; //开始日期
					$start_time = $_GET ['start_time']; //开始时间
					$stop_date = $_GET ['stop_date']; //结束日期
					$stop_time = $_GET ['stop_time']; //结束时间
					$tz = $_GET ['query'] ['tz'];//时区
					
					$start_day = $start_date;
					$end_day = $stop_date;
 	 }
		 	  	$start = $start_date . '  ' . $start_time."  ".$tz; //开始时间
					$end = $stop_date . '  ' . $stop_time."  ".$tz; //结束时间
					$start=local_time_to_gmt($start);
					$end=local_time_to_gmt($end);
		    $date_where="  and  (payment_time  between   '$start'  and  '$end')";
 	    
 	     //  pr();
   $sqlStr="select count(*) as c from client_payment left join client  on client.client_id=client_payment.client_id where client.name is not null $like_where  $start_amount_where  $end_amount_where  $client_where $client_name_where  $tran_type_where  $privilege $date_where";
   //pr($sqlStr);
	 $totalrecords = $this->query($sqlStr);
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		//pr($totalrecords[0][0]['c']);
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		//$page = $page->checkRange($page);//检查当前页范围
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$sql = "select  client_payment.*,client.name as client_name from  client_payment  left join client on  client.client_id=client_payment.client_id  where 
		client.name is not null
		$like_where  $start_amount_where  $end_amount_where  $date_where $client_where  $client_name_where     $tran_type_where  $privilege  order by $order  ";
		if(isset($options['is_download']) && $options['is_download']){
			if($this->download_by_sql($sql,array('objectives'=>'client_pay'))){
				exit(1);
			}
		}
	 $sql .= "   	limit '$pageSize' offset '$offset'";
	 //pr($sql);
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	
	public function client_tran_findAll_one($client_id){
	 empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 13:	$pageSize = $_GET['size'];
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 $login_type=$_SESSION['login_type'];
	 $privilege='';//权限条件
		 if($login_type==3){
	 		$privilege="  and(client_id='{$_SESSION['sst_client_id']})' ";
	 }
	 //充值结果 
//模糊搜索
	 $like_where=!empty($_GET['search'])?" and (client_name like '%{$_GET['search']}%'  or  client_id = '%{$_GET['search']}%'  or
	  amount::text like '%{$_GET['search']}%')":'';
 	 //起始金额
	 $start_amount_where=!empty($_GET['start_amount'])?"  and (amount::numeric>={$_GET['start_amount']})":'';
//结束金额
	 $end_amount_where=!empty($_GET['end_amount'])?"     and (amount::numeric<={$_GET['end_amount']})":'';
	 //充值方式
	  $tran_type_where=!empty($_GET['tran_type'])?" and (tran_type={$_GET['tran_type']})":'';
   $client_where=!empty($client_id)?"  and (client_id={$client_id})":'';
 	 //按时间搜索
 	 $date_where='';
 	 if(!empty($_GET['start_date'])||!empty($_GET['end_date'])){
 	  $start =!empty($_GET['start_date'])?$_GET['start_date']:date ( "Y-m-1  00:00:00" );
	  $end = !empty($_GET['end_date'])?$_GET['end_date']:date ( "Y-m-d 23:59:59" );
    $date_where="  and  (payment_time  between   '$start'  and  '$end')";
 	 }
	 $totalrecords = $this->query("select count(*) as c from class4_view_client_transactions where 1=1 
	  $like_where  $start_amount_where  $end_amount_where  $client_where    $tran_type_where  $privilege");
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		//$page = $page->checkRange($page);//检查当前页范围
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select  * from  class4_view_client_transactions where 1=1  
		$like_where  $start_amount_where  $end_amount_where  $date_where    $client_where   $tran_type_where  $privilege  ";
	 $sql .= " order by time     desc  	limit '$pageSize' offset '$currPage'";
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
function likequery($key,$currPage=1,$pageSize=13,$search_res=null){
	$condition="'%".$key."%'";
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
			 $login_type=$_SESSION['login_type'];
	 //damin
	 if($login_type==1){
	 $sql1="select count(*) as c from transation where 1=1 ";
	  $sql2=" where 1=1";
	  if (!empty($search_res)){
	  		$sql1 .= " and id = '$search_res'";
	  		$sql2 .= " and id = '$search_res'";
	  	}
	 }
	 //reseller
	 if($login_type==2){
	 		$reseller_id = $_SESSION['sst_reseller_id'];
	 		!empty($search_res)?$reseller_id=$search_res:$reseller_id=$_SESSION['sst_reseller_id'];
	 	 $sql1="select count(*) as c from transation where  id=$reseller_id  and user_type=2 ";
	   $sql2=" where  id=$reseller_id  and user_type=2";
	 }
	 //client
	 if($login_type==3){
	 	 	$client_id = $_SESSION['sst_client_id'];
	  $sql1="select count(*) as c from transation where  id=$client_id  and user_type=3 ";
	   $sql2="  where  id=$client_id  and user_type=3";
	 }
		 if($login_type==4){
	 	 	$card_id = $_SESSION['card_id'];
	  $sql1="select count(*) as c from transation where  id=$card_id  and user_type=4 ";
	   $sql2="  where  id=$card_id  and user_type=4 ";
	 }
	 //判断日期
		if(preg_match('/^[0-9]{4}$/', trim($key)))
		{
			$year=$key;
				$totalrecords = $this->query("$sql1 
	 	 and  name   like $condition
	 	or (select count(*)>0 from transation   where  create_time between '$year-01-01'  and '$year-12-30' )
	 	");
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select *
		    from  transation a  $sql2  
and  name   like $condition
	 	or (select count(*)>0 from transation   where  create_time between '$year-01-01'  and '$year-12-30' )
	order by create_time   desc  	limit '$pageSize' offset '$currPage'";
		$results = $this->query($sql);
		$page->setDataArray($results);
		return $page;
		}
	 	$totalrecords = $this->query("$sql1 
	 	 and  name   like $condition
	 	");
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		//$page = $page->checkRange($page);//检查当前页范围
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select *
		    from  transation a  $sql2  
and  name   like $condition
	order by create_time   desc  	limit '$pageSize' offset '$currPage'";
		$results = $this->query($sql);
		$page->setDataArray($results);
		return $page;
}	
	/**
 * 高级搜索
 * @param unknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function Advancedquery($data,$currPage=1,$pageSize=13,$search_res=null){
		$last_conditions = '';
		$adv_search = " where 1=1 ";
		if (!empty($data['start_amount'])){
			$adv_search .= " and amount >={$data['start_amount']}";
			$last_conditions .= "&start_amount={$data['start_amount']}";
		}
		if (!empty($data['end_amount'])){
			$adv_search .= " and amount <={$data['end_amount']}";
			$last_conditions .= "&end_amount={$data['end_amount']}";
		}
		if (!empty($data['start_date'])){
			$adv_search .= " and create_time >='{$data['start_date']}'";
			$last_conditions .= "&start_date={$data['start_date']}";
		}
		if (!empty($data['end_date'])){
			$adv_search .= " and create_time <='{$data['end_date']}'";
			$last_conditions .= "&end_date={$data['end_date']}";
		}
		if (!empty($data['query']['client_type'])){
			$adv_search .= " and user_type ='{$data['query']['client_type']}'";
			$last_conditions .= "&query['client_type']={$data['query']['client_type']}";
		}
		if (!empty($data['query']['id_clients_name'])){
			$adv_search .= " and name ='{$data['query']['id_clients_name']}'";
			$last_conditions .= "&query['id_clients_name']={$data['query']['id_clients_name']}";
		}
		if (!empty($data['tran_type'])){
			if ($data['tran_type'] == 15){
				$adv_search .= " and tran_type not in (11,12,13)";
			} else {
				$adv_search .= " and tran_type =='{$data['tran_type']}'";
			}
			$last_conditions .= "&tran_type={$data['tran_type']}";
		}
		if (!empty($search_res)){
			$adv_search .= " and id = '$search_res'";
		}
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(id) as c from transation  $adv_search");
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		//$page = $page->checkRange($page);//检查当前页范围
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select *
		    from  transation a $adv_search order by a.id desc  	limit '$pageSize' offset '$currPage'";
		$results = $this->query($sql);
		$page->setDataArray($results);
		return $page;
	      }	
	public function update_refill($f){
		$newpayment = $f['newpayment'];
		$user_type = $f['user_type'];
		$user_id = $f['user_id'];
		$oldmoney = $f['oldmoney'];
		$qs = null;
		if ($user_type == 2) {
			$now_balance = $this->query("select balance from reseller_balance where reseller_id = $user_id
																						order by reseller_balance_id desc limit 1");
			
			$update_balance = empty($now_balance[0][0]['balance'])?0:$now_balance[0][0]['balance'] + ($newpayment-$oldmoney);
			
			$qs = $this->query("insert into reseller_balance(reseller_id,balance,create_time)
												values($user_id,'{$update_balance}',current_timestamp(0))");
		} else {
			$now_balance = $this->query("select balance from c4_client_balance where client_id = '$user_id'
																						order by client_balance_id desc limit 1");
			
			$update_balance = empty($now_balance[0][0]['balance'])?0:$now_balance[0][0]['balance'] + ($newpayment-$oldmoney);

            $qs = $this->clientBalanceOperation($user_id, $update_balance, 0, true);
		}
		
		return count($qs) == 0;
	}
	
	
	
	/**
	 * 
	 * 
	 * 审核充值
	 * @param unknown_type $user_type
	 * @param unknown_type $payment_id
	 * @param unknown_type $user_id
	 */
	public function approve_payment($user_type,$payment_id,$user_id){
		$qs_count = 0;
		
		//代理商审核充值
		if ($user_type == 2) {
			$payments = $this->query("select amount from reseller_payment where reseller_payment_id = $payment_id");
			$now_balance = $this->query("select balance from reseller_balance where reseller_id = $user_id
																						order by reseller_balance_id desc limit 1");
			
			$update_balance = empty($now_balance[0][0]['balance'])?0:$now_balance[0][0]['balance'] + ($payments[0][0]['amount']);
			
			$qs = $this->query("insert into reseller_balance(reseller_id,balance,create_time)
												values($user_id,'{$update_balance}',current_timestamp(0))");
			
			$qs_count += count($qs);
			
			$qs = $this->query("update reseller_payment set approved = true where reseller_payment_id = $payment_id");
			$qs_count += count($qs);
		} else {
			
				//路由伙伴审核充值
				
		
			$payments = $this->query("select amount from client_payment where client_payment_id = $payment_id");
				$amount=!empty($payments[0][0]['amount'])?$payments[0][0]['amount']:0;
			
			$this->begin();

            $qs = $this->clientBalanceOperation($user_id, $amount, 2, true);

			$qs_count += count($qs);
			$qs = $this->query("update client_payment set approved = true where client_payment_id = $payment_id");
				$this->commit();
			$qs_count += count($qs);
		}
		return $qs_count == 0;
	}
   
   public function get_client_transaction($start,$end,$otherwhere,$pageSize,$offset) {
//       $results = $this->query("SELECT
//(SELECT name from client where client_id = client_transaction.client_id) as client,
//amount,
//balance,
//CASE type
//	WHEN 5 THEN 'Payment Received'
//	WHEN 6 THEN 'Payment Sent'
//	WHEN 2 THEN 'Invoice Received'
//	WHEN 0 THEN 'Invoice Sent'
//	WHEN 7 THEN 'Credit Note Received'
//	WHEN 8 THEN 'Credit Note Sent'
//	WHEN 9 THEN 'Reset'
//END AS type,
//date::date,id
//FROM client_transaction WHERE date::date BETWEEN '{$start}' and '{$end}' $otherwhere ORDER BY client_id ASC, date DESC limit $pageSize offset $offset;");
       $results = array();
       return $results;
   }
   
   public function get_client_transaction_count($start,$end,$otherwhere) {
//       $results = $this->query("SELECT count(*) as cnt FROM client_transaction WHERE date::date BETWEEN '{$start}' and '{$end}' $otherwhere ;");
//       return $results[0][0]['cnt'];
       return 0;
   }
   
   
   public function get_clients() {
	   $where = '';
	   if ($_SESSION['login_type'] == 2)
	   {
           $agent_id = (int) $_SESSION['sst_agent_info']['Agent']['agent_id'];
           $where = "where exists (select 1 from agent_clients where agent_id = {$agent_id} and client_id = client.client_id)";
	   }
       $results = $this->query("SELECT client_id, name FROM client $where ORDER BY name ASC");
       return $results;
   }
   
   public function get_begin_balance($start,$end,$otherwhere) {
       $results = $this->query("SELECT 

	balance

FROM client_transaction

WHERE date::date BETWEEN '{$start} $gmt' and '{$end} $gmt' $otherwhere ORDER BY id asc limit 1;");
        return $results[0][0]['balance'];
   }
   
   
   
   public function get_client_actual_transaction($start,$end, $client_id, $type) {
       $sql = "select a, b, (select name from client where client_id::text = c) as c, d from actual_trans('{$start}','{$end}', {$client_id},{$type}) as t(a text,b text,c text,d text)
ORDER BY  a ASC";
       $results = $this->query($sql);  
       return $results;
   }
   
   public function get_client_actual_exchange_transaction($start,$end, $client_id, $type) {
       $sql = "select a, b, (select name from client where client_id::text = c) as c, d from actual_trans_exchange('{$start}','{$end}', {$client_id},{$type}) as t(a text,b text,c text,d text)
ORDER BY  a ASC";
       $results = $this->query($sql);  
       return $results;
   }
   
   public function get_client_mutual_transaction($start,$end, $client_id, $type,$pageSize = "",$offset = "") {
       $limit = "";
       if($pageSize && $offset)
       {
           $limit = "offset {$offset} limit {$pageSize}";
       }
       $sql = "select a, b, (select name from client where client_id::text = c) as c, d from mutual_trans('{$start}','{$end}', {$client_id},{$type}) as t(a text,b text,c text,d text)
ORDER BY  a ASC {$limit}";
       $results = $this->query($sql);  
       return $results;
   }
   
   public function get_client_mutual_transaction_count($start,$end, $client_id, $type) {
       $sql = "select count(*) from mutual_trans('{$start}','{$end}', {$client_id},{$type}) as t(a text,b text,c text,d text)";
       $results = $this->query($sql);  
       return $results[0][0]['count'];
   }
   /*
   public function get_client_mutual_transaction($start,$end, $client_id, $type) {
       $sql = "select a, b, (select name from client where client_id::text = c) as c, d , (select description from client_payment where client_id::text = c and amount::text = d limit 1) as e from mutual_trans('{$start} 00:00:00','{$end} 23:59:59', {$client_id},{$type}) as t(a text,b text,c text,d text)
ORDER BY b ASC, a ASC";
       $results = $this->query($sql);  
       return $results;
   }
   */
   
   public function get_actual_type_sum($type,$start,$end) {
       $type_where = "";
       if(!is_null($type)) {
           $type_where = " and type = {$type}";
       }
//       $results = $this->query("SELECT COALESCE(sum(amount), 0) AS s FROM actual_transaction WHERE time BETWEEN '{$start}' and '{$end}' $type_where");
//       return $results[0][0]['s'];
   }
   
   public function get_mutual_type_sum($type,$start,$end, $client_id = '') {
       $type_where = "";
       if(!is_null($type)) {
           $type_where = " and type = {$type}";
       }
       if(!empty($client_id)) {
           $type_where .= " and client_id = '{$client_id}'";
       }
       $results = $this->query("SELECT COALESCE(sum(amount), 0) AS s FROM mutual_transaction WHERE time BETWEEN '{$start}' and '{$end}' $type_where");
       return $results[0][0]['s'];
   }
   
   
   public function get_actual_begin_balance($start,$end,$otherwhere) {
//       $results = $this->query("SELECT
//
//	balance
//
//FROM actual_transaction
//
//WHERE time BETWEEN '{$start}' and '{$end}' $otherwhere ORDER BY id asc limit 1;");
//        return $results[0][0]['balance'];
   }
   
   public function get_mutual_begin_balance($start,$end,$otherwhere) {
       $results = $this->query("SELECT 

	balance

FROM mutual_transaction

WHERE time BETWEEN '{$start}' and '{$end}' $otherwhere ORDER BY id asc limit 1;");
        if(empty($results)) {
            return 0;
        }
        return $results[0][0]['balance'];
   }



    public function get_payments($type, $start, $end, $gmt, $where, $pageSize, $offset, $is_export,$order_sql = '')
    {
        if (true == (bool)$is_export) {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->autoLayout = false;
            $database_export_path = Configure::read('database_export_path');

            if (empty($database_export_path)) {
                $database_export_path = "/tmp/exports";
            }

            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=Payment_record_{$start}_{$end}.csv");
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');

            $random_filename = uniqid('payment_record_') . '.csv';
            $filename = $database_export_path . DS . $random_filename;

            $sql = <<<EOT
SELECT payment_time::timestamp(0) with time zone AS "Entered Time", receiving_time AS "Payment Time",
(SELECT name FROM client WHERE client_id = client_payment.client_id) AS "Carrier", amount AS "Amount",invoice_number as "Invoice Number"
FROM client_payment 
WHERE {$type} AND payment_time BETWEEN TIMESTAMP '{$start} {$gmt}' and TIMESTAMP '{$end} {$gmt}' {$where}
ORDER BY payment_time DESC, client_id DESC
EOT;
            $tmpResult = $this->query($sql);

            $handle = fopen($filename, 'w');
            fputcsv($handle, array_keys($tmpResult[0][0]));

            foreach ($tmpResult as $item) {
                fputcsv($handle, $item[0]);
            }
            fclose($handle);

            ob_clean();
            readfile($filename);
            exit;
        }

        if (!$order_sql) {
            $order_sql = "ORDER BY payment_time DESC, client_id DESC";
        }

        $sql = <<<SQL
SELECT client_payment_id, payment_time, payment_type, update_by,
	(SELECT name FROM client WHERE client_id = client_payment.client_id) AS client, amount, invoice_number, receiving_time, client_id 
FROM client_payment 
WHERE {$type} AND payment_time BETWEEN TIMESTAMP '{$start} {$gmt}' and TIMESTAMP '{$end} {$gmt}' {$where}
{$order_sql} LIMIT {$pageSize} offset {$offset}
SQL;

        $result = $this->query($sql);
        return $result;
    }
   
   public function get_count_payments($type, $start, $end, $gmt, $where) {
       $sql = "SELECT count(*) as c FROM (
SELECT 
	client_payment_id, payment_time, (SELECT name FROM client WHERE client_id = client_payment.client_id) AS client, amount,invoice_number 
FROM 
	client_payment 

WHERE  
	({$type}) 
	AND payment_time BETWEEN TIMESTAMP '{$start} {$gmt}' and TIMESTAMP '{$end} {$gmt}' {$where}
ORDER BY
	payment_time DESC) as t";
       $result = $this->query($sql);
       return $result[0][0]['c'];
   }
   
   public function insert_offset($client_id, $amount, $create_by) {
       $sql = "INSERT INTO client_payment (client_id, amount, payment_type, update_by, payment_time, result) VALUES ({$client_id}, {$amount}, 10, '{$create_by}', CURRENT_TIMESTAMP, true)";
       $this->query($sql);
   }
   
   public function get_offsets($pageSize, $offset, $where) {
       $sql = "SELECT 
payment_time, (SELECT name FROM client WHERE client_id = client_payment.client_id) as client,amount, update_by 
FROM client_payment WHERE payment_type = 10 {$where} ORDER BY payment_time DESC LIMIT {$pageSize} offset {$offset}";
       $result = $this->query($sql);
       return $result;
   }
   
   public function get_offsets_count($where) {
       $sql = "SELECT 
count(*) FROM client_payment WHERE payment_type = 10 {$where}";
       $result = $this->query($sql);
       return $result[0][0]['count'];
   }
   
   public function del_history_actual_balance($client_id, $start_date, $end_date) {
//       $sql = "DELETE FROM actual_transaction WHERE time BETWEEN '{$start_date}' AND  '{$end_date}' AND client_id = '{$client_id}'";
//       $this->query($sql);
   }
   
   public function del_history_mutual_balance($client_id, $start_date, $end_date) {
       $sql = "DELETE FROM mutual_transaction WHERE time BETWEEN '{$start_date}' AND  '{$end_date}' AND client_id = '{$client_id}'";
       $this->query($sql);
   }
   
   public function update_to_actual_balance($client_id, $start_date, $end_date) {
       $today_date = date("Ymd");
//       $sql =<<<EOT
//INSERT INTO actual_transaction
//
//SELECT *  FROM
//(
//
//SELECT payment_time::date AS time, sum(amount) AS amount, client_id::VARCHAR, 1 AS type
//FROM client_payment WHERE  payment_type in (4,5) AND payment_time::date between '{$start_date}' AND '{$end_date}' AND client_id = {$client_id} GROUP BY client_id, payment_time::date
//
//UNION ALL
//
//SELECT time::date, sum(ingress_client_cost::real + lnp_dipping_cost::real) as amount, ingress_client_id AS client_id, 2 AS type
//FROM client_cdr{$today_date} WHERE time::date between '{$start_date}' AND '{$end_date}' AND ingress_client_id = '{$client_id}' GROUP BY ingress_client_id, time::date
//
//UNION ALL
//
//SELECT payment_time::date AS time, sum(amount) AS amount, client_id::VARCHAR, 3 AS type
//FROM client_payment WHERE payment_type in (6, 11) AND payment_time::date between '{$start_date}' AND '{$end_date}' AND client_id = {$client_id} GROUP BY client_id, payment_time::date
//
//UNION ALL
//
//SELECT time::date, sum(egress_cost::real) as amount, egress_client_id AS client_id, 4 AS type
//FROM client_cdr{$today_date} WHERE time::date between '{$start_date}' AND '{$end_date}' AND egress_client_id = '{$client_id}' GROUP BY egress_client_id, time::date
//
//UNION ALL
//
//SELECT payment_time::date AS time, sum(amount) AS amount, client_id::VARCHAR, 5 AS type
//FROM client_payment WHERE payment_type = 10 AND payment_time::date between '{$start_date}' AND '{$end_date}' AND client_id = {$client_id} GROUP BY client_id,payment_time::date
//
//UNION ALL
//
//SELECT payment_time::date AS time, sum(amount) AS amount, client_id::VARCHAR, 6 AS type  FROM client_payment
//WHERE client_id = $client_id and payment_type = 7
//AND payment_time::date between '{$start_date}' AND '{$end_date}'
//GROUP BY client_id,payment_time::date
//
//) AS T ORDER BY time DESC
//EOT;
//    $this->query($sql);
   }
   
   public function update_to_mutual_balance($client_id, $start_date, $end_date) {
       $sql =<<<EOT
INSERT INTO mutual_transaction 
SELECT * FROM 
( 
SELECT 
	payment_time::date AS time, sum(amount) AS amount, client_id::VARCHAR, 1 AS type 
FROM client_payment 
WHERE payment_type in (4,5) AND payment_time::date between '{$start_date}' AND '{$end_date}' AND client_id = {$client_id} 
GROUP BY client_id, payment_time::date 

UNION ALL 

SELECT 
	invoice_time::date AS time, COALESCE(sum(total_amount), 0) AS amount,client_id::VARCHAR, 2 AS type 
FROM invoice WHERE state = 9 AND client_id = {$client_id} AND invoice_time::date BETWEEN '{$start_date}' AND '{$end_date}' AND type = 0 GROUP BY invoice_time::date,client_id 

UNION ALL 

SELECT payment_time::date AS time, sum(amount) AS amount, client_id::VARCHAR, 3 AS type 
FROM client_payment WHERE payment_type in (6, 11) AND payment_time::date between '{$start_date}' AND '{$end_date}' AND client_id = {$client_id} GROUP BY client_id, payment_time::date

UNION ALL

SELECT 
	invoice_time::date AS time, COALESCE(sum(total_amount), 0) AS amount,client_id::VARCHAR, 4 AS type 
FROM invoice WHERE state = 9 AND client_id = {$client_id} AND invoice_time::date BETWEEN '{$start_date}' AND '{$end_date}' AND type = 1 GROUP BY invoice_time::date,client_id 

UNION ALL 

SELECT payment_time::date AS time, sum(amount) AS amount, client_id::VARCHAR, 5 AS type 
FROM client_payment 
WHERE payment_type = 10 AND payment_time::date between '{$start_date}' AND '{$end_date}' AND client_id = {$client_id} 
GROUP BY client_id,payment_time::date 

UNION ALL

SELECT payment_time::date AS time, sum(amount) AS amount, client_id::VARCHAR, 6 AS type  FROM client_payment 
WHERE client_id = $client_id and payment_type = 7
AND payment_time::date between '{$start_date}' AND '{$end_date}'
GROUP BY client_id,payment_time::date

) 
AS T ORDER BY time DESC   
EOT;
    $this->query($sql);
   }
   
   public function get_last_actual_balance($client_id, $id) {
       //$sql = "SELECT COALESCE(balance, 0) AS balance FROM actual_transaction WHERE client_id = '{$client_id}' AND time < '{$start_date}' ORDER BY time DESC limit 1";
//       $sql = "SELECT COALESCE(balance, 0) AS balance FROM actual_transaction WHERE client_id = '{$client_id}' AND id < {$id} ORDER BY id DESC limit 1";
//       $result = $this->query($sql);
       if(empty($result)) {
           return 0;
       } else {
        return $result[0][0]['balance'];
       }
   }
   
   public function update_actual_balance($id, $balance) {
//       $sql = "UPDATE actual_transaction SET balance = {$balance} WHERE id = {$id}";
//       $this->query($sql);
   }
   
   public function get_select_actual_balance($client_id, $start_date, $end_date) {
//       $sql = "SELECT * FROM actual_transaction WHERE client_id = '{$client_id}' AND time BETWEEN '{$start_date}' AND '{$end_date}' ORDER BY id ASC";
//       $result = $this->query($sql);
//       return $result;
   }
   
   public function get_select_mutual_balance($client_id, $start_date, $end_date) {
       $sql = "SELECT * FROM mutual_transaction WHERE client_id = '{$client_id}' AND time BETWEEN '{$start_date}' AND '{$end_date}' ORDER BY id ASC";
       $result = $this->query($sql);
       return $result;
   }
   
   public function get_last_mutual_balance($client_id, $id) {
       $sql = "SELECT COALESCE(balance, 0) AS balance FROM mutual_transaction WHERE client_id = '{$client_id}' AND id < {$id} ORDER BY id DESC limit 1";
       $result = $this->query($sql);
       if(empty($result)) {
           return 0;
       } else {
        return $result[0][0]['balance'];
       }
   }
   
   public function update_mutual_balance($id, $balance) {
       $sql = "UPDATE mutual_transaction SET balance = {$balance} WHERE id = {$id}";
       $this->query($sql);
   }
   
   public function get_company_address($client_id) {
       $sql = "SELECT name, company, address FROM client WHERE client_id = {$client_id}";
       $data = $this->query($sql);
       return array(
           $data[0][0]['name'],
           $data[0][0]['company'],
           $data[0][0]['address'],
       );
   }
   
   public function get_client_amount_due($client_id) {
       $sql = "SELECT sum(total_amount) as amount_due FROM invoice WHERE due_date <= now() and  type in  (0, 2)  and client_id = {$client_id}";
       $data = $this->query($sql);
       return $data[0][0]['amount_due'];
   }
   
   public function add_ingress_balance($amount, $client_id)
   {
       $this->clientBalanceOperation($client_id, $amount, 2);
   }

    public function minus_egress_balance($amount, $client_id)
    {
        $this->clientBalanceOperation($client_id, $amount, 5);
    }

    public function add_payment($client_id, $type, $amount, $received_at, $note, $update_by = '')
    {
        if(!$update_by) {
            $update_by = $_SESSION['sst_user_name'];
        }
        $sql = "INSERT INTO client_payment(client_id, payment_type, amount,
           payment_time, result, receiving_time, description,update_by)
            VALUES ({$client_id},$type,{$amount},
           current_timestamp(0), TRUE, '{$received_at}', '{$note}', '{$update_by}' ) returning client_payment_id";
        $data = $this->query($sql);
        return $data[0][0]['client_payment_id'];
    }
   
   public function change_low_balance_type($client_id)
   {
       $sql = "update client set low_balance_number = daily_balance_notification where client_id = {$client_id}";
       $this->query($sql);
   }
   
   
   public function paid_invoice($client_payment_id, $invoice_number, $invoice_paid)
   {
       if ($invoice_number != NULL)
       {
        $sql = "update invoice set pay_amount = pay_amount + {$invoice_paid} where invoice_number = '{$invoice_number}'";
        $this->query($sql);
        $sql = "update invoice set paid = true where invoice_number = '{$invoice_number}' and pay_amount:: numeric(30,2) >= total_amount:: numeric(30,2)";
        $this->query($sql);
        $sql = "insert into payment_invoice(payment_id, invoice_id, amount) values ($client_payment_id, (select invoice_id from invoice where invoice_number = '{$invoice_number}'), $invoice_paid)";
        $this->query($sql);
       } else {
        $sql = "insert into payment_invoice(payment_id, invoice_id, amount) values ($client_payment_id, NULL, $invoice_paid)";
        $this->query($sql);   
       }
       
   }
   
   public function get_invoice_payment($client_payment_id) {
       $sql = "select * from payment_invoice 

left join invoice on payment_invoice.invoice_id = invoice.invoice_id

where payment_id = $client_payment_id order by id asc
";
       
       return $this->query($sql);
   }
   
   public function get_payment_invoice($payment_invoice_id)
   {
       $sql = "select * from payment_invoice where id = {$payment_invoice_id}";
       $result = $this->query($sql);
       return $result[0][0];
   }
   
   
   public function delete_payment_invoice($payment_invoice_id)
   {
       $sql = "delete from payment_invoice where id = {$payment_invoice_id}";
       $this->query($sql);
   }
   
   public function update_remain_payment_invoice($payment_invoice_id, $amount)
   {
       $sql = "update payment_invoice set amount = {$amount} where id = {$payment_invoice_id}";
       $this->query($sql);
   }

   public function balanceDailyResetTask($startTime, $actual, $clientId)
   {
       $sql = "insert into balance_daily_reset_task (start_time,actual,client_id) values ('$startTime', $actual, $clientId)";

       return $this->query($sql);
   }

    /**
     * Get total values for client for specific period.
     * @param $clientId
     * @param $start
     * @param $end
     */
   public function getActualTotalValues($clientId, $start, $end)
   {
       /*
      WHEN payment_type in (3,6) THEN sum(amount) as payment_sent,
      WHEN payment_type in (7) THEN sum(amount) as credit_note_received,
      WHEN payment_type in (8) THEN sum(amount) as credit_note_sent,
      WHEN payment_type in (9) THEN sum(amount) as ingress_reset,
      WHEN payment_type in (11) THEN sum(amount) as debit_note_received,
      WHEN payment_type in (12) THEN sum(amount) as debit_note_sent
        */

        $sql = <<<SQL
SELECT
(CASE WHEN payment_type in (4,5) THEN sum(amount) END) as payment_received,
(CASE WHEN payment_type in (3,6) THEN sum(amount) END) as payment_sent,
(CASE WHEN payment_type in (7) THEN sum(amount) END) as credit_note_received,
(CASE WHEN payment_type in (8) THEN sum(amount) END) as credit_note_sent,
(CASE WHEN payment_type in (9) THEN sum(amount) END) as ingress_reset,
(CASE WHEN payment_type in (11) THEN sum(amount) END) as debit_note_received,
(CASE WHEN payment_type in (12) THEN sum(amount) END) as debit_note_sent
FROM client_payment
WHERE client_id = {$clientId} AND payment_time BETWEEN '{$start}' AND '{$end}'
GROUP BY payment_type
SQL;
       $result = $this->query($sql);

       $startDate = date('Y-m-d', strtotime($start));
       $endDate = date('Y-m-d', strtotime($end));
       $dateArr = $this->_app_get_date_result_admin($startDate, $endDate, 'cdr_report_detail2%');
       $firstSql = '';
       $secondSql = '';

       foreach ($dateArr as $value) {
           $tableDate = "cdr_report_detail" . $value;
           $union = "";

           if (!empty($firstSql)) {
               $union = " union all ";
           }
           $firstSql .= " {$union}  SELECT
sum(egress_call_cost) as egress_call_cost
from {$tableDate}
where report_time between '{$start}' and '{$end}' and egress_client_id = {$clientId}
";
           $secondSql .= " {$union}  SELECT
sum(ingress_call_cost) as ingress_call_cost
from {$tableDate}
where report_time between '{$start}' and '{$end}' and ingress_client_id = {$clientId}
";
       }

       $sql = <<<SQL
SELECT
sum(egress_call_cost) as egress_call_cost
FROM ({$firstSql}) as t1
SQL;
       $res = $this->query($sql);

       if ($res) {
           $result[0][0]['egress_call_cost'] = $res[0][0]['egress_call_cost'];
       }
       $sql = <<<SQL
SELECT
sum(ingress_call_cost) as ingress_call_cost
FROM ({$secondSql}) as t1
SQL;
       $res = $this->query($sql);

       if ($res) {
           $result[0][0]['ingress_call_cost'] = $res[0][0]['ingress_call_cost'];
       }

       return $result;
   }
  
        
}