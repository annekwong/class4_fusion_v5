<?php
class SettlementreportsController extends AppController {
	var $name = 'Settlementreports';
	var $uses = array ('Cdr' );
	var $helpers = array ('javascript', 'html' );
	
	//查询封装
	
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	//初始化查询参数
	function init_query() {
		$this->set ( 'city', $this->Cdr->find_city () );
		$this->set ( 'account', $this->Cdr->find_account () );
		$reseller_id = $this->Session->read('sst_reseller_id');
		$this->Cdr->generateTree($reseller_id);
			$this->set('r_reseller',Cdr::$show_reseller);
		$this->set ( 'ingress', $this->Cdr->findAll_ingress_alias () );
		$this->set ( 'egress', $this->Cdr->findAll_egress_alias () );
		$this->set ( 'client', $this->Cdr->findClient () );
		


	
	}
	
	function init_account_query() {
		$this->set ( 'month', $this->Cdr->find_month () );
	
	}
	

	
	/**
	 * get请求
	 */
	function settlement_report_get(){
				$login_type = $_SESSION ['login_type'];
		$start = date ( "Y-m-d  00:00:00" );
		$end = date ( "Y-m-d 23:59:59" );
		$start_day = date ( "Y-m-d  " );
		$end_day = date ("Y-m-d ");
			

			//admin
			if ($login_type == 1) {
				
				$reseller_sql = "select  reseller.reseller_id,name  , r_income,r_total_cost,r_profit,r_call_duration,r_bill_time ,r_cdr_count,
reseller_package from  reseller  left join (
select  reseller_id, sum( income) as  r_income,  sum(cost) as  r_total_cost,sum(profit)as r_profit, sum( call_duration/60+1)as r_call_duration   ,
sum(bill_time/60+1)as r_bill_time  ,count(cdr_id)as r_cdr_count from  reseller_cost  where  time  between '$start'  and  '$end' group by reseller_id) rc
on rc.reseller_id=reseller.reseller_id   
left join
(
 select reseller_id, sum(cost)  as   reseller_package     from  reseller_cost     where  time  between '$start'  and  '$end' and    cost_type= 12   group by  reseller_id
) rp  on rp.reseller_id=reseller.reseller_id     where r_income>0
order by r_income
 ";
				
				$client_sql = "select  client.client_id,name  , r_income,r_total_cost,r_call_duration,r_bill_time ,r_cdr_count from  client  left join (
select  client_id,   sum(cost) as  r_total_cost, sum( call_duration/60+1)as r_call_duration   ,
sum(bill_time/60+1)as r_bill_time  ,count(cdr_id)as r_cdr_count from  client_cost   where  time  between '$start'  and  '$end'  group by client_id) rc
on rc.client_id=client.client_id   
left join
(
 select client_id,     sum( cost)  as   r_income     from  egress_cost where  time  between '$start'  and  '$end'     group by  client_id
) rp  on rp.client_id=client.client_id     where r_income>0
order by r_income
 ";
				
				$account_sql = "select  card.card_id,card_number  ,r_total_cost,r_call_duration,r_bill_time ,r_cdr_count ,reseller_package from  card 

				left join (
select  account_id,  sum(cost) as  r_total_cost, sum( call_duration/60+1)as r_call_duration   ,sum(bill_time/60+1)as r_bill_time 
 ,count(cdr_id)as r_cdr_count
 from  account_cost   where  time  between '$start'  and  '$end'  group by account_id) rc
on rc.account_id=card.card_id   
left join
(
 select account_id,     sum( cost)  as   reseller_package      from  account_cost     where  time  between '$start'  and  '$end'   and cost_type= 12     group by  account_id
) rp  on rp.account_id=card.card_id     where r_total_cost>0
order by r_total_cost
 ";
			}
			//reseller
			if ($login_type == 2) {
				$reseller_id = $_SESSION ['sst_reseller_id'];
				
				$sql = "select  lc.country,lc.state,lc.city ,lc.number,total_cost,bill_time,cdr_size from   location_code  as  lc
left join (

select         
city ,  sum(cost)as total_cost ,sum(minutes)as  bill_time, count(cdr_id)as  cdr_size
  from  location_cdr_report   where  time between '$start'  and  '$end'   $city  group by  city  )  l  on l.city=lc.city  where true $city2
 ";
			}
			//client
			if ($login_type == 3) {
				$client_id = $_SESSION ['sst_client_id'];
				
				$sql = "select   egress_name, reseller_name,
sum (six_seconds)as six_seconds ,sum(minutes)as minutes,sum(bill_time)/60  as  bill_time,
sum(cost)as cost ,sum(call_duration)as call_duration from  egress_cost    

left join (select  resource_id, name as egress_name  from  resource )as resource on resource.resource_id =egress_id 
left join (select reseller_id,name as reseller_name from reseller  where reseller_id=reseller_id )as reseller on reseller.reseller_id =reseller_id 
where client_id=$client_id  and  time between  '$start'  and  '$end'  group by egress_id,reseller_id;";
			}
			//充值用户
			

