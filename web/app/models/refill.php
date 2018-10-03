<?php
class Refill extends AppModel {
	var $name = 'Refill';
	var $useTable = 'client_payment';
	var $primaryKey = 'client_payment_id';

	public function card_refill($amount, $method,$refill_type=1) {
		$qs_count = 0;
		$balance = $amount;//充值金额
		$card_id = $_SESSION ['card_id'];
		$sales_policy = null;
		if (empty ( $card_id )) {
			$cardn = $_REQUEST ['acc'];
			$qs_card = $this->query ( "select card_id from card where card_number = '$cardn'" );
			$card_id = $qs_card [0] [0] ['card_id'];
		}
		//判断该客户是不是首次充值
		$pays = $this->query ( "select account_payment_id from account_payment where account_id = '$card_id'" );
		//首次充值
		if (count ( $pays ) == 0) {
			//首次充值最大余额     如果超出就不可以 充值
			$max = $this->query ( "select first_payment_max  from card where card_id = '$card_id'" );
			if (! empty ( $max [0] [0] ['first_payment_max'] )) {
				if ($amount > $max [0] [0] ['first_payment_max']) {
					return "toobig";
					exit();
				}
			}
			//帐户首次充值,如果该帐户是被推荐的,
			//按照推荐策略赠送推荐帐户基础金或其他
			$rec_accounts = $this->query("select  account_id from recommend_record where recommend_number = (select number from card where card_id = $card_id) order by recommend_time asc limit 1");
			if (!empty($rec_accounts)){
				$this->calc_recommend($rec_accounts[0][0]['account_id'],2,$amount);
			}
		}
		
		$resids = $this->query ( "select reseller_id from card where card_id = '$card_id'" );
		$this->begin ();
		
		//插入一条充值记录
		$paymentmethod = $_SESSION ['paymentmethod'];
		$qs = $this->query ( "insert into account_payment(payment_time,amount,result,account_id,reseller_id,payment_method,platform_trace,refill_type)
												 values(current_timestamp(0),$amount,true,'$card_id',{$resids[0][0]['reseller_id']},'$method','$paymentmethod','$refill_type')" );
		$qs_count += count ( $qs );
		
		$last_payment = $this->query ( "select max(account_payment_id) as lp from account_payment where account_id = '$card_id'" );
		
		$filters = $this->query ( "select filter_num_of_charge from reseller where reseller_id = {$resids[0][0]['reseller_id']}" );
		
		if (! empty ( $filters [0] [0] ['filter_num_of_charge'] )) {
			$filter = explode ( ',', $filters [0] [0] ['filter_num_of_charge'] );
			
			for($i = 0; $i < count ( $filter ); $i ++) {
				if (substr ( $last_payment [0] [0] ['lp'], - 1, strlen ( $filter [$i] ) ) == $filter [$i]) {
					$qs = $this->query ( "update account_payment set visible = false where account_payment_id = '{$last_payment[0][0]['lp']}'" );
					$qs_count += count ( $qs );
					break;
				}
			}
		}
		
		$calc_sales_strategy = true;
		
		//查看是否有充值策略
		$sales = $this->query ( "select sales_strategy_id,first_sales_strategy from card where card_id = '$card_id'" );
		if (! empty ( $sales )) {
			$sales_policy = null;
			$sales_strategy_id = $sales [0] [0] ['sales_strategy_id'];
			if (count ( $pays ) == 0) {
				//采用首次充值策略
				$sales_strategy_id = $sales [0] [0] ['first_sales_strategy']; 
			
			}
			if (empty($sales_strategy_id)) {
				$calc_sales_strategy = false;
			} else {
			$sales_policy = $this->query ( "select * from sales_strategy where start_time <= current_timestamp(0) and (end_time >= current_timestamp(0) or end_time is null) and sales_strategy_id = '$sales_strategy_id'" );
			}
//			//充值策略已过期
//			if ($sales_policy [0] [0] ['end_time'] <= time () + 6 * 60 * 60) {
//				$calc_sales_strategy = false;
//			}
			
			//$new_or_old_account = $this->query ( "select account_payment_id from account_payment where account_id = '$card_id'" );
			
			//新客户
			if (count ( $pays ) == 0) {
				//判断卡是否在首次充值优惠有效天数内
				$eee = $this->query ( "select effective_date::date - current_timestamp(0)::date<charge_effective_days as e from card where card_id = $card_id" );
				if (count ( $eee ) == 0) {
					$calc_sales_strategy = false;
				} else {
					if ($eee [0] [0] ['e'] == false) {
						$calc_sales_strategy = false;
					}
				}
			}
				if (count($pays) == 0) {
		//检查首次充值有效天数
			$first_expired = $this->query("select current_timestamp(0)::date-effective_date::date>charge_effective_days as first_expired from card where card_id = $card_id");
			if (empty($first_expired) || $first_expired[0][0]['first_expired']==true){
				if ($qs_count == 0) {
					//$this->commit();
					//return true;
					$calc_sales_strategy = false;
				} 
			}
		}
			if ($calc_sales_strategy == true) {
				$gift_amounts = $this->query ( "select refill_amount,gift_amount,basic_amount,bonus_credit from sales_strategy_charges
												 where refill_amount <= '$amount' 
												 and sales_strategy_id = '$sales_strategy_id'
												 order by refill_amount desc limit 1" );
				if (count ( $gift_amounts ) > 0) {
					$balance += $gift_amounts [0] [0] ['basic_amount'];
					$rewards = $this->query ( "select reward_type from sales_strategy where sales_strategy_id = '$sales_strategy_id'" );
					//如果是累计计算  则需要将充值金额>充值策略的部分累加
					if ($rewards [0] [0] ['reward_type'] == 2) {
						$r = $amount - $gift_amounts [0] [0] ['refill_amount'];
						if ($r > 0) {
							$dd = $this->query ( "select gift_balance from card where card_id = '$card_id'" );
							if (! empty ( $dd [0] [0] ['gift_balance'] ))
								$qs = $this->query ( "update card set gift_balance = case when gift_balance is null then 0 + $r end where card_id = '$card_id'" );
							else
								$qs = $this->query ( "update card set gift_balance = $r where card_id = '$card_id'" );
							$qs_count += count ( $qs );
						}
					}
				}
				//计算充值累计余额是否达到送话费的金额
				//如果达到了  则再计算一次本次充值该送的话费
				$calc_gifts = $this->query ( "select gift_balance from card where card_id = '$card_id'" );
				$calc_gift = $calc_gifts [0] [0] ['gift_balance'];
				if ($calc_gift > 0) {
					$gift_amounts = $this->query ( "select refill_amount,bonus_credit,gift_amount,basic_amount from sales_strategy_charges
												 where refill_amount <= '$calc_gift' 
												 and sales_strategy_id = '$sales_strategy_id'
												 order by refill_amount desc limit 1" );
					
					if (count ( $gift_amounts ) > 0) {
						$balance += $gift_amounts [0] [0] ['basic_amount'];
						$r = $calc_gift - $gift_amounts [0] [0] ['refill_amount'];
						$qs = $this->query ( "update card set gift_balance = $r where card_id = '$card_id'" );
						$qs_count += count ( $qs );
					}
				}
			}
		}
		
		//将余额记录插入到数据库中
		$ggg = empty ( $gift_amounts [0] [0] ['gift_amount'] ) ? 0 : $gift_amounts [0] [0] ['gift_amount'];
		$now_balances = $this->query ( "select balance,gift_amount_balance from account_balance where account_id = '$card_id' order by account_balance_id desc limit 1" );
		if (! empty ( $now_balances [0] [0] ['balance'] )) {
			$balance += $now_balances [0] [0] ['balance'];
		}
		
		if (! empty ( $now_balances [0] [0] ['gift_amount_balance'] )) {
			$ggg += $now_balances [0] [0] ['gift_amount_balance'];
		}
		
		$payment_ids = $this->query ( "select account_payment_id from account_payment where account_id = '$card_id' order by account_payment_id desc limit 1" );
		$qs = $this->query ( "insert into account_balance 
												(account_id,balance,account_payment_id,cost_type,gift_amount_balance,cost)
													values('$card_id','$balance','{$payment_ids[0][0]['account_payment_id']}','4','$ggg','$amount')" );
		$qs_count += count ( $qs );
		
		
		$n_p = 0;
		$bonus = $this->query("select * from bonus_strategy where bonus_strategy_id = (select bonus_strategy_id from card where card_id = $card_id)");
		if (!empty($bonus)) {
			$old_points = $this->query ("select point_balance from account_point where account_id = '$card_id' order by account_point_id desc limit 1" );
			$add_points = floor($amount/$bonus[0][0]['refill_amount']*$bonus[0][0]['gift_points']);
			$n_p = empty($old_points[0][0]['point_balance'])?$add_points:$old_points[0][0]['point_balance'] + $add_points;
			//$qs = $this->query ( "insert into account_point (account_id,payment_id,point_balance)
				//								values('$card_id','{$payment_ids[0][0]['account_payment_id']}','$n_p')" );
			//$qs_count += count ( $qs );
		}
		
		if ($calc_sales_strategy == true) {
			//累加积分
//			$ggg = empty ( $gift_amounts [0] [0] ['gift_amount'] ) ? 0 : $gift_amounts [0] [0] ['gift_amount'];
//			$qs = $this->query ( "update card set gift_amount = case when gift_amount is null then 0 +{$ggg} end where card_id = '$card_id'" );
//			$qs_count += count ( $qs );
			
			$point = '0';
			$old_points = $this->query ( "select point_balance from account_point where account_id = '$card_id' order by account_point_id desc limit 1" );
			if (count ( $old_points ) > 0) {
				$point = $old_points [0] [0] ['point_balance'];
			}
			
			if (! empty ( $sales_strategy_id )) {
				$gift_amounts = $this->query ( "select refill_amount,gift_amount,basic_amount,bonus_credit from sales_strategy_charges
												 where refill_amount <= '$amount' 
												 and sales_strategy_id = '$sales_strategy_id'
												 order by refill_amount desc limit 1" );
				
				//$point += $gift_amounts [0] [0] ['bonus_credit'];
				$point +=$n_p+$gift_amounts [0] [0] ['bonus_credit'];
				$qs = $this->query ( "insert into account_point (account_id,payment_id,point_balance)
												values('$card_id','{$payment_ids[0][0]['account_payment_id']}','$point')" );
				$qs_count += count ( $qs );
			}
			
			//延长帐户有效期限
			if (! empty ( $sales_policy [0] [0] ['extended_days'] )) {
				$expire_date = $this->query ( "select expire_date from card where card_id = '$card_id'" );
				if (empty ( $expire_date )) {
					$expire_date = array(array('expire_date'=>date ( 'Y-m-d H:i:s', time () + 6 * 60 * 60 )));
				}
				$tmp_time = (strtotime ( $expire_date [0] [0] ['expire_date'] ) + 6 * 60 * 60) + ($sales_policy [0] [0] ['extended_days'] * 24 * 60 * 60);
				$expire_date = date ( 'Y-m-d H:i:s', $tmp_time );
				$qs = $this->query ( "update card set expire_date = '$expire_date' where card_id = '$card_id'" );
				$qs_count += count ( $qs );
			}
			
			//更改费率模板为充值后设定的费率模板
			if (! empty ( $sales_policy [0] [0] ['new_rate_table_id'] )) {
				$qs = $this->query ( "update card set rate_table_id = {$sales_policy[0][0]['new_rate_table_id']} where card_id = $card_id" );
				$qs_count += count ( $qs );
			}
		}
		if ($qs_count == 0) {
			$this->commit ();
			return true;
		} else {
			$this->rollback ();
			return false;
		}
	}
	
	/**
	 * 代理商充值
	 * @param numeric $amount
	 */
	public function reseller_refill($amount, $reseller_id,$payment_method,$is_approved) {
		$qs_count = 0;
		$this->begin ();
		$qs = $this->query ( "insert into reseller_payment 
											(payment_time,amount,result,reseller_id,payment_method,approved)
											values(current_timestamp(0),$amount,true,$reseller_id,$payment_method,$is_approved)" );
		
		$qs_count += count ( $qs );
		
		if ($is_approved == 'true') {
			$reseller_payment_ids = $this->query ( "select reseller_payment_id from reseller_payment where reseller_id = $reseller_id order by reseller_payment_id desc limit 1" );
			$reseller_payment_id = $reseller_payment_ids [0] [0] ['reseller_payment_id'];
			$balance = $amount;
			
			$now_balances = $this->query ( "select balance from reseller_balance where reseller_id = '$reseller_id' order by reseller_balance_id desc limit 1" );
			
			$balance += $now_balances [0] [0] ['balance'];
			$qs = $this->query ( "insert into reseller_balance 
												(reseller_id,balance,reseller_payment_id)
													values($reseller_id,$balance,$reseller_payment_id)" );
			$qs_count += count ( $qs );
		}
		if ($qs_count == 0) {
			$this->commit ();
			return true;
		} else {
			$this->rollback ();
			return false;
		}
	}
	
	/**
	 * 客户充值
	 * @param numeric $amount
	 */
	public function client_refill($amount, $client_id,$payment_method,$is_approved) {
		$qs_count = 0;
		$list=$this->query("select  balance  from   c4_client_balance  where client_id='$client_id'  ");
		$current_balance=empty($list[0][0]['balance'])?'null':$list[0][0]['balance'];
		$this->begin ();
		$is_approved=!empty($is_approved)?'true':'false';
		$qs = $this->query ( "insert into client_payment 
											(payment_time,amount,result,client_id,payment_method,approved,current_balance,payment_type)
											values(current_timestamp(0),$amount,true,'$client_id',2,$is_approved,$current_balance,1)" );
		$qs_count += count ( $qs );
		if ($is_approved == 'true') {
				$balance = $amount;
				$result = $this->clientBalanceOperation($client_id, $balance, 2, true);
				$qs_count += $result ? 1 : 0;
		}
		if ($qs_count == 0) {
			$this->commit ();
			return true;
		} else {
			$this->rollback ();
			return false;
		}
	}
	
	function x_client_refill($data)
	{
		$array_clients=Array();
		try{
			$this->begin();
			foreach($data as $d)
			{
				if(empty($d['amount']))
				{
					continue;
				}
				$client_id=$d['client_id'];

				$this->clientBalanceOperation($d['client_id'], $d['amount'], 2);

				$d['result']='true';
				$this->x_save($d);
				$list=null;
				$this->id=false;
			}
			$this->commit();
			return true;
		}catch(Exception $e){
			$this->rollback();
			return false;
		}
	}
	
	
	/**
	 * 快钱网银支付
	 * @param int $account_id 帐户
	 * @param String $account_key 密钥
	 * @param int $amt 充值金额
	 * @param String $bgurl //后台处理地址
	 */
	public function bill99OfBank($account_id, $account_key, $amt, $bgurl) {
		$merchantAcctId = $account_id;
		$key = $account_key;
		$inputCharset = "3";
		$bgUrl = $bgurl;
		$pageUrl = $bgurl;
		$version = "v2.0";
		$language = "1";
		$signType = "1";
		$payerName = $_SESSION ['sst_user_name'];
		$payerContactType = "1";
		$payerContact = "";
		$orderId = date ( 'YmdHis' );
		$orderAmount = $amt * 100;
		$orderTime = date ( 'YmdHis' );
		$productName = "Payment";
		$productNum = "1";
		$productId = "";
		$productDesc = "";
		$ext1 = "";
		$ext2 = "";
		$payType = "10";
		$redoFlag = "1";
		$pid = "";
		$signMsgVal = "";
		$signMsgVal = $this->appendParam ( $signMsgVal, "inputCharset", $inputCharset );
		$signMsgVal = $this->appendParam ( $signMsgVal, "bgUrl", $bgUrl );
		$signMsgVal = $this->appendParam ( $signMsgVal, "version", $version );
		$signMsgVal = $this->appendParam ( $signMsgVal, "language", $language );
		$signMsgVal = $this->appendParam ( $signMsgVal, "signType", $signType );
		$signMsgVal = $this->appendParam ( $signMsgVal, "merchantAcctId", $merchantAcctId );
		$signMsgVal = $this->appendParam ( $signMsgVal, "payerName", $payerName );
		$signMsgVal = $this->appendParam ( $signMsgVal, "payerContactType", $payerContactType );
		$signMsgVal = $this->appendParam ( $signMsgVal, "payerContact", $payerContact );
		$signMsgVal = $this->appendParam ( $signMsgVal, "orderId", $orderId );
		$signMsgVal = $this->appendParam ( $signMsgVal, "orderAmount", $orderAmount );
		$signMsgVal = $this->appendParam ( $signMsgVal, "orderTime", $orderTime );
		$signMsgVal = $this->appendParam ( $signMsgVal, "productName", $productName );
		$signMsgVal = $this->appendParam ( $signMsgVal, "productNum", $productNum );
		$signMsgVal = $this->appendParam ( $signMsgVal, "productId", $productId );
		$signMsgVal = $this->appendParam ( $signMsgVal, "productDesc", $productDesc );
		$signMsgVal = $this->appendParam ( $signMsgVal, "ext1", $ext1 );
		$signMsgVal = $this->appendParam ( $signMsgVal, "ext2", $ext2 );
		$signMsgVal = $this->appendParam ( $signMsgVal, "payType", $payType );
		$signMsgVal = $this->appendParam ( $signMsgVal, "redoFlag", $redoFlag );
		$signMsgVal = $this->appendParam ( $signMsgVal, "pid", $pid );
		$signMsgVal = $this->appendParam ( $signMsgVal, "key", $key );
		$signMsg = strtoupper ( md5 ( $signMsgVal ) );
		
		$msg = "<input type='hidden' name='inputCharset' value='$inputCharset'/>
			<input type='hidden' name='bgUrl' value='$bgUrl'/>
			<input type='hidden' name='version' value='$version'/>
			<input type='hidden' name='language' value='$language'/>
			<input type='hidden' name='signType' value='$signType'/>
			<input type='hidden' name='signMsg' value='$signMsg'/>
			<input type='hidden' name='merchantAcctId' value='$merchantAcctId'/>
			<input type='hidden' name='payerName' value='$payerName'/>
			<input type='hidden' name='payerContactType' value='$payerContactType'/>
			<input type='hidden' name='payerContact' value='$payerContact'/>
			<input type='hidden' name='orderId' value='$orderId'/>
			<input type='hidden' name='orderAmount' value='$orderAmount'/>
			<input type='hidden' name='orderTime' value='$orderTime'/>
			<input type='hidden' name='productName' value='$productName'/>
			<input type='hidden' name='productNum' value='$productNum'/>
			<input type='hidden' name='productId' value='$productId'/>
			<input type='hidden' name='productDesc' value='$productDesc'/>
			<input type='hidden' name='ext1' value='$ext1'/>
			<input type='hidden' name='ext2' value='$ext2'/>
			<input type='hidden' name='payType' value='$payType'/>
			<input type='hidden' name='redoFlag' value='$redoFlag'/>
			<input type='hidden' name='pid' value='$pid'/>
				<script>document.getElementById('submitform').submit();</script>";
		return $msg;
	}
	
	public function bill99OfCard($account_id, $account_key, $amt, $cardtype, $bgurl, $cardn, $cardp) {
		
		$merchantAcctId = $account_id;
		
		$key = $account_key;
		
		$inputCharset = "1";
		
		$bgUrl = $bgurl;
		
		$pageUrl = $bgurl;
		
		$version = "v2.0";
		
		$language = "1";
		
		$signType = "1";
		
		$payerName = $_SESSION ['sst_user_name'];
		
		$payerContactType = "";
		
		$payerContact = "";
		
		$orderId = date ( 'YmdHis' );
		
		$orderAmount = $amt * 100;
		
		$payType = "52";
		$cardNumber = $cardn;
		$cardPwd = $cardp;
		
		$fullAmountFlag = "0";
		
		$orderTime = date ( 'YmdHis' );
		
		$productName = "Payment";
		
		$productNum = "1";
		
		$productId = "";
		
		$productDesc = "";
		
		$ext1 = "";
		
		$ext2 = "";
		
		$bossType = "0";
		
		if ($cardtype == 'LT') {
			$bossType = "1";
		} else if ($cardtype == 'DX') {
			$bossType = "3";
		} else {
			$bossType = "9";
		}
		///请务必按照如下顺序和规则组成加密串！
		$signMsgVal = "";
		$signMsgVal = $this->appendParam ( $signMsgVal, "inputCharset", $inputCharset );
		$signMsgVal = $this->appendParam ( $signMsgVal, "bgUrl", $bgUrl );
		//	$signMsgVal = $this->appendParam ( $signMsgVal, "pageUrl", $pageUrl );
		$signMsgVal = $this->appendParam ( $signMsgVal, "version", $version );
		$signMsgVal = $this->appendParam ( $signMsgVal, "language", $language );
		$signMsgVal = $this->appendParam ( $signMsgVal, "signType", $signType );
		
		$signMsgVal = $this->appendParam ( $signMsgVal, "merchantAcctId", $merchantAcctId );
		$signMsgVal = $this->appendParam ( $signMsgVal, "payerName", urlencode ( $payerName ) );
		$signMsgVal = $this->appendParam ( $signMsgVal, "payerContactType", $payerContactType );
		$signMsgVal = $this->appendParam ( $signMsgVal, "payerContact", $payerContact );
		
		$signMsgVal = $this->appendParam ( $signMsgVal, "orderId", $orderId );
		$signMsgVal = $this->appendParam ( $signMsgVal, "orderAmount", $orderAmount );
		$signMsgVal = $this->appendParam ( $signMsgVal, "payType", $payType );
		$signMsgVal = $this->appendParam ( $signMsgVal, "cardNumber", $cardNumber );
		$signMsgVal = $this->appendParam ( $signMsgVal, "cardPwd", $cardPwd );
		$signMsgVal = $this->appendParam ( $signMsgVal, "fullAmountFlag", $fullAmountFlag );
		$signMsgVal = $this->appendParam ( $signMsgVal, "orderTime", $orderTime );
		$signMsgVal = $this->appendParam ( $signMsgVal, "productName", urlencode ( $productName ) );
		$signMsgVal = $this->appendParam ( $signMsgVal, "productNum", $productNum );
		$signMsgVal = $this->appendParam ( $signMsgVal, "productId", $productId );
		$signMsgVal = $this->appendParam ( $signMsgVal, "productDesc", urlencode ( $productDesc ) );
		$signMsgVal = $this->appendParam ( $signMsgVal, "ext1", urlencode ( $ext1 ) );
		$signMsgVal = $this->appendParam ( $signMsgVal, "ext2", urlencode ( $ext2 ) );
		$signMsgVal = $this->appendParam ( $signMsgVal, "bossType", $bossType );
		$signMsgVal = $this->appendParam ( $signMsgVal, "key", $key );
		$signMsg = strtoupper ( md5 ( $signMsgVal ) ); //安全校验域
		$msg = "<input type='hidden' name='inputCharset' value='$inputCharset'>
			<input type='hidden' name='bgUrl' value='$bgUrl'>
			<input type='hidden' name='version' value='$version'>
			<input type='hidden' name='language' value='$language'>
			<input type='hidden' name='signType' value='$signType'>			
			<input type='hidden' name='merchantAcctId' value='$merchantAcctId'>
			<input type='hidden' name='payerName' value='$payerName'>
			<input type='hidden' name='payerContactType' value='$payerContactType'>
			<input type='hidden' name='payerContact' value='$payerContact'>
			<input type='hidden' name='orderId' value='$orderId'>
			<input type='hidden' name='orderAmount' value='$orderAmount'>
    <input type='hidden' name='payType' value='$payType'>
    <input type='hidden' name='cardNumber' value='$cardNumber'>
    <input type='hidden' name='cardPwd' value='$cardPwd'>
			<input type='hidden' name='fullAmountFlag' value='$fullAmountFlag'>
			<input type='hidden' name='orderTime' value='$orderTime'>
			<input type='hidden' name='productName' value='$productName'>
			<input type='hidden' name='productNum' value='$productNum'>
			<input type='hidden' name='productId' value='$productId'>
			<input type='hidden' name='productDesc' value='$productDesc'>
			<input type='hidden' name='ext1' value='$ext1'>
			<input type='hidden' name='ext2' value=' $ext2'>
	        <input type='hidden' name='bossType' value='$bossType'>
			<input type='hidden' name='signMsg' value='$signMsg'>	
			<input type='hidden' value='$signMsgVal'/>
		</form>		<script>document.getElementById('submitform').submit();</script>";
		return $msg;
	}
	
	//功能函数。将变量值不为空的参数组成字符串   (快钱使用)
	private function appendParam($returnStr, $paramId, $paramValue) {
		
		if ($returnStr != "") {
			
			if ($paramValue != "") {
				
				$returnStr .= "&" . $paramId . "=" . $paramValue;
			}
		
		} else {
			
			If ($paramValue != "") {
				$returnStr = $paramId . "=" . $paramValue;
			}
		}
		
		return $returnStr;
	}
	
	/**
	 * 快钱充值卡支付
	 */
	public function payment_result_99bill_of_card() {
		
		$merchantAcctId = trim ( $_REQUEST ['merchantAcctId'] );
		
		$key = "";
		
		$version = trim ( $_REQUEST ['version'] );
		
		$language = trim ( $_REQUEST ['language'] );
		
		$payType = trim ( $_REQUEST ['payType'] );
		
		$cardNumber = trim ( $_REQUEST ['cardNumber'] );
		
		$cardPwd = trim ( $_REQUEST ['cardPwd'] );
		
		//获取商户订单号
		$orderId = trim ( $_REQUEST ['orderId'] );
		
		$orderAmount = trim ( $_REQUEST ['orderAmount'] );
		
		$dealId = trim ( $_REQUEST ['dealId'] );
		
		$orderTime = trim ( $_REQUEST ['orderTime'] );
		
		$ext1 = trim ( $_REQUEST ['ext1'] );
		
		$ext2 = trim ( $_REQUEST ['ext2'] );
		
		$payAmount = trim ( $_REQUEST ['payAmount'] );
		
		$billOrderTime = trim ( $_REQUEST ['billOrderTime'] );
		
		$payResult = trim ( $_REQUEST ['payResult'] );
		
		$signType = trim ( $_REQUEST ['signType'] );
		
		$bossType = trim ( $_REQUEST ['bossType'] );
		$receiveBossType = trim ( $_REQUEST ['receiveBossType'] );
		
		$receiverAcctId = trim ( $_REQUEST ['receiverAcctId'] );
		
		$signMsg = trim ( $_REQUEST ['signMsg'] );
		
		//生成加密串。必须保持如下顺序。
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "merchantAcctId", $merchantAcctId );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "version", $version );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "language", $language );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "payType", $payType );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "cardNumber", $cardNumber );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "cardPwd", $cardPwd );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "orderId", $orderId );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "orderAmount", $orderAmount );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "dealId", $dealId );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "orderTime", $orderTime );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "ext1", $ext1 );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "ext2", $ext2 );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "payAmount", $payAmount );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "billOrderTime", $billOrderTime );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "payResult", $payResult );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "signType", $signType );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "bossType", $bossType );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "receiveBossType", $receiveBossType );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "receiverAcctId", $receiverAcctId );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "key", $key );
		$merchantSignMsg = md5 ( $merchantSignMsgVal );
		
		//初始化结果及地址
		$rtnOk = 0;
		$rtnUrl = "";
		
		//商家进行数据处理，并跳转会商家显示支付结果的页面
		///首先进行签名字符串验证
		$urls = $this->query ( "select return_url from payment_platform_new limit 1" );
		if (strtoupper ( $signMsg ) == strtoupper ( $merchantSignMsg )) {
			switch ($payResult) {
				case "10" :
					$rtnOk = 1;
					$rtnUrl = $urls [0] [0] ['return_url'] . "?msg=success";
					//$rtnUrl = "http://localhost/selfhelps/result?msg=success";
					$this->card_refill ( $orderAmount / 100, 1 );
					break;
				
				default :
					$rtnOk = 1;
					$rtnUrl = $urls [0] [0] ['return_url'] . "?msg=false";
					//$rtnUrl = "http://localhost/selfhelps/result?msg=false";
					$this->refill_fail ( $orderAmount / 100, 1, $cardNumber, $cardPwd, '未知原因' );
					break;
			}
		
		} else {
			
			$rtnOk = 1;
			$rtnUrl = $urls [0] [0] ['return_url'] . "?msg=false";
		
		}
		return array ($rtnOk, $rtnUrl );
	
	}
	/**
	 * 快钱网银支付后台处理
	 */
	public function payment_result_99bill_of_bank() {
		$merchantAcctId = trim ( $_REQUEST ['merchantAcctId'] );
		$key = "";
		$version = trim ( $_REQUEST ['version'] );
		$language = trim ( $_REQUEST ['language'] );
		$signType = trim ( $_REQUEST ['signType'] );
		
		$payType = trim ( $_REQUEST ['payType'] );
		
		$bankId = trim ( $_REQUEST ['bankId'] );
		
		$orderId = trim ( $_REQUEST ['orderId'] );
		
		$orderTime = trim ( $_REQUEST ['orderTime'] );
		
		$orderAmount = trim ( $_REQUEST ['orderAmount'] );
		
		$dealId = trim ( $_REQUEST ['dealId'] );
		
		$bankDealId = trim ( $_REQUEST ['bankDealId'] );
		
		$dealTime = trim ( $_REQUEST ['dealTime'] );
		
		$payAmount = trim ( $_REQUEST ['payAmount'] );
		
		$fee = trim ( $_REQUEST ['fee'] );
		
		$ext1 = trim ( $_REQUEST ['ext1'] );
		
		$ext2 = trim ( $_REQUEST ['ext2'] );
		
		$payResult = trim ( $_REQUEST ['payResult'] );
		
		$errCode = trim ( $_REQUEST ['errCode'] );
		
		$signMsg = trim ( $_REQUEST ['signMsg'] );
		
		$keys = $this->query ( "select kq_account_key from payment_trace where trace_type = 'BANK' and status = true" );
		$key = $keys [0] [0] ['kq_account_key'];
		
		$signMsg = trim ( $_REQUEST ['signMsg'] );
		$merchantSignMsgVal = "";
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "merchantAcctId", $merchantAcctId );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "version", $version );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "language", $language );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "signType", $signType );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "payType", $payType );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "bankId", $bankId );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "orderId", $orderId );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "orderTime", $orderTime );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "orderAmount", $orderAmount );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "dealId", $dealId );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "bankDealId", $bankDealId );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "dealTime", $dealTime );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "payAmount", $payAmount );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "fee", $fee );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "ext1", $ext1 );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "ext2", $ext2 );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "payResult", $payResult );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "errCode", $errCode );
		$merchantSignMsgVal = $this->appendParam ( $merchantSignMsgVal, "key", $key );
		$merchantSignMsg = md5 ( $merchantSignMsgVal );
		
		$rtnOk = 0;
		$rtnUrl = "";
		$urls = $this->query ( "select return_url from payment_platform_new limit 1" );
		if (strtoupper ( $signMsg ) == strtoupper ( $merchantSignMsg )) {
			switch ($payResult) {
				case "10" :
					$rtnOk = 1;
					$rtnUrl = $urls [0] [0] ['return_url'] . "?msg=success";
					//$rtnUrl = "http://localhost/selfhelps/result?msg=success";
					$this->card_refill ( $orderAmount / 100, 1, '', '', '' );
					break;
				
				default :
					$rtnOk = 1;
					$rtnUrl = $urls [0] [0] ['return_url'] . "?msg=false";
					//$rtnUrl = "http://localhost/selfhelps/result?msg=false";
					$this->refill_fail ( $orderAmount / 100, 1, '', '', '' );
					break;
			}
		
		} else {
			
			$rtnOk = 1;
			$rtnUrl = $rtnUrl = $urls [0] [0] ['return_url'] . "?msg=false";
			//$rtnUrl = "http://localhost/selfhelps/result?msg=false";
			$this->refill_fail ( $orderAmount / 100, 1, '', '', '' );
		}
		return array ($rtnOk, $rtnUrl );
	}
	
	/**
	 * 充值失败
	 * @param unknown_type $amount
	 * @param unknown_type $method
	 */
	public function refill_fail($amount, $method, $card, $pass, $cause) {
		$card_id = $_SESSION ['card_id'];
		$resids = $this->query ( "select reseller_id from card where card_id = $card_id" );
		$paymentmethod = $_SESSION ['paymentmethod'];
		$this->query ( "insert into account_payment(cause,card_no,card_pass,payment_time,amount,result,account_id,reseller_id,payment_method,platform_trace)
												 values('$cause',$card','$pass',current_timestamp(0),$amount,true,$card_id,{$resids[0][0]['reseller_id']},$method,'$paymentmethod')" );
	}
	
	/**
	 * 短信扣费
	 */
	public function msg_cost($msg_type, $nums, $content, $account_id) {
		//短信类型
		$msg_field = 'msg_one_to_one';
		if ($msg_type == 1) {
			$msg_field = 'msg_one_to_many';
		} else if ($msg_type == 2) {
			$msg_field = 'msg_find_pwd';
		} else if ($msg_type == 3) {
			$msg_field = 'msg_view_balance';
		} else if ($msg_type == 4) {
			$msg_field = 'msg_acc_reg';
		} else if ($msg_type == 5) {
			$msg_field = 'msg_one_to_one';
		}
		
		$ns = explode ( ',', $nums ); //短信条数
		

		//去掉内容的单引号和双引号
		$content = str_ireplace ( '\'', '', $content );
		$content = str_ireplace ( '\"', '', $content );
		
		//对应短信类型的短信费率id
		$msg_charges_id = $this->query ( "select $msg_field as charge_id from card where card_id = $account_id" );
		$qs_count = 0;
		
		$this->begin ();
		if (count ( $msg_charges_id ) > 0) {
			//短信费率
			$msg_charges = $this->query ( "select msg_rate,free_count from sms_charges where msg_charges_id = {$msg_charges_id[0][0]['charge_id']}" );
		
			//现在余额
			$old_balance = $this->query ( "select balance,gift_amount_balance from account_balance where account_id = '$account_id' order by account_balance_id desc limit 1" );
		
			//今天凌晨时间,计算今天已经发送该类型短信的条数
			$now_time = date ( 'Y-m-d' ) . " 00:00:00";
			$sent = $this->query ( "select msg_record_id from sms_record where type=$msg_type and card_id =$account_id and time >= '$now_time'" );
					pr(count ( $sent ));
			//计算发送该短信需要的扣费金额
		if ($msg_charges [0] [0] ['free_count'] > (count ( $sent ) + (count ( $ns )))) {
				$cost = 0;
			} else {
				$cost = $msg_charges [0] [0] ['msg_rate'] * count ( $ns );
			}
			//更新balance
			$now_balance = $old_balance [0] [0] ['balance'] - $cost;
				$old_gift_amount_balance=$old_balance[0][0]['gift_amount_balance'];
			$qs = $this->query ( "insert into account_balance (account_id,balance,create_time,cost_type,cost,gift_amount_balance)
												values ('$account_id','$now_balance',current_timestamp(0),2,'$cost','$old_gift_amount_balance')" );
			$qs_count += count ( $qs );
			
			//将短信记录存到数据库
			$reseller_id = $this->query ( "select reseller_id from card where card_id = $account_id" );
			$send_content = $this->replaceMsg($content);
			for($i = 0; $i < count ( $ns ); $i ++) {
				$qs = $this->query ( "insert into sms_record(type,reseller_id,time,receive_code,send_content,
														cost,status,sms_content,card_id)
													values($msg_type,{$reseller_id[0][0]['reseller_id']},current_timestamp(0),'{$ns[$i]}',
													'$content',{$msg_charges[0][0]['msg_rate']},1,'$send_content',$account_id)" );
				$qs_count += count ( $qs );
			}
			
			if ($qs_count == 0) {
				$this->commit ();
				return $send_content;
			} else {
				$this->rollback ();
				return false;
			}
		}
	}
	
	/**
	 * 替换短信特殊字符
	 * @param 原始信息 $msg
	 */
	public function replaceMsg($msg){
		$limits = $this->query("select limit_char,replace_char from msg_limit");
		for ($i=0;$i<count($limits);$i++) {
			 $patterns[$i]="/{$limits[$i][0]['limit_char']}/";
		   $replacements[$i] = "/{$limits[$i][0]['replace_char']}/";
		}
		
		return preg_replace($patterns, $replacements, $msg);
	}
	
	/**
	 * 连接易宝支付平台
	 * @param unknown_type $account_id
	 * @param unknown_type $account_key
	 * @param unknown_type $amt
	 * @param unknown_type $bgurl
	 * @param unknown_type $cardtype
	 */
	public function yeepay_interface($account_id, $account_key, $amt, $bgurl, $cardtype, $cardno, $cardpass) {
		#	商家设置用户购买商品的支付信息.
		##易宝支付平台统一使用GBK/GB2312编码方式,参数如用到中文，请注意转码
		

		#	商户订单号,选填.
		##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易宝支付会自动生成随机的商户订单号.
		$p2_Order = "";
		
		#	支付金额,必填.
		##单位:元，精确到分.
		$p3_Amt = $amt;
		
		#	交易币种,固定值"CNY".
		$p4_Cur = "CNY";
		
		#	商品名称
		##用于支付时显示在易宝支付网关左侧的订单产品信息.
		$p5_Pid = "";
		
		#	商品种类
		$p6_Pcat = "";
		
		#	商品描述
		$p7_Pdesc = "";
		
		#	商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
		$p8_Url = $bgurl;
		
		#	商户扩展信息
		##商户可以任意填写1K 的字符串,支付成功时将原样返回.												
		$pa_MP = "";
		
		#	银行编码
		##默认为""，到易宝支付网关.若不需显示易宝支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.			
		$pd_FrpId = $cardtype;
		
		#	应答机制
		##为"1": 需要应答机制;为"0": 不需要应答机制.
		$pr_NeedResponse = "1";
		
		#	产品通用接口正式请求地址
		#$reqURL_onLine = "https://www.yeepay.com/app-merchant-proxy/node";
		#	产品通用接口测试请求地址
		#$reqURL_onLine = "http://tech.yeepay.com:8080/robot/debug.action";
		

		# 业务类型
		# 支付请求，固定值"Buy" .	
		$p0_Cmd = "Buy";
		
		#	送货地址
		# 为"1": 需要用户将送货地址留在易宝支付系统;为"0": 不需要，默认为 "0".
		$p9_SAF = "0";
		
		$p1_MerId = $account_id;
		$key = $account_key;
		
		#调用签名函数生成签名串
		$hmac = $this->getReqHmacString ( $p2_Order, $p3_Amt, $p4_Cur, $p5_Pid, $p6_Pcat, $p7_Pdesc, $p8_Url, $pa_MP, $pd_FrpId, $pr_NeedResponse, $p1_MerId, $key );
		
		$msg = "<input type='hidden' name='p0_Cmd'					value='$p0_Cmd'>
<input type='hidden' name='p1_MerId'				value='$p1_MerId'>
<input type='hidden' name='p2_Order'				value='$p2_Order'>
<input type='hidden' name='p3_Amt'					value='$p3_Amt'>
<input type='hidden' name='p4_Cur'					value='$p4_Cur'>
<input type='hidden' name='p5_Pid'					value='$p5_Pid'>
<input type='hidden' name='p6_Pcat'					value='$p6_Pcat'>
<input type='hidden' name='p7_Pdesc'				value='$p7_Pdesc'>
<input type='hidden' name='p8_Url'					value='$p8_Url'>
<input type='hidden' name='p9_SAF'					value='$p9_SAF'>
<input type='hidden' name='pa_MP'						value='$pa_MP'>
<input type='hidden' name='pd_FrpId'				value='$pd_FrpId'>
<input type='hidden' name='pr_NeedResponse'	value='$pr_NeedResponse'>
<input type='hidden' name='hmac'						value='$hmac'>
<script>document.getElementById('submitform').submit();</script>
</form>";
		return $msg;
	}
	
	#签名函数生成签名串
	private function getReqHmacString($p2_Order, $p3_Amt, $p4_Cur, $p5_Pid, $p6_Pcat, $p7_Pdesc, $p8_Url, $pa_MP, $pd_FrpId, $pr_NeedResponse, $p1_MerId, $merchantKey) {
		//global $p0_Cmd;
		//global $p9_SAF;
		

		#进行签名处理，一定按照文档中标明的签名顺序进行
		$sbOld = "";
		#加入业务类型
		$sbOld = $sbOld . "Buy";
		#加入商户编号
		$sbOld = $sbOld . $p1_MerId;
		#加入商户订单号
		$sbOld = $sbOld . $p2_Order;
		#加入支付金额
		$sbOld = $sbOld . $p3_Amt;
		#加入交易币种
		$sbOld = $sbOld . $p4_Cur;
		#加入商品名称
		$sbOld = $sbOld . $p5_Pid;
		#加入商品分类
		$sbOld = $sbOld . $p6_Pcat;
		#加入商品描述
		$sbOld = $sbOld . $p7_Pdesc;
		#加入商户接收支付成功数据的地址
		$sbOld = $sbOld . $p8_Url;
		#加入送货地址标识
		$sbOld = $sbOld . "0";
		#加入商户扩展信息
		$sbOld = $sbOld . $pa_MP;
		#加入银行编码
		$sbOld = $sbOld . $pd_FrpId;
		#加入是否需要应答机制
		$sbOld = $sbOld . $pr_NeedResponse;
		
		return $this->HmacMd5 ( $sbOld, $merchantKey );
	
	}
	
	private function getCallbackHmacString($r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType, $p1_MerId, $merchantKey) {
		
		#取得加密前的字符串
		$sbOld = "";
		#加入商家ID
		$sbOld = $sbOld . $p1_MerId;
		#加入消息类型
		$sbOld = $sbOld . $r0_Cmd;
		#加入业务返回码
		$sbOld = $sbOld . $r1_Code;
		#加入交易ID
		$sbOld = $sbOld . $r2_TrxId;
		#加入交易金额
		$sbOld = $sbOld . $r3_Amt;
		#加入货币单位
		$sbOld = $sbOld . $r4_Cur;
		#加入产品Id
		$sbOld = $sbOld . $r5_Pid;
		#加入订单ID
		$sbOld = $sbOld . $r6_Order;
		#加入用户ID
		$sbOld = $sbOld . $r7_Uid;
		#加入商家扩展信息
		$sbOld = $sbOld . $r8_MP;
		#加入交易结果返回类型
		$sbOld = $sbOld . $r9_BType;
		
		return $this->HmacMd5 ( $sbOld, $merchantKey );
	
	}
	
	#	取得返回串中的所有参数
	private function getCallBackValue(&$r0_Cmd, &$r1_Code, &$r2_TrxId, &$r3_Amt, &$r4_Cur, &$r5_Pid, &$r6_Order, &$r7_Uid, &$r8_MP, &$r9_BType, &$hmac) {
		$r0_Cmd = $_REQUEST ['r0_Cmd'];
		$r1_Code = $_REQUEST ['r1_Code'];
		$r2_TrxId = $_REQUEST ['r2_TrxId'];
		$r3_Amt = $_REQUEST ['r3_Amt'];
		$r4_Cur = $_REQUEST ['r4_Cur'];
		$r5_Pid = $_REQUEST ['r5_Pid'];
		$r6_Order = $_REQUEST ['r6_Order'];
		$r7_Uid = $_REQUEST ['r7_Uid'];
		$r8_MP = $_REQUEST ['r8_MP'];
		$r9_BType = $_REQUEST ['r9_BType'];
		$hmac = $_REQUEST ['hmac'];
		
		return null;
	}
	
	private function CheckHmac($r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType, $hmac) {
		if ($hmac == $this->getCallbackHmacString ( $r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType ))
			return true;
		else
			return false;
	}
	
	private function HmacMd5($data, $key) {
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing(NOTE: Hacked means written)
		

		//需要配置环境支持iconv，否则中文参数不能正常处理
		$key = iconv ( "GB2312", "UTF-8", $key );
		$data = iconv ( "GB2312", "UTF-8", $data );
		
		$b = 64; // byte length for md5
		if (strlen ( $key ) > $b) {
			$key = pack ( "H*", md5 ( $key ) );
		}
		$key = str_pad ( $key, $b, chr ( 0x00 ) );
		$ipad = str_pad ( '', $b, chr ( 0x36 ) );
		$opad = str_pad ( '', $b, chr ( 0x5c ) );
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;
		
		return md5 ( $k_opad . pack ( "H*", md5 ( $k_ipad . $data ) ) );
	}
	
	public function yeepay_result() {
		$r0_Cmd = $_REQUEST ['r0_Cmd'];
		$r1_Code = $_REQUEST ['r1_Code'];
		$r2_TrxId = $_REQUEST ['r2_TrxId'];
		$r3_Amt = $_REQUEST ['r3_Amt'];
		$r4_Cur = $_REQUEST ['r4_Cur'];
		$r5_Pid = $_REQUEST ['r5_Pid'];
		$r6_Order = $_REQUEST ['r6_Order'];
		$r7_Uid = $_REQUEST ['r7_Uid'];
		$r8_MP = $_REQUEST ['r8_MP'];
		$r9_BType = $_REQUEST ['r9_BType'];
		$hmac = $_REQUEST ['hmac'];
		$bRet = true;
		//$bRet = $this->CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
		

		#	校验码正确.
		if ($bRet) {
			if ($r1_Code == "1") {
				
				#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
				#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，防止对同一条交易重复发货的情况发生.      	  	
				

				if ($r9_BType == "1") {
					echo "交易成功";
					echo "<br />在线支付页面返回";
					return true;
				} elseif ($r9_BType == "2") {
					#如果需要应答机制则必须回写流,以success开头,大小写不敏感.
					echo "success";
					$amt = $_REQUEST ['r3_Amt'];
					return true;
				} elseif ($r9_BType == "3") {
					echo "电话支付通知页面返回";
				}
			}
			return false;
		
		} else {
			echo "交易信息被篡改";
			return false;
		}
	}
	
	/**
	 *检查余额
	 */
	public function check_balance($msg_type, $nums, $content, $account_id) {
		//短信类型
		$msg_field = 'msg_one_to_one';
		if ($msg_type == 1) {
			$msg_field = 'msg_one_to_many';
		} else if ($msg_type == 2) {
			$msg_field = 'msg_find_pwd';
		} else if ($msg_type == 3) {
			$msg_field = 'msg_view_balance';
		} else if ($msg_type == 4) {
			$msg_field = 'msg_acc_reg';
		} else if ($msg_type == 5) {
			$msg_field = 'msg_one_to_one';
		}
		$ns = explode ( ',', $nums ); //短信条数
		//pr($ns);

		//对应短信类型的短信费率id
		$msg_charges_id = $this->query ( "select $msg_field as charge_id from card where card_id = $account_id" );
		
		//检查余额是否足够发送该短信
		if (count ( $msg_charges_id ) > 0) {
			$msg_charges = $this->query ( "select msg_rate,free_count from sms_charges where msg_charges_id = {$msg_charges_id[0][0]['charge_id']}" );
			$old_balance = $this->query ( "select balance from account_balance where account_id = '$account_id' order by account_balance_id  desc limit 1" );
			$cost = $msg_charges [0] [0] ['msg_rate'] * count ( $ns );
		
			if (empty ( $old_balance )) {
				//pr($old_balance);
				return false;
			}
			if ($cost > $old_balance [0] [0] ['balance']) {
			//	pr($old_balance [0] [0] ['balance']);
				return false;
			} else {
			//	pr($cost);pr($old_balance [0] [0] ['balance']);
				return true;
			}
		}
		return false;
	}
	
	
	/**
	 * 计算帐户的推荐策略
	 */
	public function calc_recommend($account_id,$type,$amt=null){
		$rec_sty = $this->query("select * from recommend_strategy where start_time<=current_timestamp(0) and  (end_time >= current_timestamp(0) or end_time is null) and recommend_strategy_id = (select recommend_strategy_id from card where card_id = $account_id)");
		if (count($rec_sty) > 0){
			if ($type != $rec_sty[0][0]['gift_type']){exit();};
			
			//将要赠送的基础金
			$basic_amount = $rec_sty[0][0]['basic_amount'];
			if ($rec_sty[0][0]['by_first_payment'] == true) {
				$basic_amount = number_format($amt*$rec_sty[0][0]['basic_amount']/100,3);
			}
			
			//将要送的赠送金
			$gift_amount = $rec_sty[0][0]['gift_amount'];
			if (!empty($basic_amount) || !empty($gift_amount)){
				$now_balance = $this->query("select balance,gift_amount_balance from account_balance where account_id = '$account_id' order by account_balance_id desc limit 1");
				$new_balance = empty($now_balance)?$basic_amount:$now_balance[0][0]['balance']+$basic_amount;
				$new_gift = empty($now_balance)?$gift_amount:$now_balance[0][0]['gift_amount_balance']+$gift_amount;
				$this->query("insert into account_balance (account_id,balance,cost_type,create_time,gift_amount_balance)
													values ('$account_id','$new_balance','3',current_timestamp(0),'$new_gift')");
			}
			
			//将要赠送的积分
			$gift_point = $rec_sty[0][0]['gift_point'];
			if (!empty($gift_point)) {
				$now_points = $this->query("select point_balance from account_point where account_id = '$account_id' order by account_point_id desc limit 1");
				$new_points = $gift_point;
				if (!empty($now_points)){
					$new_points += $now_points[0][0]['point_balance'];
				}
				
				$this->query("insert into account_point (account_id,point_balance) values('$account_id','$new_points')");
			}
		}
	}
        
        public function all_invoice($client_id, $type) {
            if($type == 'incoming') {
                $sql = "SELECT  invoice_id,invoice_id || ' (Due: ' || total_amount || ' USD)' AS invoice_name FROM  invoice WHERE client_id = {$client_id} AND paid = FALSE  AND state = 9 AND type = 0 ORDER BY invoice_time ASC;";
            } else {
                $sql = "SELECT  invoice_id,invoice_id || ' (Due: ' || total_amount || ' USD)' AS invoice_name FROM  invoice WHERE client_id = {$client_id} AND paid = FALSE  AND state = 0 AND type = 3 ORDER BY invoice_time ASC;";
            }
            $result = $this->query($sql);
            return $result;
        }
        
        public function get_unpaid_invoices($client_id, $type)
        {
            if($type == 'incoming') {
                $sql = "SELECT  * FROM  invoice WHERE client_id = {$client_id} AND paid = FALSE  AND state in (0, 9) and total_amount > 0 AND type = 0 ORDER BY invoice_time ASC;";
            } else {
                $sql = "SELECT  * FROM  invoice WHERE client_id = {$client_id} AND paid = FALSE  AND state = 0 AND type = 3 and total_amount > 0 ORDER BY invoice_time ASC;";
            }
            $result = $this->query($sql);
            return $result;
            
        }
        
        /*
        public function get_unpaid_invoice($client_id, $type)
        {
            $sql =<<<EOT
SELECT  
*
FROM  invoice 
WHERE 
client_id = {$client_id} AND paid = false and pay_amount < total_amount AND state = 9 AND type = {$type} 
ORDER BY invoice_time ASC limit 1
EOT;

            $result = $this->query($sql);
            if (!empty($result))
                return $result[0][0];
            else
                return false;
        }
        */
        
        public function update_unpaid_invoice($invoice_number, $should_paid_amount, $paid)
        {
            $sql = "update invoice set pay_amount = pay_amount + {$should_paid_amount}, paid = {$paid} where invoice_number = '{$invoice_number}'";
            
            $this->query($sql);
        }
        
        public function update_carrier_balance($amount, $client_id, $control, $balance_type='ingress_balance')
        {
            if ($balance_type == 'ingress_balance') {
                $action = $control == '+' ? 2 : 3;
            } else {
                $action = $control == '+' ? 4 : 5;
            }

            $result = $this->clientBalanceOperation($client_id, $amount, $action, true);

            return $result[0][0]['balance'];
        }
        
        
        public function create_payment_record($client_id, $type, $should_paid_amount, $current_balance, $invoice_number, $receiving_time, $note)
        {
            $sql =<<<EOT
INSERT INTO client_payment(client_id, payment_type, amount, current_balance,invoice_number, payment_time, result, receiving_time, description)
VALUES ({$client_id}, {$type},{$should_paid_amount},{$current_balance},'{$invoice_number}', 'now', TRUE, '{$receiving_time}', '{$note}')            
EOT;
            $this->query($sql);
        }
}
