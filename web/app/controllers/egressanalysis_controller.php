<?php


/**
 * 
 * 
 * 
落地网关费用分析
 * @author root
 *
 */
class EgressanalysisController extends AppController {
	var $name = 'Egressanalysis';
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
		$this->set ( 'server', $this->Cdr->find_server () );
		$this->set ( 'ingress', $this->Cdr->findAll_ingress_alias () );
		$this->set ( 'egress', $this->Cdr->findAll_egress_alias () );

	
	}


	function summary_reports() {
		$this->init_query ();
		//date_default_timezone_set ( 'Asia/Shanghai' );
		$login_type = $_SESSION ['login_type'];
		$start = date ( "Y-m-d  00:00:00" );
		$end = date ( "Y-m-d 23:59:59" );
		$start_day = date ( "Y-m-d  " );
		$end_day = date ( "Y-m-d " );
		
		//单个的where查询条件
	$client_where = '';
		$reseller_where = '';
		$code_name_where = '';
		$code_where = '';
		$code_deck_where = '';
		$server_where = '';
		$currency_where = '';
		$egress_where = '';
		$rate_where = '';
		$card_where='';
		
		$group_by_where = ' ';//group  by 的条件
		$group_by_field_arr = array ();
		$group_by_field = '';
		$grpup_size=0;
		
		$show_comm=true;//普通表格显示
		$show_subgroups=false;//以subgroup方式显示 
		$show_subtotals=false;//以subgtotal方式显示 
	
		
		if (isset ( $_POST ['searchkey'] )) {
			
			//日期条件
			$start_date = $_POST ['start_date']; //开始日期
			$start_time = $_POST ['start_time']; //开始时间
			$stop_date = $_POST ['stop_date']; //结束日期
			$stop_time = $_POST ['stop_time']; //结束时间
			$start_day = $start_date;
			$end_day = $stop_date;
			$start = $start_date . '  ' . $start_time; //开始时间
			$end = $stop_date . '  ' . $stop_time; //结束时间
			

			//********************************************************************************************************
			//            普通单个条件查询(按照代理商,帐号卡)
			//********************************************************************************************************
		
					$card_id = $_POST ['query'] ['id_cards'];
			if (! empty ( $card_id )) {
				$card_where = "and account_id='$card_id'";
				$this->set ( "card_number", $_POST ['query'] ['id_cards_name'] );
			}
			
			$client_id = $_POST ['query'] ['id_clients'];
					
			if ($client_id != '') {
						$client_where = "and client_id='$client_id'";
					$this->set ( "client_name", $_POST ['query'] ['id_clients_name'] );
			
			}
			$reseller_id = $_POST ['query'] ['id_resellers'];
				if ($reseller_id != '') {
			   $reseller_where = "and reseller_id='$reseller_id'";
					$this->set ( "reseller_name", $_POST ['query'] ['id_resellers_name'] );
			
			}
			if (isset ( $_POST ['query'] ['code_name'] )) {
				$code_name = $_POST ['query'] ['code_name'];
				if (! empty ( $code_name )) {
					$code_name_where = "and term_code_name='$code_name'";
					$this->set ( "code_name", $_POST ['query'] ['code_name'] );
				}
			}
			
			if (isset ( $_POST ['query'] ['code'] )) {
				$code = $_POST ['query'] ['code'];
				if (! empty ( $code )) {
					$code_where = "and term_code='$code'";
					$this->set ( "code", $_POST ['query'] ['code'] );
				}
			}
			
			$server_ip = $this->data ['Cdr'] ['server_ip'];
			if (! empty ( $server_ip )) {
				$server_where = "and termination_source_host_name='$server_ip'";
				$this->set ( "server_ip", $this->data ['Cdr'] ['server_ip'] );
			}
			$code_deck = $this->data ['Cdr'] ['code_deck'];
			if (! empty ( $code_deck )) {
				$code_deck_where = "and term_code_deck_name='$code_deck'";
				$this->set ( "code_deck_name", $code_deck );
			}
			$currency = $this->data ['Cdr'] ['currency'];
			
			if (! empty ( $currency )) {
				$currency_where = "and currency='$currency'";
				$this->set ( "currency_post", $this->data ['Cdr'] ['currency'] );
			}
			$egress_alias = $this->data ['Cdr'] ['egress_alias'];
			
			if (! empty ( $egress_alias )) {
				$egress_where = "  and egress_alias='$egress_alias'";
				$this->set ( "egress_post", $this->data ['Cdr'] ['egress_alias'] );
			
			}
					if (isset ( $_POST ['query'] ['id_rates'] )) {
				$rate_id = $_POST ['query'] ['id_rates'];
				if (! empty ( $rate_id )) {
					$rate_where = "and rate_table_id='$rate_id'";
					$this->set ( "rate_name", $_POST ['query'] ['rate_name'] );
				}
			}
			
			

			
			//********************************************************************************************************
			//            按照代理商，帐户卡分组查询
			//********************************************************************************************************
			

			$group_by_where = 'group by ';
			$group_by_field_arr = array ();
			$group_by_field = ''; //分组字段

			if (isset ( $_POST ['group_by'] )) {
			
				foreach ( $_POST ['group_by'] as $k => $v ) {
					if (empty ( $v )) {
						continue;
					}
								if (in_array($v, $group_by_field_arr)) {
   continue;
}
					array_push ( $group_by_field_arr, $v );
					$group_by_where = $group_by_where . $v . ",";
					$group_by_field = $group_by_field . $v . ",";

	
				}
				$group_by_where = substr ( $group_by_where, 0, - 1 );

				
			
			
			}
			
			//********************************************************************************************************
			//            按照时间(月份,年)分组查询
			//********************************************************************************************************
			if (! empty ( $_POST ['group_by_date'] )) {
				$group_by_date = $_POST ['group_by_date'];
						array_push ( $group_by_field_arr, 'date');
				if (empty ( $group_by_field )) {
					
					$group_by_field = "to_char(time, '$group_by_date') as date,";
					$group_by_where = "group by date  ";

				} else {
					//有其他的分组条件
					$group_by_field = $group_by_field."to_char(time, '$group_by_date') as date,";
					$group_by_where = $group_by_where.", date  ";

				}
			
			}else{
					if (empty ( $group_by_field )) {
					$group_by_where = "";
				} else {
				
				}
			
			
			}
		
		} 

			//********************************************************************************************************
			//           subgroups  分子组显示
			//********************************************************************************************************
				$grpup_size=count($group_by_field_arr);
	 if(isset($_POST['query']['output_subgroups'])){

        	if(!empty($_POST['query']['output_subgroups'])&& $grpup_size>1){
        		
        	$show_comm=false;//普通表格显示
		      $show_subgroups='true';//以subgroup方式显示  
        	$this->set('show_subgroups', $show_subgroups);
        	$this->set('group_size', $grpup_size);//分组的个数
        	}
         
         }
		
         
         
				//********************************************************************************************************
			//           subtotals  分组子统计
			//********************************************************************************************************
				$grpup_size=count($group_by_field_arr);
	 if(isset($_POST['query']['output_subtotals'])){
        	if(!empty($_POST['query']['output_subtotals'])&& $grpup_size>1){
        
        	$show_subtotals='true';//以subgtotal方式显示 
        	  	$this->set('show_subtotals', $show_subtotals);
        	$this->set('group_size', $grpup_size);//分组的个数
        	}
         
         }
         
				
		$this->set ( "start", $start );
		$this->set ( "end", $end );
		$this->set ( "start_day", $start_day );
		$this->set ( "end_day", $end_day );
		$this->set ( 'post', $this->data );
		$this->set ( 'show_comm', $show_comm );
		//********************************************************************************************************
		//                                                                  基本sql
		//********************************************************************************************************
		
		

		$sql = "select $group_by_field sum(six_seconds::numeric)as total_six_seconds,sum(minutes::numeric) as total_minutes,
		sum(minutes::numeric)as total_bill_time,sum(cost::numeric)as total_cost
		   from   class4_view_egress_cost_client 
    where time  between   '$start'  and  '$end'  $card_where   $client_where   $reseller_where   	$code_name_where  $code_where   $server_where
     $currency_where   $egress_where   $rate_where $group_by_where";
		



		if ($login_type == 1) {
			if (empty ( $group_by_field )) {
				$org_sql = "select * from ($sql)cost";
			} else {
				
					$org_sql = $sql;

				
			}
			
		//分组查询
		

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

		if(isset($_POST ['query'] ['output'])){
			//下载
				if (	$_POST ['query'] ['output']== 'csv'){
						Configure::write('debug',0);
					//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
						$this->Cdr->export__sql_data('Export Egress Analysis ',$org_sql,'cdr');
	  
  	$this->layout='';

				}else{
				//web显示
						$org_list = $this->Cdr->query ( $org_sql );
    	pr ( $org_sql );
		$this->set ("client_org", $org_list);
		$this->set ( "group_by_field_arr", $group_by_field_arr );
				
				}
		
		}else{
			$org_list = $this->Cdr->query ( $org_sql );
    	pr ( $org_sql );
		$this->set ("client_org", $org_list);
		$this->set ( "group_by_field_arr", $group_by_field_arr );
				}
		

	
	}
	
	
}