<?php

/**
 * 
 * 
 * 销售统计报表
 * @author root
 *
 */
class SalestatisticsController extends AppController {
	var $name = 'Salestatistics';
	var $uses = array ('Cdr' );
	var $helpers = array ('javascript', 'html' );
	

	
	//查询封装
	
	
//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
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

	
	//初始化查询参数
	function init_query() {
		$this->set ( 'code_deck', $this->Cdr->find_code_deck () );
		$this->set ( 'currency', $this->Cdr->find_currency () );
		$this->set ( 'server', $this->Cdr->find_server ());
		$this->set ( 'ingress', $this->Cdr->findAll_ingress_alias () );
		$this->set ( 'egress', $this->Cdr->findAll_egress_alias () );
	
	}


	
	
	function sales_statistics() {
		// phpinfo(); 
		$this->init_query ();
		//date_default_timezone_set ( 'Asia/Shanghai' );	
		$login_type = $_SESSION ['login_type'];
		$start = date ( "Y-m-d  00:00:00" );
		$end = date ( "Y-m-d 23:59:59" );
		$start_day = date ( "Y-m-d  " );
		$end_day = date ("Y-m-d ");
		
		$client_where='';
		$server_where='';
		$currency_where='';
		$ingress_where='';
		$egress_where='';
		$code_where='';
		  $group_by_client='';
		   $group_by_client_cdr='';
		if (isset ( $_POST ['searchkey'] )) {
				//********************************************************************************************************
				//                                                                  所有统计条件
					//********************************************************************************************************
			//日期条件
			$start_date = $_POST ['start_date'];//开始日期
			$start_time = $_POST ['start_time'];//开始时间
			$stop_date = $_POST ['stop_date'];//结束日期
			$stop_time = $_POST ['stop_time'];//结束时间
			$start_day = $start_date;
			$end_day = $stop_date;
			$start = $start_date . '  ' . $start_time;//开始时间
			$end = $stop_date . '  ' . $stop_time;//结束时间
			if(!empty ( $_POST ['group_by_date'] )){
				
				 					$this->group_by_date($_POST['group_by_date']);
			     return true;
			}
   

     //单查条件
    // pr($_POST);
		
				   $client_type=$_POST['query']['client_type'];
					     if($client_type=='0'){
     $client_id =$_POST['query']['id_clients'];
     if(!empty($client_id)){
     	$client_where="and client_id='$client_id'";
     }
     

     	$this->set ( "client_name", $_POST['query']['id_clients_name'] );
     }else{
     	$reseller_id =$_POST['query']['id_clients'];
     	
     		 	$this->set( "reseller_name", $_POST['query']['id_clients_name']);
     }
		
			
					if(isset ( $_POST ['query']['code'] )){
				  $code= $_POST ['query']['code'] ;
				  if(!empty($code)){
				  $code_where="  and  origination_destination_number::prefix_range <@ '$code'";
				   	$this->set( "code_post", $_POST['query']['code']);
				  }
			}
			


    
		
			$server_ip = $this->data ['Cdr'] ['server_ip'];
			if(!empty($server_ip)){
			$server_where="and termination_source_host_name='$server_ip'";
			 	$this->set( "server_ip",$this->data ['Cdr'] ['server_ip']);
			}
			$code_deck = $this->data ['Cdr'] ['code_deck'];
			$currency = $this->data ['Cdr'] ['currency'];
			
			if(!empty($currency)){
				$currency_where="and currency='$currency'";
					$this->set( "currency_post",$this->data ['Cdr'] ['currency']);
			}
			$egress_alias = $this->data ['Cdr'] ['egress_alias'];
			
			if(!empty($egress_alias)){
		$egress_where="  and trunk_id_termination='$egress_alias'";
			$this->set( "egress_post",$this->data ['Cdr'] ['egress_alias']);
			
			}
			$ingress_alias = $this->data ['Cdr'] ['ingress_alias'];
			
			if(!empty($ingress_alias)){
			$ingress_where="  and trunk_id_origination='$ingress_alias'";
				$this->set( "ingress_post",$this->data ['Cdr'] ['ingress_alias']);
			}
			$report_type = $this->data ['Cdr'] ['report_type'];	
		
				//分组条件
			$group_by1 = $this->data ['Cdr'] ['group_by1'];
			$group_by2 = $this->data ['Cdr'] ['group_by2'];
			$group_by3 = $this->data ['Cdr'] ['group_by3'];
//********************************************************************************************************
//                                                                  所有统计条件
//********************************************************************************************************
			//分组查询
			
			if(!empty($group_by1)||!empty($group_by2)||!empty($group_by3)){
						 					$this->summary_reports_post_group();
			     return true;

			
			}
			
		} else {

$this->set("is_get_request",'true'); //get请求
		}
		


	//********************************************************************************************************
//                                                              打进    基本sql
//********************************************************************************************************
//打进 （计费时间，cost）  
  $cost_sql= "select  sum(bill_minutes::integer)as bill_minute,    sum(cost::numeric)  as  total_cost   from   class4_view_client_cost
    where  time  between   '$start'  and  '$end'   $client_where   $server_where   $currency_where  $ingress_where  $egress_where  $code_where";
 
  //打进总的call,总的通话时长  
 $call_sql="select  count(cdr_id)  as  total_calls,sum (call_duration::integer /60 +1)   as  total_duration  from  class4_view_client_cost 
where  time  between   '$start'  and  '$end'  $client_where  $server_where  $currency_where    $ingress_where  $egress_where  $code_where";

 //打进-不为0的call call_duration>0的cdr
$notzero_sql=" select   count(cdr_id)as notzero_calls from   class4_view_client_cost   where  call_duration::integer>0  and 
 time  between   '$start'  and  '$end'  $client_where  $server_where  $currency_where   $ingress_where  $egress_where  $code_where";
		

//打进成功的call   接通时间(answer_time_of_date)大于0的cdr
 $succ_sql="select count(cdr_id)as succ_calls from     class4_view_client_cost   where  answer_time_of_date::integer>0 and 
time  between   '$start'  and  '$end'  $client_where  $server_where  $currency_where   $ingress_where  $egress_where  $code_where"; 

 //打进繁忙的call
$busy_sql="select   count(cdr_id)as busy_calls  from     class4_view_client_cost    where  release_cause_from_protocol_stack='486'  and
time  between   '$start'  and  '$end'   $client_where  $server_where  $currency_where   $ingress_where  $egress_where  $code_where";

//打进没有通道的call
 $not_ch="select     count(cdr_id)as notchannel_calls  from     class4_view_client_cost  where  release_cause_from_protocol_stack='503'   and
time  between   '$start'  and  '$end' $client_where   $server_where  $currency_where   $ingress_where  $egress_where  $code_where";     

 
 
 
 
	//********************************************************************************************************
//                                                              打出    基本sql
//********************************************************************************************************
 //打进 （计费时间，cost）  
  $cost_sql1= "select  sum(bill_time::integer /60 +1)as bill_minute,    sum(cost::numeric)  as  total_cost  
   from   class4_view_client_egress_cost  where  time  between   '$start'  and  '$end' $client_where  $server_where  $currency_where 
     $ingress_where  $egress_where  $code_where";
 
  //打进总的call,总的通话时长  
 $call_sql1="select  count(cdr_id)  as  total_calls,sum (call_duration::integer /60 +1)   as  total_duration  from  class4_view_client_egress_cost 
where  time  between   '$start'  and  '$end'  $client_where  $server_where  $currency_where   $ingress_where  $egress_where  $code_where";

 //打进-不为0的call call_duration>0的cdr
$notzero_sql1=" select   count(cdr_id)as notzero_calls from   class4_view_client_egress_cost   where  call_duration::integer>0  and 
time  between   '$start'  and  '$end'  $client_where  $server_where  $currency_where   $ingress_where  $egress_where  $code_where";
		

//打进成功的call   接通时间(answer_time_of_date)大于0的cdr
 $succ_sql1="select count(cdr_id)as succ_calls from     class4_view_client_egress_cost   where  answer_time_of_date::integer>0 and 
time  between   '$start'  and  '$end'  $client_where  $server_where  $currency_where   $ingress_where  $egress_where  $code_where"; 

 //打进繁忙的call
$busy_sql1="select   count(cdr_id)as busy_calls  from     class4_view_client_egress_cost    where  release_cause_from_protocol_stack='486'  and
time  between   '$start'  and  '$end'  $client_where  $server_where  $currency_where   $ingress_where  $egress_where  $code_where";

//打进没有通道的call
 $not_ch1="select     count(cdr_id)as notchannel_calls  from     class4_view_client_egress_cost  where  release_cause_from_protocol_stack='503'   and
time  between   '$start'  and  '$end'  $client_where  $server_where  $currency_where   $ingress_where  $egress_where  $code_where";     
 
 //admin
			if ($login_type == 1) {
			
				$org_sql="select * from ($cost_sql)cost,($call_sql)call,($notzero_sql)notzero,($succ_sql)succ,($busy_sql)busy,($not_ch)nochan";
				
				$term_sql="select * from ($cost_sql1)cost,($call_sql1)call,($notzero_sql1)notzero,($succ_sql1)succ,($busy_sql1)busy,($not_ch1)nochan";
								
			}
			//reseller
			if ($login_type == 2) {
				$reseller_id = $_SESSION ['sst_reseller_id'];
				

			}
			//client
			if ($login_type == 3) {
				$client_id = $_SESSION ['sst_client_id'];
				
			}
			//充值用户
			

			if ($login_type == 4) {
				$car_id = $_SESSION ['sst_card_id'];
				$sql = "";
			}

			
			
			$org_list = $this->Cdr->query ($org_sql );
				$term_list = $this->Cdr->query ($term_sql );
			//pr($org_sql);
				//pr($term_sql);
		$this->set ( "client_org", $org_list );
		$this->set ( "client_term", $term_list );

		$this->set ("start", $start );
		$this->set ("end", $end );
		$this->set ("start_day", $start_day);
		$this->set ( "end_day", $end_day );
		
			$this->set ('post', $this->data );
	}
	
	
	

	
	
