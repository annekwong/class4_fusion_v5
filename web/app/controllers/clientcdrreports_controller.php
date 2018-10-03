<?php
class ClientcdrreportsController extends AppController {
	var $name = 'Clientcdrreports';
	var $uses = array ('Cdr' );
	var $helpers = array ('javascript', 'html','AppClientcdrreports' );
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		parent::beforeFilter();
		$login_type = $this->Session->read('login_type');
		if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
		}else{
		$limit = $this->Session->read('sst_retail_rcardpools');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
		}
		parent::beforeFilter();
		
	}
	function init_query() {
	
		$this->set ( 'code_deck', $this->Cdr->find_code_deck () );
		$this->set ( 'currency', $this->Cdr->find_currency () );
		$this->set ( 'server', $this->Cdr->find_server () );
		$this->set ( 'ingress', $this->Cdr->findAll_ingress_alias () );
		$this->set ( 'egress', $this->Cdr->findAll_egress_alias () );
		$this->set('cdr_field',$this->Cdr->find_field());
	}
	
	



	function credit_balance(){
		App::import("Model",'Invoice');
		$this->pageTitle="Credit/Balance";
		$invoice_model = new Invoice;
		$invoice = $invoice_model->get_total_due_info($_SESSION['sst_client_id']);
		$invoice_time = $invoice['invoice_time'];
		if($invoice_time){
//		添加时间限制  最后一个invoice 时间到现在 
#		(buy)
			$buy_trading_sql = "select  sum(ingress_client_cost::numeric) as val from client_cdr   where time > '$invoice_time' AND ingress_client_id= '" .$_SESSION['sst_client_id'] . "' ";
	
	#		(sell)
			$sell_trading_sql = "select  sum(egress_cost::numeric) as val  from client_cdr   where time > '$invoice_time' AND egress_client_id='" .$_SESSION['sst_client_id'] . "'";
		}else{
			$buy_trading_sql = "select  sum(ingress_client_cost::numeric) as val from client_cdr   where ingress_client_id= '" .$_SESSION['sst_client_id'] . "' ";
			$sell_trading_sql = "select  sum(egress_cost::numeric) as val  from client_cdr   where egress_client_id='" .$_SESSION['sst_client_id'] . "'";
		}
//#		2.Amount Due   发票金额
//		$amount = "select total_amount as val   from    invoice   where  client_id=". $_SESSION['sst_client_id'] ." order by   invoice_id  desc  limit  1";

#		3. credit limit  容许欠费
		$allowed_credit_sql = "select allowed_credit as val from  client  where  client_id=" . $_SESSION['sst_client_id'];
		$payment_sql = "select sum(amount) as val from client_payment where client_id = {$_SESSION['sst_client_id']} AND result = true AND payment_type = 2   GROUP BY client_id LIMIT 1" ;
//		$limit = "select     amount as val  from     client_payment   where   client_id = {$_SESSION['sst_client_id']} order by  client_payment_id   desc limit  1";
#		4.Credit available  实际余额  
//		$available = "select balance  as val from  client_balance  where  client_id='" . $_SESSION['sst_client_id'] . "'";
		$buy = array_keys_value($this->Cdr->query($buy_trading_sql),"0.0.val",0);
		$sell = array_keys_value($this->Cdr->query($sell_trading_sql),"0.0.val",0);
		$allowed_credit = array_keys_value($this->Cdr->query($allowed_credit_sql),"0.0.val",0);
		$payment = array_keys_value($this->Cdr->query($payment_sql),"0.0.val",0);
		$credit = abs((float)$allowed_credit) + (float)$payment;
		$this->set('buy',$buy);
		$this->set('sell',$sell);
		$this->set('invoice',$invoice['total']);
		$this->set('credit',$credit);
		$this->set('availbale',$credit - (float)$invoice['total'] + $sell - $buy);
	}
	function summary_reports() {
		$this->_render_summary_reports_data();
		$this->_render_summary_reports_options();
	}
	function _render_summary_reports_options()
	{

	}
	function _render_summary_reports_data()
	{
		
		
		empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
		
			if(!empty($this->params['pass'][0])){
		   $order_type=	$this->params['pass'][0];
		   if($order_type=='buy'){  
		   	$this->set('order_type','buy');
		     $order_field='buy_order_id';
		     $order_cost_field='ingress_client_cost';
		     $where_sql="where contract_record.contract_type=1 or(contract_record.contract_type=2 and contract_record.client_id={$_SESSION['sst_client_id']})";
		    
		
		   		}
		   elseif($order_type=='sell'){ 
		   	$this->set('order_type','sell');
		     $order_field='sell_order_id';
		     $order_cost_field='egress_cost';
		      $where_sql="where (contract_record.contract_type=1 and contract_record.client_id={$_SESSION['sst_client_id']}) or (contract_record.contract_type=2)";

		   }
		   else{
		   	$this->set('order_type','buy');
		     $order_field='buy_order_id';
		     $order_cost_field='ingress_client_cost';
		     $where_sql="where (contract_record.contract_type=1) or(contract_record.contract_type=2 and contract_record.client_id={$_SESSION['sst_client_id']})";
		    
		     	}
		}else{
		    	$this->set('order_type','buy');
		     $order_field='buy_order_id';
		     $order_cost_field='ingress_client_cost';
		     $where_sql="where (contract_record.contract_type=1) or(contract_record.contract_type=2 and contract_record.client_id={$_SESSION['sst_client_id']})";
		    
		
		}
		$privilege='';
	 if($_SESSION['login_type']==3){
		//	$privilege= !empty($_SESSION['sst_client_id'])?" and (  egress_client_id  = '{$_SESSION['sst_client_id']}'  or   ingress_client_id  = '{$_SESSION['sst_client_id']}')":'';
	 }
		$this->init_query ();
		$start = date ( "Y-m-d  00:00:00" );
		$end = date ( "Y-m-d 23:59:59" );
		$start_day = date ( "Y-m-d  " );
		$end_day = date ( "Y-m-d " );
		
		//单个的where查询条件
		$cost_where='';
		$country_where='';
	  $client_where = '';
		$code_name_where = '';
		$code_where = '';
		$code_deck_where = '';
		$server_where = '';
		$currency_where = '';
		$egress_where = '';
		$ingress_where = '';
		$interval_from_where='';
		$interval_to_where='';
	
		$dst_number_where = '';
		$duration_where = "";
   $disconnect_cause_where='';
  $cost_where='';
  $src_number_where='';
  $call_id_where='';
  
  
		$order_by="order by   time desc";
		if (isset ( $_GET ['searchkey'] )) {
			
			
		if(!empty($_GET['order_type'])){
   $order_type=	$_GET['order_type'];
   if($order_type=='buy'){  
   	$this->set('order_type','buy');
           $order_field='buy_order_id';
           $order_cost_field='ingress_client_cost';
           $where_sql="where (contract_record.contract_type=1) or(contract_record.contract_type=2 and contract_record.client_id={$_SESSION['sst_client_id']})";
		    
   }
   elseif($order_type=='sell'){ 
   	      $this->set('order_type','sell');
           $order_field='sell_order_id';
            $order_cost_field='egress_cost';
             $where_sql="where (contract_record.contract_type=1 and contract_record.client_id={$_SESSION['sst_client_id']}) or (contract_record.contract_type=2)";
   }
   else{
   	    	$this->set('order_type','buy');
         $order_field='buy_order_id';
         $order_cost_field='ingress_client_cost';
      $where_sql="where (contract_record.contract_type=1) or(contract_record.contract_type=2 and contract_record.client_id={$_SESSION['sst_client_id']})";
		    
     }
}else{
    	    $this->set('order_type','buy');
         $order_field='buy_order_id';
         $order_cost_field='ingress_client_cost';
        $where_sql="where (contract_record.contract_type=1) or(contract_record.contract_type=2 and contract_record.client_id={$_SESSION['sst_client_id']})";
		    

}
			//日期条件
			$start_date = $_GET ['start_date']; //开始日期
			$start_time = $_GET ['start_time']; //开始时间
			$stop_date = $_GET ['stop_date']; //结束日期
			$stop_time = $_GET ['stop_time']; //结束时间
				$tz = $_GET ['query'] ['tz'];//时区
			$start_day = $start_date;
			$end_day = $stop_date;
			$start = $start_date . '  ' . $start_time."  ".$tz; //开始时间
			$end = $stop_date . '  ' . $stop_time."  ".$tz; //结束时间
			

			//********************************************************************************************************
			//            普通单个条件查询(按照代理商,帐号卡)
			//********************************************************************************************************



			
			if (isset ( $_GET ['query'] ['country'] )) {
				$country = $_GET ['query'] ['country'];
				if (! empty ( $country )) {
					$country_where = "and code.country='$country'";
			
				}
			}
			if (isset ( $_GET ['query'] ['code_name'] )) {
				$code_name = $_GET ['query'] ['code_name'];
				if (! empty ( $code_name )) {
					$code_name_where = "and code.name='$code_name'";
			
				}
			}
			
			if (isset ( $_GET ['query'] ['code'] )) {
				$code = $_GET ['query'] ['code'];
				if (! empty ( $code )) {
					$code_where = "and code.code='$code'";
				
				}
			}
			
			
								if (isset ( $_GET ['query'] ['cost'] )) {
				$cost = $_GET ['query'] ['cost'];
				if (! empty ( $cost )) {
						if($cost=='nonzero'){
							   $cost_where = "and (sum(case when client_cdr.time between cr.start_time and case when cr.end_time is null then now() else cr.end_time end	then call_duration::numeric  else 0 end) /60 )>0";
						}
						if($cost=='zero'){
						   $cost_where = "and (sum(case when client_cdr.time between cr.start_time and case when cr.end_time is null then now() else cr.end_time end	then call_duration::numeric  else 0 end) /60 )=0.000";
						}
				}
			}
			
			
			
			
			if (isset ( $_GET ['query'] ['interval_from'] )) {
				$interval_from = $_GET ['query'] ['interval_from'];
				if (! empty ( $interval_from )) {
					$interval_from_where = "and  ((sum ( case  call_duration  when '' then 0 else call_duration::numeric end))/60)::numeric(20,2) ::numeric>=$interval_from";
				
				}
			}
					if (isset ( $_GET ['query'] ['interval_to'] )) {
				$interval_to = $_GET ['query'] ['interval_to'];
				if (! empty ($interval_to)) {
					$interval_to_where = "and  ((sum ( case  call_duration  when '' then 0 else call_duration::numeric end))/60)::numeric(20,2) ::numeric<=$interval_to";
					$this->set ( "interval_to", $_GET ['query'] ['interval_to'] );
				}
			}
			if (isset ( $_GET ['query'] ['dst_number'] )) {
				$dst_number = $_GET ['query'] ['dst_number'];
				if (! empty ( $dst_number )) {
					$dst_number_where = "and origination_destination_number='$dst_number'";
					$this->set ( "dst_number", $_GET ['query'] ['dst_number'] );
				}
			}
			if (isset ( $_GET ['query'] ['src_number'] )) {
				$src_number = $_GET ['query'] ['src_number'];
				if (! empty ( $src_number )) {
					$src_number_where = "and origination_source_number='$src_number'";
					$this->set ( "src_number", $_GET ['query'] ['src_number'] );
				}
			}
			$egress_alias = $this->data ['Cdr'] ['egress_alias'];
			if (! empty ( $egress_alias )) {
				$egress_where = "  and trunk_id_termination='$egress_alias'";
				$this->set ( "egress_post", $this->data ['Cdr'] ['egress_alias'] );
			}
			$ingress_alias = $this->data ['Cdr'] ['ingress_alias'];
			if (! empty ( $ingress_alias )) {
				$ingress_where = "  and trunk_id_origination='$ingress_alias'";
				$this->set ( "egress_post", $this->data ['Cdr'] ['egress_alias'] );
			}
		} 
		$this->set ( "start", $start );
		$this->set ( "end", $end );
		$this->set ( "start_day", $start_day );
		$this->set ( "end_day", $end_day );
		$this->set ( 'post', $this->data );
		
		
		

		$base_sql="select contract_record.id,contract_record.confirm_order_number,contract_record.start_time,contract_record.end_time,
		case contract_record.contract_type when 1 then buy_order.resource_id  when 2 then contract_record.resource_id else null end as ingress, case contract_record.contract_type 
   when 1 then contract_record.resource_id else null end as egress
   from contract_record   
   left join buy_order on contract_record.order_id=buy_order.id and  contract_record.contract_type=1 
   $where_sql
   
   ";
			$where=		"	  $code_name_where $code_where  $country_where   $egress_where  $ingress_where";
			
		$org_bill_minute="sum(case when client_cdr.time between cr.start_time and case when cr.end_time is null then now() else cr.end_time end	then call_duration::numeric  else 0 end) /60   as   org_bill_minute";
		$org_avg_rate="case  when sum(case when client_cdr.time between cr.start_time and case when
    cr.end_time is null then now() else cr.end_time end then call_duration::numeric else 0 end)=0 
    then 0 else sum(case when client_cdr.time between cr.start_time and case when cr.end_time is null then now() else cr.end_time end 
    then $order_cost_field::numeric else 0 end)/(sum(case when client_cdr.time between cr.start_time and case when
    cr.end_time is null then now() else cr.end_time end
    then call_duration::numeric else 0 end)/60)end  as  org_avg_rate";	

		
		
		$org_sql = "select   confirm_order_number as  order_id, cr.start_time  as update_time, code.country,	code.name as code_name,code.code,$org_bill_minute,
               $org_avg_rate  from  ($base_sql)as  cr  left join client_cdr 
   on client_cdr.ingress_id=cr.ingress::text and  client_cdr.egress_id=cr.egress::text and client_cdr.origination_destination_number::prefix_range <@ cr.code
