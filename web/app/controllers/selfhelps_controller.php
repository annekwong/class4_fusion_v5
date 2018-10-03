<?php
class SelfhelpsController extends AppController {
	var $name = 'selfhelps';
	var $uses = array ();
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	/**
	 * 帐户信息
	 */
	public function account_info() {
	
		
		
		$this->loadModel ( 'Cdr' );
		$card_id = $this->Session->read ( 'card_id' );
		if ($this->params ['form']) {
			$f = $this->params ['form'];
			$qs = $this->Cdr->query ( "update card set name = '{$f['name']}',
															 phone_number='{$f['phone']}',
															 email='{$f['email']}',address='{$f['address']}',
															 company_name='{$f['company']}' 
															 where card_id = $card_id" );
			if (count ( $qs ) == 0)
				$this->Cdr->create_json_array ( '', 201, __ ( 'updatepersonalsuc', true ) );
			else
				$this->Cdr->create_json_array ( '', 101, __ ( 'updatepersonalfail', true ) );
			
			$this->set ( 'm', Cdr::set_validator () );
		}
		$info = $this->Cdr->query ( "select name,phone_number,email,address,company_name,
		(select balance from account_balance where account_id = '$card_id'
		order by account_balance_id desc limit 1) as balance,
		(select gift_amount_balance from account_balance where account_id = '$card_id'
							  order by account_balance_id desc limit 1) as gift_amount,
		(select point_balance from account_point where account_id ='$card_id'
		order by account_point_id desc limit 1) as points
		from card where card_id = $card_id" );
		$this->set ( 'info', $info );
		
	//	$value='something from somewhere';
	

	//setcookie("TestCookie2",$value);/* 简单cookie设置 */
	//setcookie("TestCookie2",$value,time()+3600);/* 有效期1个小时 */
	//setcookie("TestCookie2",$value,time()+3600,"/~rasmus/",".ex2ample.com",1);/* 有效目录 /~rasmus,有效域名example.com及其所有子域名 */
	//		
	//		pr($_COOKIE);
	

	}
	
	public function account_rate() {
		$this->loadModel ( 'Cdr' );
		$card_id = $this->Session->read ( 'card_id' );
		$ids = $this->Cdr->query ( "select rate_table_id,card_number from card where card_id = $card_id" );
		$this->redirect ( '/selfhelps/rateinfo/' . $ids [0] [0] ['rate_table_id'] );
	}
	public function rateinfo($table_id) {
		$this->loadModel ( 'Rate' );
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		
		if (! empty ( $_REQUEST ['page'] ))
			$currPage = $_REQUEST ['page'];
		
		if (! empty ( $_REQUEST ['size'] ))
			$pageSize = $_REQUEST ['size'];
		
		if (! empty ( $_REQUEST ['search'] )) {
			$search = $_REQUEST ['search'];
			$this->set ( 'search', $search );
		}
		
		$results = $this->Rate->getRates ( $currPage, $pageSize, $search, $table_id, '' );
		
		$this->set ( 'p', $results );
	}
	
	public function family_num() {
		Configure::write('debug',0);
		$this->loadModel ( 'Cdr' );
		$sid = $_REQUEST ['sid'];
		$num = $_REQUEST ['num'];
		
		if (empty($num)) {
			echo __('familynumnull',true)."|false";
			exit();
		}
		
		$acc = $this->Cdr->query("select card_id from card where card_number = '$num'");
		if (count($acc) == 0){
			echo __('accountnotexists',true)."|false";
			exit();
		}
		
		$card_id = $this->Session->read ( 'sst_reseller_id' );
		
		$resellers = $this->Cdr->query ( "select reseller_id from card where card_id = $card_id" );
		$rrr_id = 'null';
		if (count ( $resellers ) > 0)
			$rrr_id = $resellers [0] [0] ['reseller_id'];
		$qs = $this->Cdr->query ( "insert into card_service(card_id,service_id,result,reseller_id,request_time)
																 values($card_id,$sid,0,$rrr_id,current_timestamp(0))" );
		
		$this->Cdr->query("insert into card_family_num(card_id,family_num) values($card_id,'$num')");
		
		if (count ( $qs ) == 0)
			echo __('commandsuc',true)."|true";
		else
			echo __('commandfail',true)."|false";
	}
	
	private function check_balance_of_service ($card_id,$service_id) {
		$cost_sql = "select cost from value_add_service where service_id = $service_id ";
		$balance_sql = "select balance from account_balance where account_id = '$card_id' order by account_balance_id desc limit 1";
		$c = $this->query($cost_sql);
		$b = $this->query($balance_sql);
		
		if (empty($c) || $c[0] === false || $b[0] === false || $c[0][0]['cost'] > $b[0][0]['balance']) {
			return false;
		}
		return true;
	}
	
	public function account_service() {
		$this->loadModel ( 'Cdr' );
		$card_id = $this->Session->read ( 'card_id' );
		if (! empty ( $_REQUEST ['service'] )) { //申请或者退定
			$ser = $_REQUEST ['service'];
			$id = $_REQUEST ['s_id'];
			if ($ser == 1) { //申请
				
				if ($this->check_balance_of_service($card_id,$id) === false) {
					$this->Cdr->create_json_array('',101,__('noenoughbalance',true));
					$this->Session->write ( 'm', Cdr::set_validator () );
					$this->redirect ( '/selfhelps/account_service' );
				}
				
				$resellers = $this->Cdr->query ( "select reseller_id from card where card_id = $card_id" );
				$rrr_id = 'null';
				if (count ( $resellers ) > 0)
					$rrr_id = $resellers [0] [0] ['reseller_id'];
				$qs = $this->Cdr->query ( "insert into card_service(card_id,service_id,result,reseller_id,request_time)
																 values($card_id,$id,1,$rrr_id,current_timestamp(0))" );
				
				if (count ( $qs ) == 0)
					$this->Cdr->create_json_array ( '', 201, __ ( 'commandsuc', true ) );
				else
					$this->Cdr->create_json_array ( '', 101, __ ( 'commandfail', true ) );
			} else {
				$qs = $this->Cdr->query ( "delete from card_service where card_id = $card_id and service_id = $id" );
				
				if (count ( $qs ) == 0)
					$this->Cdr->create_json_array ( '', 201, __ ( 'cancelsuc', true ) );
				else
					$this->Cdr->create_json_array ( '', 101, __ ( 'cancelfail', true ) );
			}
			
			$this->Session->write ( 'm', Cdr::set_validator () );
			$this->redirect ( '/selfhelps/account_service' );
		}
		
		$no = $this->Cdr->query ( "select * from value_add_service where service_id not in (select service_id from card_service where  card_id = $card_id)" );
		$yes = $this->Cdr->query ( "select * from value_add_service where service_id in (select service_id from card_service where  card_id = $card_id and result = 1)" );
		$this->set ( 'no', $no );
		$this->set ( 'yes', $yes );
	}
	
	public function payment_list() {
		$this->loadModel ( 'Cdr' );
		$card_id = $this->Session->read ( 'card_id' );
		$currPage = empty ( $_REQUEST ['page'] ) ? 1 : $_REQUEST ['page'];
		$pageSize = empty ( $_REQUEST ['size'] ) ? 10 : $_REQUEST ['size'];
		
		$adv_search = '';
		//  
		if (! empty ( $this->params ['form'] )) {
			$last_conditions = '';
			$f = $this->params ['form'];
			
			if (! empty ( $f ['search_amount_s'] )) {
				$adv_search .= " and amount >= {$f['search_amount_s']}";
				$last_conditions .= "&search_amount_s={$f['search_amount_s']}";
			}
			
			if (! empty ( $f ['search_amount_e'] )) {
				$adv_search .= " and amount <= {$f['search_amount_e']}";
				$last_conditions .= "&search_amount_e={$f['search_amount_e']}";
			}
			
			if (! empty ( $f ['search_time_s'] )) {
				$adv_search .= " and payment_time >= '{$f['search_time_s']}'";
				$last_conditions .= "&search_time_s={$f['search_time_s']}";
			}
			
			if (! empty ( $f ['search_time_e'] )) {
				$adv_search .= " and payment_time <= '{$f['search_time_e']}'";
				$last_conditions .= "&search_time_e={$f['search_time_e']}";
			}
			
			if (! empty ( $f ['search_result'] )) {
				$adv_search .= " and result <= {$f['search_result']}";
				$last_conditions .= "&search_result={$f['search_result']}";
			}
			
			$this->set ( 'last_conditions', $last_conditions );
			$this->set ( 'searchForm', $f );
		}
		
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage ();
		
		$totalrecords = 0;
		
		$sql = "select count(account_payment_id) as c from account_payment where account_id = $card_id $adv_search";
		$totalrecords = $this->Cdr->query ( $sql );
		
		$page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
		$page->setCurrPage ( $currPage ); //当前页
		$page->setPageSize ( $pageSize ); //页大小
		

		$currPage = $page->getCurrPage () - 1;
		$pageSize = $page->getPageSize ();
		$offset = $currPage * $pageSize;
		
		$sql = "select account_payment_id,payment_time,amount,payment_method,result,cause,platform_trace from account_payment where account_id = $card_id $adv_search";
		
		$sql .= "order by account_payment_id desc limit '$pageSize' offset '$offset'";
		
		$results = $this->Cdr->query ( $sql );
		
		$page->setDataArray ( $results ); //Save Data into $page
		

		$this->set ( 'p', $page );
	}
	
	public function charge() {
		$this->loadModel ( 'Cdr' );
		if (! empty ( $this->params ['form'] )) {
			$charge_type = $this->params ['form'] ['charge_type'];
			//系统充值卡充值
			if ($charge_type == 1) { //充值卡充值
				$card_num = $this->params ['form'] ['card_num'];
				$card_pass = $this->params ['form'] ['card_pass'];
				$card = $this->Cdr->query ( "select credit_card_id,value,effective_date,expire_date,used_date from credit_card where card_number='$card_num' and pin = '$card_pass'" );
				if (count ( $card ) == 0) {
					$this->Cdr->create_json_array ( '', 101, __ ( 'cardnoexists', true ) );
				} else {
					if (empty ( $card [0] [0] ['effective_date'] )) {
						$this->Cdr->create_json_array ( '', 101, __ ( 'cardnoteffectived', true ) );
					} else if (! empty ( $card [0] [0] ['expire_date'] ) && strtotime ( $card [0] [0] ['expire_date'] ) < time () + 6 * 60 * 60) {
						$this->Cdr->create_json_array ( '', 101, __ ( 'cardexpired', true ) );
					} else if (! empty ( $card [0] [0] ['used_date'] )) {
						$this->Cdr->create_json_array ( '', 101, __ ( 'cardused', true ) );
					} else {
						$this->loadModel ( 'Refill' );
						$this->Session->write ('paymentmethod', "系统充值卡" );
						$rqs = $this->Refill->card_refill ( $card [0] [0] ['value'], 0 );
						if ($rqs==="toobig") {
							$this->Cdr->create_json_array ( '', 101, __ ( 'paymenttoobig', true ) );
							$this->Session->write ( 'm', Cdr::set_validator () );
							$this->redirect ( '/selfhelps/payment_list' );
							exit();
						} 
						if ($rqs === true){
							$this->Cdr->create_json_array ( '', 201, __ ( 'refillsuccess', true ) );
							$this->Cdr->query ( "update credit_card set used_date = current_timestamp(0) where credit_card_id = {$card[0][0]['credit_card_id']}" );
						}else {
							$this->Cdr->create_json_array ( '', 101, __ ( 'refillfail', true ) );
						}
					}
				}
			}
			$this->Session->write ( 'm', Cdr::set_validator () );
			$this->redirect ( '/selfhelps/payment_list' );
		} else {
			$status = $this->Cdr->query ( "select payment_platform_new_id  from payment_platform_new where status = true" );
			if (count ( $status ) == 0) {
				$this->set ( 'noplatform', true );
				exit ();
			}
			
			$yeepay = $this->Cdr->query ( "select status from payment_platform_new where code = 'yeepay'" );
			$bill = $this->Cdr->query ( "select status from payment_platform_new where code = '99bill'" );
			if (count ( $yeepay ) > 0) {
				$this->set ( 'yeepay', true );
				$qs = $this->Cdr->query ( "select payment_trace_id from payment_trace where payment_platform_new_id  = 
												(select payment_platform_new_id  from payment_platform_new where code = 'yeepay')
												and status = true and trace_type = 'SZX'" );
				if (count ( $qs ) > 0) {
					$this->set ( 'szx_yp', true );
				}
				
				$qs = $this->Cdr->query ( "select payment_trace_id from payment_trace where payment_platform_new_id  = 
												(select payment_platform_new_id  from payment_platform_new where code = 'yeepay')
												and status = true and trace_type = 'LT'" );
				if (count ( $qs ) > 0) {
					$this->set ( 'lt_yp', true );
				}
				
				$qs = $this->Cdr->query ( "select payment_trace_id from payment_trace where payment_platform_new_id  = 
												(select payment_platform_new_id  from payment_platform_new where code = 'yeepay')
												and status = true and trace_type = 'QB'" );
				if (count ( $qs ) > 0) {
					$this->set ( 'qb_yp', true );
				}
				
				$qs = $this->Cdr->query ( "select payment_trace_id from payment_trace where payment_platform_new_id  = 
(select payment_platform_new_id  from payment_platform_new where code = '99bill')
and status = true and trace_type = 'SZX'" );
				if (count ( $qs ) > 0) {
					$this->set ( 'szx_bl', true );
				}
				
				$qs = $this->Cdr->query ( "select payment_trace_id from payment_trace where payment_platform_new_id  = 
(select payment_platform_new_id  from payment_platform_new where code = '99bill')
and status = true and trace_type = 'DX'" );
				if (count ( $qs ) > 0) {
					$this->set ( 'dx_bl', true );
				}
				
				$qs = $this->Cdr->query ( "select payment_trace_id from payment_trace where payment_platform_new_id  = 
(select payment_platform_new_id  from payment_platform_new where code = '99bill')
and status = true and trace_type = 'LT'" );
				if (count ( $qs ) > 0) {
					$this->set ( 'lt_bl', true );
				}
			
			}
			
			if (count ( $bill ) > 0) {
				$this->set ( 'bill', true );
			}
		}
	}
	
	/**
	 * 国际业务状态
	 */
	public function international() {
		$this->loadModel ( 'Refill' );
		$card_id = $this->Session->read ( 'card_id' );
		$status = null;
		$cmded = $this->Refill->query ( "select * from international_request where account_id = $card_id" );
		if (count ( $cmded ) > 0) {
			$status = 1;
		} else {
			$internationals = $this->Refill->query ( "select international from card where card_id = $card_id" );
			$status = $internationals [0] [0] ['international'] == true ? 2 : 3;
		}
		$this->set ( 'status', $status );
		$this->Session->write ( 'm', Refill::set_validator () );
	}
	
	public function monthly_msg($command=null){
		$this->loadModel('Refill');
		$card_id = $this->Session->read('card_id');
		
		if (!empty($command)){
			$fee = $this->Refill->query("select msgmonthlyfee from system_parameter");
			$qs = $this->Refill->query("insert into card_sms_strategy(card_id,month_fee) values($card_id,{$fee[0][0]['msgmonthlyfee']})");
			if (count($qs) == 0){
				$this->Refill->create_json_array('',201,__('manipulated_suc',true));
			} else {
				$this->Refill->create_json_array('',101,__('manipulated_fail',true));
			}
			$this->set('m',Refill::set_validator());
		}
		
		$qs = $this->Refill->query("select card_sms_strategy_id from card_sms_strategy where card_id = $card_id");
		if (!empty($qs)){
			$this->set('cmd',true);
		}
	}
	/**
	 * 申请国际业务
	 */
	public function cmd_international() {
		$this->loadModel ( 'Refill' );
		$card_id = $this->Session->read ( 'card_id' );
		$qs = $this->Refill->query ( "update card set international = true where card_id = $card_id" );
		if (count ( $qs ) == 0) {
			$this->Refill->create_json_array ( '', 201, __ ( 'commandsuc', true ) );
		} else {
			$this->Refill->create_json_array ( '', 101, __ ( 'commandfail', true ) );
		}
		$this->Session->write ( 'm', Refill::set_validator () );
		$this->redirect ( '/selfhelps/international' );
	}
	
	public function send() {
		$this->loadModel ( 'Cdr' );
		//-------------充值信息------------
		
		if (!empty($_REQUEST['acc_post'])){
			$this->Session->write('card_id',$_REQUEST['acc_post']);
		}

		$cardtype = $_REQUEST ['cardtype']; //支付方式
		$amt = $_REQUEST ['amt']; //金额
		$cardno = ! empty ( $_REQUEST ['cardno'] ) ? $_REQUEST ['cardno'] : null; //卡号 (不是网银的时候需要)
		$cardpass = ! empty ( $_REQUEST ['cardpass'] ) ? $_REQUEST ['cardpass'] : null; //密码 (不是网银的时候需要)
		

		//-------------------------------
		

		//----------------帐户和密钥信息------------------
		

		$trace_info = $this->Cdr->query ( "select payment_platform_new_id,kq_account_id,kq_account_key,code
															from payment_trace
															where trace_type = '$cardtype' and status = true" );
		
		//----------------------------------------------
		

		//--------------验证是否有通-------------
		

		if (count ( $trace_info ) == 0) {
			$this->Cdr->create_json_array ( '', 101, __ ( 'notracefound', true ) );
			$this->Session->write ( 'm', Cdr::set_validator () );
			$this->redirect ( '/selfhelps/charge' );
		}
		
		//-------------------------------------
		

		//-------------提交的平台信息-----------------
		

		$platform_info = '';
		$method = "get";
		if ($cardtype != 'BANK') { //充值卡地址
			$platform_info = $this->Cdr->query ( "select  bg_url_of_card,code,szx_request_url as url from payment_platform_new
															where status = true and payment_platform_new_id = {$trace_info[0][0]['payment_platform_new_id']}" );
		} else { //网银地址
			$method = "post";
			$platform_info = $this->Cdr->query ( "select bg_url_of_bank,code,bank_request_url as url from payment_platform_new
															where payment_platform_new_id = {$trace_info[0][0]['payment_platform_new_id']}" );
		}
		//-------------------------------------------
		

		//--------------验证平台是否可用-------------
		

		if (count ( $platform_info ) == 0) {
			$this->Cdr->create_json_array ( '', 101, __ ( 'noplatformfound', true ) );
			$this->Session->write ( 'm', Cdr::set_validator () );
			$this->redirect ( '/selfhelps/charge' );
		}
		
		//-------------------------------------
		

		if ($cardtype == 'BANK') {
			$this->Session->write ( 'paymentmethod', "网银" );
		} else if ($cardtype == 'SZX') {
			$this->Session->write ( 'paymentmethod', "神州行" );
		} else if ($cardtype == 'LT') {
			$this->Session->write ( 'paymentmethod', "联通" );
		} else if ($cardtype == 'QB') {
			$this->Session->write ( 'paymentmethod', "Q币" );
		}
		
		//------------输出信息到页面并提交到充值平台------------
		$output = "<html>
											<head>
		 											<meta content=\"text/html; charset=utf-8\" http-equiv=\"content-type\">
											</head>
											<body>";
		
		$platform = strtolower ( $platform_info [0] [0] ['code'] );
		if ($platform == 'yeepay') { //易宝
			$frp_id = "";
			if ($cardtype == 'SZX') {
				$frp_id = "SZX";
			} else if ($cardtype == 'LT') {
				$frp_id = "UNICOM";
			} else if ($cardtype == 'QB') {
				$frp_id = "QQCARD";
			}
			$msg = '';
			$t = time ();
			if ($cardtype != 'BANK') {
				//$msg = $this->yeepay ( $trace_info [0] [0] ['kq_account_id'], $trace_info [0] [0] ['kq_account_key'], $amt, $platform_info [0] [0] ['bg_url_of_card'], $frp_id,$cardno,$cardpass );
				$msg = "<form style='display:none;' method='POST' id='submitform' action='/exchange/yeepays/req'>
<table class='form'>
<tr>
	<td><input size='50' type='hidden' name='p2_Order' value='$t' /></td>
</tr>

<tr>
	<td class='label label2'>充值金额</td>
	<td class='value value2'><input type='text' name='p3_Amt' value='$amt' /></td>
</tr>
<tr><td>
<input type='hidden' name='p4_verifyAmt' value='true' checked='checked' />
</td>
</tr>
<tr>
	<td>
		<input size='50' type='hidden' name='p5_Pid' value='产品名称' /></td><td><input size='50' type='hidden' name='p6_Pcat' value='产品类型' />
	</td>
	<td><input size='50' type='hidden' name='p7_Pdesc' value='产品描述' /></td>
	<td><input size='50' type='hidden' name='p8_Url' value='http://192.168.1.106/exchange/yeepays/callback' /></td>
	<td><input size='50' type='hidden' name='pa_MP' value='临时信息' /></td>
</tr>
<tr><td class='label label2'>卡面额</td><td class='value value2'>
<input  type='text' name='pa7_cardAmt[]' value='500'/>
<input size='20' type='hidden' name='pa7_cardAmt[]'/>
<input size='20' type='hidden' name='pa7_cardAmt[]'/>
<input size='20' type='hidden' name='pa7_cardAmt[]'/>
</td></tr>
<tr><td class='label label2'>卡序列号</td><td class='value value2'>
<input  type='text' name='pa8_cardNo[]' value='$cardno'/>
<input size='20' type='hidden' name='pa8_cardNo[]'/>
<input size='20' type='hidden' name='pa8_cardNo[]'/>
<input size='20' type='hidden' name='pa8_cardNo[]'/>
</td></tr>
<tr><td class='label label2'>卡密码</td><td class='value value2'>
<input  type='text' name='pa9_cardPwd[]' value='$cardpass'/>
<input size='20' type='hidden' name='pa9_cardPwd[]'/>
<input size='20' type='hidden' name='pa9_cardPwd[]'/>
<input size='20' type='hidden' name='pa9_cardPwd[]'/>
</td></tr>
<tr><td>
<input type='hidden' name='pr_NeedResponse' value='1' checked='checked' />
</td></tr>
<tr>
  <td><input size='50' type='hidden' name='pz_userId' value='100001' /></td>
</tr>
<tr><td><input size='50' type='hidden' name='pz1_userRegTime' value='2009-01-01 00:00:00' /></td></tr>
</tr>
<tr><td><input type='hidden' name='pd_FrpId' value='$frp_id'/></td></tr>
</table>
<script>document.getElementById('submitform').submit();</script>
</form>";
			} else {
				$output .= "<form name='submitform' id='submitform' action='{$platform_info[0][0]['url']}' method='$method'>";
				$msg = $this->yeepay ( $trace_info [0] [0] ['kq_account_id'], $trace_info [0] [0] ['kq_account_key'], $amt, $platform_info [0] [0] ['bg_url_of_bank'], $frp_id, $cardno, $cardpass );
			}
		} else if ($platform == '99bill') { //快钱
			$output .= "<form name='submitform' id='submitform' action='{$platform_info[0][0]['url']}' method='$method'>";
			$msg = '';
			if ($cardtype != 'BANK') {
				$msg = $this->bill99 ( $trace_info [0] [0] ['kq_account_id'], $trace_info [0] [0] ['kq_account_key'], $amt, $cardtype, $platform_info [0] [0] ['bg_url_of_card'], $cardno, $cardpass );
			} else {
				$msg = $this->bill99_bank ( $trace_info [0] [0] ['kq_account_id'], $trace_info [0] [0] ['kq_account_key'], $amt, $platform_info [0] [0] ['bg_url_of_bank'] );
			}
		}
		
		$output .= $msg;
		$output .= "</body></html>";
		$this->set ( 'output', $output );
		//------------------------------------------------
		
		if (!empty($_REQUEST['admin'])){
			$this->Session->write('admin_c',true);
		}
	}
	
	/**
	 * 易宝支付
	 * @param int $account_id
	 * @param String $account_key
	 * @param int $amt
	 * @param String $bgurl
	 * @param String $cardtype
	 */
	private function yeepay($account_id, $account_key, $amt, $bgurl, $cardtype, $cardno, $cardpass) {
		$this->loadModel ( 'Refill' );
		return $this->Refill->yeepay_interface ( $account_id, $account_key, $amt, $bgurl, $cardtype, $cardno, $cardpass );
	}
	/**
	 * 快钱网银支付
	 * @param int $account_id 帐户
	 * @param String $account_key 密钥
	 * @param int $amt 充值金额
	 * @param String $bgurl //后台处理地址
	 */
	private function bill99_bank($account_id, $account_key, $amt, $bgurl) {
		$this->loadModel ( 'Refill' );
		return $this->Refill->bill99OfBank ( $account_id, $account_key, $amt, $bgurl );
	}
	
	/**
	 * 提交到快钱
	 * @param int $account_id 帐户
	 * @param String $account_key  密钥
	 * @param int $amt  支付金额
	 * @param String $cardtype 支付类型
	 */
	private function bill99($account_id, $account_key, $amt, $cardtype, $bgurl, $cardn, $cardp) {
		$this->loadModel ( 'Refill' );
		return $this->Refill->bill99OfCard ( $account_id, $account_key, $amt, $cardtype, $bgurl, $cardn, $cardp );
	}
	
	public function payment_result($request_platform) {
		$this->loadModel ( 'Refill' );
		if (strtolower ( $request_platform ) == '99bill_bank') { //快钱网银支付
			$result = $this->Refill->payment_result_99bill_of_bank ();
			$this->set ( 'rtnOk', $result [0] );
			$this->set ( 'rtnUrl', $result [1] );
		} else if (strtolower ( $request_platform ) == '99bill_card') { //快钱充值卡支付
			$result = $this->Refill->payment_result_99bill_of_card ();
			$this->set ( 'rtnOk', $result [0] );
			$this->set ( 'rtnUrl', $result [1] );
		} else if (strtolower ( $request_platform ) == 'yeepay') { //易宝支付
			if ($this->Refill->yeepay_result ()) {
				$amt = $_REQUEST ['r3_Amt'];
				$this->Refill->card_refill ( $amt, 3 );
				$this->Refill->create_json_array ( '', 201, __ ( 'refillsuccess', true ) );
			} else {
				$amt = $_REQUEST ['r3_Amt'];
				$this->Refill->refill_fail ( $amt, 3 );
				$this->Refill->create_json_array ( '', 101, __ ( 'refillfail', true ) );
			}
			$this->Session->write ( 'm', Refill::set_validator () );
			
			$admin_c = $this->Session->read("admin_c");
			if (!empty($admin_c)){
				$this->Session->write('admin_c','');
				$this->redirect ( '/resellerpayments/payment_list' );
			} else {
				$this->redirect ( '/selfhelps/payment_list' );
			}
		}
	}
	
	public function result() {
	}
	
	/**
	 * 发送短消息
	 */
	public function msg() {
		$this->loadModel ( 'Refill' );
		$interface_url = $this->Refill->query ( "select url from msg_interface where status = true" );
		if (count ( $interface_url ) > 0) {
			$this->set ( 'msg_url', $interface_url [0] [0] ['url'] );
		}
		//检查该帐户所属于的代理商余额(代理商为预付的时候)
		$sst_card_id = $this->Session->read ( 'sst_card_id' );
		$parent = $this->Refill->query ( "select mode,type,reseller_id from reseller where reseller_id = (select reseller_id from card where card_id = '$sst_card_id')" );
		if ($parent [0] [0] ['type'] == 1 && $parent [0] [0] ['mode'] == 1) {
			$reseller_balance = $this->Refill->query ( "select balance from reseller_balance where reseller_id = '{$parent[0][0]['reseller_id']}' order by reseller_balance_id desc limit 1" );
			if (count ( $reseller_balance ) == 0 || $reseller_balance [0] [0] ['balance'] == 0) {
				$this->set ( 'nomsg', true );
			}
		}
	}
	
	/**
	 * 检查余额是否足够
	 */
	public function check_balance() {
		Configure::write ( 'debug', 0 );
		$nums = $_REQUEST ['nums'];
	//	pr($nums);
		$content = $_REQUEST ['content'];
		$msg_type = $_REQUEST ['msg_type'];
		$account_id = $this->Session->read ( 'card_id' );
		$this->loadModel ( 'Refill' );
		if ($this->Refill->check_balance ( $msg_type, $nums, $content, $account_id )) {
			
			$cost_rs = $this->Refill->msg_cost ( $msg_type, $nums, $content, $account_id );
			if ($cost_rs != false) {
				echo $cost_rs;
			} else {
				echo 'false';
			}
		} else {
			echo 'false';
		}
	}
	
	public function scoretoamount(){
		$this->loadModel('Refill');
		$account_id = $this->Session->read('card_id');
		$points = $this->Refill->query("select point_balance from account_point where account_id = $account_id order by account_point_id desc limit 1");
		
		if (!empty($this->params['form'])){
			$f = $this->params['form'];
			$point_info = $this->Refill->query("select bonus_credit,gift_amount from sales_strategy_points
				where sales_strategy_points_id = '{$f['point']}'");
			
			$now_point =  $points[0][0]['point_balance'] - $point_info[0][0]['bonus_credit'];
			$this->Refill->begin();
			$qs = $this->Refill->query("insert into account_point(account_id,point_balance,create_time,point_type)
			values('$account_id',$now_point,current_timestamp(0),2)");
			
			$sql_now = "select balance,gift_amount_balance from account_balance where account_id = '$account_id' order by account_balance_id desc limit 1";
			$now_balance = $this->Refill->query($sql_now);
			
			$b = empty($now_balance[0][0]['balance'])?0:$now_balance[0][0]['balance'];
			$g = empty($now_balance[0][0]['gift_amount_balance'])?0:$now_balance[0][0]['gift_amount_balance'];
			$g += $point_info[0][0]['gift_amount'];
			$insert_sql = "insert into account_balance (account_id,balance,cost_type,create_time,gift_amount_balance)
								values('$account_id','$b','4',current_timestamp(0),'$g')";
			
			
			
			$qs1 = $this->Refill->query($insert_sql);
			
			if (count($qs) == 0 && count($qs1) == 0){
				$this->Refill->commit();
				$this->Refill->create_json_array('',201,__('transfersuc',true));
			} else {
				$this->Refill->rollback();
				$this->Refill->create_json_array('',101,__('transferfail',true));
			}
			$this->Session->write('m',Refill::set_validator());
			$this->redirect('/selfhelps/scoretoamount');
		}
		$sales = $this->Refill->query("select sales_strategy_id from card where card_id = $account_id");
		if (!empty($sales)){
			if (!empty($points)){
				$strategys = $this->Refill->query("select sales_strategy_points_id,bonus_credit,gift_amount from sales_strategy_points
				where sales_strategy_id = '{$sales[0][0]['sales_strategy_id']}'
				and bonus_credit <= '{$points[0][0]['point_balance']}'");
				if (!empty($strategys)){
					$this->set('points',$points[0][0]['point_balance']);
					$this->set('strategys',$strategys);
				}
			}
		}
	}
}