	/**
	 * 按时间分组
	 * @param unknown_type $group_by_date
	 */
	function group_by_date($group_by_date){
				$this->set ( 'is_group_by_date', 'true');
			
			$login_type = $_SESSION ['login_type'];//用户身份
			$start_date = $_POST ['start_date'];//开始日期
			$start_time = $_POST ['start_time'];//开始时间
			$stop_date = $_POST ['stop_date'];//结束日期
			$stop_time = $_POST ['stop_time'];//结束时间
				$start_day = $start_date;
			$end_day = $stop_date;
			$start = $start_date . '  ' . $start_time;//开始时间
			$end = $stop_date . '  ' . $stop_time;//结束时间
			
			//单查条件
    // pr($_POST);
		
				   $client_type=$_POST['query']['client_type'];
				 if($client_type=='0'){
     $client_id =$_POST['query']['id_clients'];
     	$this->set ( "client_name", $_POST['query']['id_clients_name'] );
     }else{
     	$reseller_id =$_POST['query']['id_clients'];
     	$this->set( "reseller_name", $_POST['query']['id_clients_name']);
     }
		
			
					if(isset ( $_POST ['query']['code'] )){
				  $code= $_POST ['query']['code'] ;
			}
			$server_ip = $this->data ['Cdr'] ['server_ip'];
			$code_deck = $this->data ['Cdr'] ['code_deck'];
			$currency = $this->data ['Cdr'] ['currency'];
			$egress_alias = $this->data ['Cdr'] ['egress_alias'];
			$ingress_alias = $this->data ['Cdr'] ['ingress_alias'];
			$report_type = $this->data ['Cdr'] ['report_type'];	
		
				//分组条件
			$group_by1 = $this->data ['Cdr'] ['group_by1'];
			$group_by2 = $this->data ['Cdr'] ['group_by2'];
			$group_by3 = $this->data ['Cdr'] ['group_by3'];
//********************************************************************************************************
//                                                                  所有统计条件
//********************************************************************************************************
			//分组查询
			
			if(!empty($group_by1)||!empty($group_by2)||!empty($group_by3)){
		
				if($group_by1=='client'||$group_by2=='client'||$group_by3=='client'){
		         $group_by_client='group by  client_id';
		           $group_by_client_cdr='group by  trunk_id_origination';
				}
			
			}
			

		


	//********************************************************************************************************
//                                                              打进    基本sql
//********************************************************************************************************
//打进 （计费时间，cost）  
  $cost_sql= "select   to_char(time, '$group_by_date') as d,    sum(bill_minutes::integer)as bill_minute,    sum(cost::numeric)  as  total_cost 
    from   class4_view_client_cost
    where  time  between   '$start'  and  '$end'   group by d  order  by  d  ";
 
  //打进总的call,总的通话时长  
 $call_sql="select   to_char(time, '$group_by_date') as d,  count(cdr_id)  as  total_calls,sum (call_duration::integer /60 +1)   as  total_duration 
  from  class4_view_client_cost 
where  time  between   '$start'  and  '$end'   group by d  order  by  d";

 //打进-不为0的call call_duration>0的cdr
$notzero_sql=" select  to_char(time, '$group_by_date') as d,  count(cdr_id)as notzero_calls
 from   class4_view_client_cost   where  call_duration::integer>0  and 
   time  between   '$start'  and  '$end'   group by d  order  by  d ";
		

//打进成功的call   接通时间(answer_time_of_date)大于0的cdr
 $succ_sql="select to_char(time, '$group_by_date') as d, count(cdr_id)as succ_calls 
 from     class4_view_client_cost   where  answer_time_of_date::integer>0 and 
 time  between   '$start'  and  '$end'   group by d  order  by  d "; 

 //打进繁忙的call
$busy_sql="select to_char(time, '$group_by_date') as d,  count(cdr_id)as busy_calls 
 from     class4_view_client_cost    where  release_cause_from_protocol_stack='486'  and
  time  between   '$start'  and  '$end'   group by d  order  by  d ";

//打进没有通道的call
 $not_ch="select   to_char(time, '$group_by_date') as d,  count(cdr_id)as notchannel_calls  from  
    class4_view_client_cost  where  release_cause_from_protocol_stack='503'   and
 time  between   '$start'  and  '$end'   group by d  order  by  d ";     

 
 
 
 
	//********************************************************************************************************
//                                                              打出    基本sql
//********************************************************************************************************
 //打进 （计费时间，cost）  
  $cost_sql1= "select   to_char(time, '$group_by_date') as d,  sum(bill_time::integer /60 +1)as bill_minute,    sum(cost::numeric)  as  total_cost 
    from   class4_view_client_egress_cost  where   time  between   '$start'  and  '$end'  group by d  order  by  d";
 
  //打进总的call,总的通话时长  
 $call_sql1="select  to_char(time, '$group_by_date') as d,   count(cdr_id)  as  total_calls,sum (call_duration::integer /60 +1)   as  total_duration 
  from  class4_view_client_egress_cost 
where  time  between   '$start'  and  '$end' group by d  order  by  d";

 //打进-不为0的call call_duration>0的cdr
$notzero_sql1=" select  to_char(time, '$group_by_date') as d,   count(cdr_id)as notzero_calls from   class4_view_client_egress_cost 
  where  call_duration::integer>0  and time  between   '$start'  and  '$end'  group by d  order  by  d";
		

//打进成功的call   接通时间(answer_time_of_date)大于0的cdr
 $succ_sql1="select  to_char(time, '$group_by_date') as d, count(cdr_id)as succ_calls from     class4_view_client_egress_cost
    where  answer_time_of_date::integer>0 and 
time  between   '$start'  and  '$end'  group by d  order  by  d"; 

 //打进繁忙的call
$busy_sql1="select   to_char(time, '$group_by_date') as d,  count(cdr_id)as busy_calls  from     class4_view_client_egress_cost  
  where  release_cause_from_protocol_stack='486'  and
time  between   '$start'  and  '$end'  group by d  order  by  d";

//打进没有通道的call
 $not_ch1="select   to_char(time, '$group_by_date') as d,  count(cdr_id)as notchannel_calls  from     class4_view_client_egress_cost 
  where  release_cause_from_protocol_stack='503'   and
time  between   '$start'  and  '$end'  group by d  order  by  d";     
 
 //admin
 
 
 
			if ($login_type == 1) {
			
				$org_sql="select cost.d as date ,bill_minute ,total_cost,total_calls, total_duration,notzero_calls,succ_calls,busy_calls,notchannel_calls  from 
				($cost_sql)cost  left join ($call_sql)call on call.d=cost.d left  join
				 ($notzero_sql)notzero  on notzero.d=cost.d left  join   ($succ_sql)succ   on succ.d=cost.d left  join  
				 ($busy_sql)busy   on busy.d=cost.d left  join($not_ch)nochan  on nochan.d=cost.d ";
				
				$term_sql="select cost.d as date ,bill_minute ,total_cost,total_calls, total_duration,notzero_calls,succ_calls,busy_calls,notchannel_calls from ($cost_sql1)cost  left  join($call_sql1)call  on call.d=cost.d left  join 
				($notzero_sql1)notzero on notzero.d=cost.d left  join  ($succ_sql1)succ on succ.d=cost.d 
				left  join  ($busy_sql1)busy  on busy.d=cost.d left  join ($not_ch1)nochan  on nochan.d=cost.d    ";
								
			}
			//reseller
			if ($login_type == 2) {
				$reseller_id = $_SESSION ['sst_reseller_id'];
				

			}
			//client
			if ($login_type == 3) {
				$client_id = $_SESSION ['sst_client_id'];
				
			}
			//充值用户
			

			if ($login_type == 4) {
				$car_id = $_SESSION ['sst_card_id'];
				$sql = "";
			}

			
			
			$org_list = $this->Cdr->query ($org_sql );
				$term_list = $this->Cdr->query ($term_sql );
	//	pr($org_sql);
		//		pr($term_sql);
		//		pr($_POST);
		$this->set ( "client_org", $org_list );
		$this->set ( "client_term", $term_list );

		$this->set ("start", $start );
		$this->set ("end", $end );
		$this->set ("start_day", $start_day);
		$this->set ( "end_day", $end_day );
		
			$this->set ('post', $this->data );

			
	
	}
	
	
	
	
	