and   client_cdr.time  between   '$start'  and  '$end' 
   left join code on code.code=cr.code    
        $where  
     group by  confirm_order_number,cr.start_time,cr.end_time	  ,country, code_name, code.code	
     having 1=1  $interval_to_where  $interval_from_where  $cost_where order by 1,2";
		
        
        
        
        
  		empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 	empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
			require_once 'MyPage.php';
			$page = new MyPage ();
			$totalrecords = $this->Cdr->query ("select count(*) as c from ($org_sql) as cc
      " );
		
		$page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
		$page->setCurrPage ( $currPage ); //当前页
		$page->setPageSize ( $pageSize ); //页大小
		$currPage = $page->getCurrPage () - 1;
		$pageSize = $page->getPageSize ();
		$_GET['page']=$currPage;
		$_GET['size']=$pageSize;
		$offset=$currPage*$pageSize;
        
			$page_where="  limit '$pageSize' offset '$offset'";
			$org_sql=$org_sql.$page_where;
        if(isset($_GET ['query'] ['output'])){
				if (	$_GET ['query'] ['output']== 'csv'){
					Configure::write('debug',0);
					$this->_catch_exception_msg(array('ClientcdrreportsController','_summary_reports_download_impl'),array('download_sql' => $org_sql));
				}else{
							$results = $this->Cdr->query ($org_sql );
		$page->setDataArray($results);
	$this->set('p',$page);
				}
		
		}else{
		
						$results = $this->Cdr->query ($org_sql );
		$page->setDataArray($results);
	$this->set('p',$page);
		}
	}
	
	function _summary_reports_download_impl($params=array()){
 		extract($params);
		if($this->Cdr->download_by_sql($download_sql,array('objectives'=>'summary_reports'))){
			exit(1);
		} 
	}
}