			if ($login_type == 4) {
				$car_id = $_SESSION ['sst_card_id'];
				$sql = "";
			}
		
			$list = $this->Cdr->query ( $reseller_sql );
		$client_list = $this->Cdr->query ( $client_sql );
		$account_list = $this->Cdr->query ( $account_sql );

	/*		pr($reseller_sql);
			pr($client_sql);
				pr($account_sql);*/
		$this->set ( "post", $list );
		$this->set ( "post2", $client_list );
		$this->set ( "post3", $account_list );
		
		$this->set ("start", $start );
		$this->set ("end", $end );
		$this->set ("start_day", $start_day);
		$this->set ( "end_day", $end_day );
	}
	
	
	
	
	

	
	/**
	 * 
	 * 
	 * 
	 * 
	 * 结算报表
	 */
	function settlement_report() {
		$this->init_query ();
		//初始化查询条件
		//date_default_timezone_set ( 'Asia/Shanghai' );
		$login_type = $_SESSION ['login_type'];
		$start = date ( "Y-m-d  00:00:00" );
		$end = date ( "Y-m-d 23:59:59" );
		$start_day = date ( "Y-m-d  " );
		$end_day = date ("Y-m-d ");
		
		//post请求
		if (isset ( $_POST ['searchkey'] )) {
				$this->set ( 'is_query', 'true');
			$smartPeriod = $_POST ['smartPeriod'];
			$start_date = $_POST ['start_date'];//开始日期
			$start_time = $_POST ['start_time'];//开始时间
			$stop_date = $_POST ['stop_date'];//结束日期
			$stop_time = $_POST ['stop_time'];//结束时间
	
			
			$reseller_id = $this->data ['Cdr'] ['reseller_id'];
			$client_id = $this->data ['Cdr'] ['client_id'];
			$account_id = $this->data ['Cdr'] ['account_id'];
					$call_type = $this->data ['Cdr'] ['call_type'];
			$start_day = $start_date;
			$end_day = $stop_date;
			$start = $start_date . '  ' . $start_time;
			$end = $stop_date . '  ' . $stop_time;
			
			if ($login_type == 1) {
				if (empty ( $reseller_id )) {
					$reseller_sql = "select  reseller.reseller_id,name  , r_income,r_total_cost,r_profit,r_call_duration,r_bill_time ,r_cdr_count,
reseller_package from  reseller  left join (
select  reseller_id, sum( income) as  r_income,  sum(cost) as  r_total_cost,sum(profit)as r_profit, sum( call_duration/60+1)as r_call_duration   ,
sum(bill_time/60+1)as r_bill_time  ,count(cdr_id)as r_cdr_count from  reseller_cost  where time between '$start'  and '$end'  group by reseller_id) rc
on rc.reseller_id=reseller.reseller_id   
left join
(
 select reseller_id, sum(cost)  as   reseller_package     from  reseller_cost   where   time between '$start'  and '$end' and    cost_type= 12   group by  reseller_id
) rp  on rp.reseller_id=reseller.reseller_id     where r_income>0
order by r_income
 ";
				
				} else {
					$reseller_sql = "select  reseller.reseller_id,name  , r_income,r_total_cost,r_profit,r_call_duration,r_bill_time ,r_cdr_count,
reseller_package from  reseller  left join (
select  reseller_id, sum( income) as  r_income,  sum(cost) as  r_total_cost,sum(profit)as r_profit, sum( call_duration/60+1)as r_call_duration   ,
sum(bill_time/60+1)as r_bill_time  ,count(cdr_id)as r_cdr_count from  reseller_cost where   time between '$start'  and '$end' and   reseller_id=$reseller_id   group by reseller_id) rc
on rc.reseller_id=reseller.reseller_id   
left join
(
 select reseller_id, sum(cost)  as   reseller_package     from  reseller_cost   where   time between '$start'  and '$end'   and  cost_type= 12   and  reseller_id=$reseller_id
 
 group by reseller_id
) rp  on rp.reseller_id=reseller.reseller_id 
    where  reseller.reseller_id  in($reseller_id)  
order by r_income
 ";
				}
				
				if (empty ( $client_id )) {
					$client_sql = '';
					$client_sql = "select  client.client_id,name  , r_income,r_total_cost,r_call_duration,r_bill_time ,r_cdr_count from  client  left join (
select  client_id,   sum(cost) as  r_total_cost, sum( call_duration/60+1)as r_call_duration   ,
sum(bill_time/60+1)as r_bill_time  ,count(cdr_id)as r_cdr_count from     client_cost   where time between '$start'  and '$end'  group by client_id) rc
on rc.client_id=client.client_id   
left join
(
 select client_id,     sum( cost)  as   r_income     from  egress_cost    where time between '$start'  and '$end'   group by  client_id
) rp  on rp.client_id=client.client_id     where r_income>0
order by r_income
 ";
				
				} else {
					$client_sql = "select  client.client_id,name  , r_income,r_total_cost,r_call_duration,r_bill_time ,r_cdr_count from  client  left join (
select  client_id,   sum(cost) as  r_total_cost, sum( call_duration/60+1)as r_call_duration   ,
sum(bill_time/60+1)as r_bill_time  ,count(cdr_id)as r_cdr_count from  client_cost where client_id=$client_id  and time between '$start'  and '$end'     group by client_id) rc
on rc.client_id=client.client_id   
left join
(
 select client_id,     sum( cost)  as   r_income     from  egress_cost     where  client_id=$client_id  and time between '$start'  and '$end'   group by client_id
) rp  on rp.client_id=client.client_id     where  client.client_id=$client_id  
order by r_income
 ";
				}
				
				if (empty ( $account_id )) {
					$account_sql = "select  card.card_id,card_number  ,r_total_cost,r_call_duration,r_bill_time ,r_cdr_count ,reseller_package from  card  left join (
select  account_id,  sum(cost) as  r_total_cost, sum( call_duration/60+1)as r_call_duration   ,sum(bill_time/60+1)as r_bill_time 
 ,count(cdr_id)as r_cdr_count
 from  account_cost   where time between '$start'  and '$end' group by account_id) rc
on rc.account_id=card.card_id   
left join
(
 select account_id,     sum( cost)  as   reseller_package      from  account_cost  where time between '$start'  and '$end'  and cost_type= 12     group by  account_id
) rp  on rp.account_id=card.card_id     where r_total_cost>0
order by r_total_cost
 ";
				} else {
					$account_sql = "select  card.card_id,card_number  ,r_total_cost,r_call_duration,r_bill_time ,r_cdr_count ,reseller_package from  card  left join (
select  account_id,  sum(cost) as  r_total_cost, sum( call_duration/60+1)as r_call_duration   ,sum(bill_time/60+1)as r_bill_time 
 ,count(cdr_id)as r_cdr_count
 from  account_cost where account_id=$account_id and time between '$start'  and '$end'  group by account_id) rc