	/*
	 * 
	 * 
	 * 各种分组查询
	 * 
	 */
	function summary_reports_post_group(){
		
				
					
			$login_type = $_SESSION ['login_type'];//用户身份
			$start_date = $_POST ['start_date'];//开始日期
			$start_time = $_POST ['start_time'];//开始时间
			$stop_date = $_POST ['stop_date'];//结束日期
			$stop_time = $_POST ['stop_time'];//结束时间
			$start_day = $start_date;
			$end_day = $stop_date;
			$start = $start_date . '  ' . $start_time;//开始时间
			$end = $stop_date . '  ' . $stop_time;//结束时间
			
			//单查条件
    // pr($_POST);
		
				   $client_type=$_POST['query']['client_type'];
				 if($client_type=='0'){
     $client_id =$_POST['query']['id_clients'];
     	$this->set ( "client_name", $_POST['query']['id_clients_name'] );
     }else{
     	$reseller_id =$_POST['query']['id_clients'];
     	$this->set( "reseller_name", $_POST['query']['id_clients_name']);
     }
		
			
					if(isset ( $_POST ['query']['code'] )){
				  $code= $_POST ['query']['code'] ;
			}
			$server_ip = $this->data ['Cdr'] ['server_ip'];
			$code_deck = $this->data ['Cdr'] ['code_deck'];
			$currency = $this->data ['Cdr'] ['currency'];
			$egress_alias = $this->data ['Cdr'] ['egress_alias'];
			$ingress_alias = $this->data ['Cdr'] ['ingress_alias'];
			$report_type = $this->data ['Cdr'] ['report_type'];	
		
				//分组条件
			$group_by1 = $this->data ['Cdr'] ['group_by1'];
			$group_by2 = $this->data ['Cdr'] ['group_by2'];
			$group_by3 = $this->data ['Cdr'] ['group_by3'];
			
			$group_by4 = $this->data ['Cdr'] ['group_by4'];
			$group_by5 = $this->data ['Cdr'] ['group_by5'];
			$group_by6 = $this->data ['Cdr'] ['group_by6'];
//********************************************************************************************************
//                                                                  所有统计条件
//********************************************************************************************************
			//分组查询
			
			if(!empty($group_by1)||!empty($group_by2)||!empty($group_by3)){
				//按client 分组
				if($group_by1=='client'||$group_by2=='client'||$group_by3=='client'){
		         $group_by_field1='client_name';
		         	$this->set ('group_by_field1','client_name');
		         	//第2次分组
										if($group_by1=='class4'||$group_by2=='class4'||$group_by3=='class4'){
		             $group_by_field1='client_name,termination_source_host_name';
		            	$this->set ('group_by_field1', 'client_name');
		            	$this->set ('group_by_field2', 'termination_source_host_name');
		         
				}
		         
				}
				//按class4-server分组
							if($group_by1=='class4'||$group_by2=='class4'||$group_by3=='class4'){
		         $group_by_field1='termination_source_host_name';
		         	$this->set ('group_by_field1', 'termination_source_host_name');
		         
				}
							//按code分组
							if($group_by1=='code'||$group_by2=='code'||$group_by3=='code'){
		         $group_by_field1='code';
		         	$this->set ('group_by_field1', '号码');
		         
				}
				
				
										//按code名字分组
							if($group_by1=='code_name'||$group_by2=='code_name'||$group_by3=='code_name'){
		         $group_by_field1='code_name';
		         	$this->set ('group_by_field2', '号码名称');
		         
				}
				
												//城市分组
							if($group_by1=='city'||$group_by2=='city'||$group_by3=='city'){
		         $group_by_field1='city';
		         	$this->set ('group_by_field1', 'city');
		         
				}
				
												//按省份分组
							if($group_by1=='state'||$group_by2=='state'||$group_by3=='state'){
		         $group_by_field1='state';
		         	$this->set ('group_by_field1', 'state');
		         
				}
				
				
															//按省份分组
							if($group_by1=='state'||$group_by2=='state'||$group_by3=='state'){
		         $group_by_field1='state';
		         	$this->set ('group_by_field1', 'state');
		         
				}
				
																		//按国家分组
							if($group_by1=='country'||$group_by2=='country'||$group_by3=='country'){
		         $group_by_field1='country';
		         	$this->set ('group_by_field1', 'country');
		         
				}
			
			}
			

		


	//********************************************************************************************************
//                                                              打进    基本sql
//********************************************************************************************************
//打进 （计费时间，cost）  
  $cost_sql= "select   $group_by_field1 ,    sum(bill_minutes::integer)as bill_minute,    sum(cost::numeric)  as  total_cost 
    from   class4_view_client_cost
    where  time  between   '$start'  and  '$end'   group by $group_by_field1   ";
 
  //打进总的call,总的通话时长  
 $call_sql="select   $group_by_field1 ,  count(cdr_id)  as  total_calls,sum (call_duration::integer /60 +1)   as  total_duration 
  from  class4_view_client_cost 
where  time  between   '$start'  and  '$end'   group by $group_by_field1  ";

 //打进-不为0的call call_duration>0的cdr
$notzero_sql=" select  $group_by_field1 ,  count(cdr_id)as notzero_calls
 from   class4_view_client_cost   where  call_duration::integer>0  and 
   time  between   '$start'  and  '$end'   group by  $group_by_field1 ";
		

//打进成功的call   接通时间(answer_time_of_date)大于0的cdr
 $succ_sql="select $group_by_field1 , count(cdr_id)as succ_calls 
 from     class4_view_client_cost   where  answer_time_of_date::integer>0 and 
 time  between   '$start'  and  '$end'   group by  $group_by_field1 "; 

 //打进繁忙的call
$busy_sql="select $group_by_field1 ,  count(cdr_id)as busy_calls 
 from     class4_view_client_cost    where  release_cause_from_protocol_stack='486'  and
  time  between   '$start'  and  '$end'   group by  $group_by_field1 ";

//打进没有通道的call
 $not_ch="select   $group_by_field1 ,  count(cdr_id)as notchannel_calls  from  
    class4_view_client_cost  where  release_cause_from_protocol_stack='503'   and
 time  between   '$start'  and  '$end'   group by   $group_by_field1 ";     

 
 
 
 
	//********************************************************************************************************
//                                                              打出    基本sql
//********************************************************************************************************
 //打进 （计费时间，cost）  
  $cost_sql1= "select   $group_by_field1 ,  sum(bill_time::integer /60 +1)as bill_minute,    sum(cost::numeric)  as  total_cost 
    from   class4_view_client_egress_cost  where   time  between   '$start'  and  '$end'  group by $group_by_field1";
 
  //打进总的call,总的通话时长  
 $call_sql1="select  $group_by_field1 ,   count(cdr_id)  as  total_calls,sum (call_duration::integer /60 +1)   as  total_duration 
  from  class4_view_client_egress_cost 
where  time  between   '$start'  and  '$end' group by $group_by_field1";

 //打进-不为0的call call_duration>0的cdr
$notzero_sql1=" select  $group_by_field1 ,   count(cdr_id)as notzero_calls from   class4_view_client_egress_cost 
  where  call_duration::integer>0  and time  between   '$start'  and  '$end'  group by $group_by_field1";
		

//打进成功的call   接通时间(answer_time_of_date)大于0的cdr
 $succ_sql1="select  $group_by_field1 , count(cdr_id)as succ_calls from     class4_view_client_egress_cost
    where  answer_time_of_date::integer>0 and 
time  between   '$start'  and  '$end'  group by $group_by_field1"; 

 //打进繁忙的call
$busy_sql1="select   $group_by_field1 ,  count(cdr_id)as busy_calls  from     class4_view_client_egress_cost  
  where  release_cause_from_protocol_stack='486'  and
time  between   '$start'  and  '$end'  group by $group_by_field1";

//打进没有通道的call
 $not_ch1="select  $group_by_field1 ,  count(cdr_id)as notchannel_calls  from     class4_view_client_egress_cost 
  where  release_cause_from_protocol_stack='503'   and
time  between   '$start'  and  '$end'  group by $group_by_field1";     
 
 //admin
 
 
 
			if ($login_type == 1) {
			
				$org_sql="select cost.*  ,total_calls, total_duration,notzero_calls,succ_calls,busy_calls,notchannel_calls  from 
				($cost_sql)cost  left join ($call_sql)call on call.$group_by_field1=cost.$group_by_field1 left  join
				 ($notzero_sql)notzero  on notzero.$group_by_field1=cost.$group_by_field1 left  join   ($succ_sql)succ   on succ.$group_by_field1=cost.$group_by_field1
				  left  join  
				 ($busy_sql)busy   on busy.$group_by_field1=cost.$group_by_field1 left  join($not_ch)nochan  on nochan.$group_by_field1=cost.$group_by_field1 ";
				
				$term_sql="select cost.* ,total_calls, total_duration,notzero_calls,succ_calls,busy_calls,notchannel_calls  from
				 ($cost_sql1)cost  left  join($call_sql1)call  on call.$group_by_field1=cost.$group_by_field1 left  join 
				($notzero_sql1)notzero on notzero.$group_by_field1=cost.$group_by_field1 left  join  ($succ_sql1)succ on succ.$group_by_field1=cost.$group_by_field1 
				left  join  ($busy_sql1)busy  on busy.$group_by_field1=cost.$group_by_field1 left  join ($not_ch1)nochan  on nochan.$group_by_field1=cost.$group_by_field1    ";
								
			}
			//reseller
			if ($login_type == 2) {
				$reseller_id = $_SESSION ['sst_reseller_id'];
				

			}
			//client
			if ($login_type == 3) {
				$client_id = $_SESSION ['sst_client_id'];
				
			}
			//充值用户
			

			if ($login_type == 4) {
				$car_id = $_SESSION ['sst_card_id'];
				$sql = "";
			}

			
			
			$org_list = $this->Cdr->query ($org_sql );
				$term_list = $this->Cdr->query ($term_sql );
//pr($org_sql);
//pr($term_sql);
		$this->set ( "client_org", $org_list );
		$this->set ( "client_term", $term_list );

		$this->set ("start", $start );
		$this->set ("end", $end );
		$this->set ("start_day", $start_day);
		$this->set ( "end_day", $end_day );
		
			$this->set ('post', $this->data );

			
	}
	
	
	
	
	
	/**
	 *
	 *
	 *
	 *post查询
	 */
	function summary_reports_post(){
		
		//提取条件
			$login_type = $_SESSION ['login_type'];//用户身份
			$start_date = $_POST ['start_date'];//开始日期
			$start_time = $_POST ['start_time'];//开始时间
			$stop_date = $_POST ['stop_date'];//结束日期
			$stop_time = $_POST ['stop_time'];//结束时间
	

			$reseller_id = $this->data ['Cdr'] ['reseller_id'];
			$client_id = $this->data ['Cdr'] ['client_id'];
			$account_id = $this->data ['Cdr'] ['account_id'];			
			$where='';
			$client_where='';
			//通过代理商查询
			if(!empty($reseller_id)){
			$where =" and reseller.reseller_id=$reseller_id";
				}
				
				
				//通过client查询
			if(!empty($client_id)){
			$client_where =" and client.client_id=$client_id";
			
				}
				
				if(!empty($account_id)){
			$client_where =" and client.card_id=$client_id";
			
				}

			$start_day = $start_date;
			$end_day = $stop_date;
			$start = $start_date . '  ' . $start_time;//开始时间
			$end = $stop_date . '  ' . $stop_time;//结束时间
			

			
			//admin
			if ($login_type == 1) {
				
	
				
			}
			//reseller
			if ($login_type == 2) {
				$reseller_id = $_SESSION ['sst_reseller_id'];
				

			}
			//client
			if ($login_type == 3) {
				$client_id = $_SESSION ['sst_client_id'];
				

			}
			//充值用户
			

			if ($login_type == 4) {
				$car_id = $_SESSION ['sst_card_id'];
				$sql = "";
			}


    	

		$this->set ("start", $start );
		$this->set ("end", $end );
		$this->set ("start_day", $start_day);
		$this->set ( "end_day", $end_day );
	}
	
	
}