on rc.account_id=card.card_id   
left join
(
 select account_id,     sum( cost)  as   reseller_package      from  account_cost  where cost_type= 12    and  account_id=$account_id  and time between '$start'  and '$end'
 
 group by account_id
) rp  on rp.account_id=card.card_id     where  card.card_id=$account_id   
order by r_total_cost
 ";
				}
			
			}
			//reseller
			if ($login_type == 2) {
				$reseller_id = $_SESSION ['sst_reseller_id'];
				
				$sql = "select  lc.country,lc.state,lc.city ,lc.number,total_cost,bill_time,cdr_size from   location_code  as  lc
left join (

select         
city ,  sum(cost)as total_cost ,sum(minutes)as  bill_time, count(cdr_id)as  cdr_size
  from  location_cdr_report   where  time between '$start'  and  '$end'   $city  group by  city  )  l  on l.city=lc.city  where true $city2
 ";
			}
			//client
			if ($login_type == 3) {
				$client_id = $_SESSION ['sst_client_id'];
				
				$sql = "select   egress_name, reseller_name,
sum (six_seconds)as six_seconds ,sum(minutes)as minutes,sum(bill_time)/60  as  bill_time,
sum(cost)as cost ,sum(call_duration)as call_duration from  egress_cost    

left join (select  resource_id, name as egress_name  from  resource )as resource on resource.resource_id =egress_id 
left join (select reseller_id,name as reseller_name from reseller  where reseller_id=reseller_id )as reseller on reseller.reseller_id =reseller_id 
where client_id=$client_id  and  time between  '$start'  and  '$end'  group by egress_id,reseller_id;";
			}
			//充值用户
			

			if ($login_type == 4) {
				$car_id = $_SESSION ['sst_card_id'];
				$sql = "";
			}
				$list = $this->Cdr->query ( $reseller_sql );
		$client_list = $this->Cdr->query ( $client_sql );
		$account_list = $this->Cdr->query ( $account_sql );

/*		pr($reseller_sql);
			pr($client_sql);
				pr($account_sql);*/
		$this->set ( "post", $list );
		$this->set ( "post2", $client_list );
		$this->set ( "post3", $account_list );
		
		$this->set ("start", $start );
		$this->set ("end", $end );
		$this->set ("start_day", $start_day);
		$this->set ( "end_day", $end_day );
		} else {
			
			$this->settlement_report_get();

		}
		

	
	}

	

	
	
	

	
	
	
	
	
	





	

	
}